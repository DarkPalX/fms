@extends('layouts.app')

@section('pagecss')
<link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
@endsection

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                
            </div>
            <div class="col-sm-6">
	            <ol class="breadcrumb float-sm-right">
	                <li class="breadcrumb-item"><a href="#">Fuel Issuances</a></li>
	            </ol>
	        </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Fuel Settings</h3>
                    <div class="card-tools">
                        <form autocomplete="off" id="search_ref_no">
                            <div class="input-group input-group-sm" style="width: 250px;">
                                <input required type="search" name="ref_no" class="form-control float-right" placeholder="Search transaction #">

                                <div class="input-group-append">
                                     <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    
                    <form class="col-sm-4 offset-sm-4 mt-4 mb-4" action="{{ route('fuel-settings.update', $setting->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="odo"> Max Odo </label>
                            <input type="text" name="odo" class="form-control" value="{{ $setting->odo_max }}">
                        </div>

                        <div class="form-group">
                            <label for="odo"> Max Fuel </label>
                            <input type="text" name="fuel" class="form-control" value="{{ $setting->fuel_max }}">
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary"> Save </button>
                        </div>

                    </form>

                </div>
                
            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="modal-unpost">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('unpost-issuanced-request') }}" method="post">
                @csrf
                <div class="modal-header">
                    <h4 class="modal-title">Unpost Fuel Issuance</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>State your reason for unposting this issued fuel request. <i class="text-danger">*</i></p>
                    <textarea required class="form-control" name="remarks" rows="5"></textarea>
                    
                    <input type="hidden" name="rqid" id="ureq_id">
                    <input type="hidden" name="type" value="fuel">
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn btn-primary">Submit Request</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('pagejs')
<script src="{{ asset('assets/plugins/datatables/jquery.dataTables.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
@endsection