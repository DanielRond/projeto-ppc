<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\AuthController; // Importando o AuthController para o login

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Rota pública: Ninguém precisa estar logado para fazer o login
Route::post('/login', [AuthController::class, 'login']);

// Rotas protegidas: Precisa enviar o Token Bearer (Auth: Sanctum)
Route::middleware('auth:sanctum')->group(function () {

    // Rota para recuperar dados do usuário logado
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Agora o CourseController está protegido pelo middleware
    // Apenas quem estiver logado (enviar o token) conseguirá listar, criar ou editar cursos
    Route::apiResource('courses', CourseController::class);

});
