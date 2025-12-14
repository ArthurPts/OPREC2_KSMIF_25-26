<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create operator user for KSMIF OPREC
        User::firstOrCreate(
            ['email' => 'operator@ksmif.org'],
            [
                'name' => 'KSMIF Operator',
                'email' => 'operator@ksmif.org',
                'password' => Hash::make('Password321'),
                'is_operator' => true,
            ]
        );

        // Create regular test user
        User::firstOrCreate(
            ['email' => 'test@ksmif.org'],
            [
                'name' => 'Test User',
                'email' => 'test@ksmif.org',
                'password' => Hash::make('Password321'),
                'is_operator' => false,
            ]
        );

        // Call other seeders
        $this->call([
            TeamSeeder::class,
            SnippetSeeder::class,
        ]);
    }
}
