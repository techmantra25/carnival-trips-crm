<div>
    <style>
        body {
            margin: 0;
            padding: 0;
            background: #e5e7eb;
            font-family: math;
        }

        @page {
            size: A4;
            margin: 0;
        }

        /* ================= SCREEN DESIGN ================= */

        #quotation-print {
            width: 100%;
            position: relative;
            padding-top: 70px;
        }

        /* PRINT BUTTON */
        #quotation-print .print-btn {
            position: absolute;
            top: 15px;
            right: 20px;
            z-index: 10;
            padding: 10px 18px;
            background: #2563eb;
            color: #fff;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 6px;
            box-shadow: 0 4px 10px rgba(0,0,0,.15);
        }

        #quotation-print .print-btn:hover {
            background: #1e40af;
        }

        /* A4 PAGE */
        .a4-page {
            width: 210mm;
            height: 297mm;
            margin: 0 auto;
            background: #fff;
            overflow: hidden;
            page-break-after: always;
        }

        .a4-bg {
            width: 100%;
            height: 100%;
            position: relative;
        }

        .a4-bg-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .a4-center {
            position: absolute;
            top: 35%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            color: #000;
        }
        .a4-center-title {
            position: absolute;
            top: 63%;
            left: 45%;
            transform: translate(-39%, -50%);
            text-align: center;
            color: #000;
        }
        .page-logo {
            position: absolute;
            top: 9%;
            left: 82%;
            transform: translate(-50%, -50%);
            text-align: center;
            color: #000;
        }

        .a4-logo {
            margin-bottom: 15px;
        }

        .a4-title {
            font-size: 60px;
            font-weight: bold;
            letter-spacing: .5px;
            font-family: math;
            color: #fff;

            /* Text shadow */
            text-shadow: 2px 2px 2px #000000;
        }

        .a4-content {
            position: absolute;
            top: 60%;
            left: 50%;
            color: #ffffff;
            transform: translateX(-50%);
            width: 80%;
            text-align: justify;
            font-size: 20px;
            text-shadow: 2px 1px 1px #000000;
            line-height: 1.6;
        }
        .editable-text {
            outline: none;
            min-height: 120px;
            padding: 10px;
            font-size: 20px;
            line-height: 1.6;
            border-radius: 6px;
            cursor: text;
        }

        /* Optional: subtle border on focus (screen only) */
        .editable-text:focus {
            border: 1px dashed #999;
        }

        /* ================= PRINT MODE ================= */

        @media print {

            /* Hide everything */
            body * {
                visibility: hidden !important;
            }

            /* Show only quotation */
            #quotation-print,
            #quotation-print * {
                visibility: visible !important;
            }

            /* Position quotation */
            #quotation-print {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                padding: 0 !important;
                margin: 0 !important;
            }

            /* Full A4 pages */
            .a4-page {
                width: 210mm;
                height: 297mm;
                margin: 0 !important;
                page-break-after: always;
            }

            /* Hide print button */
            .print-btn {
                display: none !important;
            }

            body {
                background: #fff !important;
            }
            .editable-text {
                border: none !important;
                padding: 0 !important;
            }
            .a4-title,.a4-content {
                /* FORCE browser to keep colors */
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
    @if(session('text_error'))
        <div class="alert alert-danger flex justify-between items-center p-4 mb-4 border rounded bg-red-100 border-red-400 text-red-800">
            <span>{{ session('text_error') }}</span>
            <a href="{{ route('admin.leads.final-quotation', $sent_lead_itinerary->itinerary_code) }}" 
            class="text-white bg-red-600 hover:bg-red-700 px-3 py-1 rounded ml-4">
                Back
            </a>
        </div>
    @else
        <div id="quotation-print">

            <!-- PRINT BUTTON -->
            <button class="print-btn" onclick="window.print()">🖨️ Print</button>

            <!-- PAGE 1 -->
            <div class="a4-page">
                <div class="a4-bg">
                    <img src="{{ asset($first_page_bg_image) }}" class="a4-bg-img">
                    <div class="a4-center">
                        <div style="background: #fff; border-radius: 50px; width: 400px; box-sizing: border-box; padding: 20px; margin: 0 auto;">
                            <img src="{{ asset('assets/final_quotation_images/logo1.png') }}" class="a4-logo" style="max-width: 100%;">
                        </div>
                    </div>
                    <div class="a4-center-title">
                        <h1 class="a4-title">{{$leadData->travel_in_days}} Days Amazing <br> {{$destinationName}} Tour</h1>
                    </div>
                    
                </div>
            </div>
            {{-- PAGE 2 --}}
            <div class="a4-page">
                <div class="a4-bg">
                    <img src="{{ $second_page_bg_image }}" class="a4-bg-img">
                    <div class="page-logo">
                        <div style="background: #fff;border-radius: 50px;width: 216px;box-sizing: border-box;padding: 20px;margin: 0 auto;">
                            <img src="{{ asset('assets/final_quotation_images/logo1.png') }}" class="a4-logo" style="max-width: 100%;">
                        </div>
                    </div>
                    <div class="a4-center" style="top: 22% !important;">
                        <h1 class="a4-title">Carrnival Trips</h1>
                    </div>
                    <div class="a4-content" style="top: 29% !important;padding:8px; margin: 0;background: #817d7d1a;">
                        <p style="font-size: 25px !important;">Dear {{$leadData->customer_name}},</p>
                        <!-- Editable content -->
                        <div class="editable-text" contenteditable="true">
                           We are pleased to assist you with your upcoming trip and thank you for choosing Carrnival Trips.
                            Based on our discussion, we have curated the following itinerary to ensure a comfortable,
                            enjoyable, and memorable travel experience.

                            This itinerary has been carefully designed keeping your preferences, travel dates,
                            and comfort in mind. Should you require any modifications, additions, or special arrangements,
                            please feel free to let us know and our team will be happy to customize it for you.

                            We look forward to making your journey smooth, enjoyable, and truly unforgettable.
                        </div>
                        <div>
                            <p>Travel Dates:-{{$itinerary['travel_dates']}}</p>
                            <p>
                                <strong>No. of Pax:</strong>
                                {{ $leadData->number_of_adults }} Adult{{ $leadData->number_of_adults > 1 ? 's' : '' }}
                                @if($leadData->number_of_children > 0)
                                    , {{ $leadData->number_of_children }} Child{{ $leadData->number_of_children > 1 ? 'ren' : '' }}
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            {{-- PAGE 3 --}}
            <div class="a4-page">
                <div class="a4-bg">
                    <img src="{{ $third_page_bg_image }}" class="a4-bg-img">
                    <div class="page-logo">
                        <div style="background: #fff;border-radius: 50px;width: 216px;box-sizing: border-box;padding: 20px;margin: 0 auto;">
                            <img src="{{ asset('assets/final_quotation_images/logo1.png') }}" class="a4-logo" style="max-width: 100%;">
                        </div>
                    </div>
                    <div class="a4-center" style="top: 22% !important;">
                        <h1 class="a4-title">Trip Highlights</h1>
                    </div>
                   
                    <div class="a4-content" style="top: 29% !important;">
                        <ul style="list-style:none; padding:8px; margin: 0;background: #817d7d1a;">
                            @foreach ($itinerary['trip_highligts'] as $item_highlights)
                                <li style="position: relative; font-family: Arial, Helvetica, sans-serif; font-size: 24px; color: rgba(255, 255, 255, 1); line-height: 34px; max-width: 100%; padding-left: 50px; margin: 0 0 16px;">
                                    <span style="position:absolute; left:0;">➤</span>
                                    {{$item_highlights}}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

        </div>
    @endif
    
</div>
