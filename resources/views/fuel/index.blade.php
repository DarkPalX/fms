@extends('layouts.app')

@section('pagecss')
<!-- Bootstrap Select CSS -->
<link rel="stylesheet" href="{{ asset('assets/css/bootstrap-select.min.css') }}">

<link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
@endsection

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active">Fuel Issuances</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <h3>Fuel Issuances Table</h3>
                <div class="card-header">
                    <form>
                        <input type="hidden" name="search" value="on">
                            <table width="100%">
                                <tr style="font-weight:bold;">
                                    <td>Transaction #</td>
                                    <td>Dept</td>
                                    <td>Driver</td>
                                    <td>Vehicle Plate#</td>
                                    <td>RQ#</td>
                                    <td>Expense</td>
                                </tr>
                                <tr>
                                    <td><input class="form-control srch" type="text" name="trans" placeholder="Transaction #" @if(isset($_GET['trans']) && strlen($_GET['trans']) >= 2) value="{{$_GET['trans']}}" @endif></td>
                                    <td><input class="form-control srch" type="dept" name="dept" placeholder="Dept Name" @if(isset($_GET['dept']) && strlen($_GET['dept']) >= 2) value="{{$_GET['dept']}}" @endif></td>
                                    <td><input class="form-control srch" type="text" name="driver" placeholder="Driver" @if(isset($_GET['driver']) && strlen($_GET['driver']) >= 2) value="{{$_GET['driver']}}" @endif></td>
                                    <td><input class="form-control srch" type="text" name="vehicle" placeholder="Plate #" @if(isset($_GET['vehicle']) && strlen($_GET['vehicle']) >= 2) value="{{$_GET['vehicle']}}" @endif></td>
                                    <td><input class="form-control srch" type="text" name="rq" placeholder="RQ #" @if(isset($_GET['rq']) && strlen($_GET['rq']) >= 2) value="{{$_GET['rq']}}" @endif></td>
                                    <td><input class="form-control srch" type="text" name="expense" placeholder="Expense Cost Code" @if(isset($_GET['expense']) && strlen($_GET['expense']) >= 2) value="{{$_GET['expense']}}" @endif></td>

                                    <td><input type="submit" class="btn btn-success" value="Search"></td>
                                    <td><a href="{{route('fuel.issuances')}}" class="btn btn-info">Reset</a></td>
                                    
                                </tr>
                            </table>
                      
                    </form>
                </div>
                <div class="card-body">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>                         
                            <tr>
                                <th>Transaction No.</th>
                                <th>Originating Department</th>
                                <th>Vehicle</th>
                                <th>Driver</th>
                                <th>RQ No.</th>
                                <th>Expense Cost Code</th>
                                <th>Requested Qty</th>                                
                                <th>Actual Qty</th>
                                <th>Fuel Price</th>
                                <th>Processed By</th>
                                <th>Issued By</th>
                                <th>Status</th>
                                @if(!auth()->user()->role != 'gas man')
                                <th>Gas Station</th>
                                @endif
                                @if(auth()->user()->role == 'gas man')
                                <th></th>
                                @endif
                                @if((auth()->user()->dept == 'AUDIT & SYSTEMS IMPROVEMENT DEPARTMENT' || auth()->user()->domainAccount=='easia') || auth()->user()->role == 'admin')
                                <th>Actions</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody id="issuance_tbl">
                            @forelse($issuances as $issuance)
                            <tr>
                                <td><a href="{{ route('view.issuance',$issuance->id) }}">{{ $issuance->uniq_code }}</a></td>
                                <td>{{ $issuance->dept }}</td>
                                <td>{{ $issuance->vehicle }}<br><small>{{ $issuance->plate_no }}</small></td>
                                <td>{{ $issuance->driver }} {{ $issuance->alternate_driver  ? '/'. $issuance->alternate_driver  : ''}}</td>
                                <td>{{ $issuance->rq }}<br><small>{{ $issuance->rq_costcode }}</small></td>
                                {{-- <td>{{ $issuance->cost_code }}</td> --}}
                                <td>
                                    {{ $issuance->cost_code }}
                                    @if(auth()->user()->dept == 'AUDIT AND SYSTEMS IMPROVEMENT')<a href="#" data-toggle="modal" data-target="#editCostCodeModal{{ $issuance->id }}"><i class="fa fa-edit"></i></a>@endif
                                    {{-- <a href="#" data-toggle="modal" data-target="#editCostCodeModal{{ $issuance->id }}">[Edit]</a> --}}
                                </td>
                                <td class="text-right">{{ $issuance->requested_qty }}</td>
                                <td class="text-right">{{ $issuance->actual_qty }}</td>
                                <td class="text-right">{{ number_format($issuance->actual_qty * $issuance->fuel_cost,2) }}</td>
                                <td>
                                    {{ strtoupper($issuance->processed_by) }}<br><small>{{ date('Y-m-d h:i A',strtotime($issuance->processed_at)) }}</small>
                                    @if((auth()->user()->dept == 'AUDIT & SYSTEMS IMPROVEMENT DEPARTMENT' || auth()->user()->domainAccount=='easia') || auth()->user()->role == 'admin')
                                        <a href="#" data-toggle="modal" data-target="#editProcessedBy{{ $issuance->id }}"><i class="fa fa-edit"></i></a>
                                    @endif
                                </td>
                                <td>{{ $issuance->added_by }}<br><small>{{ date('Y-m-d h:i A',strtotime($issuance->created_at)) }}</small></td> 
                                <td>{{ $issuance->status }}</td>      
                                @if(auth()->user()->role != 'gas man')                         
                                <td>{{ $issuance->location }}</td>
                                @endif
                                @if((auth()->user()->dept == 'AUDIT & SYSTEMS IMPROVEMENT DEPARTMENT' || auth()->user()->domainAccount=='easia') || auth()->user()->role == 'admin')
                                    <td>                                
                                        <a href="{{ route('edit-issued-request',$issuance->id) }}" class="btn btn-xs btn-primary">Edit</a>
                                    </td>
                                @endif
                            </tr>

                            <div class="modal fade" id="editCostCodeModal{{ $issuance->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Expense Cost Code</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span>&times;</span>
                                        </button>
                                    </div>
                                    <form method="POST" action="{{ route('update.issuance-cost-code') }}">
                                        @csrf
                                        <div class="modal-body">
                                            <input type="hidden" name="issuance_id" value="{{ $issuance->id }}">

                                            <div class="row">
                                                <div class="col-md-4">
                                                    <label>Job/Vehicle Code</label>
                                                    <input type="text" name="vehicle_code" class="form-control"
                                                        value="{{ explode('.', $issuance->cost_code)[0] ?? '' }}" readonly>
                                                </div>

                                                <div class="col-md-4">
                                                    <label>Cost Code</label>
                                                    <select name="phase_code" class="form-control selectpicker" data-live-search="true" required>
                                                        @foreach($phase_codes as $code)
                                                            <option value="{{ $code }}"
                                                                @if($code === (explode('.', $issuance->cost_code)[1] ?? '')) selected @endif>
                                                                {{ $code }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-md-4">
                                                    <label>Fuel Code</label>
                                                    <input type="text" name="fuel_code" class="form-control"
                                                        value="{{ explode('.', $issuance->cost_code)[2] ?? '' }}" readonly>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-primary">Save</button>
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                        </div>
                                    </form>
                                    </div>
                                </div>
                            </div>

                            <div class="modal fade" id="editProcessedBy{{ $issuance->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Processed Details</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span>&times;</span>
                                            </button>
                                        </div>
                                        <form method="POST" action="{{ route('update.issuance-processed-by') }}">
                                            @csrf
                                            <div class="modal-body">
                                                <input type="hidden" name="issuance_id" value="{{ $issuance->id }}">

                                                <div class="row">
                                                    <!-- Processed By Select -->
                                                    <div class="col-md-12 mb-3">
                                                        <label>Processed By</label>
                                                        <input type="text" name="processed_by"  class="form-control" value="{{ strtoupper($issuance->processed_by) }}" readonly>
                                                        {{-- <select name="processed_by" class="form-control selectpicker" data-live-search="true" required style="display: none">
                                                            <option value="">-- Select Personnel --</option>
                                                            @foreach($users as $user)
                                                                <option value="{{ $user->domainAccount }}" 
                                                                    {{ $issuance->processed_by == $user->domainAccount ? 'selected' : '' }}>
                                                                    {{ strtoupper($user->name) }}
                                                                </option>
                                                            @endforeach
                                                        </select> --}}3
                                                    </div>

                                                    <!-- Processed At Date-Time -->
                                                    <div class="col-md-12">
                                                        <label>Processed Date & Time</label>
                                                        <input type="datetime-local" 
                                                            name="processed_at" 
                                                            class="form-control" 
                                                            value="{{ $issuance->processed_at ? date('Y-m-d\TH:i', strtotime($issuance->processed_at)) : '' }}" 
                                                            required>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-primary">Save Changes</button>
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            {{-- <div class="modal fade" id="editProcessedBy{{ $issuance->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Processed By</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span>&times;</span>
                                        </button>
                                    </div>
                                    <form method="POST" action="{{ route('update.issuance-processed-by') }}">
                                        @csrf
                                        <div class="modal-body">
                                            <input type="hidden" name="issuance_id" value="{{ $issuance->id }}">

                                            <div class="row">
                                                <div class="col-md-4">
                                                    <label>Job/Vehicle Code</label>
                                                    <input type="text" name="vehicle_code" class="form-control"
                                                        value="{{ explode('.', $issuance->cost_code)[0] ?? '' }}" readonly>
                                                </div>

                                                <div class="col-md-4">
                                                    <label>Cost Code</label>
                                                    <select name="phase_code" class="form-control selectpicker" data-live-search="true" required>
                                                        @foreach($phase_codes as $code)
                                                            <option value="{{ $code }}"
                                                                @if($code === (explode('.', $issuance->cost_code)[1] ?? '')) selected @endif>
                                                                {{ $code }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-md-4">
                                                    <label>Fuel Code</label>
                                                    <input type="text" name="fuel_code" class="form-control"
                                                        value="{{ explode('.', $issuance->cost_code)[2] ?? '' }}" readonly>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-primary">Save</button>
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                        </div>
                                    </form>
                                    </div>
                                </div>
                            </div> --}}


                            @empty
                            <tr>
                                <td colspan="10"><center>NO FUEL ISSUANCES FOUND.</center></td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer clearfix">
                    Showing {{ $issuances->firstItem() }} to {{ $issuances->lastItem() }} of {{ $issuances->total() }} items
                    <ul class="pagination pagination-sm m-0 float-right">
                        {{ $issuances->appends(Request::all())->links() }}
                      
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="modal-unpost">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('unpost-issuanced-request') }}" method="post">
                @csrf
                <div class="modal-header">
                    <h4 class="modal-title">Unpost Fuel Issuance</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>State your reason for unposting this issued fuel request. <i class="text-danger">*</i></p>
                    <textarea required class="form-control" name="remarks" rows="5"></textarea>
                    
                    <input type="hidden" name="rqid" id="ureq_id">
                    <input type="hidden" name="type" value="fuel">
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn btn-primary">Submit Request</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('pagejs')


<!-- jQuery (required) -->
<script src="{{ asset('assets/js/jquery-3.6.0.min.js') }}"></script>

<!-- Bootstrap Select JS -->
<script src="{{ asset('assets/js/bootstrap-select.min.js') }}"></script>

<script src="{{ asset('assets/plugins/datatables/jquery.dataTables.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>

<script type="text/javascript">
    function unpost_request(id){
        $('#modal-unpost').modal('show');
        $('.modal-body #ureq_id').val(id);
    }

    $(document).ready(function(){
        $('#search_ref_no').submit(function(e){
            e.preventDefault();
            $.ajax({
                type: "GET",
                url: "{{ route('search.issued-ref-no') }}",
                data: $('#search_ref_no').serialize(),
                success: function( response ) {
                    $('#issuance_tbl').html(response);  
                }
            });
        });
    });
</script>

<script>
    $(document).ready(function() {
        $('.selectpicker').selectpicker();
    });
</script>
@endsection