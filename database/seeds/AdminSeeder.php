<?php

use Illuminate\Database\Seeder;

use App\User;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::UpdateOrCreate([
            'name' => 'admin',
            'email' => 'dgcandidate@admin.com',
            'password' => bcrypt('admin'),
            'user_type_id' => 1
        ]);

    }
}
