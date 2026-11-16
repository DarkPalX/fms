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
                    <h3 class="card-title">Create User</h3>
                </div>
                <form autocomplete="off" class="form-horizontal" method="post" action="{{ route('user.store') }}">
                    @csrf
                    <div class="card-body">
                        <div class="form-group row">
                            <label for="inputName" class="col-sm-2 col-form-label">Name <i class="text-danger">*</i></label>
                            <div class="col-sm-10">
                                <input required type="text" id="input2" name="employee_data">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="inputName" class="col-sm-2 col-form-label">Username <i class="text-danger">*</i></label>
                            <div class="col-sm-10">
                                <input required type="text" class="form-control" name="domain">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="inputName2" class="col-sm-2 col-form-label">Role <i class="text-danger">*</i></label>
                            <div class="col-sm-10">
                                <select required name="role" id="role" class="select2" data-placeholder="Select Role" style="width: 100%;">
                                    <option value="admin">Admin</option>
                                    <option value="user">User</option>
                                    <option value="clerk">Clerk</option>
                                    <option value="gas man">Gas Man</option>
                                    <option value="super admin">Super Admin</option>
                                    <option value="department secretary">Department Secretary</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="inputName" class="col-sm-2 col-form-label">Assigned Location (for gasman only) <i class="text-danger">*</i></label>
                            <div class="col-sm-10">
                                <select name="role2" id="role2" class="select2" data-placeholder="Select Role" style="width: 100%;">
                                    <option value="">Select Option</option>
                                    <option value="admin">Admin</option>
                                    <option value="user">User</option>
                                    <option value="clerk">Clerk</option>
                                    <option value="gas man">Gas Man</option>
                                </select>
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
    <script src="{{ asset('assets/plugins/bootstrap-tagsinput/bootstrap-tagsinput.min.js') }}"></script> 
    <script src="{{ asset('assets/plugins/typeahead.js/typeahead.bundle.min.js') }}"></script>
    <script>
        $(function () {
            $('.select2').select2();
        });
    </script>

    <script>       
        
        $(document).ready(function(){
            
            var employees = {!! \App\Users::employee_lookup() !!};
            var employees = new Bloodhound({
                datumTokenizer: Bloodhound.tokenizers.whitespace,
                queryTokenizer: Bloodhound.tokenizers.whitespace,
                local : employees
            });


            $('#input2').tagsinput({
                maxTags : 1,
                typeaheadjs: ({
                    hint: true,
                    highlight: true,
                    minLength: 1
                },
                {
                    name : 'employees',
                    source: employees
                })
            });

            $(document).on('change', '#role', function() {
                if($(this).val() == 'gas man') {
                    $('#role2').attr('required', true);
                } else {
                    $('#role2').removeAttr('required');
                }
            });

        });
    </script>
@endsection