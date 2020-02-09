<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $path = base_path().'/database/seeds/chinook-data.sql';
        DB::unprepared(file_get_contents($path));
        $this->command->info('Chinook database seeded!');
    }
}
