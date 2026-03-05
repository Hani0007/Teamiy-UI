<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>

    $(document).ready(function () {


        $("#team_meeting").select2({
            placeholder: "{{ __('index.select_meeting_participants') }}"
        });

        $("#department_id").select2({
                placeholder: "{{ __('index.select_department') }}"
            });


        $('.meetingDate').nepaliDatePicker({
            language: "english",
            dateFormat: "mm/dd/yyyy",
            ndpYear: true,
            ndpMonth: true,
            ndpYearCount: 20,
            disableAfter: "2089-12-30",
            onChange: function(date) {
                // Validate date range
                const currentYear = new Date().getFullYear();
                const selectedYear = date.getFullYear();
                
                if (selectedYear < currentYear || selectedYear > 2089) {
                    alert('Please select a valid date between ' + currentYear + ' and 2089');
                    $(this).val('');
                    return false;
                }
                
                // Convert to m/d/Y format for backend (single digit month/day when possible)
                const month = date.getMonth() + 1;
                const day = date.getDate();
                const year = date.getFullYear();
                const backendFormat = month + '/' + day + '/' + year;
                
                // Update input value to backend format
                $(this).val(backendFormat);
                console.log('Date picker changed - set to:', backendFormat);
            }
        }).on('change', function() {
            // Also handle manual input changes
            console.log('Manual date change detected:', $(this).val());
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('.delete').click(function (event) {
            event.preventDefault();
            let href = $(this).data('href');
            Swal.fire({
                title: `{{__('index.delete_team_meeting_confirmation')}}`,
                showDenyButton: true,
                confirmButtonText: `{{__('index.yes')}}`,
                denyButtonText: `{{__('index.no')}}`,
                padding: '10px 50px 10px 50px',
                allowOutsideClick: false
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = href;
                }
            })
        })

        $('.removeImage').click(function (event) {
            event.preventDefault();
            let href = $(this).data('href');
            Swal.fire({
                title: `{{ __('index.image_delete_confirmation') }}`,
                showDenyButton: true,
                confirmButtonText: `{{__('index.yes')}}`,
                denyButtonText: `{{__('index.no')}}`,
                padding: '10px 50px 10px 50px',
                allowOutsideClick: false
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = href;
                }
            })
        });

        $('body').on('click', '.showMeetingDescription', function (event) {
            event.preventDefault();

            let url = $(this).data('href');

            $.get(url, function (data) {
                $('.meetingTitle').html('Meeting Detail');
                $('.title').text(data.data.title);
                $('.date').text(data.data.meeting_date);
                $('.time').text(data.data.time);
                $('.venue').text(data.data.venue);
                $('.publish_date').text(data.data.meeting_published_at);
                $('.description').text(data.data.description);
                $('.creator').text(data.data.creator);
                $('.image').attr('src', data.data.image);

                $('#meetingDetail').modal('show');
            })
        });

        $('.reset').click(function (event) {
            event.preventDefault();
            $('#participator').val('');
            $('.fromDate').val('');
            $('.toDate').val('');
        });

    });

    $(document).ready(function () {

        const isAdmin = {{ auth('admin')->check() ? 'true' : 'false' }};
        const defaultBranchId = {{ auth()->user()->branch_id ?? 'null' }};
        const branchId = "{{ $filterParameters['branch_id'] ?? null }}";

        // Ensure filter parameters are arrays and normalize to strings
        const filterDepartmentIds = Array.isArray(JSON.parse('{!! json_encode($filterParameters['department_id'] ?? []) !!}'))
            ? JSON.parse('{!! json_encode($filterParameters['department_id'] ?? []) !!}').map(String)
            : [String(JSON.parse('{!! json_encode($filterParameters['department_id'] ?? []) !!}'))].filter(Boolean);
        const filterEmployeeIds = Array.isArray(JSON.parse('{!! json_encode($filterParameters['employee_id'] ?? []) !!}'))
            ? JSON.parse('{!! json_encode($filterParameters['participator'] ?? []) !!}').map(String)
            : [String(JSON.parse('{!! json_encode($filterParameters['participator'] ?? []) !!}'))].filter(Boolean);

        const formDepartmentIds = {!! isset($departmentIds) ? json_encode($departmentIds) : '[]' !!}.map(String);
        const formEmployeeIds = {!! isset($participatorIds) ? json_encode($participatorIds) : '[]' !!}.map(String);

        const departmentIds = filterDepartmentIds.length > 0 ? filterDepartmentIds : formDepartmentIds;
        let employeeIds = filterEmployeeIds.length > 0 ? filterEmployeeIds : formEmployeeIds; // Make this mutable
        // Common function to load departments
        const preloadDepartments = async (selectedBranchId) => {
            if (!selectedBranchId) return;

            try {
                $('#department_id').empty();
                const response = await $.ajax({
                    type: 'GET',
                    url: `{{ url('admin/departments/get-All-Departments') }}/${selectedBranchId}`,
                });

                if (!response || !response.data || response.data.length === 0) {
                    $('#department_id').append('<option disabled>{{ __("index.no_departments_found") }}</option>');
                    return;
                }

                response.data.forEach(data => {
                    const isSelected = departmentIds.includes(String(data.id));
                    $('#department_id').append(`
                    <option value="${data.id}" ${isSelected ? "selected" : ""}>
                        ${data.dept_name}
                    </option>
                `);
                });

                $('#department_id').trigger('change');
            } catch (error) {
                $('#department_id').append('<option disabled>{{ __("index.error_loading_departments") }}</option>');
            }
        };

        // Common function to load and merge employees
        const preloadEmployees = async () => {
            const selectedDepartments = $('#department_id').val() || [];
            if (selectedDepartments.length === 0) {
                $('#team_meeting').empty().append('<option disabled>{{ __("index.no_employees_found") }}</option>');
                return;
            }

            // Store current employee selections before clearing
            const currentEmployeeSelections = $('#team_meeting').val() || [];

            try {
                const response = await fetch('{{ route('admin.employees.fetchByDepartment') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({ department_ids: selectedDepartments }),
                });

                const data = await response.json();
                $('#team_meeting').empty(); // Clear options every time

                if (!data || data.length === 0) {
                    $('#team_meeting').append('<option disabled>{{ __("index.no_employees_found") }}</option>');
                    return;
                }

                // Repopulate with employees, preserving valid selections
                data.forEach(employee => {
                    const employeeIdStr = String(employee.id);
                    const isSelected = currentEmployeeSelections.includes(employeeIdStr) || employeeIds.includes(employeeIdStr);
                    $('#team_meeting').append(`
                    <option value="${employee.id}" ${isSelected ? "selected" : ""}>
                        ${employee.name}
                    </option>
                `);
                });

                // Update employeeIds to reflect current selections
                employeeIds = $('#team_meeting').val() || [];
            } catch (error) {
                $('#team_meeting').append('<option disabled>{{ __("index.error_loading_employees") }}</option>');
            }
        };

        const initializeDropdowns = async () => {
            let selectedBranchId;

            if (isAdmin) {
                selectedBranchId = $('#branch_id').val() || branchId || defaultBranchId;
                $('#branch_id').on('change', async () => {
                    const newBranchId = $('#branch_id').val();
                    await preloadDepartments(newBranchId);
                    await preloadEmployees();
                });

                if (selectedBranchId) {
                    await preloadDepartments(selectedBranchId);
                    await preloadEmployees();
                }
            } else {
                selectedBranchId = defaultBranchId;
                if (selectedBranchId) {
                    await preloadDepartments(selectedBranchId);
                    await preloadEmployees();
                }
            }

            $('#department_id').on('change', preloadEmployees);
        };

        // Initialize everything
        initializeDropdowns();
        
        // Add form validation - try multiple selectors
        $(document).on('submit', 'form', function(e) {
            console.log('Form submission detected!');
            
            const meetingDate = $('.meetingDate').val();
            const meetingDateInput = $('.meetingDate');
            
            // Debug: log current value
            console.log('=== DATE DEBUG ===');
            console.log('Original meeting date value:', meetingDate);
            console.log('Type of meetingDate:', typeof meetingDate);
            console.log('Length of meetingDate:', meetingDate ? meetingDate.length : 'null');
            
            // Validate date format and range
            if (meetingDate) {
                // Convert mm/dd/yyyy to m/d/Y format for backend
                const dateParts = meetingDate.split('/');
                console.log('Date parts after split:', dateParts);
                
                if (dateParts.length === 3) {
                    let month = parseInt(dateParts[0]);
                    let day = parseInt(dateParts[1]);
                    let year = parseInt(dateParts[2]);
                    
                    console.log('Parsed - Month:', month, 'Day:', day, 'Year:', year);
                    
                    // Validate date range
                    const currentYear = new Date().getFullYear();
                    console.log('Current year:', currentYear);
                    
                    if (year < currentYear || year > 2089) {
                        e.preventDefault();
                        alert('Please select a valid date between ' + currentYear + ' and 2089');
                        meetingDateInput.focus();
                        return false;
                    }
                    
                    // Format as m/d/Y for backend
                    const backendFormat = month + '/' + day + '/' + year;
                    console.log('Backend format before setting:', backendFormat);
                    console.log('Type of backendFormat:', typeof backendFormat);
                    
                    meetingDateInput.val(backendFormat);
                    console.log('Input value after setting:', meetingDateInput.val());
                    console.log('Final formatted for backend:', backendFormat);
                } else {
                    console.log('Invalid date format - not 3 parts after split');
                    // Check if date contains invalid year like 275760
                    const yearMatch = meetingDate.match(/(\d{4})/);
                    if (yearMatch) {
                        const year = parseInt(yearMatch[1]);
                        const currentYear = new Date().getFullYear();
                        
                        if (year < currentYear || year > 2089) {
                            e.preventDefault();
                            alert('Please select a valid date between ' + currentYear + ' and 2089');
                            meetingDateInput.focus();
                            return false;
                        }
                    }
                    
                    // Check for invalid date patterns
                    if (meetingDate.includes('275760') || meetingDate.length > 10) {
                        e.preventDefault();
                        alert('Please enter a valid date in mm/dd/yyyy format');
                        meetingDateInput.focus();
                        return false;
                    }
                }
            } else {
                console.log('Meeting date is empty or null');
            }
            console.log('=== END DATE DEBUG ===');
        });
        
        // Also try button click event as backup
        $(document).on('click', 'button[type="submit"]', function(e) {
            console.log('Submit button clicked!');
            const form = $(this).closest('form');
            const meetingDate = form.find('.meetingDate').val();
            console.log('Button click - meeting date:', meetingDate);
        });
    });


</script>
