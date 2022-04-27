@extends('admin.layout.base')

@section('title', $page)

@section('content')

    <div class="content-area py-1">
        <div class="container-fluid">
            <div class="box box-block bg-white">
            	<h3>{{$page}}</h3>

            	<div class="row">

						<div class="row row-md mb-2" style="padding: 15px;">
							<div class="col-md-12">
									<div class="box bg-white">
										<div class="box-block clearfix">
											<h5 class="float-xs-left">@lang('admin.static_content_provider.earnings')</h5>
											<div class="float-xs-right">
											</div>
										</div>

										@if(count($Providers) != 0)
								            <table class="table table-striped table-bordered dataTable" id="table-2">
								                <thead>
								                   <tr>
														<td>@lang('admin.static_content_provider.provider_name')</td>
														<td>@lang('admin.static_content_user.mobile')</td>
														<td>@lang('admin.static_content_provider.status')</td>
														<td>@lang('admin.static_content_provider.total_delivery')</td>
														<td>@lang('admin.static_content_provider.total_earning')</td>
														<td>@lang('admin.static_content_provider.commission')</td>
														<td>@lang('admin.static_content_provider.joined_at')</td>
														<td>@lang('admin.static_content_provider.details')</td>
													</tr>
								                </thead>
								                <tbody>
								                <?php $diff = ['-success','-info','-warning','-danger']; ?>
														@foreach($Providers as $index => $provider)
															<tr>
																<td>
																	{{$provider->first_name}} 
																	{{$provider->last_name}}
																</td>
																<td>
																	{{$provider->mobile}}
																</td>
																<td>
																	@if($provider->status == "approved")
																		<span class="tag tag-success">{{$provider->status}}</span>
																	@elseif($provider->status == "banned")
																		<span class="tag tag-danger">{{$provider->status}}</span>
																	@else
																		<span class="tag tag-info">{{$provider->status}}</span>
																	@endif
																</td>
																<td>
																	@if($provider->rides_count)
																		{{$provider->rides_count}}
																	@else
																	 	-
																	@endif
																</td>
																<td>
																	@if($provider->payment)
																		{{currency($provider->payment[0]->overall)}}
																	@else
																	 	-
																	@endif
																</td>
																<td>
																	@if($provider->payment)
																		{{currency($provider->payment[0]->commission)}}
																	@else
																	 	-
																	@endif
																</td>
																<td>
																	@if($provider->created_at)
																		<span class="text-muted">{{$provider->created_at->diffForHumans()}}</span>
																	@else
																	 	-
																	@endif
																</td>
																<td>
																	<a href="{{route('admin.provider.statement', $provider->id)}}">@lang('admin.static_content_provider.view_by_ride')</a>
																</td>
															</tr>
														@endforeach
															
								                <tfoot>
								                    <tr>
														<td>@lang('admin.static_content_provider.provider_name')</td>
														<td>@lang('admin.static_content_user.mobile')</td>
														<td>@lang('admin.static_content_provider.status')</td>
														<td>@lang('admin.static_content_provider.total_delivery')</td>
														<td>@lang('admin.static_content_provider.total_earning')</td>
														<td>@lang('admin.static_content_provider.commission')</td>
														<td>@lang('admin.static_content_provider.joined_at')</td>
														<td>@lang('admin.static_content_provider.details')</td>
													</tr>
								                </tfoot>
								            </table>
								            @else
								            <h6 class="no-result">@lang('admin.static_content_provider.no_result')</h6>
								            @endif 

									</div>
								</div>

							</div>

            	</div>

            </div>
        </div>
    </div>

@endsection
