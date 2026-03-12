{{--@extends('layouts.master')

@section('title', __('index.create_employee'))

@section('action', __('index.add'))

@section('button')
    <div class="float-md-end">
        <a href="{{ route('admin.employees.index') }}">
            <button class="btn btn-sm btn-primary"><i class="link-icon" data-feather="arrow-left"></i> {{ __('index.back') }}</button>
        </a>
    </div>
@endsection

@section('main-content')

    <section class="content">

        @include('admin.section.flash_message')

        @include('admin.employees.common.breadcrumb')

        <div class="card-user">
            <form class="forms-sample" id="employeeDetail" action="{{ route('admin.employees.store') }}" enctype="multipart/form-data" method="POST">
                @csrf
                @include('admin.employees.common.form')
            </form>
        </div>

    </section>
@endsection

@section('scripts')

    @include('admin.employees.common.scripts')

    <!-- <script>
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
                        $department.empty();

                        $department.append(
                            $('<option>', {
                                text: "{{ __('index.select_department') }}",
                                disabled: true,
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
                       alert(response.message);
                      
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
                        $department.empty();

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
                        alert(response.message);  // department 
                    

                    }
                }
            });
        });

        $(document).on('change', '#branch', function() {
            var branchId = $(this).val();
            if (!branchId) return;

            $.ajax({
                url: '{{ route('admin.fetch.office.timings') }}',
                type: 'GET',
                data: { branchId: branchId },
                dataType: 'json',
                success: function(response) {
                    if (response.status) {

                        const $officeTime = $('#officeTime');
                        $officeTime.empty();

                        $officeTime.append(
                            $('<option>', {
                                text: "{{ __('index.select_office_timing') }}",
                                disabled: true,
                                selected: true
                            })
                        );

                        $.each(response.data, function (i, officeTiming) {
                            $officeTime.append(
                                $('<option>', {
                                    value: officeTiming.id,
                                    text: `${officeTiming.opening_time} - ${officeTiming.closing_time}`
                                })
                            );
                        });

                    } else {
                        alert(response.message); // branch
                    }
                }
            }).fail(function(xhr) {
                alert("Something went wrong, please try again.");
                console.error(xhr.responseText);
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
    </script> -->
    
<script>

    // ===============================
    // BRANCH CHANGE
    // ===============================
    $(document).on('change', '#branch', function() {

        var branchId = $(this).val();
        if (!branchId) return;

        // ===== Fetch Departments =====
        $.ajax({
            url: '{{ route('admin.fetch.departments') }}',
            type: 'GET',
            dataType: 'JSON',
            data: { branchId: branchId },

            success: function(response) {

                var $department = $('#department');
                $department.empty();

                if (response.data && response.data.length > 0) {

                    $department.append(
                        $('<option>', {
                            text: "{{ __('index.select_department') }}",
                            disabled: true,
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

                } else {

                    $department.append(
                        $('<option>', {
                            text: "{{ __('no_department_found') }}",
                            disabled: true,
                            selected: true
                        })
                    );

                    alert("{{ __('no_department_found') }}");
                }
            }
        });


        // ===== Fetch Office Timings =====
        $.ajax({
            url: '{{ route('admin.fetch.office.timings') }}',
            type: 'GET',
            dataType: 'JSON',
            data: { branchId: branchId },

            success: function(response) {

                var $officeTime = $('#officeTime');
                $officeTime.empty();

                if (response.data && response.data.length > 0) {

                    $officeTime.append(
                        $('<option>', {
                            text: "{{ __('index.select_office_timing') }}",
                            disabled: true,
                            selected: true
                        })
                    );

                    $.each(response.data, function (i, officeTiming) {
                        $officeTime.append(
                            $('<option>', {
                                value: officeTiming.id,
                                text: officeTiming.opening_time + ' - ' + officeTiming.closing_time
                            })
                        );
                    });

                } else {

                    $officeTime.append(
                        $('<option>', {
                            text: "No Office Timing Found",
                            disabled: true,
                            selected: true
                        })
                    );
                }
            }
        });

    });



    // ===============================
    // DEPARTMENT CHANGE
    // ===============================
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

                var $designations = $('#post');
                $designations.empty();

                if (response.data && response.data.length > 0) {

                    $designations.append(
                        $('<option>', {
                            text: "Select Designation",
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

                } else {

                    $designations.append(
                        $('<option>', {
                            text: "{{ __('no_designation_found') }}",
                            disabled: true,
                            selected: true
                        })
                    );

                    alert("{{ __('no_designation_found') }}");
                }
            }
        });

    });


    // ===============================
    // ADD DOCUMENT FIELD
    // ===============================
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
--}}

@extends('layouts.master')

@section('title', __('index.create_employee'))

@section('button')
    <a href="{{ route('admin.employees.index') }}">
        <button class="btn btn-sm btn-primary">
            <i class="link-icon" data-feather="arrow-left"></i> {{ __('index.back') }}
        </button>
    </a>
@endsection

@section('main-content')

<div class="teamy-body-wrapper">
    {{-- Modern Header --}}
    <div class="teamy-top-header">
        <div>
            <h2>{{ __('index.employee_management') }}</h2>
            <div class="header-info-row">
                <div class="header-info-item">
                    <span class="status-badge" style="background: #eef2ff; color: #6366f1;">New</span>
                </div>
                <div class="header-info-item">
                    <i class="fa fa-user-plus"></i> {{ __('index.create_employee') }}
                </div>
            </div>
        </div>
    </div>
    
    @section('action', __('index.create_employee'))
    @include('admin.employees.common.breadcrumb')
    @include('admin.section.flash_message')

    {{-- Form Start --}}
    <form class="forms-sample" id="employeeDetail" action="{{ route('admin.employees.store') }}" enctype="multipart/form-data" method="POST">
        @csrf
        
        @include('admin.employees.common.form')

        {{-- Footer Buttons --}}
        <!-- <div class="branch-footer-actions">
            <a href="{{ route('admin.employees.index') }}" class="branch-back-btn">
                <i class="fa fa-times"></i> {{ __('index.cancel') }}
            </a>
            <button type="submit" class="btn btn-primary">
                {{ __('index.add_employee') }}
            </button>
        </div> -->
    </form>
</div>

@endsection

@section('scripts')
    @include('admin.employees.common.scripts')

    <script>
        $(document).ready(function() {
            // Reusable select reset
            const resetDropdown = (el, text) => {
                $(el).empty().append(`<option value="" disabled selected>${text}</option>`);
            };

            // 1. Branch Change logic
            $(document).on('change', '#branch', function() {
                var branchId = $(this).val();
                if (!branchId) return;

                // Fetch Departments
                $.ajax({
                    url: '{{ route("admin.fetch.departments") }}',
                    type: 'GET',
                    data: { branchId: branchId },
                    success: function(response) {
                        var $dept = $('#department');
                        resetDropdown($dept, "{{ __('index.select_department') }}");
                        if (response.status && response.data.length > 0) {
                            $.each(response.data, function(i, d) {
                                $dept.append(`<option value="${d.id}">${d.dept_name}</option>`);
                            });
                        }
                    }
                });

                // Fetch Office Timings
                $.ajax({
                    url: '{{ route("admin.fetch.office.timings") }}',
                    type: 'GET',
                    data: { branchId: branchId },
                    success: function(response) {
                        var $time = $('#officeTime');
                        resetDropdown($time, "{{ __('index.select_office_timing') }}");
                        if (response.status && response.data.length > 0) {
                            $.each(response.data, function(i, t) {
                                $time.append(`<option value="${t.id}">${t.opening_time} - ${t.closing_time}</option>`);
                            });
                        }
                    }
                });
            });

            // 2. Department Change logic
            $(document).on('change', '#department', function() {
                var deptId = $(this).val();
                var branchId = $('#branch').val();
                if (!deptId) return;

                $.ajax({
                    url: '{{ route("admin.fetch.designations") }}',
                    type: 'GET',
                    data: { branchId: branchId, departmentId: deptId },
                    success: function(response) {
                        var $post = $('#post');
                        resetDropdown($post, "Select Designation");
                        if (response.status && response.data.length > 0) {
                            $.each(response.data, function(i, p) {
                                $post.append(`<option value="${p.id}">${p.post_name}</option>`);
                            });
                        }
                    }
                });
            });

            // 3. Dynamic Document Addition
            $('#add-document').on('click', function() {
                let container = $('#document-container');
                let newField = `
                    <div class="col-lg-6 col-md-6 mb-3 document-field">
                        <label class="form-label">Upload Document</label>
                        <input type="file" class="form-control" name="employee_document[]" 
                               accept=".pdf,.doc,.docx,.jpg,.jpeg">
                    </div>`;
                container.append(newField);
            });
        });
    </script>
@endsection