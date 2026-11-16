@extends('layouts.app')

@section('pagecss')
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css' ) }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css' ) }}">
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
                    <li class="breadcrumb-item active">Fuel Deliveries</li>
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
                            Delivery Transactions
                        </h3>

                        <a href="{{ route('fuel-deliveries.create') }}" class="btn btn-primary btn-sm float-right">New Delivery Transaction</a>

                    </div>

                    <div class="card-body">

                        <div class="table-responsive">

                            <table class="table table-striped table-bordered">

                                <thead>
                                    <th> Supplier </th>
                                    <th> Delivery Date </th>
                                    <th> Total Quantity Delivered </th>
                                    <th> Dispstick Measurement (cm) </th>
                                    <th> Delivered in ff tanks </th>
                                    <th width="10%"> Action </th>
                                </thead>

                                <tbody>
                                    @forelse($deliveries as $key => $delivery )
                                        <tr>
                                            <td> {{ $delivery->supplier }} </td>
                                            <td> {{ \Carbon\Carbon::parse($delivery->date_created)->format('D, M d, Y h:i A') }} </td>
                                            <td> {{ $delivery->fuel_delivery_details->sum('actual_delivered_in_liters') }} </td>
                                            <td> {{ $delivery->fuel_delivery_details->sum('after_cm') }} </td>
                                            <td> {{ implode(",", $delivery->fuel_delivery_details()->distinct('tank_name')->pluck('tank_name')->toArray()) }} </td>
                                            <td> 
                                                <button class="btn btn-primary" data-toggle="collapse" href="#collapseExample{{$key}}" role="button" aria-expanded="false" aria-controls="collapseExample"> show details </button> 
                                            </td>
                                        </tr>
                                        <tr>                                            
                                            @php
                                                $dipsticks = \App\DipstickMeasurement::where('transaction_ref', $delivery->id)->get()->groupBy('tank_name');
                                            @endphp
                                            <td colspan="6">
                                                <table class="table table-bordered collapse" id="collapseExample{{$key}}">
                                                    <thead>
                                                        <th> Fuel Type </th>
                                                        <th> Delivery Date </th>
                                                        <th> Dipstick No. </th>
                                                        <th> Measurement (cm) </th>
                                                        <th> Measurement (L) </th>
                                                    </thead>
                                                    <tbody>
                                                        @foreach( $dipsticks as $dipstik )
                                                            @foreach( $dipstik as $stick )
                                                            <tr>
                                                                <td> {{ $stick->fuel_type }} </td>
                                                                <td> {{ $stick->date_delivered }} </td>
                                                                <td> {{ $stick->dip_no }} </td>
                                                                <td> {{ $stick->cm }} ( {{ $stick->before_midday == 1 ? 'before' : 'after' }} ) </td>
                                                                <td> {{ number_format($stick->to_liter, 2) }} ( {{ $stick->before_midday == 1 ? 'before' : 'after' }} ) </td>
                                                            </tr>
                                                            @endforeach
                                                        @endforeach 
                                                    </tbody>
                                                </table>

                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center"> No Delivery Found </td>
                                        </tr>
                                    @endforelse 
                                </tbody>

                            </table>

                        </div>

                        <div class="card-footer clearfix">
                            Showing {{ $deliveries->firstItem() }} to {{ $deliveries->lastItem() }} of {{ $deliveries->total() }} items
                            <ul class="pagination pagination-sm m-0 float-right">
                                {{ $deliveries->links() }}
                            </ul>
                        </div>

                    </div>            

                </div>

            </div>
        </div>
    </section>

@endsection

