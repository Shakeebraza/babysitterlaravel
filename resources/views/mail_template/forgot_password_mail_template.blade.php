<!DOCTYPE html>
<html lang="{{$data['language']}}">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>{{trans('messages.password_reset_title')}}</title>
</head>
<body>
<div style="line-height:inherit;margin:0;background-color:#f5f5f5">
    <table cellpadding="0" cellspacing="0" role="presentation" width="100%" bgcolor="#f5f5f5" valign="top" style="line-height:inherit;table-layout:fixed;vertical-align:top;border-spacing:0;border-collapse:collapse;background-color:#f5f5f5;width:100%;text-align: center;">
        <tbody style="line-height:inherit">
        <tr valign="top">
            <td valign="top" style="line-height:inherit;border-collapse:collapse;word-break:break-word;vertical-align:top;text-align: center;padding: 60px 60px 0;">
                <img src="{{url('assets/dist/img/logo.png')}}" alt="logo" width="214" height="40" style="object-fit: contain;" />
            </td>
        </tr>
        <tr valign="top">
            <td valign="top" style="line-height:inherit;border-collapse:collapse;word-break:break-word;vertical-align:top;text-align: center;padding:30px 0 60px;">
                <table cellpadding="0" cellspacing="0" role="presentation" width="100%" bgcolor="#FFFFFF" valign="top" style="line-height:inherit;table-layout:fixed;vertical-align:top;min-width:320px;max-width: 612px;border-spacing:0;border-collapse:collapse;background-color:#ffffff;width:100%;margin: 0 auto;">
                    <tbody style="line-height:inherit">
                    <tr valign="top" style="line-height:inherit;border-collapse:collapse;vertical-align:top">
                        <td valign="top" style="line-height:inherit;border-collapse:collapse;word-break:break-word;vertical-align:top;font-family: 'Poppins',Arial,sans-serif;font-style: normal;font-weight: 400;font-size: 16px;line-height: 26px;color: #495057;padding: 50px 60px;text-align: left;">
                            <h4 style="margin:0 0 15px;">{{trans('messages.hello')}} </h4>
                            <p style="margin:0 0 15px;">{{trans('messages.pr_para1')}} <strong>{{$data['fp_code']}}</strong></p>
                            <p style="margin:0 0 15px;">{{trans('messages.best_regards')}}</p>
                            <p style="margin:0 0 15px;">{{trans('messages.babysitter_app_team')}}</p>
                            <p style="margin:0px;">
                                <a href="https://apps.apple.com/us/app/babysitting/id6463607853" target="_blank" style="text-decoration:none">
                                    <img src="{{url('assets/dist/img/ios-appStore-img1.png')}}" alt="" width="143" height="42" style="object-fit: contain;" />
                                </a>
                                <a href="https://play.google.com/store/apps/details?id=com.babysitter.application" target="_blank" style="text-decoration:none">
                                    <img src="{{url('assets/dist/img/googlePlayStore1.png')}}" alt="" width="143" height="42" style="object-fit: contain;" />
                                </a>
                            </p>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        </tbody>
    </table>
</div>
</body>
</html>
