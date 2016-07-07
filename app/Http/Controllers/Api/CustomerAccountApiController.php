<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use App\Role;
use Validator;

class CustomerAccountApiController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    public function index() {
        
    }
    
    /**
     * Allow customer to create an account
     */
    public function store(Request $request) {
        $em = $request->email;
        //validate password and email first
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6'
        ]);
        
        if($validator->fails()) {
            $response = [
                'error' => $validator->errors()
            ];
            return response()->json($response);
        }
        
        //get role_id for customer
        $role_id = Role::where('role_name','Customer')->first()->id;
        
        //create in stripe
        $this->_setStripeKey();
        $stripe_response = \Stripe\Customer::create(array(
            'email' => $request->email
        ));
        
        //create in database
        $response = User::create([
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role_id' => $role_id,
            'stripe_id' => $stripe_response->id
        ]);

        return response()->json($response);
    }
    
    public function show($id) {
        $userResponse = User::findOrFail($id);
        $response = [
            'email' => $userResponse->email
        ];
        return response()->json($response);
    }
    
    public function edit($id) {
        
    }
    
    public function update(Request $request, $id) {
       
        
    }
    
    public function destroy(Request $request, $id) {
        
       
    }
    
    private function _setStripeKey() {
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
    }
}
