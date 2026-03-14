@extends('layouts.master')

@section('title', __('index.show_project_detail'))
@section('action', __('index.detail'))

@section('styles')
    <style>
        /* Custom Modern Dashboard Styles */
        body {
            background-color: #FCFCFC !important;
            color: #2D3748;
        }

        .card {
            border: none !important;
            border-radius: 16px !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03) !important;
            background: #ffffff;
            margin-bottom: 1.5rem;
            transition: transform 0.2s ease;
        }

.card-header {
    background-color: #057DB0 !important; /* Blue theme */
    color: #ffffff !important;           /* White text for contrast */
    border-bottom: 1px solid #f1f1f1 !important;
    padding: 1.25rem 1.5rem !important;
    border-top-left-radius: 16px !important; 
    border-top-right-radius: 16px !important;
}

        .card-header h3, .card-header h5 {
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 0;
        }

        /* Buttons & Actions */
        /* .btn-primary {
            background-color: #FB8233 !important;
            border-color: #FB8233 !important;
            border-radius: 10px;
            padding: 8px 20px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            background-color: #e06d22 !important;
            transform: translateY(-1px);
            box-shadow: 0 4px 10px rgba(251, 130, 51, 0.3);
        } */

        .btn-success, .btn-secondary {
            border-radius: 10px;
            font-weight: 600;
        }

        /* Progress Bar */
        .progress {
            height: 10px !important;
            border-radius: 20px !important;
            background-color: #f1f1f1 !important;
            overflow: visible;
        }

        .progress-bar {
            background-color: #FB8233 !important;
            border-radius: 20px !important;
            position: relative;
        }

        .progress-bar span {
            position: absolute;
            right: 0;
            top: -25px;
            font-size: 12px;
            font-weight: 700;
            color: #FB8233;
        }

        /* Attachments & Images */
        .uploaded-image {
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid #f1f1f1;
            position: relative;
            transition: all 0.3s ease;
        }

        .uploaded-image:hover {
            box-shadow: 0 8px 15px rgba(0,0,0,0.1);
        }

        .uploaded-image img {
            height: 150px;
            object-fit: cover;
        }

        .remove-image, .remove-files {
            background: #ffffff;
            color: #e53e3e;
            border-radius: 50%;
            padding: 2px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }

        /* Task List Styling */
        .task-row {
            padding: 1rem;
            border-radius: 12px;
            transition: background 0.2s;
            border-bottom: 1px solid #f8f8f8;
        }

        .task-row:hover {
            background-color: #fff9f5;
        }

        .task-row a {
            color: #2d3748;
            font-weight: 600;
            text-decoration: none;
        }

        .task-row a:hover {
            color: #FB8233;
        }

        /* Avatars */
        .checklist-image, .member-section-image img {
            border: 2px solid #fff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            width: 35px;
            height: 35px;
        }

        /* Sidebar Table */
        .table-summary td {
            padding: 12px 0;
            border: none;
            font-size: 0.95rem;
        }

        /* Modal Customization */
        .modal-content {
            border-radius: 20px;
            border: none;
        }


        #updateMemberModal .modal-content, #updateMemberModal .modal-body {
            overflow: visible !important;
        }

        .select2-container--open {
            z-index: 9999;
        }
    </style>
    <link rel="stylesheet" href="{{ asset('assets/css/imageuploadify.min.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css" rel="stylesheet" />
@endsection

@section('button')
    <div class="float-md-end d-md-flex align-items-center mb-4">
        @can('edit_project')
            @endcan
    </div>
@endsection

@section('main-content')
    <section class="content pb-0">
        @include('admin.section.flash_message')

        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h2 class="mb-1" style="color:#057DB0; font-weight: 800;">{{ ucfirst($projectDetail->name) }}</h2>
                @include('admin.project.common.breadcrumb')
            </div>
            
            <div class="d-flex align-items-center gap-2">
                @can('edit_project')
                    <a href="{{ route('admin.projects.edit', $projectDetail->id) }}" class="btn btn-success border d-flex align-items-center shadow-sm">
                        <i class="link-icon me-1" data-feather="edit" style="width: 16px;"></i> @lang('index.edit_project')
                    </a>
                @endcan

                @can('create_task')
                    <a href="{{ route('admin.project-task.create', $projectDetail->id) }}" class="btn btn-success border d-flex align-items-center shadow-sm">
                        <i class="link-icon me-1" data-feather="plus" style="width: 16px;"></i> @lang('index.create_task')
                    </a>
                @endcan

                @can('upload_project_attachment')
                    <a href="#" data-bs-toggle="modal" data-bs-target="#projectAttachmentModal" class="btn btn-primary d-flex align-items-center shadow-sm text-white">
                        <i class="link-icon me-1" data-feather="clipboard" style="width: 16px;"></i> @lang('index.upload_attachments')
                    </a>
                @endcan
            </div>
        </div>

        <div class="row position-relative">
            @php
                $ProjectStatus = [
                    'in_progress' => '#057DB0',      // Blue
                    'completed'   => '#057DB0',      // Blue
                    'on_hold'     => '#057DB0',      // Blue
                    'not_started' => '#FB8233',      // Orange
                    'cancelled'   => '#FB8233',      // Orange
                ];
            @endphp

            <div class="col-lg-8 mb-4">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5>@lang('index.description')</h5>
                        <div class="text-end">
                            <span class=" small">@lang('index.total_tasks'): {{ $projectDetail->tasks->count() }}</span>
                            <span class="mx-2 ">|</span>
                            <span class=" small">@lang('index.completed_tasks'): {{ $projectDetail->completedTask ? $projectDetail->completedTask->count() : 0 }}</span>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="progress mb-4 mt-2">
                            <div class="progress-bar" role="progressbar"
                                 style="{{ \App\Helpers\AppHelper::getProgressBarStyle($projectDetail->getProjectProgressInPercentage()) }}"
                                 aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                                <span>{{ $projectDetail->getProjectProgressInPercentage() }}%</span>
                            </div>
                        </div>
                        <div class="project-desc text-muted" style="line-height: 1.8;">
                            {!! $projectDetail->description !!}
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">@lang('index.uploaded_image_files')</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @forelse($images as $key => $imageData)
                                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                                    <div class="uploaded-image">
                                        <a href="{{ asset(\App\Models\Attachment::UPLOAD_PATH.$imageData->attachment) }}" data-lightbox="image-1" data-title="{{ $imageData->attachment }}">
                                            <img class="w-100" src="{{ asset(\App\Models\Attachment::UPLOAD_PATH.$imageData->attachment) }}" alt="@lang('index.document_images')">
                                        </a>
                                        <div class="p-2 d-flex justify-content-between align-items-center">
                                            <small class="text-truncate me-2">{{ $imageData->attachment }}</small>
                                            @can('delete_pm_attachment')
                                                <a class="documentDelete" data-href="{{ route('admin.attachment.delete', $imageData->id) }}" style="cursor:pointer">
                                                    <i class="link-icon text-danger" data-feather="trash-2" style="width:14px"></i>
                                                </a>
                                            @endcan
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-4 w-100">
                                    <p class="text-muted">@lang('index.no_project_image_found')</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">@lang('index.uploaded_files')</h5>
                    </div>
                    <div class="card-body">
                        @forelse($files as $key => $fileData)
                            <div class="p-3 mb-2 rounded-3 border-bottom hover-bg-light transition-all">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <div class="bg-light p-2 rounded-3 text-primary">
                                            <i class="link-icon" data-feather="file-text" style="color:#FB8233"></i>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <a class="d-block font-weight-bold text-dark mb-1" target="_blank" href="{{ asset(\App\Models\Attachment::UPLOAD_PATH.$fileData->attachment) }}">
                                            {{ $fileData->attachment }}
                                        </a>
                                        <span class="text-muted small">{{ date_format($fileData->created_at, 'M d Y') }}</span>
                                    </div>

                                    @can('delete_pm_attachment')
                                        <div class="col-auto">
                                            <a class="documentDelete btn btn-link text-danger" data-href="{{ route('admin.attachment.delete', $fileData->id) }}">
                                                <i class="link-icon" data-feather="x"></i>
                                            </a>
                                        </div>
                                    @endcan
                                </div>
                            </div>
                        @empty
                            <p class="text-muted text-center py-3">@lang('index.no_project_files_found')</p>
                        @endforelse
                    </div>
                </div>

                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">@lang('index.project_tasks_lists')</h5>
                    </div>
                    <div class="card-body p-0">
                        @forelse($projectDetail->tasks as $key => $value)
                            @php
                                $avatarPath = \App\Models\User::AVATAR_UPLOAD_PATH . ($memberDetail->user->avatar ?? '');
                                $avatar = (!empty($memberDetail->user->avatar) && file_exists(public_path($avatarPath)))
                                            ? asset($avatarPath)
                                            : asset('assets/images/img.png');
                            @endphp
                            <div class="task-row d-flex align-items-center justify-content-between px-4 py-3">
                                <div class="d-flex align-items-center">
                                    <span class="text-muted me-3 font-weight-bold">{{ sprintf('%02d', ++$key) }}</span>
                                    <a href="{{ route('admin.tasks.show', $value->id) }}" class="h6 mb-0">{{ ucfirst($value->name) }}</a>
                                </div>
                                
                                <div class="d-flex align-items-center">
                                    <div class="assigned_members d-flex align-items-center me-4">
                                        @forelse($value->assignedMembers as $key => $memberDetail)
                                            <img class="rounded-circle checklist-image" 
                                                 style="margin-left: -10px; z-index: {{ 10 - $key }};"
                                                 src="{{ $avatar }}"
                                                 alt="@lang('profile')">
                                        @empty
                                        @endforelse
                                    </div>

                                    @canany(['edit_task','show_task_detail','delete_task'])
                                    <div class="dropdown">
                                        <button class="btn btn-link p-0 text-muted" type="button" data-bs-toggle="dropdown" style="border:none; background:transparent"y>
                                            <i class="link-icon" data-feather="more-vertical"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                                            @can('edit_task')
                                                <a href="{{ route('admin.tasks.edit', $value->id) }}" class="dropdown-item py-2">
                                                    <i class="link-icon me-2" data-feather="edit" style="width:14px"></i> @lang('index.edit')
                                                </a>
                                            @endcan
                                            @can('show_task_detail')
                                                <a href="{{ route('admin.tasks.show', $value->id) }}" class="dropdown-item py-2">
                                                    <i class="link-icon me-2" data-feather="eye" style="width:14px"></i> @lang('index.view')
                                                </a>
                                            @endcan
                                            @can('delete_task')
                                                <div class="dropdown-divider"></div>
                                                <a data-href="{{ route('admin.tasks.delete', $value->id) }}" class="delete dropdown-item py-2 text-danger">
                                                    <i class="link-icon me-2" data-feather="trash" style="width:14px"></i> @lang('index.delete')
                                                </a>
                                            @endcan
                                        </div>
                                    </div>
                                    @endcanany
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5">
                                <img src="{{ asset('assets/images/no-task.svg') }}" width="100" class="mb-3 opacity-50">
                                <p class="text-muted">No tasks assigned yet.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="col-lg-4 mb-4">
                <div class="position-sticky" style="top: 2rem;">
                    <div class="card">
                        <div class="card-header">
                            <h5>@lang('index.project_summary')</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-summary">
                                <tbody>
                                <tr>
                                    <td class="text-muted">@lang('index.cost')</td>
                                    <td class="text-end font-weight-bold">{{ $projectDetail->cost }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">@lang('index.total_hours')</td>
                                    <td class="text-end font-weight-bold">{{ $projectDetail->estimated_hours }}h</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">@lang('index.created')</td>
                                    <td class="text-end" style="color:#2D3748">{{ \App\Helpers\AppHelper::formatDateForView($projectDetail->start_date) }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">@lang('index.deadline')</td>
                                    <td class="text-end" style="color:#FB8233; font-weight:700">{{ \App\Helpers\AppHelper::formatDateForView($projectDetail->deadline) }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">@lang('index.priority')</td>
                                    <td class="text-end">
                                        <span class="badge px-3 py-2" style="background:#fff3eb; color:#FB8233; border: 1px solid #ffdbc5">
                                            {{ ucfirst($projectDetail->priority) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted">@lang('index.status')</td>
                                    <td class="text-end">
<span class="badge px-3 py-2" style="background-color: {{ $ProjectStatus[$projectDetail->status] }}; color:#FFFF; border: 1px solid {{ $ProjectStatus[$projectDetail->status] }};">
                                            {{ \App\Helpers\PMHelper::STATUS[$projectDetail->status] }}
                                        </span>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                            <div class="mt-3 p-3 rounded-3 text-center" style="background: #fff9f5; border: 1px dashed #FB8233;">
                                <span class="d-block text-muted small mb-1">@lang('index.remaining_days')</span>
                                <h4 class="mb-0" style="color:#FB8233; font-weight: 800;">
                                    {{ $projectDetail->projectRemainingDaysToComplete() }} @lang('index.days_left')
                                </h4>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <h5>@lang('index.project_members')</h5>
                            <button class=" branch-back-btn  open-employee-modal d-flex align-items-center shadow-sm text-sm p-2" 
                                    data-add-type="member"
                                    data-href="{{ route('admin.projects.update-member-data') }}"
                                    data-project-id="{{ $projectDetail->id }}">
                                <i data-feather="plus" class="me-1"></i> @lang('index.update_member')
                            </button>
                        </div>
                        <div class="card-body">
                            @foreach($assignedMember as $key => $value)
                            @php
                                $avatarPath = \App\Models\User::AVATAR_UPLOAD_PATH . ($value->user->avatar ?? '');
                                $avatar = (!empty($value->user->avatar) && file_exists(public_path($avatarPath)))
                                            ? asset($avatarPath)
                                            : asset('assets/images/img.png');
                            @endphp
                                <div class="d-flex align-items-center mb-3 p-2 rounded-3 hover-bg-light">
                                    <div class="member-section-image me-3">
                                        <img class="rounded-circle" style="width:42px; height:42px; object-fit: cover"
                                             src="{{ $avatar }}" alt="@lang('profile')">
                                    </div>
                                    <div class="member-section-heading">
                                        <h6 class="mb-0" style="font-weight:700">{{ $value->user->name }}</h6>
                                        <p class="small text-muted mb-0">Team Member</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="projectAttachmentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content shadow-lg">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title font-weight-bold">@lang('index.upload_project_attachments')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <form id="projectDocument" action="{{ route('admin.project-attachment.store') }}" 
                          enctype="multipart/form-data" method="POST">
                        @csrf
                        <input type="hidden" value="{{ $projectDetail->id }}" readonly name="project_id">
                        <div class="mb-4">
                            <input id="image-uploadify" type="file" name="attachments[]"
                                   accept=".pdf,.jpg,.jpeg,.png,.docx,.doc,.xls,.txt,.zip" multiple/>
                        </div>
                        <button type="submit" class="btn branch-back-btn w-100 shadow-sm">
                            <i class="link-icon me-2" data-feather="upload-cloud"></i> @lang('index.submit')
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="mb-3 text-end">
        <a href="{{ route('admin.projects.index') }}" class="btn branch-back-btn d-inline-flex align-items-center">
            <i data-feather="arrow-left" class="me-2"></i> @lang('index.back')
        </a>
    </div>

    @include('admin.project.common.update-member')
@endsection

@section('scripts')
    <script src="{{ asset('assets/js/imageuploadify.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            $("#image-uploadify").imageuploadify();
        });

        $(document).ready(function() {
            $('#updateMemberModal').on('shown.bs.modal', function() {
                $('#memberAdd').select2({
                    placeholder: 'Select employees',
                    dropdownParent: $('#updateMemberModal .modal-content')
                });
            });

            $('.open-employee-modal').on('click', function(e) {
                e.preventDefault();
                const addType = $(this).data('add-type');
                const projectId = $(this).data('project-id');
                const actionUrl = $(this).data('href');
                const modalId = '#updateMemberModal';
                const formId = '#addMemberToProjectForm';

                $(`${formId}`).attr('action', actionUrl);
                $(`${modalId} #${addType}_project_id`).val(projectId);
                $(modalId).modal('show');
            });

            $('#addMemberToProjectForm').on('submit', function(e) {
                e.preventDefault();
                const form = $(this);
                const actionUrl = form.attr('action');
                const modal = form.closest('.modal');
                const formData = new FormData(this);
                const submitBtn = form.find('button[type="submit"]');
                
                submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Processing...');

                fetch(actionUrl, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    submitBtn.prop('disabled', false).text('Submit');
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Updated!',
                            text: data.message,
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            modal.modal('hide');
                            window.location.reload();
                        });
                    } else {
                        Swal.fire({ icon: 'error', title: 'Oops...', text: data.message });
                    }
                })
                .catch(error => {
                    submitBtn.prop('disabled', false).text('Submit');
                    console.error('Error:', error);
                });
            });
        });
    </script>
    @include('admin.project.common.scripts')
@endsection