
@extends('layouts.master')
@section('title', $common['pageTitle']) <!-- This sets the page title dynamically -->
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
        font-size: 14px;
        margin-bottom: 7px;
    }
    .total-ex-list li i {
        color: #f2a144;
        margin-right:10px;
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
    <div class="my-auto">
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
    </div>

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
        🖨️ Print This Section
        </button>
    </div>
</div>



<table class="table" id="print-section">
    <tr>
        <td>
            <table>
                <tr>
                    <td style="vertical-align:top;" class="">
                        <table>
                            <tr>
                                <td style="width:120px; font-weight:600; color: #005390;">Client:</td>
                                <td style="text-align:left;">Sandipa Das</td>
                            </tr>
                            <tr>
                                <td style="width:120px; font-weight:600; color: #005390;">Client Email:</td>
                                <td style="text-align:left;">sandi@gmail.com</td>
                            </tr>
                            <tr>
                                <td style="width:120px; font-weight:600; color: #005390;">Client Mobile:</td>
                                <td style="text-align:left;">+91 9845764446</td>
                            </tr>
                        </table>
                    </td>

                    <td style="vertical-align:top;">
                    <table>
                        <tr>
                            <td style="width:120px; font-weight:600; color: #005390;">Travel Date:</td>
                            <td style="text-align:left;">22-04-2025</td>
                        </tr>
                        <tr>
                            <td style="width:120px; font-weight:600; color: #005390;">Andaman Trip:</td>
                            <td style="text-align:left;">5D/4N</td>
                        </tr>
                        <tr>
                            <td style="width:120px; font-weight:600; color: #005390;">Pax:</td>
                            <td style="text-align:left;">6</td>
                        </tr>
                        <tr>
                            <td style="width:120px; font-weight:600; color: #005390;">Adults:</td>
                            <td style="text-align:left;">4</td>
                        </tr>
                        <tr>
                            <td style="width:120px; font-weight:600; color: #005390;">Child:</td>
                            <td style="text-align:left;">2</td>
                        </tr>
                    </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    <tr >
        <td colspan="2">
            <table style="border:1px solid rgba(1, 98, 232, 0.5) !important">
                <thead class="bg-color" style="background-color:#d2e8ff;">
                    <tr>
                        <td><h4 style="font-size:22px; color:#031b4e;"> <i class="fa-solid fa-circle-check" style="font-size:20px; color:#1e58a3;"></i>  DAY 1 (Port Blair)<h4></td>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="2">
                            <table>
                                <tr>
                                    <td>
                                        <h5 style="font-size:20px; text-transform: uppercase; color: #031b4e;">TSG Emerald View Hotel & Spa<h5>
                                        <p style="font-size:13px; color:#000;">Lamba Line, opp. to Airport Gate, Junglighat, Sri Vijaya Puram, 
                                        Andaman and Nicobar Islands 744103</p>
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        <table style="border:1px solid rgba(1, 98, 232, 0.5);">
                                            <tbody>
                                                <tr>
                                                    <td style="font-size:14px; font-weight:600; background: rgba(1, 98, 232, 0.1); color:#031b4e; width:180px; border-bottom: 1px solid rgba(1, 98, 232, 0.5) !important; border-right: 1px solid rgba(1, 98, 232, 0.5) !important;">Rooms</td>
                                                    <td style="font-size:13px; border-bottom: 1px solid rgba(1, 98, 232, 0.5) !important; color:#838383;">Luxury - Non-AC <strong><i class="fa-solid fa-circle-arrow-right"></i> 10</strong></td>
                                                </tr>

                                                <tr>
                                                    <td style="font-size:14px; font-weight:600; background: rgba(1, 98, 232, 0.1); color:#031b4e; width:180px; border-bottom: 1px solid rgba(1, 98, 232, 0.5) !important; border-right: 1px solid rgba(1, 98, 232, 0.5) !important;">Plan</td>
                                                    <td style="font-size:13px; border-bottom: 1px solid rgba(1, 98, 232, 0.5) !important; color:#838383;">CP</td>
                                                </tr>
                                                <tr>
                                                    <td style="font-size:14px; font-weight:600; background: rgba(1, 98, 232, 0.1); width:180px; color:#031b4e; border-bottom: 1px solid rgba(1, 98, 232, 0.5) !important; border-right: 1px solid rgba(1, 98, 232, 0.5) !important;">CWB</td>
                                                    <td style="font-size:13px; border-bottom: 1px solid rgba(1, 98, 232, 0.5) !important; color:#838383;">
                                                        <ul>
                                                            <li> <span><i class="fa-solid fa-child"></i>  6-7 Years <i class="fa-solid fa-circle-arrow-right"></i> 2</span></li>
                                                            <li> <span><i class="fa-solid fa-child"></i>  3-6 Years <i class="fa-solid fa-circle-arrow-right"></i> 1</span></li>
                                                        </ul>
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
                        <td colspan="2">
                            <table>
                                <tr>
                                    <td>
                                        <h5 style="font-size:20px; text-transform: uppercase; color: #031b4e;">Cabs<h5>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <table style="border:1px solid rgba(1, 98, 232, 0.5);">
                                            <tbody>
                                                <tr>
                                                    <td style="font-size:14px; font-weight:600; background: rgba(1, 98, 232, 0.1); width:180px; color:#031b4e; border-bottom: 1px solid rgba(1, 98, 232, 0.5) !important; border-right: 1px solid rgba(1, 98, 232, 0.5) !important;">Tempo (23S)</td>
                                                    <td style="font-size:13px; border-bottom: 1px solid rgba(1, 98, 232, 0.5) !important; color:#838383;"><i class="fa-solid fa-car-side"></i> 2</td>
                                                </tr>

                                                <!--<tr>-->
                                                <!--    <td style="font-size:14px; font-weight:600; background: rgba(1, 98, 232, 0.1); width:180px; color:#031b4e; border-bottom: 1px solid rgba(1, 98, 232, 0.5) !important; border-right: 1px solid rgba(1, 98, 232, 0.5) !important;">Sedan (4S)</td>-->
                                                <!--    <td style="font-size:13px; border-bottom: 1px solid rgba(1, 98, 232, 0.5) !important; color:#838383;"> <i class="fa-solid fa-car-side"></i> 1</td>-->
                                                <!--</tr>-->
                                                <tr>
                                                    <td style="font-size:14px; font-weight:600; background: rgba(1, 98, 232, 0.1); width:180px; color:#031b4e; border-bottom: 1px solid rgba(1, 98, 232, 0.5) !important; border-right: 1px solid rgba(1, 98, 232, 0.5) !important;">Kia Carens (4S)</td>
                                                    <td style="font-size:13px; border-bottom: 1px solid rgba(1, 98, 232, 0.5) !important; color:#838383;;">
                                                      <i class="fa-solid fa-car-side"></i> 1
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
                        <td colspan="2">
                            <table>
                                <tr>
                                    <td>
                                        <h5 style="font-size:20px; text-transform: uppercase; color: #031b4e;">Routes<h5>
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        <table style="border:1px solid rgba(1, 98, 232, 0.5);">
                                            <tbody>
                                                <tr>
                                                    <td style="border: 1px solid rgba(34, 192, 60, 0.8) !important; background-color:rgba(34, 192, 60, 0.1); color:#22c03c; width:44px; text-align:center;" rowspan="3">1</td>
                                                    <td style="font-size:14px; font-weight:600; background: rgba(1, 98, 232, 0.1); width:180px; color:#031b4e; border-bottom: 1px solid rgba(1, 98, 232, 0.5) !important; border-right: 1px solid rgba(1, 98, 232, 0.5) !important;">
                                                        <h4 style=" font-size: 16px; margin-bottom: 12px; color: #031b4e; text-transform: uppercase;">Port Blair City Tour</h4>
                                                    </td>
                                                    <td style="font-size:13px; border-bottom: 1px solid rgba(1, 98, 232, 0.5) !important; background:rgb(255, 255, 255);">
                                                        <ul class="route-style">
                                                            <li>Airport Pickup</li>
                                                            <li>Hotel Check-in</li>
                                                            <li>Hotel To Cellular Jail</li>
                                                            <li>Cellular Jail To Hotel (Night Drop)</li>
                                                            <li>Hotel To Cellular Jail (for Light & Sound Show)</li>
                                                            <li>Marina Park To Hotel (Rest Time)</li>
                                                            <li>Cellular Jail To Marina Park & Flag Point</li>
                                                        </ul>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="font-size:14px; font-weight:600; background: rgba(1, 98, 232, 0.1); width:180px; color:#031b4e; border-bottom: 1px solid rgba(1, 98, 232, 0.5) !important; border-right: 1px solid rgba(1, 98, 232, 0.5) !important;">Sihghttseeings</td>
                                                    <td style="font-size:13px; border-bottom: 1px solid rgba(1, 98, 232, 0.5) !important; background:rgb(255, 255, 255);">
                                                      <span style="display:block; color:#838383;"><i class="fa-solid fa-circle-arrow-right"></i> Cellular Jail (Historic Freedom Fighter Memorial)</span>
                                                      <span style="display:block; color:#838383;"><i class="fa-solid fa-circle-arrow-right"></i> Marina Park & Flag Point (Leisure Walk)</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="font-size:14px; font-weight:600; background: rgba(1, 98, 232, 0.1); width:180px; color:#031b4e; border-bottom: 1px solid rgba(1, 98, 232, 0.5) !important; border-right: 1px solid rgba(1, 98, 232, 0.5) !important;">Activities</td>
                                                    <td style="font-size:13px; border-bottom: 1px solid rgba(1, 98, 232, 0.5) !important; background:rgb(255, 255, 255);">
                                                      <span style="display:block; color:#838383;"><i class="fa-solid fa-circle-arrow-right"></i> Light & Sound Show at Cellular Jail (Evening)</span>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        <table style="border:1px solid rgba(1, 98, 232, 0.5);">
                                            <tbody>
                                                <tr>
                                                    <td style="border: 1px solid rgba(34, 192, 60, 0.8) !important; background-color:rgba(34, 192, 60, 0.1); color:#22c03c; width:44px; text-align:center;" rowspan="3">2</td>
                                                    <td style="font-size:14px; font-weight:600; background: rgba(1, 98, 232, 0.1); width:180px; color:#031b4e; border-bottom: 1px solid rgba(1, 98, 232, 0.5) !important; border-right: 1px solid rgba(1, 98, 232, 0.5) !important;"><h4 style=" font-size: 16px; margin-bottom: 12px; color: #031b4e; text-transform: uppercase;"> Ross Island & North Bay Excursion</h4></td>
                                                    <td style="font-size:13px; border-bottom: 1px solid rgba(1, 98, 232, 0.5) !important; background:rgb(255, 255, 255);">
                                                        <ul class="route-style">
                                                            <li>Airport Pickup</li>
                                                            <li>Hotel Check-in</li>
                                                            <li>Hotel To Cellular Jail</li>
                                                            <li>Cellular Jail To Hotel (Night Drop)</li>
                                                            <li>Hotel To Cellular Jail (for Light & Sound Show)</li>
                                                            <li>Marina Park To Hotel (Rest Time)</li>
                                                            <li>Cellular Jail To Marina Park & Flag Point</li>
                                                        </ul>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="font-size:14px; font-weight:600; background: rgba(1, 98, 232, 0.1); width:180px; color:#031b4e; border-bottom: 1px solid rgba(1, 98, 232, 0.5) !important; border-right: 1px solid rgba(1, 98, 232, 0.5) !important;">Sightseeings</td>
                                                    <td style="font-size:13px; border-bottom: 1px solid rgba(1, 98, 232, 0.5) !important; background:rgb(255, 255, 255);">
                                                      <span style="display:block; color:#838383;"><i class="fa-solid fa-circle-arrow-right"></i> Cellular Jail (Historic Freedom Fighter Memorial)</span>
                                                      <span style="display:block; color:#838383;"><i class="fa-solid fa-circle-arrow-right"></i> Marina Park & Flag Point (Leisure Walk) </span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="font-size:14px; font-weight:600; background: rgba(1, 98, 232, 0.1); width:180px; color:#031b4e; border-bottom: 1px solid rgba(1, 98, 232, 0.5) !important; border-right: 1px solid rgba(1, 98, 232, 0.5) !important;">Activities</td>
                                                    <td style="font-size:13px; border-bottom: 1px solid rgba(1, 98, 232, 0.5) !important; background:rgb(255, 255, 255);">
                                                      <span style="display:block; color:#838383;"><i class="fa-solid fa-circle-arrow-right"></i> Light & Sound Show at Cellular Jail (Evening)</span>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        <div style=" background: rgba(254, 215, 170, 0.2);  border: 1px solid #fed7aa; padding: 20px; max-width: 419px;">
                                            <ul class="total-ex-list">
                                                <li><i class="fa-solid fa-circle-arrow-right"></i> Day Room Main Plan (1 * 1600) = ₹1600</li>
                                                <li> <i class="fa-solid fa-circle-arrow-right"></i> Day Room Addon Plan Meal Plan (4 * 0) = ₹0</li>
                                                <li><i class="fa-solid fa-circle-arrow-right"></i> Day Room Addon Plan Cwb (1 * 650) = ₹650</li>
                                                <li><i class="fa-solid fa-circle-arrow-right"></i> Per Day Cab (2 * 8000) = ₹16000</li>
                                                <li><i class="fa-solid fa-circle-arrow-right"></i> Day Sightseeing (7 * 78.57) = ₹550</li>
                                            </ul>
                                            <hr style="border: 1px solid #fed7aa; background-color:#fed7aa; margin-top: 16px; margin-bottom: 10px;">
                                            <h3 style="font-size: 19px;">Total Amount: ₹18,930</h3>
                                        </div>
                                    </td>
                                </tr>

                            </table>
                        </td>
                    </tr>


                </tbody>
            </table>
            <table style="border:1px solid rgba(1, 98, 232, 0.5) !important">
                <thead style="background:#d2e8ff;">
                    <tr>
                        <td><h4 style="font-size:22px; color:#031b4e;"> <i class="fa-solid fa-circle-check" style="font-size:20px; color:#1e58a3;"></i>  DAY 2 (Port Blair)<h4></td>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="2">
                            <table>
                                <tr>
                                    <td>
                                        <h5 style="font-size:20px; text-transform: uppercase; color: #031b4e;">TSG Emerald View Hotel & Spa<h5>
                                        <p style="font-size:13px; color:#000;">Lamba Line, opp. to Airport Gate, Junglighat, Sri Vijaya Puram, 
                                        Andaman and Nicobar Islands 744103</p>
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        <table style="border:1px solid rgba(1, 98, 232, 0.5);">
                                            <tbody>
                                                <tr>
                                                    <td style="font-size:14px; font-weight:600; background: rgba(1, 98, 232, 0.1); color:#031b4e; width:180px; border-bottom: 1px solid rgba(1, 98, 232, 0.5) !important; border-right: 1px solid rgba(1, 98, 232, 0.5) !important;">Rooms</td>
                                                    <td style="font-size:13px; border-bottom: 1px solid rgba(1, 98, 232, 0.5) !important; color:#838383;">Luxury - Non-AC <strong><i class="fa-solid fa-circle-arrow-right"></i> 10</strong></td>
                                                </tr>

                                                <tr>
                                                    <td style="font-size:14px; font-weight:600; background: rgba(1, 98, 232, 0.1); color:#031b4e; width:180px; border-bottom: 1px solid rgba(1, 98, 232, 0.5) !important; border-right: 1px solid rgba(1, 98, 232, 0.5) !important;">Plan</td>
                                                    <td style="font-size:13px; border-bottom: 1px solid rgba(1, 98, 232, 0.5) !important; color:#838383;">CP</td>
                                                </tr>
                                                <tr>
                                                    <td style="font-size:14px; font-weight:600; background: rgba(1, 98, 232, 0.1); width:180px; color:#031b4e; border-bottom: 1px solid rgba(1, 98, 232, 0.5) !important; border-right: 1px solid rgba(1, 98, 232, 0.5) !important;">CWB</td>
                                                    <td style="font-size:13px; border-bottom: 1px solid rgba(1, 98, 232, 0.5) !important; color:#838383;">
                                                        <ul>
                                                            <li> <span><i class="fa-solid fa-child"></i>  6-7 Years <i class="fa-solid fa-circle-arrow-right"></i> 2</span></li>
                                                            <li> <span><i class="fa-solid fa-child"></i>  3-6 Years <i class="fa-solid fa-circle-arrow-right"></i> 1</span></li>
                                                        </ul>
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
                        <td colspan="2">
                            <table>
                                <tr>
                                    <td>
                                        <h5 style="font-size:20px; text-transform: uppercase; color: #031b4e;">Cabs<h5>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <table style="border:1px solid rgba(1, 98, 232, 0.5);">
                                            <tbody>
                                                <tr>
                                                    <td style="font-size:14px; font-weight:600; background: rgba(1, 98, 232, 0.1); width:180px; color:#031b4e; border-bottom: 1px solid rgba(1, 98, 232, 0.5) !important; border-right: 1px solid rgba(1, 98, 232, 0.5) !important;">Tempo (23S)</td>
                                                    <td style="font-size:13px; border-bottom: 1px solid rgba(1, 98, 232, 0.5) !important; color:#838383;"><i class="fa-solid fa-car-side"></i> 2</td>
                                                </tr>

                                                <!--<tr>-->
                                                <!--    <td style="font-size:14px; font-weight:600; background: rgba(1, 98, 232, 0.1); width:180px; color:#031b4e; border-bottom: 1px solid rgba(1, 98, 232, 0.5) !important; border-right: 1px solid rgba(1, 98, 232, 0.5) !important;">Sedan (4S)</td>-->
                                                <!--    <td style="font-size:13px; border-bottom: 1px solid rgba(1, 98, 232, 0.5) !important; color:#838383;"> <i class="fa-solid fa-car-side"></i> 1</td>-->
                                                <!--</tr>-->
                                                <tr>
                                                    <td style="font-size:14px; font-weight:600; background: rgba(1, 98, 232, 0.1); width:180px; color:#031b4e; border-bottom: 1px solid rgba(1, 98, 232, 0.5) !important; border-right: 1px solid rgba(1, 98, 232, 0.5) !important;">Kia Carens (4S)</td>
                                                    <td style="font-size:13px; border-bottom: 1px solid rgba(1, 98, 232, 0.5) !important; color:#838383;;">
                                                      <i class="fa-solid fa-car-side"></i> 1
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
                        <td colspan="2">
                            <table>
                                <tr>
                                    <td>
                                        <h5 style="font-size:20px; text-transform: uppercase; color: #031b4e;">Routes<h5>
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        <table style="border:1px solid rgba(1, 98, 232, 0.5);">
                                            <tbody>
                                                <tr>
                                                    <td style="border: 1px solid rgba(34, 192, 60, 0.8) !important; background-color:rgba(34, 192, 60, 0.1); color:#22c03c; width:44px; text-align:center;" rowspan="3">1</td>
                                                    <td style="font-size:14px; font-weight:600; background: rgba(1, 98, 232, 0.1); width:180px; color:#031b4e; border-bottom: 1px solid rgba(1, 98, 232, 0.5) !important; border-right: 1px solid rgba(1, 98, 232, 0.5) !important;">
                                                        <h4 style=" font-size: 16px; margin-bottom: 12px; color: #031b4e; text-transform: uppercase;">Port Blair City Tour</h4>
                                                    </td>
                                                    <td style="font-size:13px; border-bottom: 1px solid rgba(1, 98, 232, 0.5) !important; background:rgb(255, 255, 255);">
                                                        <ul class="route-style">
                                                            <li>Airport Pickup</li>
                                                            <li>Hotel Check-in</li>
                                                            <li>Hotel To Cellular Jail</li>
                                                            <li>Cellular Jail To Hotel (Night Drop)</li>
                                                            <li>Hotel To Cellular Jail (for Light & Sound Show)</li>
                                                            <li>Marina Park To Hotel (Rest Time)</li>
                                                            <li>Cellular Jail To Marina Park & Flag Point</li>
                                                        </ul>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="font-size:14px; font-weight:600; background: rgba(1, 98, 232, 0.1); width:180px; color:#031b4e; border-bottom: 1px solid rgba(1, 98, 232, 0.5) !important; border-right: 1px solid rgba(1, 98, 232, 0.5) !important;">Sihghttseeings</td>
                                                    <td style="font-size:13px; border-bottom: 1px solid rgba(1, 98, 232, 0.5) !important; background:rgb(255, 255, 255);">
                                                      <span style="display:block; color:#838383;"><i class="fa-solid fa-circle-arrow-right"></i> Cellular Jail (Historic Freedom Fighter Memorial)</span>
                                                      <span style="display:block; color:#838383;"><i class="fa-solid fa-circle-arrow-right"></i> Marina Park & Flag Point (Leisure Walk)</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="font-size:14px; font-weight:600; background: rgba(1, 98, 232, 0.1); width:180px; color:#031b4e; border-bottom: 1px solid rgba(1, 98, 232, 0.5) !important; border-right: 1px solid rgba(1, 98, 232, 0.5) !important;">Activities</td>
                                                    <td style="font-size:13px; border-bottom: 1px solid rgba(1, 98, 232, 0.5) !important; background:rgb(255, 255, 255);">
                                                      <span style="display:block; color:#838383;"><i class="fa-solid fa-circle-arrow-right"></i> Light & Sound Show at Cellular Jail (Evening)</span>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        <table style="border:1px solid rgba(1, 98, 232, 0.5);">
                                            <tbody>
                                                <tr>
                                                    <td style="border: 1px solid rgba(34, 192, 60, 0.8) !important; background-color:rgba(34, 192, 60, 0.1); color:#22c03c; width:44px; text-align:center;" rowspan="3">2</td>
                                                    <td style="font-size:14px; font-weight:600; background: rgba(1, 98, 232, 0.1); width:180px; color:#031b4e; border-bottom: 1px solid rgba(1, 98, 232, 0.5) !important; border-right: 1px solid rgba(1, 98, 232, 0.5) !important;"><h4 style=" font-size: 16px; margin-bottom: 12px; color: #031b4e; text-transform: uppercase;"> Ross Island & North Bay Excursion</h4></td>
                                                    <td style="font-size:13px; border-bottom: 1px solid rgba(1, 98, 232, 0.5) !important; background:rgb(255, 255, 255);">
                                                        <ul class="route-style">
                                                            <li>Airport Pickup</li>
                                                            <li>Hotel Check-in</li>
                                                            <li>Hotel To Cellular Jail</li>
                                                            <li>Cellular Jail To Hotel (Night Drop)</li>
                                                            <li>Hotel To Cellular Jail (for Light & Sound Show)</li>
                                                            <li>Marina Park To Hotel (Rest Time)</li>
                                                            <li>Cellular Jail To Marina Park & Flag Point</li>
                                                        </ul>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="font-size:14px; font-weight:600; background: rgba(1, 98, 232, 0.1); width:180px; color:#031b4e; border-bottom: 1px solid rgba(1, 98, 232, 0.5) !important; border-right: 1px solid rgba(1, 98, 232, 0.5) !important;">Sightseeings</td>
                                                    <td style="font-size:13px; border-bottom: 1px solid rgba(1, 98, 232, 0.5) !important; background:rgb(255, 255, 255);">
                                                      <span style="display:block; color:#838383;"><i class="fa-solid fa-circle-arrow-right"></i> Cellular Jail (Historic Freedom Fighter Memorial)</span>
                                                      <span style="display:block; color:#838383;"><i class="fa-solid fa-circle-arrow-right"></i> Marina Park & Flag Point (Leisure Walk) </span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="font-size:14px; font-weight:600; background: rgba(1, 98, 232, 0.1); width:180px; color:#031b4e; border-bottom: 1px solid rgba(1, 98, 232, 0.5) !important; border-right: 1px solid rgba(1, 98, 232, 0.5) !important;">Activities</td>
                                                    <td style="font-size:13px; border-bottom: 1px solid rgba(1, 98, 232, 0.5) !important; background:rgb(255, 255, 255);">
                                                      <span style="display:block; color:#838383;"><i class="fa-solid fa-circle-arrow-right"></i> Light & Sound Show at Cellular Jail (Evening)</span>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        <div style=" background: rgba(254, 215, 170, 0.2);  border: 1px solid #fed7aa; padding: 20px; max-width: 419px;">
                                            <ul class="total-ex-list">
                                                <li><i class="fa-solid fa-circle-arrow-right"></i> Day Room Main Plan (1 * 1600) = ₹1600</li>
                                                <li> <i class="fa-solid fa-circle-arrow-right"></i> Day Room Addon Plan Meal Plan (4 * 0) = ₹0</li>
                                                <li><i class="fa-solid fa-circle-arrow-right"></i> Day Room Addon Plan Cwb (1 * 650) = ₹650</li>
                                                <li><i class="fa-solid fa-circle-arrow-right"></i> Per Day Cab (2 * 8000) = ₹16000</li>
                                                <li><i class="fa-solid fa-circle-arrow-right"></i> Day Sightseeing (7 * 78.57) = ₹550</li>
                                            </ul>
                                            <hr style="border: 1px solid #fed7aa; background-color:#fed7aa; margin-top: 16px; margin-bottom: 10px;">
                                            <h3 style="font-size: 19px;">Total Amount: ₹18,930</h3>
                                        </div>
                                    </td>
                                </tr>

                            </table>
                        </td>
                    </tr>


                </tbody>
            </table>
            <table style="border:1px solid rgba(1, 98, 232, 0.5) !important">
                <thead style="background:#d2e8ff;">
                    <tr>
                        <td><h4 style="font-size:22px; color:#031b4e;"> <i class="fa-solid fa-circle-check" style="font-size:20px; color:#1e58a3;"></i>  DAY 3 (Havelock)<h4></td>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="2">
                            <table>
                                <tr>
                                    <td>
                                        <h5 style="font-size:20px; text-transform: uppercase; color: #031b4e;">TSG Emerald View Hotel & Spa<h5>
                                        <p style="font-size:13px; color:#000;">Lamba Line, opp. to Airport Gate, Junglighat, Sri Vijaya Puram, 
                                        Andaman and Nicobar Islands 744103</p>
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        <table style="border:1px solid rgba(1, 98, 232, 0.5);">
                                            <tbody>
                                                <tr>
                                                    <td style="font-size:14px; font-weight:600; background: rgba(1, 98, 232, 0.1); color:#031b4e; width:180px; border-bottom: 1px solid rgba(1, 98, 232, 0.5) !important; border-right: 1px solid rgba(1, 98, 232, 0.5) !important;">Rooms</td>
                                                    <td style="font-size:13px; border-bottom: 1px solid rgba(1, 98, 232, 0.5) !important; color:#838383;">Luxury - Non-AC <strong><i class="fa-solid fa-circle-arrow-right"></i> 10</strong></td>
                                                </tr>

                                                <tr>
                                                    <td style="font-size:14px; font-weight:600; background: rgba(1, 98, 232, 0.1); color:#031b4e; width:180px; border-bottom: 1px solid rgba(1, 98, 232, 0.5) !important; border-right: 1px solid rgba(1, 98, 232, 0.5) !important;">Plan</td>
                                                    <td style="font-size:13px; border-bottom: 1px solid rgba(1, 98, 232, 0.5) !important; color:#838383;">CP</td>
                                                </tr>
                                                <tr>
                                                    <td style="font-size:14px; font-weight:600; background: rgba(1, 98, 232, 0.1); width:180px; color:#031b4e; border-bottom: 1px solid rgba(1, 98, 232, 0.5) !important; border-right: 1px solid rgba(1, 98, 232, 0.5) !important;">CWB</td>
                                                    <td style="font-size:13px; border-bottom: 1px solid rgba(1, 98, 232, 0.5) !important; color:#838383;">
                                                        <ul>
                                                            <li> <span><i class="fa-solid fa-child"></i>  6-7 Years <i class="fa-solid fa-circle-arrow-right"></i> 2</span></li>
                                                            <li> <span><i class="fa-solid fa-child"></i>  3-6 Years <i class="fa-solid fa-circle-arrow-right"></i> 1</span></li>
                                                        </ul>
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
                        <td colspan="2">
                            <table>
                                <tr>
                                    <td>
                                        <h5 style="font-size:20px; text-transform: uppercase; color: #031b4e;">Cabs<h5>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <table style="border:1px solid rgba(1, 98, 232, 0.5);">
                                            <tbody>
                                                <tr>
                                                    <td style="font-size:14px; font-weight:600; background: rgba(1, 98, 232, 0.1); width:180px; color:#031b4e; border-bottom: 1px solid rgba(1, 98, 232, 0.5) !important; border-right: 1px solid rgba(1, 98, 232, 0.5) !important;">Tempo (23S)</td>
                                                    <td style="font-size:13px; border-bottom: 1px solid rgba(1, 98, 232, 0.5) !important; color:#838383;"><i class="fa-solid fa-car-side"></i> 2</td>
                                                </tr>

                                                <!--<tr>-->
                                                <!--    <td style="font-size:14px; font-weight:600; background: rgba(1, 98, 232, 0.1); width:180px; color:#031b4e; border-bottom: 1px solid rgba(1, 98, 232, 0.5) !important; border-right: 1px solid rgba(1, 98, 232, 0.5) !important;">Sedan (4S)</td>-->
                                                <!--    <td style="font-size:13px; border-bottom: 1px solid rgba(1, 98, 232, 0.5) !important; color:#838383;"> <i class="fa-solid fa-car-side"></i> 1</td>-->
                                                <!--</tr>-->
                                                <tr>
                                                    <td style="font-size:14px; font-weight:600; background: rgba(1, 98, 232, 0.1); width:180px; color:#031b4e; border-bottom: 1px solid rgba(1, 98, 232, 0.5) !important; border-right: 1px solid rgba(1, 98, 232, 0.5) !important;">Kia Carens (4S)</td>
                                                    <td style="font-size:13px; border-bottom: 1px solid rgba(1, 98, 232, 0.5) !important; color:#838383;;">
                                                      <i class="fa-solid fa-car-side"></i> 1
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
                        <td colspan="2">
                            <table>
                                <tr>
                                    <td>
                                        <h5 style="font-size:20px; text-transform: uppercase; color: #031b4e;">Routes<h5>
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        <table style="border:1px solid rgba(1, 98, 232, 0.5);">
                                            <tbody>
                                                <tr>
                                                    <td style="border: 1px solid rgba(34, 192, 60, 0.8) !important; background-color:rgba(34, 192, 60, 0.1); color:#22c03c; width:44px; text-align:center;" rowspan="3">1</td>
                                                    <td style="font-size:14px; font-weight:600; background: rgba(1, 98, 232, 0.1); width:180px; color:#031b4e; border-bottom: 1px solid rgba(1, 98, 232, 0.5) !important; border-right: 1px solid rgba(1, 98, 232, 0.5) !important;">
                                                        <h4 style=" font-size: 16px; margin-bottom: 12px; color: #031b4e; text-transform: uppercase;">Port Blair City Tour</h4>
                                                    </td>
                                                    <td style="font-size:13px; border-bottom: 1px solid rgba(1, 98, 232, 0.5) !important; background:rgb(255, 255, 255);">
                                                        <ul class="route-style">
                                                            <li>Airport Pickup</li>
                                                            <li>Hotel Check-in</li>
                                                            <li>Hotel To Cellular Jail</li>
                                                            <li>Cellular Jail To Hotel (Night Drop)</li>
                                                            <li>Hotel To Cellular Jail (for Light & Sound Show)</li>
                                                            <li>Marina Park To Hotel (Rest Time)</li>
                                                            <li>Cellular Jail To Marina Park & Flag Point</li>
                                                        </ul>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="font-size:14px; font-weight:600; background: rgba(1, 98, 232, 0.1); width:180px; color:#031b4e; border-bottom: 1px solid rgba(1, 98, 232, 0.5) !important; border-right: 1px solid rgba(1, 98, 232, 0.5) !important;">Sihghttseeings</td>
                                                    <td style="font-size:13px; border-bottom: 1px solid rgba(1, 98, 232, 0.5) !important; background:rgb(255, 255, 255);">
                                                      <span style="display:block; color:#838383;"><i class="fa-solid fa-circle-arrow-right"></i> Cellular Jail (Historic Freedom Fighter Memorial)</span>
                                                      <span style="display:block; color:#838383;"><i class="fa-solid fa-circle-arrow-right"></i> Marina Park & Flag Point (Leisure Walk)</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="font-size:14px; font-weight:600; background: rgba(1, 98, 232, 0.1); width:180px; color:#031b4e; border-bottom: 1px solid rgba(1, 98, 232, 0.5) !important; border-right: 1px solid rgba(1, 98, 232, 0.5) !important;">Activities</td>
                                                    <td style="font-size:13px; border-bottom: 1px solid rgba(1, 98, 232, 0.5) !important; background:rgb(255, 255, 255);">
                                                      <span style="display:block; color:#838383;"><i class="fa-solid fa-circle-arrow-right"></i> Light & Sound Show at Cellular Jail (Evening)</span>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        <table style="border:1px solid rgba(1, 98, 232, 0.5);">
                                            <tbody>
                                                <tr>
                                                    <td style="border: 1px solid rgba(34, 192, 60, 0.8) !important; background-color:rgba(34, 192, 60, 0.1); color:#22c03c; width:44px; text-align:center;" rowspan="3">2</td>
                                                    <td style="font-size:14px; font-weight:600; background: rgba(1, 98, 232, 0.1); width:180px; color:#031b4e; border-bottom: 1px solid rgba(1, 98, 232, 0.5) !important; border-right: 1px solid rgba(1, 98, 232, 0.5) !important;"><h4 style=" font-size: 16px; margin-bottom: 12px; color: #031b4e; text-transform: uppercase;"> Ross Island & North Bay Excursion</h4></td>
                                                    <td style="font-size:13px; border-bottom: 1px solid rgba(1, 98, 232, 0.5) !important; background:rgb(255, 255, 255);">
                                                        <ul class="route-style">
                                                            <li>Airport Pickup</li>
                                                            <li>Hotel Check-in</li>
                                                            <li>Hotel To Cellular Jail</li>
                                                            <li>Cellular Jail To Hotel (Night Drop)</li>
                                                            <li>Hotel To Cellular Jail (for Light & Sound Show)</li>
                                                            <li>Marina Park To Hotel (Rest Time)</li>
                                                            <li>Cellular Jail To Marina Park & Flag Point</li>
                                                        </ul>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="font-size:14px; font-weight:600; background: rgba(1, 98, 232, 0.1); width:180px; color:#031b4e; border-bottom: 1px solid rgba(1, 98, 232, 0.5) !important; border-right: 1px solid rgba(1, 98, 232, 0.5) !important;">Sightseeings</td>
                                                    <td style="font-size:13px; border-bottom: 1px solid rgba(1, 98, 232, 0.5) !important; background:rgb(255, 255, 255);">
                                                      <span style="display:block; color:#838383;"><i class="fa-solid fa-circle-arrow-right"></i> Cellular Jail (Historic Freedom Fighter Memorial)</span>
                                                      <span style="display:block; color:#838383;"><i class="fa-solid fa-circle-arrow-right"></i> Marina Park & Flag Point (Leisure Walk) </span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="font-size:14px; font-weight:600; background: rgba(1, 98, 232, 0.1); width:180px; color:#031b4e; border-bottom: 1px solid rgba(1, 98, 232, 0.5) !important; border-right: 1px solid rgba(1, 98, 232, 0.5) !important;">Activities</td>
                                                    <td style="font-size:13px; border-bottom: 1px solid rgba(1, 98, 232, 0.5) !important; background:rgb(255, 255, 255);">
                                                      <span style="display:block; color:#838383;"><i class="fa-solid fa-circle-arrow-right"></i> Light & Sound Show at Cellular Jail (Evening)</span>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        <div style=" background: rgba(254, 215, 170, 0.2);  border: 1px solid #fed7aa; padding: 20px; max-width: 419px;">
                                            <ul class="total-ex-list">
                                                <li><i class="fa-solid fa-circle-arrow-right"></i> Day Room Main Plan (1 * 1600) = ₹1600</li>
                                                <li> <i class="fa-solid fa-circle-arrow-right"></i> Day Room Addon Plan Meal Plan (4 * 0) = ₹0</li>
                                                <li><i class="fa-solid fa-circle-arrow-right"></i> Day Room Addon Plan Cwb (1 * 650) = ₹650</li>
                                                <li><i class="fa-solid fa-circle-arrow-right"></i> Per Day Cab (2 * 8000) = ₹16000</li>
                                                <li><i class="fa-solid fa-circle-arrow-right"></i> Day Sightseeing (7 * 78.57) = ₹550</li>
                                            </ul>
                                            <hr style="border: 1px solid #fed7aa; background-color:#fed7aa; margin-top: 16px; margin-bottom: 10px;">
                                            <h3 style="font-size: 19px;">Total Amount: ₹18,930</h3>
                                        </div>
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