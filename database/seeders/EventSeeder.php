<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //all() retrieves all rows from the table.
        //It returns a collection of User objects, not just raw database row
        $users = User::all();

        for ($i = 0; $i < 200; $i++) {
            $user = $users->random();
            // create creates a new model and stores it in the db
            \App\Models\Event::factory()->create([
                'user_id' => $user->id
            ]);
        }
    }
}
