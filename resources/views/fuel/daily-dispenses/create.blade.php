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
                    <li class="breadcrumb-item"><a href="{{ route('daily-fuel-dispenses.index') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Create Daily Fuel Dispense</li>
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
                            Daily Fuel Dispense
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

                            <form method="POST" action="{{ route('daily-fuel-dispenses.store') }}" id="delivery-report-form" role="form">                               
                                @csrf
                                <input type="hidden" name="total_issuance" id="total_issuance">
                                <input type="hidden" name="issuances" id="issuances">

                                <div class="row">
                                        
                                    <div class="form-group col-sm-3">

                                        <label for="tank"> Tank </label>                                
                                        <select name="tank_id" class="form-control" id="tank">
                                            <option value="">Select Tank</option>
                                            @foreach( $tanks as $tank)
                                                <option value="{{ $tank->id}}">{{ $tank->name }}</option>
                                            @endforeach
                                        </select>

                                    </div>

                                    <div class="form-group col-sm-3">

                                        <label for="date created"> Date Created </label>                                
                                        <input type="text" class="form-control" id="timepicker" name="date_created"  />

                                    </div>

                                    <div class="col-sm-6"></div>

                                    <div class="clearfix"></div>

                                </div>

                                <div class="row">
                                        
                                    <div class="form-group col-sm-6">

                                        <label for="discription"> Product Description </label>                                
                                        <textarea name="description" readonly id="description" rows="5" class="form-control"></textarea>

                                    </div>

                                    <div class="col-sm-6"></div>

                                    <div class="clearfix"></div>

                                </div>

                                <div class="row">
                                        
                                    <div class="form-group col-sm-6">

                                        <label for="beginning dipstick measurement"> Beginning Dipstick Measurement </label>                                
                                        <input type="text" readonly class="form-control" id="beginning_dipstick_measurement" name="beginning_dipstick_measurement">

                                    </div>

                                    <div class="col-sm-6"></div>

                                    <div class="clearfix"></div>

                                </div>

                                <div class="row">
                                        
                                    <div class="form-group col-sm-6">

                                        <label for="beginning dipstick measurement"> Beginning Totalizer Reading (Mechanical) </label>                                
                                        <input type="text" readonly class="form-control" id="beginning_totalizer_m_reading" name="beginning_totalizer_m_reading">

                                    </div>

                                    <div class="col-sm-6"></div>

                                    <div class="clearfix"></div>

                                </div>

                                <div class="row">
                                        
                                    <div class="form-group col-sm-6">

                                        <label for="beginning totalizer reading"> Beginning Totalizer Reading (Digital)</label>                                
                                        <input type="text" readonly class="form-control" id="beginning_totalizer_d_reading" name="beginning_totalizer_d_reading">

                                    </div>

                                    <div class="col-sm-6"></div>

                                    <div class="clearfix"></div>

                                </div>

                                <table class="table table-bordered" id="delivery-table">

                                    <thead>

                                        <tr>
                                            <td class="text-center">No.</td>
                                            <td class="text-center">Fuel Slip/Rq No.</td>
                                            <td class="text-center">Plate No.</td>
                                            <td class="text-center">Actual Dispense</td>
                                        </tr>

                                    </thead>

                                    <tbody>

                                    </tbody>


                                </table>

                                <div class="row">
                                        
                                    <div class="form-group col-sm-6">

                                        <label for="fuel delivery"> Fuel Delivery </label>                                
                                        <input type="text" readonly class="form-control" id="fuel_delivery" name="fuel_delivery">

                                    </div>

                                    <div class="col-sm-6"></div>

                                    <div class="clearfix"></div>

                                </div>

                                <div class="row">
                                        
                                    <div class="form-group col-sm-6">

                                        <label for="ending dipstick measurement"> Ending Dipstick Measurement </label>                                
                                        <input type="text" readonly class="form-control" id="ending_dipstick_measurement" name="ending_dipstick_measurement">

                                    </div>

                                    <div class="col-sm-6"></div>

                                    <div class="clearfix"></div>

                                </div>

                                <div class="row">
                                        
                                    <div class="form-group col-sm-6">

                                        <label for="ending dipstick measurement"> Ending Totalizer Reading (Mechanical) </label>                                
                                        <input type="text" readonly class="form-control" id="ending_totalizer_m_reading" name="ending_totalizer_m_reading">

                                    </div>

                                    <div class="col-sm-6"></div>

                                    <div class="clearfix"></div>

                                </div>

                                <div class="row">
                                        
                                    <div class="form-group col-sm-6">

                                        <label for="ending totalizer reading"> Ending Totalizer Reading (Digital)</label>                                
                                        <input type="text" readonly class="form-control" id="ending_totalizer_d_reading" name="ending_totalizer_d_reading">

                                    </div>

                                    <div class="col-sm-6"></div>

                                    <div class="clearfix"></div>

                                </div>

                                <div class="row">

                                    <div class="col-sm-6 mb-2">

                                        <label for="remarks"> Remarks </label>
                                        <textarea name="remarks" id="remarks" rows="5" class="form-control"></textarea>

                                    </div>

                                    <div class="col-sm-6"></div>

                                    <div class="clearfix"></div>

                                </div>

                                <div class="row">
                                        
                                    <div class="form-group col-sm-6">

                                        <label for="prepared by"> Prepared By </label>                                
                                        <input type="text" class="form-control" name="prepared_by">

                                    </div>

                                    <div class="col-sm-6"></div>

                                    <div class="clearfix"></div>

                                </div>

                                <div class="row">
                                        
                                    <div class="form-group col-sm-6">

                                        <label for="received by"> Received By </label>                                
                                        <input type="text" class="form-control" name="received_by">

                                    </div>

                                    <div class="col-sm-6"></div>

                                    <div class="clearfix"></div>

                                </div>

                                <div class="row">
                                        
                                    <div class="form-group col-sm-6">

                                        <label for="encoded by"> Encoded By </label>                                
                                        <input type="text" class="form-control" name="encoded_by">

                                    </div>

                                    <div class="col-sm-6"></div>

                                    <div class="clearfix"></div>

                                </div>

                                <div class="col-12 mb-5">
                                    <button class="btn btn-primary btn-md" id="submit-delivery-report"> Submit </button>
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
    
    <script src="{{ asset('assets/plugins/daterangepicker/daterangepicker.js') }}"></script>
    <script>

        $(function() {
            $('#timepicker').daterangepicker({
                timePicker: true,
                startDate: moment().startOf('hour') ,
                locale: {
                    format: 'Y-MM-DD hh:mm A'
                }, 
                singleDatePicker: true
            });
        });    
        
        $(document).on('change', '#tank', function() {

            $('#description').val('');
            $('#beginning_dipstick_measurement').val('');
            $('#ending_dipstick_measurement').val('');
            $('#delivery-table tbody').empty();
            $('#delivery-table tfoot').remove();
            $('#total_issuance').val('');
            $('#issuances').val('');

            let tank = $(this).val();
            let _url = '/get-related-detail-of-tank?tank='+tank 
            $.get(_url ,function(data){
                console.log(data);
                if(data.hasOwnProperty('error')) {
                    alert('Dipstick Measurement for the day is incomplete please add dipstick first');
                    return false;
                } else {

                    $('#description').val(data.tank.description);
                    $('#beginning_dipstick_measurement').val(data.morning_d_avg);
                    $('#ending_dipstick_measurement').val(data.afternoon_d_avg);

                    html = '';
                    total_issuance = 0;
                    issuances = [];

                    $.each(data.issuances, function(i, o) {
                        html += '<tr>';
                        html += '<td>' + o.id + '</td>';
                        html += '<td>' + o.uniq_code + ' / ' + o.rq + '</td>';
                        html += '<td>' + o.plate_no + '</td>';
                        html += '<td>' + o.actual_qty + '</td>';
                        html += '</tr>';

                        if(o.actual_qty != null) {
                            total_issuance = total_issuance + parseFloat(o.actual_qty);
                        }
                        issuances.push(o.id);
                    });

                    $('#delivery-table tbody').append(html);

                    html1 = '';
                    html1 += '<tfoot><tr>';
                    html1 += '<td></td><td></td>';
                    html1 += '<td> Total </td>';
                    html1 += '<td>'+ total_issuance +'</td>';

                    $('#delivery-table').append(html1);
                    $('#total_issuance').val(total_issuance);
                    $('#issuances').val(issuances);
                    $('#fuel_delivery').val(parseFloat(data.fuel_delivered));
                    $('#beginning_totalizer_d_reading').val(parseFloat(data.totalizer_d_b));
                    $('#ending_totalizer_d_reading').val(parseFloat(data.totalizer_d_e));
                    $('#beginning_totalizer_m_reading').val(parseFloat(data.totalizer_m_b));
                    $('#ending_totalizer_m_reading').val(parseFloat(data.totalizer_m_e));

                }



            });

        });


    </script>

@endsection