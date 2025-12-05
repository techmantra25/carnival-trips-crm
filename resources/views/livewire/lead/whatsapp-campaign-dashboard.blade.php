<div>
    <div class="bg-white dark:bg-bodybg border border-success alert !rounded-full px-6 text-success text-center">
        <h3 class="font-bold"> <img src="https://sprukomarket.com/products/html/tailwind/valex/dist/assets/images/faces/14.jpg" class="avatar avatar-sm w-6 h-6 me-2 !rounded-full ring-0" alt="WhatsApp Logo"> Welcome {{ $authUser->name }}!</h3>
         Please Start your day by sending a message to your clients
    </div>
    

    <div class="grid grid-cols-12 gap-6 mt-2 individual-customers">
        <div class="xl:col-span-12 col-span-12">
            <div class="box custom-box">
                <div class="box-header">
                    <div class="badge gap-2 !bg-danger text-white rounded-md uppercase mb-2">
                        <i class="fas fa-user cursor-pointer text-xs ms-auto"></i> Customer Selection
                    </div>
                </div>
                <div class="box-body">
                    <div class="table-responsive {{$selectedCustomerType=='select_customers'?'customer-table-responsive':''}}">
                        <table class="table whitespace-nowrap ti-striped-table table-hover min-w-full ti-custom-table-hover select-customer-table">
                            <thead>
                                <tr>
                                    <th scope="col" class="{{$selectedCustomerType=='all'?'!bg-success':'default-tab'}} text-white !text-center cursor-pointer" wire:click="toggleCustomerSelection('all')">
                                        <i class="fas fa-users cursor-pointer text-xs ms-auto"></i> All Customers
                                        <span class="available-select-customers">{{count($leads['allLeads'])}}</span>
                                    </th>
                                    <th scope="col" class="{{$selectedCustomerType=='hot_lead'?'!bg-success':'default-tab'}} text-white !text-center cursor-pointer" wire:click="toggleCustomerSelection('hot_lead')">
                                        <i class="fas fa-fire cursor-pointer text-xs ms-auto"></i> Hot Leads
                                        <span class="available-select-customers">{{count($leads['hotLeads'])}}</span>
                                    </th>
                                    <th scope="col" class="{{$selectedCustomerType=='active_lead'?'!bg-success':'default-tab'}} text-white !text-center cursor-pointer" wire:click="toggleCustomerSelection('active_lead')">
                                        <i class="fa-solid fa-arrow-trend-up cursor-pointer text-xs ms-auto"></i> Active Leads
                                        <span class="available-select-customers">{{count($leads['activeLeads'])}}</span>
                                    </th>
                                    <th scope="col" class="{{$selectedCustomerType=='cnp'?'!bg-success':'default-tab'}} text-white !text-center cursor-pointer" wire:click="toggleCustomerSelection('cnp')">
                                        <i class="fas fa-user-check cursor-pointer text-xs ms-auto"></i> CNP
                                        <span class="available-select-customers">{{count($leads['cnpLeads'])}}</span>
                                    </th>
                                    <th scope="col" class="{{$selectedCustomerType=='select_customers'?'!bg-success':'default-tab'}} text-white !text-center cursor-pointer" wire:click="toggleCustomerSelection('select_customers')" width="20%">
                                        <i class="fa-solid fa-user-plus cursor-pointer text-xs ms-auto"></i> Select Customers
                                        <span class="available-select-customers">{{count($leads['allLeads'])}}</span>
                                    </th>
                                </tr>
                            </thead>
                            @if($selectedCustomerType=='select_customers')
                                <tbody>
                                    <tr class="filter-row">
                                        <td colspan="3"></td>
                                        <td colspan="2">
                                            <div class="text-end flex items-center justify-end search-customer-container">
                                               <input 
                                                    type="text" 
                                                    onkeyup="filterLeads.call(this)"
                                                    class="badge w-xs"
                                                    id="lead-filter"
                                                    style="width: 240px;" 
                                                    placeholder="Filter here..">
                                                <button class="ti-btn ti-btn-outline-danger badge ti-btn-wave me-[0.375rem] uppercase" type="button" onclick="clearFilter.call(this)">Clear</button>
                                            </div>
                                        </td>
                                    </tr>

                                    @forelse ($leads['allLeads'] as $lead_item)
                                        <tr class="cursor-pointer lead-row" 
                                            wire:click="toggleLead({{ $lead_item->id }})">
                                            <td scope="row" class="text-start !ps-4" colspan="3">
                                                <div class="form-check flex items-center gap-2">
                                                    <input class="lead-item" type="hidden" 
                                                    data-name="{{ $lead_item->customer_name }}"
                                                    data-mobile="{{ $lead_item->customer_whatsapp }}"
                                                    data-status="{{ $lead_item->status }}"
                                                    >
                                                    <input class="form-check-input"
                                                        type="checkbox"
                                                        value="{{ $lead_item->id }}"
                                                        wire:click.stop="toggleLead({{ $lead_item->id }})"
                                                        id="lead-{{ $lead_item->id }}"
                                                        @if(in_array($lead_item->id, $selectedLeads)) checked @endif>
                                                    <div>
                                                        <div class="leading-none">
                                                            <span>{{ $lead_item->customer_name }}</span>
                                                        </div>
                                                        @if($lead_item->customer_whatsapp)
                                                            <div class="leading-none">
                                                                <span class="text-[0.6875rem] text-textmuted">
                                                                    +{{ $lead_item->country_code }}{{ $lead_item->customer_whatsapp }}
                                                                </span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>

                                            <td class="!text-center">
                                                <span class="text-[0.6875rem] text-textmuted">
                                                    <i class="fa-brands fa-whatsapp text-success"></i> Last contact: 1/12/2024
                                                </span>
                                            </td>

                                            <td class="!text-center">
                                                <span class="badge gap-2 {{ \App\Helpers\CustomHelper::getLeadStatusBadgeColor($lead_item->status) }}">
                                                    <span class="w-1.5 h-1.5 inline-block bg-black rounded-full"></span>
                                                    {{ ucwords($lead_item->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center">No customers found.</td>
                                        </tr>
                                    @endforelse

                                </tbody>

                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="whatsapp-message-preview">
        <div class="container mb-4">
            <div class="form-section">
                <div class="toggle-container">
                    <!-- Preset Only -->
                    <input 
                        type="radio" 
                        id="preset-toggle" 
                        name="message-type" 
                        value="preset"
                        wire:model="messageType"  
                        checked>
                    <label for="preset-toggle" class="!bg-success">Preset Templates</label>
                </div>

                    @if($messageType === 'preset')
                        <div id="preset-panel" class="panel">

                            <!-- Search -->
                            <div class="flex justify-end mb-4">
                                <input 
                                    type="text"
                                    class="badge w-xs rounded-lg border px-3 py-2 shadow-sm focus:ring-2 focus:ring-success/40"
                                    wire:model="template_search" 
                                    wire:keyup="QuickSearch($event.target.value)" 
                                    placeholder="Search templates..."
                                >
                            </div>

                            <!-- Templates Grid -->
                            <div class="grid grid-cols-12 gap-6">
                                @foreach ($whatsappTemplates as $item)
                                <div class="sm:col-span-6 col-span-12" wire:key="wtpl-{{ $item['Id'] }}">
                                    
                                    <div class="box rounded-xl overflow-hidden shadow hover:shadow-lg transition-all duration-300
                                        {{ $selectedTemplateId == $item['Id'] ? 'ring-2 ring-success' : 'ring-1 ring-gray-200' }}">
                                        
                                        <div class="box-body preset-template-item relative p-5">

                                            <!-- Template Title -->
                                            <h6 class="box-title font-semibold text-base flex items-center gap-2 mb-3 text-gray-800">
                                                <i class="fas fa-message text-success text-sm"></i> 
                                                {{ ucfirst($item['name']) }}
                                            </h6>

                                            <!-- Badges -->
                                            <div class="flex flex-wrap gap-2 mb-4">
                                                <span class="px-2 py-1 bg-blue-50 text-blue-600 text-xs rounded-md">
                                                    Category: {{ $item['category'] }}
                                                </span>
                                                <span class="px-2 py-1 bg-green-50 text-green-600 text-xs rounded-md">
                                                    Status: {{ $item['Status'] }}
                                                </span>
                                                <span class="px-2 py-1 bg-gray-100 text-gray-600 text-xs rounded-md">
                                                    Lang: {{ $item['language'] }}
                                                </span>
                                            </div>

                                            <!-- BODY Preview -->
                                            @php 
                                                $body = collect($item['components'])->firstWhere('type', 'BODY');
                                            @endphp
                                            @if($body)
                                                <div class="p-4 rounded-lg bg-gray-100 border text-sm leading-6 mb-4 text-gray-700">
                                                    {!! nl2br(e($body['text'])) !!}
                                                </div>
                                            @endif

                                            <!-- CAROUSEL Preview -->
                                            @php 
                                                $carousel = collect($item['components'])->firstWhere('type', 'CAROUSEL');
                                            @endphp

                                            @if($carousel)
                                                <div class="swiper mySwiper mb-4" wire:ignore>
                                                    <div class="swiper-wrapper">

                                                        @foreach($carousel['cards'] as $card)
                                                            @php
                                                                $header = collect($card['components'])->firstWhere('type','HEADER');
                                                                $bodyTxt = collect($card['components'])->firstWhere('type','BODY');
                                                            @endphp

                                                            <div class="swiper-slide">
                                                                <div class="w-full border rounded-lg p-2 bg-white shadow-sm">

                                                                    @if(isset($header['example']['header_handle'][0]))
                                                                        <img src="http://127.0.0.1:8000/build/assets/images/logo/hotel.jpg" 
                                                                            class="rounded mb-2 w-full h-32 object-cover" />
                                                                    @endif

                                                                    <p class="text-sm text-gray-800">{{ $bodyTxt['text'] ?? '' }}</p>
                                                                </div>
                                                            </div>
                                                        @endforeach

                                                    </div>

                                                    <!-- Slide Controls -->
                                                    <div class="swiper-button-next"></div>
                                                    <div class="swiper-button-prev"></div>
                                                    <div class="swiper-pagination"></div>
                                                </div>

                                            @endif


                                            <!-- Select Button -->
                                            <button 
                                                class="ti-btn w-full uppercase py-2 rounded-lg transition 
                                                    {{ $selectedTemplateId == $item['Id'] 
                                                        ? 'bg-success text-white hover:bg-success/90' 
                                                        : 'border border-success text-success hover:bg-success hover:text-white' }}"
                                                wire:click="selectTemplate({{ $item['Id'] }})"
                                            >
                                                {{ $selectedTemplateId == $item['Id'] ? 'Selected' : 'Select' }}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>

                        </div>
                        @endif


                
            </div>
            
            <div class="preview-section">
                @php
                    $selected = $previewTemplate ?? null;

                    // BODY TEXT
                    $selected_body = $selected 
                        ? collect($selected['components'])->firstWhere('type', 'BODY') 
                        : null;

                    // HEADER IMAGE
                    $selected_header = $selected 
                        ? collect($selected['components'])->firstWhere('type', 'HEADER') 
                        : null;

                    $headerImage = $selected_header['example']['header_handle'][0] ?? null;
                    // CAROUSEL
                    $selected_carousel = $selected
                        ? collect($selected['components'])->firstWhere('type', 'CAROUSEL')
                        : null;
                @endphp

                <h2 class="text-end mb-2">WhatsApp Preview</h2>
                    @if($selected)
                        <div class="box active_template">
                            <div class="box-body preset-template-item">

                                {{-- Template Name --}}
                                <h6 class="box-title font-semibold text-sm flex items-center gap-2 mb-2">
                                    <i class="fas fa-message text-success text-xs"></i> 
                                    {{ ucfirst($selected['name']) }}
                                </h6>

                                {{-- HEADER IMAGE --}}
                                @if($headerImage)
                                    <img src="{{ $headerImage }}" class="rounded mb-3 w-full h-32 object-cover" />
                                @endif

                                {{-- BODY --}}
                                @if($selected_body)
                                    <div class="p-3 rounded bg-gray-50 border text-xs leading-5 mb-4">
                                        {!! nl2br(e($selected_body['text'])) !!}
                                    </div>
                                @endif

                            </div>
                        </div>
                    @endif
                <div class="mt-4">
                    <strong>Selected Customers:</strong>
                    @if($selectedCustomerType=='select_customers')
                        @forelse($selectedCustomer as $customer)
                            <span class="badge gap-2 bg-success/10 text-success">
                                <i class="fa-brands fa-whatsapp text-success"></i> {{ $customer }}
                            </span>
                        @empty
                            <span class="text-muted">No customers selected</span>
                        @endforelse
                    @else
                        <span class="badge gap-2 bg-success/10 text-success">
                            <i class="fa-brands fa-whatsapp text-success"></i> {{ count($selectedCustomer) }} customers selected
                        </span>
                    @endif
                </div>
                <div class="whatsapp-footer-link justify-center">
                    <button 
                        type="button" 
                        id="submit-button" 
                        wire:click="sendWhatsAppMessage" 
                        class="submit-button {{ $selectedTemplateId == null || count($selectedCustomer) === 0 ? 'bg-gray-400 cursor-not-allowed' : 'bg-green-500 hover:bg-green-600' }}" 
                        @if($selectedTemplateId == null || count($selectedCustomer) === 0) disabled @endif
                    >
                        <i class="fas fa-paper-plane"></i> Send Now
                    </button>
                </div>


            </div>
        </div>
    </div>
    <div wire:loading class="loader" wire:target="sendWhatsAppMessage,selectTemplate,toggleLead,toggleCustomerSelection">
        <div class="spinner">
            <img src="{{asset('build/assets/images/media/loader.svg')}}" alt="">
        </div>
    </div>
</div>
@section('scripts')
        <script>
            // Swiper initializer function
            function initSwipers() {
                // Destroy existing Swipers to avoid duplicates
                if (window.activeSwipers) {
                    window.activeSwipers.forEach(swiper => swiper.destroy(true, true));
                }
                window.activeSwipers = [];

                document.querySelectorAll(".mySwiper").forEach((el) => {
                    let swiper = new Swiper(el, {
                        slidesPerView: 1,
                        spaceBetween: 12,
                        loop: true,
                        pagination: {
                            el: el.querySelector(".swiper-pagination"),
                            clickable: true,
                        },
                        navigation: {
                            nextEl: el.querySelector(".swiper-button-next"),
                            prevEl: el.querySelector(".swiper-button-prev"),
                        },
                    });

                    window.activeSwipers.push(swiper);
                });
            }

            // First page load
            document.addEventListener("DOMContentLoaded", () => {
                initSwipers();
            });

            // Re-run after Livewire updates DOM
            document.addEventListener("livewire:navigated", () => {
                initSwipers();
            });

            document.addEventListener("livewire:update", () => {
                initSwipers();
            });
        </script>

        <script>
            function clearFilter() {
                document.getElementById('lead-filter').value = '';
                filterLeads.call(this);
            }
            function filterLeads() {
                let filter = this.value.toLowerCase();
                let rows = document.querySelectorAll('.lead-row');

                rows.forEach(function(row) {
                    let hiddenInput = row.querySelector('.lead-item');
                    let name = hiddenInput.getAttribute('data-name')?.toLowerCase() || '';
                    let mobile = hiddenInput.getAttribute('data-mobile')?.toLowerCase() || '';
                    let status = hiddenInput.getAttribute('data-status')?.toLowerCase() || '';

                    if (name.includes(filter) || mobile.includes(filter) || status.includes(filter)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            }
            window.addEventListener('refreshComponent', function (event) {
               filterLeads.call(this);
            });
        </script>

@endsection
