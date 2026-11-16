@extends('layouts.app')

@section('pagecss')
    <link href="{{ asset('assets/plugins/bootstrap-daterangepicker/daterangepicker.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/plugins/chosen/css/bootstrap-chosen.css') }}">
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8 col-xl-1"></div>
        <div class="col-lg-8 col-xl-10">
            <div class="card">
                <div class="card-body">
                    <form id="form_vehicle">
                        @csrf
                        <div class="form-row">
                            <div class="col-md-3">
                                <label>Department</label>
                                <select name="dept" class="chosen-select">
                                    <option selected value="">Choose Department</option>
                                    @foreach($dept as $d)
                                    <option value="{{$d->dept_id}}">{{$d->dept}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label>Year Model</label>
                                <div class="input-daterange input-group" id="date-range">
                                    <input type="text" class="form-control" name="start"> <span class="input-group-addon b-0">&nbsp;</span>
                                    <input type="text" class="form-control" name="end">
                                </div>
                            </div>

                            <div class="col-md-2">
                                <label>Category</label>
                                <select name="category" class="form-control input-sm">
                                    <option value="">Choose Category</option>
                                    <option value="motorcycle">Motorcycle</option>
                                    <option value="light">Light Vehicle</option>
                                    <option value="medium">Medium Vehicle</option>
                                    <option value="heavy">Heavy Vehicle</option>
                                </select>
                            </div>
                            
                             <div class="col-md-2">
                                <label>&nbsp;</label>
                                <div class="input-daterange input-group" id="date-range">
                                    <button class="btn btn-primary px-3 ml-4 pull-right" type="submit"><i class="fa fa-filter"></i> Generate</button>
                                </div>
                            </div>

                            <div class="col-md-1">
                                <div class="form-check">
                                <label>&nbsp;</label>
                                    <div class="bootstrap-popover">
                                        <button type="button" class="btn btn-default" data-html="true" data-toggle="popover" title="Filter Combination" data-content="<div>* Department Only</div> <div>* Department + Year Model</div> <div>* Department + Year Model + Category</div> <div>* Year Model Only </div> <div>* Year Model + Category</div> <div>* Category Only</div> <div>* Department + Category"><i class="fa fa-question-circle"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-8 col-xl-1"></div>
    </div>

    <div class="row">
        <div class="col-lg-8 col-xl-1"></div>
        <div class="col-lg-8 col-xl-10">
            <center><img id='loader' style="display: none;" src="{{ asset('assets/images/spinner1.gif') }}"></center>
            <div class="card">
                <div id="data_tbl"> </div>
            </div>
        </div>
        <div class="col-lg-8 col-xl-1"></div>
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

    <script src="{{ asset('assets/plugins/chosen/js/chosen.jquery.js') }}"></script>
    <script src="{{ asset('assets/plugins/chosen/js/chosen-active.js') }}"></script>

    <script type="text/javascript">
        $(document).ready(function(){
            $.ajaxSetup({
              headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              }
            });
        });

        $('#form_vehicle').submit(function(e){
            e.preventDefault();
            $('#loader').show();
            var dept    = $('select[name=dept]').val();
            var start   = $('input[name=start]').val();
            var end     = $('input[name=end]').val();
            var category= $('select[name=category]').val();

            $.ajax({
                type: "GET",
                url: "/ajax/vehicles",
                data: $('#form_vehicle').serialize(),
                success: function( response ) {
                    $('#data_tbl').html(response);    
                    $('#loader').hide();
                }
            });
        });
    </script>
@endsection