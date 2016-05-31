<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\PricingPlan;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PricingPlanApiController extends Controller
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
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pricingPlans = PricingPlan::all();
        $response = [
            'plans' =>  $pricingPlans
        ];
        return response()->json($response);
    }
    
    public function create() {
        
    }
    
    public function store(Request $request) {
        $response = PricingPlan::create([
            'name' => $request->name,
            'price' => $request->price,
            'currency' => 'SGD'
        ]);
        return response()->json($response);
    }
    
    public function show($id) {
        $showPlan = PricingPlan::findOrFail($id);
        $response = [
            'plan' => $showPlan
        ];
        return response()->json($response);
    }
    
    public function edit($id) {
        $editPlan = PricingPlan::findOrFail($id);
        $response = [
            'plan' => $editPlan
            ];
        return response()->json($response);
    }
    
    public function update(Request $request, $id) {
        $response = PricingPlan::findOrFail($id);
        
        if($request->name) {
            $response->name = $request->name;
        }
        if($request->price) {
            $response->price = $request->price;
        }
        if($request->currency) {
            $response->currency = $request->currency;
        }
        $response->save();
        
       return response()->json($response);
        
    }
    
    public function destroy(Request $request, $id) {
        
        $plan = PricingPlan::findOrFail($id);
        $delete_status = $plan->delete();
         $response = [
            'delete_successful' => $delete_status
            ];
        return response()->json($response);
    }
    
}
