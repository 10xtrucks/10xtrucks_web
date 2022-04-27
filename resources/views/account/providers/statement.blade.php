@extends('account.layout.base')

@section('title', $page)

@section('content')

    <div class="content-area py-1">
        <div class="container-fluid">
            <div class="box box-block bg-white">
            	<h3>{{$page}}</h3>

            	<div class="clearfix" style="margin-top: 15px;">
					<form class="form-horizontal" action="{{route('account.ride.statement.range')}}" method="GET" enctype="multipart/form-data" role="form">

						<div class="form-group row col-md-5">
							<label for="name" class="col-xs-4 col-form-label">@lang('fleet_dispatcher.date_from')</label>
							<div class="col-xs-8">
								<input class="form-control" type="date" name="from_date" required placeholder="From Date">
							</div>
						</div>

						<div class="form-group row col-md-5">
							<label for="email" class="col-xs-4 col-form-label">@lang('fleet_dispatcher.date_to')</label>
							<div class="col-xs-8">
								<input class="form-control" type="date" required name="to_date" placeholder="To Date">
							</div>
						</div>
						<div class="form-group row col-md-2">
							<button type="submit" class="btn btn-primary">@lang('fleet_dispatcher.submit')</button>
						</div>
					</form>
				</div>

            	<div style="text-align: center;padding: 20px;color: blue;font-size: 24px;">
            		<p><strong>
            			<span>@lang('fleet_dispatcher.overall_earn') : {{currency($revenue[0]->overall)}}</span>
            			<br>
            			<span>@lang('fleet_dispatcher.overall_com') : {{currency($revenue[0]->commission)}}</span>
            		</strong></p>
            	</div>

            	<div class="row">

	            	<div class="col-lg-4 col-md-6 col-xs-12">
						<div class="box box-block bg-white tile tile-1 mb-2">
							<div class="t-icon right"><span class="bg-danger"></span><i class="ti-rocket"></i></div>
							<div class="t-content">
								<h6 class="text-uppercase mb-1">@lang('fleet_dispatcher.total_no_ride')</h6>
								<h1 class="mb-1">{{$rides->count()}}</h1>
								<span class="text-muted font-90">% @lang('fleet_dispatcher.down_cancel')</span>
							</div>
						</div>
					</div>


					<div class="col-lg-4 col-md-6 col-xs-12">
						<div class="box box-block bg-white tile tile-1 mb-2">
							<div class="t-icon right"><span class="bg-success"></span><i class="ti-bar-chart"></i></div>
							<div class="t-content">
								<h6 class="text-uppercase mb-1">@lang('fleet_dispatcher.revenue')</h6>
								<h1 class="mb-1">{{currency($revenue[0]->overall)}}</h1>
								<i class="fa fa-caret-up text-success mr-0-5"></i><span>@lang('fleet_dispatcher.from') {{$rides->count()}} @lang('fleet_dispatcher.rides')</span>
							</div>
						</div>
					</div>

					<div class="col-lg-4 col-md-6 col-xs-12">
						<div class="box box-block bg-white tile tile-1 mb-2">
							<div class="t-icon right"><span class="bg-warning"></span><i class="ti-archive"></i></div>
							<div class="t-content">
								<h6 class="text-uppercase mb-1">@lang('fleet_dispatcher.cancl_ride')</h6>
								<h1 class="mb-1">{{$cancel_rides}}</h1>
								<i class="fa fa-caret-down text-danger mr-0-5"></i><span>@lang('fleet_dispatcher.for') @if($cancel_rides == 0) 0.00 @else {{round($cancel_rides/$rides->count(),2)}}% @endif @lang('fleet_dispatcher.rides')</span>
							</div>
						</div>
					</div>

						<div class="row row-md mb-2" style="padding: 15px;">
							<div class="col-md-12">
									<div class="box bg-white">
										<div class="box-block clearfix">
											<h5 class="float-xs-left">@lang('fleet_dispatcher.earnings')</h5>
											<div class="float-xs-right">
											</div>
										</div>

										@if(count($rides) != 0)
								            <table class="table table-striped table-bordered dataTable" id="table-2">
								                <thead>
								                   <tr>
														<td>@lang('fleet_dispatcher.booking_id')</td>
														<td>@lang('fleet_dispatcher.picked_up')</td>
														<td>@lang('fleet_dispatcher.dropped')</td>
														<td>@lang('fleet_dispatcher.request_detail')</td>
														<td>@lang('fleet_dispatcher.commission')</td>
														<td>@lang('fleet_dispatcher.dated_on')</td>
														<td>@lang('fleet_dispatcher.status')</td>
														<td>@lang('fleet_dispatcher.earned')</td>
													</tr>
								                </thead>
								                <tbody>
								                <?php $diff = ['-success','-info','-warning','-danger']; ?>
														@foreach($rides as $index => $ride)
															<tr>
																<td>{{ $ride->booking_id }}</td>
																<td>
																	@if($ride->s_address != '')
																		{{$ride->s_address}}
																	@else
																		@lang('fleet_dispatcher.not_prov')
																	@endif
																</td>
																<td>
																	@if($ride->d_address != '')
																		{{$ride->d_address}}
																	@else
																		@lang('fleet_dispatcher.not_prov')
																	@endif
																</td>
																<td>
																	@if($ride->status != "CANCELLED")
																		<a class="text-primary" href="{{route('account.requests.show',$ride->id)}}"><span class="underline">@lang('fleet_dispatcher.view_delivery')</span></a>
																	@else
																		<span>@lang('fleet_dispatcher.no_details') </span>
																	@endif									
																</td>
																<td>{{currency($ride->payment['commision'])}}</td>
																<td>
																	<span class="text-muted">{{date('d M Y',strtotime($ride->created_at))}}</span>
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
																<td>{{currency($ride->payment['fixed'] + $ride->payment['distance'])}}</td>

															</tr>
														@endforeach
															
								                <tfoot>
								                    <tr>
														<td>@lang('fleet_dispatcher.booking_id')</td>
														<td>@lang('fleet_dispatcher.picked_up')</td>
														<td>@lang('fleet_dispatcher.dropped')</td>
														<td>@lang('fleet_dispatcher.request_detail')</td>
														<td>@lang('fleet_dispatcher.commission')</td>
														<td>@lang('fleet_dispatcher.dated_on')</td>
														<td>@lang('fleet_dispatcher.status')</td>
														<td>@lang('fleet_dispatcher.earned')</td>
													</tr>
								                </tfoot>
								            </table>
								            @else
								            <h6 class="no-result">@lang('fleet_dispatcher.no_result')</h6>
								            @endif 

									</div>
								</div>

							</div>

            	</div>

            </div>
        </div>
    </div>

@endsection
