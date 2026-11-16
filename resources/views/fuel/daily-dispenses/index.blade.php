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
                    <li class="breadcrumb-item active">Daily Fuel Dispense</li>
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
                            Daily Fuel Dispense Report
                        </h3>
                        <br><br>
                        <a href="{{ route('daily-fuel-dispenses.create') }}" class="btn btn-primary btn-sm float-right">New Fuel Dispense</a>

                        <form class="form-inline">

                            <div class="form-group col-lg-2">
                                <label for="tank" class="col-lg-4"> Tank </label>
                                <select name="tank_name" id="tank_name" class="form-control col-lg-8">
                                    <option value="">Select Tank</option>
                                    @foreach( $tanks as $tank)
                                        <option value="{{ $tank->id }}" @if(request()->has('tank_name') && request()->tank_name == $tank->id) selected @endif>{{ $tank->name }}</option>
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
                                    <th> Tank </th>
                                    <th> Date Created </th>
                                    <th> Opening Stock </th>
                                    <th> Opening Totalizer </th>
                                    <th> Ending Stock </th>
                                    <th> Ending Totalizer </th>
                                    <th> Total Dispense </th>                                    
                                </thead>

                                <tbody>
                                    
                                    @forelse( $daily_dispences as $dispense )
                                        <tr>
                                            <td> {{ $dispense->tank->name }} </td>
                                            <td> {{ \Carbon\Carbon::parse($dispense->date_created)->toFormattedDateString() }} </td>
                                            <td> {{ $dispense->beginning_dipstick_measurement }} </td>
                                            <td> {{ $dispense->beginning_totalizer_reading }} </td>
                                            <td> {{ $dispense->ending_dipstick_measurement }} </td>
                                            <td> {{ $dispense->ending_totalizer_reading }} </td>
                                            <td> {{ $dispense->total_dispensed }} </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td class="text-center" colspan="7"> No Daily Dispense Found </td>
                                        </tr>
                                    @endforelse

                                </tbody>

                            </table>

                        </div>

                        <div class="card-footer clearfix">
                            Showing {{ $daily_dispences->firstItem() }} to {{ $daily_dispences->lastItem() }} of {{ $daily_dispences->total() }} items
                            <ul class="pagination pagination-sm m-0 float-right">
                                {{ $daily_dispences->links() }}
                            </ul>
                        </div>

                    </div>            

                </div>

            </div>
        </div>
    </section>

@endsection

