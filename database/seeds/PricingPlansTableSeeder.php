<?php

use Illuminate\Database\Seeder;
use App\PricingPlan;

class PricingPlansTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         DB::table('pricing_plans')->delete();
         
         PricingPlan::create([
            'name' => 'basic plan',
            'currency' => 'SGD',
            'price' => '19.90'
         ]);
    }
}
