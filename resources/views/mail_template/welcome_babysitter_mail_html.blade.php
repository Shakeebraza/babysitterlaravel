<!DOCTYPE html>
<html lang="{{ $language }}">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
</head>
<body style="margin: 0; padding: 0; background-color: #f5f5f5; font-family: Arial, sans-serif; font-size: 16px; color: #495057;">
<p style="display:none;font-size:1px;color:#ffffff;max-height:0px;max-width:0px;opacity:0;overflow:hidden;">
    {{$preheaderText}}
</p>
<div style="background-color: #f5f5f5; padding: 20px; text-align: center;">
    <table role="presentation" style="margin: 0 auto; background-color: #ffffff; padding: 0; text-align: center; border-collapse: collapse; width: 100%; max-width: 600px;">
        <tr>
            <td style="padding: 40px 0;">
                <img src="{{url('assets/dist/img/logo.png')}}" alt="logo" width="200" style="width: 200px; max-width: 100%; height: auto; display: block; margin: 0 auto;">
            </td>
        </tr>
        <tr>
            <td style="padding: 20px; text-align: left;">
                <h4 style="font-size: 20px; margin-bottom: 10px;">{{ __('emails.welcome_title', ['name' => $firstName]) }}</h4>
                <p style="margin-bottom: 10px;">{{ __('emails.welcome_babysitter_intro') }}</p>

                <table role="presentation" width="100%" style="border-collapse: collapse; margin-bottom: 10px;">
                    <tr>
                        <td style="padding-right: 20px; vertical-align: top;">
                            <h5 style="font-size: 16px; margin-bottom: 10px;">{{ __('emails.babysitter_step1_title') }}</h5>
                            <p style="margin-bottom: 10px;">{{ __('emails.babysitter_step1_description') }}</p>
                        </td>
                        <td width="120">
                            <img src="{{ __('emails.babysitter_step1_gif') }}" alt="{{ __('emails.babysitter_step1_alt') }}" width="120" style="max-width: 120px; display: block;">
                        </td>
                    </tr>
                </table>

                <table role="presentation" width="100%" style="border-collapse: collapse; margin-bottom: 10px;">
                    <tr>
                        <td width="120">
                            <img src="{{ __('emails.babysitter_step2_gif') }}" alt="{{ __('emails.babysitter_step2_alt') }}" width="120" style="max-width:120px; display: block;">
                        </td>
                        <td style="padding-left: 20px; vertical-align: top;">
                            <h5 style="font-size: 16px; margin-bottom: 10px;">{{ __('emails.babysitter_step2_title') }}</h5>
                            <p style="margin-bottom: 10px;">{{ __('emails.babysitter_step2_description') }}</p>
                        </td>
                    </tr>
                </table>

                <table role="presentation" width="100%" style="border-collapse: collapse; margin-bottom: 10px;">
                    <tr>
                        <td style="padding-right: 20px; vertical-align: top;">
                            <h5 style="font-size: 16px; margin-bottom: 10px;">{{ __('emails.babysitter_step3_title') }}</h5>
                            <p style="margin-bottom: 10px;">{{ __('emails.babysitter_step3_description') }}</p>
                        </td>
                        <td width="120">
                            <img src="{{ __('emails.babysitter_step3_gif') }}" alt="{{ __('emails.babysitter_step3_alt') }}" width="120" style="max-width:120px; display: block;">
                        </td>
                    </tr>
                </table>

                <p style="margin-bottom: 10px;">{{ __('emails.babysitter_closing') }}</p>

                <p>{{ __('emails.signature') }}</p>
                <p>{{ __('emails.signature_name') }}</p>
            </td>
        </tr>
    </table>
</div>
</body>
</html>
