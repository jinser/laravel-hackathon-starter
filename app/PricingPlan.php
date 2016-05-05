<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PricingPlan extends Model
{
    use SoftDeletes;
    
    protected $table = 'pricing_plans'; 
    protected $dates = ['deleted_at'];
    
    /**
     *fillable fields for PricingPlan table
     */
     protected $fillable = ['name','currency','price','billing_frequency_length','billing_frequency_period','description','display_order'];
    
    /**
     * Get list of all pricing plans
     * @return Response
     */
     
     public function index() {
        $pricingPlans = PricingPlan::all;
        
        return view('pricingPlan.index',[pricingPlans => $pricingPlans]);
     }
     
     /**
      *  Get all pricing plans based on their display order ascending
      */
     public function getByDisplayOrderAsc() {
         $pricingPlans = PricingPlan::orderBy('display_order','asc');
         
         return $pricingPlans;
     }
     
     public function user() {
         return $this->belongsTo(User::class);
     }
     
     
    
}
