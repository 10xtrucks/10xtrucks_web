@extends('admin.layout.base')

@section('title', 'Provider Documents ')

@section('content')
<div class="content-area py-1">
    <div class="container-fluid">
        <div class="box box-block bg-white">
            <h5 class="mb-1">@lang('admin.static_content_provider.service_type_allocate')</h5>
            <div class="row">
                <div class="col-xs-12">
                    @if($ProviderService->count() > 0)
                    <hr><h6>@lang('admin.static_content_provider.allocated_service') :  </h6>
                    <table class="table table-striped table-bordered dataTable">
                        <thead>
                            <tr>
                                <th>@lang('admin.static_content_provider.service_name')</th>
                                <th>@lang('admin.static_content_provider.service_number')</th>
                                <th>@lang('admin.static_content_provider.service_model')</th>
                                <!-- <th>Maximum Weight</th> -->
                                <th>@lang('admin.static_content_user.action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($ProviderService as $service)
                            <tr>
                                <td>{{ $service->service_type->name }}</td>
                                <td>{{ $service->service_number }}</td>
                                <td>{{ $service->service_model }}</td>
                                <!-- <td>{{ $service->maximum_weight }}</td> -->
                                <td>
                                    <form action="{{ route('admin.provider.document.service', [$Provider->id, $service->id]) }}" method="POST">
                                        {{ csrf_field() }}
                                        {{ method_field('DELETE') }}
                                        <button class="btn btn-danger btn-large btn-block">@lang('admin.static_content_user.delete')</a>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>@lang('admin.static_content_provider.service_name')</th>
                                <th>@lang('admin.static_content_provider.service_number')</th>
                                <th>@lang('admin.static_content_provider.service_model')</th>
                                <!-- <th>Maximum Weight</th> -->
                                <th>@lang('admin.static_content_user.action')</th>
                            </tr>
                        </tfoot>
                    </table>
                    @endif
                    <hr>
                </div>
                <form action="{{ route('admin.provider.document.store', $Provider->id) }}" method="POST">
                    {{ csrf_field() }}
                    <div class="col-xs-3">
                        <select class="form-control input" name="service_type" required>
                            @forelse($ServiceTypes as $Type)
                            <option value="{{ $Type->id }}">{{ $Type->name }}</option>
                            @empty
                            <option>- @lang('admin.static_content_provider.create_service_type') -</option>
                            @endforelse
                        </select>
                    </div>
                    <div class="col-xs-3">
                        <input type="text" required name="service_number" class="form-control" placeholder="Number (CY 98769)">
                    </div>
                    <div class="col-xs-3">
                        <input type="text" required name="service_model" class="form-control" placeholder="Model (Audi R8 - Black)">
                    </div>
                    <!-- <div class="col-xs-2">
                        <input type="text" required name="maximum_weight" class="form-control" placeholder="Maximum Weight">
                    </div> -->
                    <div class="col-xs-3">
                        <button class="btn btn-primary btn-block" type="submit">@lang('admin.static_content_provider.update')</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="box box-block bg-white">
            <h5 class="mb-1">@lang('admin.static_content_provider.provider_documents')</h5>
            <table class="table table-striped table-bordered dataTable" id="table-2">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>@lang('admin.static_content_provider.document_type')</th>
                        <th>@lang('admin.static_content_provider.status')</th>
                        <th>@lang('admin.static_content_user.action')</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($Provider->documents as $Index => $Document)
                    <tr>
                        <td>{{ $Index + 1 }}</td>
                        <td>{{ @$Document->document->name }}</td>
                        <td>{{ $Document->status }}</td>
                        <td>
                            <div class="input-group-btn">
                                <a href="{{ route('admin.provider.document.edit', [$Provider->id, $Document->id]) }}"><span class="btn btn-success btn-large">@lang('admin.static_content_provider.view')</span></a>
                                <button class="btn btn-danger btn-large" form="form-delete">@lang('admin.static_content_user.delete')</button>
                                <form action="{{ route('admin.provider.document.destroy', [$Provider->id, $Document->id]) }}" method="POST" id="form-delete">
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
                        <th>@lang('admin.static_content_provider.document_type')</th>
                        <th>@lang('admin.static_content_provider.status')</th>
                        <th>@lang('admin.static_content_user.action')</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection