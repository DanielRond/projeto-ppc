<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Unidade Teste',
            'email' => 'unidade@teste.com',
            'role' => 'unidade',
            'password' => Hash::make('12345678'),
        ]);

        User::create([
            'name' => 'Avaliador Teste',
            'email' => 'avaliador@teste.com',
            'role' => 'avaliador',
            'password' => Hash::make('12345678'),
        ]);

        User::create([
            'name' => 'Câmara Teste',
            'email' => 'camera@teste.com',
            'role' => 'camera_ensino',
            'password' => Hash::make('12345678'),
        ]);
    }
}
