@extends('layouts.app')

@section('pagecss')
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
@endsection

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Settings</a></li>
                    <li class="breadcrumb-item active">Downtime</li>
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
                    <h3 class="card-title">Downtime Table</h3>
                    <a href="{{ route('downtime-create') }}" class="btn btn-sm btn-primary float-right" style="margin-right: 5px; "><i class="fa fa-cogs"></i> Add Downtime</a>
                </div>
                <div class="card-body">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Reason</th>
                                <th>Status</th>
                                <th>Encoded By</th>
                                <td></td>
                            </tr>
                        </thead>
                        <tbody>
                           @forelse($downtime as $d)
                           <tr>
                               <td>{{ date('m-d-Y h:i A',strtotime($d->startdate)) }}</td>
                               <td>{{ date('m-d-Y h:i A',strtotime($d->enddate)) }}</td>
                               <td>{{ $d->reason }}</td>
                               <td>{{ $d->status }}</td>
                               <td>{{ $d->encoded_by }}</td>
                               <td><a href="{{ route('downtime-edit',$d->id) }}" class="btn btn-primary btn-sm">Edit</a></td>
                           </tr>
                           @empty
                           <tr><td colspan="6" class="text-center">No downtime found.</td></tr>
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
@endsection