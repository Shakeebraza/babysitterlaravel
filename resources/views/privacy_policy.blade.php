@extends('layouts.app')
@section('content')
<div class="features-main py-5" id="privacyPolicy">
    <div class="container">
        <div class="title-main mb-4">
            <h2 class="display-5 text-center text-capitalize fw-bold">{{__(trans('messages.privacy_policy_title'))}}</h2>
        </div>
        <div class="about-content">
            <p><strong>{{__(trans('messages.effective_date_message'))}}:</strong>{{__(trans('messages.effective_date'))}}</p>
            <p>{{__(trans('messages.protecting_personal_information'))}}</p>
        </div>
        <div class="about-content">
            <h2>{{__(trans('messages.information_we_collect'))}}</h2>
            <p>{{__(trans('messages.protecting_personal_information'))}}</p>
            <ul>
                <li><strong>{{__(trans('messages.personal_data'))}}</strong> {{__(trans('messages.personal_information_such_as'))}}</li>
                <li><strong>{{__(trans('messages.usage_data'))}}</strong> {{__(trans('messages.information_about_how_you_use'))}}</li>
                <li><strong>{{__(trans('messages.device_information'))}}</strong> {{__(trans('messages.collect_information_about_device'))}}</li>
            </ul>
        </div>
        <div class="about-content">
            <h2>{{__(trans('messages.use_your_information'))}}</h2>
            <p>{{__(trans('messages.use_your_information_to'))}}</p>
            <ul>
                <li>{{__(trans('messages.app_and_its_features'))}}</li>
                <li>{{__(trans('messages.content_and_features'))}}</li>
                <li>{{__(trans('messages.new_features_of_the_app'))}}</li>
                <li>{{__(trans('messages.respond_to_your_requests_and_inquiries'))}}</li>
            </ul>
        </div>
        <div class="about-content">
            <h2>{{__(trans('messages.protect_your_information'))}}</h2>
            <p>{{__(trans('messages.reasonable_security_measures'))}}</p>
        </div>
        <div class="about-content">
            <h2>{{__(trans('messages.information_sharing'))}}</h2>
            <p>{{__(trans('messages.share_your_personal_information'))}}</p>
        </div>
        <div class="about-content">
            <h2>{{__(trans('messages.rights_and_control_information'))}}</h2>
            <p>{{__(trans('messages.access_correct_or_delete_information'))}} <a href="mailto:contact@babysitter-app.com">contact@babysitter-app.com</a>.</p>
        </div>
        <div class="about-content">
            <h2>{{__(trans('messages.changes_privacy_policy'))}}</h2>
            <p>{{__(trans('messages.update_privacy_policy_periodically'))}}</p>
        </div>
        <div class="about-content">
            <h2>{{__(trans('messages.privacy_policy_contact_us'))}}</h2>
            <p>{{__(trans('messages.questions_privacy_policy'))}}</p>
            <p><strong>{{__(trans('messages.privacy_policy_email'))}}</strong> <a href="mailto:contact@babysitter-app.com">contact@babysitter-app.com</a></p>
            <p><strong>{{__(trans('messages.privacy_policy_postal_address'))}}</strong> {{__(trans('messages.privacy_policy_address'))}}</p>
        </div>
    </div>
</div>
@endsection
