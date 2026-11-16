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
                    <li class="breadcrumb-item active">Pre Post Operations</li>
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
                            Pre Post Operation Form
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

                            <form method="POST" action="{{ route('pre-post-operations.store') }}" id="delivery-report-form" role="form">
                                @csrf
                                <input type="hidden" name="delivery_dipsticks" id="delivery-dipsticks"  value="{{ $d_ids }}"/>

                                <div class="row">
                                        
                                    <div class="form-group col-sm-3">

                                        <label for="date created"> Date And Time Created </label>                                
                                        <input type="text" class="form-control" id="timepicker" name="date_created"  />

                                    </div>

                                    <div class="col-sm-9"></div>

                                    <div class="clearfix"></div>

                                </div>

                                <table class="table table-bordered" id="delivery-table">

                                    <tr>
                                        <td>Tank Name</td>
                                        <td colspan="4" class="text-center">Beginning Inventory</td>
                                        <td colspan="4" class="text-center">Ending Inventory</td>
                                    </tr>

                                    <tr>
                                        <td></td>
                                        <td colspan="2" class="text-center">Disp. Totalizer Reading</td>
                                        <td colspan="2" class="text-center">Dipstick Measurement</td>
                                        <td colspan="2" class="text-center">Disp. Totalizer Reading</td>
                                        <td colspan="2" class="text-center">Dipstick Measurement</td>
                                    </tr>

                                    <tr>
                                        <td></td>
                                        <td class="text-center">Digital</td>
                                        <td class="text-center">Mechanical</td>
                                        <td class="text-center">(cm)</td>
                                        <td class="text-center">equiv in liter</td>
                                        <td class="text-center">Digital</td>
                                        <td class="text-center">Mechanical</td>
                                        <td class="text-center">(cm)</td>
                                        <td class="text-center">equiv in liter</td>
                                    </tr>

                                    @forelse($data as $key => $measurement)
                                        <tr>
                                            <td class="text-center">{{ $key }}</td>
                                            <td><input type="text" class="form-control" name="{{ strtolower($key) }}_totalizer_digital_begin" readonly value="{{ number_format($measurement['am_td'], 2) }}"></td>
                                            <td><input type="text" class="form-control" name="{{ strtolower($key) }}_totalizer_mechanical_begin" readonly
                                                value="{{ number_format($measurement['am_tm'], 2) }}"></td>
                                            <td class="text-center">{{ number_format($measurement['am'], 2) }}</td>
                                            <td class="text-center">{{ number_format($measurement['am_l'], 2) }}</td>
                                            <td><input type="text" class="form-control" name="{{ strtolower($key) }}_totalizer_digital_end"></td>
                                            <td><input type="text" class="form-control" name="{{ strtolower($key) }}_totalizer_mechanical_end"></td>
                                            <td class="text-center">{{ number_format($measurement['pm'], 2) }}</td>
                                            <td class="text-center">{{ number_format($measurement['pm_l'], 2) }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td class="text-center" colspan="9"> No Dipsticking Found </td>
                                        </tr>
                                    @endforelse
                                    
                                </table>

                                <div class="row">

                                    <div class="col-sm-12 mb-2">

                                        <label for="remarks"> Remarks </label>
                                        <textarea name="remarks" rows="5" class="form-control"></textarea>

                                    </div>

                                    <div class="col-sm-3 mb-2">

                                        <label for="conducted by"> Conducted By </label>
                                        <input type="text" class="form-control" name="conducted_by"  />

                                    </div>

                                    <div class="col-1"></div>

                                    <div class="col-sm-3">

                                        <label for="assited by"> Assited By </label>
                                        <input type="text" class="form-control" name="assisted_by"  />

                                    </div>

                                    <div class="col-1"></div>

                                    <div class="col-sm-3">

                                        <label for="witnessed by"> Witnessed By </label>
                                        <input type="text" class="form-control" name="witnessed_by"  />

                                    </div>

                                    <div class="col-12"></div>

                                    <div class="col-sm-3">

                                        <label for="received by"> Received By </label>
                                        <input type="text" class="form-control" name="received_by"  />

                                    </div>

                                </div>

                                <div class="col-12 mb-5">
                                    <button class="btn btn-primary btn-md float-right" type="submit"> Submit </button>
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
                startDate: moment().startOf('hour'),
                endDate: moment().startOf('hour').add(32, 'hour'),
                locale: {
                    format: 'Y-MM-DD hh:mm A'
                }, 
                singleDatePicker: true
            });
        });
    </script>

@endsection