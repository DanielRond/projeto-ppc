<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\States\UnderReview;
use App\States\AdjustmentRecommended;
use App\States\FinalDecision;
use Spatie\ModelStates\Exceptions\TransitionNotFound;

class CourseController extends Controller
{
    public function index()
    {
        return response()->json(Course::with('subjects')->get());
    }

    public function store(Request $request)
    {
        // Regra: Apenas 'unidade' pode submeter propostas
        if ($request->user()->role !== 'unidade') {
            return response()->json(['message' => 'Acesso negado. Apenas Unidades podem submeter propostas.'], 403);
        }

        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'carga_horaria_total' => 'required|integer|min:1',
            'quantidade_semestres' => 'required|integer|min:1',
            'justificativa' => 'required|string',
            'impacto_social' => 'required|string',
            'disciplinas' => 'required|array|min:1',
            'disciplinas.*.nome' => 'required|string|max:255',
            'disciplinas.*.carga_horaria' => 'required|integer|min:1',
            'disciplinas.*.semestre' => 'required|integer|min:1|max:10',
        ]);

        try {
            return DB::transaction(function () use ($validated) {
                $course = Course::create([
                    'nome' => $validated['nome'],
                    'carga_horaria_total' => $validated['carga_horaria_total'],
                    'quantidade_semestres' => $validated['quantidade_semestres'],
                    'justificativa' => $validated['justificativa'],
                    'impacto_social' => $validated['impacto_social'],
                ]);

                foreach ($validated['disciplinas'] as $disciplinaData) {
                    $course->subjects()->create($disciplinaData);
                }

                return response()->json(['message' => 'Proposta submetida com sucesso.', 'data' => $course->load('subjects')], 201);
            });
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao submeter.', 'error' => $e->getMessage()], 500);
        }
    }

    public function show(Course $course)
    {
        return response()->json($course->load('subjects'));
    }

    public function update(Request $request, Course $course)
    {
        $user = $request->user();
        $request->validate(['status' => 'required|string']);

        $map = [
            'em-avaliacao' => UnderReview::class,
            'recomendacao-ajuste' => AdjustmentRecommended::class,
            'decisao-final' => FinalDecision::class,
        ];

        $newStatus = $map[$request->status] ?? null;

        if (!$newStatus) {
            return response()->json(['message' => 'Status inválido.'], 422);
        }

        // Regras de Autorização de Status
        // 1. Avaliador pode colocar em 'recomendacao-ajuste' (enviar de volta para unidade)
        // 2. Câmara de Ensino pode colocar em 'decisao-final' (aprovar ou reprovar)

        if ($request->status === 'recomendacao-ajuste' && $user->role !== 'avaliador') {
            return response()->json(['message' => 'Apenas avaliadores podem recomendar ajustes.'], 403);
        }

        if ($request->status === 'decisao-final' && $user->role !== 'camera_ensino') {
            return response()->json(['message' => 'Apenas a Câmara de Ensino pode dar a decisão final.'], 403);
        }

        try {
            $course->status->transitionTo($newStatus);
            return response()->json(['message' => 'Status atualizado com sucesso.', 'status' => $course->status]);
        } catch (TransitionNotFound $e) {
            return response()->json(['message' => 'Transição não permitida.'], 422);
        }
    }

    public function destroy(Course $course)
    {
        // Regra: Apenas a própria 'unidade' deveria deletar, ou admin.
        // Para simplificar, deixamos como 'unidade'.
        if ($request->user()->role !== 'unidade') {
            return response()->json(['message' => 'Acesso negado.'], 403);
        }

        $course->delete();
        return response()->json(['message' => 'Proposta removida com sucesso.']);
    }
}
