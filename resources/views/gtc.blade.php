@extends('layouts.app')
@section('content')
    <div class="features-main py-5" id="privacyPolicy">
        <div class="container">
            <div class="title-main mb-4">
                <h2 class="display-5 text-center text-capitalize fw-bold">{{__(trans('messages.terms_conditions'))}}</h2>
            </div>
            <div class="about-content">
                <p><strong>{{__(trans('messages.last_updated'))}}</strong>04.10.2023</p>
                <p>{{__(trans('messages.read_terms'))}}</p>
            </div>
            <div class="about-content">
                <h2>{{__(trans('messages.acceptance_terms'))}}</h2>
                <p>{{__(trans('messages.by_accessing'))}}</p>
            </div>
            <div class="about-content">
                <h2>{{__(trans('messages.use_of_service'))}}</h2>
                <ul>
                    <li>
                        <h3>{{__(trans('messages.accounts'))}}</h3>
                        <p>{{__(trans('messages.to_user_certain'))}}</p>
                    </li>
                    <li>
                        <h3>{{__(trans('messages.content'))}}</h3>
                        <p>{{__(trans('messages.you_are_solely'))}}</p>
                    </li>
                    <li>
                        <h3>{{__(trans('messages.privacy'))}}</h3>
                        @php
                            $locale = app()->getLocale();
                            $privacyPolicyRouteName = "$locale.privacyPolicy";
                        @endphp
                        <p>{{__(trans('messages.governed_by'))}} <a href="{{route($privacyPolicyRouteName)}}">{{__(trans('messages.here'))}}</a></p>
                    </li>
                </ul>
            </div>
            <div class="about-content">
                <h2>{{__(trans('messages.termination'))}}</h2>
                <p>{{__(trans('messages.we_may_terminate'))}}</p>
            </div>
            <div class="about-content">
                <h2>{{__(trans('messages.intellectual_property'))}}</h2>
                <p>{{__(trans('messages.service_original_content'))}}</p>
            </div>
            <div class="about-content">
                <h2>{{__(trans('messages.limitation_liability'))}}</h2>
                <p>{{__(trans('messages.event_shall_babysitter'))}}</p>
            </div>
            <div class="about-content">
                <h2>{{__(trans('messages.changes_terms'))}}</h2>
                <p>{{__(trans('messages.reserve_rights'))}}</p>
            </div>
            <div class="about-content">
                <h2>{{__(trans('messages.contact_us'))}}</h2>
                <p>{{__(trans('messages.any_question_terms'))}} <a href="mailto:contact@babysitter-app.com">contact@babysitter-app.com</a></p>
            </div>
        </div>
    </div>
@endsection
