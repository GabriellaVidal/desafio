<?php

namespace Database\Seeders;

use App\Models\Profile;
use Illuminate\Database\Seeder;

class ProfileTableSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $itens = [
            ['profile' => 'Logistas'],
            ['profile'=> 'Comuns']
        ];
        foreach ($itens as $item){
            Profile::firstOrCreate($item);
        }
    }
}
