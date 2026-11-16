@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Settings</a></li>
                    <li class="breadcrumb-item active">Activity Logs</li>
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
                    <h3 class="card-title">User Activities</h3>
                </div>
                <div class="card-body">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Transaction #</th>
                                <th>Action</th>
                                <th>Description</th>
                                <th>User</th>
                                <th>Activity Date</th>
                                <th>From Approval ?</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($logs as $l)
                                <tr>
                                    <td>{{ $l->details->uniq_code }}</td>
                                    <td>{{ $l->activity_type }}</td>
                                    <td>{{ $l->activity_desc }}</td>
                                    <td>{{ $l->details->added_by }}</td>
                                    <td>{{ date('m-d-Y h:i A',strtotime($l->activity_date)) }}</td>
                                    <td>{{ $l->approval_req_id ?? 'NO' }}</td>
                                </tr>
                            @empty

                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="row card-footer clearfix">
                    <div class="col-md-6">
                        <p>Showing {{$logs->firstItem()}} to {{$logs->lastItem()}} of {{$logs->total()}} items</p>
                    </div>
                    <div class="col-md-6">
                        <ul class="pagination pagination-sm m-0 float-right">
                            {{ $logs->links() }}
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection