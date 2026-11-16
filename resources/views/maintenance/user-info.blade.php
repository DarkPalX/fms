@extends('layouts.app')

@section('pagecss')
    <link href="{{ asset('assets/plugins/bootstrap-daterangepicker/daterangepicker.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-4 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="media align-items-center mb-4">
                        <img class="mr-3" src="{{ asset('assets/images/vehicles/Yamaha_Sniper_135MX_L_1.jpg') }}" width="80" height="80" alt="">
                        <div class="media-body">
                            <h3 class="mb-0">{{ ucwords($user->name) }}</h3>
                            <p class="text-muted mb-0">{{ $user->dept }}</p>
                        </div>
                    </div>
                    
                    <div class="row mb-5">
                        <div class="col-12 text-center">
                            <a href="#" class="btn btn-danger"><i class="icon-key"></i> Change Password</a>
                        </div>
                    </div>
                </div>
            </div>  
        </div>
        <div class="col-lg-8 col-xl-9">
            <div class="card">
                <div class="card-body">
                    <form action="#" class="form-profile">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <div class="input-daterange input-group" id="date-range">
                                    <input type="text" class="form-control" name="start"> <span class="input-group-addon b-0 ">&nbsp;</span>
                                    <input type="text" class="form-control" name="end">
                                </div>
                            </div>
                            <div class="form-group col-md-3">
                                <div class="input-group">                            
                                    <button class="btn btn-primary px-3 ml-4" type="submit"><i class="fa fa-filter"></i> Generate Logs</button>
                                </div>
                            </div>
                        </div>
                    </form>

                    <div class="card-title">
                        <h4>Logs</h4>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>2</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('pagejs')
    <script src="{{ asset('assets/plugins/moment/moment.js') }}"></script>
    <script src="{{ asset('assets/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js') }}"></script>
    <script src="{{ asset('assets/plugins/clockpicker/dist/jquery-clockpicker.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/jquery-asColorPicker-master/libs/jquery-asColor.js') }}"></script>
    <script src="{{ asset('assets/plugins/jquery-asColorPicker-master/libs/jquery-asGradient.js') }}"></script>
    <script src="{{ asset('assets/plugins/jquery-asColorPicker-master/dist/jquery-asColorPicker.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ asset('assets/js/plugins-init/form-pickers-init.js') }}"></script>
@endsection