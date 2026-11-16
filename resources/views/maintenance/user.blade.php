@extends('layouts.app')

@section('pagecss')
<link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css' ) }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css' ) }}">
@endsection

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Users List</h1>
            </div>
            <div class="col-sm-6">
                <a href="{{ route('user.create') }}" class="btn btn-primary float-right">Add new User</a>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="row">
        <div class="col-12">

            
        <div class="card card-primary card-tabs">
        
            <div class="card-header p-0 pt-1">
                
                <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="custom-tabs-one-home-tab" data-toggle="pill" href="#custom-tabs-one-home" role="tab" aria-controls="custom-tabs-one-home" aria-selected="false">FMS</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="custom-tabs-one-profile-tab" data-toggle="pill" href="#custom-tabs-one-profile" role="tab" aria-controls="custom-tabs-one-profile" aria-selected="true">DPortal</a>
                    </li>
                </ul>

            </div>
                
            <div class="card-body">
                
                <div class="tab-content" id="custom-tabs-one-tabContent">
                
                    <div class="tab-pane active fade show" id="custom-tabs-one-home" role="tabpanel" aria-labelledby="custom-tabs-one-home-tab">

                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Username</th>
                                    <th>Department</th>
                                    <th>Role</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                <tr>
                                    <td class="text-uppercase">{{ ucwords($user->name) }}</td>
                                    <td>{{ $user->domainAccount }}</td>
                                    <td>{{ $user->dept }}</td>
                                    <td>{{ $user->role }}</td>
                                    <td>
                                        <a href="{{ route('user.edit',$user->id) }}" class="btn btn-xs btn-primary">Edit</a>
                                        <a href="javascript:;" onclick="delete_user('{{$user->id}}');" class="btn btn-xs btn-danger">Delete</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div>
                            <div class="card-footer clearfix">
                                Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }} items
                                <ul class="pagination pagination-sm m-0 float-right">
                                    {{ $users->links() }}
                                </ul>
                            </div>
                        </div>

                    </div>

                    <div class="tab-pane fade" id="custom-tabs-one-profile" role="tabpanel" aria-labelledby="custom-tabs-one-profile-tab">

                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Username</th>
                                    <th>Department</th>
                                    <th>Role</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($dportal_users as $d_user)
                                <tr>
                                    <td class="text-uppercase">{{ ucwords($d_user->fullname) }}</td>
                                    <td>{{ $d_user->domain }}</td>
                                    <td>{{ $d_user->dept_name }}</td>
                                    <td>Department Secretary</td>
                                    <td>
                                        <a href="{{ route('user-dportal.edit',$d_user->id) }}" class="btn btn-xs btn-primary">Edit</a>
                                        <a href="javascript:;" onclick="delete_dportal_user('{{$d_user->id}}');" class="btn btn-xs btn-danger">Delete</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div>
                            <div class="card-footer clearfix">
                                Showing {{ $dportal_users->firstItem() }} to {{ $dportal_users->lastItem() }} of {{ $dportal_users->total() }} items
                                <ul class="pagination pagination-sm m-0 float-right">
                                    {{ $dportal_users->links() }}
                                </ul>
                            </div>
                        </div>

                    </div>

                </div>
            </div>

        </div>


            <div class="card">
                <div class="card-body">
                    
                </div>
            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="modal-delete">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('user-delete') }}" method="post">
                @csrf
                <div class="modal-header">
                    <h4 class="modal-title">Confirmation</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>You are about to delete this user. Do you want to continue?</p>
                    <input type="hidden" id="uid" name="uid">
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-sm btn-danger">Yes, Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="modal-delete-dportal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('user-delete') }}" method="post">
                <input type="hidden" value="1" name="is_dportal">
                @csrf
                <div class="modal-header">
                    <h4 class="modal-title">Confirmation</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>You are about to delete this user. Do you want to continue?</p>
                    <input type="hidden" id="uid-dportal" name="uid">
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-sm btn-danger">Yes, Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('pagejs')
    <script src="{{ asset('assets/plugins/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ asset('assets/plugins/select2/js/select2.full.min.js') }}"></script>

    <script>
        function delete_user(id)
        {
            $('#modal-delete').modal('show');
            $('#uid').val(id);
        }
        function delete_dportal_user(id)
        {
            $('#modal-delete-dportal').modal('show');
            $('#uid-dportal').val(id);
        }
    </script>
@endsection