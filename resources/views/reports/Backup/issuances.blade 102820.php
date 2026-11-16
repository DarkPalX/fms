@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Reports</a></li>
                    <li class="breadcrumb-item active">Fuel Requests &amp; Issuances</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="row">
        <div class="col-md-12">
            <form autocomplete="off">
                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label>Start Date <i class="text-danger">*</i></label>
                            <input required type="date" class="form-control" name="startdate" value="{{ app('request')->input('startdate') }}">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label>End Date <i class="text-danger">*</i></label>
                            <input required type="date" class="form-control" name="enddate" value="{{ app('request')->input('enddate') }}">
                        </div>
                    </div>
                    <div class="col-6">
                        <button type="submit" class="btn btn-primary" style="margin-top: 32px;">Generate</button>

                        @if(isset($_GET['startdate']) && strlen($_GET['startdate'])>=1)
                            <a href="{{ route('export.issuances-daterange',[$_GET['startdate'],$_GET['enddate']]) }}" class="btn btn-info float-sm-right" style="margin-top: 29px;margin-left: 5px;">Export to CSV</a>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-md-12">
            <h3 class="text-center">FUEL REQUESTS &amp; ISSUANCES</h3>
            <p class="text-center">
                @if(isset($_GET['startdate']) && strlen($_GET['startdate'])>=1)
                    {{ date('m-d-Y',strtotime(app('request')->input('startdate'))) }} to {{ date('m-d-Y',strtotime(app('request')->input('enddate'))) }}
                @else
                    {{ today()->startOfMonth()->format('m-d-Y') }} to {{ today()->endOfMonth()->format('m-d-Y') }}
                @endif
            </p>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th width="10%">Transaction No.</th>
                                <th>Originating Department</th>
                                <th>RQ #</th>
                                <th>Expense Cost Code</th>
                                <th>Requested Qty</th>
                                <th>Actual Qty</th>
                                <th>Vehicle</th>
                                <th>Driver</th>
                                <th>Processed By</th>
                                <th>Status</th>
                                <th>Cancelled By</th>
                                <th width="15%">Reason</th>
                                <th>Issued By</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($rs))
                                @php $row = 1; @endphp
                                @forelse($rs as $r)
                                    <tr style="font-size: 14px;">
                                        <td>{{ $row++ }}</td>
                                        <td>{{ $r->uniq_code }}</td>
                                        <td>{{ $r->dept }}</td>
                                        <td>{{ $r->rq }}<br><small>{{ $r->rq_costcode }}</small></td>
                                        <td>{{ $r->cost_code }}</td>
                                        <td>{{ $r->requested_qty }}</td>
                                        <td>{{ $r->actual_qty ?? '-'}}</td>
                                        <td>{{ $r->vehicle }}<br><small>Plate No: {{ $r->plate_no }}</small></td>
                                        <td>{{ $r->driver }}</td>
                                        <td>{{ $r->added_by }}<br><small>{{ date('Y-m-d h:i A',strtotime($r->created_at)) }}</small></td>
                                        <td>{{ $r->status }}</td>
                                        <td>@if($r->status == 'CANCELLED') {{ $r->initiator }}<br><small>{{ date('Y-m-d h:i A',strtotime($r->initiated_at)) }}</small> @endif</td>
                                        <td>{{ $r->remarks ?? '-' }}</td>
                                        <td>@if($r->status == 'ISSUED'){{ $r->processed_by }}<br><small>{{ date('Y-m-d h:i A',strtotime($r->processed_at)) }}</small>@endif</td> 
                                    </tr>
                                @empty

                                @endforelse
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection