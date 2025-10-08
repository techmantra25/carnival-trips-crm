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
                    <!-- Custom -->
                    <input type="radio" id="custom-toggle" name="message-type" value="custom"
                        wire:model="messageType" wire:click="selectMessageType('custom')" checked>
                    <label for="custom-toggle">Custom Message</label>

                    <!-- Preset -->
                    <input type="radio" id="preset-toggle" name="message-type" value="preset"
                        wire:model="messageType" wire:click="selectMessageType('preset')">
                    <label for="preset-toggle">Preset Templates</label>

                    <!-- Slider -->
                    <span class="slider"></span>
                </div>
                @if($messageType === 'custom')
                    <div id="custom-panel" class="panel">
                        <h2>Customize Your Message</h2>
                        <div class="input-group">
                            <label for="message-content">Message</label>
                            <textarea id="message-content" wire:keyup="messageContent($event.target.value)" placeholder="Type your message here..." rows="4"></textarea>
                        </div>
                    </div>
                @else
                    <div id="preset-panel" class="panel">
                        <h2>Preset Message Selection</h2>
                        <div class="text-end mb-2">
                            <input type="text" class="badge w-xs" wire:model="template_search" wire:keyup="QuickSearch($event.target.value)" placeholder="Quick search here..">
                        </div>

                        {{-- Template selection --}}
                        <div class="grid grid-cols-12 gap-x-6 whatsapp-template-section">
                            @foreach ($templates as $templateItem)
                                <div class="sm:col-span-4 col-span-12" wire:key="template-{{ $templateItem->id }}">
                                    <div class="box {{ $selectedTemplateId == $templateItem->id ? 'active_template' : '' }}">
                                        <div class="box-body preset-template-item"> 
                                            <a href="{{ $templateItem->external_link }}" target="_blank" class="absolute-badge badge gap-2 !bg-{{ $selectedTemplateId == $templateItem->id ? 'success' : 'secondary' }} text-white rounded-md">
                                                <i class="fas fa-external-link-alt text-white cursor-pointer text-xs ms-auto"></i>
                                            </a>
                                            <img src="{{ $templateItem->image }}" class="card-img mb-4 !rounded-md" alt="...">
                                            <h6 class="box-title font-semibold !text-xs">
                                                <i class="fas fa-message text-success cursor-pointer !text-xs"></i> {{ $templateItem->template_name }}
                                            </h6>
                                            <div class="mt-2">
                                                <button class="ti-btn ti-btn-{{ $selectedTemplateId == $templateItem->id ? 'success-full' : 'outline-success' }} ti-btn-wave me-[0.375rem] w-full uppercase"
                                                    wire:click="selectTemplate({{ $templateItem->id }})">
                                                    {{ $selectedTemplateId == $templateItem->id ? 'Selected' : 'Select' }}
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                        </div>
                    </div>
                @endif
                
            </div>
            
            <div class="preview-section">
                <h2 class="text-end mb-2">WhatsApp Preview</h2>
                <div class="whatsapp-message-bubble">
                    <div class="whatsapp-card-content">
                        @if($previewTemplate['image'])
                        <div id="whatsapp-image-container" class="whatsapp-image-container">
                            <img src="{{$previewTemplate['image']}}" alt="">
                        </div>
                        @endif
                        <div class="whatsapp-text-content">
                            <h3 id="whatsapp-card-title">{{$previewTemplate['title']}}</h3>
                            <p id="whatsapp-card-message">{{$previewTemplate['body']}}</p>
                        </div>
                    </div>
                    @if($previewTemplate['external_link'])
                        <div class="whatsapp-footer-link justify-center">
                            <a href="{{$previewTemplate['external_link']}}" target="_blank" id="whatsapp-footer-text" class="text-base cursor-pointer flex items-center justify-center gap-1">
                                <i class="fas fa-external-link-alt text-xs"></i> Plan Your Trip
                            </a>
                        </div>
                    @endif
                </div>
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
                        class="submit-button {{ empty($previewTemplate['body']) || count($selectedCustomer) === 0 ? 'bg-gray-400 cursor-not-allowed' : 'bg-green-500 hover:bg-green-600' }}" 
                        @if(empty($previewTemplate['body']) || count($selectedCustomer) === 0) disabled @endif
                    >
                        <i class="fas fa-paper-plane"></i> Send Now
                    </button>
                </div>


            </div>
        </div>
    </div>
</div>
@section('scripts')
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
