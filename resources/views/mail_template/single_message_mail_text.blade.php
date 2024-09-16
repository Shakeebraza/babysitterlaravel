{{ __('emails.singe_message_title', ['name' => $firstName]) }}

{{$messageBody}}

@if($request)
{{__('emails.belongs_request')}}
@include('mail_template.components.request_text', ['request' => $request])
@endif
