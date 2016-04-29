@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                   
                   @if (Auth::user()->hasRole('Merchant'))
                   This is the homepage.
                   @else
                   This is not your homepage.
                   @endif
                   
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
