@extends('admin.layout.base')

@section('title', 'Users ')

@section('content')
<div class="content-area py-1">
    <div class="container-fluid">
        <div class="box box-block bg-white">
            <h5 class="mb-1">
                @lang('admin.static_content_user.users')
                @if(Setting::get('demo_mode', 0) == 1)
                <span class="pull-right">(*personal information hidden in demo)</span>
                @endif
            </h5>
            <a href="{{ route('admin.user.create') }}" style="margin-left: 1em;" class="btn btn-primary pull-right"><i class="fa fa-plus"></i> @lang('admin.static_content_user.add_new')</a>
            <table class="table table-striped table-bordered dataTable" id="table-2">
                <thead>
                    <tr>
                        <th>@lang('admin.static_content_user.id')</th>
                        <th>@lang('admin.static_content_user.first_name')</th>
                        <th>@lang('admin.static_content_user.last_name')</th>
                        <th>@lang('admin.static_content_user.email')</th>
                        <th>@lang('admin.static_content_user.mobile')</th>
                        <th>@lang('admin.static_content_user.user_type')</th>
                        <th>@lang('admin.static_content_user.rating')</th>
                        <th>@lang('admin.static_content_user.wallet_amount')</th>
                        <th>@lang('admin.static_content_user.action')</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $index => $user)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $user->first_name }}</td>
                        <td>{{ $user->last_name }}</td>
                        @if(Setting::get('demo_mode', 0) == 1)
                        <td>{{ substr($user->email, 0, 3).'****'.substr($user->email, strpos($user->email, "@")) }}</td>
                        @else
                        <td>{{ $user->email }}</td>
                        @endif
                        @if(Setting::get('demo_mode', 0) == 1)
                        <td>+919876543210</td>
                        @else
                        <td>{{ $user->mobile }}</td>
                        @endif
                         <td>{{ $user->user_type }}</td>
                        <td>{{ $user->rating }}</td>
                        <td>{{ currency().$user->wallet_balance }}</td>
                        <td>
                            <form action="{{ route('admin.user.destroy', $user->id) }}" method="POST">
                                {{ csrf_field() }}
                                <input type="hidden" name="_method" value="DELETE">
                                <a href="{{ route('admin.user.request', $user->id) }}" class="btn btn-info"><i class="fa fa-search"></i> @lang('admin.static_content_user.history')</a>
                                <a href="{{ route('admin.user.edit', $user->id) }}" class="btn btn-info"><i class="fa fa-pencil"></i> @lang('admin.static_content_user.edit')</a>
                                <button class="btn btn-danger" onclick="var del_cont='{{trans("admin.static_content_user.are_sure")}}';return confirm(del_cont)"><i class="fa fa-trash"></i> @lang('admin.static_content_user.delete')</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th>@lang('admin.static_content_user.id')</th>
                        <th>@lang('admin.static_content_user.first_name')</th>
                        <th>@lang('admin.static_content_user.last_name')</th>
                        <th>@lang('admin.static_content_user.email')</th>
                        <th>@lang('admin.static_content_user.mobile')</th>
                        <th>@lang('admin.static_content_user.user_type')</th>
                        <th>@lang('admin.static_content_user.rating')</th>
                        <th>@lang('admin.static_content_user.wallet_amount')</th>
                        <th>@lang('admin.static_content_user.action')</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection