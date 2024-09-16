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
                        <div class="card-body table-responsive">
                            <table id="feedbackTable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>User</th>
                                        <th>Subject</th>
                                        <th>Description</th>
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
            var table = $('#feedbackTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('feedback.index') }}",
                columns: [
                    {data: 'user', "width": "15%", name: 'user'},
                    {data: 'subject', "width": "25%", name: 'subject'},
                    {data: 'description', "width": "53%", name: 'description'},
                    {data: 'action', "width": "7%", name: 'action', orderable: false, searchable: false,
                        render: function(data, type, row) {
                            var  actionBtn = '';
                            actionBtn += '<span data-toggle="tooltip" title="Show User" data-trigger="hover">'+
                                '<a href="../admin/users/' + row.user_id + '/edit"><button class="btn btn-sm btn-success" type="button"><i class="fa fa-user"></i></button></a>'
                                '</span>';
                            actionBtn += '<span data-toggle="tooltip" title="Delete Feedback" data-trigger="hover">'+
                                                            '<button class="btn btn-sm btn-danger deleteFeedback" data-id="'+row.id+'" type="button"><i class="fa fa-trash"></i></button>'+
                                                        '</span>';
                            return actionBtn;
                        }
                    },
                ]
            });

            $('#feedbackTable tbody').on('click', '.deleteFeedback', function (event) {
                event.preventDefault();
                var roleId = $(this).attr("data-id");
                swal({
                    title: "Are you sure?",
                    text: "You want to delete this feedback?",
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
                            url: "{{url('admin/feedback')}}/"+roleId,
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
        });
    </script>
@endsection
