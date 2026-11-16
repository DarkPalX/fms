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
                    <div class="col-3">
                        <div class="form-group">
                            <label>Department <i class="text-danger">*</i></label>
                            <select class="form-control" name="dept">
                                <option selected disabled value="">Choose Department</option>
                                <option value="ALL">All Vehicles</option>
                                @foreach($depts as $d)
                                <option @if(($_GET['dept'] ?? '') == $d->dept) selected @endif value="{{ $d->dept }}">{{ $d->dept }}</option>
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
                                    <option value="daily">Daily</option>
                                    <option value="weekly">weekly</option>
                                    <option value="monthly">monthly</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <button type="submit" class="btn btn-primary" style="margin-top: 29px;">Generate</button>
                           
                       
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <table id="example1" class="table table-bordered table-striped">
                        {!! $table !!}
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('pagejs')
<script>
    $('body').addClass('sidebar-collapse');
</script>
@endsection