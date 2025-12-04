
@extends('layouts.master')
{{-- @section('title', $common['pageTitle']) <!-- This sets the page title dynamically --> --}}
@section('styles')
<style>
    .table{
        width:100%;
        background:#fff;
    }
    table{
        width:100%;
    }
    .table tr td {
        font-size:15px;
        color:#000;
    }
    .table thead i {
        color:#1e58a3;
    }
    .route-style {
        position:relative;
    }
    .route-style:after {
        content: "";
        position: absolute;
        left: 0;
        top: 0;
        width: 1px;
        height: 100%;
        background: #0162e8;
    }
    .route-style li {
        position: relative;
        padding: 3px 12px;
        color:#838383;
    }
    .route-style li:after {
        content: "";
        width: 7px;
        height: 7px;
        position: absolute;
        border-radius: 50%;
        background: #0162e8;
        left: -3px;
        top: 8px;
        z-index: 2;
        border: 2px solid #fff;
    }

    .total-ex-list {
        list-style:none;
    }
    .total-ex-list li {
        color:rgb(0, 0, 0);
        font-size: 11px;
        margin-bottom: 7px;
    }
    .total-ex-list li i {
        color: #f2a144;
        margin-right:10px;
    }

    .hotel-list {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(297px, 1fr));
        gap:8px;
    }
     .hotel-list li {
        border:1px solid #ccc;
        border-top-left-radius:6px;
        border-top-right-radius:6px;
     }
    .hotel-list li figure {
        overflow:hidden;
        border-top-left-radius:6px;
        border-top-right-radius:6px;
        height: 200px;
    }
    .hotel-list li figcaption {
        padding: 12px;
    }

    .hotel-list li figcaption h3 {
        font-size: 12px;
        text-transform: uppercase;
        color: #1e58a3;
        font-weight: 500;
        margin-bottom: 8px;
    }
    .hotel-list li figcaption h2 {
        font-size: 13px;
        font-weight: 500;
        margin-bottom: 8px;
    }
    .hotel-list li figcaption p {
        font-size: 12px;
    }

    .activity-list {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap:8px;
    }

    .activity-list li {
        border-radius:6px;
        overflow:hidden;
        position:relative;
        z-index: 1;
        height: 187px;
    }

     .activity-list li:after {
        content:"";
        position:absolute;
        top:0;
        left:0;
        width:100%;
        height:100%;
        background: linear-gradient(1deg,rgba(0, 0, 0, 1) 14%, rgba(0, 0, 0, 0) 100%);
        z-index: -1;
     }
     .activity-list li figcaption {
        padding: 22px;
        color: #fff;
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
     }

     .activity-list li figcaption h2 {
        color:#fff;
        font-size:13px;
     }

    .keep-together {
        page-break-inside: avoid;
        break-inside: avoid;
    }

.page-break-after {
  page-break-after: always;
  break-after: page;
}


    @media print {

        body {
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .bg-color {
            background-color:#d2e8ff !important;
        }
    }
    
</style>
@endsection
@section('content')

<div class="md:flex block items-center justify-between mb-6 mt-1  page-header-breadcrumb">
    {{-- <div class="my-auto">
        <h5 class="page-title text-[1.3125rem] font-medium text-defaulttextcolor mb-0 uppercase">{{$common['childHeader']}}</h5>
        <nav>
            <ol class="flex items-center whitespace-nowrap min-w-0">
                <li class="text-[12px]"> <a class="flex items-center text-primary hover:text-primary"
                        href="javascript:void(0);"> {{$common['parentHeader']}} <i
                            class="ti ti-chevrons-right flex-shrink-0 mx-3 overflow-visible text-textmuted rtl:rotate-180"></i>
                    </a> </li>
                <li class="text-[12px]"> <a class="flex items-center text-textmuted" href="javascript:void(0);">{{$common['childHeader']}}
                    </a> </li>
            </ol>
        </nav>
    </div> --}}

    <div class="my-auto">
        <button onclick="printDiv('print-section')" 
        style="background-color: #0162e8; 
            color: white; 
            border: none; 
            padding: 10px 20px; 
            font-size: 14px; 
            border-radius: 8px; 
            cursor: pointer; 
            box-shadow: 0 4px 6px rgba(0,0,0,0.1); 
            transition: background-color 0.3s ease;">
        </button>
        <a href="{{route('admin.cost_calculator.download_pdf')}}" style="background-color: #0162e8; 
            color: white; 
            border: none; 
            padding: 10px 20px; 
            font-size: 14px; 
            border-radius: 8px; 
            cursor: pointer; 
            box-shadow: 0 4px 6px rgba(0,0,0,0.1); 
            transition: background-color 0.3s ease;">Download</a>
    </div>
</div>



<table class="table" id="print-section">
    <tr>
        <td>
            <table>
                <tr>
                    <td style=" vertical-align:top;">
                        <p>
                            <h4 style="background:rgba(238, 51, 94, 0.2); color:rgb(238, 51, 94); border:1px solid rgb(238, 51, 94); display: inline-block; font-size: 12px; text-transform: uppercase; border-radius: 5px; padding:4px;">Destination</h4>
                        </p>
                        <span>Andaman</span>
                    </td>
                    <td>
                        <table>
                            <tr>
                                <td>
                                    <table style="background: rgba(254, 215, 170, 0.2);  border: 1px solid #fed7aa;">
                                        <tr>
                                            <td style="font-size:16px; color:#000; text-transform:uppercase; padding-left:15px !important; padding-right:15px !important; padding-bottom:0px !important;">
                                                Itinerary is for
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="padding:15px !important;">
                                                <p style="font-size:13px; color:#000; margin-bottom:9px;"><strong>Client:</strong> Rajib Ali Khan </p>
                                                <p style="font-size:13px; color:#000; margin-bottom:9px;"><strong>Client Email:</strong> rajib@gmail.com </p>
                                                <p style="font-size:13px; color:#000; margin-bottom:9px;"><strong>Client Mobile:</strong> +919874563698 </p>
                                                <p style="font-size:13px; color:#000; margin-bottom:9px;"><strong>Travel Date:</strong> 10-04-2025 </p>
                                                
                                            </td>
                                            <td style="padding:15px !important;">
                                                <p style="font-size:13px; color:#000; margin-bottom:9px;"><strong>Andaman Trip:</strong> 5D/4N </p>
                                                <p style="font-size:13px; color:#000; margin-bottom:9px;"><strong>PAX:</strong> 6 </p>
                                                <p style="font-size:13px; color:#000; margin-bottom:9px;"><strong>Adults:</strong> 4 </p>
                                                <p style="font-size:13px; color:#000; margin-bottom:9px;"><strong>Child:</strong> 2 </p>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                                <td style=" vertical-align:top;">
                                    <table style="background:#1e58a3; color:#fff; text-align:center;">
                                        <tr>
                                            <td style="text-align:center;">
                                                <h4 style="color:#fff; text-transform:uppercase; font-size: 13px; ">Total Amount</h4>
                                                <strong style="color:#fff; font-size:18px;">₹15080</strong>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    <tr>
        <td colspan="2">
            <table style="border:1px solid rgba(1, 98, 232, 0.5) !important">
                <thead class="bg-color" style="background-color:#d2e8ff;">
                    <tr>
                        <td><h4 style="font-size:22px; color:#031b4e; text-align:center;"> <i class="fa-solid fa-circle-check" style="font-size:20px; color:#1e58a3;"></i>  DAY 1 (Port Blair)<h4></td>
                    </tr>
                </thead>
                <tbody>

                    <tr>
                        <td >
                            <table>
                                <tr>
                                    <td>
                                        <span style="background:rgba(238, 51, 94, 0.2); color:rgb(238, 51, 94); border:1px solid rgb(238, 51, 94); display: inline-block; font-size: 11px; text-transform: uppercase; border-radius: 5px; padding:7px; line-height: 1;">
                                            <i class="fas fa-hotel"></i> Hotel | 1 night | in Port Blair
                                        </span>
                                    </td>
                                </tr>

                                <tr>
                                    <td style="width:70%; vertical-align:top;">
                                        <table style="border:1px solid #ccc;">
                                            <tr>
                                                <td>
                                                    <img src="http://christmastree.quickdemo.in/public/storage/hotel/lemon-tree-hotel-1643-20250327073919.webp" style="width:225px; height:120px;">
                                                </td>
                                                <td style="vertical-align:top;">
                                                    <h5 style="font-size:15px; text-transform: uppercase; color: #031b4e;">TSG Emerald View Hotel & Spa<h5>
                                                    <p style="font-size:12px; color:#000; margin-bottom:15px;">Lamba Line, opp. to Airport Gate, Junglighat, Sri Vijaya Puram, 
                                                    Andaman and Nicobar Islands 744103</p>
                                                <p>
                                                    <span style="background:rgba(238, 51, 94, 0.2); color:rgb(238, 51, 94); border:1px solid rgb(238, 51, 94); display: inline-block; font-size: 11px; text-transform: uppercase; border-radius: 5px; padding:4px; line-height: 1;">
                                                        ROOMS
                                                    </span>
                                                </p>

                                                <span style=" color:#4e4e4e; border:1px solid #ddd; display: inline-block; font-size: 12px; text-transform: uppercase; border-radius: 5px; padding:4px; line-height: 1;">Luxury - Non-AC</span>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td colspan="2">
                                                    <table style="border: 1px solid rgba(1, 98, 232, 0.5) !important; width:100%;">
                                                        <tr>
                                                            <td style="padding:0 !important;">
                                                                <table style="table-layout:fixed;">
                                                                    <thead style="font-size:14px; font-weight:600; background: rgba(1, 98, 232, 0.1); color:#031b4e; width:180px; border-bottom: 1px solid rgba(1, 98, 232, 0.5) !important;">
                                                                        <tr>
                                                                            <th style="font-size:11px; text-transform:uppercase; color:#031b4e; padding: 10px;">Plan</th>
                                                                            <th style="font-size:11px; text-transform:uppercase; color:#031b4e; padding: 10px;">Meal Plan</th>
                                                                            <th style="font-size:11px; text-transform:uppercase; color:#031b4e; padding: 10px;">CNB</th>
                                                                            <th style="font-size:11px; text-transform:uppercase; color:#031b4e; padding: 10px;">CWM</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <tr>
                                                                            <td style="vertical-align:top;">
                                                                                <p><span style=" color:#031b4e; border:1px solid #ddd; display: inline-block; font-size: 11px; text-transform: uppercase; border-radius: 5px; padding:4px 6px; line-height: 1;">epa</span></p>
                                                                            </td>
                                                                            <td style="vertical-align:top;">
                                                                                <p><span style=" color:#031b4e; border:1px solid #ddd; display: inline-block; font-size: 11px; text-transform: uppercase; border-radius: 5px; padding:4px 6px; line-height: 1;">breakfast <i style="color:#ee335e;">(5)</i></span></p>
                                                                                <p><span style=" color:#031b4e; border:1px solid #ddd; display: inline-block; font-size: 11px; text-transform: uppercase; border-radius: 5px; padding:4px 6px; line-height: 1;">Lunch <i style="color:#ee335e;">(3)</i></span></p>
                                                                                <p><span style=" color:#031b4e; border:1px solid #ddd; display: inline-block; font-size: 11px; text-transform: uppercase; border-radius: 5px; padding:4px 6px; line-height: 1;">Dinner <i style="color:#ee335e;">(4)</i></span></p>
                                                                            </td>
                                                                            <td style="vertical-align:top;">
                                                                                <p><span style=" color:#031b4e; border:1px solid #ddd; display: inline-block; font-size: 11px; text-transform: uppercase; border-radius: 5px; padding:4px 6px; line-height: 1;">below 3 years <i style="color:#ee335e;">(3)</i></span></p>
                                                                                <p><span style=" color:#031b4e; border:1px solid #ddd; display: inline-block; font-size: 11px; text-transform: uppercase; border-radius: 5px; padding:4px 6px; line-height: 1;">below 1 years <i style="color:#ee335e;">(2)</i></span></p>
                                                                            </td>
                                                                            <td style="vertical-align:top;">
                                                                                <p><span style=" color:#031b4e; border:1px solid #ddd; display: inline-block; font-size: 11px; text-transform: uppercase; border-radius: 5px; padding:4px 6px; line-height: 1;">6-8 years <i style="color:#ee335e;">(3)</i></span></p>
                                                                                <p><span style=" color:#031b4e; border:1px solid #ddd; display: inline-block; font-size: 11px; text-transform: uppercase; border-radius: 5px; padding:4px 6px; line-height: 1;">1-4 years <i style="color:#ee335e;">(1)</i></span></p>
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>

                                    <td style="vertical-align:top;">
                                        <div style=" background: rgba(254, 215, 170, 0.2);  border: 1px solid #fed7aa; padding: 20px; max-width: 419px;">
                                            <ul class="total-ex-list">
                                                <li><i class="fa-solid fa-circle-arrow-right"></i> Day Room Main Plan (1 * 1600) = ₹1600</li>
                                                <li> <i class="fa-solid fa-circle-arrow-right"></i> Day Room Addon Plan Meal Plan (4 * 0) = ₹0</li>
                                                <li><i class="fa-solid fa-circle-arrow-right"></i> Day Room Addon Plan Cwb (1 * 650) = ₹650</li>
                                                <li><i class="fa-solid fa-circle-arrow-right"></i> Per Day Cab (2 * 8000) = ₹16000</li>
                                                <li><i class="fa-solid fa-circle-arrow-right"></i> Day Sightseeing (7 * 78.57) = ₹550</li>
                                            </ul>
                                            <hr style="border: 1px solid #fed7aa; background-color:#fed7aa; margin-top: 16px; margin-bottom: 10px;">
                                            <h3 style="font-size: 13px;">Total Amount: ₹18,930</h3>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                       
                    </tr>

                    <tr>
                        <td style="vertical-align:top;">
                            <table>
                                <tr>
                                    <td>
                                        <span style="background:rgba(238, 51, 94, 0.2); color:rgb(238, 51, 94); border:1px solid rgb(238, 51, 94); display: inline-block; font-size: 11px; text-transform: uppercase; border-radius: 5px; padding:7px; line-height: 1;">
                                            <i class="fas fa-taxi"></i> Cabs | in Port Blair
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="vertical-align:top;">
                                        <table style="border: 1px solid rgba(1, 98, 232, 0.5) !important;">
                                            <thead style="font-size:14px; font-weight:600; background: rgba(1, 98, 232, 0.1); color:#031b4e; width:180px; border-bottom: 1px solid rgba(1, 98, 232, 0.5) !important; text-align:left;">
                                                <tr>
                                                    <th style="font-size:11px; text-transform:uppercase; color:#031b4e; padding: 10px;">CAB</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <table style=" margin-bottom:6px; width:100%;">
                                                            <tr>
                                                                <td style="text-align:center;">
                                                                    <ul style="display:flex; align-items:center; flex-wrap:wrap;">
                                                                        <li style="text-align:center; width:180px; border:1px solid #ccc; margin-bottom:6px; margin-right:6px; padding: 18px 0;">
                                                                            <img width="80" src="https://christmastree.quickdemo.in/assets/img/cab.png" style="margin:auto;">
                                                                            <p>
                                                                                <span style=" color:#031b4e; border:1px solid #ddd; display: inline-block; font-size: 11px; text-transform: uppercase; border-radius: 5px; padding:4px 6px; line-height: 1; background-color: rgba(34, 192, 60, 0.1);
                                                                                color: #22c03c;">Tempo (17S) <i style="color:#ee335e;">(1)</i></span>
                                                                            </p>
                                                                        </li>
                                                                        <li style="text-align:center; width:180px; border:1px solid #ccc; margin-bottom:6px; margin-right:6px; padding: 18px 0;">
                                                                            <img width="80" src="https://christmastree.quickdemo.in/assets/img/cab.png" style="margin:auto;">
                                                                            <p>
                                                                                <span style=" color:#031b4e; border:1px solid #ddd; display: inline-block; font-size: 11px; text-transform: uppercase; border-radius: 5px; padding:4px 6px; line-height: 1; background-color: rgba(34, 192, 60, 0.1);
                                                                                color: #22c03c;">Tempo (17S) <i style="color:#ee335e;">(1)</i></span>
                                                                            </p>
                                                                        </li>
                                                                    </ul>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr >
                        <td style="vertical-align:top;" >
                            <table>
                                <tr>
                                    <td>
                                        <span style="background:rgba(238, 51, 94, 0.2); color:rgb(238, 51, 94); border:1px solid rgb(238, 51, 94); display: inline-block; font-size: 11px; text-transform: uppercase; border-radius: 5px; padding:7px; line-height: 1;">
                                            <i class="fas fa-route"></i> Route | <i class="fas fa-binoculars"></i> Sight | <i class="fas fa-running"></i> Activity |  seen in Port Blair
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="vertical-align:top;">
                                        <table style="border: 1px solid rgba(1, 98, 232, 0.5) !important;">
                                            <thead style="font-size:14px; font-weight:600; background: rgba(1, 98, 232, 0.1); color:#031b4e; width:180px; border-bottom: 1px solid rgba(1, 98, 232, 0.5) !important; text-align:left;">
                                                <tr>
                                                    <th style="font-size:11px; text-transform:uppercase; color:#031b4e; padding: 10px; width:65px; border-right: 1px solid rgba(1, 98, 232, 0.5) !important;">SL.No</th>
                                                    <th style="font-size:11px; text-transform:uppercase; color:#031b4e; padding: 10px; border-right: 1px solid rgba(1, 98, 232, 0.5) !important;">Route</th>
                                                    <th style="font-size:11px; text-transform:uppercase; color:#031b4e; padding: 10px; border-right: 1px solid rgba(1, 98, 232, 0.5) !important;">Sightseen</th>
                                                    <th style="font-size:11px; text-transform:uppercase; color:#031b4e; padding: 10px;">Activity</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr style="border-bottom:1px solid #ccc !important;">
                                                    <td rowspan="2" style="vertical-align:top; border-right: 1px solid #ccc !important;">
                                                        <span style="border: 1px solid rgba(34, 192, 60, 0.8) !important; background-color:rgba(34, 192, 60, 0.1); color:#22c03c; text-align:center; border-radius: 4px; padding: 2px 3px; font-size: 11px;">
                                                            1
                                                        </span>
                                                    </td>

                                                    <td style="vertical-align:top; border-right: 1px solid #ccc !important;">
                                                        <h6 style="font-size:14px; text-transform: uppercase; color: #031b4e; margin-bottom:5px;">Port Blair City Tour</h6>
                                                    </td>

                                                    <td style="vertical-align:top; border-right: 1px solid #ccc !important;">
                                                        <p>
                                                            <span style=" color:#031b4e; border:1px solid #ddd; display: inline-block; font-size: 11px; border-radius: 5px; padding:4px 6px; line-height: 1;">
                                                                Cellular Jail (Historic Freedom Fighter Memorial) <i style="color:#ee335e;">(5)</i>
                                                            </span>
                                                        </p>
                                                        <p>
                                                            <span style=" color:#031b4e; border:1px solid #ddd; display: inline-block; font-size: 11px; border-radius: 5px; padding:4px 6px; line-height: 1;">
                                                                Marina Park & Flag Point (Leisure Walk) <i style="color:#ee335e;">(4)</i>
                                                            </span>
                                                        </p>
                                                    </td>

                                                    <td style="vertical-align:top;">
                                                        <p>
                                                            <span style=" color:#031b4e; border:1px solid #ddd; display: inline-block; font-size: 11px; border-radius: 5px; padding:4px 6px; line-height: 1;">
                                                                Light & Sound Show at Cellular Jail (Evening) <i style="color:#ee335e;">(5)</i>
                                                            </span>
                                                        </p>
                                                    </td>
                                                    
                                                </tr>

                                                <tr style="border-bottom:1px solid #ccc;">
                                                    <td colspan="4">
                                                        <table style="">
                                                            <tbody>
                                                                <tr>
                                                                    <td >
                                                                        <ul style="display:flex; align-items:center; flex-wrap:wrap;">
                                                                            <li style="text-align:center; width:180px; border:1px solid #ccc; margin-bottom:6px; margin-right:6px; padding: 18px 0;">
                                                                                <img width="80" src="https://christmastree.quickdemo.in/assets/img/cab.png" style="margin:auto;">
                                                                                <p>
                                                                                    <span style=" color:#031b4e; border:1px solid #ddd; display: inline-block; font-size: 11px; text-transform: uppercase; border-radius: 5px; padding:4px 6px; line-height: 1; background-color: rgba(34, 192, 60, 0.1);
                                                                                    color: #22c03c;">Tempo (17S) <i style="color:#ee335e;">(1)</i></span>
                                                                                </p>
                                                                            </li>
                                                                            <li style="text-align:center; width:180px; border:1px solid #ccc; margin-bottom:6px; margin-right:6px; padding: 18px 0;">
                                                                                <img width="80" src="https://christmastree.quickdemo.in/assets/img/cab.png" style="margin:auto;">
                                                                                <p>
                                                                                    <span style=" color:#031b4e; border:1px solid #ddd; display: inline-block; font-size: 11px; text-transform: uppercase; border-radius: 5px; padding:4px 6px; line-height: 1; background-color: rgba(34, 192, 60, 0.1);
                                                                                    color: #22c03c;">Tempo (17S) <i style="color:#ee335e;">(1)</i></span>
                                                                                </p>
                                                                            </li>
                                                                        </ul>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td rowspan="2" style="vertical-align:top; border-right: 1px solid #ccc !important;">
                                                        <span style="border: 1px solid rgba(34, 192, 60, 0.8) !important; background-color:rgba(34, 192, 60, 0.1); color:#22c03c; text-align:center; border-radius: 4px; padding: 2px 3px; font-size: 11px;">
                                                            2
                                                        </span>
                                                    </td>

                                                    <td style="vertical-align:top; border-right: 1px solid #ccc !important;">
                                                        <h6 style="font-size:14px; text-transform: uppercase; color: #031b4e; margin-bottom:5px;">Port Blair City Tour</h6>
                                                    </td>

                                                    <td style="vertical-align:top; border-right: 1px solid #ccc !important;">
                                                        <p>
                                                            <span style=" color:#031b4e; border:1px solid #ddd; display: inline-block; font-size: 11px; border-radius: 5px; padding:4px 6px; line-height: 1;">
                                                                Cellular Jail (Historic Freedom Fighter Memorial) <i style="color:#ee335e;">(5)</i>
                                                            </span>
                                                        </p>
                                                        <p>
                                                            <span style=" color:#031b4e; border:1px solid #ddd; display: inline-block; font-size: 11px; border-radius: 5px; padding:4px 6px; line-height: 1;">
                                                                Marina Park & Flag Point (Leisure Walk) <i style="color:#ee335e;">(4)</i>
                                                            </span>
                                                        </p>
                                                    </td>

                                                    <td style="vertical-align:top;">
                                                        <p>
                                                            <span style=" color:#031b4e; border:1px solid #ddd; display: inline-block; font-size: 11px; border-radius: 5px; padding:4px 6px; line-height: 1;">
                                                                Light & Sound Show at Cellular Jail (Evening) <i style="color:#ee335e;">(5)</i>
                                                            </span>
                                                        </p>
                                                    </td>
                                                    
                                                </tr>



                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                </tbody>
            </table>

            <table style="border:1px solid rgba(1, 98, 232, 0.5) !important" class="">
                <thead class="bg-color" style="background-color:#d2e8ff;">
                    <tr>
                        <td><h4 style="font-size:22px; color:#031b4e; text-align:center;"> <i class="fa-solid fa-circle-check" style="font-size:20px; color:#1e58a3;"></i>  DAY 2 (Port Blair)<h4></td>
                    </tr>
                </thead>
                <tbody>

                    <tr>
                        <td >
                            <table>
                                <tr>
                                    <td>
                                        <span style="background:rgba(238, 51, 94, 0.2); color:rgb(238, 51, 94); border:1px solid rgb(238, 51, 94); display: inline-block; font-size: 11px; text-transform: uppercase; border-radius: 5px; padding:7px; line-height: 1;">
                                            <i class="fas fa-hotel"></i> Hotel | 1 night | in Port Blair
                                        </span>
                                    </td>
                                </tr>

                                <tr>
                                    <td style="width:70%; vertical-align:top;">
                                        <table style="border:1px solid #ccc;">
                                            <tr>
                                                <td>
                                                    <img src="http://christmastree.quickdemo.in/public/storage/hotel/lemon-tree-hotel-1643-20250327073919.webp" style="width:225px; height:120px;">
                                                </td>
                                                <td style="vertical-align:top;">
                                                    <h5 style="font-size:15px; text-transform: uppercase; color: #031b4e;">TSG Emerald View Hotel & Spa<h5>
                                                    <p style="font-size:12px; color:#000; margin-bottom:15px;">Lamba Line, opp. to Airport Gate, Junglighat, Sri Vijaya Puram, 
                                                    Andaman and Nicobar Islands 744103</p>
                                                <p>
                                                    <span style="background:rgba(238, 51, 94, 0.2); color:rgb(238, 51, 94); border:1px solid rgb(238, 51, 94); display: inline-block; font-size: 11px; text-transform: uppercase; border-radius: 5px; padding:4px; line-height: 1;">
                                                        ROOMS
                                                    </span>
                                                </p>

                                                <span style=" color:#4e4e4e; border:1px solid #ddd; display: inline-block; font-size: 12px; text-transform: uppercase; border-radius: 5px; padding:4px; line-height: 1;">Luxury - Non-AC</span>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td colspan="2">
                                                    <table style="border: 1px solid rgba(1, 98, 232, 0.5) !important; width:100%;">
                                                        <tr>
                                                            <td style="padding:0 !important;">
                                                                <table style="table-layout:fixed;">
                                                                    <thead style="font-size:14px; font-weight:600; background: rgba(1, 98, 232, 0.1); color:#031b4e; width:180px; border-bottom: 1px solid rgba(1, 98, 232, 0.5) !important;">
                                                                        <tr>
                                                                            <th style="font-size:11px; text-transform:uppercase; color:#031b4e; padding: 10px;">Plan</th>
                                                                            <th style="font-size:11px; text-transform:uppercase; color:#031b4e; padding: 10px;">Meal Plan</th>
                                                                            <th style="font-size:11px; text-transform:uppercase; color:#031b4e; padding: 10px;">CNB</th>
                                                                            <th style="font-size:11px; text-transform:uppercase; color:#031b4e; padding: 10px;">CWM</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <tr>
                                                                            <td style="vertical-align:top;">
                                                                                <p><span style=" color:#031b4e; border:1px solid #ddd; display: inline-block; font-size: 11px; text-transform: uppercase; border-radius: 5px; padding:4px 6px; line-height: 1;">epa</span></p>
                                                                            </td>
                                                                            <td style="vertical-align:top;">
                                                                                <p><span style=" color:#031b4e; border:1px solid #ddd; display: inline-block; font-size: 11px; text-transform: uppercase; border-radius: 5px; padding:4px 6px; line-height: 1;">breakfast <i style="color:#ee335e;">(5)</i></span></p>
                                                                                <p><span style=" color:#031b4e; border:1px solid #ddd; display: inline-block; font-size: 11px; text-transform: uppercase; border-radius: 5px; padding:4px 6px; line-height: 1;">Lunch <i style="color:#ee335e;">(3)</i></span></p>
                                                                                <p><span style=" color:#031b4e; border:1px solid #ddd; display: inline-block; font-size: 11px; text-transform: uppercase; border-radius: 5px; padding:4px 6px; line-height: 1;">Dinner <i style="color:#ee335e;">(4)</i></span></p>
                                                                            </td>
                                                                            <td style="vertical-align:top;">
                                                                                <p><span style=" color:#031b4e; border:1px solid #ddd; display: inline-block; font-size: 11px; text-transform: uppercase; border-radius: 5px; padding:4px 6px; line-height: 1;">below 3 years <i style="color:#ee335e;">(3)</i></span></p>
                                                                                <p><span style=" color:#031b4e; border:1px solid #ddd; display: inline-block; font-size: 11px; text-transform: uppercase; border-radius: 5px; padding:4px 6px; line-height: 1;">below 1 years <i style="color:#ee335e;">(2)</i></span></p>
                                                                            </td>
                                                                            <td style="vertical-align:top;">
                                                                                <p><span style=" color:#031b4e; border:1px solid #ddd; display: inline-block; font-size: 11px; text-transform: uppercase; border-radius: 5px; padding:4px 6px; line-height: 1;">6-8 years <i style="color:#ee335e;">(3)</i></span></p>
                                                                                <p><span style=" color:#031b4e; border:1px solid #ddd; display: inline-block; font-size: 11px; text-transform: uppercase; border-radius: 5px; padding:4px 6px; line-height: 1;">1-4 years <i style="color:#ee335e;">(1)</i></span></p>
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>

                                    <td style="vertical-align:top;">
                                        <div style=" background: rgba(254, 215, 170, 0.2);  border: 1px solid #fed7aa; padding: 20px; max-width: 419px;">
                                            <ul class="total-ex-list">
                                                <li><i class="fa-solid fa-circle-arrow-right"></i> Day Room Main Plan (1 * 1600) = ₹1600</li>
                                                <li> <i class="fa-solid fa-circle-arrow-right"></i> Day Room Addon Plan Meal Plan (4 * 0) = ₹0</li>
                                                <li><i class="fa-solid fa-circle-arrow-right"></i> Day Room Addon Plan Cwb (1 * 650) = ₹650</li>
                                                <li><i class="fa-solid fa-circle-arrow-right"></i> Per Day Cab (2 * 8000) = ₹16000</li>
                                                <li><i class="fa-solid fa-circle-arrow-right"></i> Day Sightseeing (7 * 78.57) = ₹550</li>
                                            </ul>
                                            <hr style="border: 1px solid #fed7aa; background-color:#fed7aa; margin-top: 16px; margin-bottom: 10px;">
                                            <h3 style="font-size: 13px;">Total Amount: ₹18,930</h3>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                       
                    </tr>

                    <tr>
                        <td style="vertical-align:top;">
                            <table>
                                <tr>
                                    <td>
                                        <span style="background:rgba(238, 51, 94, 0.2); color:rgb(238, 51, 94); border:1px solid rgb(238, 51, 94); display: inline-block; font-size: 11px; text-transform: uppercase; border-radius: 5px; padding:7px; line-height: 1;">
                                            <i class="fas fa-taxi"></i> Cabs | in Port Blair
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="vertical-align:top;">
                                        <table style="border: 1px solid rgba(1, 98, 232, 0.5) !important;">
                                            <thead style="font-size:14px; font-weight:600; background: rgba(1, 98, 232, 0.1); color:#031b4e; width:180px; border-bottom: 1px solid rgba(1, 98, 232, 0.5) !important; text-align:left;">
                                                <tr>
                                                    <th style="font-size:11px; text-transform:uppercase; color:#031b4e; padding: 10px;">CAB</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <table style=" margin-bottom:6px; width:100%;">
                                                            <tr>
                                                                <td style="text-align:center;">
                                                                    <ul style="display:flex; align-items:center; flex-wrap:wrap;">
                                                                        <li style="text-align:center; width:180px; border:1px solid #ccc; margin-bottom:6px; margin-right:6px; padding: 18px 0;">
                                                                            <img width="80" src="https://christmastree.quickdemo.in/assets/img/cab.png" style="margin:auto;">
                                                                            <p>
                                                                                <span style=" color:#031b4e; border:1px solid #ddd; display: inline-block; font-size: 11px; text-transform: uppercase; border-radius: 5px; padding:4px 6px; line-height: 1; background-color: rgba(34, 192, 60, 0.1);
                                                                                color: #22c03c;">Tempo (17S) <i style="color:#ee335e;">(1)</i></span>
                                                                            </p>
                                                                        </li>
                                                                        <li style="text-align:center; width:180px; border:1px solid #ccc; margin-bottom:6px; margin-right:6px; padding: 18px 0;">
                                                                            <img width="80" src="https://christmastree.quickdemo.in/assets/img/cab.png" style="margin:auto;">
                                                                            <p>
                                                                                <span style=" color:#031b4e; border:1px solid #ddd; display: inline-block; font-size: 11px; text-transform: uppercase; border-radius: 5px; padding:4px 6px; line-height: 1; background-color: rgba(34, 192, 60, 0.1);
                                                                                color: #22c03c;">Tempo (17S) <i style="color:#ee335e;">(1)</i></span>
                                                                            </p>
                                                                        </li>
                                                                    </ul>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td style="vertical-align:top;">
                            <table>
                                <tr>
                                    <td>
                                        <span style="background:rgba(238, 51, 94, 0.2); color:rgb(238, 51, 94); border:1px solid rgb(238, 51, 94); display: inline-block; font-size: 11px; text-transform: uppercase; border-radius: 5px; padding:7px; line-height: 1;">
                                            <i class="fas fa-route"></i> Route | <i class="fas fa-binoculars"></i> Sight | <i class="fas fa-running"></i> Activity |  seen in Port Blair
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="vertical-align:top;">
                                        <table style="border: 1px solid rgba(1, 98, 232, 0.5) !important;">
                                            <thead style="font-size:14px; font-weight:600; background: rgba(1, 98, 232, 0.1); color:#031b4e; width:180px; border-bottom: 1px solid rgba(1, 98, 232, 0.5) !important; text-align:left;">
                                                <tr>
                                                    <th style="font-size:11px; text-transform:uppercase; color:#031b4e; padding: 10px; width:65px; border-right: 1px solid rgba(1, 98, 232, 0.5) !important;">SL.No</th>
                                                    <th style="font-size:11px; text-transform:uppercase; color:#031b4e; padding: 10px; border-right: 1px solid rgba(1, 98, 232, 0.5) !important;">Route</th>
                                                    <th style="font-size:11px; text-transform:uppercase; color:#031b4e; padding: 10px; border-right: 1px solid rgba(1, 98, 232, 0.5) !important;">Sightseen</th>
                                                    <th style="font-size:11px; text-transform:uppercase; color:#031b4e; padding: 10px;">Activity</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr style="border-bottom:1px solid #ccc !important;">
                                                    <td rowspan="2" style="vertical-align:top; border-right: 1px solid #ccc !important;">
                                                        <span style="border: 1px solid rgba(34, 192, 60, 0.8) !important; background-color:rgba(34, 192, 60, 0.1); color:#22c03c; text-align:center; border-radius: 4px; padding: 2px 3px; font-size: 11px;">
                                                            1
                                                        </span>
                                                    </td>

                                                    <td style="vertical-align:top; border-right: 1px solid #ccc !important;">
                                                        <h6 style="font-size:14px; text-transform: uppercase; color: #031b4e; margin-bottom:5px;">Port Blair City Tour</h6>
                                                    </td>

                                                    <td style="vertical-align:top; border-right: 1px solid #ccc !important;">
                                                        <p>
                                                            <span style=" color:#031b4e; border:1px solid #ddd; display: inline-block; font-size: 11px; border-radius: 5px; padding:4px 6px; line-height: 1;">
                                                                Cellular Jail (Historic Freedom Fighter Memorial) <i style="color:#ee335e;">(5)</i>
                                                            </span>
                                                        </p>
                                                        <p>
                                                            <span style=" color:#031b4e; border:1px solid #ddd; display: inline-block; font-size: 11px; border-radius: 5px; padding:4px 6px; line-height: 1;">
                                                                Marina Park & Flag Point (Leisure Walk) <i style="color:#ee335e;">(4)</i>
                                                            </span>
                                                        </p>
                                                    </td>

                                                    <td style="vertical-align:top;">
                                                        <p>
                                                            <span style=" color:#031b4e; border:1px solid #ddd; display: inline-block; font-size: 11px; border-radius: 5px; padding:4px 6px; line-height: 1;">
                                                                Light & Sound Show at Cellular Jail (Evening) <i style="color:#ee335e;">(5)</i>
                                                            </span>
                                                        </p>
                                                    </td>
                                                    
                                                </tr>

                                                <tr style="border-bottom:1px solid #ccc;">
                                                    <td colspan="4">
                                                        <table style="">
                                                            <tbody>
                                                                <tr>
                                                                    <td >
                                                                        <ul style="display:flex; align-items:center; flex-wrap:wrap;">
                                                                            <li style="text-align:center; width:180px; border:1px solid #ccc; margin-bottom:6px; margin-right:6px; padding: 18px 0;">
                                                                                <img width="80" src="https://christmastree.quickdemo.in/assets/img/cab.png" style="margin:auto;">
                                                                                <p>
                                                                                    <span style=" color:#031b4e; border:1px solid #ddd; display: inline-block; font-size: 11px; text-transform: uppercase; border-radius: 5px; padding:4px 6px; line-height: 1; background-color: rgba(34, 192, 60, 0.1);
                                                                                    color: #22c03c;">Tempo (17S) <i style="color:#ee335e;">(1)</i></span>
                                                                                </p>
                                                                            </li>
                                                                            <li style="text-align:center; width:180px; border:1px solid #ccc; margin-bottom:6px; margin-right:6px; padding: 18px 0;">
                                                                                <img width="80" src="https://christmastree.quickdemo.in/assets/img/cab.png" style="margin:auto;">
                                                                                <p>
                                                                                    <span style=" color:#031b4e; border:1px solid #ddd; display: inline-block; font-size: 11px; text-transform: uppercase; border-radius: 5px; padding:4px 6px; line-height: 1; background-color: rgba(34, 192, 60, 0.1);
                                                                                    color: #22c03c;">Tempo (17S) <i style="color:#ee335e;">(1)</i></span>
                                                                                </p>
                                                                            </li>
                                                                        </ul>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td rowspan="2" style="vertical-align:top; border-right: 1px solid #ccc !important;">
                                                        <span style="border: 1px solid rgba(34, 192, 60, 0.8) !important; background-color:rgba(34, 192, 60, 0.1); color:#22c03c; text-align:center; border-radius: 4px; padding: 2px 3px; font-size: 11px;">
                                                            2
                                                        </span>
                                                    </td>

                                                    <td style="vertical-align:top; border-right: 1px solid #ccc !important;">
                                                        <h6 style="font-size:14px; text-transform: uppercase; color: #031b4e; margin-bottom:5px;">Port Blair City Tour</h6>
                                                    </td>

                                                    <td style="vertical-align:top; border-right: 1px solid #ccc !important;">
                                                        <p>
                                                            <span style=" color:#031b4e; border:1px solid #ddd; display: inline-block; font-size: 11px; border-radius: 5px; padding:4px 6px; line-height: 1;">
                                                                Cellular Jail (Historic Freedom Fighter Memorial) <i style="color:#ee335e;">(5)</i>
                                                            </span>
                                                        </p>
                                                        <p>
                                                            <span style=" color:#031b4e; border:1px solid #ddd; display: inline-block; font-size: 11px; border-radius: 5px; padding:4px 6px; line-height: 1;">
                                                                Marina Park & Flag Point (Leisure Walk) <i style="color:#ee335e;">(4)</i>
                                                            </span>
                                                        </p>
                                                    </td>

                                                    <td style="vertical-align:top;">
                                                        <p>
                                                            <span style=" color:#031b4e; border:1px solid #ddd; display: inline-block; font-size: 11px; border-radius: 5px; padding:4px 6px; line-height: 1;">
                                                                Light & Sound Show at Cellular Jail (Evening) <i style="color:#ee335e;">(5)</i>
                                                            </span>
                                                        </p>
                                                    </td>
                                                    
                                                </tr>



                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                </tbody>
            </table>
        </td>
    </tr>

    <tr>
        <td>
            <table style="border:1px solid rgba(1, 98, 232, 0.5) !important">
                <thead style="background-color:#d2e8ff;">
                    <tr>
                        <th>
                            <h4 style="font-size:22px; color:#031b4e; text-align:center;"> <i class="fa-solid fa-circle-check" style="font-size:20px; color:#1e58a3;"></i> Hotel Summery </h4>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                           <ul class="hotel-list">
                                <li>
                                    <figure>
                                        <img src="http://christmastree.quickdemo.in/public/storage/hotel/lemon-tree-hotel-1643-20250327073919.webp">
                                    </figure>
                                    <figcaption>
                                        <h3>Day 1</h3>
                                        <h2>TSG Emerald View Hotel &amp; Spa</h2>
                                        <p>Lamba Line, opp. to Airport Gate, Junglighat, Sri Vijaya Puram, Andaman and Nicobar Islands 744103</p>
                                    </figcaption>
                                </li>

                                <li>
                                    <figure>
                                        <img src="http://christmastree.quickdemo.in/public/storage/hotel/lemon-tree-hotel-1643-20250327073919.webp">
                                    </figure>
                                    <figcaption>
                                        <h3>Day 2</h3>
                                        <h2>TSG Emerald View Hotel &amp; Spa</h2>
                                        <p>Lamba Line, opp. to Airport Gate, Junglighat, Sri Vijaya Puram, Andaman and Nicobar Islands 744103</p>
                                    </figcaption>
                                </li>

                                <li>
                                    <figure>
                                        <img src="http://christmastree.quickdemo.in/public/storage/hotel/lemon-tree-hotel-1643-20250327073919.webp">
                                    </figure>
                                    <figcaption>
                                        <h3>Day 3</h3>
                                        <h2>TSG Emerald View Hotel &amp; Spa</h2>
                                        <p>Lamba Line, opp. to Airport Gate, Junglighat, Sri Vijaya Puram, Andaman and Nicobar Islands 744103</p>
                                    </figcaption>
                                </li>
                           </ul>
                        </td>
                    </tr>
                </tbody>
            </table>
        </td>
    </tr>


    <tr>
        <td>
            <table style="border:1px solid rgba(1, 98, 232, 0.5) !important">
                <thead style="background-color:#d2e8ff;">
                    <tr>
                        <th>
                            <h4 style="font-size:22px; color:#031b4e; text-align:center;"> <i class="fa-solid fa-circle-check" style="font-size:20px; color:#1e58a3;"></i> Activities Summery </h4>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                           <ul class="activity-list">
                                <li style="background:url(http://christmastree.quickdemo.in/public/storage/hotel/lemon-tree-hotel-1643-20250327073919.webp); background-repeat:no-repeat; background-size:cover; background-position:center center;">
                                    <figcaption>
                                        <h2>Light & Sound Show at Cellular Jail (Evening) </h2>
                                    </figcaption>
                                </li>
                                <li style="background:url(http://christmastree.quickdemo.in/public/storage/hotel/lemon-tree-hotel-1643-20250327073919.webp); background-repeat:no-repeat; background-size:cover; background-position:center center;">
                                    <figcaption>
                                        <h2>Light & Sound Show at Cellular Jail (Evening) </h2>
                                    </figcaption>
                                </li>
                                <li style="background:url(http://christmastree.quickdemo.in/public/storage/hotel/lemon-tree-hotel-1643-20250327073919.webp); background-repeat:no-repeat; background-size:cover; background-position:center center;">
                                    <figcaption>
                                        <h2>Light & Sound Show at Cellular Jail (Evening) </h2>
                                    </figcaption>
                                </li>
                                <li style="background:url(http://christmastree.quickdemo.in/public/storage/hotel/lemon-tree-hotel-1643-20250327073919.webp); background-repeat:no-repeat; background-size:cover; background-position:center center;">
                                    <figcaption>
                                        <h2>Light & Sound Show at Cellular Jail (Evening) </h2>
                                    </figcaption>
                                </li>
                                <li style="background:url(http://christmastree.quickdemo.in/public/storage/hotel/lemon-tree-hotel-1643-20250327073919.webp); background-repeat:no-repeat; background-size:cover; background-position:center center;">
                                    <figcaption>
                                        <h2>Light & Sound Show at Cellular Jail (Evening) </h2>
                                    </figcaption>
                                </li>
                           </ul>
                        </td>
                    </tr>
                </tbody>
            </table>
        </td>
    </tr>

    <tr>
        <td>
            <table style="border:1px solid rgba(1, 98, 232, 0.5) !important">
                <thead style="background-color:#d2e8ff;">
                    <tr>
                        <th>
                            <h4 style="font-size:22px; color:#031b4e; text-align:center;"> <i class="fa-solid fa-circle-check" style="font-size:20px; color:#1e58a3;"></i> Exclusions </h4>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                           <p>
                                <span style=" color:#031b4e; border:1px solid #ddd; display: inline-block; font-size: 11px; border-radius: 5px; padding:4px 6px; line-height: 1; margin-bottom:5px;">
                                    1. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
                                </span>
                            </p>
                           <p>
                                <span style=" color:#031b4e; border:1px solid #ddd; display: inline-block; font-size: 11px; border-radius: 5px; padding:4px 6px; line-height: 1; margin-bottom:5px;">
                                    2. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
                                </span>
                            </p>
                           <p>
                                <span style=" color:#031b4e; border:1px solid #ddd; display: inline-block; font-size: 11px; border-radius: 5px; padding:4px 6px; line-height: 1; margin-bottom:5px;">
                                    3. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
                                </span>
                            </p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </td>
    </tr>

    <tr class="keep-together">
        <td>
            <table style="border:1px solid rgba(1, 98, 232, 0.5) !important">
                <thead style="background-color:#d2e8ff;">
                    <tr>
                        <th>
                            <h4 style="font-size:22px; color:#031b4e; text-align:center;"> <i class="fa-solid fa-circle-check" style="font-size:20px; color:#1e58a3;"></i> Inclusions </h4>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                           <p>
                                <span style=" color:#031b4e; border:1px solid #ddd; display: inline-block; font-size: 11px; border-radius: 5px; padding:4px 6px; line-height: 1; margin-bottom:5px;">
                                    1. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
                                </span>
                            </p>
                           <p>
                                <span style=" color:#031b4e; border:1px solid #ddd; display: inline-block; font-size: 11px; border-radius: 5px; padding:4px 6px; line-height: 1; margin-bottom:5px;">
                                    2. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
                                </span>
                            </p>
                           <p>
                                <span style=" color:#031b4e; border:1px solid #ddd; display: inline-block; font-size: 11px; border-radius: 5px; padding:4px 6px; line-height: 1; margin-bottom:5px;">
                                    3. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
                                </span>
                            </p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </td>
    </tr>

</table>



   
@endsection

@section('scripts')

<script>
    function printDiv(divId) {
        let printContents = document.getElementById(divId).innerHTML;
        let originalContents = document.body.innerHTML;

        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
        location.reload(); // to reload Livewire components properly
    }
</script>
@endsection