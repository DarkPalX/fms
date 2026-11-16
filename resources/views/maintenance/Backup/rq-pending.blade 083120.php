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
                    <li class="breadcrumb-item active">RQ Verification</li>
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
                        <h3 class="card-title">Pending RQ</h3>
                        <div class="card-tools">
                            <form autocomplete="off" id="search_rqno">
                                <div class="input-group input-group-sm" style="width: 250px;">
                                    <input required type="search" name="rqno" class="form-control float-right" placeholder="Search RQ number or cost code">
                                    <input type="hidden" name="status" value="PENDING">
                                    <div class="input-group-append">
                                         <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="card-body">
                        <table class="table table-bordered table-striped">
                            <thead>                  
                                <tr>
                                    <th>RQ Number</th>
                                    <th>Liters</th>
                                    <th>Cutoff</th>
                                    <th>Fuel Type</th>
                                    <th>Submitted By</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="rqtbl">
                                @forelse($rqs as $rq)
                                    <tr>
                                        <td>{{ $rq->rq_no }}<br><small>{{ $rq->costcode }}</small></td>
                                        <td>{{ number_format($rq->liters,2) }}</td>
                                        <td>{{ $rq->cutoff }}</td>
                                        <td>{{ $rq->fueltype }}</td>
                                        <td>{{ $rq->created_by }} - {{ date('Y-m-d h:i A',strtotime($rq->created_at)) }} <br> <small>{{ $rq->dept }}</small></td>
                                        <td>  
                                            <a href="" class="btn btn-xs btn-primary verify" data-toggle="modal" data-target="#modal-verify" data-rq_id="{{$rq->id}}">Verify</a>
                                            <a href="" class="btn btn-xs btn-danger unverify" data-toggle="modal" data-target="#modal-unverify" data-rq_id="{{$rq->id}}">Unverify</a>

                                            @if($rq->attachment != 'NULL')
                                                <a href="https://mlappsvr.philsaga.com/dportal/storage/app/public/attachments/{{$rq->rq_no}}/{{ $rq->attachment }}" class="btn btn-xs btn-secondary" data-toggle="lightbox" data-title="{{ $rq->rq_no }}<br><small>Liters: {{$rq->liters}}</small><br><small>Cutoff: {{$rq->cutoff}}</small><br><small>Costcode: {{$rq->costcode}}</small><br><small>Fuel Type: {{ $rq->fueltype }}</small>">View Attachment</a>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9"><center>NO PENDING RQs.</center></td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="modal-verify">
    <div class="modal-dialog">
        <div class="modal-content">
            <form autocomplete="off" action="{{ route('verify-rq') }}" method="post">
                @csrf
                <div class="modal-header">
                    <h4 class="modal-title">RQ Verification</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>You are about to update this RQ status into <b>VERIFIED.</b> Do you want to continue?</p>
                    <input type="hidden" id="v_rq_id" name="rq_id">
                    <input type="hidden" value="VERIFIED" name="status">
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Yes, Verifiy</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-unverify">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="/rq/verification" method="post">
                @csrf
                <div class="modal-header">
                    <h4 class="modal-title">RQ Unverification</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to unverify this RQ. Please state your reason for unverifying this RQ.</p>
                    <input type="hidden" id="u_rq_id" name="rq_id">
                    <input type="hidden" value="UNVERIFIED" name="status">
                    <textarea required class="form-control" name="reason" id="" rows="5"></textarea>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-sm btn-danger">Yes, Unverify</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('pagejs')
    <script src="{{ asset('assets/plugins/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ asset('assets/plugins/ekko-lightbox/ekko-lightbox.min.js') }}"></script>

    <script>
        $(function () {
            $(document).on('click', '[data-toggle="lightbox"]', function(event) {
                event.preventDefault();
                $(this).ekkoLightbox({
                    alwaysShowClose: true
                });
            });
        })

        $(document).ready(function(){
            $('#search_rqno').submit(function(e){
                e.preventDefault();
                $.ajax({
                    type: "GET",
                    url: "{{ route('search.rqno') }}",
                    data: $('#search_rqno').serialize(),
                    success: function( response ) {
                        $('#rqtbl').html(response);  
                    }
                });
            });
        });
    </script>

    <script>
        $(document).on('click','.verify', function(){
            $('#v_rq_id').val($(this).data('rq_id'));
        });

        $(document).on('click','.unverify', function(){
            $('#u_rq_id').val($(this).data('rq_id'));
        });
    </script>
@endsection
