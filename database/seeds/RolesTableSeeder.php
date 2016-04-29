<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->delete();
        
        Role::create([
            'id'                 => 1,
            'role_name'          => 'SuperAdmin',
            'role_description'   => 'SuperAdmin user for complete root access.'
        ]);
        Role::create([
            'id'                 => 2,
            'role_name'          => 'Merchant',
            'role_description'   => 'Merchant users '
        ]);
        Role::create([
            'id'                 => 3,
            'role_name'           => 'Customer',
            'role_description'   => 'Customers of Merchant, end users '
        ]);
    }
}
