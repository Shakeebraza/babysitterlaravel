@extends('admin.layouts.app')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>{{ucfirst($user['first_name']).' '.ucfirst($user['surname'])}} - <small>Kids</small></h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{route('users.index')}}">{{$menu}}</a></li>
                            <li class="breadcrumb-item active">Kids</li>
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
                            <table id="kidsTable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Date Of Birth</th>
                                        <th>Age</th>
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
                    <h5 class="modal-title" id="exampleModalLabel">Edit Kids</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="kidsId" id="kidsId">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label" for="name">Name :<span class="text-red">*</span></label>
                                {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Enter Name', 'id' => 'kidName']) !!}
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group{{ $errors->has('date_of_birth') ? ' has-error' : '' }}">
                                <label class="control-label" for="date_of_birth">Date Of Birth :<span class="text-red">*</span></label>
                                {!! Form::date('date_of_birth', null, ['class' => 'form-control', 'placeholder' => 'Enter Date Of Birth', 'id' => 'dob']) !!}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="saveKids">Update</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('jquery')
    <script type="text/javascript">
        $(function () {
            var table = $('#kidsTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('users.myKids',['id'=>$user->id]) }}",
                columns: [
                    {data: 'name', "width": "15%", name: 'name'},
                    {data: 'date_of_birth', "width": "15%", name: 'surname'},
                    {data: 'age', "width": "15%", name: 'age'},
                    {data: 'status', "width": "15%", name: 'status',
                        render: function(data, type, row) {
                            var  statusBtn = '';
                            if (data == "active") {
                                statusBtn += '<div class="btn-group-horizontal" id="assign_remove_"'+row.id+ '">'+
                                    '<button class="btn btn-success unassign ladda-button" data-style="slide-left" id="remove" url="{{route('kids.unassign')}}" ruid="' +row.id+'"  type="button" style="height:28px; padding:0 12px"><span class="ladda-label">Active</span> </button>'+
                                    '</div>';
                                statusBtn += '<div class="btn-group-horizontal" id="assign_add_"' +row.id+'"  style="display: none"  >'+
                                    '<button class="btn btn-danger assign ladda-button" data-style="slide-left" id="assign" uid="' +row.id+ '" url="{{route('kids.assign')}}" type="button"  style="height:28px; padding:0 12px"><span class="ladda-label">In Active</span></button>'+
                                    '</div>';
                            } else {
                                statusBtn += '<div class="btn-group-horizontal" id="assign_add_"' +row.id+ '">'+
                                    '<button class="btn btn-danger assign ladda-button" id="assign" data-style="slide-left" uid="'+row.id+'" url="{{route('kids.assign')}}"  type="button" style="height:28px; padding:0 12px"><span class="ladda-label">In Active</span></button>'+
                                    '</div>';
                                statusBtn += '<div class="btn-group-horizontal" id="assign_remove_"' +row.id+ '" style="display: none" >'+
                                    '<button class="btn  btn-success unassign ladda-button" id="remove" ruid="' +row.id+ '" data-style="slide-left" url="{{route('kids.unassign')}}" type="button" style="height:28px; padding:0 12px"><span class="ladda-label">Active</span></button>'+
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
                                                            '<a class="dropdown-item editKids" data-id="'+row.id+'"  href="javascript:void(0)"><i class="fa fa-edit text-primary pr-2"></i>Edit</a>'+
                                                            '<a class="dropdown-item deleteKids" data-id="'+row.id+'"  href="javascript:void(0)"><i class="fa fa-trash text-danger pr-2"></i>Delete</a>'+
                                                            '</div>'+
                                                        '</div>';
                            return actionBtn;
                        }
                    },
                ]
            });

            $('#kidsTable tbody').on('click', '.editKids', function (event) {
                event.preventDefault();
                var kidsId = $(this).attr("data-id");
                $.ajax({
                    url: "{{url('admin/kids/details')}}/"+kidsId,
                    type: "POST",
                    data: {_token: '{{csrf_token()}}' },
                    success: function(data){
                        if(data.status == 1){
                            $('#kidsId').val(kidsId);
                            $('#kidName').val(data.name);
                            $('#dob').val(data.dob);
                            $('#detailsModal').modal('show');
                        }else{
                            swal("Cancelled", "Kids details not found!", "error");
                        }
                    }
                });
            });

            $('#saveKids').on('click', function(event){
                event.preventDefault();
                var kidsId = $('#kidsId').val();
                var kidName = $('#kidName').val();
                var dob = $('#dob').val();
                $.ajax({
                    url: "{{url('admin/kids/updateDetails')}}/"+kidsId,
                    type: "POST",
                    data: {name:kidName,date_of_birth:dob,_token: '{{csrf_token()}}'},
                    success: function(data){
                        if(data == 1){
                            $('#detailsModal').modal('hide');
                            table.draw(false);
                            swal("Success", "Your data successfully updated!", "success");
                        }else{
                            swal("Cancelled", "Kids details not found!", "error");
                        }
                    }
                });
            });

            $('#kidsTable tbody').on('click', '.deleteKids', function (event) {
                event.preventDefault();
                var kidsId = $(this).attr("data-id");
                swal({
                        title: "Are you sure?",
                        text: "You want to delete this kid?",
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
                                url: "{{url('admin/kids/delete')}}/"+kidsId,
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

            $('#kidsTable tbody').on('click', '.assign', function (event) {
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

            $('#kidsTable tbody').on('click', '.unassign', function (event) {
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
        });
    </script>
@endsection
