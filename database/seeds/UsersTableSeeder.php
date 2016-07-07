<?php
use Illuminate\Database\Seeder;
use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the seeds for Users table.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->delete();
        
        User::create(array(
            'fullname' => 'superadmin',
            'username' => 'superadmin',
            'email' => 'superadmin@test.com',
            'password' => Hash::make('welcome1')
            ));
            
        User::create(array(
            'fullname' => 'merchant_fullname',
            'username' => 'test_merchant',
            'email' => 'merchant@test.com',
            'password' => Hash::make('welcome1')
            ));
       
        User::create(array(
            'fullname' => 'customer_fullname',
            'username' => 'test_customer',
            'email' => 'customer@test.com',
            'password' => Hash::make('welcome1')
            )); 
    }
}
