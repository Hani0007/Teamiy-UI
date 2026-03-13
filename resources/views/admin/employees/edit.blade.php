@extends('layouts.master')

@section('title', __('index.edit_user_detail'))

@section('action', __('index.edit'))

@section('button')
    <div class="float-end">
        <a href="{{ route('admin.employees.index') }}">
            <button class="btn btn-sm btn-primary"><i class="link-icon" data-feather="arrow-left"></i> {{ __('index.back') }}</button>
        </a>
    </div>
@endsection

@section('main-content')

    <section class="content">
<div class="teamy-top-header">
        <div>
            <h2>{{ __('index.employee_management') }}</h2>
            <div class="header-info-row">
                <div class="header-info-item">
                    <span class="status-badge" style="background: #eef2ff; color: #6366f1;">Update</span>
                </div>
                <div class="header-info-item">
                    <i class="fa fa-user-plus"></i> Update Employee
                </div>
            </div>
        </div>
    </div>
        @include('admin.section.flash_message')

        @include('admin.employees.common.breadcrumb')

        <div class="card-user">
            <form class="forms-sample" id="employeeDetail" action="{{ route('admin.employees.update', $userDetail->id) }}" enctype="multipart/form-data" method="post">
                @method('PUT')
                @csrf
                @include('admin.employees.common.form')
            </form>
        </div>

    </section>
@endsection

@section('scripts')

    @include('admin.employees.common.scripts')

    <script>
        $(document).on('change', '#branch', function() {
            var branchId = $(this).val();
            if (!branchId) return;

            $.ajax({
                url: '{{ route('admin.fetch.departments') }}',
                type: 'GET',
                dataType: 'JSON',
                data: { branchId: branchId },
                success: function(response) {
                    if (response.status) {
                        var $department = $('#department');
                        var $designations = $('#post');
                        $department.empty();
                        $designations.empty();

                        // Add default option with empty value
                        $department.append(
                            $('<option>', {
                                value: "",
                                text: "Select Department",
                                selected: true
                            })
                        );

                        $.each(response.data, function (i, dept) {
                            $department.append(
                                $('<option>', {
                                    value: dept.id,
                                    text: dept.dept_name
                                })
                            );
                        });
                    }
                    else {
                        // alert(response.message);
                        alert("{{ __('no_department_found') }}");
                    }
                }
            });
        });


        $(document).on('change', '#department', function () {
            var departmentId = $(this).val();
            var branchId = $('#branch').val();

            if (!departmentId) return;

            $.ajax({
                url: '{{ route('admin.fetch.designations') }}',
                type: 'GET',
                dataType: 'JSON',
                data: {
                    branchId: branchId,
                    departmentId: departmentId
                },
                success: function (response) {
                    if (response.status) {
                        var $designations = $('#post');
                        $designations.empty();

                        $designations.append(
                            $('<option>', {
                                text: "Select Designations",
                                disabled: true,
                                selected: true
                            })
                        );

                        $.each(response.data, function (i, desig) {
                            $designations.append(
                                $('<option>', {
                                    value: desig.id,
                                    text: desig.post_name
                                })
                            );
                        });
                    }
                    else {
                        // alert(response.message);
                        alert({{ __('index.no_designation_found') }});
                    }
                }
            });
        });


        document.getElementById('add-document').addEventListener('click', function() {
            let container = document.getElementById('document-container');

            let newField = document.createElement('div');
            newField.classList.add('col-lg-6', 'col-md-6', 'mb-3', 'document-field');

            newField.innerHTML = `
                <label class="form-label">Upload Document</label>
                <input type="file" class="form-control" name="employee_document[]"
                        accept="application/pdf,
                        application/msword,
                        application/vnd.openxmlformats-officedocument.wordprocessingml.document,
                        image/jpeg">

            `;

            container.appendChild(newField);
        });
    </script>

@endsection
