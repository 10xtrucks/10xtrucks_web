<div class="site-sidebar">
	<div class="custom-scroll custom-scroll-light">
		<ul class="sidebar-menu">
			<li class="menu-title">@lang('fleet_dispatcher.account_dash')</li>
			<li>
				<a href="{{ route('account.dashboard') }}" class="waves-effect waves-light">
					<span class="s-icon"><i class="ti-anchor"></i></span>
					<span class="s-text">@lang('fleet_dispatcher.dashboard')</span>
				</a>
			</li>
			<li class="menu-title">@lang('fleet_dispatcher.account_state')</li>
			<li>
				<a href="{{ route('account.ride.statement') }}" class="waves-effect waves-light">
					<span class="s-icon"><i class="ti-target"></i></span>
					<span class="s-text">@lang('fleet_dispatcher.overa_ride_state')</span>
				</a>
			</li>
			<li>
				<a href="{{ route('account.ride.statement.provider') }}" class="waves-effect waves-light">
					<span class="s-icon"><i class="ti-target"></i></span>
					<span class="s-text">@lang('fleet_dispatcher.provider_state')</span>
				</a>
			</li>
			<li>
				<a href="{{ route('account.ride.statement.today') }}" class="waves-effect waves-light">
					<span class="s-icon"><i class="ti-target"></i></span>
					<span class="s-text">@lang('fleet_dispatcher.daily_state')</span>
				</a>
			</li>
			<li>
				<a href="{{ route('account.ride.statement.monthly') }}" class="waves-effect waves-light">
					<span class="s-icon"><i class="ti-target"></i></span>
					<span class="s-text">@lang('fleet_dispatcher.month_state')</span>
				</a>
			</li>
			<li>
				<a href="{{ route('account.ride.statement.yearly') }}" class="waves-effect waves-light">
					<span class="s-icon"><i class="ti-target"></i></span>
					<span class="s-text">@lang('fleet_dispatcher.year_state')</span>
				</a>
			</li>
			<li class="menu-title">@lang('fleet_dispatcher.account')</li>
			<li>
				<a href="{{ route('account.profile') }}" class="waves-effect  waves-light">
					<span class="s-icon"><i class="ti-user"></i></span>
					<span class="s-text">@lang('fleet_dispatcher.account_settings')</span>
				</a>
			</li>
			<li>
				<a href="{{ route('account.password') }}" class="waves-effect  waves-light">
					<span class="s-icon"><i class="ti-exchange-vertical"></i></span>
					<span class="s-text">@lang('fleet_dispatcher.change_password')</span>
				</a>
			</li>
			<li class="compact-hide">
				<a href="{{ url('/account/logout') }}"
                            onclick="event.preventDefault();
                                     document.getElementById('logout-form').submit();">
					<span class="s-icon"><i class="ti-power-off"></i></span>
					<span class="s-text">@lang('fleet_dispatcher.logout')</span>
                </a>

                <form id="logout-form" action="{{ url('/account/logout') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                </form>
			</li>
			
		</ul>
	</div>
</div>