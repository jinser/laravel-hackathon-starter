<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\PricingPlan;

class PricingPlanController extends Controller
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
     * Show the application dashboard.
     * Get all plans from Stripe
     *
     * Stripe response attributes:
     * has_more
     * object (always list)
     * url
     * data[n] - where n is the number of plans
     *  - name
     *  - currency
     *  - amount (in cents)
     *  - created (date in int format)
     *  - currency (not in capital)
     *  - id
     *  - interval (day, month, week, year)
     *  - interval_count 
     *  - livemode (boolean)
     *  - object (always plan)
     * 
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->_setStripeKey();
        
        $pricingPlans = \Stripe\Plan::all();
        
        return view('pricingplans.index')->with('pricingPlans',$pricingPlans->data);
    }
    
    public function create() {
        
    }
    
    /**
     * Stripe request attributes:
     * interval, possible ENUMs: day, week, month,year
     * amount (in cents)
     * 
     * Attributes to be used in the invoice:
     * name
     * statement_descriptor 22 chars (displayed in customer credit card statement)
     * 
     * Stripe response attributes:
     * id
     * object (always is plan)
     * amount (in cents)
     * created
     * currency
     * interval
     * interval_count
     * livemode
     * metadata
     * name
     * statement_descriptor
     * trial_period_days
     * 
     */ 
    public function store(Request $request) {
       
        $dbResponse = PricingPlan::create([
            'name' => $request->name,
            'price' => $request->price,
            'currency' => 'SGD'
        ]);
        
    
    return redirect('pricingplans');
    }
    
    public function show($id) {
        
    }
    
    /**
     * Stripe request attribute:
     * id of the desired plan
     * 
     * Stripe response attributes:
     * id
     * object (always plan)
     * amount (in cents)
     * created (date in int)
     * currency (lowercase)
     * interval
     * interval_count
     * livemode
     * name
     *
     * throws error if invalid plan ID provided
     */
    public function edit($id) {
        $this->_setStripeKey();
        $err = "general error has occurred.";
        
        try {
            $editPlan = \Stripe\Plan::retrieve($id);
            return View('pricingplans.edit')->with('editPlan',$editPlan);
        }
        catch(\Stripe\Error $e) {
             $body = $e->getJsonBody();
          $err  = $body['error'];
        }
        catch(\Stripe\Error\Card $e) {
          // Since it's a decline, \Stripe\Error\Card will be caught
          $body = $e->getJsonBody();
          $err  = $body['error'];
        } catch (\Stripe\Error\RateLimit $e) {
          // Too many requests made to the API too quickly
          $body = $e->getJsonBody();
          $err  = $body['error'];
        } catch (\Stripe\Error\InvalidRequest $e) {
          // Invalid parameters were supplied to Stripe's API
          $body = $e->getJsonBody();
          $err  = $body['error'];
        } catch (\Stripe\Error\Authentication $e) {
          // Authentication with Stripe's API failed
          // (maybe you changed API keys recently)
          $body = $e->getJsonBody();
          $err  = $body['error'];
        } catch (\Stripe\Error\ApiConnection $e) {
          // Network communication with Stripe failed
          $body = $e->getJsonBody();
          $err  = $body['error'];
        } catch (\Stripe\Error\Base $e) {
          // Display a very generic error to the user, and maybe send
          // yourself an email
          $body = $e->getJsonBody();
          $err  = $body['error'];
        } catch (Exception $e) {
          // Something else happened, completely unrelated to Stripe
          $body = $e->getJsonBody();
          $err  = $body['error'];
        }
        
        
        return redirect('pricingplans');
        
        
    }
    
    /**
     * Only name of the plan is updatable, other plan details are not
     * 
     * Stripe request attribute:
     * name 
     * 
     * Stripe response attributes:
     * same as edit
     * 
     */
    public function update(Request $request, $id) {
        $this->_setStripeKey();
        
        try {
            $plan = \Stripe\Plan::retrieve($id);
            $plan->name=$request->name;
            $plan->save();
            
        }
        catch(\Stripe\Error $e) {
             $body = $e->getJsonBody();
          $err  = $body['error'];
        }
        catch(\Stripe\Error\Card $e) {
          // Since it's a decline, \Stripe\Error\Card will be caught
          $body = $e->getJsonBody();
          $err  = $body['error'];
        } catch (\Stripe\Error\RateLimit $e) {
          // Too many requests made to the API too quickly
          $body = $e->getJsonBody();
          $err  = $body['error'];
        } catch (\Stripe\Error\InvalidRequest $e) {
          // Invalid parameters were supplied to Stripe's API
          $body = $e->getJsonBody();
          $err  = $body['error'];
        } catch (\Stripe\Error\Authentication $e) {
          // Authentication with Stripe's API failed
          // (maybe you changed API keys recently)
          $body = $e->getJsonBody();
          $err  = $body['error'];
        } catch (\Stripe\Error\ApiConnection $e) {
          // Network communication with Stripe failed
          $body = $e->getJsonBody();
          $err  = $body['error'];
        } catch (\Stripe\Error\Base $e) {
          // Display a very generic error to the user, and maybe send
          // yourself an email
          $body = $e->getJsonBody();
          $err  = $body['error'];
        } catch (Exception $e) {
          // Something else happened, completely unrelated to Stripe
          $body = $e->getJsonBody();
          $err  = $body['error'];
        }
        
        return redirect('pricingplans');
        
    }
    
    /**
     * deleting plans does not affect current subscribers, new subscribers cannot be added
     * 
     * Stripe request attributes
     * id
     * 
     * Stripe response attributes
     * deleted (always true)
     * id 
     * 
     */
    public function destroy(Request $request, $id) {
        
         $this->_setStripeKey();
        
        try {
            $plan = \Stripe\Plan::retrieve($id);
            $plan->delete();
            
        }
        catch(\Stripe\Error $e) {
             $body = $e->getJsonBody();
          $err  = $body['error'];
        }
        catch(\Stripe\Error\Card $e) {
          // Since it's a decline, \Stripe\Error\Card will be caught
          $body = $e->getJsonBody();
          $err  = $body['error'];
        } catch (\Stripe\Error\RateLimit $e) {
          // Too many requests made to the API too quickly
          $body = $e->getJsonBody();
          $err  = $body['error'];
        } catch (\Stripe\Error\InvalidRequest $e) {
          // Invalid parameters were supplied to Stripe's API
          $body = $e->getJsonBody();
          $err  = $body['error'];
        } catch (\Stripe\Error\Authentication $e) {
          // Authentication with Stripe's API failed
          // (maybe you changed API keys recently)
          $body = $e->getJsonBody();
          $err  = $body['error'];
        } catch (\Stripe\Error\ApiConnection $e) {
          // Network communication with Stripe failed
          $body = $e->getJsonBody();
          $err  = $body['error'];
        } catch (\Stripe\Error\Base $e) {
          // Display a very generic error to the user, and maybe send
          // yourself an email
          $body = $e->getJsonBody();
          $err  = $body['error'];
        } catch (Exception $e) {
          // Something else happened, completely unrelated to Stripe
          $body = $e->getJsonBody();
          $err  = $body['error'];
        }
        
        return redirect('pricingplans');
    }
    
    private function _setStripeKey() {
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
    }
    
}
