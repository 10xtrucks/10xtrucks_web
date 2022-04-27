@extends('user.layout.app')

@section('content')
<div class="banner row no-margin" style="background-image: url('{{ asset('asset/img/banner-bg.jpg') }}');">
    <div class="banner-overlay"></div>
    <div class="container">
        <div class="col-md-8">
            <h1 style="color:#FFF !important; font-size:37px; font-weight: 600;">{{Setting::get('site_title')}}</h1>
            <h2 class="banner-head">Una aplicaci&oacute;n m&oacute;vil y web que agiliza el proceso de env&iacute;o / entrega de mercanc&iacute;a a&nbsp;tan&nbsp;solo un par de clic!!!</h2>
        </div>
        <div class="col-md-4">
            <div class="banner-form">
                <div class="row no-margin fields">
                    <div class="left">
                        <img src="{{ asset('asset/img/send-stuff.png') }}">
                    </div>
                    <div class="right">
                        <a href="{{url('login')}}">
                            <h3>Env&iacute;a con Truker</h3>
                        </a>
                        <a href="{{url('register')}}">
                            <h5>REG&Iacute;STRATE <i class="fa fa-chevron-right"></i></h5>
                        </a>
                    </div>
                </div>
                <div class="row no-margin fields">
                    <div class="left">
                        <img src="{{ asset('asset/img/ride-form-icon.png') }}">
                    </div>
                    <div class="right">
                        <a href="{{ url('/provider/login') }}">
                            <h3>Conduce con Truker</h3>
                        </a>
                        <a href="{{ url('/provider/register') }}">
                            <h5>REG&Iacute;STRATE <i class="fa fa-chevron-right"></i></h5>
                        </a>
                    </div>
                </div>
                <p class="note-or">O <a href="{{ url('/provider/login') }}">inicia sesi&oacute;n</a> con una cuenta existente.</p>
            </div>
        </div>
    </div>
</div>

<div class="row white-section no-margin">
    <div class="container">
        <div class="col-md-6 img-block text-center"> 
            <img src="{{ asset('asset/img/tap.png') }}">
        </div>
        <div class="col-md-6 content-block">
            <h2>Conveniencia en su m&aacute;xima expresi&oacute;n</h2>
            <div class="title-divider"></div>
            <!-- <p>{{ Setting::get('site_title', 'Fetschstr')  }} is the smartest way to get around. One tap and a car comes directly to you. Your Deliver knows exactly where to go. And you can pay with either cash or card.</p> -->
            <p>Ya sea que se trate de muebles, un televisor, art&iacute;culos de oficina o una computadora que dej&oacute; en la casa de alg&uacute;n amigo, TRUKER BOT lo recoger&aacute; y se lo entregar&aacute; con solo presionar un bot&oacute;n.</p>
            <!-- <a class="content-more" href="#">Sign up <i class="fa fa-chevron-right"></i></a> -->
        </div>
    </div>
</div>

<div class="row gray-section no-margin">
    <div class="container">                
        <div class="col-md-6 content-block">
 	    <h2>Seguro</h2>
            <div class="title-divider"></div>
            <p>A trav&eacute;s de la aplicaci&oacute;n puede realizar un seguimiento del progreso de su entrega en tiempo real. Tambi&eacute;n puede asegurarse de que su mercanc&iacute;a se entreguen a la persona correcta a trav&eacute;s de un pin &uacute;nico.</p>
            <!-- <a class="content-more" href="#">MORE REASONS TO DELIVERY <i class="fa fa-chevron-right"></i></a> -->
        </div>
        <div class="col-md-6 img-block text-center"> 
            <img src="{{ asset('asset/img/anywhere.png') }}">
        </div>
    </div>
</div>

<div class="row white-section no-margin">
    <div class="container">
        <div class="col-md-6 img-block text-center"> 
            <img src="{{ asset('asset/img/low-cost.png') }}">
        </div>
        <div class="col-md-6 content-block">
            <h2>Econ&oacute;mico</h2>
            <div class="title-divider"></div>
            <p>Consulte el costo de cada entrega antes de que ocurra el despacho. Todos los precios se basan en la distancia del acarreo.</p>
            <!-- <a class="content-more-btn" href="#">Get a fair estimate <i class="fa fa-chevron-right"></i></a> -->
        </div>
    </div>
</div>

<div class="row gray-section no-margin full-section">
    <div class="container">                
        <div class="col-md-6 content-block">
            <h2>Gana dinero extra<br/>Reg&iacute;strate como conductor</h2>
            <div class="title-divider"></div>
            <p>&iquest;Tienes pickup o cami&oacute;n?</p>
            <p>&iquest;Tiene una licencia para conducir camiones?</p>
            <p>&iquest;Quieres ser tu propio jefe?</p>
            <a class="content-more-btn" href="{{ url('/provider/register') }}">Reg&iacute;strate como conductor ahora <i class="fa fa-chevron-right"></i></a>
        </div>
        <div class="col-md-6 full-img text-center" style="background-image: url({{ asset('asset/img/truck.png') }});"> 
            <!-- <img src="img/anywhere.png"> -->
        </div>
    </div>
</div>

<!-- <div class="row white-section no-margin">
    <div class="container">
        <div class="col-md-6 img-block text-center"> 
            <img src="{{ asset('asset/img/low-cost.png') }}">
        </div>
        <div class="col-md-6 content-block">
            <h2>Helping Cities For the good of all</h2>
            <div class="title-divider"></div>
            <p>A city with {{ Setting::get('site_title', 'Fetschstr') }} has more economic opportunities for residents, fewer drunk Deliver on the streets, and better access to transportation for those without it.</p>
            <a class="content-more" href="#">OUR LOCAL IMPACT <i class="fa fa-chevron-right"></i></a>
        </div>
    </div>
</div> -->

<!-- <div class="row gray-section no-margin">
    <div class="container">
        <div class="col-md-6 content-block">
            <h2>Safety Putting people first</h2>
            <div class="title-divider"></div>
            <p>Whether riding in the backseat or Delivering up front, every part of the {{ Setting::get('site_title', 'Fetschstr') }} experience has been designed around your safety and security.</p>
            <a class="content-more" href="#">HOW WE KEEP YOU SAFE <i class="fa fa-chevron-right"></i></a>
        </div>
        <div class="col-md-6 img-block text-center"> 
            <img src="{{ asset('asset/img/seat-belt.jpg') }}">
        </div>
    </div>
</div>

<div class="row find-city no-margin">
    <div class="container">
        <h2>{{ Setting::get('site_title','Tranxit') }} in Your City</h2>
        <form>
            <div class="input-group find-form">
                <input type="text" class="form-control" placeholder="Search" >
                <span class="input-group-addon">
                    <button type="submit">
                        <i class="fa fa-arrow-right"></i>
                    </button>  
                </span>
            </div>
        </form>
    </div>
</div> -->

<div class="footer-city row no-margin"></div>
@endsection