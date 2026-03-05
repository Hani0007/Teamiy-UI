<script src="{{ asset('assets/vendors/tinymce/tinymce.min.js') }}"></script>
<script src="{{ asset('assets/js/tinymce.js') }}"></script>
<script src="{{ asset('assets/jquery-validation/jquery.validate.min.js') }}"></script>
<script src="{{ asset('assets/jquery-validation/additional-methods.min.js') }}"></script>

<script>
    $(document).ready(function () {

        $("#department").select2({});
        $("#branch").select2({});
        $("#post").select2({});
        $("#supervisor").select2({});
        $("#employment_type").select2({});
        $("#officeTime").select2({});
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('.changePassword').click(function (event) {
            event.preventDefault();
            let url = $(this).data('href');
            $('.modal-title').html('{{ __('index.user_change_password') }}');
            $('#changePassword').attr('action', url);
            $('#statusUpdate').modal('show');
        });

        $('.toggleStatus').change(function (event) {
            event.preventDefault();
            let status = $(this).prop('checked') == true ? 1 : 0;
            let href = $(this).attr('href');

            Swal.fire({
                title: '{{ __('index.confirm_change_status') }}',
                showDenyButton: true,
                confirmButtonText: `{{ __('index.yes') }}`,
                denyButtonText: `{{ __('index.no') }}`,
                padding: '10px 50px 10px 50px',
                allowOutsideClick: false
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = href;
                } else if (result.isDenied) {
                    (status === 0) ? $(this).prop('checked', true) : $(this).prop('checked', false)
                }
            })
        });

        $('.toggleHolidayCheckIn').change(function (event) {
            event.preventDefault();
            let status = $(this).prop('checked') == true ? 1 : 0;
            let href = $(this).attr('href');

            Swal.fire({
                title: '{{ __('index.confirm_change_holiday_checkin') }}',
                showDenyButton: true,
                confirmButtonText: `{{ __('index.yes') }}`,
                denyButtonText: `{{ __('index.no') }}`,
                padding: '10px 50px 10px 50px',
                allowOutsideClick: false
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = href;
                } else if (result.isDenied) {
                    (status === 0) ? $(this).prop('checked', true) : $(this).prop('checked', false)
                }
            })
        });

        $('.deleteEmployee').click(function (event) {
            event.preventDefault();
            let href = $(this).data('href');
            Swal.fire({
                title: '{{ __('index.confirm_delete_employee') }}',
                showDenyButton: true,
                confirmButtonText: `{{ __('index.yes') }}`,
                denyButtonText: `{{ __('index.no') }}`,
                padding: '10px 50px 10px 50px',
                allowOutsideClick: false
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = href;
                }
            })
        });

        $('.forceLogOut').click(function (event) {
            event.preventDefault();
            let href = $(this).data('href');
            Swal.fire({
                title: '{{ __('index.confirm_force_logout') }}',
                showDenyButton: true,
                confirmButtonText: `{{ __('index.yes') }}`,
                denyButtonText: `{{ __('index.no') }}`,
                padding: '10px 50px 10px 50px',
                allowOutsideClick: false
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = href;
                }
            })
        });

        $('.changeWorkPlace').click(function (event) {
            event.preventDefault();
            let href = $(this).data('href');
            Swal.fire({
                title: '{{ __('index.confirm_change_workplace') }}',
                showDenyButton: true,
                confirmButtonText: `{{ __('index.yes') }}`,
                denyButtonText: `{{ __('index.no') }}`,
                padding: '10px 50px 10px 50px',
                allowOutsideClick: false
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = href;
                }
            })
        });




        $('.joiningDate').nepaliDatePicker({
            language: "english",
            dateFormat: "YYYY-MM-DD",
            ndpYear: true,
            ndpMonth: true,
            ndpYearCount: 20,
            readOnlyInput: true,
            disableAfter: "2089-12-30",
        });
        $('.birthDate').nepaliDatePicker({
            language: "english",
            dateFormat: "YYYY-MM-DD",
            ndpYear: true,
            ndpMonth: true,
            ndpYearCount: 50,
            readOnlyInput: true,
            disableAfter: "2089-12-30",
        });


    });
    $('#export_employee').on('click', function (e) {
        e.preventDefault();
        let route = $(this).data('href');

        // Create a form data object with all current filter values
        let filtered_params = {
            employee_name: $('#employeeName').val(),
            email: $('#email').val(),
            phone: $('#phone').val(),
            branch_id: $('#branch').val(),
            department_id: $('#department').val(),
            action: 'export'  // This should match what the controller is checking for
        };

        let queryString = $.param(filtered_params);
        let url = route + '?' + queryString;
        window.open(url, '_blank');
    });
    function getEmployeeFilterParam() {
        return {
            employee_name: $('#employeeName').val(),
            email: $('#email').val(),
            phone: $('#phone').val(),
            branch_id: $('#branch').val(),
            department_id: $('#department').val()
        };
    }


    function capitalize(str) {
        strVal = '';
        str = str.split(' ');
        for (let chr = 0; chr < str.length; chr++) {
            strVal += str[chr].substring(0, 1).toUpperCase() + str[chr].substring(1, str[chr].length) + ' ';
        }
        return strVal;
    }

    $('#employeeDetail').validate({
        rules: {
            name: { required: true },
            address: { required: true },
            email: { required: true },
            role_id: { required: true },
            username: { required: true },
        },
        messages: {
            name: {
                required: "{{ __('index.enter_name') }}",
            },
            address: {
                required: "{{ __('index.enter_address') }}"
            },
            email: {
                required: "{{ __('index.enter_valid_email') }}"
            },
            role_id: {
                required: "{{ __('index.select_role') }}"
            },
            username: {
                required: "{{ __('index.enter_username') }}"
            }
        },
        errorElement: 'span',
        errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            element.closest('div').append(error);
        },
        highlight: function (element) {
            $(element).addClass('is-invalid');
            $(element).removeClass('is-valid');
            $(element).siblings().addClass("text-danger").removeClass("text-success");
            $(element).siblings().find('span .input-group-text').addClass("bg-danger").removeClass("bg-success");
        },
        unhighlight: function (element) {
            $(element).removeClass('is-invalid');
            $(element).addClass('is-valid');
            $(element).siblings().addClass("text-success").removeClass("text-danger");
            $(element).find('span .input-group-prepend').addClass("bg-success").removeClass("bg-danger");
            $(element).siblings().find('span .input-group-text').addClass("bg-success").removeClass("bg-danger");
        }
    });

    $('#avatar').change(function () {
        const input = document.getElementById('avatar');
        const preview = document.getElementById('image-preview');
        const file = input.files[0];
        const reader = new FileReader();
        reader.addEventListener('load', function () {
            preview.src = reader.result;
        }, false);
        if (file) {
            reader.readAsDataURL(file);
        }

    });

    document.addEventListener('DOMContentLoaded', function() {
        const leaveForm = document.getElementById('employeeDetail');
        const leaveAllocatedInput = document.getElementById('leave_allocated');
        const leaveDaysInputs = document.querySelectorAll('.leave-days');
        const errorMessage = document.getElementById('error-message');

        // Check if all required elements exist
        if (!leaveAllocatedInput) {
            return; // Exit early if element doesn't exist
        }

        if (!errorMessage) {
            // Continue but log the issue
        }

        function displayError(element, message) {
            if (!element) return; // Skip if element doesn't exist
            element.classList.add('text-danger');
            element.textContent = message;
            element.style.display = 'block';
        }

        function hideError(element) {
            if (!element) return; // Skip if element doesn't exist
            element.classList.remove('text-danger');
            element.style.display = 'none';
        }

        function validateForm(event) {
            let totalDays = 0;
            let isValid = true;

            leaveDaysInputs.forEach(input => {
                totalDays += parseInt(input.value) || 0;
            });

            if (parseInt(leaveAllocatedInput.value) < totalDays) {
                displayError(errorMessage, 'Allocated leave cannot be less than the total leave days.');
                leaveAllocatedInput.classList.add('text-danger');
                isValid = false;
            } else {
                hideError(errorMessage);
                leaveAllocatedInput.classList.remove('text-danger');
            }

            leaveDaysInputs.forEach(input => {
                if (!input.value && parseInt(leaveAllocatedInput.value) > 0) {
                    displayError(input.nextElementSibling, 'This field is required.');
                    isValid = false;
                } else {
                    hideError(input.nextElementSibling);
                }
            });

            if (!isValid && event) {
                event.preventDefault();
            }
        }

        function setRequiredAttribute() {
            const leaveAllocatedValue = parseInt(leaveAllocatedInput.value);
            leaveDaysInputs.forEach(input => {
                if (leaveAllocatedValue > 0 && !input.value) {
                    displayError(input.nextElementSibling, 'This field is required.');
                } else {
                    hideError(input.nextElementSibling);
                }
            });
        }

        leaveAllocatedInput.addEventListener('input', setRequiredAttribute);

        if (leaveForm) {
            leaveForm.addEventListener('submit', validateForm);
        } else {
            console.error("Element with ID 'employeeDetail' not found");
        }

        setRequiredAttribute();
    });

    document.addEventListener('DOMContentLoaded', function() {
        const leaveDaysInputs = document.querySelectorAll('.leave-days');
        const isActiveCheckboxes = document.querySelectorAll('.is-active-checkbox');

        leaveDaysInputs.forEach((input, index) => {
            input.addEventListener('input', function() {
                const isActiveCheckbox = isActiveCheckboxes[index];
                if (input.value === '') {
                    isActiveCheckbox.checked = false;
                } else {
                    input.classList.remove('text-danger');  // Clear error when input is filled
                    input.nextElementSibling.style.display = 'none';  // Ensure error message is hidden
                }
            });
        });
    });




    // branch wise department, office_time etc
    $(document).ready(function () {
        const loadDepartmentsAndOfficeTime = async () => {
            const isAdmin = {{ auth('admin')->check() ? 'true' : 'false' }};
            const defaultBranchId = {{ auth()->user()->branch_id ?? 'null' }};
            const selectedBranchId = isAdmin ? $('#branch').val() : defaultBranchId;
            let departmentId = "{{ $userDetail->department_id ?? $filterParameters['department_id'] ?? old('department_id') }}";
            let officeTimeId = "{{ isset($userDetail) ? $userDetail['office_time_id'] : old('office_time_id') }}";

            if (!selectedBranchId) return;

            try {
                const response = await $.ajax({
                    type: 'GET',
                    url: `{{ url('admin/transfer/get-user-transfer-branch-data') }}/${selectedBranchId}`,
                });

                $('#department').empty(); // Changed selector to #department
                $('#officeTime').empty(); // Added for office time

                // Departments
                if (!departmentId) {
                    $('#department').append('<option disabled selected>{{ __('index.select_department') }}</option>');
                }
                if (response.departments && response.departments.length > 0) {
                    response.departments.forEach(department => {
                        $('#department').append(`<option ${department.id == departmentId ? 'selected' : ''} value="${department.id}">${department.dept_name}</option>`);
                    });
                } else {
                    // $('#department').append('<option disabled>{{ __("index.no_department_found") }}</option>');
                }

                // Office Times
                if (!officeTimeId) {
                    $('#officeTime').append('<option value="" selected>{{ __('index.select_office_time') }}</option>');
                }
                if (response.officeTimes && response.officeTimes.length > 0) {
                    response.officeTimes.forEach(shift => {
                        $('#officeTime').append(`<option ${shift.id == officeTimeId ? 'selected' : ''} value="${shift.id}">${shift.opening_time} - ${shift.closing_time}</option>`);
                    });
                } else {
                    $('#officeTime').append('<option disabled>{{ __("index.office_time_not_found") }}</option>');
                }
            } catch (error) {
                $('#department').append('<option disabled>{{ __("index.error_loading_departments") }}</option>');
                $('#officeTime').append('<option disabled>{{ __("index.error_loading_office_times") }}</option>');
            }
        };

        const loadSupervisorAndPosts = async () => {
            const selectedDepartmentId = $('#department').val(); // Changed selector to #department
            let supervisorId = "{{ isset($userDetail) ? $userDetail['supervisor_id'] : old('supervisor_id') }}";
            let employeeId = "{{ isset($userDetail) ? $userDetail['id'] : ''  }}";
            let postId = "{{ isset($userDetail) ? $userDetail['post_id'] : old('post_id') }}";

            if (!selectedDepartmentId) return;

            try {
                const response = await fetch(`{{ url('admin/transfer/get-user-transfer-department-data') }}/${selectedDepartmentId}`, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    }
                });

                let data = await response.json();

                $('#supervisor').empty(); // Changed selector to #supervisor
                $('#post').empty(); // Changed selector to #post

                // Supervisors
                if (!supervisorId) {
                    $('#supervisor').append('<option value="" selected>{{ __('index.select_supervisor') }}</option>');
                }
                if (data.supervisors && data.supervisors.length > 0) {
                    data.supervisors.forEach(user => {
                        if (employeeId != user.id){
                            $('#supervisor').append(`<option ${user.id == supervisorId ? 'selected' : ''} value="${user.id}">${user.name}</option>`);

                        }
                    });
                } else {
                    $('#supervisor').append('<option disabled>{{ __("index.no_employees_found") }}</option>');
                }

                // Posts
                if (!postId) {
                    $('#post').append('<option value="" selected>{{ __('index.select_option') }}</option>');
                }
                if (data.posts && data.posts.length > 0) {
                    data.posts.forEach(post => {
                        $('#post').append(`<option ${post.id == postId ? 'selected' : ''} value="${post.id}">${post.post_name}</option>`);
                    });
                } else {
                    // $('#post').append('<option disabled>{{ __("no_designation_found") }}</option>');
                }
            } catch (error) {
                $('#supervisor').append('<option disabled>{{ __("index.error_loading_employees") }}</option>');
                $('#post').append('<option disabled>{{ __("index.error_loading_posts") }}</option>');
            }
        };

        const isAdmin = {{ auth('admin')->check() ? 'true' : 'false' }};
        if (isAdmin) {
            $('#branch').on('change', loadDepartmentsAndOfficeTime);
            $('#branch').trigger('change');
        } else {
            loadDepartmentsAndOfficeTime();
        }
        // Load employees and posts when department is selected
        $('#department').change(loadSupervisorAndPosts).trigger('change'); // Changed selector and added trigger
    });


    // load leave types as per gender
    $(document).ready(function () {
        $('#gender').on('change', function () {
            const gender = $(this).val();
            if (gender) {
                $.ajax({
                    url: '{{ url('admin/leaves/get-gender-leave-types') }}/' + gender,
                    method: 'GET',
                    success: function (response) {
                        const leaveTypes = response.leveTypes; // Note: typo in 'leveTypes' from controller
                        let tableBody = '';
                        leaveTypes.forEach((leaveType, index) => {
                            tableBody += `
                                <tr>
                                    <td>
                                        ${capitalize(leaveType.name)}
                                        <input type="hidden" name="leave_type_id[${index}]" value="${leaveType.id}">
                                    </td>
                                    <td>
                                        <input type="number" min="0" class="form-control leave-days"
                                               value=""
                                               oninput="validity.valid||(value='');"
                                               placeholder="{{ __('index.total_leave_days') }}"
                                               name="days[${index}]">
                                        <span class="error-message" style="display: none; color: red;">{{ __('index.required_field') }}.</span>
                                    </td>
                                    <td>
                                        <input class="me-1 is-active-checkbox" type="checkbox"
                                               name="is_active[${index}]" value="1">{{ __('index.is_active') }}
                            </td>
                        </tr>`;
                        });
                        $('#leave-types-table').html(tableBody);
                    },
                    error: function (xhr) {
                        console.error('Error fetching leave types:', xhr.responseJSON.message);
                        $('#leave-types-table').html('<tr><td colspan="3">Error loading leave types</td></tr>');
                    }
                });
            } else {
                $('#leave-types-table').html('');
            }
        });

        // Trigger change event on page load if gender is pre-selected
        if ($('#gender').val()) {
            $('#gender').trigger('change');
        }

        // Password visibility toggle
        $('#togglePassword').on('click', function () {
            const passwordField = $('#password');
            const type = passwordField.attr('type') === 'password' ? 'text' : 'password';
            passwordField.attr('type', type);
            
            // Toggle eye icon: when password is visible (text), show eye (no line); when hidden (password), show eye-off (with line)
            const button = $(this);
            const icon = button.find('i');
            
            if (type === 'text') {
                // Show eye icon (password visible)
                button.html('<i class="feather" data-feather="eye" style="width: 18px; height: 18px;"></i>');
            } else {
                // Show eye-off icon (password hidden)
                button.html('<i class="feather" data-feather="eye-off" style="width: 18px; height: 18px;"></i>');
            }
            // Re-initialize feather icons
            feather.replace();
        });
    });
</script>
