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
                            <table id="usersTable" class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>First Name</th>
                                    <th>Surname</th>
                                    <th>Email</th>
                                    <th>Documents</th>
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
                ajax: "{{ route('openIdentify') }}",
                columns: [
                    {data: 'first_name', "width": "15%", name: 'first_name'},
                    {data: 'surname', "width": "15%", name: 'surname'},
                    {data: 'email',  ame: 'email'},
                    {data: 'action', "width": "15%", name: 'action', orderable: false, searchable: false,
                        render: function(data, type, row) {
                            var  actionBtn = '';
                            if(row.documentCount > 0){
                                actionBtn += '<a class="dropdown-item" href="'+row.document_url+'"><i class="fa fa-file-invoice text-primary pr-2"></i></a>';
                            }
                            return actionBtn;
                        }
                    },
                ]
            });
        });
    </script>
@endsection
