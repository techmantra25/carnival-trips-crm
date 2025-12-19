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
            <img src="{{ asset('front_assets/images/logo.png') }}" alt="Logo" style="width:165px;">
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

                @endif
                {{-- ================= FOOTER / SIGNATURE ================= --}}
                <tr>
                    <td style="padding:20px; text-align:center;">
                        <p>
                            Best regards,<br>
                            <strong>{{ $sender_name }}</strong><br>
                            <span style="font-size:13px; color:#555;">
                                {{ $company_name }}<br>
                                @if($sender_mobile)
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
