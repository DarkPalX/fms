@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Reports</a></li>
                    <li class="breadcrumb-item active">Monthly Issuances: Vehicle</li>
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
                                <td style="width:30%">Vehicle Type <i class="text-danger">*</i></td>
                                <td style="width:10%">Month <i class="text-danger">*</i></td>
                                <td style="width:10%">Year <i class="text-danger">*</i></td>
                                <td style="width:10%">Status <i class="text-danger">*</i></td>
                                <td style="width:25%">&nbsp;</td>
                                <td style="width:15%">&nbsp;</td>
                            </tr>
                            <tr>
                                <td>
                                    <select class="form-control" name="v_type" required="required">
                                        <option value="">- Select -</option>
                                        <option value="Light Vehicle" {{ request()->v_type == 'Light Vehicle' ? 'selected' : ''}}>Light Vehicle</option>
                                            <option value="Medium Vehicle" {{ request()->v_type == 'Medium Vehicle' ? 'selected' : ''}}>Medium Vehicle</option>
                                            <option value="Heavy Equipment" {{ request()->v_type == 'Heavy Equipment' ? 'selected' : ''}}>Heavy Equipment</option>
                                            <option value="Motorcycle" {{ request()->v_type == 'Motorcycle' ? 'selected' : ''}}>Motorcycle</option>
                                    </select>
                                </td>
                                
                                <td>
                                    <select required class="form-control" name="month">
                                        @foreach( $months as $key => $month)
                                            @if(isset($_GET['month']) && $_GET['month'] == $key)
                                                <option value="{{ $key }}" selected> {{ $month }} </option>
                                            @else
                                                <option value="{{ $key }}"> {{ $month }} </option>
                                            @endif
                                        @endforeach
                                    </select>
                                </td>

                                <td>
                                    <select required class="form-control" name="year">
                                        @for( $i = date('Y'); $i > 2010; $i--)
                                            @if(isset($_GET['year']) && $_GET['year'] == $i)
                                                <option value="{{ $i }}" selected> {{ $i }} </option>
                                            @else
                                                <option value="{{ $i }}"> {{ $i }} </option>
                                            @endif
                                        @endfor
                                    </select>
                                </td>

                                <td>
                                    <select required class="form-control" name="status">
                                        <option @if(($_GET['status'] ?? '') == 'ISSUED') selected @endif value="ISSUED">ISSUED</option>
                                        <option @if(($_GET['status'] ?? '') == 'PENDING') selected @endif value="PENDING">PENDING</option>
                                        <option @if(($_GET['status'] ?? '') == 'CANCELLED') selected @endif value="CANCELLED">CANCELLED</option>
                                    </select>
                                </td>

                                <td>
                                    
                                </td>

                                <td>
                                    <button type="submit" class="btn btn-primary">Generate</button>
                                    @if(isset($_GET['v_type']))
                                        <a href='/export/monthly-issuance-vehicle?type={{request()->v_type}}&month={{request()->month}}&year={{request()->year}}&status={{request()->status}}' class="btn btn-info">Export to CSV</a>
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
                                @if(request()->has('status') && request()->status == 'ISSUED')
                                    <th>Actual Qty</th>
                                    <th>Fuel Cost</th>
                                @endif
                                <th>Driver</th>
                                @if(($_GET['status'] ?? '') == 'CANCELLED')
                                    <th>Cancelled By</th>
                                    <th width="18%">Reason</th>
                                @else
                                    <th>Processed By</th>
                                @endif
                                @if(request()->has('status') && request()->status == 'ISSUED')
                                    <th>Issued By</th>
                                @endif      
                                <th>Tank Status</th>
                                <th>Location</th>
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
                                        <td>{{ number_format($r->actual_qty * $r->fuel_cost, 2) }}</td>
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
                                        <td>{{ $r->type }}</td>
                                        <td>{{ $r->location }}</td>
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