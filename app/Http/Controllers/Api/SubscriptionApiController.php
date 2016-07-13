<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use App\PricingPlan;

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
     * 
     * cancelled plans have a non-null 'canceled_at' field in $response->subscriptions->data (array)
     */
    public function index() {
       
        //get auth user and the stripe id
        $user = \Auth::user();
        $test = $user->stripe_id;
        $this->_setStripeKey();
        if($test == null) {
            $response = $this->_getErrorResponseJSON('user has no stripe id.');
             return response()->json($response);
        }
        
        $response = \Stripe\Customer::retrieve($test);
        
        $finalResponse = [
            'all_subscriptions' => $response->subscriptions->data
        ];
        
        return response()->json($finalResponse);
        
    }
    
    public function create() {
        
    }
    
    /**
     * Creates a new subscription for a User with Customer role based on 
     * pricing plan using Laravel Cashier
     * 
     * Inputs:
     * $request should contain:
     * planid
     * plan name
     * frequency
     */
    public function store(Request $request) {
        $user = \Auth::user();
       
        //check that user has a credit card already stored
        
        $plan = PricingPlan::findOrFail($request->planid);
        
        //if user is subscribed, return already subscribed, or update the plan
        if($user->subscribed($plan->name)) {
            $response = $this->_getErrorResponseJSON('is already subscribed');
            return response()->json($response);
        }
        $userStripeId = $user->stripe_id;
        $this->_setStripeKey();
        $stripeCustomer = \Stripe\Customer::retrieve($userStripeId);
        $custCards = $stripeCustomer->sources->data;
        
        //check if customer has credit card info
        if(count($custCards) == 0) {
              $response = $this->_getErrorResponseJSON('no credit card information found. Please update your credit card information.');
              return response()->json($response);
        }
        
        //check validity of credit cards
        $numOfFailedCards = 0;
        $numOfStoredCards = 0;
        
        for($i=0;$i<count($custCards);$i++) {
            $custCardsect = $custCards[$i];
            
            //object is not a credit card
            if(strcmp($custCards[$i]->object,'card') != 0) {
                $numOfStoredCards++;
            }
            
            //credit card validity check failed
            if(strcmp($custCards[$i]->cvc_check,'pass') != 0) {
                $numOfFailedCards++;
            }
        }
        if($numOfFailedCards == count($custCards) || $numOfStoredCards == count($custCards)) {
            //return all credit cards failed, need to add a new credit card
            $response = $this->_getErrorResponseJSON('invalid credit card information found. Please update your credit card information.');
            return response()->json($response);
        }
        
        //get plan details
        $pricingPlanFrequency = $plan->billing_frequency_period;
        if($pricingPlanFrequency == 'month') {
            $pricingPlanFrequency = 'monthly';
        }
        
        //check that plan is live
        $response = \Stripe\Plan::retrieve($request->planid);
        if($response == null) {
            $response = $this->_getErrorResponseJSON('plan is not live.');
             return response()->json($response);
        }
       
        //TO REMOVE SAMPLE TOKEN - create sample token if does not exist
        if($user->card_brand == null) {
            $creditCardToken = \Stripe\Token::create(array(
              "card" => array(
                "number" => "4242424242424242",
                "exp_month" => 1,
                "exp_year" => 2019,
                "cvc" => "314"
              )
            ));
            
            $user->newSubscription(
                $plan->name,
                $request->planid
            )->create($creditCardToken->id);
            return response()->json($creditCardToken);
        }
        
        //create subscription with laravel cashier
        $user->newSubscription(
            $plan->name,
            $request->planid
        )->create();
        //first param is plan name e.g. stripe sst 2
        //second param is the id of the plan in Stripe (id field, also is id of pricing_plans table )
        //creditCardToken can be null, if not null it will update the Stripe customer credit card details
        
        return response()->json($user);
    }
    
    public function show($id) {
        $user = User::findOrFail($id);
        //$user = \Auth::user();
        $this->_setStripeKey();
        $test = $user->stripe_id;
        $response = \Stripe\Customer::retrieve($test);
        
        /*
        $charge = \Stripe\Charge::create(array(
            "amount" => 1000, // amount in cents, again
            "currency" => "sgd",
            "customer" => $user->stripe_id,
            "description" => "Example charge"
            ));
        */
        
        
        return response()->json($charge);
    }
    
    /**
     * cancel subscription
     * 
     * 
     * returns a response that includes a non-null ends_at field 
     */
    public function destroy($id) {
        $user = \Auth::user();
        
        $plan = PricingPlan::findOrFail($id);
        $response = $user->subscription($plan->name)->cancel();
        return response()->json($response);
        
    }
    
    
    private function _setStripeKey() {
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
    }
    
    private function _getErrorResponseJSON($errorMsg) {
         $finalResponse = [
                    'error' => $errorMsg
                ];
            return $finalResponse;
    }
}
