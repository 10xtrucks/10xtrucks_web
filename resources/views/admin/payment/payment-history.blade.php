@extends('admin.layout.base')

@section('title', 'Payment History ')

@section('content')

    <div class="content-area py-1">
        <div class="container-fluid">
            <div class="box box-block bg-white">
                <h5 class="mb-1">@lang('admin.payment.payment_history')</h5>
                <table class="table table-striped table-bordered dataTable" id="table-2">
                    <thead>
                        <tr>
                            <th>@lang('admin.request.Request_Id')</th>
                            <th>@lang('admin.payment.transaction_id')</th>
                            <th>@lang('admin.payment.from')</th>
                            <th>@lang('admin.payment.to')</th>
                            <th>@lang('admin.payment.total_amount')</th>
                            <th>@lang('admin.payment.payment_mode')</th>
                            <th>@lang('admin.payment.payment_status')</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($payments as $index => $payment)
                        <tr>
                            <td>{{$payment->id}}</td>
                            <td>{{$payment->payment->payment_id}}</td>
                            <td>{{$payment->user->first_name}} {{$payment->user->last_name}}</td>
                            <td>{{$payment->provider->first_name}} {{$payment->provider->last_name}}</td>
                            <td>{{currency($payment->payment->total)}}</td>
                            <td>{{$payment->payment_mode}}</td>
                            <td>
                                @if($payment->paid)
                                    @lang('admin.static_content_dashboard.paid')
                                @else
                                    @lang('admin.static_content_dashboard.not_paid')
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>@lang('admin.request.Request_Id')</th>
                            <th>@lang('admin.payment.transaction_id')</th>
                            <th>@lang('admin.payment.from')</th>
                            <th>@lang('admin.payment.to')</th>
                            <th>@lang('admin.payment.total_amount')</th>
                            <th>@lang('admin.payment.payment_mode')</th>
                            <th>@lang('admin.payment.payment_status')</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            
        </div>
    </div>
@endsection