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
                        <h3 class="card-title">Verified RQ</h3>
                        <div class="card-tools">
                            <form autocomplete="off" id="search_rqno">
                                <div class="input-group input-group-sm" style="width: 250px;">
                                    <input required type="search" name="rqno" class="form-control float-right" placeholder="Search RQ number or cost code">
                                    <input type="hidden" name="status" value="VERIFIED">
                                    <div class="input-group-append">
                                         <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="card-body">
                        <form>
                            <table width="100%">
                                <tr>
                                    <td>Cutoff Start</td>
                                    <td>Cutoff End</td>
                                    <td>RQ Number</td>
                                    <td>Dept</td>
                                    <td>Costcode</td>
                                    <td align="center"></td>
                                </tr>
                                <tr>
                                    <td><input type="date" class="form-control" name="start_date" @if(isset($_GET['start_date']) && strlen($_GET['start_date']) > 0) value="{{$_GET['start_date']}}" @endif></td>
                                    <td><input type="date" class="form-control" name="end_date" @if(isset($_GET['end_date']) && strlen($_GET['end_date']) > 0) value="{{$_GET['end_date']}}" @endif></td>
                                    <td><input type="text" class="form-control" placeholder="RQ number" name="rq" @if(isset($_GET['rq']) && strlen($_GET['rq']) > 0) value="{{$_GET['rq']}}" @endif></td>
                                    <td>
                                        <select name="dept" class="form-control">
                                            <option value="">- Select -</option>
                                            @if(isset($_GET['dept']) && strlen($_GET['dept']) > 0)
                                                <option value="{{$_GET['dept']}}" selected="selected">{{$_GET['dept']}}</option>    
                                            @endif
                                            @forelse($depts as $dept)
                                                <option value="{{$dept->dept}}">{{$dept->dept}}</option>
                                            @empty
                                            @endforelse
                                        </select>
                                    </td>
                                    <td><input type="text" class="form-control" placeholder="Enter costcode" name="costcode" @if(isset($_GET['costcode']) && strlen($_GET['costcode']) > 0) value="{{$_GET['costcode']}}" @endif></td>
                                    <td width="150">
                                        <input type="submit" value="Search" class="btn btn-success">
                                        <a href="{{route('rq.list')}}" class="btn btn-info"> Reset </a>
                                    </td>
                                </tr>
                            </table>
                        </form>
                        <br>
                        <table class="table table-bordered table-striped">
                            <thead>                  
                                <tr>
                                    <th>RQ Number</th>
                                    <th>Liters</th>
                                    <th>Cutoff</th>
                                    <th>Fuel Type</th>
                                    <th>Submitted By</th>
                                    <th>Verified By</th>
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
                                            @if($rq->status == 'VERIFIED')
                                                {{ $rq->verified_by }}<br><small>{{ date('Y-m-d h:i A',strtotime($rq->verified_at)) }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            @if($rq->attachment != 'NULL')

                                                {{-- <a href="http://mlsvrdportal.philsaga.com/dportal/storage/app/public/attachments/{{$rq->rq_no}}/{{ str_replace('#','%23',$rq->attachment) }}" class="btn btn-xs btn-secondary" data-toggle="lightbox" data-title="{{ $rq->rq_no }}<br><small>Liters: {{$rq->liters}}</small><br><small>Cutoff: {{$rq->cutoff}}</small><br><small>Costcode: {{$rq->costcode}}</small><br><small>Fuel Type: {{ $rq->fueltype }}</small>">View Attachment</a> --}}

                                               <a href="http://mlsvrdportal.philsaga.com:8009/storage/attachments/{{$rq->rq_no}}/{{ str_replace('#','%23',$rq->attachment) }}" 
                                                    class="btn btn-xs btn-secondary" 
                                                    data-toggle="lightbox" 
                                                    data-title="{{ $rq->rq_no }}<br><small>Liters: {{$rq->liters}}</small><br><small>Cutoff: {{$rq->cutoff}}</small><br><small>Costcode: {{$rq->costcode}}</small><br><small>Fuel Type: {{ $rq->fueltype }}</small>" onclick="window.open(this.href,'_blank'); return false;">View Attachment</a>
                                            
                                      
                                           



                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5"><center>NO PENDING RQ'S FOR VERIFICATION.</center></td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="row card-footer clearfix">
                        <div class="col-md-6">
                            <p>Showing {{$rqs->firstItem()}} to {{$rqs->lastItem()}} of {{$rqs->total()}} items</p>
                        </div>
                        <div class="col-md-6">
                            <ul class="pagination pagination-sm m-0 float-right">
                                {{ $rqs->links() }}
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
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
@endsection
