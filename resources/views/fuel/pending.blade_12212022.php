@extends('layouts.app')

@section('pagecss')
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
	                <li class="breadcrumb-item"><a href="{{ route('fuel.issuances') }}">Fuel Issuances</a></li>
	                <li class="breadcrumb-item active">Fuel Requests</li>
	            </ol>
	        </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Pending Fuel Request</h3>
                    <div class="card-tools">
                        <form autocomplete="off" id="search_ref_no">
                            <div class="input-group input-group-sm" style="width: 250px;">
                                <input required type="search" name="ref_no" class="form-control float-right" placeholder="Search transaction #">

                                <div class="input-group-append">
                                     <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Transaction No.</th>
                                <th>Originating Department</th>
                                <th>Prepared By</th>
                                <th>Driver</th>
                                <th>Vehicle</th>
                                <th>Requested Qty</th>
                                <th>Fuel Type</th>
                                <th>Fuel Price</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="issuance_tbl">
                        	@forelse($issuances as $issuance)
                            <tr>
                                <td>{{ $issuance->uniq_code }}</td>
                                <td>{{ $issuance->dept }}</td>
                                <td>{{ $issuance->added_by }}<br><small>{{ date('Y-m-d h:i A',strtotime($issuance->created_at)) }}</small></td>
                                <td>{{ $issuance->driver }}</td>
                                <td>{{ $issuance->vehicle }}<br><small>{{$issuance->plate_no}}</small></td>
                                <td class="text-right">{{ $issuance->requested_qty }}</td>
                                <td>{{ $issuance->fuel_type }}</td>
                                <td class="text-right">{{ number_format($issuance->actual_qty * $issuance->fuel_cost,2) }}</td>
                                <td>{{$issuance->status}}</td>
                                <td>
                                    <a href="{{ route('process.issuance',$issuance->id) }}" class="btn btn-xs btn-primary">Process</a>
                                    @if(auth()->user()->role != 'gas man' || auth()->user()->role == 'clerk' )
                                        <button type="button" onclick="unlock_request('{{$issuance->id}}');" class="btn btn-xs btn-primary">Unlock</button>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9"><center>NO ISSUANCE REQUEST FOUND.</center></td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer clearfix">
                    Showing {{ $issuances->firstItem() }} to {{ $issuances->lastItem() }} of {{ $issuances->total() }} items
                    <ul class="pagination pagination-sm m-0 float-right">
                        {{ $issuances->links() }}
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
<div class="modal" id="location_modal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
        <form action="{{route('location_save_to_session')}}" method="post">
                @csrf
              <div class="modal-header">
                <h5 class="modal-title">Gas man location</h5>
                </button>
              </div>
              <div class="modal-body">
                
                    <p>Please select your current station location now:</p>
                    <select name="location_gasman" id="location_gasman" class="form-control" required="required">
                        <option value="">Select</option>
                        <option value="MILL">MILL</option>
                        <option value="MINE">MINE</option>
                        <option value="TANKER">TANKER</option>
                    </select>
                
              </div>
              <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Submit</button>
              </div>
        </form>
    </div>
  </div>
</div>

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
<script src="{{ asset('assets/plugins/datatables/jquery.dataTables.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>

<script type="text/javascript">

    $(document).ready(function(){
        $('#search_ref_no').submit(function(e){
            e.preventDefault();
            $.ajax({
                type: "GET",
                url: "{{ route('search.ref-no') }}",
                data: $('#search_ref_no').serialize(),
                success: function( response ) {
                    $('#issuance_tbl').html(response);  
                }
            });
        });
    });

    function unlock_request(id){
        $('#modal-unlock').modal('show');
        $('.modal-body #rqid').val(id);
    }

    @if(Auth::user()->role == 'gas man' && empty(session('location_gasman')) )
        $(document).ready(function(){
            $('#location_modal').modal({
                backdrop: 'static',
                keyboard: false,
                show: true
            });
        }); 
    @endif
</script>
@endsection