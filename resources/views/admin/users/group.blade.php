@extends('admin.layouts.app')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>{{ucfirst($user['first_name']).' '.ucfirst($user['surname'])}} - <small>Groups</small></h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{route('users.index')}}">{{$menu}}</a></li>
                            <li class="breadcrumb-item active">Group</li>
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
                            <table id="groupTable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Name</th>
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
                    <h5 class="modal-title" id="exampleModalLabel">Edit Group</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="groupId" id="groupId">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label" for="name">Name :<span class="text-red">*</span></label>
                                {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Enter Name', 'id' => 'groupName']) !!}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="saveGroup">Update</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('jquery')
    <script type="text/javascript">
        $(function () {
            var table = $('#groupTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('users.myGroups',['id'=>$user->id]) }}",
                columns: [
                    {data: 'name', "width": "15%", name: 'name'},
                    {data: 'created_at', "width": "15%", name: 'created_at'},
                    {data: 'status', "width": "10%", name: 'status',
                        render: function(data, type, row) {
                            var  statusBtn = '';
                            if (data == "active") {
                                statusBtn += '<div class="btn-group-horizontal" id="assign_remove_"'+row.id+ '">'+
                                    '<button class="btn btn-success unassign ladda-button" data-style="slide-left" id="remove" url="{{route('group.unassign')}}" ruid="' +row.id+'"  type="button" style="height:28px; padding:0 12px"><span class="ladda-label">Active</span> </button>'+
                                    '</div>';
                                statusBtn += '<div class="btn-group-horizontal" id="assign_add_"' +row.id+'"  style="display: none"  >'+
                                    '<button class="btn btn-danger assign ladda-button" data-style="slide-left" id="assign" uid="' +row.id+ '" url="{{route('group.assign')}}" type="button"  style="height:28px; padding:0 12px"><span class="ladda-label">In Active</span></button>'+
                                    '</div>';
                            } else {
                                statusBtn += '<div class="btn-group-horizontal" id="assign_add_"' +row.id+ '">'+
                                    '<button class="btn btn-danger assign ladda-button" id="assign" data-style="slide-left" uid="'+row.id+'" url="{{route('group.assign')}}"  type="button" style="height:28px; padding:0 12px"><span class="ladda-label">In Active</span></button>'+
                                    '</div>';
                                statusBtn += '<div class="btn-group-horizontal" id="assign_remove_"' +row.id+ '" style="display: none" >'+
                                    '<button class="btn  btn-success unassign ladda-button" id="remove" ruid="' +row.id+ '" data-style="slide-left" url="{{route('group.unassign')}}" type="button" style="height:28px; padding:0 12px"><span class="ladda-label">Active</span></button>'+
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
                                '<a class="dropdown-item editGroup" data-id="'+row.id+'"  href="javascript:void(0)"><i class="fa fa-edit text-primary pr-2"></i>Edit</a>'+
                                '<a class="dropdown-item deleteGroup" data-id="'+row.id+'"  href="javascript:void(0)"><i class="fa fa-trash text-danger pr-2"></i>Delete</a>'+
                                '</div>'+
                                '</div>';
                            return actionBtn;
                        }
                    },
                ]
            });

            $('#groupTable tbody').on('click', '.editGroup', function (event) {
                event.preventDefault();
                var kidsId = $(this).attr("data-id");
                $.ajax({
                    url: "{{url('admin/group/details')}}/"+kidsId,
                    type: "POST",
                    data: {_token: '{{csrf_token()}}' },
                    success: function(data){
                        if(data.status == 1){
                            $('#groupId').val(kidsId);
                            $('#groupName').val(data.name);
                            $('#detailsModal').modal('show');
                        }else{
                            swal("Cancelled", "Group details not found!", "error");
                        }
                    }
                });
            });

            $('#saveGroup').on('click', function(event){
                event.preventDefault();
                var groupId = $('#groupId').val();
                var groupName = $('#groupName').val();
                $.ajax({
                    url: "{{url('admin/group/updateDetails')}}/"+groupId,
                    type: "POST",
                    data: {name:groupName,_token: '{{csrf_token()}}'},
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

            $('#groupTable tbody').on('click', '.deleteGroup', function (event) {
                event.preventDefault();
                var groupId = $(this).attr("data-id");
                swal({
                        title: "Are you sure?",
                        text: "You want to delete this group?",
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
                                url: "{{url('admin/group/delete')}}/"+groupId,
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

            $('#groupTable tbody').on('click', '.assign', function (event) {
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

            $('#groupTable tbody').on('click', '.unassign', function (event) {
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
