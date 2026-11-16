@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Reports</a></li>
                    <li class="breadcrumb-item active">Issuances: Department</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="row">
        <div class="col-md-12">
            <form autocomplete="off">
                <div class="row">
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label>Department <i class="text-danger">*</i></label>
                            <select class="form-control" name="dept">
                                <option selected disabled value="">Choose Department</option>
                                <option value="ALL">All Departments</option>
                                @foreach($depts as $d)
                                <option @if(($_GET['dept'] ?? '') == $d->dept) selected @endif value="{{ $d->dept }}">{{ $d->dept }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>        
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label>Vehicle Type <i class="text-danger">*</i></label>
                            <select class="form-control" name="type">
                                <option selected disabled value="">Choose Vehicle Type</option>
                                <option value="ALL">All Vehicle Types</option>
                                @foreach($vehicle_types as $type)
                                    <option @if(($_GET['type'] ?? '') == $type) selected @endif value="{{ $type }}">{{ $type }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>                
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label>Start Date</label>
                            <input type="date" class="form-control" name="startdate" value="{{ app('request')->input('startdate') ?? date('Y-m-d') }}">
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label>End Date</label>
                            <input type="date" class="form-control" name="enddate" value="{{ app('request')->input('enddate') ?? date('Y-m-d') }}">
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label>End Date</label>
                            <select required class="form-control" name="tayp">
                                <option selected disabled value="">Choose Type</option>
                                    <option value="weekly" @if(($_GET['tayp'] ?? '') == 'weekly') selected @endif>weekly</option>
                                    <option value="monthly" @if(($_GET['tayp'] ?? '') == 'monthly') selected @endif>monthly</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <button type="submit" class="btn btn-primary" style="margin-top: 29px;">Generate</button>
                    <a href="#" class="btn btn-info float-sm-right" style="margin-top: 29px;margin-left: 5px;" onclick="exportcsv()">Export to CSV</a>
                       
                    </div>
                </div>
            </form>
        </div>
    </div>

 
            <div class="card">
                <div class="card-body">
                    <table id="tablers" class="table table-bordered table-condensed" style="z-index:1000">
                        {!! $table !!}
                    </table>
                </div>
            </div>
     
</section>
@endsection
@section('pagejs')
<script src="/assets/csvExport.js"></script>

<script>
    $('body').addClass('sidebar-collapse');

    function exportcsv(){
        //window.open(window.location.href+'&csv=on', '_blank'); 
        $('#tablers').csvExport();

    }

    
</script>
@endsection