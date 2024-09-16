{{ __('emails.new_recommendation_title', ['name' => $firstName]) }}

@foreach($requests as $request)
@include('mail_template.components.request_text', ['request' => $request])
@endforeach
