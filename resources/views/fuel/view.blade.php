@extends('layouts.app')

@section('pagecss')

@endsection

@section('content')
<div class="content-header">
    <div class="row mb-2">
        <div class="col-sm-6">
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('fuel.issuances') }}">Fuel Issuances</a></li>
                <li class="breadcrumb-item active">Details</li>
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
                        Fuel Issuance Details
                    </h3>
                </div>
                <div class="card-body">
                    <div class="invoice p-3 mb-3">
                        <div class="row col-md-12">
                            <div class="col-md-4">
                                Transaction No.<br>{{ $data->uniq_code }}
                                <hr>
                            </div>
                            <div class="col-md-4">
                                Plate #<br>{{ $data->plate_no }}
                                <hr>
                            </div>
                            <div class="col-md-4">
                                Vehicle<br>{{ $data->vehicle }}
                                <hr>
                            </div>
                        </div>
                        <br>
                        <div class="row invoice-info">
                            <div class="col-sm-6 invoice-col">
                                <b>General Information</b><br><br>
                                <address>
                                    Requested By : {{ strtoupper($data->added_by) }}<br>
                                    Date Requested : {{ date('Y-m-d h:i A',strtotime($data->created_at)) }}<br>
                                    Originating Department : {{ $data->dept }}<br>
                                    Driver : {{ $data->driver }} @if(Auth::user()->id == 60) <a href="#" onclick="change_driver({{$data->driver_id}},'{{$data->driver}}',{{$data->id}});"><i class="fa fa-edit"></i></a> @endif<br><br>
                                    Purpose : {{ strtoupper($data->purpose) }}
                                </address>
                            </div>

                            <div class="col-sm-6 invoice-col">
                                <b>Other Information</b><br><br>
                                <address>
                                    RQ # : {{ $data->rq }}<br>
                                    Originator Cost Code : {{ \App\RQ::rq_costcode($data->rq) }}<br>
                                    Expense Cost Code : {{ $data->cost_code }}<br>
                                    Issued By : {{ strtoupper($data->processed_by) }}<br>
                                    Issued At : {{ date('m-d-Y h:i A',strtotime($data->processed_at)) }}
                                </address>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Item Code</th>
                                            <th>Fuel Type</th>
                                            <th>Requested Qty</th>
                                            <th>Actual Qty</th>
                                            <th>Odometer</th>
                                            <th>Tank Status</th>
                                            <th>Tank Name</th>
                                            <th>Location</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>{{ $data->item_code }}</td>
                                            <td class="text-uppercase">{{ $data->fuel_type }}</td>
                                            <td>{{ $data->requested_qty }}</td>
                                            <td>{{ $data->actual_qty }}</td>
                                            <td>{{ number_format($data->odo,2) }}</td>
                                            <td>{{ $data->type }}</td>
                                            @php
                                                $tank = \App\Tank::where('id', $data->tank_id)->first();
                                            @endphp
                                            <td>{{ $tank ? $tank->name : '' }}</td>
                                            <td>{{ $data->location }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="text-right">
                                <input type="checkbox" name="is_defective_odo" id="is_defective_odo" value="{{ $data->is_defective_odo }}" @if($data->is_defective_odo==0) @else checked @endif onclick="return false;">
                                <label for="is_defective_odo"> Defective Odo</label>
                            </div>  
                    </div>
                </div>
                <div class="card-footer">
                    <div class="row no-print">
                        <div class="col-12">
                            <a href="{{ route('fuel.issuances') }}" class="btn btn-default"><i class="fas fa-backward"></i> Back</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modal-change-driver">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('change_driver') }}" method="post">
                    @csrf
                    <div class="modal-header">
                        <h4 class="modal-title">Change Driver</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>New Driver</p>
                        <select name="new_driver" id="new_driver" class="form-control">

                                <option value="{{$data->driver_id}}|{{$data->driver}}" selected>{{$data->driver}} (Current)</option>
                            @foreach(\App\IssuanceRequest::select('driver','driver_id')->distinct()->orderBy('driver')->get() as $driver)
                                <option value="{{$driver->driver_id}}|{{$driver->driver}}">{{$driver->driver}}</option>
                            @endforeach
                        </select>
                        <br><br>
                        <p>State your reason for changing the driver. <i class="text-danger">*</i></p>
                        <textarea required class="form-control" name="driver_change_reason" id="driver_change_reason" rows="5"></textarea>
                        
                        <input type="hidden" name="issuance_id" id="issuance_id" value="{{$data->id}}">
                   
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn btn-default" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn btn-primary">Change Driver</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
<script>
    function change_driver(i,v,iss){
 
        $('#modal-change-driver').modal('show');
    }
</script>
@endsection
