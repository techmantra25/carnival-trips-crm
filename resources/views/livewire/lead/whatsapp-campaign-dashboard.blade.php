<div>
    <style>
        .available-select-customers {
            background: #fff;
            color: #22c03c;
            border-radius: 15px;
            padding: 2px 5px;
        }
        .individual-customers .default-tab{
            background: #e8f9eb;
            color: #128d26;
        }
        .select-customer-table thead{
            position: sticky;
            top: 0;
        }
        /* .select-customer-table{
            height: 250px;
            overflow-y: auto !important;
        } */
    </style>
    <div class="bg-white dark:bg-bodybg border border-success alert !rounded-full px-6 text-success text-center">
        <h3 class="font-bold"> <img src="https://sprukomarket.com/products/html/tailwind/valex/dist/assets/images/faces/14.jpg" class="avatar avatar-sm w-6 h-6 me-2 !rounded-full ring-0" alt="WhatsApp Logo"> Welcome {{ $authUser->name }}!</h3>
         Please Start your day by sending a message to your clients
    </div>
    <div class="text-end mb-2">
        <input type="text" class="badge w-xs" wire:model="search" wire:keyup="QuickSearch($event.target.value)" placeholder="Quick template search..">
    </div>

    {{-- Template selection --}}
    <div class="grid grid-cols-12 gap-x-6 whatsapp-template-section">
        @foreach ($templates as $templateItem)
            <div class="sm:col-span-3 col-span-12">
                <div class="box {{$selectedTemplateId==$templateItem->id?'active_template':''}}">
                    <div class="box-body"> 
                        <a href="{{$templateItem->external_link}}" target="_blank" class="absolute-badge badge gap-2 !bg-{{$selectedTemplateId==$templateItem->id?'success':'secondary'}} text-white rounded-md">
                            <i class="fas fa-external-link-alt text-white cursor-pointer text-xs ms-auto"></i>
                        </a>
                        <img src="{{ $templateItem->image }}" class="card-img mb-4 !rounded-md"
                            alt="...">
                        <h6 class="box-title font-semibold"><i class="fas fa-message text-success cursor-pointer text-xs"></i> {{ $templateItem->title }}</h6>
                        <p class="card-text mb-4">{{ $templateItem->body }}</p>
                    <div>
                        <button class="ti-btn ti-btn-{{$selectedTemplateId==$templateItem->id?'success-full':'outline-success'}}  ti-btn-wave  me-[0.375rem] w-full uppercase" wire:click="selectTemplate({{ $templateItem->id }})">{{$selectedTemplateId==$templateItem->id?'Selected':'Select'}}</button>
                    </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    @if($selectedTemplateId)
        <div class="grid grid-cols-12 gap-6 mt-2 individual-customers">
            <div class="xl:col-span-12 col-span-12">
                <div class="box custom-box">
                    <div class="box-header">
                        <div class="badge gap-2 !bg-primary text-white rounded-md uppercase mb-2">
                            <i class="fas fa-user cursor-pointer text-xs ms-auto"></i> Customer Selection
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive">
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
                                        <th scope="col" class="{{$selectedCustomerType=='select_customers'?'!bg-success':'default-tab'}} text-white !text-center cursor-pointer" wire:click="toggleCustomerSelection('select_customers')" width="15%">
                                            <i class="fa-solid fa-user-plus cursor-pointer text-xs ms-auto"></i> Select Customers
                                            <span class="available-select-customers">{{count($leads['allLeads'])}}</span>
                                        </th>
                                    </tr>
                                </thead>
                                @if($selectedCustomerType=='select_customers')
                                    <tbody>
                                        <tr>
                                            <td colspan="3"></td>
                                            <td colspan="2">
                                                <div class="text-end flex items-center gap-2 justify-end">
                                                    <input 
                                                        type="text" 
                                                        wire:model.debounce.300ms="search" 
                                                        class="badge w-xs" 
                                                        style="width: 240px;" 
                                                        placeholder="Filter here..">
                                                </div>
                                            </td>
                                        </tr>

                                        @forelse ($leads['allLeads']->filter(function($lead) use($search) {
                                            if(!$search) return true;
                                            $value = strtolower(trim($search));
                                            return str_contains(strtolower($lead->customer_name), $value)
                                                || str_contains(strtolower($lead->customer_whatsapp ?? ''), $value)
                                                || str_contains(strtolower($lead->status), $value);
                                        }) as $lead_item)
                                            <tr class="cursor-pointer lead-row" 
                                                wire:click="toggleLead({{ $lead_item->id }})"
                                                data-name="{{ strtolower($lead_item->customer_name) }}"
                                                data-mobile="{{ strtolower($lead_item->customer_whatsapp) }}"
                                                data-status="{{ strtolower($lead_item->status) }}">

                                                <td scope="row" class="text-start !ps-4" colspan="3">
                                                    <div class="form-check flex items-center gap-2">
                                                        <input class="form-check-input"
                                                            type="checkbox"
                                                            value="{{ $lead_item->id }}"
                                                            wire:model="selectedLeads"
                                                            wire:click.stop
                                                            id="lead-{{ $lead_item->id }}">

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
                        <div>
                            <div class="mt-3">
                                <strong>Selected Customers:</strong>
                                @forelse($selectedCustomer as $customer)
                                    <span class="badge gap-2 bg-success/10 text-success">
                                       <i class="fa-brands fa-whatsapp text-success"></i> {{ $customer }}
                                    </span>
                                @empty
                                    <span class="text-muted">No customers selected</span>
                                @endforelse
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@section('scripts')
   <script>
       document.addEventListener("DOMContentLoaded", function () {
            const input = document.getElementById("leadFilter");
            const rows = document.querySelectorAll(".lead-row");

            if (!input) return; // safety check

            input.addEventListener("keyup", function () {
                const value = input.value.toLowerCase().trim();

                rows.forEach(row => {
                    const name   = row.dataset.name || "";
                    const mobile = row.dataset.mobile || "";
                    const status = row.dataset.status || "";

                    const match =
                        name.includes(value) ||
                        mobile.includes(value) ||
                        status.includes(value);

                    row.style.display = match ? "" : "none";
                });
            });
        });
   </script>
@endsection
