@extends('layouts.app')

@section('pagecss')
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endsection

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Fuel Issuances</a></li>
                    <li class="breadcrumb-item">Vehicles</li>
                    <li class="breadcrumb-item active">Create</li>
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
                        <h3 class="card-title">Add New Vehicle</h3>
                    </div>
                    <div class="card-body">
                        <form autocomplete="off" role="form" method="post" action="{{ route('vehicle-store') }}">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Name <i class="text-danger">*</i></label>
                                            <input type="hidden" name="vid">
                                            <input required type="text" name="name" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Plate No.</label>
                                            <input type="text" name="plateno" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Cost Code <i class="text-danger">*</i></label>
                                            <input required type="text" name="ccode" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Type <i class="text-danger">*</i></label>
                                            <input required type="text" name="type" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Department <i class="text-danger">*</i></label>
                                            <input required type="text" name="dept" class="form-control">
                                        </div>
                                    </div>
                                    <!-- <div class="col-sm-6">
                                        <div class="form-group">
                                            <div class="select2-purple">
                                                <label>Location <i class="text-danger">*</i></label>
                                                <select class="select2" multiple="multiple" data-placeholder="Select a State" style="width: 100%;">
                                                    <option>None</option>
                                                    <option>Mill</option>
                                                    <option>Mine</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div> -->
                                </div>   
                            </div>

                            <div class="card-footer">
                                <a href="{{ route('vehicle.lists') }}" class="btn btn-secondary">Back</a>
                                <button type="submit" class="btn btn-primary">Create</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('pagejs')
    <script src="{{ asset('assets/plugins/select2/js/select2.full.min.js') }}"></script>
    <script>
        $(function () {
            //Initialize Select2 Elements
            $('.select2').select2()
        })
    </script>
@endsection