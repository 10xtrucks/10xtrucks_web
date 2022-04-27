@extends('fleet.layout.auth')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">@lang('fleet_dispatcher.dashboard')</div>

                <div class="panel-body">
                    @lang('fleet_dispatcher.logged_fleet')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
