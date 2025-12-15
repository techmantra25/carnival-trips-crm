<!DOCTYPE html>
<html>
  <body style="margin:0; padding:0; font-family:Arial, sans-serif; background-color:#f4f6f8;">

    <!-- Main wrapper -->
    <table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="background-color:#f4f6f8; padding:20px 0;">
      <tr>
        <td align="center">
          <table role="presentation" cellpadding="0" cellspacing="0" width="600" style="width:600px; max-width:600px; background-color:#ffffff; border-radius:6px; overflow:hidden;">

            <!-- Header -->
            <tr>
              <td style="background-color:#2563eb; color:#ffffff; text-align:center; padding:20px;">
                <h1 style="margin:0; font-size:22px; font-weight:bold; letter-spacing:0.5px;">Your Company Name</h1>
                <p style="margin:4px 0 0 0; font-size:14px; opacity:0.9;">Your Company Tagline or Message</p>
              </td>
            </tr>

            <!-- Dynamic Body -->
            <tr>
              <td style="padding:24px; color:#374151; font-size:15px; line-height:24px;">
                {!! $html !!}
              </td>
            </tr>

            <!-- Footer -->
            <tr>
              <td style="background-color:#f9fafb; color:#6b7280; text-align:center; padding:16px; font-size:13px; line-height:20px; border-top:1px solid #e5e7eb;">
                <p style="margin:0 0 6px 0;">&copy; {{ date('Y') }} Your Company Name. All rights reserved.</p>
                <p style="margin:0;">123 Business Street, Your City, Country</p>
              </td>
            </tr>

          </table>
        </td>
      </tr>
    </table>

  </body>
</html>