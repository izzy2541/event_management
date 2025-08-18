<?php

namespace Database\Seeders;

use App\Models\Attendee;
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
        //uses a model factory to create 1000 fake users and save them in the users table
        \App\Models\User::factory(1000)->create();

        //runs the seeders
    $this->call(EventSeeder::class);
    $this->call(AttendeeSeeder::class);

    }
}
