<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Crear el Docente (Super Usuario)
        User::create([
            'name' => 'Prof. Carlos Mendoza',
            'email' => 'docente@escuela.com',
            'password' => Hash::make('docente123'),
            'role' => 'docente',
            'is_active' => true,
        ]);

        // 2. Crear 15 Alumnos
        $students = [
            ['name' => 'Alejandro Ruiz', 'email' => 'alejandro@alumno.com'],
            ['name' => 'Beatriz Gómez', 'email' => 'beatriz@alumno.com'],
            ['name' => 'César Torres', 'email' => 'cesar@alumno.com'],
            ['name' => 'Daniela Morales', 'email' => 'daniela@alumno.com'],
            ['name' => 'Eduardo Castillo', 'email' => 'eduardo@alumno.com'],
            ['name' => 'Fernanda Gutiérrez', 'email' => 'fernanda@alumno.com'],
            ['name' => 'Gabriel Hernández', 'email' => 'gabriel@alumno.com'],
            ['name' => 'Helena Díaz', 'email' => 'helena@alumno.com'],
            ['name' => 'Ignacio Flores', 'email' => 'ignacio@alumno.com'],
            ['name' => 'Jimena Reyes', 'email' => 'jimena@alumno.com'],
            ['name' => 'Kevin Vargas', 'email' => 'kevin@alumno.com'],
            ['name' => 'Lucía Mendoza', 'email' => 'lucia@alumno.com'],
            ['name' => 'Manuel Ortiz', 'email' => 'manuel@alumno.com'],
            ['name' => 'Natalia Silva', 'email' => 'natalia@alumno.com'],
            ['name' => 'Oscar Romero', 'email' => 'oscar@alumno.com'],
        ];

        foreach ($students as $student) {
            User::create([
                'name' => $student['name'],
                'email' => $student['email'],
                'password' => Hash::make('alumno123'),
                'role' => 'alumno',
                'is_active' => true,
            ]);
        }
    }
}