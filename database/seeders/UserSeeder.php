<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Lazaro',
            'email' => 'rkirisho@gmail.com',
            'admission_date' => '2023-10-16',
            'password' => bcrypt('12345678'),
        ])->assignRole('admin');

        // Crear 100 usuarios con admission_date actual
        User::factory(100)->create(['admission_date' => now()]);

        $adminRole = 'admin';
        $employeeRole = 'employee';
        // Crear 5 usuarios con el rol 'admin'
        User::factory(5)->create()->each(function ($user) use ($adminRole) {
            $user->assignRole($adminRole);
        });

        // Asignar el rol 'employee' a los demás usuarios
        User::whereNotIn('id', User::role('admin')->pluck('id'))->each(function ($user) use ($employeeRole) {
            $user->assignRole($employeeRole);
        });
    }
}
