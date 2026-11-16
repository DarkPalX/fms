@extends('layouts.app')

@section('pagecss')
<link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css' ) }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css' ) }}">
@endsection

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Report</a></li>
                    <li class="breadcrumb-item active">Unpost Requests</li>
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
                    <h3 class="card-title">Unpost Request Table</h3>
                </div>
                <div class="card-body">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Transaction #</th>
                                <th>Requested By</th>
                                <th>Reason</th>
                                <th>Approver</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($requests as $r)
                                <tr>
                                    <td>{{ ($requests->currentpage()-1) * $requests->perpage() + $loop->index + 1 }}</td>
                                    <td>{{ $r->details->uniq_code }}</td>
                                    <td>{{ $r->initiator }}<br><small>{{ date('Y-m-d h:i A',strtotime($r->initiated_at)) }}</small></td>
                                    <td>{{ $r->reason }}</td>
                                    <td>
                                        @if($r->status != 'PENDING') {{ $r->approver }}<br><small>{{ date('Y-m-d h:i A',strtotime($r->approved_at)) }}</small> @endif
                                    </td>
                                    <td>{{ $r->status }}</td>
                                </tr>
                            @empty

                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('pagejs')
<script src="{{ asset('assets/plugins/datatables/jquery.dataTables.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
<script src="{{ asset('assets/plugins/select2/js/select2.full.min.js') }}"></script>

<script type="text/javascript">

    $(function () {
        $('.select2-dept').select2()  

    })

    $(document).ready(function(){
        $('#filter_issuances').submit(function(e){
            e.preventDefault();
            $.ajax({
                type: "GET",
                url: "/filter/issuance",
                data: $('#filter_issuances').serialize(),
                success: function( response ) {
                    $('#issuance_tbl').html(response);  
                }
            });
        });
    });
</script>
@endsection