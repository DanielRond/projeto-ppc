<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Rota pública: Login
Route::post('/login', [AuthController::class, 'login']);

// Rotas protegidas: Precisa do Token Bearer
Route::middleware('auth:sanctum')->group(function () {

    // Rota para recuperar dados do usuário logado
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // O apiResource já cria automaticamente:
    // GET    /api/courses        -> index()
    // POST   /api/courses        -> store()  <-- O formulário usará este
    // GET    /api/courses/{id}   -> show()
    // PUT    /api/courses/{id}   -> update()
    // DELETE /api/courses/{id}   -> destroy()
    Route::apiResource('courses', CourseController::class);

});
