<div>
    <section class="itinerary-banner-section">
        <div class="background">
            @php
                $bannerSection = $template
                    ? $template->detail
                        ->where('header', 'banner_section')
                        ->where('field', 'main_banner')
                        ->first()
                    : null;
            @endphp

            @if ($bannerSection && $bannerSection->value)
                <img src="{{ asset($bannerSection->value) }}" alt="">
            @else
                <img src="{{ asset('front_assets/images/banner-bg.jpg') }}" alt="">
            @endif
        </div>

        <div class="content">
            <div class="container">
                <div class="row">
                    <div class="col-xl-8 col-lg-10 col-12">
                        <div class="banner-logo">
                            <a href="javascript:void(0)">
                                <img src="{{asset('front_assets/images/footer-logo.png')}}" alt="Logo">
                            </a>
                        </div>
                        <h1>Hello {{$lead->customer_name}}</h1>
                        <h2>Here Is Your Dream Itinerary</h2>
                        <div class="duration">
                            <span class="text">Refreshing</span>
                            <span class="time"><img src="{{asset('front_assets/icons/clock-white.png')}}" alt="">{{$lead->travel_duration_text}}</span>
                        </div>
                        <div class="location">
                            <img src="{{asset('front_assets/icons/company_logo.png')}}" alt="">
                            @foreach($divisions_journeies as $journey_index=>$divisions_journey)
                                {{$divisions_journey['city_name'].'('.$divisions_journey['count'].'D)'}} @if (!$loop->last), @endif
                            @endforeach
                        </div>
                        {{-- <div class="cta-row">
                            <a href="#" class="cta">
                                <img src="{{asset('front_assets/icons/telephone-white.png')}}" alt="">
                                Call Us Now
                            </a>
                            <a href="#" class="cta-2">Send Enquiry</a>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </section>
@php
    $about_destination_slider_image = [];
@endphp
    @if($template && $template->detail)
        @php
            $great_experience_title = $template->detail->where('header','great_experience')->where('field','great_experience_title')->first();
            $great_experience_text = $template->detail->where('header','great_experience')->where('field','great_experience_text')->first();

            $great_experience_image = $template->detail->where('header','great_experience')->where('field','great_experience_image')->first();

            $great_experience_sub_details = $template->detail()
                ->where('header', 'great_experience')
                ->where('field', 'like', 'great_experience_sub_detail_%')
                ->get();
        @endphp
        <section class="itinerary-about-section">
            <div class="background-holder">
                <div class="bg-wrap">
                    <div class="bg-cloud-1">
                        <img src="{{asset('front_assets/images/cloud1.png')}}" alt="Background">
                        <div class="bg-cloud-2">
                            <img src="{{asset('front_assets/images/cloud2.png')}}" alt="Background">
                        </div>
                    </div>
                </div>
                <div class="bg-wrap-2">
                    <img src="{{asset('front_assets/images/section-2-bg-middle.png')}}" class="dot-bg dot-bg-1">
                </div>
            </div>
            <div class="content">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-7 col-12">
                            <div class="left-content">
                                <h2>For Great Experience</h2>
                                <h3><span>Travel</span> {{$great_experience_title?$great_experience_title->value:"...."}}</h3>
                                <p class="desc">{{$great_experience_text?$great_experience_text->value:"...."}}</p>
                                <ul>
                                    @foreach($great_experience_sub_details as $great_experience_sub_detail)
                                        <li>
                                            <div class="icon"><img src="{{asset('front_assets/icons/tick-circle2x.png')}}" alt="Bullet"></div>
                                            <div class="list-content">
                                                <h4>{{$great_experience_sub_detail->value}}</h4>
                                                <p>{{$great_experience_sub_detail->description}}</p>
                                            </div>
                                        </li>
                                    @endforeach
                                    
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-5 col-12">
                            @if (!empty($great_experience_image->value))
                                <div class="right-content">
                                    <img src="{{$great_experience_image->value}}" alt="">
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>
        @php
            $about_destination_title = $template->detail->where('header','about_destination')->where('field','about_destination_title')->first();
            $about_destination_text = $template->detail->where('header','about_destination')->where('field','about_destination_text')->first();

            $about_destination_image = $template->detail->where('header','about_destination')->where('field','about_destination_image')->first();
            $about_destination_slider_image = $template->detail()->where('header','about_destination')->where('field','slider_image')->get();

            $trip_highlights = $itinerary->details()
                ->where('header', 'about_destination')
                ->where('field', 'like', 'trip_highlights_%')
                ->orderBy('id','ASC')
                ->get();
        @endphp
        <section class="itinerary-highlights-section">
            <div class="background">
                <img src="" alt="">
            </div>
            <div class="content">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-6 col-12">
                            @if (!empty($about_destination_image->value))
                                <div class="left-content">
                                    <img src="{{$about_destination_image->value}}" alt="">
                                </div>
                            @endif
                        </div>
                        <div class="col-lg-6 col-12">
                            <div class="right-content">
                               <h2>{{ trim($about_destination_title->value ?? '') !== '' ? $about_destination_title->value : '...' }}</h2>
                                <p class="desc">{{ trim($about_destination_text->value ?? '') !== '' ? $about_destination_text->value : '...' }}</p>
                                <h3>Trip Highlights</h3>
                                <ul class="description-list">
                                    @foreach ($trip_highlights as $trip_highlight_item)
                                        <li>
                                            <img src="{{asset('front_assets/icons/tick-circle.png')}}" alt="">
                                            <div class="desc">{{$trip_highlight_item->value}}</div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif
    <section class="feature-tabs-content-section" wire:ignore>
        <div class="content">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <ul class="nav nav-tabs justify-content-center align-items-end feature-tabs" id="featureTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="itinerary-tab" data-bs-toggle="tab" data-bs-target="#itinerary" type="button" role="tab" aria-controls="itinerary" aria-selected="true">Itinerary</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="inclusion-tab" data-bs-toggle="tab" data-bs-target="#inclusion" type="button" role="tab" aria-controls="inclusion" aria-selected="false">Inclusion</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="exclusions-tab" data-bs-toggle="tab" data-bs-target="#exclusion" type="button" role="tab" aria-controls="exclusion" aria-selected="false">Exclusion</button>
                            </li>
                        </ul>
                        <div class="tab-content feature-tabs-content">
                            <div class="tab-pane active" id="itinerary" role="tabpanel" aria-labelledby="itinerary-tab">
                                <div class="itinerary-content">

                                    <div class="location-gallery-slider-wrap">
                                        <div class="swiper itinerary-location-gallery-slider itinerary-location-gallery-slider-1">
                                            <div class="swiper-wrapper">
                                                @foreach ($about_destination_slider_image as $slider_image_index=>$about_destination_slider_image_item)
                                                    @if($slider_image_index <= 9)
                                                        <div class="swiper-slide">
                                                            <div class="location-slide-content">
                                                                <img src="{{$about_destination_slider_image_item->value}}" alt="">
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                        <div class="itinerary-location-gallery-slider-nav">
                                            <div class="swiper-button-next itinerary-location-gallery-slider-next itinerary-location-gallery-slider1-next">
                                                <img src="{{asset('front_assets/icons/slider-angle-right.png')}}" alt="next">
                                            </div>
                                            <div class="swiper-button-prev itinerary-location-gallery-slider-prev itinerary-location-gallery-slider1-prev">
                                                <img src="{{asset('front_assets/icons/slider-angle-left.png')}}" alt="prev">
                                            </div>
                                        </div>
                                        <div class="location-gallery-thumbs">
                                            <ul class="thumbnails-list">
                                                 @foreach ($about_destination_slider_image as $slider_image_index=>$about_destination_slider_image_item)
                                                    @if($slider_image_index > 9)
                                                        <li>
                                                            <div class="thumbnail">
                                                                <img src="{{$about_destination_slider_image_item->value}}" alt="Thumbnail">
                                                            </div>
                                                        </li>
                                                    @endif
                                                @endforeach
                                                @if(count($about_destination_slider_image)>10)
                                                    <li>
                                                        <div class="thumbnail-count">
                                                            <span>+</span>
                                                            <span>{{count($about_destination_slider_image)-10}}</span>
                                                        </div>
                                                    </li>
                                                @endif
                                            </ul>
                                        </div>
                                    </div>

                                    <h2 class="text-center">Your Dream Itinerary</h2>
                                    <h2 class="text-center">Day by Day Brief</h2>
                                    
                                    <div class="content-wrap-box">
                                        <div class="accordion itinerary-content-accordion" id="itineraryAccordion1">
                                           @foreach (explode(',',$itinerary->stay_by_journey) as $division_key=> $item)
                                            @php
                                                $division = App\Models\City::findOrFail($item);
                                                $headingId = 'heading_'.$division_key;
                                                $collapseId = 'collapse_'.$division_key;
                                                $division_hotels = $division->nonServiceHotels()
                                                    ->where('category_id', $itinerary->hotel_category)
                                                    ->with('category')->get();
                                            @endphp
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="{{ $headingId }}">
                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#{{$collapseId}}" 
                                                    aria-expanded="false"
                                                    aria-controls="{{ $collapseId }}"
                                                    >
                                                        <div class="accordion-head">
                                                            <div class="day-count">Day<span>0{{$division_key+1}}</span></div>
                                                            <div class="location-name">{{$division->name}}</div>
                                                            <div class="accordion-head-info-box">
                                                                <div class="inner-info">
                                                                    <img src="{{asset('front_assets/icons/activities-white.png')}}" alt="">
                                                                    <span>1</span>
                                                                </div>
                                                                <div class="inner-info">
                                                                    <img src="{{asset('front_assets/icons/car-white.png')}}" alt="">
                                                                    <span>1</span>
                                                                </div>
                                                                <div class="inner-info">
                                                                    <img src="{{asset('front_assets/icons/bed-white.png')}}" alt="">
                                                                    <span>{{count($division->nonServiceHotels)}}</span>
                                                                </div>
                                                            </div>
                                                            <div class="accordion-status-indicator">
                                                                <img src="{{asset('front_assets/icons/up-angle-blue.png')}}" alt="Indicator">
                                                            </div>
                                                        </div>
                                                    </button>
                                                </h2>
                                                <div id="{{$collapseId }}" class="accordion-collapse collapse" aria-labelledby="{{$headingId}}" data-bs-parent="#itineraryAccordion1">
                                                    <div class="accordion-body">
                                                        {{-- @if($itinerary->hotel_category && $division_hotels->count() > 0) --}}
                                                        <h2 class="text-center">Stay Cetegory</h2>
                                                        <ul class="stay-category-tabs">
                                                            <li>
                                                                <a href="javascript:void(0)" class="active">{{ $division_hotels->first()->category->name ?? '' }}

                                                                </a>
                                                            </li>
                                                        </ul>

                                                         
                                                            
                                                        
                                                        <div class="itinerary-hospitalities">
                                                            <div class="info-header">
                                                                <label>Hotels &amp; Hospitalities</label>
                                                                <span>Total {{count($division->nonServiceHotels)}} Selected</span>
                                                            </div>
                                                            <ul class="hospitalities-list">
                                                                
                                                                @foreach ($division->nonServiceHotels as $hotels_key =>$division_hotels_item)
                                                                    <li>
                                                                    <div class="hospitality-card selected">
                                                                        <div class="img-wrap">
                                                                            <img src="{{asset($division_hotels_item->image)}}" alt="Hospitality">
                                                                        </div>
                                                                        <div class="info-wrap">
                                                                            <div class="title">{{$division_hotels_item->hotel_name ?? ''}}</div>
                                                                            <div class="address">{{$division_hotels_item->address ?? ''}}</div>
                                                                            {{-- <div class="bottom-row">
                                                                                <h4>Semi Dlx</h4>
                                                                                <span class="indicator"></span>
                                                                            </div> --}}
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                                @endforeach
                                                            </ul>
                                                        </div>
                                                        {{-- @endif --}}

                                                        <div class="inner-content-wrap-box">
                                                            <div class="heading" data-bs-toggle="collapse" data-bs-target="#stack1">
                                                                <img src="{{asset('front_assets/icons/car.png')}}" alt="">
                                                                <span>Kaziranga to Shillong Transfer</span>
                                                            </div>
                                                            <div class="content-stack collapse show" id="stack1">
                                                                <div class="divider"></div>
                                                                <div class="booking-wrap">
                                                                    <div class="grouping-list">
                                                                        <h5>Sightseeing</h5>
                                                                        <div class="grouping-stack">Kaziranga National Park <span>(1)<span></div>
                                                                        <div class="grouping-stack">Umiam Lake <span>(1)<span></div>
                                                                        <div class="grouping-stack">Police Bazar <span>(1)<span></div>
                                                                    </div>
                                                                    <div class="grouping-list">
                                                                        <h5>Activity</h5>
                                                                        <div class="grouping-stack">Jeep Safari for 1-6Pax <span>(2)<span></div>
                                                                        <div class="grouping-stack">Elephant Safari <span>(2)<span></div>
                                                                        <div class="grouping-stack">Boating at Umiam Lake <span>(2)<span></div>
                                                                    </div>
                                                                    <div class="grouping-list">
                                                                        <h5>Cab</h5>
                                                                        <div class="grouping-stack">Sedan (4S) <span>(1)<span></div>
                                                                    </div>
                                                                </div>


                                                                <div class="activities-slider-wrap">
                                                                    <div class="activities-slider-navigation">
                                                                        <div class="swiper-button-next activities-swiper-button-next">
                                                                            <img src="{{asset('front_assets/icons/slider-angle-right.png')}}" alt="next">
                                                                        </div>
                                                                        <div class="swiper-button-prev activities-swiper-button-prev">
                                                                            <img src="{{asset('front_assets/icons/slider-angle-left.png')}}" alt="prev">
                                                                        </div>
                                                                    </div>

                                                                    <div class="swiper activities-swiper">
                                                                        <div class="swiper-wrapper">

                                                                            <div class="swiper-slide" data-slide="1">
                                                                                <div class="activity-content-box">
                                                                                    <img src="{{asset('front_assets/images/activity-1.webp')}}" alt="Trip">
                                                                                    <div class="info-holder">
                                                                                        
                                                                                        <div class="price">Starting with Rs.1,29,990/-</div>
                                                                                        
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="swiper-slide" data-slide="2">
                                                                                <div class="activity-content-box">
                                                                                    <img src="{{asset('front_assets/images/activity-2.webp')}}" alt="Trip">
                                                                                    <div class="info-holder">
                                                                                       
                                                                                        <div class="price">Starting with Rs.1,29,990/-</div>
                                                                                        
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="swiper-slide" data-slide="3">
                                                                                <div class="activity-content-box">
                                                                                    <img src="{{asset('front_assets/images/activity-3.webp')}}" alt="Trip">
                                                                                    <div class="info-holder">
                                                                                        
                                                                                        <div class="price">Starting with Rs.1,29,990/-</div>
                                                                                        
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="swiper-slide" data-slide="4">
                                                                                <div class="activity-content-box">
                                                                                    <img src="{{asset('front_assets/images/activity-4.webp')}}" alt="Trip">
                                                                                    <div class="info-holder">
                                                                                        
                                                                                        <div class="price">Starting with Rs.1,29,990/-</div>
                                                                                       
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <div class="swiper-slide" data-slide="5">
                                                                                <div class="activity-content-box">
                                                                                    <img src="{{asset('front_assets/images/activity-1.webp')}}" alt="Trip">
                                                                                    <div class="info-holder">
                                                                                        
                                                                                        <div class="price">Starting with Rs.1,29,990/-</div>
                                                                                        
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="swiper-slide" data-slide="6">
                                                                                <div class="activity-content-box">
                                                                                    <img src="{{asset('front_assets/images/activity-2.webp')}}" alt="Trip">
                                                                                    <div class="info-holder">
                                                                                       
                                                                                        <div class="price">Starting with Rs.1,29,990/-</div>
                                                                                        
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="swiper-slide" data-slide="7">
                                                                                <div class="activity-content-box">
                                                                                    <img src="{{asset('front_assets/images/activity-3.webp')}}" alt="Trip">
                                                                                    <div class="info-holder">
                                                                                        
                                                                                        <div class="price">Starting with Rs.1,29,990/-</div>
                                                                                        
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="swiper-slide" data-slide="8">
                                                                                <div class="activity-content-box">
                                                                                    <img src="{{asset('front_assets/images/activity-4.webp')}}" alt="Trip">
                                                                                    <div class="info-holder">
                                                                                        
                                                                                        <div class="price">Starting with Rs.1,29,990/-</div>
                                                                                        
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                        </div>
                                                                    </div>
                                                                    
                                                                </div>

                                                                <div class="content-wrap-box" id="details1">
                                                                    <h2>slide1</h2>
                                                                    <div class="detail-content">
                                                                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident,</p>
                                                                        <p>Sunt in culpa qui officia deserunt mollit anim id est laborum. Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem.</p>
                                                                    </div>
                                                                </div>
                                                                <div class="content-wrap-box" id="details2">
                                                                    <h2>slide2</h2>
                                                                    <div class="detail-content">
                                                                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident,</p>
                                                                        <p>Sunt in culpa qui officia deserunt mollit anim id est laborum. Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem.</p>
                                                                    </div>
                                                                </div>
                                                                <div class="content-wrap-box" id="details3">
                                                                    <h2>slide3</h2>
                                                                    <div class="detail-content">
                                                                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident,</p>
                                                                        <p>Sunt in culpa qui officia deserunt mollit anim id est laborum. Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem.</p>
                                                                    </div>
                                                                </div>
                                                                <div class="content-wrap-box" id="details4">
                                                                    <h2>slide4</h2>
                                                                    <div class="detail-content">
                                                                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident,</p>
                                                                        <p>Sunt in culpa qui officia deserunt mollit anim id est laborum. Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem.</p>
                                                                    </div>
                                                                </div>
                                                                <div class="content-wrap-box" id="details5">
                                                                    <h2>slide5</h2>
                                                                    <div class="detail-content">
                                                                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident,</p>
                                                                        <p>Sunt in culpa qui officia deserunt mollit anim id est laborum. Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem.</p>
                                                                    </div>
                                                                </div>
                                                                <div class="content-wrap-box" id="details6">
                                                                    <h2>slide6</h2>
                                                                    <div class="detail-content">
                                                                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident,</p>
                                                                        <p>Sunt in culpa qui officia deserunt mollit anim id est laborum. Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem.</p>
                                                                    </div>
                                                                </div>
                                                                <div class="content-wrap-box" id="details7">
                                                                    <h2>slide7</h2>
                                                                    <div class="detail-content">
                                                                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident,</p>
                                                                        <p>Sunt in culpa qui officia deserunt mollit anim id est laborum. Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem.</p>
                                                                    </div>
                                                                </div>
                                                                <div class="content-wrap-box" id="details8">
                                                                    <h2>slide8</h2>
                                                                    <div class="detail-content">
                                                                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident,</p>
                                                                        <p>Sunt in culpa qui officia deserunt mollit anim id est laborum. Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem.</p>
                                                                    </div>
                                                                </div>



                                                            </div>
                                                        </div>

                                                        <div class="inner-content-wrap-box">
                                                            <div class="heading"  data-bs-toggle="collapse" data-bs-target="#stack2">
                                                                <img src="{{asset('front_assets/icons/car.png')}}" alt="">
                                                                <span>Shillong to Cherrapunjee Transfer</span>
                                                            </div>
                                                            <div class="content-stack collapse" id="stack2">
                                                                <div class="divider"></div>
                                                                <div class="booking-wrap">
                                                                    <div class="grouping-list">
                                                                        <h5>Sightseeing</h5>
                                                                        <div class="grouping-stack">Elephant Falls  <span>(1)<span></div>
                                                                        <div class="grouping-stack">Mawkdok Dympep ViewPoint  <span>(1)<span></div>
                                                                        <div class="grouping-stack">Nohkalikai Falls <span>(1)<span></div>
                                                                        <div class="grouping-stack">Seven Sisters Falls <span>(1)<span></div>
                                                                        <div class="grouping-stack">Mawsmai Cave <span>(1)<span></div>
                                                                        <div class="grouping-stack">Arwah Cave <span>(1)<span></div>
                                                                    </div>
                                                                    <div class="grouping-list">
                                                                        <h5>Activity</h5>
                                                                        <div class="grouping-stack">Jeep Safari for 1-6Pax <span>(2)<span></div>
                                                                        <div class="grouping-stack">Elephant Safari <span>(2)<span></div>
                                                                        <div class="grouping-stack">Boating at Umiam Lake <span>(2)<span></div>
                                                                    </div>
                                                                    <div class="grouping-list">
                                                                        <h5>Cab</h5>
                                                                        <div class="grouping-stack">Sedan (4S) <span>(1)<span></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                           @endforeach
                                            

                                            {{-- <div class="accordion-item">
                                                <h2 class="accordion-header" id="headingTwo">
                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                                        <div class="accordion-head">
                                                            <div class="day-count">Day<span>02</span></div>
                                                            <div class="location-name">Travamet</div>
                                                            <div class="accordion-head-info-box">
                                                                <div class="inner-info">
                                                                    <img src="{{asset('front_assets/icons/activities-white.png')}}" alt="">
                                                                    <span>1</span>
                                                                </div>
                                                                <div class="inner-info">
                                                                    <img src="{{asset('front_assets/icons/car-white.png')}}" alt="">
                                                                    <span>1</span>
                                                                </div>
                                                                <div class="inner-info">
                                                                    <img src="{{asset('front_assets/icons/bed-white.png')}}" alt="">
                                                                    <span>3</span>
                                                                </div>
                                                            </div>
                                                            <div class="accordion-status-indicator">
                                                                <img src="{{asset('front_assets/icons/up-angle-blue.png')}}" alt="Indicator">
                                                            </div>
                                                        </div>
                                                    </button>
                                                </h2>
                                                <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#itineraryAccordion1">
                                                    <div class="accordion-body">
                                                        <strong>This is the second item's accordion body.</strong> It is hidden by default, until the collapse plugin adds the appropriate classes that we use to style each element. These classes control the overall appearance, as well as the showing and hiding via CSS transitions. You can modify any of this with custom CSS or overriding our default variables. It's also worth noting that just about any HTML can go within the <code>.accordion-body</code>, though the transition does limit overflow.
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="headingThree">
                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                                        <div class="accordion-head">
                                                            <div class="day-count">Day<span>03</span></div>
                                                            <div class="location-name">Ipsumol</div>
                                                            <div class="accordion-head-info-box">
                                                                <div class="inner-info">
                                                                    <img src="{{asset('front_assets/icons/activities-white.png')}}" alt="">
                                                                    <span>1</span>
                                                                </div>
                                                                <div class="inner-info">
                                                                    <img src="{{asset('front_assets/icons/car-white.png')}}" alt="">
                                                                    <span>1</span>
                                                                </div>
                                                                <div class="inner-info">
                                                                    <img src="{{asset('front_assets/icons/bed-white.png')}}" alt="">
                                                                    <span>3</span>
                                                                </div>
                                                            </div>
                                                            <div class="accordion-status-indicator">
                                                                <img src="{{asset('front_assets/icons/up-angle-blue.png')}}" alt="Indicator">
                                                            </div>
                                                        </div>
                                                    </button>
                                                </h2>
                                                <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#itineraryAccordion1">
                                                    <div class="accordion-body">
                                                        <strong>This is the third item's accordion body.</strong> It is hidden by default, until the collapse plugin adds the appropriate classes that we use to style each element. These classes control the overall appearance, as well as the showing and hiding via CSS transitions. You can modify any of this with custom CSS or overriding our default variables. It's also worth noting that just about any HTML can go within the <code>.accordion-body</code>, though the transition does limit overflow.
                                                    </div>
                                                </div>
                                            </div> --}}

                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane" id="inclusion" role="tabpanel" aria-labelledby="inclusion-tab">
                                <div class="inclusion-content">
                                    @php
                                        $inclusions = $itinerary->details()
                                        ->where('header', 'inclusion_exclusions')
                                        ->where('field', 'like', 'inclusions_%')
                                        ->orderBy('id','ASC')
                                        ->get();
                                    @endphp
                                    <div class="inner-content-wrap-box">
                                        <div class="heading">
                                            <span>All Inclusion:</span>
                                        </div>
                                        <div class="divider"></div>

                                        <ul class="inclusion-exclusion-list">
                                            @foreach ($inclusions as $inclusion_item)
                                                <li>
                                                    <img src="{{asset('front_assets/icons/circled-tick-blue.png')}}" alt="Tick">
                                                    <div class="desc">{{$inclusion_item->value}}</div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane" id="exclusion" role="tabpanel" aria-labelledby="exclusion-tab">
                                <div class="exclusion-content">
                                     @php
                                        $exclusions = $itinerary->details()
                                        ->where('header', 'inclusion_exclusions')
                                        ->where('field', 'like', 'exclusions_%')
                                        ->orderBy('id','ASC')
                                        ->get();
                                    @endphp
                                    <div class="inner-content-wrap-box">
                                        <div class="heading">
                                            <span>All Exclusion:</span>
                                        </div>
                                        <div class="divider"></div>

                                        <ul class="inclusion-exclusion-list">
                                            @foreach ($exclusions as $exclusion_item)
                                                <li>
                                                     <img src="{{asset('front_assets/icons/circled-cross-red.png')}}" alt="Tick">
                                                    <div class="desc">{{$exclusion_item->value}}</div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- <div class="two-sections-background">
        <div class="background">
            <img src="{{asset('front_assets/images/section-2-bg-last.png')}}" alt="">
        </div>
        <section class="campaign-section">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="campaign-box">
                            <a href="javascript:void(0)">
                                <img src="{{asset('front_assets/images/campaign-banner-4.webp')}}" alt="Campaign">
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="itinerary-special-experience-section">
            <div class="container">
                <din class="row">
                    <div class="col-lg-12">
                        <h2 class="text-center">Special Experience Shillong &amp; Cherrapunjee</h2>

                        <div class="special-experience-slider-wrap">
                            <div class="special-experience-navigation">
                                <div class="swiper-button-next special-experience-swiper-button-next">
                                    <img src="{{asset('front_assets/icons/slider-angle-right.png')}}" alt="next">
                                </div>
                                <div class="swiper-button-prev special-experience-swiper-button-prev">
                                    <img src="{{asset('front_assets/icons/slider-angle-left.png')}}" alt="prev">
                                </div>
                            </div>

                            <div class="swiper special-experience-swiper">
                                <div class="swiper-wrapper">

                                    <div class="swiper-slide">
                                        <div class="activity-content-box">
                                            <img src="{{asset('front_assets/images/activity-1.webp')}}" alt="Trip">
                                            <div class="info-holder">
                                                <div class="title">Lorem Ipsum</div>
                                                <div class="price">Starting with Rs.1,29,990/-</div>
                                                <a href="#" class="cta">
                                                    <img src="{{asset('front_assets/icons/telephone-white.png')}}" alt="">
                                                    Call To Know More
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="activity-content-box">
                                            <img src="{{asset('front_assets/images/activity-2.webp')}}" alt="Trip">
                                            <div class="info-holder">
                                                <div class="title">Lorem Ipsum</div>
                                                <div class="price">Starting with Rs.1,29,990/-</div>
                                                <a href="#" class="cta">
                                                    <img src="{{asset('front_assets/icons/telephone-white.png')}}" alt="">
                                                    Call To Know More
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="activity-content-box">
                                            <img src="{{asset('front_assets/images/activity-3.webp')}}" alt="Trip">
                                            <div class="info-holder">
                                                <div class="title">Lorem Ipsum</div>
                                                <div class="price">Starting with Rs.1,29,990/-</div>
                                                <a href="#" class="cta">
                                                    <img src="{{asset('front_assets/icons/telephone-white.png')}}" alt="">
                                                    Call To Know More
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="activity-content-box">
                                            <img src="{{asset('front_assets/images/activity-4.webp')}}" alt="Trip">
                                            <div class="info-holder">
                                                <div class="title">Lorem Ipsum</div>
                                                <div class="price">Starting with Rs.1,29,990/-</div>
                                                <a href="#" class="cta">
                                                    <img src="{{asset('front_assets/icons/telephone-white.png')}}" alt="">
                                                    Call To Know More
                                                </a>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="swiper-slide">
                                        <div class="activity-content-box">
                                            <img src="{{asset('front_assets/images/activity-1.webp')}}" alt="Trip">
                                            <div class="info-holder">
                                                <div class="title">Lorem Ipsum</div>
                                                <div class="price">Starting with Rs.1,29,990/-</div>
                                                <a href="#" class="cta">
                                                    <img src="{{asset('front_assets/icons/telephone-white.png')}}" alt="">
                                                    Call To Know More
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="activity-content-box">
                                            <img src="{{asset('front_assets/images/activity-2.webp')}}" alt="Trip">
                                            <div class="info-holder">
                                                <div class="title">Jungle Safari</div>
                                                <div class="price">Starting with Rs.1,29,990/-</div>
                                                <a href="#" class="cta">
                                                    <img src="{{asset('front_assets/icons/telephone-white.png')}}" alt="">
                                                    Call To Know More
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="activity-content-box">
                                            <img src="{{asset('front_assets/images/activity-3.webp')}}" alt="Trip">
                                            <div class="info-holder">
                                                <div class="title">Lorem Ipsum</div>
                                                <div class="price">Starting with Rs.1,29,990/-</div>
                                                <a href="#" class="cta">
                                                    <img src="{{asset('front_assets/icons/telephone-white.png')}}" alt="">
                                                    Call To Know More
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="activity-content-box">
                                            <img src="{{asset('front_assets/images/activity-4.webp')}}" alt="Trip">
                                            <div class="info-holder">
                                                <div class="title">Lorem Ipsum</div>
                                                <div class="price">Starting with Rs.1,29,990/-</div>
                                                <a href="#" class="cta">
                                                    <img src="{{asset('front_assets/icons/telephone-white.png')}}" alt="">
                                                    Call To Know More
                                                </a>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            
                        </div>

                    </div>
                </din>
            </div>
        </section>
    </div> --}}

    {{-- <div class="two-sections-background-2">
        <div class="background">
            <img src="{{asset('front_assets/images/section-2-bg-middle.png')}}" alt="">
        </div>
        <section class="itinerary-payment-policy-section">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <h2 class="text-center">Payment Policy</h2>
                        <ul class="payment-policy-list">
                            <li>
                                <div class="content-box">
                                    <div class="icon-holder">
                                        <img src="{{asset('front_assets/icons/booking-white.png')}}" alt="Icon">
                                    </div>
                                    <div class="desc">
                                        Booking
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="content-box">
                                    <div class="icon-holder">
                                        <img src="{{asset('front_assets/icons/rescheduling-white.png')}}" alt="Icon">
                                    </div>
                                    <div class="desc">
                                        Rescheduling
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="content-box">
                                    <div class="icon-holder">
                                        <img src="{{asset('front_assets/icons/cancellation-white.png')}}" alt="Icon">
                                    </div>
                                    <div class="desc">
                                        Cancellation
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <section class="itinerary-popular-places-gallery-section">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <h2 class="text-center">Popular Places Image Gallery</h2>

                        <div class="popular-places-gallery-blocks">
                            <div class="gallery-block">
                                <img src="{{asset('front_assets/images/detail-banner-img-1.webp')}}" alt="Location">
                                <div class="block-content">
                                    <div class="title">Lorem ipsum dolor sit amet</div>
                                    <p class="desc">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore</p>
                                </div>
                            </div>
                            <div class="gallery-block">
                                <img src="{{asset('front_assets/images/detail-banner-img-2.webp')}}" alt="Location">
                                <div class="block-content">
                                    <div class="title">Lorem ipsum dolor</div>
                                    <p class="desc">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod</p>
                                </div>
                            </div>
                            <div class="gallery-block">
                                <img src="{{asset('front_assets/images/detail-banner-img-3.webp')}}" alt="Location">
                                <div class="block-content">
                                    <div class="title">Lorem ipsum dolor</div>
                                    <p class="desc">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod</p>
                                </div>
                            </div>
                            <div class="gallery-block">
                                <img src="{{asset('front_assets/images/detail-banner-img-4.webp')}}" alt="Location">
                                <div class="block-content">
                                    <div class="title">Lorem ipsum dolor</div>
                                    <p class="desc">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod</p>
                                </div>
                            </div>
                            <div class="gallery-block">
                                <img src="{{asset('front_assets/images/detail-banner-img-5.webp')}}" alt="Location">
                                <div class="block-content">
                                    <div class="title">Lorem ipsum dolor</div>
                                    <p class="desc">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod</p>
                                </div>
                            </div>
                        </div>

                        <div class="cta-block"><a href="javascript:void(0)" class="cta">Explore All</a></div>

                    </div>
                </div>
            </div>
        </section>
    </div> --}}

    {{-- <section class="itinerary-reviews-section">
        <div class="background">
            <img src="{{asset('front_assets/images/map-bg.png')}}" alt="">
        </div>
        <div class="content">
            <div class="container">
                <div class="row ">
                    <div class="col-lg-12">
                        <h2 class="text-center">Reviews(15,494)</h2>
                    </div>
                </div>
                <div class="row info-content-row">
                    <div class="col-lg-4 col-12">
                        <div class="content-left">
                            <img src="{{asset('front_assets/icons/rating-stars.png')}}" alt="" class="rating-img">
                            <div class="rating-value">4.8</div>
                            <div class="customers-count">From 35k+ Customers</div>
                        </div>
                    </div>
                    <div class="col-lg-8 col-12">
                        <div class="content-right">
                            <ul class="ratings-list">
                                <li>
                                    <span class="rating-value">5</span>
                                    <span class="rating-star"><img src="{{asset('front_assets/icons/yellow-star.png')}}" alt="Star"></span>
                                    <div class="progress">
                                        <div class="progress-bar" role="progressbar" style="width: 90%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <span class="rating-count">20.6k</span>
                                </li>
                                <li>
                                    <span class="rating-value">4</span>
                                    <span class="rating-star"><img src="{{asset('front_assets/icons/yellow-star.png')}}" alt="Star"></span>
                                    <div class="progress">
                                        <div class="progress-bar" role="progressbar" style="width: 40%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <span class="rating-count">12.6k</span>
                                </li>
                                <li>
                                    <span class="rating-value">3</span>
                                    <span class="rating-star"><img src="{{asset('front_assets/icons/yellow-star.png')}}" alt="Star"></span>
                                    <div class="progress">
                                        <div class="progress-bar" role="progressbar" style="width: 5%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <span class="rating-count">1k</span>
                                </li>
                                <li>
                                    <span class="rating-value">2</span>
                                    <span class="rating-star"><img src="{{asset('front_assets/icons/yellow-star.png')}}" alt="Star"></span>
                                    <div class="progress">
                                        <div class="progress-bar" role="progressbar" style="width: 18%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <span class="rating-count">3.6k</span>
                                </li>
                                <li>
                                    <span class="rating-value">1</span>
                                    <span class="rating-star"><img src="{{asset('front_assets/icons/yellow-star.png')}}" alt="Star"></span>
                                    <div class="progress">
                                        <div class="progress-bar" role="progressbar" style="width: 0%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <span class="rating-count">0.6k</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="row feedback-row">
                    <div class="col-12">
                        <h2 class="text-center">Customer Feedback</h2>

                        <div class="customer-feedback-box">
                            <div class="info-row">
                                <div class="content-left">
                                    <div class="profile-pic">
                                        <img src="{{asset('front_assets/images/reviewer.png')}}" alt="">
                                    </div>
                                    <div class="person-info">
                                        <div class="name">Budi Sudarsono</div>
                                        <div class="designation">Homemaker</div>
                                    </div>
                                </div>
                                <div class="content-right">
                                    <img src="{{asset('front_assets/icons/yellow-star.png')}}" alt="Star">
                                    <span class="rating-value">4.8</span>
                                </div>
                            </div>
                            <div class="booking-info-row">
                                <span>Booked:&nbsp;</span>
                                Gems of Bali &amp; Singapore | Free Balinese Massage
                            </div>

                            <div class="description">
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                            </div>

                            <ul class="shared-pics-list">
                                <li>
                                    <div class="img-wrap">
                                        <img src="{{asset('front_assets/images/review-thumb-1.webp')}}" alt="">
                                    </div>
                                </li>
                                <li>
                                    <div class="img-wrap">
                                        <img src="{{asset('front_assets/images/review-thumb-2.webp')}}" alt="">
                                    </div>
                                </li>
                                <li>
                                    <div class="img-wrap">
                                        <img src="{{asset('front_assets/images/review-thumb-3.webp')}}" alt="">
                                    </div>
                                </li>
                                <li>
                                    <div class="img-wrap">
                                        <img src="{{asset('front_assets/images/review-thumb-4.webp')}}" alt="">
                                    </div>
                                </li>
                                <li>
                                    <div class="img-wrap">
                                        <img src="{{asset('front_assets/images/review-thumb-5.webp')}}" alt="">
                                        <div class="more-text">+ 5 more</div>
                                    </div>
                                </li>
                            </ul>
                        </div>

                        <div class="customer-feedback-box">
                            <div class="info-row">
                                <div class="content-left">
                                    <div class="profile-pic">
                                        <img src="{{asset('front_assets/images/reviewer.png')}}" alt="">
                                    </div>
                                    <div class="person-info">
                                        <div class="name">Budi Sudarsono</div>
                                        <div class="designation">Homemaker</div>
                                    </div>
                                </div>
                                <div class="content-right">
                                    <img src="{{asset('front_assets/icons/yellow-star.png')}}" alt="Star">
                                    <span class="rating-value">4.8</span>
                                </div>
                            </div>
                            <div class="booking-info-row">
                                <span>Booked:&nbsp;</span>
                                Gems of Bali &amp; Singapore | Free Balinese Massage
                            </div>

                            <div class="description">
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                            </div>

                            <ul class="shared-pics-list">
                                <li>
                                    <div class="img-wrap">
                                        <img src="{{asset('front_assets/images/review-thumb-1.webp')}}" alt="">
                                    </div>
                                </li>
                                <li>
                                    <div class="img-wrap">
                                        <img src="{{asset('front_assets/images/review-thumb-2.webp')}}" alt="">
                                    </div>
                                </li>
                                <li>
                                    <div class="img-wrap">
                                        <img src="{{asset('front_assets/images/review-thumb-3.webp')}}" alt="">
                                    </div>
                                </li>
                                <li>
                                    <div class="img-wrap">
                                        <img src="{{asset('front_assets/images/review-thumb-4.webp')}}" alt="">
                                    </div>
                                </li>
                                <li>
                                    <div class="img-wrap">
                                        <img src="{{asset('front_assets/images/review-thumb-5.webp')}}" alt="">
                                        <div class="more-text">+ 5 more</div>
                                    </div>
                                </li>
                            </ul>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </section> --}}

    {{-- <section class="itinerary-why-choose-section">
        <div class="background">
            <div class="bg bg-2">
                <img src="{{asset('front_assets/images/section-3-bg-2.png')}}" alt="Background">
            </div>
        </div>
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <h2 class="text-center">Why Choose CarnivalTrips</h2>
                    </div>
                </div>
                <div class="row content-row">
                    <div class="col-lg-5 col-12 left-col">
                        <div class="content-left">
                            <ul>
                                <li>
                                    <span>01</span>
                                    <div class="text-wrap">
                                        <label>Lorem ipsum dolor</label>
                                        <p>Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt .</p>
                                    </div>
                                </li>
                                <li>
                                    <span>02</span>
                                    <div class="text-wrap">
                                        <label>Lorem ipsum dolor</label>
                                        <p>Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt .</p>
                                    </div>
                                </li>
                                <li>
                                    <span>03</span>
                                    <div class="text-wrap">
                                        <label>Lorem ipsum dolor</label>
                                        <p>Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt .</p>
                                    </div>
                                </li>
                                <li>
                                    <span>04</span>
                                    <div class="text-wrap">
                                        <label>Lorem ipsum dolor</label>
                                        <p>Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt .</p>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-7 col-12 right-col">
                        <div class="content-right">
                            <img src="{{asset('front_assets/images/section-3-content-img.png')}}" alt="">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section> --}}

    {{-- <section class="itinerary-statistics-section">
        <div class="background">
            <img src="{{asset('front_assets/images/listing-section-3-bg.png')}}" alt="">
        </div>
        <div class="content">
            <div class="container">
                <div class="row">
                    <div class="col-lg-3 content-col">
                        <h3 class="title">10 Million+</h3>
                        <p class="desc">Happy customers from 65+ countries all around.</p>
                    </div>
                    <div class="col-lg-3 content-col">
                        <h3 class="title">4.6 / 5.0</h3>
                        <p class="desc">Cumulative ratings of our trips across platforms.</p>
                    </div>
                    <div class="col-lg-3 content-col">
                        <h3 class="title">Curated with love</h3>
                        <p class="desc">Expert-guided trips with meticulous planning.</p>
                    </div>
                    <div class="col-lg-3 content-col">
                        <h3 class="title">24*7 Support</h3>
                        <p class="desc">We are always there to help you pre, post and on the trip.</p>
                    </div>
                </div>
            </div>
        </div>
    </section> --}}
</div>
@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // 1. Track every page click
            document.addEventListener('click', function () {
                 @this.call('incrementClick');
            });

            // 2. Track when user exits the page/tab
            window.addEventListener('beforeunload', function () {
                 @this.call('setExitTime');
            });

           // 1. Start session when visible
            if (document.visibilityState === 'visible') {
                @this.call('startNewClickLog');
            }

            // 2. Handle tab visibility change
            document.addEventListener('visibilitychange', function () {
                if (document.visibilityState === 'visible') {
                    @this.call('startNewClickLog');
                } else {
                    @this.call('closeClickLog');
                }
            });

            // 3. Update exit_time every second only if tab is active
            setInterval(function () {
                if (document.visibilityState === 'visible') {
                    @this.call('updateExitTime');
                }
            }, 1000);

        });




jQuery(document).ready(function($) {
    var activitiesSwiper = new Swiper(".activities-swiper", {
        slidesPerView: 4,
        spaceBetween: 30,
        navigation: {
            nextEl: ".activities-swiper-button-next",
            prevEl: ".activities-swiper-button-prev",
        },
        breakpoints: {
            320: {
              slidesPerView: 1
            },
            640: {
              slidesPerView: 2
            },
            800: {
              slidesPerView: 3
            },
            1025: {
              slidesPerView: 4
            },
        }
    });

    // Get all slides and details containers
    const slides = document.querySelectorAll('.swiper-slide');
    const detailsContainers = document.querySelectorAll('.content-wrap-box');
    //const closeButtons = document.querySelectorAll('.close-btn');

    // Track currently active slide
    let activeSlideId = null;

    // Function to show details for a specific slide
    function showDetails(slideId) {
        // Hide all details containers first
        detailsContainers.forEach(container => {
            container.classList.remove('active');
        });
        
        // Remove active class from all slides
        slides.forEach(slide => {
            slide.classList.remove('active');
        });
        
        // Show the selected details container
        const targetDetails = document.getElementById(`details${slideId}`);
        if (targetDetails) {
            targetDetails.classList.add('active');
            // Scroll to the details container smoothly
            // targetDetails.scrollIntoView({ 
            //     behavior: 'smooth', 
            //     block: 'start',
            //     inline: 'nearest'
            // });
        }
        
        // Mark the clicked slide as active
        const activeSlide = document.querySelector(`.swiper-slide[data-slide="${slideId}"]`);
        if (activeSlide) {
            activeSlide.classList.add('active');
        }
        
        // Update active slide ID
        activeSlideId = slideId;
    }

    // Function to close all details
    function closeAllDetails() {
        detailsContainers.forEach(container => {
            container.classList.remove('active');
        });
        
        slides.forEach(slide => {
            slide.classList.remove('active');
        });
        
        activeSlideId = null;
    }

    // Add click event to each slide
    slides.forEach(slide => {
        slide.addEventListener('click', () => {
            const slideId = slide.getAttribute('data-slide');
            
            // If clicking the same slide, close it
            if (activeSlideId === slideId) {
                closeAllDetails();
            } else {
                showDetails(slideId);
            }
        });
    });

    // Add click event to close buttons
    // closeButtons.forEach(button => {
    //     button.addEventListener('click', (e) => {
    //         e.stopPropagation(); // Prevent event bubbling
    //         closeAllDetails();
    //     });
    // });

    // Optional: Close details when clicking outside
    document.addEventListener('click', (e) => {
        // Check if click is outside details containers and not on a slide
        if (!e.target.closest('.content-wrap-box') && 
            !e.target.closest('.swiper-slide')) {
            closeAllDetails();
        }
    });

    // Optional: Close details with Escape key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            closeAllDetails();
        }
    });

    // Optional: Auto-close other details when opening a new one
    function showDetailsExclusive(slideId) {
        closeAllDetails(); // Close all first
        setTimeout(() => {
            showDetails(slideId); // Then open the selected one
        }, 50); // Small delay for smoother transition
    }

    // If you want to toggle instead (click same slide to close)
    function toggleDetails(slideId) {
        const details = document.getElementById(`details${slideId}`);
        const slide = document.querySelector(`.swiper-slide[data-slide="${slideId}"]`);
        
        if (details.classList.contains('active')) {
            // Already active, so close it
            details.classList.remove('active');
            slide.classList.remove('active');
            activeSlideId = null;
        } else {
            // Not active, so show it
            closeAllDetails();
            setTimeout(() => {
                details.classList.add('active');
                slide.classList.add('active');
                details.scrollIntoView({ behavior: 'smooth', block: 'start' });
                activeSlideId = slideId;
            }, 10);
        }
    }
    
    
    /* -------------------------------- 22-12-2025 -------------------------------- */

    document.querySelectorAll('.inner-content-wrap-box').forEach(parent => {
        const boxes = parent.querySelectorAll('.content-wrap-box');
        boxes.forEach(box => box.classList.remove('active'));
        if (boxes.length > 0) {
          boxes[0].classList.add('active');
        }
    });
    
    // document.addEventListener('click', e => {
    //     if (e.target.matches('.content-wrap-box h2')) {
    //     const box = e.target.closest('.content-wrap-box');
    //     const parent = box.closest('.inner-content-wrap-box');
    //     parent.querySelectorAll('.content-wrap-box').forEach(b => b.classList.remove('active'));
    //     box.classList.add('active');
    //     }
    // });

});
    </script>
@endsection
