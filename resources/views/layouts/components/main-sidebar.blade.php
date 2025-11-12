
			<aside class="app-sidebar" id="sidebar">

				<!-- Start::main-sidebar-header -->
				<div class="main-sidebar-header">
					<a href="{{route('admin.dashboard')}}" class="header-logo">
						<img src="{{asset('build/assets/images/brand-logos/desktop-logo.png')}}" alt="logo" class="desktop-logo">
						<img src="{{asset('build/assets/images/brand-logos/toggle-logo.png')}}" alt="logo" class="toggle-logo">
						<img src="{{asset('build/assets/images/brand-logos/desktop-dark.png')}}" alt="logo" class="desktop-dark">
						<img src="{{asset('build/assets/images/brand-logos/toggle-dark.png')}}" alt="logo" class="toggle-dark">
						<img src="{{asset('build/assets/images/brand-logos/desktop-white.png')}}" alt="logo" class="desktop-white">
					</a>
				</div>
				<!-- End::main-sidebar-header -->

				<!-- Start::main-sidebar -->
				<div class="main-sidebar" id="sidebar-scroll">

					<!-- Start::nav -->
					<nav class="main-menu-container nav nav-pills flex-column sub-open">
						<div class="slide-left" id="slide-left"><svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24"
								height="24" viewBox="0 0 24 24">
								<path d="M13.293 6.293 7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z"></path>
							</svg></div>
						<ul class="main-menu">
							<!-- Start::slide__category -->
							<a href="{{route('admin.dashboard')}}"><li class="slide__category"><span class="category-name">Dashboard</span></li></a>
							<li class="slide__category"><span class="category-name">Main</span></li>
							<!-- End::slide__category -->

							<li class="slide has-sub">
								<a href="javascript:void(0);" class="side-menu__item">
									<svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24" ><path d="M0 0h24v24H0V0z" fill="none"/><path d="M5 5h4v6H5zm10 8h4v6h-4zM5 17h4v2H5zM15 5h4v2h-4z" opacity=".3"/><path d="M3 13h8V3H3v10zm2-8h4v6H5V5zm8 16h8V11h-8v10zm2-8h4v6h-4v-6zM13 3v6h8V3h-8zm6 4h-4V5h4v2zM3 21h8v-6H3v6zm2-4h4v2H5v-2z"/></svg>
									<span class="side-menu__label">Master Management</span>
									<i class="fe fe-chevron-right side-menu__angle"></i>
								</a>
								<ul class="slide-menu child1">
									<li class="slide">
										<a href="{{route('admin.country.index')}}" class="side-menu__item">Countries</a>
									</li>
									<li class="slide">
										<a href="{{route('admin.cab.index')}}" class="side-menu__item">Cabs</a>
									</li>
									<li class="slide">
										<a href="{{route('admin.state.index')}}" class="side-menu__item">Destinations</a>
									</li>
									<li class="slide">
										<a href="{{route('admin.destination_wise_season')}}" class="side-menu__item">Destination Wise Season</a>
									</li>
									<li class="slide">
										<a href="{{route('admin.division.index')}}" class="side-menu__item">Divisions</a>
									</li>
									<li class="slide">
										<a href="{{route('admin.master.trip_preference_form')}}" class="side-menu__item">Trip Preference Form</a>
									</li>
								</ul>
							</li>
							<!-- Start::slide -->
							<li class="slide has-sub {{ request()->is('admin/employee*') 
                            ? 'open active' 
                            : '' }}">
								<a href="javascript:void(0);" class="side-menu__item">
									<svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24" ><path d="M0 0h24v24H0V0z" fill="none"/><path d="M5 9h14V5H5v4zm2-3.5c.83 0 1.5.67 1.5 1.5S7.83 8.5 7 8.5 5.5 7.83 5.5 7 6.17 5.5 7 5.5zM5 19h14v-4H5v4zm2-3.5c.83 0 1.5.67 1.5 1.5s-.67 1.5-1.5 1.5-1.5-.67-1.5-1.5.67-1.5 1.5-1.5z" opacity=".3"/><path d="M20 13H4c-.55 0-1 .45-1 1v6c0 .55.45 1 1 1h16c.55 0 1-.45 1-1v-6c0-.55-.45-1-1-1zm-1 6H5v-4h14v4zm-12-.5c.83 0 1.5-.67 1.5-1.5s-.67-1.5-1.5-1.5-1.5.67-1.5 1.5.67 1.5 1.5 1.5zM20 3H4c-.55 0-1 .45-1 1v6c0 .55.45 1 1 1h16c.55 0 1-.45 1-1V4c0-.55-.45-1-1-1zm-1 6H5V5h14v4zM7 8.5c.83 0 1.5-.67 1.5-1.5S7.83 5.5 7 5.5 5.5 6.17 5.5 7 6.17 8.5 7 8.5z"/></svg>
									<span class="side-menu__label">Employee Management</span>
									<i class="fe fe-chevron-right side-menu__angle"></i>
								</a>
								<ul class="slide-menu child1 {{ request()->is('admin/employee*') ? 'active' : '' }}">
									<li class="slide side-menu__label1">
										<a href="javascript:void(0);">Menu Levels</a>
									</li>
									<li class="slide {{ request()->is('admin/employee*')? 'active' : '' }}">
										<a href="{{route('admin.employee.index')}}" class="side-menu__item">Employee List</a>
									</li>
									<li class="slide {{ request()->is('admin/employee/designations*')? 'active' : '' }}">
										<a href="{{route('admin.designation.index')}}" class="side-menu__item">Designations</a>
									</li>
									<li class="slide {{ request()->is('admin/employee/hierarchy*')? 'active' : '' }}">
										<a href="{{route('admin.employee-hierarchy')}}" class="side-menu__item">Employee Hierarchy</a>
									</li>
								</ul>
							</li>
							<li class="slide has-sub {{ request()->is('admin/leads*') 
                            ? 'open active' 
                            : '' }}">
								<a href="javascript:void(0);" class="side-menu__item">
									<svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24" ><path d="M0 0h24v24H0V0z" fill="none"/><path d="M5 9h14V5H5v4zm2-3.5c.83 0 1.5.67 1.5 1.5S7.83 8.5 7 8.5 5.5 7.83 5.5 7 6.17 5.5 7 5.5zM5 19h14v-4H5v4zm2-3.5c.83 0 1.5.67 1.5 1.5s-.67 1.5-1.5 1.5-1.5-.67-1.5-1.5.67-1.5 1.5-1.5z" opacity=".3"/><path d="M20 13H4c-.55 0-1 .45-1 1v6c0 .55.45 1 1 1h16c.55 0 1-.45 1-1v-6c0-.55-.45-1-1-1zm-1 6H5v-4h14v4zm-12-.5c.83 0 1.5-.67 1.5-1.5s-.67-1.5-1.5-1.5-1.5.67-1.5 1.5.67 1.5 1.5 1.5zM20 3H4c-.55 0-1 .45-1 1v6c0 .55.45 1 1 1h16c.55 0 1-.45 1-1V4c0-.55-.45-1-1-1zm-1 6H5V5h14v4zM7 8.5c.83 0 1.5-.67 1.5-1.5S7.83 5.5 7 5.5 5.5 6.17 5.5 7 6.17 8.5 7 8.5z"/></svg>
									<span class="side-menu__label">Lead Management</span>
									<i class="fe fe-chevron-right side-menu__angle"></i>
								</a>
								<ul class="slide-menu child1 {{ request()->is('admin/leads*') ? 'active' : '' }}">
									<li class="slide side-menu__label1">
										<a href="javascript:void(0);">Menu Levels</a>
									</li>
									<li class="slide {{ request()->is('admin/leads*')? 'active' : '' }}">
										<a href="{{route('admin.leads.index')}}" class="side-menu__item">All Leads</a>
									</li>
									<li class="slide {{ request()->is('admin/leads/confirmed*')? 'active' : '' }}">
										<a href="{{route('admin.leads.confirmed.index')}}" class="side-menu__item">Confirmed Leads</a>
									</li>
								</ul>
							</li>
							<li class="slide has-sub {{ request()->is('admin/leads*') 
                            ? 'open active' 
                            : '' }}">
								<a href="javascript:void(0);" class="side-menu__item">
									<svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24" ><path d="M0 0h24v24H0V0z" fill="none"/><path d="M5 9h14V5H5v4zm2-3.5c.83 0 1.5.67 1.5 1.5S7.83 8.5 7 8.5 5.5 7.83 5.5 7 6.17 5.5 7 5.5zM5 19h14v-4H5v4zm2-3.5c.83 0 1.5.67 1.5 1.5s-.67 1.5-1.5 1.5-1.5-.67-1.5-1.5.67-1.5 1.5-1.5z" opacity=".3"/><path d="M20 13H4c-.55 0-1 .45-1 1v6c0 .55.45 1 1 1h16c.55 0 1-.45 1-1v-6c0-.55-.45-1-1-1zm-1 6H5v-4h14v4zm-12-.5c.83 0 1.5-.67 1.5-1.5s-.67-1.5-1.5-1.5-1.5.67-1.5 1.5.67 1.5 1.5 1.5zM20 3H4c-.55 0-1 .45-1 1v6c0 .55.45 1 1 1h16c.55 0 1-.45 1-1V4c0-.55-.45-1-1-1zm-1 6H5V5h14v4zM7 8.5c.83 0 1.5-.67 1.5-1.5S7.83 5.5 7 5.5 5.5 6.17 5.5 7 6.17 8.5 7 8.5z"/></svg>
									<span class="side-menu__label">Whatsapp Campaigns</span>
									<i class="fe fe-chevron-right side-menu__angle"></i>
								</a>
								<ul class="slide-menu child1 {{ request()->is('admin/whatsapp-campaign*') ? 'active' : '' }}">
									<li class="slide {{ request()->is('admin/whatsapp-campaign*')? 'active' : '' }}">
										<a href="{{route('admin.whatsapp.campaign.index')}}" class="side-menu__item">Dashboard</a>
									</li>
									<li class="slide {{ request()->is('admin/leads/confirmed*')? 'active' : '' }}">
										<a href="{{route('admin.leads.confirmed.index')}}" class="side-menu__item">Whatsapp Templates</a>
									</li>
								</ul>
							</li>
							<li class="slide has-sub {{ request()->is('admin/hotel*') 
                            ? 'open active' 
                            : '' }}">
								<a href="javascript:void(0);" class="side-menu__item">
									<svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24" ><path d="M0 0h24v24H0V0z" fill="none"/><path d="M5 9h14V5H5v4zm2-3.5c.83 0 1.5.67 1.5 1.5S7.83 8.5 7 8.5 5.5 7.83 5.5 7 6.17 5.5 7 5.5zM5 19h14v-4H5v4zm2-3.5c.83 0 1.5.67 1.5 1.5s-.67 1.5-1.5 1.5-1.5-.67-1.5-1.5.67-1.5 1.5-1.5z" opacity=".3"/><path d="M20 13H4c-.55 0-1 .45-1 1v6c0 .55.45 1 1 1h16c.55 0 1-.45 1-1v-6c0-.55-.45-1-1-1zm-1 6H5v-4h14v4zm-12-.5c.83 0 1.5-.67 1.5-1.5s-.67-1.5-1.5-1.5-1.5.67-1.5 1.5.67 1.5 1.5 1.5zM20 3H4c-.55 0-1 .45-1 1v6c0 .55.45 1 1 1h16c.55 0 1-.45 1-1V4c0-.55-.45-1-1-1zm-1 6H5V5h14v4zM7 8.5c.83 0 1.5-.67 1.5-1.5S7.83 5.5 7 5.5 5.5 6.17 5.5 7 6.17 8.5 7 8.5z"/></svg>
									<span class="side-menu__label">Hotel Management</span>
									<i class="fe fe-chevron-right side-menu__angle"></i>
								</a>
								<ul class="slide-menu child1 {{ request()->is('admin/hotel*') ? 'active' : '' }}">
									<li class="slide side-menu__label1">
										<a href="javascript:void(0);">Menu Levels</a>
									</li>
									<li class="slide {{ request()->is('admin/hotel*')? 'active' : '' }}">
										<a href="{{route('admin.hotel.index')}}" class="side-menu__item">All Hotels</a>
									</li>
									<li class="slide {{ request()->is('admin/non-service-hotels*')? 'active' : '' }}">
										<a href="{{route('admin.non-service-hotels.index')}}" class="side-menu__item">Non Service Hotels</a>
									</li>
									<li class="slide {{ request()->is('admin/hotel/division-wise-hotel-priority*')? 'active' : '' }}">
										<a href="{{route('admin.hotel.division_wise_hotel_priority')}}" class="side-menu__item">Division Wise Hotels</a>
									</li>
									<li class="slide {{ request()->is('admin/price-chart*')? 'active' : '' }}">
										<a href="{{route('admin.hotel.price_chart')}}" class="side-menu__item">Hotel Price Chart</a>
									</li>
									<li class="slide" {{ request()->is('admin/category*')? 'active' : '' }}>
										<a href="{{route('admin.category.index')}}" class="side-menu__item">Hotel Categories</a>
									</li> 
									<li class="slide">
										<a href="{{route('admin.room.category.index')}}" class="side-menu__item">Room Categories</a>
									</li>
									<li class="slide">
										<a href="{{route('admin.ammenity.index')}}" class="side-menu__item">Hotel Ammenity</a>
									</li>
									{{-- <li class="slide">
										<a href="javascript:void(0);" class="side-menu__item">Hotel Meal Plans</a>
									</li> --}}
									<li class="slide {{ request()->is('admin/hotel-seasion-plan')? 'active' : '' }}">
										<a href="{{route('admin.hotel_seasion_plan')}}" class="side-menu__item">Hotel Seasion Plans</a>
									</li>
								</ul>
							</li>

							<li class="slide has-sub {{ request()->is('admin/inventories*') 
                            ? 'open active' 
                            : '' }}">
								<a href="javascript:void(0);" class="side-menu__item">
									<svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24" ><path d="M0 0h24v24H0V0z" fill="none"/><path d="M5 9h14V5H5v4zm2-3.5c.83 0 1.5.67 1.5 1.5S7.83 8.5 7 8.5 5.5 7.83 5.5 7 6.17 5.5 7 5.5zM5 19h14v-4H5v4zm2-3.5c.83 0 1.5.67 1.5 1.5s-.67 1.5-1.5 1.5-1.5-.67-1.5-1.5.67-1.5 1.5-1.5z" opacity=".3"/><path d="M20 13H4c-.55 0-1 .45-1 1v6c0 .55.45 1 1 1h16c.55 0 1-.45 1-1v-6c0-.55-.45-1-1-1zm-1 6H5v-4h14v4zm-12-.5c.83 0 1.5-.67 1.5-1.5s-.67-1.5-1.5-1.5-1.5.67-1.5 1.5.67 1.5 1.5 1.5zM20 3H4c-.55 0-1 .45-1 1v6c0 .55.45 1 1 1h16c.55 0 1-.45 1-1V4c0-.55-.45-1-1-1zm-1 6H5V5h14v4zM7 8.5c.83 0 1.5-.67 1.5-1.5S7.83 5.5 7 5.5 5.5 6.17 5.5 7 6.17 8.5 7 8.5z"/></svg>
									<span class="side-menu__label">Inventory Management</span>
									<i class="fe fe-chevron-right side-menu__angle"></i>
								</a>
								<ul class="slide-menu child1 {{ request()->is('admin/inventory*') ? 'active' : '' }}">
									<li class="slide side-menu__label1">
										<a href="javascript:void(0);">Inventory</a>
									</li>
									<li class="slide {{ request()->is('admin/inventory*')? 'active' : '' }}">
										<a href="{{route('admin.inventory.index')}}" class="side-menu__item">Inventories</a>
									</li>
									<li class="slide {{ request()->is('admin/inventory/hotel-booking-request*')? 'active' : '' }}">
										<a href="{{route('admin.inventory.hotel-booking-request')}}" class="side-menu__item">Hotel Booking Request</a>
									</li>
								</ul>
							</li>
							<li class="slide has-sub {{ request()->is('admin/route*') 
                            ? 'open active' 
                            : '' }}">
								<a href="javascript:void(0);" class="side-menu__item">
									<svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24" ><path d="M0 0h24v24H0V0z" fill="none"/><path d="M5 9h14V5H5v4zm2-3.5c.83 0 1.5.67 1.5 1.5S7.83 8.5 7 8.5 5.5 7.83 5.5 7 6.17 5.5 7 5.5zM5 19h14v-4H5v4zm2-3.5c.83 0 1.5.67 1.5 1.5s-.67 1.5-1.5 1.5-1.5-.67-1.5-1.5.67-1.5 1.5-1.5z" opacity=".3"/><path d="M20 13H4c-.55 0-1 .45-1 1v6c0 .55.45 1 1 1h16c.55 0 1-.45 1-1v-6c0-.55-.45-1-1-1zm-1 6H5v-4h14v4zm-12-.5c.83 0 1.5-.67 1.5-1.5s-.67-1.5-1.5-1.5-1.5.67-1.5 1.5.67 1.5 1.5 1.5zM20 3H4c-.55 0-1 .45-1 1v6c0 .55.45 1 1 1h16c.55 0 1-.45 1-1V4c0-.55-.45-1-1-1zm-1 6H5V5h14v4zM7 8.5c.83 0 1.5-.67 1.5-1.5S7.83 5.5 7 5.5 5.5 6.17 5.5 7 6.17 8.5 7 8.5z"/></svg>
									<span class="side-menu__label">Route Management</span>
									<i class="fe fe-chevron-right side-menu__angle"></i>
								</a>
								<ul class="slide-menu child1 {{ request()->is('admin/route*') ? 'active' : '' }}">
										<li class="slide {{ request()->is('admin/route/division-wise-cab*')? 'active' : '' }}">
											<a href="{{route('admin.route.division_wise_cab_list')}}" class="side-menu__item">Division Wise Cabs</a>
										</li>
										<li class="slide {{ request()->is('admin/route/division-wise-activities*')? 'active' : '' }}">
											<a href="{{route('admin.route.division_wise_activity_list')}}" class="side-menu__item">Division Wise Activities</a>
										</li>
										<li class="slide {{ request()->is('admin/route/division-wise-sightseeings*')? 'active' : '' }}">
											<a href="{{route('admin.route.division_wise_sightseeing_list')}}" class="side-menu__item">Division Wise Sightseeings</a>
										</li>
										<li class="slide {{ request()->is('admin/route/destination-wise-route-list*')? 'active' : '' }}">
											<a href="{{route('admin.route.destination_wise_route_list')}}" class="side-menu__item">Destination Wish Routes</a>
										</li>
										<li class="slide {{ request()->is('admin/route/all-services*') ? 'active' : '' }}">
											<a href="{{ route('admin.route.all_services') }}" class="side-menu__item">
												<span class="side-menu__label">All Routes & Services</span>
											</a>
										</li>
								</ul>
							</li>
							<li class="slide has-sub {{ request()->is('admin/itinerary*') 
                            ? 'open active' 
                            : '' }}">
								<a href="javascript:void(0);" class="side-menu__item">
									<svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24" ><path d="M0 0h24v24H0V0z" fill="none"/><path d="M5 9h14V5H5v4zm2-3.5c.83 0 1.5.67 1.5 1.5S7.83 8.5 7 8.5 5.5 7.83 5.5 7 6.17 5.5 7 5.5zM5 19h14v-4H5v4zm2-3.5c.83 0 1.5.67 1.5 1.5s-.67 1.5-1.5 1.5-1.5-.67-1.5-1.5.67-1.5 1.5-1.5z" opacity=".3"/><path d="M20 13H4c-.55 0-1 .45-1 1v6c0 .55.45 1 1 1h16c.55 0 1-.45 1-1v-6c0-.55-.45-1-1-1zm-1 6H5v-4h14v4zm-12-.5c.83 0 1.5-.67 1.5-1.5s-.67-1.5-1.5-1.5-1.5.67-1.5 1.5.67 1.5 1.5 1.5zM20 3H4c-.55 0-1 .45-1 1v6c0 .55.45 1 1 1h16c.55 0 1-.45 1-1V4c0-.55-.45-1-1-1zm-1 6H5V5h14v4zM7 8.5c.83 0 1.5-.67 1.5-1.5S7.83 5.5 7 5.5 5.5 6.17 5.5 7 6.17 8.5 7 8.5z"/></svg>
									<span class="side-menu__label">Itinerary Management</span>
									<i class="fe fe-chevron-right side-menu__angle"></i>
								</a>
								<ul class="slide-menu child1 {{ request()->is('admin/itinerary*') ? 'active' : '' }}">
									<li class="slide {{ request()->is('admin/itinerary/division/banners')? 'active' : '' }}">
										<a href="{{route('admin.itinerary.division.banners')}}" class="side-menu__item">Division Wise Banners</a>
									</li>
									<li class="slide {{ request()->is('admin/itinerary/template/list')? 'active' : '' }}">
										<a href="{{route('admin.itinerary.template.list')}}" class="side-menu__item">Itinerary Templates</a>
									</li>
									<li class="slide {{ request()->is('admin/itinerary/preset/list')? 'active' : '' }}">
										<a href="{{route('admin.itinerary.preset.list')}}" class="side-menu__item">Preset Itinerary List</a>
									</li>
									{{-- <li class="slide {{ request()->is('admin/itinerary/post-lead/list')? 'active' : '' }}">
										<a href="{{route('admin.itinerary.postlead.list')}}" class="side-menu__item">Post Lead Itinerary List</a>
									</li> --}}
								</ul>
							</li>
							<li class="slide has-sub {{ request()->is('admin/cost-calculator*') 
                            ? 'open active' 
                            : '' }}">
								<a href="javascript:void(0);" class="side-menu__item">
									<svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24" ><path d="M0 0h24v24H0V0z" fill="none"/><path d="M5 9h14V5H5v4zm2-3.5c.83 0 1.5.67 1.5 1.5S7.83 8.5 7 8.5 5.5 7.83 5.5 7 6.17 5.5 7 5.5zM5 19h14v-4H5v4zm2-3.5c.83 0 1.5.67 1.5 1.5s-.67 1.5-1.5 1.5-1.5-.67-1.5-1.5.67-1.5 1.5-1.5z" opacity=".3"/><path d="M20 13H4c-.55 0-1 .45-1 1v6c0 .55.45 1 1 1h16c.55 0 1-.45 1-1v-6c0-.55-.45-1-1-1zm-1 6H5v-4h14v4zm-12-.5c.83 0 1.5-.67 1.5-1.5s-.67-1.5-1.5-1.5-1.5.67-1.5 1.5.67 1.5 1.5 1.5zM20 3H4c-.55 0-1 .45-1 1v6c0 .55.45 1 1 1h16c.55 0 1-.45 1-1V4c0-.55-.45-1-1-1zm-1 6H5V5h14v4zM7 8.5c.83 0 1.5-.67 1.5-1.5S7.83 5.5 7 5.5 5.5 6.17 5.5 7 6.17 8.5 7 8.5z"/></svg>
									<span class="side-menu__label">Cost Calculator</span>
									<i class="fe fe-chevron-right side-menu__angle"></i>
								</a>
								
								<ul class="slide-menu child1 {{ request()->is('admin/cost-calculator*') ? 'active' : '' }}">
									<li class="slide {{ request()->is('admin/cost-calculator/query-list')? 'active' : '' }}">
										<a href="{{route('admin.cost_calculator.query_list')}}" class="side-menu__item">query list</a>
									</li>
									<li class="slide {{ request()->is('admin/cost-calculator/query-pdf')? 'active' : '' }}">
										<a href="{{route('admin.cost_calculator.make_pdf')}}" class="side-menu__item">query pdf</a>
									</li>
								</ul>
							</li>
						</ul>
						<div class="slide-right" id="slide-right"><svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24"> <path d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z"></path> </svg></div>
					</nav>
					<!-- End::nav -->

				</div>
				<!-- End::main-sidebar -->

			</aside>
