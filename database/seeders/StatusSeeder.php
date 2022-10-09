<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['sta_desc' => 'Created'],
            ['sta_desc' => 'Payed'],
            ['sta:sta_desc' => 'Rejected']
        ];
            
        DB::table('status')->insert($data);
    }
}
