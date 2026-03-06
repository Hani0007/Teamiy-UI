@php use App\Models\Asset; @endphp
@php use App\Helpers\AppHelper; @endphp
@extends('layouts.master')

@section('title', __('index.assets'))

@section('main-content')
<section class="content" style="padding: 10px 20px;">
    @include('admin.section.flash_message')

    {{-- Header Section --}}
    <div class="d-flex align-items-center justify-content-between mb-5 flex-wrap gap-4">
        <div class="page-identity">
            <h2 style="color: #057db0;">
                {{ __('index.assets') }}
            </h2>
            <p style="color: #94a3b8; font-weight: 500; font-size: 12px;">
                <i data-feather="box" style="width: 14px; vertical-align: middle;"></i> Asset Inventory Management
            </p>
        </div>
        
        @can('create_assets')
            <a href="{{ route('admin.assets.create')}}" style="text-decoration: none;">
                <button class="btn-premium-add">
                    <i data-feather="plus" style="width: 20px;"></i>
                    <span>{{ __('index.add_asset') }}</span>
                </button>
            </a>
        @endcan
    </div>

    {{-- Grid Section --}}
    <div class="row g-4 justify-content-start">
        @forelse($assetLists as $key => $value)
            <div class="col-xxl-3 col-xl-4 col-lg-6 col-md-6">
                <div class="branch-master-card">
                    <div class="card-glossy-header">
                        <div class="header-overlay"></div>
                        <div class="d-flex justify-content-between align-items-start position-relative" style="z-index: 2;">
                            <div class="branch-icon-square">
                                <i data-feather="package"></i>
                            </div>
                        </div>
                        <h4 class="branch-name-display">{{ucfirst($value->name)}}</h4>
                        <span class="branch-ref-pill">Asset ID: #{{$value->id}}</span>
                    </div>

                    <div class="card-white-body">
                        <div class="info-listing">
                            
                            {{-- Availability Field (Aligned with other columns) --}}
                            <div class="info-item-box">
                                <div class="icon-circle"><i data-feather="check-circle"></i></div>
                                <div class="text-content">
                                    <small>{{ __('index.is_available') }}</small>
                                    <p>
                                                                                    {{($value->is_available) == 1 ? __('index.yes_available'): __('index.notavailable')}}

                                    </p>
                                </div>
                            </div>

                            {{-- Asset Type (With Clickable Link) --}}
                            <div class="info-item-box">
                                <div class="icon-circle"><i data-feather="tag"></i></div>
                                <div class="text-content">
                                    <small>{{ __('index.type') }}</small>
                                    <p>
                                        <a href="{{ route('admin.asset-types.show', $value->type->id) }}" style="color: #057db0; text-decoration: none; font-weight: 600;">
                                            {{ucfirst($value->type->name)}}
                                        </a>
                                    </p>
                                </div>
                            </div>

                            <div class="info-item-box">
                                <div class="icon-circle"><i data-feather="activity"></i></div>
                                <div class="text-content">
                                    <small>{{ __('index.is_working') }}</small>
                                    <p>{{ucfirst($value->is_working)}}</p>
                                </div>
                            </div>

                            <div class="info-item-box">
                                <div class="icon-circle"><i data-feather="user"></i></div>
                                <div class="text-content">
                                    <small>ASSIGNED TO</small>
                                    <p>
                                        @if(isset($value->latestAssignment) && is_null($value->latestAssignment->returned_date))
                                            {{ $value?->latestAssignment?->user?->name }}
                                        @else
                                            <span class="text-muted">Unassigned</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="stats-footer-box">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="emp-group">
                                    @if(isset($value->latestAssignment) && $value->latestAssignment->status === 'assigned')
                                        <button class="btn btn-sm btn-outline-warning py-1 px-3" data-bs-toggle="modal" data-bs-target="#return_{{ $key }}" style="border-radius: 20px; font-size: 12px;">
                                            Return
                                        </button>
                                    @else
                                        <button class="btn btn-sm btn-outline-info py-1 px-3" data-bs-toggle="modal" data-bs-target="#exampleModal_{{ $key }}" style="border-radius: 20px; font-size: 12px;">
                                            Assign
                                        </button>
                                    @endif
                                </div>

                                <div class="action-dock">
                                    @can('show_asset')
                                        <a href="javascript:void(0)" class="btn-action edit" title="{{ __('index.asset_detail') }}"
                                           onclick="showAssetDetails('{{ route('admin.assets.show',$value->id) }}')">
                                            <i data-feather="eye" style="width:16px; height:16px;"></i>
                                        </a>
                                    @endcan

                                    @can('edit_assets')
                                        <a href="{{route('admin.assets.edit',$value->id)}}" class="btn-action edit" title="{{ __('index.edit') }}">
                                            <i data-feather="edit-3"></i>
                                        </a>
                                    @endcan

                                    @can('delete_assets')
                                        <a class="btn-action delete delete cursor-pointer"
                                           data-title="{{$value->name}} Asset Detail"
                                           data-href="{{route('admin.assets.delete',$value->id)}}"
                                           title="{{ __('index.delete') }}">
                                            <i data-feather="trash-2"></i>
                                        </a>
                                    @endcan
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Assign Asset Modal --}}
            <div class="modal fade" id="exampleModal_{{ $key }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header" style="background-color: #057db0; color: white;">
                            <h5 class="modal-title text-white">Assign Asset</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <form method="POST" action="{{ route('admin.assign.asset') }}">
                            @csrf
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label>Branch</label>
                                        <select class="form-control" name="branch_id">
                                            <option value="{{ $value->branch_id }}" selected>{{ $value?->branch?->name }}</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label>Departments</label>
                                        <select class="form-control department-select" data-key="{{ $key }}" data-asset="{{ $value->id }}" name="department_id">
                                            <option selected disabled>Select</option>
                                            @foreach ($value->branch->departments as $department)
                                                <option value="{{ $department->id }}">{{ $department->dept_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label>Employees</label>
                                        <select class="form-control employees-select" name="user_id">
                                            <option disabled selected>Select Employee</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label>Assign Date</label>
                                        <input type="date" name="assigned_date" class="form-control" />
                                    </div>
                                    <input type="hidden" name="status" value="assigned" />
                                    <input type="hidden" name="asset_id" value="{{ $value?->id }}" />
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Assign</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Return Asset Modal (6 Fields: 4 Visible, 2 Hidden) --}}
            <div class="modal fade" id="return_{{ $key }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header" style="background-color: #057db0; color: white;">
                            <h5 class="modal-title text-white">Return Asset</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>

                        <form method="POST" action="{{ route('admin.return.asset') }}">
                            @csrf
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label>Branch</label>
                                        <select class="form-control" readonly>
                                            <option value="{{ $value?->latestAssignment?->branch_id }}" selected>{{ $value?->latestAssignment?->branch?->name }}</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label>Departments</label>
                                        <select class="form-control" readonly>
                                            <option value="{{ $value?->latestAssignment?->department_id }}" selected>{{ $value?->latestAssignment?->department?->dept_name }}</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label>Employees</label>
                                        <select class="form-control" readonly>
                                            <option value="{{ $value?->latestAssignment?->user_id }}" selected>{{ $value?->latestAssignment?->user?->name }}</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label>Assign Date</label>
                                        <input type="date" class="form-control" value="{{ \Carbon\Carbon::parse($value?->latestAssignment?->assigned_date)->format('Y-m-d') }}" readonly>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label>Return Date</label>
                                        <input type="date" name="returned_date" class="form-control" required />
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label>Return Condition</label>
                                        <select class="form-control" name="return_condition" required>
                                            <option selected disabled>Select</option>
                                            <option value="working">Working</option>
                                            <option value="non-working">Non-Working</option>
                                            <option value="maintenance">Maintenance</option>
                                        </select>
                                    </div>

                                    {{-- Hidden Fields --}}
                                    <input type="hidden" name="status" value="returned" />
                                    <input type="hidden" name="asset_assigned_id" value="{{ $value?->latestAssignment?->id }}" />
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Return</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        @empty
            <div class="col-12 text-center py-5">
                <div class="empty-state">
                    <i data-feather="info" style="width: 50px; color: #cbd5e1;"></i>
                    <p class="text-muted mt-3">{{ __('index.no_records_found') }}</p>
                </div>
            </div>
        @endforelse
    </div>
</section>

@include('admin.assetManagement.assetDetail.common.assignment')
@include('admin.assetManagement.assetDetail.show')
@endsection

@section('scripts')
<style>
    /* Global style for Asset Detail Modal Header (Loaded via Ajax) */
    #assetDetail .modal-header {
        background-color: #057db0 !important;
        color: white !important;
        text-align: left !important;
        display: flex;
        justify-content: space-between;
    }
    #assetDetail .modal-title { color: white !important; }
    #assetDetail .btn-close { filter: invert(1) grayscale(100%) brightness(200%); }
</style>

<script>
    function showAssetDetails(url) {
        $.get(url, function (response) {
            if (response && response.data) {
                const data = response.data;
                var daysUsed = data.used_for;
                $('.assetTitle').html('Asset Detail');
                $('.name').text(data.name);
                $('.type').text(data.assetType);
                $('.asset_code').text(data.asset_code);
                $('.asset_serial_no').text(data.asset_serial_no);
                $('.is_working').text(data.is_working);
                $('.purchased_date').text(data.purchased_date);
                $('.is_available').text(data.is_available);
                $('.note').text(data.note);
                
                if (daysUsed > 0) {
                    $('.used_for').text(daysUsed+ ' days');
                    $('.used_for').parent().show();
                } else {
                    $('.used_for').parent().hide();
                }

                if (data.image) {
                    $('.image').attr('src', data.image).parent().show();
                } else {
                    $('.image').parent().hide();
                }

                const modal = new bootstrap.Modal(document.getElementById('assetDetail'));
                modal.show();
            }
        });
    }

    $(document).ready(function () {
        $(document).on('change', '.department-select', function () {
            let departmentId = $(this).val();
            let modal = $(this).closest('.modal');
            let employeeSelect = modal.find('.employees-select');
            employeeSelect.html('<option>Loading...</option>');
            $.ajax({
                url: "{{ route('admin.department.users') }}",
                type: "GET",
                data: { department_id: departmentId },
                success: function (response) {
                    employeeSelect.empty();
                    employeeSelect.append('<option disabled selected>Select Employee</option>');
                    if (response.length > 0) {
                        $.each(response, function (key, user) {
                            employeeSelect.append(`<option value="${user.id}">${user.name}</option>`);
                        });
                    } else {
                        employeeSelect.append('<option disabled>No users found</option>');
                    }
                }
            });
        });
    });
</script>
@include('admin.assetManagement.assetDetail.common.scripts')
@endsection