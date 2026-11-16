@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Reports</a></li>
                    <li class="breadcrumb-item active">Issuances: Vehicle</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="row">
        <div class="col-md-12">
            <form autocomplete="off">
            
             
                        <table width="100%">
                            <tr>
                                <td style="width:30%">Vehicle <i class="text-danger">*</i></td>
                                <td style="width:10%">Status <i class="text-danger">*</i></td>
                                <td style="width:10%">Start Date</td>
                                <td style="width:10%">End Date</td>
                                <td style="width:10%">Start Odo</td>
                                <td style="width:10%">End Odo</td>
                                <td style="width:20%">&nbsp;</td>
                            </tr>
                            <tr>
                                <td>
                                    <select required class="form-control" name="vehicle">
                                        <option value="">Select Vehicle</option>
                                        @foreach($vehicle as $v)

                                        <option @if(($_GET['vehicle'] ?? '') == $v->vehicle_id) selected @endif value="{{ $v->vehicle_id }}">{{ $v->plate_no }}: {{ $v->vehicle }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select required class="form-control" name="status">
                                        <option @if(($_GET['status'] ?? '') == 'ISSUED') selected @endif value="ISSUED">ISSUED</option>
                                        <option @if(($_GET['status'] ?? '') == 'PENDING') selected @endif value="PENDING">PENDING</option>
                                        <option @if(($_GET['status'] ?? '') == 'CANCELLED') selected @endif value="CANCELLED">CANCELLED</option>
                                    </select>
                                </td>
                                <td><input type="date" class="form-control" name="startdate" value="{{ app('request')->input('startdate') }}"></td>
                                <td><input type="date" class="form-control" name="enddate" value="{{ app('request')->input('enddate') }}"></td>
                                <td><input type="number" class="form-control" name="odo_start" value="{{ app('request')->input('odo_start') ?? 0}}"></td>
                                <td><input type="number" class="form-control" name="odo_end" value="{{ app('request')->input('odo_end') ?? $rs->max('odo') }}"></td>
                                <td>
                                    <button type="submit" class="btn btn-primary">Generate</button>
                                    @if(isset($_GET['startdate']) && strlen($_GET['startdate'])>=1)
                                        <a href="{{ route('export.issuance-vehicle-daterange',[ $_GET['vehicle'],$_GET['status'],$_GET['startdate'],$_GET['enddate'] ]) }}" class="btn btn-info float-sm-right">Export to CSV</a>
                                    @elseif(isset($_GET['vehicle']))
                                        <a href="{{ route('export.issuance-vehicle',[$_GET['vehicle'],$_GET['status']]) }}" class="btn btn-info float-sm-right">Export to CSV</a>
                                    @else
                                        <a href="{{ route('export.issuance-vehicle',['default','ISSUED']) }}" class="btn btn-info float-sm-right">Export to CSV</a>
                                    @endif
                                </td>
                            </tr>
                        </table>
                        <br>
                 
             
            </form>
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
                                <th width="10%">Vehicle</th>
                                <th>Originating Department</th>
                                <th>RQ #</th>
                                <th width="8%">Expense Cost Code</th>
                                <th>Requested Qty</th>

                                @if(($_GET['status'] ?? '') == 'ISSUED' || !isset($_GET['status']))
                                <th>Actual Qty</th>
                                @endif
                                <th>Driver</th>

                                @if(($_GET['status'] ?? '') == 'CANCELLED')
                                <th>Cancelled By</th>
                                <th width="18%">Reason</th>
                                @else
                                <th>Processed By</th>
                                @endif

                                @if(($_GET['status'] ?? '') == 'ISSUED' || !isset($_GET['status']))
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
                                        <td>{{ $r->vehicle }}<br><small>Plate No: {{ $r->plate_no }}</small><br><small>ODO: {{number_format($r->odo,2)}}</small></td>
                                        <td>{{ $r->dept }}</td>
                                        <td>{{ $r->rq }}<br><small>{{ $r->rq_costcode }}</small></td>
                                        <td>{{ $r->cost_code }}</td>
                                        <td>{{ $r->requested_qty }}</td>

                                        @if($r->status == 'ISSUED')
                                        <td>{{ $r->actual_qty ?? '-'}}</td>
                                        @endif
                                        <td>{{ $r->driver }}</td>

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
                                    <tr><td colspan="13" class="text-center">NO FUEL REQUESTS/ISSUANCES FOUND</td></tr>
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

@section('pagejs')
<script>
    $('body').addClass('sidebar-collapse');
</script>
@endsection