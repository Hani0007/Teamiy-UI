@extends('layouts.master')

@section('title',__('index.leave_request'))

@section('action',__('index.create'))

@section('main-content')
    <section class="content">

        @include('admin.section.flash_message')

        @include('admin.leaveRequest.common.breadcrumb')

        <div class="card">
            <div class="card-body">
                <form class="forms-sample"
                      action="{{route('admin.employee-leave-request.store')}}"
                      method="post">
                    @csrf

                    <div class="row">
                        <div class="col-lg-3 mb-3">
                            <label for="leave_type" class="form-label">{{ __('index.leave_type') }}<span style="color: red">*</span></label>
                            <select class="form-select" id="leaveType" name="leave_type_id" required>
                                <option selected disabled> {{ __('index.select_leave_type') }}</option>
                                @foreach($leaveTypes as $leave)
                                    <option value="{{ $leave->id }}" @if( old('leave_type_id')  == $leave->id) selected @endif > {{ $leave->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-lg-3 mb-3">
                            <label for="leave_from" class="form-label">{{ __('index.from_date') }}<span style="color: red">*</span></label>
                            @if($bsEnabled)
                                <input type="text" class="form-control leave_from" id="leave_from" value="{{old('leave_from')}}" name="leave_from" autocomplete="off">
                            @else
                                <input class="form-control" type="date" name="leave_from" value="{{old('leave_from')}}" required  />
                            @endif
                        </div>

                        <div class="col-lg-3 mb-3 leaveTime d-none" >
                            <label for="leave_from" class="form-label">{{ __('index.start_time') }}<span style="color: red">*</span></label>

                            <input class="form-control" type="time" name="start_time" value="{{old('start_time')}}"  />
                        </div>

                        <div class="col-lg-3 mb-3">
                            <label for="leave_to" class="form-label">{{ __('index.to_date') }}<span style="color: red">*</span></label>
                            @if($bsEnabled)
                                <input type="text" class="form-control leave_to" id="leave_to" value="{{old('leave_to')}}" name="leave_to" autocomplete="off">
                            @else
                                <input class="form-control" type="date" name="leave_to" value="{{old('leave_to')}}" required  />

                            @endif
                        </div>

                        <div class="col-lg-3 mb-3 leaveTime d-none">
                            <label for="leave_from" class="form-label">{{ __('index.end_time') }} <span style="color: red">*</span></label>
                            <input class="form-control" type="time" name="end_time" value="{{old('end_time')}}"  />
                        </div>
                        <div class="col-lg-6 mb-3">
                            <label for="note" class="form-label">{{ __('index.reason') }}  <span style="color: red"> *</span> </label>
                            <textarea class="form-control" name="reasons" rows="5" >{{  old('reasons') }}</textarea>
                        </div>

                        <div class="text-start">
                            <button type="submit" class="btn btn-primary">
                                {{ __('index.submit') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script>
        $('document').ready(function(){


            $('.leave_from').nepaliDatePicker({
                language: "english",
                dateFormat: "MM/DD/YYYY",
                ndpYear: true,
                ndpMonth: true,
                ndpYearCount: 20,
                disableAfter: "2089-12-30",
            });

            $('.leave_to').nepaliDatePicker({
                language: "english",
                dateFormat: "MM/DD/YYYY",
                ndpYear: true,
                ndpMonth: true,
                ndpYearCount: 20,
                disableAfter: "2089-12-30",
            });
        });

    </script>

@endsection

