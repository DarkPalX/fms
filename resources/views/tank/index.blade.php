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
                    <h1 class="m-0 text-dark">Tank List</h1>
                </div>
                <div class="col-sm-6">
                    <a href="{{ route('tanks.create') }}" class="btn btn-primary btn-sm float-right">Create Tank</a>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Location</th>
                                    <th>Capacity</th>
                                    <th>Remaining Fuel</th>
                                    <th>Status</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tanks as $tank)
                                <tr>
                                    <td class="text-uppercase">{{ ucfirst($tank->name) }}</td>
                                    <td>{{ $tank->location }}</td>
                                    <td>{{ $tank->capacity }}</td>
                                    <td>{{ $tank->remaining_fuel }}</td>
                                    <td>
                                        @if($tank->is_active)
                                            <span class="badge badge-success"> Yes </span>
                                        @else
                                        <span class="badge badge-danger"> No </span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('tanks.edit',$tank->id) }}" class="btn btn-xs btn-primary">Edit</a>
                                        <a href="javascript:;" onclick="delete_user('{{$tank->id}}');" class="btn btn-xs btn-danger">Delete</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div>
                            <div class="card-footer clearfix">
                                Showing {{ $tanks->firstItem() }} to {{ $tanks->lastItem() }} of {{ $tanks->total() }} items
                                <ul class="pagination pagination-sm m-0 float-right">
                                    {{ $tanks->links() }}
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="modal-delete">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('tank-delete') }}" method="post">
                    @csrf
                    <div class="modal-header">
                        <h4 class="modal-title">Confirmation</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>You are about to delete this tabk. Do you want to continue?</p>
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
    </script>
@endsection