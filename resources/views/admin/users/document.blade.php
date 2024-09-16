@extends('admin.layouts.app')
@section('content')
    <div class="content-wrapper" style="min-height: 946px;">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>{{$menu}}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{route('users.index')}}">{{$menu}}</a></li>
                            <li class="breadcrumb-item active">User Document</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">

            <div class="row">
                <div class="col-md-12">
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">User Document</h3>
                        </div>
                        {!! Form::model($user,['url' => route('document.updateStatus',['id'=>$user->id]),'method'=>'post','id' => 'usersForm','class' => 'form-horizontal','files'=>true]) !!}
                        <div class="card-body">
                            {!! Form::hidden('redirection', $menu, []) !!}
                            @if(count($documents) > 0)
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="col-md-12 control-label" for="documentStatus">User Name: <span class="font-weight-normal">{{$user['first_name'].' '.$user['surname']}}</span></label>
                                    </div>
                                    <div class="col-md-6">
                                        <img id="DisplayImage" @if(!empty($user['image'])) src="{{ url($user['image'])}}" style="margin-top: 1%; padding-bottom:5px; display: block;" @else src="" style="padding-bottom:5px; display: none;" @endif width="150">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="col-md-12 control-label" for="documentStatus">Birth date: <span class="font-weight-normal">{{$user['date_of_birth']}}</span></label>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="col-md-12 control-label" for="documentStatus">Address: <span class="font-weight-normal">{{$user['address']}}</span></label>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="col-md-12 control-label" for="documentStatus">Document Name: <span class="font-weight-normal">{{$documents[0]['document_name']}}</span></label>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="col-md-12 control-label" for="documentStatus">Documents :</label>
                                        @foreach($documents as $list)
                                            @php
                                                $ext = explode('.',$list['document']);
                                            @endphp
                                            @if(in_array($ext[1],['jpg','jpeg','png','bmp']))
                                                <a href="{{url($list['document'])}}" target="_blank">
                                                    <img src="{{url($list['document'])}}" height="100">
                                                </a>
                                            @else
                                                <a href="{{url($list['document'])}}" target="_blank">
                                                    <span style="font-size: 60px;"><i class="fa fa-file-invoice"></i></span>
                                                </a>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group{{ $errors->has('identify') ? ' has-error' : '' }}">
                                            <label class="col-md-12 control-label" for="identify">Document Status :<span class="text-red">*</span></label>
                                            <div class="col-md-12">
                                                @foreach (\App\Models\UserDocument::$approvedType as $key1 => $value1)
                                                    <input class="" type="radio" id="identify{{$key1}}" name="identify" value="{{$key1}}">
                                                    <label for="identify{{$key1}}" class="pr-2">{{ $value1 }}</label>
                                                @endforeach
                                                @if ($errors->has('identify'))
                                                    <span class="text-danger" id="statusError">
                                                        <strong>{{ $errors->first('identify') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 d-none" id="rejection">
                                        <div class="form-group">
                                            <label class="control-label" for="reason">Reason For Rejection :<span class="text-red"></span></label>
                                            {!! Form::text('document_rejection_reason', null, ['class' => 'form-control', 'placeholder' => 'Enter Reason For Rejection', 'id' => 'reason']) !!}
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="card-footer">
                            <a href="{{ route('users.index') }}" ><button class="btn btn-default" type="button">Back</button></a>
                            <button class="btn btn-info float-right" type="submit">Update</button>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('jquery')
    <script>
        $('input:radio[name="identify"]').change(
            function(){
                if (this.checked && this.value ==  3) {
                    $('#rejection').removeClass('d-none');
                }else{
                    $('#rejection').val();
                    $('#rejection').addClass('d-none');
                }
            }
        );
    </script>
@endsection

