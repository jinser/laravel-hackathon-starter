<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\PricingPlan;

class LivePricingPlanApiController extends Controller
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
     * Get all live plans from Stripe
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
        $response = \Stripe\Plan::all();
        return response()->json($response);
    }
    
    /**
     * Enable plan to go live in Stripe
     * 
     * Request attribute:
     * contains id of the plan in database
     * 
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
        $this->_setStripeKey();
        //get plan id from database
        $dbPlan = PricingPlan::findOrFail($request->id);
        
        //create plan in stripe with the same id
        $response = \Stripe\Plan::create(array(
          "amount" => $dbPlan->price*100,
          "interval" => $dbPlan->billing_frequency_period,
          "interval_count" => $dbPlan->billing_frequency_length,
          "name" => $dbPlan->name,
          "currency" => $dbPlan->currency,
          "id" => $dbPlan->id)
        );
        return response()->json($response);
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
            $response = \Stripe\Plan::retrieve($id);
            return response()->json($response);
        }
        catch(\Stripe\Error $e) {
          $body = $e->getJsonBody();
          $err  = $body['error'];
        }
        catch(\Stripe\Error\Card $e) {
          //Card decline
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
          // Authentication with Stripe's API failed, check if API keys changed recently
          $body = $e->getJsonBody();
          $err  = $body['error'];
        } catch (\Stripe\Error\ApiConnection $e) {
          // Network communication with Stripe failed
          $body = $e->getJsonBody();
          $err  = $body['error'];
        } catch (\Stripe\Error\Base $e) {
          //Generic Stripe Error
          $body = $e->getJsonBody();
          $err  = $body['error'];
        } catch (Exception $e) {
          // Non-Stripe related error
          $body = $e->getJsonBody();
          $err  = $body['error'];
        }
        $response = [
            'error' =>  $err
        ];
        return response()->json($response);
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
        $err = "general error has occurred.";
        
        try {
            $response = \Stripe\Plan::retrieve($id);
            $response->name=$request->name;
            $response->save();
            return response()->json($response);
        }
       catch(\Stripe\Error $e) {
          $body = $e->getJsonBody();
          $err  = $body['error'];
        }
        catch(\Stripe\Error\Card $e) {
          //Card decline
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
          // Authentication with Stripe's API failed, check if API keys changed recently
          $body = $e->getJsonBody();
          $err  = $body['error'];
        } catch (\Stripe\Error\ApiConnection $e) {
          // Network communication with Stripe failed
          $body = $e->getJsonBody();
          $err  = $body['error'];
        } catch (\Stripe\Error\Base $e) {
          //Generic Stripe Error
          $body = $e->getJsonBody();
          $err  = $body['error'];
        } catch (Exception $e) {
          // Non-Stripe related error
          $body = $e->getJsonBody();
          $err  = $body['error'];
        }
        $response = [
            'error' =>  $err
        ];
        return response()->json($response);
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
        $err = "general error has occurred.";
        
        try {
            $response = \Stripe\Plan::retrieve($id);
            $response->delete();
             return response()->json($response);
        }
        catch(\Stripe\Error $e) {
          $body = $e->getJsonBody();
          $err  = $body['error'];
        }
        catch(\Stripe\Error\Card $e) {
          //Card decline
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
          // Authentication with Stripe's API failed, check if API keys changed recently
          $body = $e->getJsonBody();
          $err  = $body['error'];
        } catch (\Stripe\Error\ApiConnection $e) {
          // Network communication with Stripe failed
          $body = $e->getJsonBody();
          $err  = $body['error'];
        } catch (\Stripe\Error\Base $e) {
          //Generic Stripe Error
          $body = $e->getJsonBody();
          $err  = $body['error'];
        } catch (Exception $e) {
          // Non-Stripe related error
          $body = $e->getJsonBody();
          $err  = $body['error'];
        }
        $response = [
            'error' =>  $err
        ];
        return response()->json($response);
    }
    
     private function _setStripeKey() {
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
    }
}
