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
            <form action="{{ route('update.issued-request') }}" method="post">
                @csrf
                <input type="hidden" name="request_id" value="{{$data->id}}">
                <div class="card card-danger card-outline">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-oil-can"></i>
                            Fuel Request Details
                        </h3>
                        <a href="" class="btn btn-sm btn-primary float-right" data-toggle="modal" data-target="#modal-upload"><i class="fa fa-upload"></i> Upload Attachment</a>
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
                                        Requested By : {{ $data->driver }} {{ $data->alternate_driver  ? '/'. $data->alternate_driver  : ''}}<br><br>
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
                                        @endif<br><br>
                                        Status : {{ $data->status }}
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
                                                <th>Previous Odo</th>
                                                <th>Odometer</th>
                                                <th>Tank Status</th>
                                                <th>Fuel Station</th>
                                                @if((auth()->user()->dept == 'AUDIT & SYSTEMS IMPROVEMENT DEPARTMENT' || auth()->user()->domainAccount=='easia') || auth()->user()->role == 'admin')
                                                    <th>Request Status</th>
                                                @endif
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>{{ $data->item_code }}</td>
                                                <td class="text-uppercase">{{ $data->fuel_type }}</td>
                                                <td>
                                                    {{--@if( auth()->user()->dept == 'AUDIT & SYSTEMS IMPROVEMENT DEPARTMENT' )
                                                        <input required type="number" name="requested_qty" id="req_liter" step="0.01" value="{{ $data->requested_qty }}" class="form-control text-right input_highlight" onkeydown="isNumberKey(e);">
                                                    @else @endif --}} 
                                                        {{ $data->requested_qty }}

                                                </td>                                                
                                                <td>
                                                    <input required type="number" name="actual_qty" id="liter" step="0.01" value="{{ $data->actual_qty }}" class="form-control text-right input_highlight" onkeydown="isNumberKey(e);">
                                                </td>
                                                <td>
                                                    @php
                                                        $prev_odo = \App\IssuanceRequest::where('vehicle_id', $data->vehicle_id)
                                                            ->where('status', 'ISSUED')
                                                            ->where('uniq_code', '!=', $data->uniq_code)
                                                            ->where('id', '<', $data->id)
                                                            ->latest()->first();
                                                    @endphp
                                                    <input readonly type="number" name="prev_od" value="{{ $prev_odo ? $prev_odo->odo : 0 }}" 
                                                        class="form-control text-right">
                                                </td>
                                                <td>
                                                    <input required type="number" name="odo" id="odo" step="0.01" value="{{ $data->odo }}"class="form-control text-right input_highlight" onkeydown="isNumberKey(e);">
                                                </td>
                                                <td>
                                                    <select required name="type" class="form-control input_highlight">
                                                        <option value="" selected disabled>Choose One</option>
                                                        <option @if($data->type == 'Full') selected @endif value="Full">Full</option>
                                                        <option @if($data->type == 'Partial') selected @endif value="Partial">Partial</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <select required name="location" class="form-control input_highlight">
                                                        <option value="" selected disabled>Choose One</option>
                                                        <option @if($data->location == 'Mill') selected @endif value="Mill">Mill</option>
                                                        <option @if($data->location == 'Mine') selected @endif value="Mine">Mine</option>
                                                        <option @if($data->location == 'Tanker') selected @endif value="Tanker">Tanker</option>
                                                    </select>
                                                </td>
                                                @if((auth()->user()->dept == 'AUDIT & SYSTEMS IMPROVEMENT DEPARTMENT' || auth()->user()->domainAccount=='easia') || auth()->user()->role == 'admin')
                                                    <td>
                                                        <select required name="status" class="form-control input_highlight">
                                                            <option value="" selected disabled>Choose One</option>
                                                            <option @if($data->status == 'ISSUED') selected @endif value="ISSUED">ISSUED</option>
                                                            <option @if($data->status == 'CANCELLED') selected @endif value="CANCELLED">CANCELLED</option>
                                                            <option @if($data->status == 'PENDING') selected @endif value="PENDING">PENDING</option>
                                                        </select>
                                                    </td>
                                                @endif
                                            </tr>

                                            @if( auth()->user()->dept == 'AUDIT & SYSTEMS IMPROVEMENT DEPARTMENT' )
                                            <tr>
                                                
                                                <td colspan="7" class="text-right">
                                                    <input type="checkbox" name="is_defective_odo" id="is_defective_odo">
                                                    <label for="is_defective_odo"> Defective Odo</label>
                                                </td>

                                            </tr>
                                            @endif
                                            
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
                                <button class="btn btn-primary float-right" type="submit">Save Changes</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    
    {{-- FILE UPLOAD MODAL  --}}

    <div class="modal fade" id="modal-upload">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('update.issued-request') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h4 class="modal-title">Upload Attachment</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <input type="hidden" name="request_id" value="{{$data->id}}">
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" id="attachment" name="attachment" class="custom-file-input" id="exampleInputFile" required>
                                    <label id="attachment_label" class="custom-file-label" for="exampleInputFile">Choose file</label>
                                </div>
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-sm btn-primary">Upload</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

</section>
@endsection

@section('pagejs')
    <script src="{{ asset('assets/plugins/select2/js/select2.full.min.js') }}"></script>
    <script>

        $(function () {
            $('.select2-driver').select2();
        });

        $(document).ready(function () {
            //called when key is pressed in textbox
            $("#liter,#odo").keypress(function (e) {
                //if the letter is not digit then display error and don't type anything
                var charCode = (e.which) ? e.which : event.keyCode
                if (charCode != 43 && charCode > 31 && (charCode < 48 || charCode > 57))
                    return false;
                return true;

            });

            
            $('#attachment').change(function() {
                $('#attachment_label').text("1 file selected");
            });

        });  
    </script>
@endsection