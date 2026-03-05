@extends('layouts.master')

@section('title', __('index.attendance'))

@section('action', 'Attendance Log')


@section('main-content')

    <section class="content">


        @include('admin.section.flash_message')

        @include('admin.attendance.common.breadcrumb')
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="card-title mb-0">{{ __('index.log_filter')  }}</h6>
            </div>
            <form class="forms-sample card-body pb-0" action="{{ route('admin.attendance.log') }}" method="get">

                <div class="row align-items-center">

                    @if(!isset(auth()->user()->branch_id))
                        <div class="col-lg-3 col-md-6 mb-4">
                            <select class="form-select" id="branch_id" name="branch_id">
                                <option  selected  disabled>{{ __('index.select_branch') }}
                                </option>
                                @if(isset($companyDetail))
                                    @foreach($companyDetail->branches()->get() as $key => $branch)
                                        <option value="{{$branch->id}}"
                                            {{ (isset($filterData['branch_id']) && $filterData['branch_id']  == $branch->id) ? 'selected': '' }}>
                                            {{ucfirst($branch->name)}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    @endif


                    <div class="col-lg-3 col-md-6 mb-4">

                        <select class="form-select" name="department_id" id="department_id">
                            <option  selected  disabled>{{ __('index.select_department') }}
                            </option>
                        </select>
                    </div>

                    <div class="col-lg-3 col-md-6 mb-4">

                        <select class="form-select" name="employee_id" id="employee_id">
                            <option  selected  disabled>{{ __('index.select_employee') }}
                            </option>
                        </select>
                    </div>

                    <div class="col-lg-3 col-md-6 d-md-flex">
                        <button type="submit" class="btn btn-block btn-success form-control me-md-2 me-0 mb-md-4 mb-2">{{ __('index.filter') }}</button>

                        <a class="btn btn-block btn-primary form-control me-md-2 me-0 mb-4"
                           href="{{ route('admin.attendance.log') }}">{{ __('index.reset') }}</a>
                    </div>
                </div>
            </form>
        </div>
        <div class="card">
            <div class="card-header">
                <h6 class="card-title mb-0">{{ __('index.attendance_logs') }}</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">

                        <table id="dataTableExample" class="table">
                            <thead>
                            <tr>
                                <th>SN</th>
                                <th>{{ __('index.employee_name') }}</th>
                                <th class="text-center">{{ __('index.attendance_type') }}</th>
                                <th class="text-center">{{ __('index.identifier') }}</th>
                                <th class="text-center">{{ __('index.date') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                                @forelse($logData as $log)

                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $log->user?->name }}</td>
                                        <td  class="text-center">{{ $log->attendance_type ?? 'N/A' }}</td>
                                        <td  class="text-center">{{ $log->identifier ?? 'N/A' }}</td>
                                        <td  class="text-center">{{ \App\Helpers\AttendanceHelper::formattedAttendanceDateTime(\App\Helpers\AppHelper::ifDateInBsEnabled(), $log->updated_at) }}</td>
                                    </tr>

                                @empty
                                    <tr>
                                        <td colspan="100%">
                                            <p class="text-center"><b>{{ __('index.no_records_found') }}</b></p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                </div>
            </div>
        </div>

    </section>

@endsection

@section('scripts')

    @include('admin.attendance.common.filter_scripts')

@endsection

