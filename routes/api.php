<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CourseController; // Importação necessária

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Rota padrão do Sanctum para recuperar o usuário autenticado
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Registra todas as rotas de API para o CourseController (index, store, show, update, destroy)
// O prefixo automático será /api/courses
Route::apiResource('courses', CourseController::class);
