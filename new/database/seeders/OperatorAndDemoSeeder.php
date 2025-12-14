<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Team;
use App\Models\Snippet;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OperatorAndDemoSeeder extends Seeder
{
    public function run(): void
    {
        // Operator demo
        $operator = User::firstOrCreate(
            ['email' => 'operator@ksmif.org'],
            [
                'name' => 'Operator Demo',
                'password' => Hash::make('Password321'),
                'email_verified_at' => now(),
                'is_operator' => true,
            ]
        );
        if (!$operator->is_operator) {
            $operator->is_operator = true;
            $operator->save();
        }

        // Teams demo
        Team::firstOrCreate(['name' => 'Alpha']);
        Team::firstOrCreate(['name' => 'Bravo']);
        Team::firstOrCreate(['name' => 'Charlie']);

        // Snippet master demo (urut 1..N)
        $snippets = [
            [1, '<h1>Halo</h1>'],
            [2, '<p>Selamat datang</p>'],
            [3, '<footer>~ Tim</footer>'],
        ];
        foreach ($snippets as [$ord, $html]) {
            Snippet::updateOrCreate(
                ['correct_order' => $ord],
                ['content' => $html]
            );
        }
    }
}
