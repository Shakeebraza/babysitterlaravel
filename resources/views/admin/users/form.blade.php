{!! Form::hidden('redirects_to', URL::previous()) !!}
<div class="row">
    <div class="col-md-6">
        <div class="form-group{{ $errors->has('surname') ? ' has-error' : '' }}">
            <label class="control-label" for="surname">Surname :<span class="text-red">*</span></label>
            {!! Form::text('surname', null, ['class' => 'form-control', 'placeholder' => 'Enter Surname', 'id' => 'surname']) !!}
            @if ($errors->has('surname'))
                <span class="text-danger">
                    <strong>{{ $errors->first('surname') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group{{ $errors->has('first_name') ? ' has-error' : '' }}">
            <label class="control-label" for="first_name">First Name :<span class="text-red">*</span></label>
            {!! Form::text('first_name', null, ['class' => 'form-control', 'placeholder' => 'Enter First Name', 'id' => 'first_name']) !!}
            @if ($errors->has('first_name'))
                <span class="text-danger">
                    <strong>{{ $errors->first('first_name') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
            <label class="control-label" for="email">Email :<span class="text-red">*</span></label>
            {!! Form::text('email', null, ['class' => 'form-control', 'placeholder' => 'Enter Email', 'id' => 'email']) !!}
            @if ($errors->has('email'))
                <span class="text-danger">
                    <strong>{{ $errors->first('email') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group{{ $errors->has('address') ? ' has-error' : '' }}">
            <label class="control-label" for="address">Address :<span class="text-red">*</span></label>
            {!! Form::text('address', null, ['class' => 'form-control', 'placeholder' => 'Search Address', 'id' => 'ship-address']) !!}
            @if ($errors->has('address'))
                <span class="text-danger">
                    <strong>{{ $errors->first('address') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group{{ $errors->has('street') ? ' has-error' : '' }}">
            <label class="control-label" for="street">Street :<span class="text-red">*</span></label>
            {!! Form::text('street', null, ['class' => 'form-control', 'placeholder' => 'Enter street', 'id' => 'street']) !!}
            @if ($errors->has('street'))
                <span class="text-danger">
                    <strong>{{ $errors->first('street') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group{{ $errors->has('zip') ? ' has-error' : '' }}">
            <label class="control-label" for="zip">Zip :<span class="text-red">*</span></label>
            {!! Form::text('zip', null, ['class' => 'form-control', 'placeholder' => 'Enter Zip', 'id' => 'zip']) !!}
            @if ($errors->has('zip'))
                <span class="text-danger">
                    <strong>{{ $errors->first('zip') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group{{ $errors->has('city') ? ' has-error' : '' }}">
            <label class="control-label" for="city">City :<span class="text-red">*</span></label>
            {!! Form::text('city', null, ['class' => 'form-control', 'placeholder' => 'Enter City', 'id' => 'city']) !!}
            @if ($errors->has('city'))
                <span class="text-danger">
                    <strong>{{ $errors->first('city') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group{{ $errors->has('country_code') ? ' has-error' : '' }}">
            <label class="control-label" for="country_code">Country Code :<span class="text-red">*</span></label>
            {!! Form::text('country_code', null, ['class' => 'form-control', 'placeholder' => 'Enter Country Code', 'id' => 'country_code']) !!}
            @if ($errors->has('country_code'))
                <span class="text-danger">
                    <strong>{{ $errors->first('country_code') }}</strong>
                </span>
            @endif
        </div>
    </div>

    {!! Form::hidden('latitude', null, ['class' => 'form-control', 'id' => 'latitude']) !!}
    {!! Form::hidden('longitude', null, ['class' => 'form-control', 'id' => 'longitude']) !!}
</div>
<div class="row">
    <!--<div class="col-md-4">
        <div class="form-group{{ $errors->has('gender') ? ' has-error' : '' }}">
            <label class="col-md-12 control-label" for="gender">Gender :<span class="text-red">*</span></label>
            <div class="col-md-12">
                @foreach (\App\Models\User::$gender as $key1 => $value1)
                    <label>
                        {!! Form::radio('gender', $key1, null, ['class' => 'flat-red']) !!} <span style="margin-right: 10px">{{ $value1 }}</span>
                    </label>
                @endforeach
                @if ($errors->has('gender'))
                    <span class="text-danger" id="genderError">
                        <strong>{{ $errors->first('gender') }}</strong>
                    </span>
                @endif
            </div>
        </div>
    </div>-->

    <div class="col-md-4">
        <div class="form-group{{ $errors->has('date_of_birth') ? ' has-error' : '' }}">
            <label class="control-label" for="date_of_birth">Date Of Birth :<span class="text-red">*</span></label>
            {!! Form::date('date_of_birth', null, ['class' => 'form-control', 'placeholder' => 'Enter Date Of Birth', 'id' => 'date_of_birth']) !!}
            @if ($errors->has('date_of_birth'))
                <span class="text-danger">
                    <strong>{{ $errors->first('date_of_birth') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
            <label class="control-label" for="phone">Phone :<span class="text-red">*</span></label>
            {!! Form::text('phone', null, ['class' => 'form-control', 'placeholder' => 'Enter Phone', 'id' => 'phone']) !!}
            @if ($errors->has('phone'))
                <span class="text-danger">
                    <strong>{{ $errors->first('phone') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
            <label class="control-label" for="password">Password :@if(!isset($user))<span class="text-red">*</span>@endif</label>
            {!! Form::password('password', ['class' => 'form-control', 'placeholder' => 'Enter Password', 'id' => 'password']) !!}
            @if ($errors->has('password'))
                <span class="text-danger">
                    <strong>{{ $errors->first('password') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
            <label class="control-label" for="password">Confirm Password :@if(!isset($user))<span class="text-red">*</span>@endif</label>
            {!! Form::password('password_confirmation', ['class' => 'form-control', 'placeholder' => 'Confirm password', 'id' => 'password-confirm']) !!}
            @if ($errors->has('password_confirmation'))
                <span class="text-danger">
                    <strong>{{ $errors->first('password_confirmation') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group{{ $errors->has('image') ? ' has-error' : '' }}">
            <label class="col-md-12 control-label" for="image">Image<span class="text-red">*</span></label>
            <div class="col-md-12">
                <div class="fileError">
                    {!! Form::file('image', ['class' => '', 'id'=> 'image','accept'=>'image/*', 'onChange'=>'AjaxUploadImage(this)']) !!}
                </div>
                <img id="DisplayImage" @if(!empty($user['image'])) src="{{ url($user['image'])}}" style="margin-top: 1%; padding-bottom:5px; display: block;" @else src="" style="padding-bottom:5px; display: none;" @endif width="150">
                @if ($errors->has('image'))
                    <span class="help-block">
                    <strong>{{ $errors->first('image') }}</strong>
                </span>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }}">
            <label class="col-md-12 control-label" for="status">Status :<span class="text-red">*</span></label>
            <div class="col-md-12">
                @foreach (\App\Models\User::$status as $key => $value)
                    @php $checked = !isset($user) && $key == 'active'?'checked':''; @endphp
                    <label>
                        {!! Form::radio('status', $key, null, ['class' => 'flat-red',$checked]) !!} <span style="margin-right: 10px">{{ $value }}</span>
                    </label>
                @endforeach
                @if ($errors->has('status'))
                    <span class="text-danger" id="statusError">
                        <strong>{{ $errors->first('status') }}</strong>
                    </span>
                @endif
            </div>
        </div>
    </div>
</div>

@section('jquery')
@include('admin.users.mapScript')
@endsection
