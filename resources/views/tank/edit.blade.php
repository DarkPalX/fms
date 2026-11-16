@extends('layouts.app')

@section('pagecss')
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css' ) }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css' ) }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css') }}">
    <style>
        .bootstrap-tagsinput {
            position: relative;
            background-color: #fff;
            border: 1px solid #ccc;
            box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
            display: inline-block;
            padding: 4px 6px;
            color: #555;
            vertical-align: middle;
            border-radius: 4px;
            max-width: 100%;
            width: 100%;
            line-height: 30px;
            cursor: pointer;
        }
    </style>
@endsection

@section('content')

    <div class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6"></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                    <li class="breadcrumb-item">Settings</li>
                    <li class="breadcrumb-item active">Update Tank</li>
                </ol>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="col-6 offset-3">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Create Tank</h3>
                        </div>
                        <form autocomplete="off" class="form-horizontal" method="post" action="{{ route('tanks.update', $tank->id) }}">
                            <input type="hidden" name="updated_by" value="{{ auth()->user()->domainAccount }}" />
                            @csrf
                            @method('PUT')

                            <div class="card-body">

                                <div class="form-group row">
                                    <label for="inputName" class="col-sm-2 col-form-label">Name <i class="text-danger">*</i></label>
                                    <div class="col-sm-10">
                                        <input required type="text" name="name" class="form-control" value="{{ old('name',$tank->name) }}">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="inputName" class="col-sm-2 col-form-label">Location <i class="text-danger">*</i></label>
                                    <div class="col-sm-10">
                                        <input required type="text" class="form-control" name="location" value="{{ old('location',$tank->location) }}">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="inputName" class="col-sm-2 col-form-label">Description <i class="text-danger">*</i></label>
                                    <div class="col-sm-10">
                                        <textarea name="description" required class="form-control" rows="5">{{ old('description',$tank->description) }}</textarea>
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <label for="inputName" class="col-sm-2 col-form-label">Capacity <i class="text-danger">*</i></label>
                                    <div class="col-sm-10">
                                        <input required type="number" min="1" step=".5" class="form-control" name="capacity" value="{{ old('capacity',$tank->capacity) }}">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="inputName" class="col-sm-2 col-form-label">Remaining Fuel <i class="text-danger">*</i></label>
                                    <div class="col-sm-10">
                                        <input required type="number" min="0" step=".5" class="form-control" name="remaining_fuel" value="{{ old('remaining_fuel',$tank->remaining_fuel) }}">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="inputName" class="col-sm-2 col-form-label">Active <i class="text-danger">*</i></label>
                                    <div class="col-sm-10">
                                        <label>
                                            <input type="radio" name="is_active" value="1" @if(old('is_active', $tank->is_active) == 1) checked @endif> Yes
                                        </label> &nbsp;&nbsp;
                                        <label>
                                            <input type="radio" name="is_active" value="0" @if(old('is_active', $tank->is_active) == 0) checked @endif> No
                                        </label>                                        
                                    </div>
                                </div>
                                
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary float-right">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@section('pagejs')
    <script src="{{ asset('assets/plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/bootstrap-tagsinput/bootstrap-tagsinput.min.js') }}"></script> 
    <script src="{{ asset('assets/plugins/typeahead.js/typeahead.bundle.min.js') }}"></script>
@endsection