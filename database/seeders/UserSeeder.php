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

        User::factory(100)->create();
    }
}
