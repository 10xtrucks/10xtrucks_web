@extends('admin.layout.base')

@section('title', 'Dashboard ')

@section('styles')
	<link rel="stylesheet" href="{{asset('main/vendor/jvectormap/jquery-jvectormap-2.0.3.css')}}">
@endsection

@section('content')

<div class="content-area py-1">
<div class="container-fluid">
    <div class="row row-md">
    	<a href="{{ route('admin.ride.statement') }}">
			<div class="col-lg-3 col-md-6 col-xs-12">
				<div class="box box-block bg-white tile tile-1 mb-2">
					<div class="t-icon right"><span class="bg-danger"></span><i class="ti-rocket"></i></div>
					<div class="t-content">
						<h6 class="text-uppercase mb-1">@lang('admin.static_content_dashboard.total_delivery')</h6>
						<h1 class="mb-1">{{$rides->count()}}</h1>
						<span class="tag tag-danger mr-0-5">@if($cancel_rides == 0) 0.00 @else {{round($cancel_rides/$rides->count(),2)}}% @endif</span>
						<span class="text-muted font-90">% @lang('admin.static_content_dashboard.down_cancel')</span>
					</div>
				</div>
			</div>
		</a>
		<a href="{{ route('admin.payment') }}">
			<div class="col-lg-3 col-md-6 col-xs-12">
				<div class="box box-block bg-white tile tile-1 mb-2">
					<div class="t-icon right"><span class="bg-success"></span><i class="ti-bar-chart"></i></div>
					<div class="t-content">
						<h6 class="text-uppercase mb-1">@lang('admin.static_content_dashboard.revenue')</h6>
						<h1 class="mb-1">{{currency($revenue)}}</h1>
						<i class="fa fa-caret-up text-success mr-0-5"></i><span>@lang('admin.static_content_dashboard.from') {{$rides->count()}} @lang('admin.static_content_dashboard.delivery')</span>
					</div>
				</div>
			</div>
		</a>
		<a href="{{ route('admin.service.index') }}">
			<div class="col-lg-3 col-md-6 col-xs-12">
				<div class="box box-block bg-white tile tile-1 mb-2">
					<div class="t-icon right"><span class="bg-primary"></span><i class="ti-view-grid"></i></div>
					<div class="t-content">
						<h6 class="text-uppercase mb-1">@lang('admin.static_content_dashboard.no_service_type')</h6>
						<h1 class="mb-1">{{$service}}</h1>
					</div>
				</div>
			</div>
		</a>
		<a href="{{ route('admin.requests.cancelled') }}">
			<div class="col-lg-3 col-md-6 col-xs-12">
				<div class="box box-block bg-white tile tile-1 mb-2">
					<div class="t-icon right"><span class="bg-warning"></span><i class="ti-archive"></i></div>
					<div class="t-content">
						<h6 class="text-uppercase mb-1">@lang('admin.static_content_dashboard.total_cancel')</h6>
						<h1 class="mb-1">{{$cancel_rides}}</h1>
						<i class="fa fa-caret-down text-danger mr-0-5"></i><span>@lang('admin.static_content_dashboard.for') @if($cancel_rides == 0) 0.00 @else {{round($cancel_rides/$rides->count(),2)}}% @endif @lang('admin.static_content_dashboard.delivery')</span>
					</div>
				</div>
			</div>
		</a>
		<a href="{{ route('admin.requests.cancelledbyUser') }}">
			<div class="col-lg-3 col-md-6 col-xs-12">
				<div class="box box-block bg-white tile tile-1 mb-2">
					<div class="t-icon right"><span class="bg-primary"></span><i class="ti-view-grid"></i></div>
					<div class="t-content">
						<h6 class="text-uppercase mb-1">@lang('admin.static_content_dashboard.user_cancel_count')</h6>
						<h1 class="mb-1">{{$user_cancelled}}</h1>
					</div>
				</div>
			</div>
		</a>
		<a href="{{ route('admin.requests.cancelledbyProvider') }}">
			<div class="col-lg-3 col-md-6 col-xs-12">
				<div class="box box-block bg-white tile tile-1 mb-2">
					<div class="t-icon right"><span class="bg-danger"></span><i class="ti-bar-chart"></i></div>
					<div class="t-content">
						<h6 class="text-uppercase mb-1">@lang('admin.static_content_dashboard.provider_cancel_count')</h6>
						<h1 class="mb-1">{{$provider_cancelled}}</h1>
					</div>
				</div>
			</div>
		</a>
		<a href="{{ route('admin.fleet.index') }}">
			<div class="col-lg-3 col-md-6 col-xs-12">
				<div class="box box-block bg-white tile tile-1 mb-2">
					<div class="t-icon right"><span class="bg-warning"></span><i class="ti-rocket"></i></div>
					<div class="t-content">
						<h6 class="text-uppercase mb-1">@lang('admin.static_content_dashboard.no_fleets')</h6>
						<h1 class="mb-1">{{$fleet}}</h1>
					</div>
				</div>
			</div>
		</a>
		<a href="{{ route('admin.requests.scheduled') }}">
			<div class="col-lg-3 col-md-6 col-xs-12">
				<div class="box box-block bg-white tile tile-1 mb-2">
					<div class="t-icon right"><span class="bg-success"></span><i class="ti-bar-chart"></i></div>
					<div class="t-content">
						<h6 class="text-uppercase mb-1">@lang('admin.static_content_dashboard.no_schedule_delivery')</h6>
						<h1 class="mb-1">{{$scheduled_rides}}</h1>
					</div>
				</div>
			</div>
		</a>
	</div>

	<div class="row row-md mb-2">
		<div class="col-md-12">
				<div class="box bg-white">
					<div class="box-block clearfix">
						<h5 class="float-xs-left">@lang('admin.static_content_dashboard.recent_delivery')</h5>
						<div class="float-xs-right">
							<button class="btn btn-link btn-sm text-muted" type="button"><i class="ti-close"></i></button>
						</div>
					</div>
					<table class="table mb-md-0">
						<tbody>
						<?php $diff = ['-success','-info','-warning','-danger']; ?>
						@foreach($rides as $index => $ride)
							<tr>
								<th scope="row">{{$index + 1}}</th>
								<td>{{$ride->user->first_name}} {{$ride->user->last_name}}</td>
								<td>
									@if($ride->status != "CANCELLED")
										<a class="text-primary" href="{{route('admin.requests.show',$ride->id)}}"><span class="underline">@lang('admin.static_content_dashboard.view_details')</span></a>
									@else
										<span>@lang('admin.static_content_dashboard.no_details') </span>
									@endif									
								</td>
								<td>
									<span class="text-muted">{{$ride->created_at->diffForHumans()}}</span>
								</td>
								<td>
									@if($ride->status == "COMPLETED")
										<span class="tag tag-success">{{$ride->status}}</span>
									@elseif($ride->status == "CANCELLED")
										<span class="tag tag-danger">{{$ride->status}}</span>
									@else
										<span class="tag tag-info">{{$ride->status}}</span>
									@endif
								</td>
							</tr>
							<?php if($index==10) break; ?>
						@endforeach
							
						</tbody>
					</table>
				</div>
			</div>

		</div>

	</div>
</div>
@endsection
