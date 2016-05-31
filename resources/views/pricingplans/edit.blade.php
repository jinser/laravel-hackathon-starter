@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                  
                   @if (Auth::user()->hasRole('Merchant'))
                  
                   <form action="/pricingplans/{{$editPlan->id}}" method="POST" class="form-horizontal">
                       {{ csrf_field() }}
                       {{ method_field('PATCH') }}
                    <div class="form-group">
                          <label for="pricingplan-amount" class="col-sm-3 control-label">Update Plan Price</label>  
                          <div class="col-sm-6">
                              <input type="text" name="name" id="pricingplan-name" class="form-control" value={{$editPlan->name}}>
                              <input type="text" name="price" id="pricingplan-amount" class="form-control" value={{number_format($editPlan->amount/100,'2','.',',')}}>
                              <input type="text" name="currency" id="pricingplan-currency" class="form-control" value={{$editPlan->currency}}>
                                <button>Update</button>
                          </div>
                        
                    </div>   
                   </form>
                   
                   
                  
                   @else
                   This is not your homepage.
                   @endif
                   
                </div>
            </div>
        </div>
    </div>
</div>
@endsection