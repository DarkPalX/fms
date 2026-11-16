@extends('layouts.app')

@section('pagecss')
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css' ) }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css' ) }}">
@endsection

@section('content')
<div class="content-header">
    <div class="row mb-2">
        <div class="col-sm-6"></div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                <li class="breadcrumb-item">Fuel Issuances</li>
                <li class="breadcrumb-item active">Update Request Details</li>
            </ol>
        </div>
    </div>
</div>

<section class="content">
    <div class="row">
        <div class="col-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Update User</h3>
                </div>
                <form autocomplete="off" class="form-horizontal" method="post" action="{{ route('user.update') }}">
                    <input type="hidden" value="1" name="is_dportal" />    

                    @csrf
                    <input type="hidden" name="id" value="{{$user->id}}">
                    <div class="card-body">
                        <div class="form-group row">
                            <label for="inputName" class="col-sm-2 col-form-label">Name <i class="text-danger">*</i></label>
                            <div class="col-sm-10">
                                <input readonly type="text" class="form-control" value="{{$user->fullname}}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="inputName" class="col-sm-2 col-form-label">Username <i class="text-danger">*</i></label>
                            <div class="col-sm-10">
                                <input required type="text" class="form-control" name="domain" value="{{$user->domain}}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="inputName2" class="col-sm-2 col-form-label">Role <i class="text-danger">*</i></label>
                            <div class="col-sm-10">
                                <select required name="role" class="select2" data-placeholder="Select Role" style="width: 100%;">
                                    <option value="1"> Department Secretary</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="inputName2" class="col-sm-2 col-form-label">&nbsp;</label>
                            <div class="custom-control custom-checkbox col-sm-10">
                                &nbsp;
                                <input class="custom-control-input" type="checkbox" name="pword_reset" id="customCheckbox2">
                                <label for="customCheckbox2" class="custom-control-label">Reset Password</label>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary float-right">Create</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection

@section('pagejs')
    <script src="{{ asset('assets/plugins/select2/js/select2.full.min.js') }}"></script>
    <script>
        $(function () {
            $('.select2').select2();
        });
    </script>
@endsection
