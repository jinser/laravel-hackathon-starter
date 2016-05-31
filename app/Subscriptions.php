<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subscriptions extends Model
{
    protected $table = 'subscriptions';
 
    /**
     * fillable fields for Subscription Table
     */
     protected $fillable = ['user_id','name','stripe_id','stripe_plan','quantity'];
     
     public function index() {
         $subscriptions = Subscriptions::all;
         
         return $subscriptions;
     }
     
     public function pricingPlan() {
         return $this->belongsTo(PricingPlan::class);
     }
     
     public function subscriber() {
         return $this->belongsTo(User::class);
     }
}
