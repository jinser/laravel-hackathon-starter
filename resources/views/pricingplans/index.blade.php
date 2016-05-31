@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                  
                   @if (Auth::user()->hasRole('Merchant'))
                   <!-- New Pricing Plan -->
                   <form action="/pricingplans" method="POST" class="form-horizontal">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label for="pricingplan-name" class="col-sm-3 control-label">Plan Name</label>
                            
                            <div class="col-sm-6">
                                <input type="text" name="name" id="pricingplan-name" class="form-control">
                                <input type="number" name="price" id="pricingplan-price" class="form-control" min="0" step="0.01">
                            </div>

                        </div>
                        
                        <div class="form-group">
                            <div class="col-sm-offset-3 col-sm-6">
                                <button type="submit" class="btn btn-default">
                                    <i class="fa fa-plus"></i> Add Pricing Plan
                                </button>
                            </div>
                        </div>
                   </form>
                  
                   <table class="table table-striped table-bordered">
                        <thead>
                          <tr>
                           <td>Plan Name</td>
                           <td>Currency</td>
                           <td>Price</td>
                           </tr>    
                        </thead>
                        <tbody>
                        @foreach($pricingPlans as $key => $pricingPlan)
                            <tr>
                               <td>{{ $pricingPlan->name }}</td>
                               <td>{{ $pricingPlan->currency }}</td>
                               
                               <td>{{ number_format($pricingPlan->amount/100,'2','.',',') }}</td>
                               <td>
                                   <form action="/pricingplans/{{$pricingPlan->id}}" method="POST">
                                        {{ csrf_field() }}
                                        {{ method_field('DELETE') }}
                                        <button>Delete Plan</button>
                                   </form>
                                    <form action="/pricingplans/{{$pricingPlan->id}}/edit" method="GET">
                                         {{ csrf_field() }}
                                         <button>Edit Plan</button>
                                    </form>
                               </td>
                            </tr>  
                         @endforeach
                        </tbody>
                   </table>
                   
                  
                   @else
                   <table class="table table-striped table-bordered">
                        <thead>
                          <tr>
                           <td>Plan Name</td>
                           <td>Currency</td>
                           <td>Price</td>
                           </tr>    
                        </thead>
                        <tbody>
                        @foreach($pricingPlans as $key => $pricingPlan)
                            <tr>
                               <td>{{ $pricingPlan->name }}</td>
                               <td>{{ $pricingPlan->currency }}</td>
                               
                               <td>{{ number_format($pricingPlan->amount/100,'2','.',',') }}</td>
                               <td>
                                   @if(Auth::user()->subscribed($pricingPlan->id))
                                   <form action="/pricingplans/{{$pricingPlan->id}}/edit" method="POST">
                                         {{ csrf_field() }}
                                         <button>Cancel Plan</button>
                                    </form>
                                   @else
                                     <form action="/api/subscriptions" method="POST">
                                         <div class="col-sm-6">
                                            <input type="hidden" name="pricingPlanName" id="pricingplan-name" value="{{$pricingPlan->name}}" class="form-control">
                                            <input type="hidden" name="pricingPlanFrequency" id="pricingplan-frequency" value="{{$pricingPlan->interval}}" class="form-control">
                                        </div>
                                        {{ csrf_field() }}
                                        <button>Subscribe</button>
                                   </form>
                                    @endif
                               </td>
                            </tr>  
                         @endforeach
                        </tbody>
                   </table>
                   @endif
                   
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
