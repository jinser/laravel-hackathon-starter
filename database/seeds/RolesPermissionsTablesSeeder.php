<?php

use Illuminate\Database\Seeder;
use App\Role;
use App\Permission;
use App\User;

class RolesPermissionsTablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /**
         * create default roles
         */
        $superadmin = $this->createRole('superadmin','super admin user, not for production use');
        
        $merchant = $this->createRole('merchant','for merchant use');
        
        $customer = $this->createRole('customer','for customer use');
        
        /**
         * create permissions
         */
        $createPricingPlan = $this->createPermission('create_pricingplan',
                                                    'allow creation of pricing plan in our database, excluding option to let plan go live on stripe');
        $showPricingPlan = $this->createPermission('show_pricingplan',
                                                'allow viewing of all pricing plans');
        $createLivePricingPlan = $this->createPermission('create_livepricingplan',
                                                        'allow pricing plan to go live on Stripe');
        $create_customerAccount = $this->createPermission('create_customeraccount',
                                                        'allow creation of a new customer account');
        $create_subscription = $this->createPermission('create_subscription',
                                                    'allow creation of a new subscription for a customer');
        
        /**
         * add permissions to role
         */
        $superadmin->attachPermission($createPricingPlan);
        $superadmin->attachPermission($createLivePricingPlan);
        $superadmin->attachPermission($showPricingPlan);
        $superadmin->attachPermission($create_customerAccount);
        $superadmin->attachPermission($create_subscription);
        
        $merchant->attachPermission($createPricingPlan);
        $merchant->attachPermission($createLivePricingPlan);
        $merchant->attachPermission($showPricingPlan);
        $merchant->attachPermission($create_customerAccount);
        
        $customer->attachPermission($create_customerAccount);
        $customer->attachPermission($create_subscription);
        
        /**
         * assigning roles to default users
         */
        $adminuser = User::where('fullname', '=', 'superadmin')->first();
        $adminuser->roles()->attach($superadmin->id);
        
        $merchantuser = User::where('fullname', '=' ,'merchant_fullname')->first();
        $merchantuser->roles()->attach($merchant->id);
        
        $customeruser = User::where('fullname','=', 'customer_fullname')->first();
        $customeruser->roles()->attach($customer->id);
        
    }
    
    private function createRole($name,$description) {
        $role = new Role();
        $role->name = $name;
        $role->display_name = $name;
        $role->description = $description;
        $role->save();
        
        return $role;
    }
    
    private function createPermission($name,$description) {
        $perm = new Permission();
        $perm->name = $name;
        $perm->display_name = $name;
        $perm->description = $description;
        $perm->save();
        
        return $perm;
    }
}
