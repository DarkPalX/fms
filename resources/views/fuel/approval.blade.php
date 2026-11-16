@extends('layouts.app')

@section('pagecss')
<link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css' ) }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css' ) }}">
@endsection

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Issuances</a></li>
                    <li class="breadcrumb-item active">Approval Request</li>
                    <li class="breadcrumb-item active">Request List</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Request List</h3>
                    </div>

                    <div class="card-body">
                        <table class="table table-bordered table-striped">
                            <thead>                  
                                <tr>
                                    <th>Transaction No.</th>
                                    <th>Request Type</th>
                                    <th>Requestor</th>
                                    <th>Reason</th>
                                    <th>Status</th>
                                    <th>Approver</th>
                                    <th>Remarks</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($requests as $req)
                                <tr> 
                                    <td>
                                        @if($req->request_type == 'rq')
                                            {{ $req->details->rq_no }}
                                        @else
                                            {{ $req->details->uniq_code }}
                                        @endif
                                    </td>
                                    <td class="text-uppercase">{{ $req->request_type }}</td>
                                    <td>{{ $req->initiator }}<br><small>{{date('F d, Y h:i A',strtotime($req->initiated_at)) }}</small></td>
                                    <td>{{ str_limit($req->reason, 50, $end ='...') }}</td>
                                    <td>{{ $req->status }}</td>
                                    <td>{{ $req->approver }}<br><small>{{date('F d, Y h:i A',strtotime($req->approved_at)) }}</small></td>
                                    <td>{{ $req->remarks }}</td>
                                    <td>
                                        <a href="{{ route('view.request-details',$req->id) }}" class="btn btn-xs btn-secondary">View</a>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="6" class="text-center">NO APPROVAL REQUESTS FOUND</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="row card-footer clearfix">
                        <div class="col-md-6">
                            <p>Showing {{$requests->firstItem()}} to {{$requests->lastItem()}} of {{$requests->total()}} items</p>
                        </div>
                        <div class="col-md-6">
                            <ul class="pagination pagination-sm m-0 float-right">
                                {{ $requests->links() }}
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
