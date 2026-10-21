<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\TechStack;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $superadminName = env('SUPERADMIN_NAME');
        $superadminEmail = env('SUPERADMIN_EMAIL');
        $superadminPassword = env('SUPERADMIN_PASSWORD');

        if ($superadminName && $superadminEmail && $superadminPassword) {
            User::firstOrCreate(
                ['email' => $superadminEmail],
                [
                    'name' => $superadminName,
                    'password' => $superadminPassword,
                    'role' => 'superadmin',
                ],
            );
        }

        collect(['Web Application', 'Enterprise ERP', 'Mobile App', 'API System', 'Desktop Application'])->mapWithKeys(fn ($name) => [$name => Category::firstOrCreate(['slug' => str($name)->slug()], ['name' => $name])]);
        collect(['Laravel', 'MySQL', 'Bootstrap', 'Flutter', 'Django', 'React', 'TypeScript', 'PostgreSQL', 'AWS'])->mapWithKeys(fn ($name) => [$name => TechStack::firstOrCreate(['slug' => str($name)->slug()], ['name' => $name])]);

    }
}
