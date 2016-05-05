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
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pricingPlans = PricingPlan::all();
        
        return view('pricingplans.index')->with('pricingPlans',$pricingPlans);
    }
    
    public function create() {
        
    }
    
    public function store(Request $request) {
       
        $request->user()->pricingPlans()->create([
            'name' => $request->name,
            'price' => $request->price,
            'currency' => 'SGD'
        ]);

    return redirect('pricingplans');
    }
    
    public function show($id) {
        
    }
    
    public function edit($id) {
        $editPlan = PricingPlan::findOrFail($id);
        return View('pricingplans.edit')->with('editPlan',$editPlan);
    }
    
    public function update($id,Request $request) {
        $plan = PricingPlan::findOrFail($id);
        
        $this->validate($request, [
            'name' => 'required',
            'currency' => 'required',
            'price' => 'required'
        ]);
        
        $plan->name = $request->name;
        $plan->price = $request->price;
        $plan->currency = $request->currency;
        $plan->save();
        
        return redirect('pricingplans');
        
    }
    
    public function destroy($id) {
        
        $plan = PricingPlan::findOrFail($id);
        $plan->delete();
        
        return redirect('pricingplans');
    }
}
