<?php
use Illuminate\Database\Seeder;
use App\User;
use App\Role;

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
        
        /**
         * create superadmin user
         */
        User::create(array(
            'fullname' => 'superadmin',
            'username' => 'superadmin',
            'email' => 'superadmin@test.com',
            'password' => Hash::make('welcome1')
            ));
          
        /**
         * create merchant user
         */
        User::create(array(
            'fullname' => 'merchant_fullname',
            'username' => 'test_merchant',
            'email' => 'merchant@test.com',
            'password' => Hash::make('welcome1')
            ));
       
        /**
         * create test customer user
         */
        
        
        //check for unique email addresses
        $email = "customer@test.com";
        $existingEmail = User::where("email",$email);
        if($existinEmail == null) {
            //proceed
        }
        
        //create in stripe
        $this->_setStripeKey();
        $stripe_response = \Stripe\Customer::create(array(
            'email' => 'customer@test.com'
        ));
        
        //create in database
        $response = User::create([
            'fullname' => 'customer_fullname',
            'username' => 'test_customer',
            'email' => 'customer@test.com',
            'password' => Hash::make('welcome1'),
            'stripe_id' => $stripe_response->id
        ]);
        
        
    }
    
    private function _setStripeKey() {
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
    }
}
