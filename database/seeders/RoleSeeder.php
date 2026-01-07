<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'Admin',
                'slug' => 'admin',
                'description' => 'Super administrator with full access',
            ],
            [
                'name' => 'Instructor',
                'slug' => 'instructor',
                'description' => 'Course creator and instructor',
            ],
            [
                'name' => 'Student',
                'slug' => 'student',
                'description' => 'Regular student who can enroll in courses',
            ],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(
                ['slug' => $role['slug']],
                $role
            );
        }
    }
}

