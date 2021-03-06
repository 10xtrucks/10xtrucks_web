@extends('user.layout.base')

@section('title', 'Wallet ')

@section('content')

<div class="col-md-9">
    <div class="dash-content">
        <div class="row no-margin">
            <div class="col-md-12">
                <h4 class="page-title">@lang('user.my_wallet')</h4>
            </div>
        </div>
        @include('common.notify')

        <div class="row no-margin">
            <form action="{{url('add/money')}}" method="POST">
            {{ csrf_field() }}
                <div class="col-md-6">
                     
                    <div class="wallet">
                        <h4 class="amount">
                          <span class="price">{{currency(Auth::user()->wallet_balance)}}</span>
                          <span class="txt">@lang('user.in_your_wallet')</span>
                        </h4>
                    </div>                                                               

                </div>
                
                <div class="col-md-6">
                    
                    <h6><strong>@lang('user.add_money')</strong></h6>

                    <div class="input-group full-input">
                        <input type="number" class="form-control" name="amount" placeholder="Enter Amount" >
                    </div>
                    <br>
                    
                        <select class="form-control" name="card_id">
                         @if(Setting::get('CARD') == 1)
                           @if($cards->count() > 0)
                        @foreach($cards as $card)
                          <option @if($card->is_default == 1) selected @endif value="{{$card->card_id}}">{{$card->brand}} **** **** **** {{$card->last_four}}</option>
                        @endforeach
                           @endif
                           @endif
          
                            <!-- <option value="PAYPAL">Paypal</option> -->

                        </select>
                    
                    
                    <button type="submit" class="full-primary-btn fare-btn">@lang('user.add_money')</button> 

                  

                </div>
               
            </form>
        </div>

    </div>
</div>

@endsection

@section('scripts')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

@endsection