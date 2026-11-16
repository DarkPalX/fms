@extends('layouts.app')

@section('pagecss')
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css' ) }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css' ) }}">
@endsection

@section('content')
<div class="content-header">
    <div class="row mb-2">
        <div class="col-sm-6"></div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item active">Manual Issuance</li>
            </ol>
        </div>
    </div>
</div>

<section class="content">
    <div class="row">
        <div class="col-12">
            <form autocomplete="off" action="{{ route('manual-issuance-store') }}" method="post">
                @csrf
                <div class="card card-danger card-outline">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-gas-pump"></i>
                            Fuel Request Form
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Requested By <i class="text-danger">*</i></label>
                                    <select required name="requested_by" class="form-control select2-requested">
                                        <option value="" selected disabled>Choose One</option>
                                        @foreach($requestor as $r)
                                        <option value="{{$r->fullname}}|{{$r->dept_name}}">{{ $r->fullname }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Date Requested <i class="text-danger">*</i></label>
                                    <input type="date" name="requested_at" class="form-control form-control-lg" max="{{ date('Y-m-d') }}">
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Issued By (Gas Man) <i class="text-danger">*</i></label>
                                    <select required name="processed_by" class="form-control select2-processed">
                                        <option selected disabled>Choose One</option>
                                        @foreach($gasman as $g)
                                        <option value="{{$g->domainAccount}}">{{ $g->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Issued At <i class="text-danger">*</i></label>
                                    <input type="date" name="processed_at" class="form-control form-control-lg" max="{{ date('Y-m-d') }}">
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>RQ # <i class="text-danger">*</i></label>
                                    <select required name="rq" class="form-control select2-rq" id="rqbalance">
                                        <option selected disabled>Select RQ</option>
                                        @foreach($rqs as $rq)
                                        <option @if(\App\RQ::rq_balance($rq->rq_no,$rq->costcode) == 0) disabled @endif value="{{$rq->id}}|{{ \App\RQ::rq_balance($rq->rq_no,$rq->costcode) }}|{{$rq->fueltype}}">{{$rq->rq_no}} : {{ strtoupper($rq->costcode) }} - BAL: {{ number_format(\App\RQ::rq_balance($rq->rq_no,$rq->costcode),2) }} L</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Driver <i class="text-danger">*</i></label>
                                    <select required name="driver" class="form-control select2-driver">
                                        <option selected disabled>Select Driver</option>
                                        @foreach($drivers as $driver)
                                        <option @if($driver->expiry_at < \Carbon\Carbon::today()) disabled @endif value="{{ $driver->driver_id }}|{{ $driver->first_name }} {{ $driver->middle_name }} {{ $driver->last_name }}">{{ $driver->first_name }} {{ $driver->middle_name }} {{ $driver->last_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Vehicle <i class="text-danger">*</i></label>
                                    <select required name="vehicle" id="vhcl" class="form-control select2-vehicle">
                                        <option selected disabled>Select Vehicle</option>
                                        @foreach($vehicles as $vehicle)
                                        <option value="{{ $vehicle->id }}|{{ $vehicle->plateno }}|{{ $vehicle->name }}|{{ $vehicle->vehicle_code }}">{{ $vehicle->name }} {{ $vehicle->plateno }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Job/Vehicle code <i class="text-danger">*</i></label>
                                    <input required type="text" class="form-control form-control-lg" name="jcode" id="jcode">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Phase code <i class="text-danger">*</i></label>
                                    <select required name="pcode" class="form-control select2-pcode" id="phaseCode">
                                        <option selected disabled>Select Phase Code</option>
                                        @foreach($phasecodes as $code)
                                        <option value="{{ $code->phase_code }}">{{ $code->phase_code }} : {{ $code->phase_desc }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Fuel code</label>
                                    <input readonly type="text" class="form-control form-control-lg" name="fcode" value="158">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Project Cost Code (Optional)</label>
                                    <input type="search" class="form-control form-control-lg" id="projectCode" name="pcostcode">
                                    <div id="projectcode_list"></div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Requested Qty <i class="text-danger">*</i></label>
                                    <input required type="number" step="0.01" value="0.00" class="form-control form-control-lg text-right" id="rqty" name="qty" min="1">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Fuel Type <i class="text-danger">*</i></label>
                                    <select required class="form-control select2-fuel" name="fuel_typ" id="fuel_type">
                                        <option selected disabled>Select Fuel</option>
                                        @foreach($fuels as $fuel)
                                        <option value="{{ $fuel->stock_code }}|{{ $fuel->fuel_type }}|{{ $fuel->cost }}">{{ strtoupper($fuel->fuel_type) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Actual Qty <i class="text-danger">*</i></label>
                                    <input required type="number" name="actual_qty" id="aqty" step="0.01" value="0.00" min="0.01" class="form-control form-control-lg text-right">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Odometer <i class="text-danger">*</i></label>
                                    <input required type="number" step="0.01" value="0.00" class="form-control form-control-lg text-right" id="odo" name="odo" min="1">
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label>Tank Status <i class="text-danger">*</i></label>
                                    <select required name="type" class="form-control select2-tank">
                                        <option selected disabled>Choose One</option>
                                        <option value="Full">Full</option>
                                        <option value="Partial">Partial</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label>Fuel Station <i class="text-danger">*</i></label>
                                    <select required name="location" class="form-control select2-station">
                                        <option selected disabled>Choose One</option>
                                        <option value="Mill">Mill</option>
                                        <option value="Mine">Mine</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>Purpose <i class="text-danger">*</i></label>
                                    <textarea required name="purpose" class="form-control form-control-lg" cols="30"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary float-right">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection

@section('pagejs')
    <script src="{{ asset('assets/plugins/select2/js/select2.full.min.js') }}"></script>
    <script>        
        $(document).ready(function () {
            $("#rqty,#aqty,#odo").keypress(function (e) {
                var charCode = (e.which) ? e.which : event.keyCode
                if (charCode != 43 && charCode > 31 && (charCode < 48 || charCode > 57))
                    return false;
                return true;

            });
        });  

        $('#projectCode').keydown(function(){
            var v         = $('#projectCode').val();
            var _token    = $('input[name="_token"]').val();
            var phasecode = $('#phaseCode').val();
            
            if(v.length > 3){
                $.ajax({
                    url: "{{ route('projectcode.fetch') }}",
                    method: "POST",
                    data: { query: v, phcode : phasecode, _token:_token },
                    success: function(data)
                    {
                        $('#projectcode_list').fadeIn();
                        $('#projectcode_list').html(data);
                    }
                })
            }

        });

        $('#phaseCode').change(function(){
            var x = $('#phaseCode').val();

            if(x == 'JIPF' || x == 'AEPEP' || x == 'EXPLO'){
                $('#projectCode').prop('readonly', false);
            } else {
                $('#projectCode').prop('readonly', true);
            }
        });

        $(document).on('click','.projectcode_li',function(){
            var c = $(this).text();
            var x = c.split(' : ');

            $('#projectCode').val(x[0]);
            $('#projectcode_list').fadeOut();
        });
    </script>

    <script>
        $(function () {
            $('.select2-rq').select2();
            $('.select2-fuel').select2();
            $('.select2-driver').select2();
            $('.select2-vehicle').select2();
            $('.select2-pcode').select2();
            $('.select2-tank').select2();
            $('.select2-station').select2();
            $('.select2-processed').select2();
            $('.select2-requested').select2();
        });

        $('#vhcl').change(function(){
            var vhcl = $(this).val();
            var x = vhcl.split('|');

            $('#jcode').val(x[3]);
        });

        $('#rqty').change(function(){
            var x = $(this).val();

            $("#aqty").attr({ "max" : x });
        });

        $('#rqbalance').change(function(){
            var rq = $(this).val();
            var x = rq.split('|');

            $("#qty").attr({ "max" : x[1] });
            $('#ftype').val(x[2]);
        });
    </script>
@endsection