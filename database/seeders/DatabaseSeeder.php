<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed or update a default test user (idempotent)
        User::updateOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => Hash::make(env('TEST_USER_PASSWORD', 'password')),
                'role' => 'author',
                'email_verified_at' => now(),
            ]
        );

        // +++ Admin account (idempotent) +++
        // Credentials:
        //   Email: admin@playverse.test
        //   Password: secret-admin
        User::updateOrCreate(
            ['email' => 'admin@playverse.test'],
            [
                'name' => 'Playverse Admin',
                'password' => Hash::make('secret-admin'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );
    }
}
