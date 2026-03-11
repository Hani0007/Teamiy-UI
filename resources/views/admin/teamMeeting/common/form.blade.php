@php use App\Helpers\AppHelper; @endphp
@php use App\Models\TeamMeeting; @endphp

<div class="row">
    {{-- Branch Selection --}}
    @if(!isset(auth()->user()->branch_id))
        <div class="col-lg-4 col-md-6 mb-4">
            <label for="branch_id" class="form-label fw-bold small " >{{ __('index.branch') }} <span class="text-danger">*</span></label>
            <select class="form-select select2-input border-2 shadow-none" id="branch_id" name="branch_id" style="border-color: #e9ecef;">
                <option selected disabled>{{ __('index.select_branch') }}</option>
                @if(isset($companyDetail))
                    @foreach($companyDetail->branches()->get() as $branch)
                        <option value="{{$branch->id}}"
                            {{ (isset($teamMeetingDetail) && ($teamMeetingDetail->branch_id ) == $branch->id) ? 'selected': '' }}>
                            {{ucfirst($branch->name)}}
                        </option>
                    @endforeach
                @endif
            </select>
        </div>
    @endif

    {{-- Department Selection --}}
    <div class="col-lg-4 col-md-6 mb-4 internalTrainer">
        <label for="department_id" class="form-label fw-bold small ">{{ __('index.department') }} <span class="text-danger">*</span></label>
        <select class="form-select select2-input border-2 shadow-none" id="department_id" multiple name="department[][department_id]" style="border-color: #e9ecef;">
            @if(isset($trainingDetail))
                @foreach($filteredDepartment as $department)
                    <option value="{{ $department->id }}" {{ in_array($department->id, $departmentIds) ? 'selected' : '' }}>
                        {{ ucfirst($department->dept_name) }}
                    </option>
                @endforeach
            @endif
        </select>
    </div>

    {{-- Meeting Title --}}
    <div class="col-lg-4 col-md-6 mb-4">
        <label for="title" class="form-label fw-bold small ">{{ __('index.meeting_title') }} <span class="text-danger">*</span></label>
        <input type="text" class="form-control border-2 shadow-none" id="title" name="title" required
               value="{{ (isset($teamMeetingDetail) ? $teamMeetingDetail->title : old('title')) }}"
               autocomplete="off" placeholder="{{ __('index.enter_content_title') }}" style="border-color: #e9ecef;">
    </div>

    {{-- Venue --}}
    <div class="col-lg-4 col-md-6 mb-4">
        <label for="venue" class="form-label fw-bold small ">{{ __('index.meeting_venue') }} <span class="text-danger">*</span></label>
        <input type="text" class="form-control border-2 shadow-none" id="venue" name="venue" required
               value="{{ (isset($teamMeetingDetail) ? $teamMeetingDetail->venue : old('venue')) }}"
               autocomplete="off" placeholder="{{ __('index.enter_venue_name') }}" style="border-color: #e9ecef;">
    </div>

    {{-- Meeting Date --}}
    <div class="col-lg-4 col-md-6 mb-4">
        <label for="meeting_date" class="form-label fw-bold small ">{{ __('index.meeting_date') }} <span class="text-danger">*</span></label>
        @if(AppHelper::ifDateInBsEnabled())
            <input class="form-control meetingDate border-2 shadow-none" name="meeting_date" id="meetingDate"
                   value="{{(isset($teamMeetingDetail) ? $teamMeetingDetail->meeting_date: old('meeting_date'))}}"
                   required autocomplete="off" type="text" placeholder="mm/dd/yyyy" style="border-color: #e9ecef;" />
        @else
            <input class="form-control border-2 shadow-none" name="meeting_date" type="date"
                   value="{{(isset($teamMeetingDetail) ? $teamMeetingDetail->meeting_date: old('meeting_date'))}}"
                   required style="border-color: #e9ecef;" />
        @endif
    </div>

    {{-- Start Time --}}
    <div class="col-lg-4 col-md-6 mb-4">
        <label for="meeting_start_time" class="form-label fw-bold small ">{{ __('index.meeting_start_time') }} <span class="text-danger">*</span></label>
        <input type="time" class="form-control border-2 shadow-none" id="meeting_start_time" name="meeting_start_time" required
               value="{{ (isset($teamMeetingDetail) ? $teamMeetingDetail->meeting_start_time : old('meeting_start_time')) }}" style="border-color: #e9ecef;">
    </div>

    {{-- Participators (Full Width) --}}
    <div class="col-lg-12 mb-4">
        <label for="team_meeting" class="form-label fw-bold small ">{{ __('index.meeting_participator') }} <span class="text-danger">*</span></label>
        <div class="select2-orange-container">
            <select class="form-select select2-input border-2 shadow-none" id="team_meeting" name="participator[][meeting_participator_id]"
                    multiple="multiple" required style="width: 100%;">
                {{-- Loaded via script --}}
            </select>
        </div>
    </div>

    {{-- Description --}}
    <div class="col-lg-7 mb-4">
        <label for="description" class="form-label fw-bold small ">{{ __('index.meeting_description') }}</label>
        <div class="border-1 rounded" style="border: 1px solid #e9ecef;">
            <textarea class="form-control" name="description" id="tinymceExample" 
                      rows="6">{!! (isset($teamMeetingDetail) ? $teamMeetingDetail->description : old('description')) !!}</textarea>
        </div>
    </div>

    {{-- Image Upload & Preview --}}
    <div class="col-lg-5 mb-4">
        <label for="image" class="form-label fw-bold small">{{ __('index.upload_image') }}</label>
    <div class="image-upload-wrapper border-2 rounded-4 p-4 text-center bg-white" style="border: 2px dashed #e9ecef;">
        <input class="form-control mb-3 shadow-none border-0 bg-light" type="file" id="image" name="image" accept="image/*" />
        
        {{-- Este contenedor manejará tanto la imagen existente como la nueva previsualización --}}
        <div id="image-preview-container" class="mt-2">
            @if(isset($teamMeetingDetail) && $teamMeetingDetail->image)
                <div class="img-preview-wrapper position-relative d-inline-block">
                    <span class="remove-img-btn removeImage shadow-sm d-flex align-items-center justify-content-center" 
                          data-href="{{ route('admin.team-meetings.remove-image', $teamMeetingDetail->id) }}"
                          style="position: absolute; top: -12px; right: -12px; background: #FB8233; color: white; border-radius: 50%; width: 28px; height: 28px; cursor: pointer; border: 2px solid white; font-weight: bold;">
                        &times;
                    </span>
                    <img src="{{ asset(TeamMeeting::UPLOAD_PATH.$teamMeetingDetail->image) }}"
                         class="rounded-3 shadow-sm border" style="max-height: 180px; width: 100%; object-fit: cover;">
                </div>
            @else
                <div id="placeholder-icon" class="text-muted opacity-50 my-3">
                    <i class="fa fa-image fa-3x"></i>
                        <p class="small mt-2 mb-0">No image selected</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
    /* Estilo para los inputs al enfocar */
    .form-control:focus, .form-select:focus {
        border-color: #FB8233 !important; /* Resaltado naranja al enfocar */
        box-shadow: 0 0 0 0.25rem rgba(251, 130, 51, 0.1) !important;
    }

    /* Botón de remover imagen hover */
    .remove-img-btn:hover {
        background-color: #e06b22 !important;
        transform: scale(1.1);
        transition: all 0.2s ease;
    }

    /* Contenedor de carga de imagen */
    .image-upload-wrapper:hover {
        border-color: #FB8233 !important;
        background-color: #fffaf7 !important;
        transition: all 0.3s ease;
    }
</style>
<script>
    document.getElementById('image').addEventListener('change', function(event) {
        const file = event.target.files[0];
        const container = document.getElementById('image-preview-container');
        
        if (file) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                // Limpiamos el contenedor y añadimos la nueva imagen
                container.innerHTML = `
                    <div class="img-preview-wrapper position-relative d-inline-block">
                        <span id="cancel-preview" class="shadow-sm d-flex align-items-center justify-content-center" 
                              style="position: absolute; top: -12px; right: -12px; background: #6c757d; color: white; border-radius: 50%; width: 28px; height: 28px; cursor: pointer; border: 2px solid white; font-weight: bold;">
                            &times;
                        </span>
                        <img src="${e.target.result}" class="rounded-3 shadow-sm border" style="max-height: 180px; width: 100%; object-fit: cover;">
                    </div>
                `;

                // Opción para quitar la previsualización antes de subir
                document.getElementById('cancel-preview').addEventListener('click', function() {
                    document.getElementById('image').value = ""; // Limpia el input file
                    container.innerHTML = `
                        <div class="text-muted opacity-50 my-3">
                            <i class="fa fa-image fa-3x"></i>
                            <p class="small mt-2 mb-0">No image selected</p>
                        </div>
                    `;
                });
            }
            
            reader.readAsDataURL(file);
        }
    });
</script>
<input type="hidden" readonly id="teamNotification" name="notification" value="0">