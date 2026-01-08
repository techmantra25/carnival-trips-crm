<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $subject ?? 'Notification' }}</title>
</head>
<body>
<table style="background-color:#dde2ffd6; width:600px; margin:auto; font-family: Arial, sans-serif;">
    <tr>
        <td style="text-align:center; padding:20px 0;">
            <img src="{{ asset('front_assets/images/logo.png') }}" alt="logo" style="width:165px;" >
        </td>
    </tr>

    <tr>
        <td>
            <table style="width:510px; margin:auto; margin-bottom:45px; background-color:#fff; padding-bottom:14px; border-radius:23px;">

                {{-- ================= EMPLOYEE WELCOME TEMPLATE ================= --}}
                @if($template_type === 'employee_welcome')

                    <tr>
                        <td style="background-color:#0c1b59; color:#fff; padding:0 30px; border-top-left-radius:20px; border-top-right-radius:20px;">
                            <h2 style="color:#fff; font-size:26px; font-weight:600;">
                                Welcome to {{ $company_name??"Carnival Trips" }} 🎉
                            </h2>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:20px;">
                            <p>Hi {{ $employee_name }},</p>

                            <p>
                                Welcome to <strong>{{ $company_name }}</strong>!  
                                You can now log in to the employee portal using the details below:
                            </p>

                            <p>
                                🔗 <strong>Portal:</strong>
                                <a href="{{ $portal_link }}">{{ $portal_link }}</a><br>
                                🔐 <strong>Temporary Password:</strong>
                                {{ $temporary_password }}
                            </p>

                            <p style="font-size:13px; color:#666;">
                                Please change your password after first login.
                            </p>
                        </td>
                    </tr>

                @elseif($template_type === 'preset_itinerary_link') {{-- ================= ITINERARY TEMPLATE ================= --}}

                  <tr>
                      <td style="background-color:#0c1b59; color:#fff; padding:0 30px; border-top-left-radius:20px; border-top-right-radius:20px; text-align:center;">
                          <h2 style="color:#fff; font-size:28px; font-weight:700; margin:20px 0;">
                              ✈️ Your Dreem Itinerary is Ready!
                          </h2>
                          <p style="color:#f0f0f0; font-size:14px; margin-bottom:20px;">
                              Plan your trip and explore all the details below
                          </p>
                      </td>
                  </tr>

                  <tr>
                      <td style="padding:30px 30px 20px 30px; background-color:#f8f9fb; border-radius:0 0 20px 20px;">

                          <p style="font-size:16px; color:#333; font-weight:500; margin-bottom:10px;">
                              Hi {{ $recipient_name ?? 'Customer' }},
                          </p>

                          <p style="font-size:15px; color:#555; line-height:1.6; margin-bottom:20px;">
                              Your itinerary <strong style="color:#0c1b59;">{{ $itinerary }}</strong> is ready. Click the button below to view your complete trip details and make the most of your journey.
                          </p>

                          <p style="text-align:center; margin:30px 0;">
                              <a href="{{ $itinerary_link }}" target="_blank" style="background-color:#0c1b59; color:#fff; text-decoration:none; font-size:16px; font-weight:600; padding:14px 30px; border-radius:8px; display:inline-block;">
                                  View Itinerary
                              </a>
                          </p>

                          <p style="font-size:13px; color:#777; line-height:1.5;">
                              If you have any questions or need assistance, our team is always happy to help. Feel free to contact us anytime.
                          </p>

                      </td>
                  </tr>
                @elseif($template_type === 'customize_itinerary_link')
                  {{-- ================= CUSTOMIZED ITINERARY TEMPLATE ================= --}}
                  <tr>
                      <td style="background-color:#0c1b59; color:#fff; padding:0 30px; border-top-left-radius:20px; border-top-right-radius:20px; text-align:center;">
                          <h2 style="color:#fff; font-size:28px; font-weight:700; margin:20px 0;">
                              ✨ Your Customized Itinerary is Ready!
                          </h2>
                          <p style="color:#f0f0f0; font-size:14px; margin-bottom:20px;">
                              Specially crafted just for you
                          </p>
                      </td>
                  </tr>

                  <tr>
                      <td style="padding:30px; background-color:#f8f9fb; border-radius:0 0 20px 20px;">

                          <p style="font-size:16px; color:#333; font-weight:500; margin-bottom:10px;">
                              Hi {{ $recipient_name ?? 'Customer' }},
                          </p>

                          <p style="font-size:15px; color:#555; line-height:1.6; margin-bottom:15px;">
                              Your customized itinerary <strong style="color:#0c1b59;">{{ $itinerary }}</strong> has been prepared based on your preferences.
                          </p>

                          {{-- Total Amount --}}
                          <p style="font-size:15px; color:#333; font-weight:600; margin-bottom:20px;">
                              💰 Total Trip Cost:
                              <span style="color:#0c1b59;">
                                  ₹ {{ number_format($total_amount ?? 0) }}
                              </span>
                          </p>

                          <p style="text-align:center; margin:30px 0;">
                              <a href="{{ $itinerary_link }}" target="_blank"
                                style="background-color:#0c1b59; color:#fff; text-decoration:none; font-size:16px; font-weight:600; padding:14px 30px; border-radius:8px; display:inline-block;">
                                  View Customized Itinerary
                              </a>
                          </p>

                          <p style="font-size:13px; color:#777; line-height:1.5;">
                              Need any changes or assistance? Our team is always happy to help.
                          </p>

                      </td>
                  </tr>

                @elseif($template_type === 'booking_confirmed')
                    {{-- ================= BOOKING CONFIRMED TEMPLATE (PDF ATTACHED) ================= --}}

                    <tr>
                        <td style="background-color:#0c1b59; color:#fff; padding:0 30px; border-top-left-radius:20px; border-top-right-radius:20px; text-align:center;">
                            <h2 style="color:#fff; font-size:28px; font-weight:700; margin:20px 0;">
                                🎉 Booking Confirmed!
                            </h2>
                            <p style="color:#eaf6ee; font-size:14px; margin-bottom:20px;">
                                Your trip details are attached as a PDF
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:30px; background-color:#f8f9fb; border-radius:0 0 20px 20px;">

                            <p style="font-size:16px; color:#333; font-weight:500; margin-bottom:10px;">
                                Hi {{ $recipient_name ?? 'Customer' }},
                            </p>

                            <p style="font-size:15px; color:#555; line-height:1.6; margin-bottom:15px;">
                                We’re happy to inform you that your booking for
                                <strong style="color:#0c1b59;">{{ $itinerary }}</strong>
                                has been successfully confirmed.
                            </p>

                            {{-- Booking Summary --}}
                            <div style="background:#ffffff; border-radius:10px; padding:20px; margin-bottom:20px; border:1px solid #e5e7eb;">
                                <p style="font-size:14px; margin:6px 0;">
                                    <strong>📄 Booking ID:</strong>
                                    {{ $booking_id ?? 'N/A' }}
                                </p>

                                <p style="font-size:14px; margin:6px 0;">
                                    <strong>📅 Travel Dates:</strong>
                                    {{ $travel_dates ?? 'As per itinerary' }}
                                </p>

                                <p style="font-size:14px; margin:6px 0;">
                                    <strong>💰 Total Amount:</strong>
                                    ₹ {{ number_format($total_amount ?? 0) }}
                                </p>
                            </div>

                            {{-- PDF Notice --}}
                            <div style="background:#eaf6ee; border-left:4px solid #0c1b59; padding:15px; border-radius:6px; margin-bottom:20px;">
                                <p style="font-size:14px; color:#0c1b59; margin:0;">
                                    📎 <strong>Your booking itinerary PDF is attached with this email.</strong><br>
                                    Please download and keep it handy during your journey.
                                </p>
                            </div>

                            <p style="font-size:13px; color:#777; line-height:1.5;">
                                If you need any changes or assistance, feel free to reply to this email.
                                Our team is always happy to help. Wishing you a wonderful trip!
                            </p>

                        </td>
                    </tr>
                @elseif($template_type === 'hotel_availability_check_and_booking_confirmation')
                    @php
                        $booking   = $booking_details;
                        $room      = $booking['room_bookings'][0];
                        $isConfirm = $mail_type === 'confirm';
                        $childrenData = !empty($booking['children_data'])
                                                            ? json_decode($booking['children_data'], true)
                                                            : [];
                    @endphp

                    <tr>
                        <td style="padding:0;background:#ffffff; border-radius: 19px;">
                            <table width="100%" cellpadding="0" cellspacing="0" style="font-family:Arial,sans-serif;">

                                <!-- HEADER -->
                                <tr>
                                    <td style="background-color:#0c1b59; color:#fff; padding:15px 30px; border-top-left-radius:20px; border-top-right-radius:20px; text-align:center;">
                                        <h3 style="margin:0;">
                                            {{ $room['hotel_name'] }} – {{ $booking['division_name'] }}
                                        </h3>
                                        <p style="margin:4px 0 0;font-size:13px;">
                                            ({{ $isConfirm ? 'Booking Confirmation' : 'Availability Request' }})
                                        </p>
                                    </td>
                                </tr>

                                <!-- BODY -->
                                <tr>
                                    <td style="padding:20px;font-size:14px;color:#333;">

                                        <p>Dear Reservation Team,</p>

                                        <p style="background:#eef6ff;padding:8px;border-left:4px solid #0c88c2;">
                                            <strong>Greetings from Christmas Tree Hospitality!!</strong>
                                        </p>

                                        @if($isConfirm)
                                            <p>
                                               Congratulations! New booking has been received. kindly find the booking details below.
                                            </p>
                                        @else
                                            <p>
                                                We have a new query for accommodation arrangement.
                                                Kindly share the availability as soon as possible.
                                            </p>
                                        @endif

                                        <!-- BOOKING DETAILS TABLE -->
                                        <table width="100%" cellpadding="8" cellspacing="0" border="1"
                                            style="border-collapse:collapse;font-size:13px;margin-top:10px;">

                                            <tr style="background:#f3f3f3;font-weight:bold;text-align:center;">
                                                <td>Stay Details</td>
                                                <td>Guests</td>
                                                <td>Room</td>
                                                <td>Meal Plan</td>
                                            </tr>

                                            <tr valign="top">

                                                <!-- STAY -->
                                                <td>
                                                    <strong>{{ $booking['number_of_day'] }} Night(s)</strong><br>
                                                    Check In: {{ $room['check_in'] }}<br>
                                                    Check Out: {{ $room['check_out'] }}

                                                    @if(!empty($room['re_stays']))
                                                        <hr style="margin:6px 0;">
                                                        @foreach($room['re_stays'] as $reStay)
                                                            Re-Check In: {{ $reStay['re_check_in'] }}<br>
                                                            Re-Check Out: {{ $reStay['re_check_out'] }}<br>
                                                        @endforeach
                                                    @endif
                                                </td>

                                                <!-- GUESTS -->
                                                <td>
                                                    Adults: {{ $booking['adults'] }}<br>
                                                    Children: {{ $booking['children'] }}<br>
                                                    @if(!empty($booking['extra_mattress']))
                                                        Extra Mattress: {{ $booking['extra_mattress'] }}
                                                    @endif
                                                </td>

                                                <!-- ROOM -->
                                                <td>
                                                    {{ $room['room_name'] }}<br>
                                                    Rooms: {{ $room['no_of_room'] }}

                                                     @if(!empty($childrenData))
                                                        <div class="mt-2 text-xs text-gray-600">
                                                            @foreach($childrenData as $child)
                                                                <p>
                                                                   {{ $child['addon_type'] }} : ({{ $child['quantity'] }}) - ({{ $child['age'] }})
                                                                </p>
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                </td>

                                                <!-- MEAL -->
                                                <td>
                                                    {{ $booking['rate_plan'] }}
                                                </td>
                                            </tr>

                                        </table>

                                        @if($isConfirm)
                                            <p style="margin-top:12px;">
                                                <strong>Guest Name:</strong> {{ $booking['guest_name'] }}<br>
                                                <strong>Booking ID:</strong> {{ $booking['booking_id'] }}
                                            </p>
                                             <p style="margin-top:15px;">
                                                Kindly share your acknowledgment of the booking as soon as possible.
                                            </p>
                                        @else
                                            <p style="margin-top:15px;">
                                               Looking forward to hearing from you soon regarding the availability.
                                            </p>
                                        @endif

                                    </td>
                                </tr>

                            </table>
                        </td>
                    </tr>
                @endif

                {{-- ================= FOOTER / SIGNATURE ================= --}}
                <tr>
                    <td style="padding:20px; text-align:center;">
                        <p>
                            Best regards,<br>
                            <strong>{{ $sender_name }}</strong><br>
                            <span style="font-size:13px; color:#555;">
                                {{ $company_name }}<br>
                                @if(isset($sender_mobile))
                                 📞 {{ $sender_mobile }}
                                @endif
                            </span>
                        </p>
                    </td>
                </tr>

            </table>
        </td>
    </tr>
</table>
</body>
</html>
