@extends('layouts.master')

@section('title', __('index.resignation'))

@section('button')
    <a href="{{ route('admin.resignation.index') }}">
        <button class="btn btn-sm btn-primary"><i class="link-icon" data-feather="arrow-left"></i> {{ __('index.button_back') }}</button>
    </a>
@endsection

@section('main-content')

<div class="teamy-body-wrapper">
    @include('admin.resignation.common.breadcrumb')
    {{-- Top Header --}}
    <div class="teamy-top-header">
        <div>
            <h2>{{ __('index.resignation') }}</h2>
            <div class="header-info-row">
                <div class="header-info-item">
                    <span class="status-badge" style="background: #eef2ff; color: #6366f1;">
                        {{ isset($resignationDetail) ? __('index.edit') : __('index.create') }}
                    </span>
                </div>
                <div class="header-info-item">
                    <i class="fa fa-file-alt"></i> 
                    {{ isset($resignationDetail) ? ($resignationDetail->employee->name ?? 'Update') : 'Add New Resignation' }}
                </div>
            </div>
        </div>
    </div>

    @include('admin.section.flash_message')

    <form action="{{ isset($resignationDetail) ? route('admin.resignation.update', $resignationDetail->id) : route('admin.resignation.store') }}" 
          method="POST" 
          enctype="multipart/form-data" 
          id="resignationForm">
        @csrf
        @if(isset($resignationDetail))
            @method('PUT')
        @endif

        <div class="teamy-main-card">
            
            <div class="section-title-wrapper">
                <div class="section-icon">
                    <i class="fa {{ isset($resignationDetail) ? 'fa-edit' : 'fa-plus' }}"></i>
                </div>
                <div class="section-heading-text">
                    <h4>{{ isset($resignationDetail) ? __('index.edit') : __('index.create') }} Resignation Details</h4>
                    <p>Enter the employee resignation information and status</p>
                </div>
            </div>

            <div class="section-divider"></div>

            @include('admin.resignation.common.form')
            
        </div>

        {{-- Footer Actions --}}
        <div class="branch-footer-actions">
            <a href="{{ route('admin.resignation.index') }}" class="branch-back-btn">
                <i class="fa fa-arrow-left"></i>
                {{ __('index.button_back') }}
            </a>
            <button type="submit" class="btn btn-primary">
                {{ isset($resignationDetail) ? __('index.update') : __('index.create') }}
            </button>
        </div>

    </form>
</div>

@endsection

@section('scripts')
    @include('admin.resignation.common.scripts')
    
    <!-- Inline test function -->
    <script>
        function loadEmployeesForDepartment() {
            console.log('=== AUTO EMPLOYEE LOADING START ===');
            
            const deptDropdown = document.getElementById('department_id');
            console.log('Department dropdown:', deptDropdown);
            
            if (deptDropdown) {
                const deptId = deptDropdown.value;
                console.log('Selected department ID:', deptId);
                
                if (deptId && deptId !== 'Select Department') {
                    console.log('Auto-loading employees for department:', deptId);
                    
                    // Clear existing employees
                    const employeeDropdown = document.getElementById('employee_id');
                    if (employeeDropdown) {
                        employeeDropdown.innerHTML = '<option selected disabled>Loading employees...</option>';
                    }
                    
                    // Add 3-second delay before loading employees
                    setTimeout(function() {
                        // Load employees via AJAX
                        const xhr = new XMLHttpRequest();
                        xhr.open('GET', '/admin/employees/get-all-employees/' + deptId, true);
                        xhr.onreadystatechange = function() {
                            if (xhr.readyState === 4) {
                                if (xhr.status === 200) {
                                    console.log('=== AUTO LOAD SUCCESS ===');
                                    console.log('Response:', xhr.responseText);
                                    
                                    try {
                                        const response = JSON.parse(xhr.responseText);
                                        console.log('Parsed response:', response);
                                        
                                        if (response.data && response.data.length > 0) {
                                            // Add employees to dropdown
                                            response.data.forEach(function(emp) {
                                                const option = document.createElement('option');
                                                option.value = emp.id;
                                                option.textContent = emp.name;
                                                employeeDropdown.appendChild(option);
                                            });
                                            
                                            console.log('SUCCESS! Auto-loaded ' + response.data.length + ' employees for department ' + deptId);
                                        } else {
                                            console.log('No employees found for department ' + deptId);
                                            employeeDropdown.innerHTML = '<option selected disabled>Select Employee</option><option disabled>No employees found</option>';
                                        }
                                    } catch (e) {
                                        console.error('Error parsing response:', e);
                                        employeeDropdown.innerHTML = '<option selected disabled>Select Employee</option><option disabled>Error loading employees</option>';
                                    }
                                } else {
                                    console.log('=== AUTO LOAD ERROR ===');
                                    console.error('Error loading employees:', xhr.status);
                                    employeeDropdown.innerHTML = '<option selected disabled>Select Employee</option><option disabled>Error loading employees</option>';
                                }
                            }
                        };
                        xhr.send();
                    }, 3000); // 3-second delay
                } else {
                    console.log('No department selected');
                }
            } else {
                console.log('Department dropdown not found!');
            }
            
            console.log('=== AUTO EMPLOYEE LOADING END ===');
        }
        
        document.addEventListener('DOMContentLoaded', function() {
            const deptDropdown = document.getElementById('department_id');
            if (deptDropdown) {
                deptDropdown.addEventListener('change', loadEmployeesForDepartment);
            }
        });
    </script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof feather !== 'undefined') { feather.replace(); }
        });
    </script>
@endsection