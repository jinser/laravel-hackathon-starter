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

    public function show($id) {
        $user = \Auth::user();
        if($user->id != $id) {
            $response = [
                'error' => 'logged in users can only access their own accounts.'
            ];
            return response()->json($response);
        }
        $user_account = User::findOrFail($id);
        
        $this->_setStripeKey();
        try {
             //get Stripe Account details
            $stripe_account = \Stripe\Customer::retrieve($user_account->stripe_id);
            
            $response = [
                    'db_details' => $user_account,
                    'stripe_details' => $stripe_account
                ];
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
    
    public function edit($id) {
        
    }
    
    /**
     * update customer account details
     * 
     * request attributes
     * email
     * 
     */
    public function update(Request $request, $id) {
        //validate new email
        $validator = Validator::make($request->all(), [
            'email' => 'email|max:255'
        ]);
        
        if($validator->fails()) {
            $response = [
                'error' => $validator->errors()
            ];
            return response()->json($response);
        }
        
       $user = \Auth::user();
       //ensure that only the authenticated user is updating his/her own account
       if($user->id != $id) {
            $response = [
                'error' => 'logged in users can only access their own accounts.'
            ];
            return response()->json($response);
        }
        $user_account = User::findOrFail($id);
        
        $this->_setStripeKey();
        try {
             //update Stripe Account details
            $stripe_account = \Stripe\Customer::retrieve($user_account->stripe_id);
            $stripe_account->email = $request->email;
            $stripeResponse = $stripe_account->save();
            
            //update our database details
            $user_account->email = $request->email;
            $user_account->save();
            
            $finalResponse = [
                    'status' => 'update was successful.'
                ];
            return response()->json($finalResponse);
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
     * deletes customer's account
     * 
     */
    public function destroy(Request $request, $id) {
        $user = \Auth::user();
        //ensure that only the authenticated user is updating his/her own account
       if($user->id != $id) {
            $response = [
                'error' => 'logged in users can only access their own accounts.'
            ];
            return response()->json($response);
        }
        $user_account = User::findOrFail($id);
       
       //delete Stripe Account details
        $this->_setStripeKey();
        try {
            $stripe_account = \Stripe\Customer::retrieve($user_account->stripe_id);
            $stripe_account->email = $request->email;
            $stripeResponse = $stripe_account->delete();
            
            //update our database details
            $user_account->email = $request->email;
            $user_account->delete();
            
            $finalResponse = [
                    'status' => 'account was successfully deleted.'
                ];
            return response()->json($finalResponse);
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
