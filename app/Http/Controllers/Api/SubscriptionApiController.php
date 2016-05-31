<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Subscriptions;

class SubscriptionApiController extends Controller
{
     /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }
    
    /**
     * returns all subscriptions belonging to a User with Customer role
     */
    public function index() {
        $subscriptions = Subscriptions::all();
        $response = [
            'subscriptions' => $subscriptions
        ];
        
        return response()->json($response);
        
    }
    
    public function create() {
        
    }
    
    /**
     * Creates a new subscription for a User with Customer role based on 
     * pricing plan using Laravel Cashier
     * 
     * Inputs:
     * User object that has the credit card token
     * 
     * $request should contain:
     * plan name
     * frequency
     */
    public function store(Request $request) {
        $user = \Auth::user();
        $pricingPlanName = $request->pricingPlanName;
        $pricingPlanFrequency = $request->pricingPlanFrequency;
        if($pricingPlanFrequency == 'month') {
            $pricingPlanFrequency = 'monthly';
        }
        $creditCardToken = 123456;
        
        $user->newSubscription(
            'plan_name',
            'monthly'
        )->create($creditCardToken);
    }
}
