@extends('fleet.layout.base')

@section('title', 'User Reviews ')

@section('content')

    <div class="content-area py-1">
        <div class="container-fluid">
            
            <div class="box box-block bg-white">
                <h5 class="mb-1">@lang('fleet_dispatcher.user_review')</h5>
                <table class="table table-striped table-bordered dataTable" id="table-2">
                    <thead>
                        <tr>
                             <th>@lang('fleet_dispatcher.id')</th>
                            <th>@lang('fleet_dispatcher.request_id')</th>
                            <th>@lang('fleet_dispatcher.user_name')</th>
                            <th>@lang('fleet_dispatcher.provider_name')</th>
                            <th>@lang('fleet_dispatcher.ratings')</th>
                            <th>@lang('fleet_dispatcher.date_time')</th>
                            <th>@lang('fleet_dispatcher.comments')</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($Reviews as $index => $review)
                        <tr>
                            <td>{{$index + 1}}</td>
                            <td>{{$review->request_id}}</td>
                            <td>{{$review->user->first_name}} {{$review->user->last_name}}</td>
                            <td>{{$review->provider->first_name}} {{$review->provider->last_name}}</td>
                            <td>
                                <div className="rating-outer">
                                    <input type="hidden" value="{{$review->user_rating}}" name="rating" class="rating"/>
                                </div>
                            </td>
                            <td>{{$review->created_at}}</td>
                            <td>{{$review->user_comment}}</td>
                            
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                             <th>@lang('fleet_dispatcher.id')</th>
                            <th>@lang('fleet_dispatcher.request_id')</th>
                            <th>@lang('fleet_dispatcher.user_name')</th>
                            <th>@lang('fleet_dispatcher.provider_name')</th>
                            <th>@lang('fleet_dispatcher.ratings')</th>
                            <th>@lang('fleet_dispatcher.date_time')</th>
                            <th>@lang('fleet_dispatcher.comments')</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            
        </div>
    </div>
@endsection