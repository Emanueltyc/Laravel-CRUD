<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Carbon\Carbon;
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
        Role::insert([
            [ 'name' => 'admin' ],
            [ 'name' => 'agent' ],
        ]);

        User::factory()->create([
            'name' => 'Admin',
            'role_id' => Role::where('name', 'admin')->first()->id,
            'birth_date' => Carbon::now()->subYears(20)->toDateTimeString(),
        ]);

        User::factory(9)->create([
            'birth_date' => Carbon::now()->subYears(20)->toDateTimeString(),
        ]);
    }
}
