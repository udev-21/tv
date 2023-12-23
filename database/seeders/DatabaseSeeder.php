<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use MoonShine\Models\MoonshineUser;
use MoonShine\Models\MoonshineUserRole;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        if (!MoonshineUser::where('email', 'admin')->exists()) {
            MoonshineUser::create([
                'name' => 'Admin',
                'email' => 'admin',
                'moonshine_user_role_id' => MoonshineUserRole::firstOrCreate(['name'=>'Admin'])->id,
                'password' => bcrypt('nimda'),
            ]);
        }

        if (!MoonshineUser::where('email', 'qorovul')->exists()) {
            MoonshineUser::create([
                'name' => 'Qorovul',
                'email' => 'qorovul',
                'moonshine_user_role_id' => MoonshineUserRole::firstOrCreate(['name'=>'Guard'])->id,
                'password' => bcrypt('luvoroq'),
            ]);
        }
        
    }
}
