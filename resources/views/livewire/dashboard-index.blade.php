<div>
    <div class="md:flex block items-center justify-between mb-6 mt-[2rem]  page-header-breadcrumb">
        <div class="my-auto">
            <h5 class="page-title text-[1.3125rem] font-medium text-defaulttextcolor mb-0">Dashboard</h5>
            <nav>
            <ol class="flex items-center whitespace-nowrap min-w-0">
                <li class="text-[12px]"> <a class="flex items-center text-primary hover:text-primary"
                    href="javascript:void(0);"> Dashboard <i
                    class="ti ti-chevrons-right flex-shrink-0 mx-3 overflow-visible text-textmuted rtl:rotate-180"></i>
                </a> </li>
                <li class="text-[12px]"> <a class="flex items-center text-textmuted"
                    href="javascript:void(0);">Lead Analytics 
                </a> </li>
            </ol>
            </nav>
        </div>

        <div class="flex flex-wrap items-center gap-3 xl:my-auto right-content">
           <!-- Filter Type -->
            <div class="items-center gap-2">
                <label class="form_custom_lable">Type</label>
                <select wire:model="filter_type" class="form-control form-control-sm !border-s-0 custom_field refresh_component" wire:change="FilterTypeUpdate($event.target.value)">
                    <option value="">Select</option>
                    <option value="date">Date Wise</option>
                    <option value="month">Month Wise</option>
                    <option value="year">Year Wise</option>
                </select>
            </div>

            <!-- Day-wise Filter -->
            @if ($filter_type == 'date')
                <div class="items-center gap-2">
                    <label class="form_custom_lable">Start Date</label>
                    <input type="date" wire:model="start_date" class="form-control form-control-sm !border-s-0 custom_field refresh_component" />
                </div>
                <div class="items-center gap-2">
                    <label class="form_custom_lable">End Date</label>
                    <input type="date" wire:model="end_date" class="form-control form-control-sm !border-s-0 custom_field refresh_component" />
                </div>
            @endif

            <!-- Month-wise Filter -->
            @if ($filter_type == 'month')
                <div class="items-center gap-2">
                    <label class="form_custom_lable">Month</label>
                    <select wire:model="month" class="form-control form-control-sm !border-s-0 custom_field refresh_component">
                        <option value="">Select</option>
                        @for ($m = 1; $m <= 12; $m++)
                            <option value="{{ str_pad($m, 2, '0', STR_PAD_LEFT) }}">{{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
                        @endfor
                    </select>
                </div>
            @endif

            <!-- Year-wise Filter -->
            @if ($filter_type == 'year' || $filter_type == 'month')
                <div class="items-center gap-2">
                    <label class="form_custom_lable">Year</label>
                    <select wire:model="year" class="form-control form-control-sm !border-s-0 custom_field refresh_component">
                        <option value="">Select</option>
                        @for ($i = date('Y'); $i >= 2015; $i--)
                            <option value="{{ $i }}">{{ $i }}</option>
                        @endfor
                    </select>
                </div>
            @endif


            <!-- Refresh Button -->
            <button type="button" wire:click="refreshFilters" class="ti-btn ti-btn-warning-full text-white ti-btn-icon mt-4 mb-0">
                <i class="mdi mdi-refresh"></i>
            </button>
        </div>
    </div>

    <!-- End::page-header -->

    <!-- row -->
    <div class="grid grid-cols-12 gap-x-6">
        <!-- Facebook -->
        <div class="xxl:col-span-3 md:col-span-6 col-span-12">
            <div class="box">
                <div class="box-body">
                    <div class="flex">
                        <div>
                            <p class="font-medium mb-1 text-textmuted">Facebook</p>
                            <h4 class="mb-0 font-medium">25</h4>
                        </div>
                        <div class="ms-auto">
                            <div class="h-10 w_2_5rem rounded-md flex items-center justify-center">
                                <img src="{{asset('assets/img/facebook.png')}}" alt="">
                            </div>
                        </div>
                    </div>
                    <div class="flex mt-2">
                        <span class="badge text-primary text-xs bg-primary/10 rounded-full">24%</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Instagram -->
        <div class="xxl:col-span-3 md:col-span-6 col-span-12">
            <div class="box">
                <div class="box-body">
                    <div class="flex">
                        <div>
                            <p class="font-medium mb-1 text-textmuted">Instagram</p>
                            <h4 class="mb-0 font-medium">15</h4>
                        </div>
                        <div class="ms-auto">
                            <div class="h-10 w_2_5rem rounded-md flex items-center justify-center">
                                <img src="{{asset('assets/img/instagram.png')}}" alt="">
                            </div>
                        </div>
                    </div>
                    <div class="flex mt-2">
                        <span class="badge text-primary text-xs bg-primary/10 rounded-full">24%</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Website -->
        <div class="xxl:col-span-3 md:col-span-6 col-span-12">
            <div class="box">
                <div class="box-body">
                    <div class="flex">
                        <div>
                            <p class="font-medium mb-1 text-textmuted">Website</p>
                            <h4 class="mb-0 font-medium">20</h4>
                        </div>
                        <div class="ms-auto">
                            <div class="h-10 w_2_5rem rounded-md flex items-center justify-center">
                                <img src="{{asset('assets/img/web-link.png')}}" alt="">
                            </div>
                        </div>
                    </div>
                    <div class="flex mt-2">
                        <span class="badge text-primary text-xs bg-primary/10 rounded-full">24%</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Leads -->
        <div class="xxl:col-span-3 md:col-span-6 col-span-12">
            <div class="box">
                <div class="box-body">
                    <div class="flex">
                        <div>
                            <p class="font-medium text-textmuted mb-1">Total Lead</p>
                            <h4 class="mb-0 font-medium">60</h4>
                        </div>
                        <div class="ms-auto">
                            <div class="h-10 w_2_5rem rounded-md bg-green-500 flex items-center justify-center">
                                <svg fill="white" viewBox="0 0 24 24" class="h-5 w-5">
                                    <path d="M12 2a10 10 0 1 0 .001 20.001A10 10 0 0 0 12 2zm0 18c-2.4 0-4.6-1-6.2-2.6C3.2 15.8 2 13.1 2 10c0-.6.1-1.1.2-1.6L10.4 17.6c-.5.1-1.1.2-1.6.2zm9.8-4.4L13.6 6.4c.5-.1 1.1-.2 1.6-.2 2.4 0 4.6 1 6.2 2.6C20.8 8.2 22 10.9 22 14c0 .6-.1 1.1-.2 1.6z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="flex mt-4 gap-2">
                        <div class="progress progress-xs course-status-progress progress-animate me-2" aria-valuenow="38" aria-valuemin="0"
                            aria-valuemax="100">
                            <div class="progress-bar progress-bar-striped !bg-warning w-[38%]"></div>
                        </div>
                        <div style="margin-top: -10px;">53%</div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- row closed -->

    <!-- row opened -->
    <div class="grid grid-cols-12 gap-x-6 mt-4">
        <div class="md:col-span-12 lg:col-span-7 xl:col-span-7 col-span-7">
            <div class="box">
                <div class="box-header !border-b-0 !pb-0 flex justify-between">
                    <div>
                        <h4 class="box-title mb-2">Lead Analytics</h4>
                        <div>
                            <p class="text-[.75rem] text-textmuted font-normal mb-0">Lead source breakdown</p>
                        </div>
                    </div>
                    {{-- <div>
                        <h4 class="text-lg font-bold">40</h4>
                    </div> --}}
                </div>
                <div class="box-body !pb-0">
                    <div class="total-revenue">
                        <div>
                            {{-- <h4>20</h4> --}}
                            <label><span class="bg-primary inline-block w-2 h-2 rounded-full mr-1"></span>Facebook</label>
                        </div>
                        <div>
                            {{-- <h4>10</h4> --}}
                            <label><span class="bg-danger inline-block w-2 h-2 rounded-full mr-1"></span>Instagram</label>
                        </div>
                        <div>
                            {{-- <h4>10</h4> --}}
                            <label><span class="bg-warning inline-block w-2 h-2 rounded-full mr-1"></span>Website</label>
                        </div>
                    </div>
                    <div id="Sales-bar" class="sales-bar"></div>
                </div>
            </div>
        </div>
        <div class="md:col-span-12 lg:col-span-5 xl:col-span-5 col-span-12">
            <div class="box top-countries-card">
                <div class="box-header !border-b-0 !p-0 mb-2">
                    <h4 class="box-title mb-1">Top Destinations</h4>
                    <span class="block text-textmuted font-normal text-[0.75rem]">Lead conversion performance by destination</span>
                </div>
                <div class="ti-list-group border mt-2">
                    <div class="ti-list-group-item flex justify-between items-center !border-t-0 !px-0 py-2 font-semibold text-sm bg-gray-50">
                        <p>Total Conversion Rate</p>
                        <span class="text-primary">50% (Avg)</span>
                    </div>
                    <div class="ti-list-group-item flex justify-between items-center !px-0 py-2 text-sm">
                        <p>Maldives</p>
                        <span>90%</span>
                    </div>
                    <div class="ti-list-group-item flex justify-between items-center !px-0 py-2 text-sm">
                        <p>Andaman</p>
                        <span>75%</span>
                    </div>
                    <div class="ti-list-group-item flex justify-between items-center !px-0 py-2 text-sm">
                        <p>Bali</p>
                        <span>65%</span>
                    </div>
                    <div class="ti-list-group-item flex justify-between items-center !px-0 py-2 text-sm">
                        <p>Thailand</p>
                        <span>80%</span>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
