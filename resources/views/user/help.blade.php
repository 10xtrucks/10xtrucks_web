@extends('user.layout.base')
@section('styles')
<style type="text/css">
    .help {
    	max-width: 300px;
    	margin: 0 auto;
    	border: 1px solid #eee;
    	text-align: center;
    	padding: 20px;
    }

    .help img{
    	margin-bottom: 30px;
    }

    .help-icon i{
    	font-size: 25px;
	    padding: 12px 15px;
	    background-color: #764392;
	    color: #fff;
	    border-radius: 100%;
	    margin-right: 10px;
    }
</style>
@endsection

@section('content')

<div class="col-md-9">
	<div class="dash-content">
		<div class="help-outer">
		<h3 class="no-margin1">Help</h3>
		<p>{{ Setting::get('help_content') }}</p>
			<div class="help">
				<img src="{{asset('asset/img/call.jpg')}}">
				<div>
					<a href="tel:{{ Setting::get('contact_number') }}" class="help-icon"><i class="fa fa-phone"></i></a>
					<a href="mailto:{{ Setting::get('contact_email') }}" class="help-icon"><i class="fa fa-envelope-o"></i></a>
					
				</div>
			</div>
		</div>

	</div>
</div>

@endsection

