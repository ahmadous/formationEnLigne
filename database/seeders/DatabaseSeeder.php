<?php

namespace Database\Seeders;
//use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Category;
use App\Models\Course;
use App\Models\Episode;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create categories
        $categories = [
            ['name' => 'Développement Web', 'slug' => 'developpement-web', 'description' => 'Apprenez à créer des sites et applications web'],
            ['name' => 'Développement Mobile', 'slug' => 'developpement-mobile', 'description' => 'Créez des applications pour iOS et Android'],
            ['name' => 'Design', 'slug' => 'design', 'description' => 'UI/UX Design, graphisme et création visuelle'],
            ['name' => 'Marketing', 'slug' => 'marketing', 'description' => 'Marketing digital, SEO et réseaux sociaux'],
            ['name' => 'Business', 'slug' => 'business', 'description' => 'Entrepreneuriat et gestion d\'entreprise'],
            ['name' => 'Data Science', 'slug' => 'data-science', 'description' => 'Machine Learning, IA et analyse de données'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }

        // Seed roles
        $this->call(RoleSeeder::class);
        
        // Create users with roles
        $admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
        ]);
        $admin->assignRole('admin');

        $instructor = User::factory()->create([
            'name' => 'Instructor User',
            'email' => 'instructor@example.com',
            'is_active_instructor' => true,
        ]);
        $instructor->assignRole('instructor');

        $student = User::factory()->create([
            'name' => 'Student User',
            'email' => 'student@example.com',
        ]);
        $student->assignRole('student');

        // Create regular users with student role
        $regularUsers = User::factory(8)->create();
        foreach ($regularUsers as $user) {
            $user->assignRole('student');
        }

        Course::factory(15)->create();
        Episode::factory(150)->create();
        
        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
