@php
        $documentAction = count($row['Documents']) > 0 ? '<a class="dropdown-item" href="'.url('admin/users/'.$row->id).'"><i class="fa fa-file-invoice text-primary pr-2"></i>Document</a>': '';
@endphp
<div class="btn-group">
    <button type="button" class="btn btn-info btn-sm">Action</button>
    <button type="button" class="btn btn-info btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
        <span class="sr-only">Toggle Dropdown</span>
    </button>
    <div class="dropdown-menu" role="menu">
        <a class="dropdown-item resendCode" data-id="{{$row->id}}"  href="javascript:void(0)"><i class="fa fa-mail-bulk text-primary pr-2"></i>Resend Code</a>
        <a class="dropdown-item" href="{{url('admin/users/'.$row->id.'/edit')}}"><i class="fa fa-edit text-primary pr-2"></i>Edit</a>
        {!! $documentAction !!}
        <a class="dropdown-item deleteUser" data-id="{{$row->id}}"  href="javascript:void(0)"><i class="fa fa-trash text-danger pr-2"></i>Delete</a>
        <div class="dropdown-divider"></div>
        <a class="dropdown-item" href="{{route('users.myKids',['id'=>$row->id])}}"><i class="fa fa-child text-primary pr-2"></i>My Kids</a>
        <a class="dropdown-item" href="{{route('users.myGroups',['id'=>$row->id])}}"><i class="fa fa-sitemap text-primary pr-2"></i>My Groups</a>
        <a class="dropdown-item" href="{{route('users.myRequest',['id'=>$row->id])}}"><i class="fa fa-universal-access text-primary pr-2"></i>My Request</a>
    </div>
</div>
