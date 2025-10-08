<div>
    <style>
        #leadAnalytics { max-width: 800px; }
    </style>
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
                    <input type="date" wire:model="start_date" class="form-control form-control-sm !border-s-0 custom_field refresh_component" wire:change="StartDateUpdate" />
                </div>
                <div class="items-center gap-2">
                    <label class="form_custom_lable">End Date</label>
                    <input type="date" wire:model="end_date" class="form-control form-control-sm !border-s-0 custom_field refresh_component" wire:change="EndDateUpdate"/>
                </div>
            @endif

            <!-- Month-wise Filter -->
            @if ($filter_type == 'month')
                <div class="items-center gap-2">
                    <label class="form_custom_lable">Month</label>
                    <select wire:model="month" class="form-control form-control-sm !border-s-0 custom_field refresh_component" wire:change="MonthUpdate">
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
                    <select wire:model="year" class="form-control form-control-sm !border-s-0 custom_field refresh_component" wire:change="YearUpdate">
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
                    <a href="{{route('admin.leads.index', ['source_type'=>'Facebook'])}}">
                        @php
                            $totalLeads = $leadCounts['total'] ?? 0;
                            $totalConfirmed = $confirmedCounts['total'] ?? 0;
                            $totalPercentage = $totalLeads > 0 ? round(($totalConfirmed / $totalLeads) * 100) : 0;

                        
                            $facebookLeads = $leadCounts['facebook'] ?? 0;
                            $facebookConfirmed = $confirmedCounts['facebook'] ?? 0;
                            $facebookpercentage = $facebookConfirmed > 0 ? round(($facebookConfirmed / $totalConfirmed) * 100) : 0;
                        @endphp

                        <div class="flex">
                            <div>
                                <p class="font-medium mb-1 text-textmuted">Facebook</p>
                                <h4 class="mb-0 font-medium">{{ $facebookLeads }}</h4>
                            </div>
                            <div class="ms-auto">
                                <div class="h-10 w_2_5rem rounded-md flex items-center justify-center">
                                    <img src="{{ asset('assets/img/facebook.png') }}" alt="">
                                </div>
                            </div>
                        </div>

                        @php
                            if ($facebookpercentage >= 80) {
                                $progressColor = 'bg-green-500'; // success
                            } elseif ($facebookpercentage >= 50) {
                                $progressColor = 'bg-yellow-500'; // warning
                            } else {
                                $progressColor = 'bg-red-500'; // danger
                            }
                        @endphp

                        <div class="flex mt-4 gap-2">
                            <div class="relative group progress progress-xs course-status-progress progress-animate me-2"
                                aria-valuenow="{{ $facebookpercentage }}" aria-valuemin="0" aria-valuemax="100">
                                
                                <!-- Progress Bar -->
                                <div class="progress-bar progress-bar-striped {{ $progressColor }}"
                                    style="width: {{ $facebookpercentage }}%">
                                </div>

                                <!-- Tooltip -->
                                <div class="absolute -top-8 left-1/2 -translate-x-1/2 z-10 hidden group-hover:block
                                            px-2 py-1 text-xs text-white bg-black rounded shadow">
                                    Confirmed: {{ $facebookConfirmed }}
                                </div>
                            </div>
                            
                            <div style="margin-top: -10px;">{{ $facebookpercentage }}%</div>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <!-- Instagram -->
        <div class="xxl:col-span-3 md:col-span-6 col-span-12">
            <div class="box">
                <div class="box-body">
                    <a href="{{route('admin.leads.index', ['source_type'=>'Instagram'])}}">
                        @php
                            $instagramLeads = $leadCounts['instagram'] ?? 0;
                            $instagramConfirmed = $confirmedCounts['instagram'] ?? 0;
                            $instagramPercentage = $instagramConfirmed > 0 ? round(($instagramConfirmed / $totalConfirmed) * 100) : 0;
                        @endphp
                        <div class="flex">
                            <div>
                                <p class="font-medium mb-1 text-textmuted">Instagram</p>
                                <h4 class="mb-0 font-medium">{{ $instagramLeads }}</h4>
                            </div>
                            <div class="ms-auto">
                                <div class="h-10 w_2_5rem rounded-md flex items-center justify-center">
                                    <img src="{{asset('assets/img/instagram.png')}}" alt="">
                                </div>
                            </div>
                        </div>
                        
                        @php
                            if ($instagramPercentage >= 80) {
                                $progressColor = 'bg-green-500'; // success
                            } elseif ($instagramPercentage >= 50) {
                                $progressColor = 'bg-yellow-500'; // warning
                            } else {
                                $progressColor = 'bg-red-500'; // danger
                            }
                        @endphp

                        <div class="flex mt-4 gap-2">
                            <div class="relative group progress progress-xs course-status-progress progress-animate me-2"
                                aria-valuenow="{{ $instagramPercentage }}" aria-valuemin="0" aria-valuemax="100">
                                
                                <!-- Progress Bar -->
                                <div class="progress-bar progress-bar-striped {{ $progressColor }}"
                                    style="width: {{ $instagramPercentage }}%">
                                </div>

                                <!-- Tooltip -->
                                <div class="absolute -top-8 left-1/2 -translate-x-1/2 z-10 hidden group-hover:block
                                            px-2 py-1 text-xs text-white bg-black rounded shadow">
                                    Confirmed: {{ $instagramConfirmed }}
                                </div>
                            </div>
                            
                            <div style="margin-top: -10px;">{{ $instagramPercentage }}%</div>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <!-- Website -->
        <div class="xxl:col-span-3 md:col-span-6 col-span-12">
            <div class="box">
                <div class="box-body">
                    <a href="{{route('admin.leads.index', ['source_type'=>'Website'])}}">
                        @php
                            $websiteLeads = $leadCounts['website'] ?? 0;
                            $websiteConfirmed = $confirmedCounts['website'] ?? 0;
                            $websitePercentage = $websiteConfirmed > 0 ? round(($websiteConfirmed / $totalConfirmed) * 100) : 0;
                        @endphp
                        <div class="flex">
                            <div>
                                <p class="font-medium mb-1 text-textmuted">Website</p>
                                <h4 class="mb-0 font-medium">{{$websiteLeads}}</h4>
                            </div>
                            <div class="ms-auto">
                                <div class="h-10 w_2_5rem rounded-md flex items-center justify-center">
                                    <img src="{{asset('assets/img/web-link.png')}}" alt="">
                                </div>
                            </div>
                        </div>
                        @php
                            if ($websitePercentage >= 80) {
                                $progressColor = 'bg-green-500'; // success
                            } elseif ($websitePercentage >= 50) {
                                $progressColor = 'bg-yellow-500'; // warning
                            } else {
                                $progressColor = 'bg-red-500'; // danger
                            }
                        @endphp

                        <div class="flex mt-4 gap-2">
                            <div class="relative group progress progress-xs course-status-progress progress-animate me-2"
                                aria-valuenow="{{ $websitePercentage }}" aria-valuemin="0" aria-valuemax="100">
                                
                                <!-- Progress Bar -->
                                <div class="progress-bar progress-bar-striped {{ $progressColor }}"
                                    style="width: {{ $websitePercentage }}%">
                                </div>

                                <!-- Tooltip -->
                                <div class="absolute -top-8 left-1/2 -translate-x-1/2 z-10 hidden group-hover:block
                                            px-2 py-1 text-xs text-white bg-black rounded shadow">
                                    Confirmed: {{ $websiteConfirmed }}
                                </div>
                            </div>
                            
                            <div style="margin-top: -10px;">{{ $websitePercentage }}%</div>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <!-- Total Leads -->
        <div class="xxl:col-span-3 md:col-span-6 col-span-12">
            <div class="box">
                <div class="box-body">
                    <a href="{{route('admin.leads.index')}}">
                        <div class="flex">
                            <div>
                                <p class="font-medium text-textmuted mb-1">Total Lead</p>
                                <h4 class="mb-0 font-medium">{{$leadCounts['total']}}</h4>
                            </div>
                            <div class="ms-auto">
                                <div class="h-10 w_2_5rem rounded-md bg-green-500 flex items-center justify-center">
                                    <svg fill="white" viewBox="0 0 24 24" class="h-5 w-5">
                                        <path d="M12 2a10 10 0 1 0 .001 20.001A10 10 0 0 0 12 2zm0 18c-2.4 0-4.6-1-6.2-2.6C3.2 15.8 2 13.1 2 10c0-.6.1-1.1.2-1.6L10.4 17.6c-.5.1-1.1.2-1.6.2zm9.8-4.4L13.6 6.4c.5-.1 1.1-.2 1.6-.2 2.4 0 4.6 1 6.2 2.6C20.8 8.2 22 10.9 22 14c0 .6-.1 1.1-.2 1.6z"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        @php
                            if ($totalPercentage >= 80) {
                                $progressColor = 'bg-green-500'; // success
                            } elseif ($totalPercentage >= 50) {
                                $progressColor = 'bg-yellow-500'; // warning
                            } else {
                                $progressColor = 'bg-red-500'; // danger
                            }
                        @endphp

                        <div class="flex mt-4 gap-2">
                            <div class="relative group progress progress-xs course-status-progress progress-animate me-2"
                                aria-valuenow="{{ $totalPercentage }}" aria-valuemin="0" aria-valuemax="100">
                                
                                <!-- Progress Bar -->
                                <div class="progress-bar progress-bar-striped {{ $progressColor }}"
                                    style="width: {{ $totalPercentage }}%">
                                </div>

                                <!-- Tooltip -->
                                <div class="absolute -top-8 left-1/2 -translate-x-1/2 z-10 hidden group-hover:block
                                            px-2 py-1 text-xs text-white bg-black rounded shadow">
                                    Confirmed: {{ $totalConfirmed }}
                                </div>
                            </div>
                            
                            <div style="margin-top: -10px;">{{ $totalPercentage }}%</div>
                        </div>
                    </a>
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
                    <canvas id="leadAnalytics"></canvas>
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
                        <span class="text-primary">{{$totalPercentage}}% (Avg)</span>
                    </div>
                    @foreach ($destinationStats as $stat)
                        <div class="ti-list-group-item flex justify-between items-center !px-0 py-2 text-sm">
                            <p>{{ $stat['location_name'] }}</p>
                            <span>{{ $stat['percentage'] }}%</span>
                        </div>
                    @endforeach

                </div>
            </div>
        </div>
    </div>
    <div wire:loading class="loader">
        <div class="spinner">
        <img src="{{asset('build/assets/images/media/loader.svg')}}" alt="">
        </div>
    </div>
</div>
@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
   window.addEventListener('refreshComponent', function (event) {
        let chartData = event.detail[0].chartData;
        let chartMax = chartData.chartMax;
        // console.log(chartData);

        const canvas = document.getElementById('leadAnalytics');

        canvas.style.display = 'block';
        canvas.style.boxSizing = 'border-box';
        canvas.style.height = '340px';
        canvas.style.width = '680px';

        const ctx = canvas.getContext('2d');

        new Chart(ctx, {
            type: 'bar',
            data: chartData,
            options: {
                responsive: true,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                               const leads = context.parsed.y;
                                return `${context.dataset.label}: ${leads} lead${leads !== 1 ? 's' : ''}`;
                            }
                        }
                    },
                    legend: {
                        position: 'top',
                        labels: {
                            usePointStyle: true,
                            pointStyle: 'circle'
                        }
                    },
                    title: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: `${chartMax}`,
                        ticks: {
                            callback: function (value) {
                                return value;
                            }
                        }
                    }
                }
            }
        });
    });

</script>

@endsection
