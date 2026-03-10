{{-- <script src="{{asset('assets/vendors/tinymce/tinymce.min.js')}}"></script>
<script src="{{asset('assets/js/tinymce.js')}}"></script> --}}

<script>
    console.log('Resignation script loaded successfully!');
    
    // Check if jQuery is loaded
    if (typeof jQuery === 'undefined') {
        console.error('jQuery is not loaded');
    } else {
        console.log('jQuery is loaded:', jQuery.fn.jquery);
    }
    
    $('document').ready(function(){
        console.log('Document ready fired!');
        
        // Check if branch_id element exists
        const branchElement = $('#branch_id');
        if (branchElement.length === 0) {
            console.error('Branch dropdown not found');
        } else {
            console.log('Branch dropdown found:', branchElement);
            
            // Add simple change event test
            branchElement.on('change', function() {
                console.log('Branch changed to:', $(this).val());
                
                // Load departments when branch changes
                const branchId = $(this).val();
                if (branchId) {
                    loadDepartmentsSimple(branchId);
                }
            });
        }

        $("#branch_id").select2();
        $("#employee_id").select2();
        $("#department_id").select2();


        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('body').on('click', '.delete', function (event) {
            event.preventDefault();
            let title = $(this).data('title');
            let href = $(this).data('href');
            Swal.fire({
                title: '{{ __('index.delete_confirmation') }}',
                showDenyButton: true,
                confirmButtonText: `{{ __('index.yes') }}`,
                denyButtonText: `{{ __('index.no') }}`,
                padding:'10px 50px 10px 50px',
                allowOutsideClick: false
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = href;
                }
            })
        })

        tinymce.init({
            selector: '#tinymceExample',
            height: 200,
        });




        $('body').on('click', '.resignationStatusUpdate', function (event) {
            event.preventDefault();
            let url = $(this).data('href');
            let status = $(this).data('status');
            let reason = $(this).data('reason');

            $('.modal-title').html('Resignation Status Update');
            $('#updateResignationStatus').attr('action',url)
            $('#status').val(status)
            $('#admin_remark').val(reason)


            $('#statusUpdate').modal('show');
        });


        // Simple department loading function
        function loadDepartmentsSimple(branchId) {
            console.log('Loading departments for branch:', branchId);
            
            $.ajax({
                url: `/admin/departments/get-All-Departments/${branchId}`,
                method: 'GET',
                success: function(response) {
                    console.log('Departments response:', response);
                    
                    $('#department_id').empty().append('<option selected disabled>Select Department</option>');
                    
                    if (response.data && response.data.length > 0) {
                        response.data.forEach(function(dept) {
                            console.log('Adding department:', dept);
                            $('#department_id').append(`<option value="${dept.id}">${dept.dept_name}</option>`);
                        });
                        
                        // Check if departments were actually added
                        console.log('Department dropdown after loading:', $('#department_id'));
                        console.log('Department options after loading:', $('#department_id').find('option').length);
                        
                        // Re-initialize Select2 after adding options
                        $('#department_id').trigger('change.select2');
                        
                        console.log('Departments loaded successfully: ' + response.data.length + ' departments found');
                    } else {
                        $('#department_id').append('<option disabled>No departments found</option>');
                        console.log('No departments found for this branch');
                    }
                },
                error: function(error) {
                    console.error('Error loading departments:', error);
                    $('#department_id').append('<option disabled>Error loading departments</option>');
                }
            });
        }
        
        // Department change event - Simple approach that works
        $('#department_id').on('change', function() {
            const deptId = $(this).val();
            console.log('Department selected:', deptId);
            
            if (deptId && deptId !== 'Select Department') {
                console.log('Loading employees for department:', deptId);
                loadEmployeesSimple(deptId);
            }
        });
                
        // Simple employee loading function
        function loadEmployeesSimple(deptId) {
            console.log('=== EMPLOYEE LOADING START ===');
            console.log('Loading employees for department:', deptId);
            console.log('Department ID type:', typeof deptId);
            console.log('Department ID value:', deptId);
            
            const url = `/admin/employees/get-all-employees/${deptId}`;
            console.log('Employee loading URL:', url);
            
            $.ajax({
                url: url,
                method: 'GET',
                beforeSend: function() {
                    console.log('Sending AJAX request to:', url);
                },
                success: function(response) {
                    console.log('=== EMPLOYEE AJAX SUCCESS ===');
                    console.log('Raw response:', response);
                    console.log('Response type:', typeof response);
                    console.log('Response stringified:', JSON.stringify(response));
                    
                    if (response.data) {
                        console.log('Response.data:', response.data);
                        console.log('Response.data type:', typeof response.data);
                        console.log('Response.data isArray:', Array.isArray(response.data));
                        console.log('Response.data length:', response.data.length);
                    }
                    
                    $('#employee_id').empty().append('<option selected disabled>Select Employee</option>');
                    
                    // Check if response has data property
                    if (response && response.data && Array.isArray(response.data) && response.data.length > 0) {
                        console.log('Using response.data branch');
                        response.data.forEach(function(emp, index) {
                            console.log(`Adding employee ${index}:`, emp);
                            $('#employee_id').append(`<option value="${emp.id}">${emp.name}</option>`);
                        });
                        console.log('Employees loaded successfully: ' + response.data.length + ' employees found');
                    } else if (response && Array.isArray(response) && response.length > 0) {
                        console.log('Using direct array branch');
                        response.forEach(function(emp, index) {
                            console.log(`Adding employee ${index}:`, emp);
                            $('#employee_id').append(`<option value="${emp.id}">${emp.name}</option>`);
                        });
                        console.log('Employees loaded successfully: ' + response.length + ' employees found');
                    } else {
                        console.log('No employees found, response:', response);
                        console.log('Response keys:', response ? Object.keys(response) : 'null');
                        $('#employee_id').append('<option disabled>No employees found for this department. Response: ' + JSON.stringify(response));
                    }
                    console.log('=== EMPLOYEE AJAX END ===');
                },
                error: function(error) {
                    console.log('=== EMPLOYEE AJAX ERROR ===');
                    console.error('Error loading employees:', error);
                    console.error('Error status:', error.status);
                    console.error('Error statusText:', error.statusText);
                    console.error('Error responseText:', error.responseText);
                    console.error('Error responseJSON:', error.responseJSON);
                    console.log('Error loading employees: ' + (error.status || 'Unknown error') + ' - ' + (error.statusText || ''));
                    console.log('=== EMPLOYEE AJAX ERROR END ===');
                }
            });
        }

        // Disable Nepali DatePicker to avoid conflicts
        $('.nepaliDate').prop('disabled', true).attr('placeholder', 'Use regular date picker');

        console.log('Resignation form initialized successfully!');
    });

</script>
