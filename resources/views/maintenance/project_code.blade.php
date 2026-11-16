@extends('layouts.app')

@section('pagecss')
<link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css' ) }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css' ) }}">
@endsection

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                    <li class="breadcrumb-item">Settings</li>
                    <li class="breadcrumb-item active">Project Codes</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Project Codes Table</h3>
                        <a href="" class="btn btn-sm btn-primary float-right" data-toggle="modal" data-target="#modal-upload"><i class="fa fa-upload"></i> Upload Project Codes</a>
                    </div>

                    <div class="card-body">
                        <table class="table table-bordered table-striped">
                            <thead>                  
                                <tr>
                                    <th>#</th>
                                    <th>Project Code</th>
                                    <th>Project Description</th>
                                    <th>Phase Code</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($codes as $code)
                                    <tr>
                                        <td>{{ ($codes->currentpage()-1) * $codes ->perpage() + $loop->index + 1 }}</td>
                                        <td>{{ $code->project_code }}</td>
                                        <td>{{ $code->project_desc }}</td>
                                        <td>{{ $code->phase_code }}</td>
                                        <td>
                                            <a href="" class="btn btn-xs btn-primary update" data-target="#modal-update" data-toggle="modal" data-pid="{{$code->id}}" data-pcode="{{$code->project_code}}" data-pdesc="{{$code->project_desc}}" data-phcode="{{$code->phase_code}}">Edit</a>
                                            <a href="#modal-delete" data-toggle="modal" data-pid="{{$code->id}}" class="btn btn-xs btn-danger delete">Delete</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="row card-footer clearfix">
                        <div class="col-md-6">
                            <p>Showing {{$codes->firstItem()}} to {{$codes->lastItem()}} of {{$codes->total()}} items</p>
                        </div>
                        <div class="col-md-6">
                            <ul class="pagination pagination-sm m-0 float-right">
                                {{ $codes->links() }}
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="modal-upload">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('projectcode-import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h4 class="modal-title">Upload Codes</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="file" name="file" class="form-control">
                            </div>
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-sm btn-primary">Upload</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-update">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('projectcode-update') }}" method="post">
                @csrf
                <div class="modal-header">
                    <h4 class="modal-title">Update Code</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="pid" name="projectid">
                    <div class="form-group">
                        <label for="inputName">Project Code</label>
                        <input required type="text" class="form-control" id="pcode" name="projectcode">
                    </div>
                    <div class="form-group">
                        <label for="inputDomain">Description <i class="text-danger">*</i></label>
                        <input required type="text" class="form-control" id="pdesc" name="pdesc">
                    </div>
                    <div class="form-group">
                        <label for="inputDomain">Phase Code <i class="text-danger">*</i></label>
                        <input required type="text" class="form-control" id="phcode" name="phasecode">
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn <button></button> btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-delete">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('projectcode-delete') }}" method="post">
                @csrf
                <div class="modal-header">
                    <h4 class="modal-title">Confirmation</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>You are about to delete this department code permanently. Do you want to continue?</p>
                    <input type="hidden" id="dpid" name="projectid">
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger">Yes, Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('pagejs')
<script src="{{ asset('assets/plugins/datatables/jquery.dataTables.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>

<script>
    $(document).on('click','.update', function(){
        $('#pid').val($(this).data('pid'));
        $('#pcode').val($(this).data('pcode'));
        $('#pdesc').val($(this).data('pdesc'));
        $('#phcode').val($(this).data('phcode'));
    });

    $(document).on('click','.delete', function(){
        $('#dpid').val($(this).data('pid'));
    });
</script>
@endsection
