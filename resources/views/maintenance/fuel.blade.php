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
                    <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                    <li class="breadcrumb-item">Maintenance</li>
                    <li class="breadcrumb-item active">Fuels</li>
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
                        <h3 class="card-title">Fuel Type Table</h3>
                        <a href="" class="btn btn-sm btn-primary float-right" data-toggle="modal" data-target="#modal-add"><i class="fa fa-plus"></i> Create Fuel</a>
                    </div>

                    <div class="card-body">
                        <table class="table table-bordered table-striped">
                            <thead>                  
                                <tr>
                                    <th>#</th>
                                    <th>Stock Code</th>
                                    <th>Description</th>
                                    <th>Cost</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($datas as $f)
                                <tr>
                                    <td>{{ ($datas->currentpage()-1) * $datas ->perpage() + $loop->index + 1 }}</td>
                                    <td><a href="javascript:;" data-toggle="collapse" data-target="#row{{$f->id}}">{{ $f->stock_code }}</a></td>
                                    <td>{{ $f->fuel_type }}</td>
                                    <td>{{ $f->cost }}</td>
                                    <td>
                                        <button class="btn mb-1 btn-xs btn-primary" onclick="fuelUpdate('{{ $f->id }}','{{ $f->stock_code }}','{{ $f->fuel_type }}','{{ $f->cost }}');">Edit</button>
                                        @if($f->isActive==1)
                                            <a href="#" class="btn btn-xs btn-danger mb-1 delete" data-fuel_id="{{ $f->id }}" data-fuel_name="{{ $f->fuel_type }}" data-toggle="modal" data-target="#modal-delete"> 
                                                Disable
                                            </a>
                                        @else
                                            <a href="#" class="btn btn-xs btn-success mb-1 enable" data-fuel_id="{{ $f->id }}" data-fuel_name="{{ $f->fuel_type }}" data-toggle="modal" data-target="#modal-enable"> 
                                                Enable
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="5" class="pb-0 pt-0 mb-10">
                                        <div class="collapse mt-10" id="row{{$f->id}}">
                                            <br>
                                            <table class="table table-bordered table-striped">
                                                <thead>                  
                                                    <tr>
                                                        <th>Cost</th>
                                                        <th>Updated By</th>
                                                        <th>Updated At</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse($f->costs as $c)
                                                    <tr>
                                                        <td>{{ $c->cost }}</td>
                                                        <td>{{ $c->updated_by }}</td>
                                                        <td>{{ date('Y-m-d h:i A',strtotime($c->updated_at)) }}</td>
                                                    </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="3" class="text-center">NO COST HISTORY FOUND.</td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="row card-footer clearfix">
                        <div class="col-md-6">
                            <p>Showing {{$datas->firstItem()}} to {{$datas->lastItem()}} of {{$datas->total()}} items</p>
                        </div>
                        <div class="col-md-6">
                            <ul class="pagination pagination-sm m-0 float-right">
                                {{ $datas->links() }}
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@include('maintenance.modals')
@endsection

@section('pagejs')
    <script src="{{ asset('assets/plugins/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>

    <script>
        $(document).on('click','.deactivate', function(){
            $('#d_fuel_id').val($(this).data('fuel_id'));
        });

        $(document).on('click','.activate', function(){
            $('#a_fuel_id').val($(this).data('fuel_id'));
        });

        $(document).on('click','.delete', function(){
            $('#p_fuel_id').val($(this).data('fuel_id'));
            $('#fuel_name_disable').html($(this).data('fuel_name'));
            $('#fuel_name_disable_hidden').val($(this).data('fuel_name'));
            
        });

        $(document).on('click','.enable', function(){
            $('#p_fuel_id_enable').val($(this).data('fuel_id'));
            $('#fuel_name_enable').html($(this).data('fuel_name'));            
        });

        function fuelUpdate(id,code,type,cost){
            $('#modal-update').modal('show');

            $('#u_fuel_id').val(id);
            $('#stock_code').val(code);
            $('#desc').val(type);
            $('#cost').val(cost);
        }

        $(document).on('change','#fuel_disable_reason', function(){
            var reason = $(this).val();
            if( !$(this).val() ) { 
                $('#fuel_email_disable').val('');
            }else{

                $('#fuel_email_disable').val('This is to inform that we temporarily suspended the requisition and dispensing of '+$("#fuel_name_disable_hidden").val()+' due to: \n\n'+reason+'. \n\nPlease be guided accordingly until further notice.');
            }
        });
    </script>
@endsection
