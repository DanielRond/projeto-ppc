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
    /**
     * Exibe uma lista de todas as propostas de cursos.
     */
    public function index()
    {
        return response()->json(Course::with('subjects')->get());
    }

    /**
     * Armazena uma nova proposta de curso e sua grade curricular.
     */
    public function store(Request $request)
    {
        // Validação rigorosa dos dados
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'carga_horaria_total' => 'required|integer|min:1',
            'quantidade_semestres' => 'required|integer|min:1',
            'justificativa' => 'required|string',
            'impacto_social' => 'required|string',
            'disciplinas' => 'required|array|min:1',
            'disciplinas.*.nome' => 'required|string|max:255',
            'disciplinas.*.carga_horaria' => 'required|integer|min:1',
            'disciplinas.*.semestre' => 'required|integer|min:1|max:10', // Adicionado limite conforme documentação
        ]);

        try {
            return DB::transaction(function () use ($validated) {
                // Cria o curso (o estado inicial é definido pelo boot do modelo)
                $course = Course::create([
                    'nome' => $validated['nome'],
                    'carga_horaria_total' => $validated['carga_horaria_total'],
                    'quantidade_semestres' => $validated['quantidade_semestres'],
                    'justificativa' => $validated['justificativa'],
                    'impacto_social' => $validated['impacto_social'],
                ]);

                foreach ($validated['disciplinas'] as $disciplinaData) {
                    $course->subjects()->create([
                        'nome' => $disciplinaData['nome'],
                        'carga_horaria' => $disciplinaData['carga_horaria'],
                        'semestre' => $disciplinaData['semestre'],
                    ]);
                }

                return response()->json([
                    'message' => 'Proposta de curso e grade curricular submetidas com sucesso.',
                    'data' => $course->load('subjects')
                ], 201);
            });

        } catch (\Exception $e) {
            Log::error("Falha na submissão do PPC: " . $e->getMessage());
            return response()->json([
                'message' => 'Ocorreu um erro ao processar sua solicitação.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Exibe os detalhes de uma proposta específica.
     */
    public function show(Course $course)
    {
        return response()->json($course->load('subjects'));
    }

    /**
     * Atualiza o status da proposta (Fluxo de aprovação).
     */
    public function update(Request $request, Course $course)
    {
        $request->validate(['status' => 'required|string']);

        // Mapeamento das strings vindas do frontend para as classes de Estado
        $map = [
            'em-avaliacao' => UnderReview::class,
            'recomendacao-ajuste' => AdjustmentRecommended::class,
            'decisao-final' => FinalDecision::class,
        ];

        $newStatus = $map[$request->status] ?? null;

        if (!$newStatus) {
            return response()->json(['message' => 'Status informado é inválido.'], 422);
        }

        try {
            // Executa a transição validada pela classe StateConfig
            $course->status->transitionTo($newStatus);

            return response()->json([
                'message' => 'Status atualizado com sucesso.',
                'status' => $course->status
            ]);

        } catch (TransitionNotFound $e) {
            return response()->json([
                'message' => 'Esta transição de status não é permitida pelo fluxo de aprovação.'
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao processar transição: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove a proposta de curso.
     */
    public function destroy(Course $course)
    {
        $course->delete();
        return response()->json(['message' => 'Proposta removida com sucesso.']);
    }
}
