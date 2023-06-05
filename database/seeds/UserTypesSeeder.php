<?php

use Illuminate\Database\Seeder;

use App\UserType;

class UserTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $types = [
            'admin' , 'moderator' , 'candidate'
        ];

        foreach ($types as $type) {
            UserType::UpdateOrCreate([
                'type' => $type
            ]);
        }
    }
}
