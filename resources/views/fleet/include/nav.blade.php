<div class="site-sidebar">
	<div class="custom-scroll custom-scroll-light">
		<ul class="sidebar-menu">
			<li class="menu-title">@lang('fleet_dispatcher.fleet_dashboard')</li>
			<li>
				<a href="{{ route('fleet.dashboard') }}" class="waves-effect waves-light">
					<span class="s-icon"><i class="ti-anchor"></i></span>
					<span class="s-text">@lang('fleet_dispatcher.dashboard')</span>
				</a>
			</li>
			
			<li class="menu-title">@lang('fleet_dispatcher.members')</li>
			<li class="with-sub">
				<a href="#" class="waves-effect  waves-light">
					<span class="s-caret"><i class="fa fa-angle-down"></i></span>
					<span class="s-icon"><i class="ti-car"></i></span>
					<span class="s-text">@lang('fleet_dispatcher.providers')</span>
				</a>
				<ul>
					<li><a href="{{ route('fleet.provider.index') }}">@lang('fleet_dispatcher.list_providers')</a></li>
					<li><a href="{{ route('fleet.provider.create') }}">@lang('fleet_dispatcher.add_new_provider')</a></li>
				</ul>
			</li>
			<li class="menu-title">@lang('fleet_dispatcher.details')</li>
			<li>
				<a href="{{ route('fleet.map.index') }}" class="waves-effect waves-light">
					<span class="s-icon"><i class="ti-map-alt"></i></span>
					<span class="s-text">@lang('fleet_dispatcher.map')</span>
				</a>
			</li>
			<li class="with-sub">
				<a href="#" class="waves-effect  waves-light">
					<span class="s-caret"><i class="fa fa-angle-down"></i></span>
					<span class="s-icon"><i class="ti-view-grid"></i></span>
					<span class="s-text">@lang('fleet_dispatcher.ratings') &amp; @lang('fleet_dispatcher.reviews')</span>
				</a>
				<ul>
					<li><a href="{{ route('fleet.provider.review') }}">@lang('fleet_dispatcher.provider_rating')</a></li>
				</ul>
			</li>
			<li class="menu-title">@lang('fleet_dispatcher.requests')</li>
			<li>
				<a href="{{ route('fleet.requests.index') }}" class="waves-effect  waves-light">
					<span class="s-icon"><i class="ti-infinite"></i></span>
					<span class="s-text">@lang('fleet_dispatcher.request_history')</span>
				</a>
			</li>
			<li>
				<a href="{{ route('fleet.requests.scheduled') }}" class="waves-effect  waves-light">
					<span class="s-icon"><i class="ti-palette"></i></span>
					<span class="s-text">@lang('fleet_dispatcher.schedule_rides')</span>
				</a>
			</li>
			
			<li class="menu-title">@lang('fleet_dispatcher.account')</li>
			<li>
				<a href="{{ route('fleet.profile') }}" class="waves-effect  waves-light">
					<span class="s-icon"><i class="ti-user"></i></span>
					<span class="s-text">@lang('fleet_dispatcher.account_settings')</span>
				</a>
			</li>
			<li>
				<a href="{{ route('fleet.password') }}" class="waves-effect  waves-light">
					<span class="s-icon"><i class="ti-exchange-vertical"></i></span>
					<span class="s-text">@lang('fleet_dispatcher.change_password')</span>
				</a>
			</li>
			<li class="compact-hide">
				<a href="{{ url('/fleet/logout') }}"
                            onclick="event.preventDefault();
                                     document.getElementById('logout-form').submit();">
					<span class="s-icon"><i class="ti-power-off"></i></span>
					<span class="s-text">@lang('fleet_dispatcher.logout')</span>
                </a>

                <form id="logout-form" action="{{ url('/fleet/logout') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                </form>
			</li>
			
		</ul>
	</div>
</div>