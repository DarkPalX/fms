@extends('layouts.app')

@section('pagecss')
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
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
            <div class="card card-info card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-oil-can"></i>
                        Unpost Request Details
                    </h3>
                </div>
                <div class="card-body">
                    <div class="invoice p-3 mb-3">
                        <div class="row invoice-info">
                            <div class="col-sm-6 invoice-col">
                                <address>
                                    Reference Fuel Request No. : 
                                        @if($rs->request_type == 'rq')
                                            {{ $data->rq_no }}
                                        @else
                                            {{ $data->uniq_code }}
                                        @endif
                                    <br>
                                    Requested By : {{ strtoupper($rs->initiator) }}<br>
                                    Reason : {{ $rs->reason }}
                                </address>
                            </div>

                            <div class="col-sm-6 invoice-col">
                                <address>
                                    Status : {{ $rs->status }}<br>
                                    Requested At : {{ $rs->initiated_at }}<br>
                                </address>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <br>
    @if($rs->request_type == 'fuel')
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
                                    Request #<br>{{ $data->uniq_code }}
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
                                        Date Requested : {{ date('Y-m-d h:i A',strtotime($data->created_at)) }}<br>
                                        Requested By : {{ strtoupper($data->added_by) }}<br>
                                        Department : {{ $data->dept }}<br>
                                        Driver : {{ $data->driver }}<br><br>
                                        Purpose : {{ strtoupper($data->purpose) }}
                                    </address>
                                </div>

                                <div class="col-sm-6 invoice-col">
                                    <b>Other Information</b><br><br>
                                    <address>
                                        RQ # : {{ $data->rq }}<br>
                                        RQ Cost Code : {{ \App\RQ::rq_costcode($data->rq) }}<br>
                                        Originator Code : {{ $data->cost_code }}<br>
                                        Issued By : {{ strtoupper($data->processed_by) }}<br>
                                        Issued At : {{ date('Y-m-d h:i A',strtotime($data->processed_at)) }}
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
                                                <th>Full Tank</th>
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
                                <a href="@if($rs->status =='PENDING') {{ route('approval-pending.list') }} @else {{ route('approval.list') }} @endif " class="btn btn-default"><i class="fas fa-backward"></i> Back</a>
                                @if($rs->status == 'PENDING')
                                <button type="button" onclick="disapprove();" class="btn btn-danger float-right">Disapprove</button>
                                <button type="button" onclick="approve();" class="btn btn-primary float-right" style="margin-right: 5px;">Approve</button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="row">
            <div class="col-12">
                <div class="card card-danger card-outline">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-oil-can"></i>
                            RQ Details
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="invoice p-3 mb-3">
                            <div class="row invoice-info">
                                <div class="col-sm-6 invoice-col">
                                    <strong>General Information</strong><br><br>
                                    <address>
                                        RQ No. : {{ $data->rq_no }}<br>
                                        Originating Cost Code: {{ $data->costcode }}<br>
                                        Cutoff : {{ $data->cutoff }} <br>
                                        Fuel Type : {{ $data->fueltype }}<br>
                                        Quantity : {{ $data->liters }} L<br>
                                    </address>
                                </div>

                                <div class="col-sm-6 invoice-col">
                                    <strong>Other Information</strong><br><br>
                                    <address>
                                        Status : VERIFIED<br>
                                        Created By : {{ $rs->initiator }}<br>
                                        Department : {{ $data->dept }}<br>
                                        Created At : {{ date('m-d-Y h:i A',strtotime($data->created_at)) }}<br>
                                        Verified By : {{ $data->verified_by }}<br>
                                        Verified At : {{ date('m-d-Y h:i A',strtotime($data->verified_at)) }}<br>
                                        Attachment : 
                                        @if($data->attachment != 'NULL')
                                            <a href="https://mlappsvr.philsaga.com/dportal/storage/app/public/attachments/{{$data->rq_no}}/{{ $data->attachment }}" class="btn btn-xs btn-secondary" data-toggle="lightbox" data-title="{{ $data->rq_no }}<br><small>Liters: {{$data->liters}}</small><br><small>Cutoff: {{$data->cutoff}}</small><br><small>Costcode: {{$data->costcode}}</small><br><small>Fuel Type: {{$data->fueltype}}</small>">
                                                {{ $data->attachment }}
                                            </a>
                                        @endif
                                    </address>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="row no-print">
                            <div class="col-12">
                                <a href="{{ route('fuel.issuances') }}" class="btn btn-default"><i class="fas fa-backward"></i> Back</a>
                                @if($rs->status == 'PENDING')
                                <button type="button" onclick="disapprove();" class="btn btn-danger float-right">Disapprove</button>
                                <button type="button" onclick="approve();" class="btn btn-primary float-right" style="margin-right: 5px;">Approve</button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</section>

<div class="modal fade" id="modal-approve">
    <div class="modal-dialog">
        <div class="modal-content">
            <form autocomplete="off" action="{{ route('approve-request') }}" method="post">
                @csrf
                <div class="modal-header">
                    <h4 class="modal-title">Approve Confirmation</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>You are about to approve this request. Do you want to continue?</p>
                    <input type="hidden" value="{{$rs->id}}" name="rqid">
                    <input type="hidden" value="APPROVED" name="status">
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Yes, Approve</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-disapprove">
    <div class="modal-dialog">
        <div class="modal-content">
            <form autocomplete="off" action="{{ route('disapprove-request') }}" method="post">
                @csrf
                <div class="modal-header">
                    <h4 class="modal-title">Dispprove Confirmation</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Please state your reason for disapproving this request.</p>
                    <input type="hidden" value="{{$rs->id}}" name="rqid">
                    <input type="hidden" value="DISAPPROVED" name="status">
                    <textarea name="remarks" rows="5" class="form-control"></textarea>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger">Disapprove</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('pagejs')
    <script src="{{ asset('assets/plugins/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ asset('assets/plugins/ekko-lightbox/ekko-lightbox.min.js') }}"></script>

    <script>
        $(function () {
            $(document).on('click', '[data-toggle="lightbox"]', function(event) {
                event.preventDefault();
                $(this).ekkoLightbox({
                    alwaysShowClose: true
                });
            });
        })

        function approve(){
            $('#modal-approve').modal('show');
        }

        function disapprove(){
            $('#modal-disapprove').modal('show');
        }
    </script>
@endsection

