@extends('admin.layouts.app')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>{{$menu}}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                            <li class="breadcrumb-item active">{{$menu}}</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            @include ('admin.error')
            <div id="responce" class="alert alert-success" style="display: none">
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card card-info card-outline">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-12">
                                    <button class="btn btn-info float-right" id="sendWelcomeMail" type="button"><i class="fa fa-message pr-1"></i> Send Welcome Mail</button>
                                    <a href="{{ route('users.create') }}"><button class="btn btn-info float-right" type="button"><i class="fa fa-plus pr-1"></i> Add New</button></a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body table-responsive">
                            <table id="usersTable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Created</th>
                                        <th>First Name</th>
                                        <th>Surname</th>
                                        <th>Email</th>
                                        <th>Usage level</th>
                                        <th style="width: 15%;">Status</th>
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
@endsection

@section('jquery')
<script type="text/javascript">
    $(function () {
        var table = $('#usersTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('users.index') }}",
            columns: [
                {data: 'created_at', "width": "15%", name: 'created_at'},
                {data: 'first_name', "width": "15%", name: 'first_name'},
                {data: 'surname', "width": "15%", name: 'surname'},
                {data: 'email',  name: 'email'},
                {data: 'usage_level',  name: 'usage_level'},
                {data: 'status', "width": "15%", name: 'status',
                    render: function(data, type, row) {
                        var  statusBtn = '';
                        if (data == "active") {
                            statusBtn += '<div class="btn-group-horizontal" id="assign_remove_"'+row.id+ '">'+
                            '<button class="btn btn-success unassign ladda-button" data-style="slide-left" id="remove" url="{{route('users.unassign')}}" ruid="' +row.id+'"  type="button" style="height:28px; padding:0 12px"><span class="ladda-label">Active</span> </button>'+
                            '</div>';
                            statusBtn += '<div class="btn-group-horizontal" id="assign_add_"' +row.id+'"  style="display: none"  >'+
                            '<button class="btn btn-danger assign ladda-button" data-style="slide-left" id="assign" uid="' +row.id+ '" url="{{route('users.assign')}}" type="button"  style="height:28px; padding:0 12px"><span class="ladda-label">In Active</span></button>'+
                            '</div>';
                        } else {
                            statusBtn += '<div class="btn-group-horizontal" id="assign_add_"' +row.id+ '">'+
                            '<button class="btn btn-danger assign ladda-button" id="assign" data-style="slide-left" uid="'+row.id+'" url="{{route('users.assign')}}"  type="button" style="height:28px; padding:0 12px"><span class="ladda-label">In Active</span></button>'+
                            '</div>';
                            statusBtn += '<div class="btn-group-horizontal" id="assign_remove_"' +row.id+ '" style="display: none" >'+
                            '<button class="btn  btn-success unassign ladda-button" id="remove" ruid="' +row.id+ '" data-style="slide-left" url="{{route('users.unassign')}}" type="button" style="height:28px; padding:0 12px"><span class="ladda-label">Active</span></button>'+
                            '</div>';
                        }
                        return statusBtn;
                    }
                },
                {data: 'action', "width": "15%", name: 'action', orderable: false, searchable: false},
            ]
        });

        $('#usersTable tbody').on('click', '.resendCode', function (event) {
            event.preventDefault();
            var userId = $(this).attr("data-id");
            swal({
                    title: "Are you sure?",
                    text: "You want to resend the verification code to the user?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: '#DD6B55',
                    confirmButtonText: 'Yes, send',
                    cancelButtonText: "No, cancel",
                    closeOnConfirm: false,
                    closeOnCancel: false
                },
                function(isConfirm) {
                    if (isConfirm) {
                        $.ajax({
                            url: "{{route('users.sendVerification')}}/",
                            type: "POST",
                            data: {
                                'id': userId,
                                _token: '{{csrf_token()}}'
                            },
                            success: function(data){
                                console.log(data);
                                swal("Sent", "Verification successfully sent!", "success");
                            }
                        });
                    } else {
                        swal("Cancelled", "Verification not sent", "error");
                    }
                });
        });

        $('#usersTable tbody').on('click', '.deleteUser', function (event) {
            event.preventDefault();
            var roleId = $(this).attr("data-id");
            swal({
                    title: "Are you sure?",
                    text: "You want to delete this user?",
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
                        url: "{{url('admin/users')}}/"+roleId,
                        type: "DELETE",
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

        $('#usersTable tbody').on('click', '.assign', function (event) {
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

        $('#usersTable tbody').on('click', '.unassign', function (event) {
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

        $('#sendWelcomeMail').on('click', function (event) {
            event.preventDefault();
            $.ajax({
                url: "{{route('users.welcome')}}/",
                type: "POST",
                data: {
                    _token: '{{csrf_token()}}'
                },
                success: function(data){
                    console.log(data);
                }
            });
        });
    });
  </script>
@endsection
