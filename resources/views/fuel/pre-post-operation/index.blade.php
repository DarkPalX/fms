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
                    <li class="breadcrumb-item"><a href="{{ route('pre-post-operations.index') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Pre Post Operations</li>
                </ol>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="row">
            <div class="col-12">
                
                <div class="card card-danger card-outline">

                    <div class="card-header">
                        
                        <h3 class="card-title">
                            <i class="fas fa-oil-can"></i>
                            Pre Post Operations
                        </h3>
                        <br><br>
                        <a href="{{ route('pre-post-operations.create') }}" class="btn btn-primary btn-sm float-right">New Pre Post Operation</a>

                        <form class="form-inline">

                            <div class="form-group col-lg-2">
                                <label for="tank" class="col-lg-4"> Tank </label>
                                <select name="tank_name" id="tank_name" class="form-control col-lg-8">
                                    <option value="">Select Tank</option>
                                    @foreach( $tanks as $tank)
                                        <option value="{{ $tank->name }}" @if(request()->has('tank_name') && request()->tank_name == $tank->name) selected @endif>{{ $tank->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-lg-2">
                                <label for="date_from" class="col-lg-4">From</label>
                                <input type="date" value="{{ request()->has('date_from') ? request()->date_from : \Carbon\Carbon::yesterday()->format('Y-m-d') }}" name="date_from" class="form-control col-lg-8">
                            </div>

                            <div class="form-group col-lg-2">
                                <label for="date_from" class="col-lg-4">To</label>
                                <input type="date" value="{{ request()->has('date_to') ? request()->date_to : date('Y-m-d') }}" name="date_to" class="form-control col-lg-8">
                            </div>

                            <button class="btn btn-primary"> Go </button>

                        </form>

                    </div>

                    <div class="card-body">

                        <div class="table-responsive">

                            <table class="table table-striped table-bordered">

                                <thead>
                                    <th> Fuel Type </th>
                                    <th> Date Created </th>
                                    <th> Received By </th>
                                    <th> Action </th>
                                </thead>

                                <tbody>                          
                                    @forelse($pre_post_operations as $key=>$operation)

                                        <tr>
                                            <td> 
                                                {{ $operation->dipstick_measurement[0]->fuel_type ?? 'Undefined' }}
                                            </td>
                                            <td> {{ $operation->created_at->toFormattedDateString() }} </td>
                                            <td> {{ $operation->received_by }} </td>
                                            <td> <button class="btn btn-primary" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample"> show details </button>  </td>
                                        </tr>
                                        <tr>
                                            <td colspan="5">
                                            @php

                                                $dipsticks = \App\DipstickMeasurement::where('pre_post_operation_id', $operation->id)->get()->groupBy('tank_name');

                                            @endphp

                                                <table class="table table-bordered collapse" id="collapseExample">
                                                    <thead>
                                                        <th> Tank Name </th>
                                                        <th> Delivery Date </th>
                                                        <th> Dipstick No. </th>
                                                        <th> Measurement (cm) </th>
                                                        <th> Measurement (L) </th>
                                                    </thead>
                                                    <tbody>
                                                        @forelse( $dipsticks as $dipstik )

                                                            @foreach( $dipstik as $stick )
                                                            <tr>
                                                                <td> {{ $stick->tank_name }} </td>
                                                                <td> {{ $stick->date_delivered }} </td>
                                                                <td> {{ $stick->dip_no }} </td>
                                                                <td> {{ $stick->cm }} ( {{ \Carbon\Carbon::parse($stick->date_delivered)->isToday() ? 'after' : 'before' }} ) </td>
                                                                <td> {{ number_format($stick->to_liter, 2) }} ( {{ \Carbon\Carbon::parse($stick->date_delivered)->isToday() ? 'after' : 'before' }} ) </td>
                                                            </tr>
                                                            @endforeach
                                                        @empty

                                                        @endforelse
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td class="text-center" colspan="4"> No Pre Post Operation Found </td>
                                        </tr>
                                    @endforelse

                                </tbody>

                            </table>

                        </div>

                        <div class="card-footer clearfix">
                            Showing {{ $pre_post_operations->firstItem() }} to {{ $pre_post_operations->lastItem() }} of {{ $pre_post_operations->total() }} items
                            <ul class="pagination pagination-sm m-0 float-right">
                                {{ $pre_post_operations->links() }}
                            </ul>
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

        