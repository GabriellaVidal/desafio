<?php

namespace Database\Seeders;

use App\Models\User;
use Faker\Generator as Faker;
use Illuminate\Database\Seeder;

class UsersTableSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        $users = [
            [
                'name' => $faker->name(),
                'email' => $faker->unique()->safeEmail(),
                'cpf' => '95242962097',
                'cnpj' => null,
                'password' => bcrypt('123'), // password
                'profile_id' => 2
            ],
            [
                'name' => $faker->name(),
                'email' => $faker->unique()->safeEmail(),
                'cpf' => null,
                'cnpj' => '58438798000138',
                'password' => bcrypt('456'), // password
                'profile_id' => 1
            ],
            [
                'name' => $faker->name(),
                'email' => $faker->unique()->safeEmail(),
                'cpf' => '57611653091',
                'cnpj' => null,
                'password' => bcrypt('789'), // password
                'profile_id' => 2
            ],
        ];
        foreach ($users as $item){
            $user = User::firstOrCreate($item);

            $user->wallet()->create(['amount' => 100.00]);
        }
    }
}
