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
                    <li class="breadcrumb-item active">Fuel Wetstocks</li>
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
                            Fuel Wetstock Details
                        </h3>
                        <br> <br>
                        <a href="{{ route('daily-wetstock.create') }}" class="btn btn-primary btn-sm float-right">New Wetstock Report</a>

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
                                    <th> Tank Name </th>
                                    <th> Day </th>
                                    <th> Opening Stock </th>
                                    <th> Actual Delivered </th>
                                    <th> Issuance </th>
                                    <th> Remaining Stock </th>
                                    <th> Ending Stock </th>
                                    <th> Daily Variance </th>
                                    <th> Cumulative Variance </th>
                                    <th> Cumulative Issuance </th>
                                    <th> Cumulative % Variance </th>
                                </thead>

                                <tbody>
                                    
                                    @forelse($wetstocks as $wetstock)

                                        @if(!$wetstock->tank) continue; @endif
                                        <tr>
                                            <td>{{ $wetstock->tank->name }}</td>
                                            <td>{{ \Carbon\Carbon::parse($wetstock->day)->format('l') }}</td>
                                            <td>{{ $wetstock->opening_stock }}</td>
                                            <td>{{ $wetstock->actual_delivered }}</td>
                                            <td>{{ $wetstock->issuance }}</td>
                                            <td>{{ $wetstock->remaining_stock }}</td>
                                            <td>{{ $wetstock->ending_stock }}</td>
                                            <td>{{ $wetstock->daily_variance }}</td>
                                            <td>{{ $wetstock->cumulative_variance }}</td>
                                            <td>{{ $wetstock->cumulative_issuance }}</td>
                                            <td>{{ $wetstock->cumulative_issuance_percentage }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="11" class="text-center">No Wetstock Found</td>
                                        </tr>
                                    @endforelse

                                </tbody>

                            </table>

                        </div>

                        <div class="card-footer clearfix">
                            Showing {{ $wetstocks->firstItem() }} to {{ $wetstocks->lastItem() }} of {{ $wetstocks->total() }} items
                            <ul class="pagination pagination-sm m-0 float-right">
                                {{ $wetstocks->links() }}
                            </ul>
                        </div>

                    </div>            

                </div>

            </div>
        </div>
    </section>

@endsection

@section('pagejs')

    <script>
        
        $(document).on('click', '#tanks', function() {

            

        });

    </script>

@endsection