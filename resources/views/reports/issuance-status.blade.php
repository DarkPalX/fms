@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Reports</a></li>
                    <li class="breadcrumb-item active">Issuance Status</li>
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
                    <div class="col-3">
                        <div class="form-group">
                            <label>Status <i class="text-danger">*</i></label>
                            <select required class="form-control" name="status">
                                <option @if(($_GET['status'] ?? '') == 'PENDING') selected @endif value="PENDING">PENDING</option>
                                <option @if(($_GET['status'] ?? '') == 'ISSUED') selected @endif value="ISSUED">ISSUED</option>
                                <option @if(($_GET['status'] ?? '') == 'CANCELLED') selected @endif value="CANCELLED">CANCELLED</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label>Start Date</label>
                            <input type="date" class="form-control" name="startdate" value="{{ app('request')->input('startdate') }}">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label>End Date</label>
                            <input type="date" class="form-control" name="enddate" value="{{ app('request')->input('enddate') }}">
                        </div>
                    </div>
                    <div class="col-3">
                        <button type="submit" class="btn btn-primary" style="margin-top: 29px;">Generate</button>

                        @if(isset($_GET['startdate']) && strlen($_GET['startdate'])>=1)
                            <a href="{{ route('export.issuance-status-daterange',[ $_GET['status'],$_GET['startdate'],$_GET['enddate'] ]) }}" class="btn btn-info float-sm-right" style="margin-top: 29px;margin-left: 5px;">Export to CSV</a>
                        @elseif(isset($_GET['status']))
                            <a href="{{ route('export.issuance-status',[$_GET['status']]) }}" class="btn btn-info float-sm-right" style="margin-top: 29px;margin-left: 5px;">Export to CSV</a>
                        @else
                            <a href="{{ route('export.issuance-status',['PENDING']) }}" class="btn btn-info float-sm-right" style="margin-top: 29px;margin-left: 5px;">Export to CSV</a>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-md-12">
            <h3 class="text-center">{{ $_GET['status'] ?? 'PENDING' }} ISSUANCES</h3>
            <p class="text-center">
                @if(isset($_GET['startdate']) && strlen($_GET['startdate'])>=1)
                    {{ date('m-d-Y',strtotime(app('request')->input('startdate'))) }} to {{ date('m-d-Y',strtotime(app('request')->input('enddate'))) }}
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
                                <th width="8%">Transaction No.</th>
                                <th>Originating Department</th>
                                <th>RQ Number</th>
                                <th>Expense Cost Code</th>
                                <th>Requested Qty</th>
                                @if(($_GET['status'] ?? '') == 'ISSUED')
                                <th>Actual Qty</th>
                                @endif
                                <th>Vehicle</th>
                                <th>Driver</th>

                                @if(($_GET['status'] ?? '') == 'CANCELLED')
                                <th>Cancelled By</th>
                                <th width="18%">Reason</th>
                                @else
                                <th>Processed By</th>
                                @endif

                                @if(($_GET['status'] ?? '') == 'ISSUED')
                                <th>Issued By</th>
                                @endif
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

                                        @if($r->status == 'ISSUED')
                                        <td>{{ $r->actual_qty ?? '-'}}</td>
                                        @endif

                                        <td>{{ $r->vehicle }}<br><small>Plate No: {{ $r->plate_no }}</small></td>
                                        <td>{{ $r->driver }} {{ $r->alternate_driver  ? '/'. $r->alternate_driver  : ''}}</td>

                                        @if($r->status == 'CANCELLED')
                                        <td>{{ $r->initiator }}<br><small>{{ date('Y-m-d h:i A',strtotime($r->initiated_at)) }}</small></td>
                                        <td>{{ $r->remarks }}</td>
                                        @else
                                        <td>{{ $r->added_by }}<br><small>{{ date('Y-m-d h:i A',strtotime($r->created_at)) }}</small></td>
                                        @endif

                                        @if($r->status == 'ISSUED')
                                        <td>{{ $r->processed_by }}<br><small>{{ date('Y-m-d h:i A',strtotime($r->processed_at)) }}</small></td>
                                        @endif
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