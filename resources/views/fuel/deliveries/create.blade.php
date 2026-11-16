@extends('layouts.app')

@section('pagecss')
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css' ) }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css' ) }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/daterangepicker/daterangepicker.css' ) }}">

    <style>
        input.transparent-input{
           background-color:transparent !important;
           border:none !important;
        }

        textarea.transparent-input{
           background-color:transparent !important;
           border:none !important;
        }

        .input_highlight{
            border:2px solid red;
        }

        .select2-container--default .select2-selection--single {
            border:2px solid red;
        }
    </style>
@endsection

@section('content')

    <div class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6"></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('fuel-deliveries.index') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Create Fuel Delivery</li>
                </ol>
            </div>
        </div>
    </div>

    <section class="content">
        
        <div class="row">
            
            <div class="col-12">

                <div class="card card-outline">

                    <div class="card-header">
                        
                        <h3 class="card-title text-center">
                            Fuel Delivery Form
                        </h3>

                    </div>

                    <div class="card-body">

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                        <div class="table-responsive">

                            <form method="POST" action="{{ route('delivery-report') }}" id="delivery-report-form" role="form">
                                @csrf
                                <input type="hidden" name="delivery_dipsticks" id="delivery-dipsticks"  />
                                <input type="hidden" name="delivery_details" id="delivery-details"  />

                                <div class="row">
                                    
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label for="date created" class="col-sm-3 col-form-label"> Date and Time of Arrival </label>                                
                                            <div class="col-sm-6">
                                                <input type="text" class="form-control" id="timepicker" name="date_created" value="{{ old('date_created') }}"/>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="form-group row">
                                            <label for="supplier" class="col-sm-3 col-form-label"> Fuel Product Brand </label>
                                            <div class="col-sm-6">
                                                    <label for="petron">
                                                        <input type="radio" value="petron" id="petron" name="vehicle" @if(old('vehicle') == 'petron' || old('vehicle') == '') checked @endif> Petron
                                                    </label>
                                                    &nbsp;&nbsp;&nbsp;
                                                    <label for="phoenix">
                                                        <input type="radio" value="phoenix" id="phoenix" name="vehicle" @if(old('vechile') == 'phoenix') checked @endif> Phoenix
                                                    </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label for="fuel type" class="col-sm-3 col-form-label"> Supplier </label>
                                            <div class="col-sm-6">
                                                <input type="text" class="form-control" id="supplier" name="supplier" value="{{ old('supplier') }}"/>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="form-group row">
                                            <label for="supplier" class="col-sm-3 col-form-label"> Fuel Product Type </label>
                                            <div class="col-sm-6">
                                                    <label for="diesel"><input type="radio" id="diesel" value="diesel" name="fuel_type" @if(old('fuel_type') == 'diesel' || old('fuel_type') == '') checked @endif> Diesel </label>
                                                    &nbsp;&nbsp;&nbsp;
                                                    <label for="gasoline">
                                                        <input type="radio" value="gasoline" id="gasoline" name="fuel_type" @if(old('fuel_type') == 'gasoline') checked @endif> Gasoline Regular
                                                    </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label for="fuel type" class="col-sm-3 col-form-label"> Plate Number </label>
                                            <div class="col-sm-6">
                                                <input type="text" class="form-control" id="plate_no" name="plate_no" value="{{ old('plate_no') }}"/>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label for="fuel type" class="col-sm-3 col-form-label"> Delivery Receipt </label>
                                            <div class="col-sm-6">
                                                <input type="text" class="form-control" id="receipt_no" name="receipt_no" value="{{ old('receipt_no') }}"/>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label for="fuel type" class="col-sm-3 col-form-label"> Serial No. </label>
                                            <div class="col-sm-6">
                                                <input type="text" class="form-control" id="serial_no" name="serial_no" value="{{ old('serial_no') }}"/>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label for="fuel type" class="col-sm-3 col-form-label"> Sale Invoice No. </label>
                                            <div class="col-sm-6">
                                                <input type="text" class="form-control" id="invoice_no" name="invoice_no" value="{{ old('invoice_no') }}"/>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="clearfix"></div>

                                </div>

                            

                            <div class="col-12">
                                <button type="button" class="btn btn-primary btn-sm float-right" id="addTableColumn"> + Add Row </button>
                            </div>

                            <br><br>

                            <table class="table table-bordered" id="delivery-table">

                                <tr>
                                    <td class="text-center" width="7%">Delivery Tanker</td>
                                    <td class="text-center" width="8%">Tanker Lorry Comp. Capacity (L)</td>
                                    <td class="text-center" colspan="2" width="15%">Serial No.</td>
                                    <td class="text-center" colspan="2" width="15%">Tank Destination</td>
                                    <td class="text-center" colspan="6" width="45%">Dipstick Measurement</td>
                                    <td class="text-center" width="8%">Actual Quantity Delivered</td>                            
                                    <td class="text-center" width="5%"> Action </td>
                                </tr>

                                <tr>
                                    <td class="text-center"></td>
                                    <td class="text-center"></td>
                                    <td class="text-center"></td>
                                    <td class="text-center"></td>
                                    <td class="text-center"></td>
                                    <td class="text-center"></td>
                                    <td class="text-center" colspan="3">Before Unloading</td>
                                    <td class="text-center" colspan="3">After Unloading</td>
                                    <td class="text-center"></td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td class="text-center">Dome Cover</td>
                                    <td class="text-center">Discharge Valve</td>
                                    <td class="text-center" width="10%">Tank Name</td>
                                    <td class="text-center">80% Tank Capacity (L)</td>
                                    <td class="text-center">Time</td>
                                    <td class="text-center">cm</td>
                                    <td class="text-center">Liter Equivalent</td>
                                    <td class="text-center">Time</td>
                                    <td class="text-center">cm</td>
                                    <td class="text-center">Liter Equivalent</td> 
                                    <td class="text-center">+-(L)</td>
                                    <td></td>
                                </tr>

                                <tr id="copy-row" class="delivery-input-row">
                                    <td class="text-center"><input type="text" class="form-control compartment" data-colref="compartment" ></td>
                                    <td><input type="text" class="form-control capacity comp-cap" data-colref="capacity"> </td>
                                    <td><input type="text" class="form-control d_c" data-colref="d_c"></td>
                                    <td width="8%"><input type="text" class="form-control d_v" data-colref="d_v"></td>
                                    <td>
                                        <select id="tank" class="form-control tank-selection tank_name" data-colref="tank_name">
                                            <option value="">Select Tank</option>
                                            @foreach($tanks as $tank)
                                                <option value="{{ $tank->name}}" data-cap="{{ $tank->capacity }}"> {{ $tank->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td width="8%"><input type="text" class="form-control fuel_in_liter fuel-lit-lat" data-colref="fuel_in_liter" readonly></td>
                                    <td width="8%"><input type="text" class="form-control b_t" data-colref="b_d_time"></td>
                                    <td width="8%"><input type="text" class="form-control b_d_cm" data-colref="b_d_cm" ></td>
                                    <td width="8%"><input type="text" class="form-control b_d_l fuel-lit-lat-b" data-colref="b_d_l"  ></td>
                                    <td width="8%"><input type="text" class="form-control a_t" data-colref="a_d_time"></td>
                                    <td width="8%"><input type="text" class="form-control a_d_cm" data-colref="a_d_cm" ></td>
                                    <td width="8%"><input type="text" class="form-control a_d_l fuel-lit-lat-a" data-colref="a_d_l"  ></td>
                                    <td><input type="text" class="form-control d_a_d" data-colref="d_a_d" ></td>
                                    
                                    <td>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="40%" class="d-inline remove-delivery-row" viewBox="0 0 20 20" fill="currentColor" >
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM7 9a1 1 0 000 2h6a1 1 0 100-2H7z" clip-rule="evenodd" />
                                        </svg>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="40%" class="show-delivery-modal" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                                            <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" />
                                        </svg>
                                    </td>
                                </tr>

                                <tfoot>
                                    <tr>
                                        <td><strong>Total</strong></td>
                                        <td id="total_comp"></td>
                                        <td colspan="6"></td>
                                        <td id="total_bl"></td>
                                        <td colspan="2"></td>
                                        <td id="total_al"></td>                                        
                                        <td id="total_l"></td>
                                    </tr>
                                </tfoot>

                            </table>

                                <div class="row">

                                    <div class="col-sm-12 mb-2">

                                        <label for="remarks"> Remarks </label>
                                        <textarea name="remarks" id="remarks" rows="5" class="form-control">{{ old('remarks') }}</textarea>

                                    </div>

                                    <div class="col-sm-3 mb-2">

                                        <label for="delivered by"> Delivered By </label>
                                        <input type="text" class="form-control" id="delivered_by" name="delivered_by" value="{{ old('delivered_by') }}" />

                                    </div>

                                    <div class="col-1"></div>

                                    <div class="col-sm-3">

                                        <label for="witnessed by"> Witnessed By </label>
                                        <input type="text" class="form-control" id="witnessed_by" name="witnessed_by" value="{{ old('witnessed_by') }}" />

                                    </div>

                                    <div class="col-1"></div>

                                    <div class="col-sm-3">

                                        <label for="s/g assigned"> S/G Assigned </label>
                                        <input type="text" class="form-control" id="sg_assigned" name="sg_assigned" value="{{ old('sg_assigned') }}" />

                                    </div>

                                    <div class="col-12"></div>

                                    <div class="col-sm-3">

                                        <label for="attested by"> Attested By </label>
                                        <input type="text" class="form-control" id="attested_by" name="attested_by" value="{{ old('attested_by') }}" />

                                    </div>

                                    <div class="col-1"></div>

                                    <div class="col-sm-3">

                                        <label for="noted by"> Noted By </label>
                                        <input type="text" class="form-control" id="noted_by" name="noted_by" value="{{ old('noted_by') }}" />

                                    </div>

                                </div>

                            </form>

                            <div class="col-12 mb-5">
                                <button class="btn btn-primary btn-md float-right" id="submit-delivery-report"> Submit </button>
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>

    @foreach($tanks as $tank)

        <div class="modal fade" id="modal-dipstick-{{str_replace(' ', '_', $tank->name) }}">
            <div class="modal-dialog  modal-lg">
                <div class="modal-content">
                
                    <div class="modal-header">
                        <h4 class="modal-title text-center">Dipsticking Measurement</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    
                    <div class="modal-body">

                        <div class="table-responsive">
                            <table class="table table-bordered" id="modal-dipstick-table-{{str_replace(' ', '_', $tank->name) }}">
                                
                                <tr>
                                    <td></td>
                                    <td colspan="2" align="center">Before Delivery</td>
                                    <td colspan="2" align="center">After Delivery</td>
                                </tr>

                                <tr>
                                    <td></td>
                                    <td colspan="2" align="center">Dipstick Measurement (cm)</td>
                                    <td colspan="2" align="center">Dipstick Measurement (cm)</td>
                                </tr>

                                <tr>
                                    <td align="center">Tank Name</td>
                                    <td align="center">Dip No</td>
                                    <td align="center">CM</td>
                                    <td align="center">Dip No</td>
                                    <td align="center">CM</td>
                                </tr>

                                <tr class="dipstick-input-wrap" data-ref="{{str_replace(' ', '_', $tank->name)}}">
                                    <td>{{ $tank->name }}</td>
                                    <td>1</td>
                                    <td><input type="text" class="form-control dipstick-input input-before-cm-{{str_replace(' ', '-', $tank->name)}}" 
                                        data-refraf="before-cm" data-ref="cm" data-tank="{{ str_replace(' ', '-', $tank->name) }}" data-fer="before"></td>
                                    <td>1</td>
                                    <td><input type="text" class="form-control dipstick-input input-after-cm-{{str_replace(' ', '-', $tank->name)}}" 
                                        data-refraf="after-cm" data-ref="cm" data-tank="{{ str_replace(' ', '-', $tank->name) }}" data-fer="after"></td>
                                </tr>

                                <tr class="dipstick-input-wrap" data-ref="{{str_replace(' ', '-', $tank->name)}}">
                                    <td>{{ $tank->name }}</td>
                                    <td>2</td>
                                    <td><input type="text" class="form-control dipstick-input input-before-cm-{{str_replace(' ', '-', $tank->name)}}" 
                                        data-refraf="before-cm" data-ref="cm" data-tank="{{ str_replace(' ', '-', $tank->name) }}" data-fer="before"></td>
                                    <td>2</td>
                                    <td><input type="text" class="form-control dipstick-input input-after-cm-{{str_replace(' ', '-', $tank->name)}}" 
                                        data-refraf="after-cm" data-ref="cm" data-tank="{{ str_replace(' ', '-', $tank->name) }}" data-fer="after"></td>
                                </tr>

                                <tr class="dipstick-input-wrap" data-ref="{{str_replace(' ', '-', $tank->name)}}">
                                    <td>{{ $tank->name }}</td>
                                    <td>3</td>
                                    <td><input type="text" class="form-control dipstick-input input-before-cm-{{str_replace(' ', '-', $tank->name)}}"
                                        data-refraf="before-cm" data-ref="cm" data-tank="{{ str_replace(' ', '-', $tank->name) }}" data-fer="before"></td>
                                    <td>3</td>
                                    <td><input type="text" class="form-control dipstick-input input-after-cm-{{str_replace(' ', '-', $tank->name)}}" 
                                        data-refraf="after-cm" data-ref="cm" data-tank="{{ str_replace(' ', '-', $tank->name) }}" data-fer="after"></td>
                                </tr>

                                <tr data-ref="{{str_replace(' ', '_', $tank->name)}}">
                                    <td>{{ $tank->name }}</td>
                                    <td>4</td>
                                    <td><input type="text" readonly class="form-control dipstick-input-average" id="input-before-cm-{{str_replace(' ', '-', $tank->name)}}-ave" data-ref="cm" data-tank="{{ str_replace(' ', '-', $tank->name) }}" data-fer="before"></td>
                                     <td>4</td>
                                    <td><input type="text" readonly class="form-control dipstick-input-average" id="input-after-cm-{{str_replace(' ', '-', $tank->name)}}-ave" data-ref="cm" data-tank="{{ str_replace(' ', '-', $tank->name) }}" data-fer="after"></td>
                                </tr>

                            </table>
                        </div>

                        </div>
                        
                        <div class="modal-footer justify-content-between">
                        </div>

                    </form>
                </div>
            </div>
        </div>

    @endforeach

@endsection

@section('pagejs')
    
    <script src="{{ asset('assets/plugins/daterangepicker/daterangepicker.js') }}"></script>

    <script>
        
        var tank_list = [];
        var deliveries = {};
        var tank_dipstick = {};
        var tanks = {!! $tanks !!};

        $(function() {

            $('#timepicker').daterangepicker({
                timePicker: true,
                startDate: moment(),
                locale: {
                    format: 'Y-MM-DD hh:mm A'
                }, 
                singleDatePicker: true
            });
        });

        $(document).on('click', '#addTableColumn', function() {

            var c = $('#copy-row').clone();
            c.find('input').val('');            
            $('#delivery-table').append(c);

        });

        $(document).on('change', '.tank-selection', function() {

            if($(this).val() != '') {

                let _tank_name = $(this).val().replaceAll(' ', '_', $(this).val());

                $('#modal-dipstick-'+_tank_name).modal('show');
                $(this).parent().siblings("td").find(".show-delivery-modal").attr("data-toggle", "modal").attr("data-target", "#modal-dipstick-"+_tank_name);

                tank_checker();

                $(".delivery-input-row[data-tankname='" + _tank_name + "']").find("input[data-colref='fuel_in_liter']").val($(this).find(':selected').data('cap'));

            } else {

                $(this).parent().parent().removeAttr('data-tankName');
                $(this).parent().siblings("td").find(".show-delivery-modal").removeAttr("data-toggle").removeAttr("data-target");
                $(this).parent().siblings("td").find("input[data-colref='b_d_cm']").val('');
                $(this).parent().siblings("td").find("input[data-colref='b_d_l']").val('');
                $(this).parent().siblings("td").find("input[data-colref='a_d_cm']").val('');
                $(this).parent().siblings("td").find("input[data-colref='a_d_l']").val('');
            }

        });

        $(document).on('change', '.dipstick-input', function() {
            
            let _that = $(this);
            let _ref = $(this).data('ref');
            let _fer = $(this).data('fer');
            let _tank = $(this).data('tank')
            let _total = 0;
            let _get_ave = 'input-'+_fer+'-'+_ref+'-'+_tank+'-ave';

            $('.input-'+_fer+'-'+_ref+'-'+_tank).each(function(i, e) {
                if(e.value == '') {
                    _total = parseFloat(_total) + parseFloat(0);
                } else {
                    _total = parseFloat(_total) + parseFloat(e.value);
                }
            });

            _total = _total/3;

            //let _to_liter = _total / 1000;
            let _fuel_type = $('#fuel_type').val();
            let _tank_name = _tank.replaceAll("-", " ", _tank);

            let _url = '/dipstick-conversion/'+_tank_name+'/'+_fuel_type+'/'+_total;

            $('#'+_get_ave).val(_total.toFixed(2));
            if(_ref == 'cm' && _fer == 'before') {
                $(".delivery-input-row[data-tankname='" + _tank.replaceAll("-","_",_tank) + "']").find("input[data-colref='b_d_cm']").val(_total.toFixed(2));
                
                $.get(_url, function(data){
                    console.log(data);
                    $(".delivery-input-row[data-tankname='" + _tank.replaceAll("-","_",_tank) + "']").find("input[data-colref='b_d_l']").val(parseFloat(data).toFixed(2));

                    if(isNaN($(".delivery-input-row[data-tankname='" + _tank.replaceAll("-","_",_tank) + "']").find("input[data-colref='a_d_l']").val()) || 
                    $(".delivery-input-row[data-tankname='" + _tank.replaceAll("-","_",_tank) + "']").find("input[data-colref='a_d_l']").val() == '') {
                        $(".delivery-input-row[data-tankname='" + _tank.replaceAll("-","_",_tank) + "']").find("input[data-colref='d_a_d']").val(0);
                    } else {
                        let _diff = $(".delivery-input-row[data-tankname='" + _tank.replaceAll("-","_",_tank) + "']").find("input[data-colref='a_d_l']").val() - parseFloat(data).toFixed(2);
                        $(".delivery-input-row[data-tankname='" + _tank.replaceAll("-","_",_tank) + "']").find("input[data-colref='d_a_d']").val(_diff.toFixed(2));
                    }
                    $('.fuel-lit-lat-b').keyup();
                });

            } else if(_ref == 'cm' && _fer == 'after') {
                $(".delivery-input-row[data-tankname='" + _tank.replaceAll("-","_",_tank) + "']").find("input[data-colref='a_d_cm']").val(_total.toFixed(2));                
                $.get(_url, function(data){
                    console.log(data);
                    $(".delivery-input-row[data-tankname='" + _tank.replaceAll("-","_",_tank) + "']").find("input[data-colref='a_d_l']").val(parseFloat(data).toFixed(2));
                    
                    if(isNaN($(".delivery-input-row[data-tankname='" + _tank.replaceAll("-","_",_tank) + "']").find("input[data-colref='b_d_l']").val()) || 
                    $(".delivery-input-row[data-tankname='" + _tank.replaceAll("-","_",_tank) + "']").find("input[data-colref='b_d_l']").val() == '') {
                        console.log(data);
                        console.log('nan');
                        $(".delivery-input-row[data-tankname='" + _tank.replaceAll("-","_",_tank) + "']").find("input[data-colref='d_a_d']").val(0);
                    } else {
                        console.log(data);
                        let _diff = parseFloat(data).toFixed(2) - $(".delivery-input-row[data-tankname='" + _tank.replaceAll("-","_",_tank) + "']").find("input[data-colref='b_d_l']").val();
                        $(".delivery-input-row[data-tankname='" + _tank.replaceAll("-","_",_tank) + "']").find("input[data-colref='d_a_d']").val(_diff.toFixed(2));
                    }
                    $('.fuel-lit-lat-a').keyup();
                });
            }
            

        });

        $(document).on('click', '#submit-delivery-report', function() {

            $.each(tanks, function(i, e) {
                let __t = e.name.replaceAll(" ", "_", e.name);
                deliveries[__t] = [];
                tank_dipstick[__t] = [];                          
            });

            // main table
            $('.delivery-input-row').each(function() {
                
                let _data1 = {};  

                let _compartment = $(this).find("td").find("input[data-colref='compartment']").val();
                let _capacity = $(this).find("td").find("input[data-colref='capacity']").val();
                let _tank_name = $(this).find("td").find("select[data-colref='tank_name']").val();
                let _fuel_in_liter = $(this).find("td").find("input[data-colref='fuel_in_liter']").val();
                let _b_d_time = $(this).find("td").find("input[data-colref='b_d_time']").val();
                let _b_d_cm = $(this).find("td").find("input[data-colref='b_d_cm']").val();
                let _b_d_l = $(this).find("td").find("input[data-colref='b_d_l']").val();
                let _a_d_time = $(this).find("td").find("input[data-colref='a_d_time']").val();
                let _a_d_cm = $(this).find("td").find("input[data-colref='a_d_cm']").val();
                let _a_d_l = $(this).find("td").find("input[data-colref='a_d_l']").val();
                let _d_a_d = $(this).find("td").find("input[data-colref='d_a_d']").val();
                let _d_c = $(this).find("td").find("input[data-colref='d_c']").val();
                let _d_v = $(this).find("td").find("input[data-colref='d_v']").val();
                // let _b_t = $(this).find("td").find("input[data-colref='b_t']").val();
                // let _a_t = $(this).find("td").find("input[data-colref='a_t']").val();

                if(_tank_name == '') return false;

                _data1.compartment = _compartment;
                _data1.capacity = _capacity;
                _data1.name = _tank_name;
                _data1.liter = _fuel_in_liter;
                _data1.before_time = _b_d_time;
                _data1.before_cm = _b_d_cm;
                _data1.before_l = _b_d_l;
                _data1.after_time = _a_d_time;
                _data1.after_cm = _a_d_cm;
                _data1.after_l = _a_d_l;
                _data1.actual_delivered = _d_a_d;
                _data1.dome_cover = _d_c;
                _data1.discharge_valve = _d_v;      
                            

                deliveries[_tank_name.replaceAll(" ", "_", _tank_name)].push(_data1);                    

            });

            // dipstick data
            $.each(tank_dipstick, function(key, val){
                
                let _data1 = {}; 

                $('#modal-dipstick-table-'+key).find('.dipstick-input-wrap').each(function(){
                    
                    let b_cm = $(this).find("td").find("input[data-refraf='before-cm']").val();
                    let b_time = $(this).find("td").find("input[data-refraf='before-time']").val();
                    let b_temp = $(this).find("td").find("input[data-refraf='before-temp']").val();
                    let a_cm = $(this).find("td").find("input[data-refraf='after-cm']").val();
                    let a_time = $(this).find("td").find("input[data-refraf='after-time']").val();
                    let a_temp = $(this).find("td").find("input[data-refraf='after-temp']").val();

                    _data1.before_cm = b_cm;
                    _data1.before_time = b_time;
                    _data1.before_temp = b_temp;
                    _data1.after_cm = a_cm;
                    _data1.after_time = a_time;
                    _data1.after_temp = a_temp;

                    tank_dipstick[key].push(_data1);

                });

            })

            let supplier = $("#supplier").val();
            let plate_no = $("#plate_no").val();
            let receipt_no = $("#receipt_no").val();
            let invoice_no = $("#invoice_no").val();
            let serial_no = $("#serial_no").val();
            let remarks = $("#remarks").val();
            let delivered_by = $("#delivered_by").val();
            let witnessed_by = $("#witnessed_by").val();
            let sg_assigned = $("#sg_assigned").val();
            let attested_by = $("#attested_by").val();
            let noted_by = $("#noted_by").val();

            if(supplier.length=="" || plate_no.length=="" || receipt_no.length=="" || invoice_no.length=="" ||
                serial_no.length=="" || remarks.length=="" || delivered_by.length=="" || witnessed_by.length=="" ||
                sg_assigned.length=="" || attested_by.length=="" || noted_by.length=="") {
                alert('All fields are required..');
                return false;
            }


            $('#delivery-dipsticks').val(JSON.stringify(tank_dipstick));
            $('#delivery-details').val(JSON.stringify(deliveries));
            $('#delivery-report-form').submit();

        });

        $(document).on('click', '.remove-delivery-row', function() {

            $(this).parent().parent().remove();

        });        

        var tank_checker = () => {

            tank_list = [];

            $('.tank-selection').each(function() {
                $(this).parent().parent().attr('data-tankName', $(this).val().replaceAll(' ', '_', $(this).val()));
                if(tank_list.indexOf($(this).val()) == -1) {
                    tank_list.push($(this).val());                    
                } else {
                    
                    let _this_tank = $(this).val().replaceAll(' ', '-', $(this).val());
                    let _this_b_ave_cm = parseFloat($('#input-before-cm-'+_this_tank+'-ave').val());
                    let _to_b_liter = _this_b_ave_cm / 1000;
                    let _this_a_ave_cm = parseFloat($('#input-after-cm-'+_this_tank+'-ave').val());
                    let _to_a_liter = _this_a_ave_cm / 1000;
                    $(this).parent().siblings("td").find("input[data-colref='b_d_cm']").val(_this_b_ave_cm.toFixed(2));
                    //$(this).parent().siblings("td").find("input[data-colref='b_d_l']").val(_to_b_liter.toFixed(2));
                    $(this).parent().siblings("td").find("input[data-colref='a_d_cm']").val(_this_a_ave_cm.toFixed(2));
                    //$(this).parent().siblings("td").find("input[data-colref='a_d_l']").val(_to_a_liter.toFixed(2));
                }
            });

        }

        $(document).on('keyup', '.comp-cap', function() {
            let _total = 0;
            $('.comp-cap').each(function() {
                if($(this).val() != '' || parseFloat(!isNaN($(this).val()))) {
                    _total = _total + parseFloat($(this).val());
                } else {
                    _total = _total + 0;
                }
            });
            $('#total_comp').text(_total.toFixed(2));
        });

        $(document).on('keyup', '.fuel-lit-lat', function() {
            let _total = 0;
            
            if(parseFloat($(this).parent().siblings("td").find("input[data-colref='capacity']").val()) < parseFloat($(this).val())) {
                alert('Quantity delivered must be less than or equal to the compartment quantiy');
                $(this).val('');
                return false;
            }

            $('.fuel-lit-lat').each(function() {
                if($(this).val() != '' || parseFloat(!isNaN($(this).val()))) {
                    _total = _total + parseFloat($(this).val());
                } else {
                    _total = _total + 0;
                }
            });


            $('#total_l').text(_total.toFixed(2));
        });

        $(document).on('keyup', '.fuel-lit-lat-b', function() {
            let _total = 0;
            let _total2 = 0;
            $('.fuel-lit-lat-b').each(function() {
                if($(this).val() != '' || parseFloat(!isNaN($(this).val()))) {
                    _total = _total + parseFloat($(this).val());
                } else {
                    _total = _total + 0;
                }
            });
            $('#total_bl').text(_total.toFixed(2));

            if(parseFloat($(this).parent().siblings("td").find("input[data-colref='capacity']").val()) < parseFloat($(this).val())) {
                alert('Quantity delivered must be less than or equal to the compartment quantiy');
                $(this).val('');
                return false;
            }

            $('.fuel-lit-lat').each(function() {
                if($(this).val() != '' || parseFloat(!isNaN($(this).val()))) {
                    _total = _total + parseFloat($(this).val());
                } else {
                    _total = _total + 0;
                }
            });


            $('#total_l').text(_total.toFixed(2));

            $('.d_a_d').each(function() {
                if($(this).val() != '' || parseFloat(!isNaN($(this).val()))) {
                    _total2 = _total2 + parseFloat($(this).val());
                } else {
                    _total2 = _total2 + 0;
                }
            });

            $('#total_l').text(_total2.toFixed(2));

        });

        $(document).on('keyup', '.fuel-lit-lat-a', function() {
  
            let _total = 0;
            let _total2 = 0;
            $('.fuel-lit-lat-a').each(function() {
                if($(this).val() != '' || parseFloat(!isNaN($(this).val()))) {
                    _total = _total + parseFloat($(this).val());
                } else {
                    _total = _total + 0;
                }
            });
            $('#total_al').text(_total.toFixed(2));

            $('.d_a_d').each(function() {
                if($(this).val() != '' || parseFloat(!isNaN($(this).val()))) {
                    _total2 = _total2 + parseFloat($(this).val());
                } else {
                    _total2 = _total2 + 0;
                }
            });

            $('#total_l').text(_total2.toFixed(2));

        });



    </script>

@endsection