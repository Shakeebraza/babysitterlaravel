@extends('admin.layouts.app')
@section('content')
    <style>
        .pac-container {
            background-color: #FFF;
            z-index: 2001;
            position: fixed;
            display: inline-block;
            float: left;
        }
    </style>

    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>{{ucfirst($user['first_name']).' '.ucfirst($user['surname'])}} - <small>Request</small></h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{route('users.index')}}">{{$menu}}</a></li>
                            <li class="breadcrumb-item active">Request</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            @include ('admin.error')
            <div class="row">
                <div class="col-12">
                    <div class="card card-info card-outline">
                        <div class="card-body table-responsive">
                            <table id="requestTable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>From Date</th>
                                        <th>To Date</th>
                                        <th>Group Visibility</th>
                                        <th>Public Visibility</th>
                                        <th>Created Date</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <div class="modal fade" id="detailsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Request</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {!! Form::open(['id' => 'userRequestForm', 'class' => 'form-horizontal','files'=>true]) !!}
                        <input type="hidden" name="requestId" id="requestId">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label" for="title">Title :<span class="text-red">*</span></label>
                                    {!! Form::text('title', null, ['class' => 'form-control', 'placeholder' => 'Enter Title', 'id' => 'requestTitle']) !!}
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label" for="description">Description :</label>
                                    {!! Form::text('description', null, ['class' => 'form-control', 'placeholder' => 'Enter Description', 'id' => 'description']) !!}
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label" for="from_date">From Date :<span class="text-red">*</span></label>
                                    {!! Form::datetimeLocal('from_date', null, ['class' => 'form-control', 'placeholder' => 'Enter From Date', 'id' => 'fromDate']) !!}
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label" for="to_date">To Date :<span class="text-red">*</span></label>
                                    {!! Form::datetimeLocal('to_date', null, ['class' => 'form-control', 'placeholder' => 'Enter To Date', 'id' => 'toDate']) !!}
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group{{ $errors->has('group_visibility') ? ' has-error' : '' }}">
                                    <label class="col-md-12 control-label" for="group_visibility">Group Visibility :</label>
                                    <div class="col-md-12">
                                        <span class="mr-2">
                                            <input type="hidden" name="group_visibility" value="0">
                                            {!! Form::checkbox('group_visibility', 1, old('group_visibility'), ['id' =>'group_visibility']) !!}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group{{ $errors->has('public_visibility') ? ' has-error' : '' }}">
                                    <label class="col-md-12 control-label" for="public_visibility">Public Visibility :</label>
                                    <div class="col-md-12">
                                        <span class="mr-2">
                                            <input type="hidden" name="public_visibility" value="0">
                                            {!! Form::checkbox('public_visibility', 1, old('public_visibility'), ['id' =>'public_visibility']) !!}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12" id="groupSelection" style="display: none">
                                <div class="form-group{{ $errors->has('group') ? ' has-error' : '' }}">
                                    <label class="col-md-12 control-label" for="group">Group :<span class="text-red">*</span></label>
                                    <div class="col-md-12" id="groupDropDown">
                                        {!! Form::select('group[]', $group, null, ['class' => 'form-control select2','multiple','id'=>'oldSelection','style'=>'width:100%','data-placeholder'=>'Select Group']) !!}
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group{{ $errors->has('address_type') ? ' has-error' : '' }}">
                                    <label class="col-md-12 control-label" for="address_type">Address Type :<span class="text-red">*</span></label>
                                    <div class="col-md-12">
                                        @foreach (\App\Models\UserRequest::$addressType as $key1 => $value1)
                                            <span class="mr-2">
                                                {!! Form::radio('address_type', $key1, null, ['id' =>'address_type_'.$key1, 'class' => 'addressTypeRadio']) !!}
                                                <label for="address_type_{{$key1}}">{{$value1}}</label>
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12" id="otherAddress" style="display: none">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group{{ $errors->has('address') ? ' has-error' : '' }}">
                                            <label class="control-label" for="address">Address :<span class="text-red">*</span></label>
                                            {!! Form::text('address', null, ['class' => 'form-control', 'placeholder' => 'Search Address', 'id' => 'ship-address']) !!}
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group{{ $errors->has('street') ? ' has-error' : '' }}">
                                            <label class="control-label" for="street">Street :<span class="text-red">*</span></label>
                                            {!! Form::text('street', null, ['class' => 'form-control', 'placeholder' => 'Enter street', 'id' => 'street']) !!}
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group{{ $errors->has('zip') ? ' has-error' : '' }}">
                                            <label class="control-label" for="zip">Zip :<span class="text-red">*</span></label>
                                            {!! Form::text('zip', null, ['class' => 'form-control', 'placeholder' => 'Enter Zip', 'id' => 'zip']) !!}
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group{{ $errors->has('city') ? ' has-error' : '' }}">
                                            <label class="control-label" for="city">City :<span class="text-red">*</span></label>
                                            {!! Form::text('city', null, ['class' => 'form-control', 'placeholder' => 'Enter City', 'id' => 'city']) !!}
                                        </div>
                                    </div>

                                    {!! Form::hidden('latitude', null, ['class' => 'form-control', 'id' => 'latitude']) !!}
                                    {!! Form::hidden('longitude', null, ['class' => 'form-control', 'id' => 'longitude']) !!}
                                </div>
                            </div>
                        </div>
                    {!! Form::close() !!}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="saveRequest">Update</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('jquery')
    <script type="text/javascript">
        $(function () {
            var table = $('#requestTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('users.myRequest',['id'=>$user->id]) }}",
                columns: [
                    {data: 'title', "width": "15%", name: 'title'},
                    {data: 'from_date', "width": "15%", name: 'from_date'},
                    {data: 'to_date', "width": "15%", name: 'to_date'},
                    {data: 'group_visibility', "width": "15%", name: 'group_visibility'},
                    {data: 'public_visibility', "width": "15%", name: 'public_visibility'},
                    {data: 'created_at', "width": "15%", name: 'created_at'},
                    {data: 'status', "width": "15%", name: 'status',
                        render: function(data, type, row) {
                            var  statusBtn = '';
                            if (data == "active") {
                                statusBtn += '<div class="btn-group-horizontal" id="assign_remove_"'+row.id+ '">'+
                                    '<button class="btn btn-success unassign ladda-button" data-style="slide-left" id="remove" url="{{route('userRequest.unassign')}}" ruid="' +row.id+'"  type="button" style="height:28px; padding:0 12px"><span class="ladda-label">Active</span> </button>'+
                                    '</div>';
                                statusBtn += '<div class="btn-group-horizontal" id="assign_add_"' +row.id+'"  style="display: none"  >'+
                                    '<button class="btn btn-danger assign ladda-button" data-style="slide-left" id="assign" uid="' +row.id+ '" url="{{route('userRequest.assign')}}" type="button"  style="height:28px; padding:0 12px"><span class="ladda-label">In Active</span></button>'+
                                    '</div>';
                            } else {
                                statusBtn += '<div class="btn-group-horizontal" id="assign_add_"' +row.id+ '">'+
                                    '<button class="btn btn-danger assign ladda-button" id="assign" data-style="slide-left" uid="'+row.id+'" url="{{route('userRequest.assign')}}"  type="button" style="height:28px; padding:0 12px"><span class="ladda-label">In Active</span></button>'+
                                    '</div>';
                                statusBtn += '<div class="btn-group-horizontal" id="assign_remove_"' +row.id+ '" style="display: none" >'+
                                    '<button class="btn  btn-success unassign ladda-button" id="remove" ruid="' +row.id+ '" data-style="slide-left" url="{{route('userRequest.unassign')}}" type="button" style="height:28px; padding:0 12px"><span class="ladda-label">Active</span></button>'+
                                    '</div>';
                            }
                            return statusBtn;
                        }
                    },
                    {data: 'action', "width": "15%", name: 'action',
                        render: function(data, type, row) {
                            var  actionBtn = '<div class="btn-group">'+
                                '<button type="button" class="btn btn-info btn-sm">Action</button>'+
                                '<button type="button" class="btn btn-info btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">'+
                                '<span class="sr-only">Toggle Dropdown</span>'+
                                '</button>'+
                                '<div class="dropdown-menu" role="menu">'+
                                '<a class="dropdown-item editRequest" data-id="'+row.id+'"  href="javascript:void(0)"><i class="fa fa-edit text-primary pr-2"></i>Edit</a>'+
                                '<a class="dropdown-item deleteRequest" data-id="'+row.id+'"  href="javascript:void(0)"><i class="fa fa-trash text-danger pr-2"></i>Delete</a>'+
                                '</div>'+
                                '</div>';
                            return actionBtn;
                        }
                    },
                ]
            });

            $('#requestTable tbody').on('click', '.editRequest', function (event) {
                event.preventDefault();
                var requestId = $(this).attr("data-id");
                $.ajax({
                    url: "{{url('admin/userRequest/details')}}/"+requestId,
                    type: "POST",
                    data: {_token: '{{csrf_token()}}' },
                    success: function(data){
                        if(data.status == 1){
                            $('#requestId').val(requestId);
                            $('#requestTitle').val(data.userRequest.title);
                            $('#description').val(data.userRequest.description);
                            $('#fromDate').val(data.userRequest.from_date);
                            $('#toDate').val(data.userRequest.to_date);
                            $('#ship-address').val(data.userRequest.address);
                            $('#street').val(data.userRequest.street);
                            $('#zip').val(data.userRequest.zip);
                            $('#city').val(data.userRequest.city);
                            $('#latitude').val(data.userRequest.latitude);
                            $('#longitude').val(data.userRequest.longitude);

                            if (data.userRequest.group_visibility) {
                                $("#group_visibility").attr('checked', 'checked');
                                $('#groupSelection').show();
                                $('#groupDropDown').html(data.group);
                            }

                            if (data.userRequest.public_visibility) {
                                $("#public_visibility").attr('checked', 'checked');
                            }

                            $("#address_type_"+data.userRequest.address_type).attr('checked', 'checked');
                            if(data.userRequest.address_type == 'other'){
                                $('#otherAddress').show();
                            }

                            $('#detailsModal').modal('show');
                            $('.select2').select2();
                        }else{
                            swal("Cancelled", "User Request details not found!", "error");
                        }
                    }
                });
            });

            $('#saveRequest').on('click', function(event){
                event.preventDefault();
                var requestId = $('#requestId').val();
                $.ajax({
                    url: "{{url('admin/userRequest/updateDetails')}}/"+requestId,
                    type: "POST",
                    data: $('#userRequestForm').serialize(),
                    success: function(data){
                        if(data == 1){
                            $('#detailsModal').modal('hide');
                            table.draw(false);
                            swal("Success", "Your data successfully updated!", "success");
                        }else{
                            swal("Cancelled", "Group details not found!", "error");
                        }
                    }
                });
            });

            $('#requestTable tbody').on('click', '.deleteRequest', function (event) {
                event.preventDefault();
                var groupId = $(this).attr("data-id");
                swal({
                        title: "Are you sure?",
                        text: "You want to delete this request?",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: '#DD6B55',
                        confirmButtonText: 'Yes, Delete',
                        cancelButtonText: "No, cancel",
                        closeOnConfirm: false,
                        closeOnCancel: false
                    },
                    function(isConfirm) {
                        if (isConfirm) {
                            $.ajax({
                                url: "{{url('admin/userRequest/delete')}}/"+groupId,
                                type: "POST",
                                data: {_token: '{{csrf_token()}}' },
                                success: function(data){
                                    console.log(data);
                                    table.row('.selected').remove().draw(false);
                                    swal("Deleted", "Your data successfully deleted!", "success");
                                }
                            });
                        } else {
                            swal("Cancelled", "Your data safe!", "error");
                        }
                    });
            });

            $('#requestTable tbody').on('click', '.assign', function (event) {
                event.preventDefault();
                var user_id = $(this).attr('uid');
                var url = $(this).attr('url');
                var l = Ladda.create(this);
                l.start();
                $.ajax({
                    url: url,
                    type: "post",
                    data: {'id': user_id},
                    headers: { 'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content') },
                    success: function(data){
                        l.stop();
                        $('#assign_remove_'+user_id).show();
                        $('#assign_add_'+user_id).hide();
                        table.draw(false);
                    }
                });
            });

            $('#requestTable tbody').on('click', '.unassign', function (event) {
                event.preventDefault();
                var user_id = $(this).attr('ruid');
                var url = $(this).attr('url');
                var l = Ladda.create(this);
                l.start();
                $.ajax({
                    url: url,
                    type: "post",
                    data: {'id': user_id,'_token' : $('meta[name=_token]').attr('content') },
                    success: function(data){
                        l.stop();
                        $('#assign_remove_'+user_id).hide();
                        $('#assign_add_'+user_id).show();
                        table.draw(false);
                    }
                });
            });

            $('#group_visibility').on('change', function(){
                var visibility = $(this).val();
                if(visibility){
                    $('#groupSelection').show();
                }else{
                    $('#groupSelection').hide();
                }
            });

            $('.addressTypeRadio').on('change', function(){
                var addressType = $(this).val();
                if(addressType == 'other'){
                    $('#otherAddress').show();
                }else{
                    $('#otherAddress').hide();
                }
            });
        });
    </script>

    @include('admin.users.mapScript')
@endsection
