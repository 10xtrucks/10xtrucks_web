<form method="POST" action="https://sandbox.payfast.co.za/eng/process">

    <input type="hidden" name="merchant_id" value="{{Setting::get('payfast_merchent_id')}}"  />
    <input type="hidden" name="merchant_key" value="{{Setting::get('payfast_merchent_key')}}" />


    <input type="hidden" name="return_url" value="{{url('/'.$success)}}" />
    <input type="hidden" name="cancel_url" value="{{url('/'.$failure)}}" />
    <input type="hidden" name="notify_url" value="{{url('/payNotify')}}" />
    <input type="hidden" name="custom_str1" value="{{$cus_str}}" />
    <input type="hidden" name="name_first" value="{{$user->first_name}}" />
    <input type="hidden" name="name_last" value="{{$user->last_name}}" />
    <input type="hidden" name="email_address" value="{{$user->email}}" />

    <input type="hidden" name="m_payment_id" value="{{$tnx_id}}" />
    <input type="hidden" name="amount" value="{{$amount}}" />
    <input type="hidden" name="item_name" value="Payment{{$tnx_id}}" />
</form>
<script type="text/javascript">
    var form = document.forms[0];
    form.submit();
</script>