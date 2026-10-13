<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'username' => 'admin',
            'email'    => 'admin@tokobuku.test',
            'password' => 'admin12345',
            'role'     => 'admin',
        ]);

        foreach (['Informatika & Komputer', 'Novel', 'Pendidikan', 'Bisnis'] as $name) {
            Category::create(['name' => $name]);
        }
    }
}