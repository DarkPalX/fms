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
                <li class="breadcrumb-item"><a href="{{ route('fuel.pending-request') }}">Fuel Requests</a></li>
                <li class="breadcrumb-item active">Process</li>
            </ol>
        </div>
    </div>
</div>

<section class="content">
    <div class="row">
        <div class="col-12">
            <form action="{{ route('complete.issuance') }}" method="post">
                @csrf
                <input type="hidden" name="rq" value="{{$data->rq}}">
                <input type="hidden" name="request_id" value="{{$data->id}}">
                <div class="card card-danger card-outline">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-oil-can"></i>
                            Fuel Request Details
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
                                        Originator Cost Code : {{ $data->rq_costcode }}<br>
                                        Prepared By : {{ strtoupper($data->added_by) }}<br>
                                        Department : {{ $data->dept }}<br>
                                        Date Requested : {{ date('Y-m-d h:i A',strtotime($data->created_at)) }}<br>
                                        Requested By : {{ $data->driver }}<br><br>
                                        Purpose : {{ $data->purpose }}
                                    </address>
                                </div>

                                <div class="col-sm-6 invoice-col">
                                    <b>Other Information</b><br><br>
                                    <address>
                                        RQ # : {{ $data->rq }}<br>
                                        Expense Cost Code : {{ $data->cost_code }}<br>
                                        Project Cost Code : {{ $data->project_ccode }}<br>
                                        @if($data->status == 'ISSUED')
                                            Issued By : {{ $data->processed_by }}<br>
                                            Issued At : @if($data->status == 'ISSUED') {{ date('Y-m-d h:i A',strtotime($data->processed_at)) }} @endif
                                        @endif
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
                                                <th>Fuel Station</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>{{ $data->item_code }}</td>
                                                <td class="text-uppercase">{{ $data->fuel_type }}</td>
                                                <td>{{ $data->requested_qty }}</td>
                                                <td>
                                                    <input required type="number" name="actual_qty" id="liter" step="0.01" value="0.00" max="{{$data->requested_qty}}" min="0.01" class="form-control text-right input_highlight" onkeydown="isNumberKey(e);">
                                                </td>
                                                <td>
                                                    <input required type="number" name="odo" id="odo" step="0.01" value="0.00" min="{{ $data->odo ?? 0.01 }}" class="form-control text-right input_highlight" onkeydown="isNumberKey(e);">
                                                </td>
                                                <td>
                                                    <select required name="type" class="form-control input_highlight">
                                                        <option disabled>Choose One</option>
                                                        <option value="Full">Full</option>
                                                        <option value="Partial">Partial</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <select required name="location" class="form-control input_highlight">
                                                        <option disabled>Choose One</option>
                                                        <option value="Mill">Mill</option>
                                                        <option value="Mine">Mine</option>
                                                    </select>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="row no-print">
                            <div class="col-12">
                              <a href="{{ route('fuel.pending-request') }}" class="btn btn-default"><i class="fas fa-backward"></i> Back</a>
                              <button type="button" onclick="unlock_request('{{$data->id}}');" class="btn btn-secondary">Unlock</button>
                              <button class="btn btn-primary float-right" type="submit">Save Changes</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>

<div class="modal fade" id="modal-unlock">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('unlock-fuel-request') }}" method="post">
                @csrf
                <div class="modal-header">
                    <h4 class="modal-title">Unlock Confirmation</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>You are about to unlock this fuel request. Once unlock department user can now cancel the request anytime. Do you want to continue?</p>
                    <input type="hidden" name="rqid" id="rqid">
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn btn-danger">Yes, Unlock</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('pagejs')
    <script src="{{ asset('assets/plugins/select2/js/select2.full.min.js') }}"></script>
    <script>
        function unlock_request(id){
            $('#modal-unlock').modal('show');
            $('.modal-body #rqid').val(id);
        }


        $(document).ready(function () {
            //called when key is pressed in textbox
            $("#liter,#odo").keypress(function (e) {
                //if the letter is not digit then display error and don't type anything
                var charCode = (e.which) ? e.which : event.keyCode
                if (charCode != 43 && charCode > 31 && (charCode < 48 || charCode > 57))
                    return false;
                return true;

            });
        });  
    </script>
@endsection