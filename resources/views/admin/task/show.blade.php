@extends('layouts.master')

@section('title', __('index.show_task_detail'))
@section('action', __('index.detail'))

<?php
$status = [
    'in_progress' => 'primary',
    'not_started' => 'primary',
    'on_hold' => 'info',
    'cancelled' => 'danger',
    'completed' => 'success',
];
?>

@section("styles")
<style>
body { background-color: #FCFCFC !important; color: #2D3748; }
.card { border: none !important; border-radius: 16px !important; box-shadow: 0 4px 12px rgba(0,0,0,0.03) !important; background: #fff; margin-bottom: 1.5rem; }
.card-header { background-color: #057DB0 !important; color: #fff !important; border-top-left-radius: 16px !important; border-top-right-radius: 16px !important; padding: 1.25rem 1.5rem !important; }
.card-header h3, .card-header h5 { margin: 0; font-weight: 700; color: #fff; }
.btn { border-radius: 10px; font-weight: 600; }
.progress { height: 10px !important; border-radius: 20px !important; background-color: #f1f1f1 !important; overflow: visible; }
.progress-bar { background-color: #FB8233 !important; border-radius: 20px !important; position: relative; }
.progress-bar span { position: absolute; right: 0; top: -25px; font-size: 12px; font-weight: 700; color: #FB8233; }
.uploaded-image { border-radius: 12px; overflow: hidden; border: 1px solid #f1f1f1; position: relative; }
.uploaded-image img { height: 150px; object-fit: cover; }
.remove-image, .remove-files { background: #fff; color: #e53e3e; border-radius: 50%; padding: 2px; }
.checklist-image { border: 2px solid #fff; width: 35px; height: 35px; object-fit: cover; }
#updateMemberModal .modal-content, #updateMemberModal .modal-body { overflow: visible !important; }
.select2-container--open { z-index: 9999; }
.comment-buttons { cursor: pointer; margin-top: 5px; }
.comment-section { max-height: 400px; overflow-y: auto; }
</style>
<link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css" rel="stylesheet" />
@endsection

@section('button')
<div class="breadcrumb-button float-md-end d-md-flex align-items-center">
    @can('edit_task')
        <a href="{{ route('admin.tasks.edit', $taskDetail->id) }}" class="me-2">
            <button class="btn btn-success d-md-flex align-items-center">
                <i class="link-icon me-1" data-feather="edit"></i>{{ __('index.task_edit') }}
            </button>
        </a>
    @endcan
    @can('create_checklist')
        <button class="btn btn-secondary d-md-flex align-items-center me-2 checklistAdd">
            <i class="link-icon me-1" data-feather="plus"></i>{{ __('index.create_checklist') }}
        </button>
    @endcan
    @can('upload_task_attachment')
        <a href="{{ route('admin.task-attachment.create', $taskDetail->id) }}">
            <button class="btn btn-primary d-md-flex align-items-center">
                <i class="link-icon me-1" data-feather="clipboard"></i>{{ __('index.upload_attachment') }}
            </button>
        </a>
    @endcan
</div>
@endsection

@section('main-content')
<section class="content">
    @include('admin.section.flash_message')
    @include('admin.task.common.breadcrumb')

    <div class="row">
        <!-- Left Column -->
        <div class="col-lg-8">
            <!-- Task Info Card -->
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="mb-2">{{ ucfirst($taskDetail->name) }}</h3>
                    <ul class="list-unstyled d-flex mb-0">
                        <li class="me-2">{{ __('index.total_checklist') }}: {{ $taskDetail->taskChecklists->count() }}</li>
                        <li >{{ __('index.completed_checklist') }}: {{ $taskDetail->completedTaskChecklist->count() }}</li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="progress mb-3">
                        <div class="progress-bar color2" role="progressbar"
                             style="{{ \App\Helpers\AppHelper::getProgressBarStyle($taskDetail->getTaskProgressInPercentage()) }}">
                            <span>{{ $taskDetail->getTaskProgressInPercentage() }} %</span>
                        </div>
                    </div>
                    {!! $taskDetail->description !!}

                    <!-- Attachments -->
                    <div class="attachment">
                        <div class="row">
                            @forelse($images as $imageData)
                                <div class="col-lg-3 mb-4">
                                    <div class="uploaded-image">
                                        <a href="{{ asset(\App\Models\Attachment::UPLOAD_PATH . $imageData->attachment) }}" data-lightbox="image-1" data-title="{{ $imageData->attachment }}">
                                            <img class="w-100" src="{{ asset(\App\Models\Attachment::UPLOAD_PATH . $imageData->attachment) }}" alt="{{ $imageData->attachment }}">
                                        </a>
                                        <p>{{ basename($imageData->attachment) }}</p>
                                        @can('delete_pm_attachment')
                                            <a class="documentDelete" data-title="{{ __('index.image') }}" data-href="{{ route('admin.attachment.delete', $imageData->id) }}">
                                                <i class="link-icon remove-image" data-feather="x"></i>
                                            </a>
                                        @endcan
                                    </div>
                                </div>
                            @empty
                                <p class="text-muted">No images found.</p>
                            @endforelse
                        </div>

                        <div class="row">
                            @forelse($files as $fileData)
                                <div class="uploaded-files mb-3">
                                    <div class="row align-items-center">
                                        <div class="col-lg-1"><div class="file-icon"><i class="link-icon" data-feather="file-text"></i></div></div>
                                        <div class="col-lg-10">
                                            <a target="_blank" href="{{ asset(\App\Models\Attachment::UPLOAD_PATH . $fileData->attachment) }}">{{ basename($fileData->attachment) }}</a>
                                            <p>{{ \App\Helpers\AppHelper::formatDateForView($fileData->created_at) }}</p>
                                        </div>
                                        @can('delete_pm_attachment')
                                            <div class="col-lg-1">
                                                <a class="documentDelete" data-title="{{ __('index.file') }}" data-href="{{ route('admin.attachment.delete', $fileData->id) }}">
                                                    <i class="link-icon remove-files" data-feather="x"></i>
                                                </a>
                                            </div>
                                        @endcan
                                    </div>
                                </div>
                            @empty
                                <p class="text-muted">No files found.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Comments Section -->
            <div class="card mb-4">
                <div class="card-body">
                    @include('admin.task.comment_section')
                </div>
            </div>

            <!-- Checklist Card -->
            <div class="card checklistTaskAdd mb-4">
                <div class="card-header">
                    <h5>{{ __('index.task_checklist_lists') }}</h5>
                </div>
                <div class="card-body pb-0">
                    @forelse($taskDetail->taskChecklists as $value)
                    <div class="row align-items-center mb-4 border-bottom pb-2">
                        <div class="col-lg-6 d-flex align-items-center" style="{{ $value->is_completed ? 'text-decoration: line-through;' : '' }}">
                            @can('edit_checklist')
                                <input type="checkbox" class="me-2" name="checklist" value="0" {{ $value->is_completed ? 'checked' : '' }} data-href="{{ route('admin.task-checklists.toggle-status', $value->id) }}">
                            @endcan
                            {{ $value->name }}
                        </div>
                    
                        <div class="col-lg-3 d-flex align-items-center">
                            @php
                                $avatarPath = \App\Models\User::AVATAR_UPLOAD_PATH . ($value->taskAssigned->avatar ?? '');
                                $avatar = (!empty($value->taskAssigned->avatar) && file_exists(public_path($avatarPath))) 
                                          ? asset($avatarPath) 
                                          : asset('assets/images/img.png');
                            @endphp
                            @if($value->taskAssigned)
                                <img class="rounded-circle checklist-image me-2" style="object-fit: cover" width="35" height="35" title="{{ $value->taskAssigned->name }}" src="{{ $avatar }}" alt="profile">
                                <span>{{ $value->taskAssigned->name }}</span>
                            @endif
                        </div>
                    
                        <div class="col-lg-3 text-end">
                            @can('edit_checklist')
                                <a href="{{ route('admin.task-checklists.edit', $value->id) }}"><i class="link-icon" data-feather="edit"></i></a>
                            @endcan
                            @can('delete_checklist')
                                <a href="javascript:void(0);" class="documentDelete" data-title="{{ __('index.checklist') }}" data-href="{{ route('admin.task-checklists.delete', $value->id) }}">
                                    <i class="link-icon" data-feather="delete"></i>
                                </a>
                            @endcan
                        </div>
                    </div>
                    @empty
                    <p class="text-muted">No checklist found.</p>
                    @endforelse

                    @can('create_checklist')
                        <div class="checklistForm">
                            <div class="row align-items-center justify-content-between">
                                <div class="col-lg-7 mb-4">
                                    <h5>{{ __('index.create_task_checklist') }}</h5>
                                </div>
                                <div class="col-lg-3 mb-4">
                                    <button type="button" class="btn btn-secondary float-end" id="createChecklist">{{ __('index.create_checklist') }}</button>
                                </div>
                            </div>
                            <div class="formChecklist d-none">
                                <form id="taskAdd" class="forms-sample" action="{{ route('admin.task-checklists.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="task_id" value="{{ $taskDetail->id }}" />
                                    <div id="addTaskCheckList">
                                        <div class="row checklist align-items-center justify-content-between">
                                            <div class="col-lg-7 mb-4">
                                                <input type="text" class="form-control" name="name[]" required placeholder="{{ __('index.enter_checklist_title') }}">
                                            </div>
                                            <div class="col-lg-3 mb-4">
                                                <select class="form-select" name="assigned_to[]" required>
                                                    <option value="" selected disabled>{{ __('index.select_member') }}</option>
                                                    @foreach($taskDetail->assignedMembers as $member)
                                                        <option value="{{ $member->user->id }}">{{ $member->user->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-lg-2 mb-4">
                                                <button type="button" class="btn btn-primary" id="addChecklist" title="{{ __('index.add_more_checklist') }}">+</button>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-success mb-4">{{ __('index.submit') }}</button>
                                </form>
                            </div>
                        </div>
                    @endcan
                </div>
            </div>

        </div>

        <!-- Right Column -->
        <div class="col-lg-4">
            @include('admin.task.task_summary')
        </div>
    </div>
    
</section>
<div class="mb-4 text-end">
    <a href="{{ route('admin.tasks.index') }}" class="btn branch-back-btn">
        <i class="link-icon" data-feather="arrow-left"></i> {{ __('index.back') }}
    </a>
</div>
@include('admin.task.update-employee')
@endsection

@section('scripts')
<script>
$(document).ready(function() {

    // Employee Modal
    $(document).on('click', '.open-employee-modal', function(){
        $('#updateMemberModal').modal('show');
    });

    // SweetAlert for delete
    $(document).on('click', '.documentDelete', function(e){
        e.preventDefault();
        const url = $(this).data('href');
        const title = $(this).data('title') || 'item';
        Swal.fire({
            title: `Are you sure you want to delete this ${title}?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if(result.isConfirmed){
                window.location.href = url;
            }
        });
    });

    // Toggle Create Checklist
    $('#createChecklist').click(function(){
        $('.formChecklist').toggleClass('d-none');
    });

});
</script>

@include('admin.task.common.comment_scripts')
@include('admin.task.common.scripts')
@endsection