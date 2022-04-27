@extends('fleet.layout.base')

@section('title', 'Provider Documents ')

@section('content')
<div class="content-area py-1">
    <div class="container-fluid">
        <div class="box box-block bg-white">
            <h5 class="mb-1">@lang('fleet_dispatcher.provider_alloc')</h5>
            <div class="row">
                <div class="col-xs-12">
                    @if($ProviderService->count() > 0)
                    <hr><h6>@lang('fleet_dispatcher.allocate_service') :  </h6>
                    <table class="table table-striped table-bordered dataTable">
                        <thead>
                            <tr>
                                <th>@lang('fleet_dispatcher.service_name')</th>
                                <th>@lang('fleet_dispatcher.service_number')</th>
                                <th>@lang('fleet_dispatcher.service_model')</th>
                                <th>@lang('fleet_dispatcher.action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($ProviderService as $service)
                            <tr>
                                <td>{{ $service->service_type->name }}</td>
                                <td>{{ $service->service_number }}</td>
                                <td>{{ $service->service_model }}</td>
                                <td>
                                    <form action="{{ route('fleet.provider.document.service', [$Provider->id, $service->id]) }}" method="POST">
                                        {{ csrf_field() }}
                                        {{ method_field('DELETE') }}
                                        <button class="btn btn-danger btn-large btn-block">@lang('fleet_dispatcher.delete')</a>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>@lang('fleet_dispatcher.service_name')</th>
                                <th>@lang('fleet_dispatcher.service_number')</th>
                                <th>@lang('fleet_dispatcher.service_model')</th>
                                <th>@lang('fleet_dispatcher.action')</th>
                            </tr>
                        </tfoot>
                    </table>
                    @endif
                    <hr>
                </div>
                <form action="{{ route('fleet.provider.document.store', $Provider->id) }}" method="POST">
                    {{ csrf_field() }}
                    <div class="col-xs-3">
                        <select class="form-control input" name="service_type" required>
                            @forelse($ServiceTypes as $Type)
                            <option value="{{ $Type->id }}">{{ $Type->name }}</option>
                            @empty
                            <option>- @lang('fleet_dispatcher.create_service') -</option>
                            @endforelse
                        </select>
                    </div>
                    <div class="col-xs-3">
                        <input type="text" required name="service_number" class="form-control" placeholder="@lang('fleet_dispatcher.service_num_place')">
                    </div>
                    <div class="col-xs-3">
                        <input type="text" required name="service_model" class="form-control" placeholder="@lang('fleet_dispatcher.service_mod_place')">
                    </div>
                    <div class="col-xs-3">
                        <button class="btn btn-primary btn-block" type="submit">@lang('fleet_dispatcher.update')</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="box box-block bg-white">
            <h5 class="mb-1">@lang('fleet_dispatcher.provider_doc')</h5>
            <table class="table table-striped table-bordered dataTable" id="table-2">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>@lang('fleet_dispatcher.document_type')</th>
                        <th>@lang('fleet_dispatcher.status')</th>
                        <th>@lang('fleet_dispatcher.action')</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($Provider->documents as $Index => $Document)
                    <tr>
                        <td>{{ $Index + 1 }}</td>
                        <td>{{ $Document->document->name }}</td>
                        <td>{{ $Document->status }}</td>
                        <td>
                            <div class="input-group-btn">
                                <a href="{{ route('fleet.provider.document.edit', [$Provider->id, $Document->id]) }}"><span class="btn btn-success btn-large">@lang('fleet_dispatcher.view')</span></a>
                                <button class="btn btn-danger btn-large" form="form-delete">@lang('fleet_dispatcher.delete')</button>
                                <form action="{{ route('fleet.provider.document.destroy', [$Provider->id, $Document->id]) }}" method="POST" id="form-delete">
                                    {{ csrf_field() }}
                                    {{ method_field('DELETE') }}
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th>#</th>
                        <th>@lang('fleet_dispatcher.document_type')</th>
                        <th>@lang('fleet_dispatcher.status')</th>
                        <th>@lang('fleet_dispatcher.action')</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection