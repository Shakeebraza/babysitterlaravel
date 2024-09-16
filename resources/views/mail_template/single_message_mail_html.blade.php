<!DOCTYPE html>
<html lang="{{ $language }}">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
</head>
<body
    style="margin: 0; padding: 0; background-color: #f5f5f5; font-family: Arial, sans-serif; font-size: 16px; color: #495057;">
<p style="display:none;font-size:1px;color:#ffffff;max-height:0px;max-width:0px;opacity:0;overflow:hidden;">
    {{$preheaderText}}
</p>
<div style="background-color: #f5f5f5; padding: 20px; text-align: center;">
    <table role="presentation"
           style="margin: 0 auto; background-color: #ffffff; padding: 0; text-align: center; border-collapse: collapse; width: 100%; max-width: 600px;">
        <tr>
            <td style="padding: 40px 0;">
                <img src="{{url('assets/dist/img/logo.png')}}" alt="logo" width="200"
                     style="width: 200px; max-width: 100%; height: auto; display: block; margin: 0 auto;">
            </td>
        </tr>
        <tr>
            <td style="padding: 20px; text-align: left;">
                <p style="margin-bottom: 10px;">{{ __('emails.singe_message_title', ['name' => $firstName]) }}</p>

                <div style="padding: 20px; background-color: #f9f9f9; border-left: 4px solid #5734ac; font-size: 18px; color: #333;">
                    {{$messageBody}}
                </div>

                @if($request)
                    <p style="margin-top: 20px;">{{__('emails.belongs_request')}}</p>
                    @include('mail_template.components.request_html', ['request' => $request])
                @endif
            </td>
        </tr>
    </table>
</div>
</body>
</html>
