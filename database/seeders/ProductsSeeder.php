<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\Products;
use DB;

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        foreach (range(1, 10) as $index)  
        {
            DB::table('products')->insert([
                'product_name' => $faker->city,
                'product_price' => $faker->numberBetween($min = 10, $max = 200),
            ]);
        }
    }
}
