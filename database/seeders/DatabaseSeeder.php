<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use MoonShine\Models\MoonshineUser;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\User::factory(10)->create();

        MoonshineUser::create([
            'name' => 'Admin',
            'email' => 'admin',
            'password' => bcrypt('admin'),
        ]);
    }
}
