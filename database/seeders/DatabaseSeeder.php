<?php

namespace Database\Seeders;

use App\Models\BlogPost;
use App\Models\Project;
use App\Models\Skill;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);

        Skill::insert([
            ['name' => 'Flutter', 'category' => 'Mobile Development', 'proficiency' => 90, 'icon' => 'flutter'],
            ['name' => 'Dart', 'category' => 'Mobile Development', 'proficiency' => 88, 'icon' => 'dart'],
            ['name' => 'Laravel', 'category' => 'Backend Development', 'proficiency' => 92, 'icon' => 'laravel'],
            ['name' => 'PHP', 'category' => 'Backend Development', 'proficiency' => 90, 'icon' => 'php'],
            ['name' => 'REST APIs', 'category' => 'Backend Development', 'proficiency' => 90, 'icon' => 'api'],
            ['name' => 'HTML', 'category' => 'Frontend Development', 'proficiency' => 88, 'icon' => 'html'],
            ['name' => 'CSS', 'category' => 'Frontend Development', 'proficiency' => 86, 'icon' => 'css'],
            ['name' => 'JavaScript', 'category' => 'Frontend Development', 'proficiency' => 88, 'icon' => 'javascript'],
            ['name' => 'Next.js', 'category' => 'Frontend Development', 'proficiency' => 78, 'icon' => 'nextjs'],
            ['name' => 'MySQL', 'category' => 'Database', 'proficiency' => 90, 'icon' => 'mysql'],
            ['name' => 'ESP32', 'category' => 'IoT Development', 'proficiency' => 75, 'icon' => 'esp32'],
            ['name' => 'Arduino', 'category' => 'IoT Development', 'proficiency' => 72, 'icon' => 'arduino'],
            ['name' => 'Sensors', 'category' => 'IoT Development', 'proficiency' => 70, 'icon' => 'sensor'],
            ['name' => 'Python', 'category' => 'Programming Languages', 'proficiency' => 90, 'icon' => 'python'],
            ['name' => 'JavaScript', 'category' => 'Programming Languages', 'proficiency' => 88, 'icon' => 'javascript'],
            ['name' => 'PHP', 'category' => 'Programming Languages', 'proficiency' => 90, 'icon' => 'php'],
            ['name' => 'Dart', 'category' => 'Programming Languages', 'proficiency' => 88, 'icon' => 'dart'],
        ]);

        Project::insert([
            ['title' => 'Smart Irrigation System', 'slug' => 'smart-irrigation-system', 'description' => 'A connected irrigation solution that monitors soil moisture and schedules water delivery using ESP32, Flutter mobile controls, Laravel backend, and MySQL storage.', 'technologies' => json_encode(['Flutter', 'ESP32', 'Laravel', 'MySQL']), 'category' => 'IoT & Agriculture', 'image' => '', 'github_url' => 'https://github.com', 'live_url' => '', 'featured' => true, 'created_at' => now(), 'updated_at' => now()],
            ['title' => 'Fuel Monitoring System', 'slug' => 'fuel-monitoring-system', 'description' => 'A fuel tracking platform with real-time sensor telemetry, mobile dashboards, and automated alerts for fleet managers.', 'technologies' => json_encode(['Flutter', 'Laravel', 'MySQL']), 'category' => 'Logistics', 'image' => '', 'github_url' => 'https://github.com', 'live_url' => '', 'featured' => true, 'created_at' => now(), 'updated_at' => now()],
            ['title' => 'Blood Bank Management System', 'slug' => 'blood-bank-management-system', 'description' => 'A secure inventory and donor coordination system integrating Flutter, Laravel, ESP32 donor kiosks, and MySQL database management.', 'technologies' => json_encode(['Flutter', 'Laravel', 'ESP32', 'MySQL']), 'category' => 'Healthcare', 'image' => '', 'github_url' => 'https://github.com', 'live_url' => '', 'featured' => false, 'created_at' => now(), 'updated_at' => now()],
            ['title' => 'Visual Aid for the Blind', 'slug' => 'visual-aid-for-the-blind', 'description' => 'An assistive technology prototype using ESP32 sensors and a Flutter mobile app to provide navigation support for visually impaired users.', 'technologies' => json_encode(['ESP32', 'Sensors', 'Flutter']), 'category' => 'Accessibility', 'image' => '', 'github_url' => 'https://github.com', 'live_url' => '', 'featured' => false, 'created_at' => now(), 'updated_at' => now()],
            ['title' => 'Property Management System', 'slug' => 'property-management-system', 'description' => 'A full-stack property portal with Laravel admin, tenant management, payment workflows, and optimized MySQL schemas.', 'technologies' => json_encode(['Laravel', 'MySQL']), 'category' => 'Real Estate', 'image' => '', 'github_url' => 'https://github.com', 'live_url' => '', 'featured' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);

        BlogPost::insert([
            ['title' => 'Building Secure REST APIs with Laravel', 'slug' => 'building-secure-rest-apis-with-laravel', 'content' => 'A practical guide to authentication, validation, and API versioning in Laravel applications.', 'featured_image' => '', 'category' => 'Laravel', 'status' => 'published', 'created_at' => now(), 'updated_at' => now()],
            ['title' => 'Optimizing MySQL for High-Performance Queries', 'slug' => 'optimizing-mysql-for-high-performance-queries', 'content' => 'Tips for indexing, query design, and schema optimization to keep your MySQL database responsive under load.', 'featured_image' => '', 'category' => 'MySQL', 'status' => 'published', 'created_at' => now(), 'updated_at' => now()],
            ['title' => 'Developing IoT Systems with ESP32 and Flutter', 'slug' => 'developing-iot-systems-with-esp32-and-flutter', 'content' => 'How to connect sensors to a mobile dashboard using ESP32 and Flutter for real-time monitoring.', 'featured_image' => '', 'category' => 'IoT', 'status' => 'published', 'created_at' => now(), 'updated_at' => now()],
        ]);

        Testimonial::insert([
            ['client_name' => 'Grace Mwangi', 'company' => 'AgriTech Solutions', 'designation' => 'Product Lead', 'testimonial' => 'Delivered a polished smart irrigation app that exceeded our expectations on speed and reliability.', 'image' => '', 'created_at' => now(), 'updated_at' => now()],
            ['client_name' => 'James Kimani', 'company' => 'Health Systems Ltd', 'designation' => 'Operations Manager', 'testimonial' => 'The blood bank system made our donor tracking seamless and reduced manual work significantly.', 'image' => '', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
