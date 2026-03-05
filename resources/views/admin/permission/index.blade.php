@extends('layouts.master')

@section('title', 'Permission')

@section('action',__('index.lists'))

@section('button')
    {{-- @can('create_role') --}}
        @php $guardName = ($guard === 'admin') ? 'a' : 'e'; @endphp

        <a href="{{ route('admin.permissions.create', ['slug' => $guardName])}}">
            <button class="btn btn-primary">
                <i class="link-icon" data-feather="plus"></i>Add Permission
            </button>
        </a>
    {{-- @endcan --}}
@endsection


@section('main-content')

    <section class="content">
        @include('admin.section.flash_message')

        @include('admin.permission.common.breadcrumb')

        <div class="card">
            <div class="card-header">
                <h6 class="card-title mb-0">Permission List</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="dataTableExample" class="table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Permission</th>
                            {{-- <th class="text-center">@lang('index.status')</th> --}}
                            {{-- <th class="text-center">@lang('index.can_login')</th> --}}
                            {{-- @can('role_permission') --}}
                                <th class="text-center">@lang('index.action')</th>
                            {{-- @endcan --}}
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>

                        @php $count = 1; @endphp
                        @forelse($permissions as $key => $value)
                            <tr>
                                <td>{{$count++}}</td>
                                <td>{{ $value->name }}</td>
                                {{-- <td class="text-center">
                                    <label class="switch">
                                        <input class="toggleStatus"
                                               href="{{route('admin.roles.toggle-status',$value->id)}}"
                                               type="checkbox" {{($value->is_active) == 1 ?'checked':''}}>
                                        <span class="slider round"></span>
                                    </label>
                                </td> --}}

                                {{-- <td class="text-center">
                                    <span>{{$value->backend_login_authorize ? __('index.yes'):__('index.no')}}</span>
                                </td> --}}

                                {{-- @can('role_permission') --}}
                                    <td class="text-center">
                                        <ul class="d-flex list-unstyled mb-0 align-items-center justify-content-center">

                                            <li class="me-2">
                                                <a href="{{route('admin.permissions.edit',$value->id)}}"
                                                   title="@lang('index.edit')">
                                                    <i class="link-icon" data-feather="edit"></i>
                                                </a>
                                            </li>

                                            <li>
                                                <form action="{{ route('admin.permissions.destroy', $value->id) }}"
                                                    method="POST" class="deleteForm d-inline">
                                                    @csrf
                                                    @method('DELETE')

                                                    <input type="hidden" name="guard" value="{{ $guardName }}" />
                                                    
                                                    <a href="#" class="deleteRole" title="@lang('index.delete')">
                                                        <i class="link-icon" data-feather="delete"></i>
                                                    </a>
                                                </form>
                                            </li>

                                            {{-- <li>
                                                <span class="ms-2">
                                                     <a href="{{route('admin.roles.permission', $value->id)}}">
                                                        <button class="btn btn-xs btn-primary ">
                                                          @lang('index.assign_permissions')
                                                        </button>
                                                     </a>
                                                </span>
                                            </li> --}}

                                        </ul>
                                    </td>
                                {{-- @endcan --}}
                            </tr>
                        @empty
                            <tr>
                                <td colspan="100%">
                                    <p class="text-center"><b>@lang('index.no_records_found')</b></p>
                                </td>
                            </tr>
                        @endforelse

                        </tbody>
                    </table>
                    
                    <div class="d-flex justify-content-end mt-3">
                        {{ $permissions->links() }}
                    </div>
                </div>
            </div>
        </div>

        {{--        <div class="row">--}}
        {{--            <div class="dataTables_paginate">--}}
        {{--                {{$roles->appends($_GET)->links()}}--}}
        {{--            </div>--}}
        {{--        </div>--}}


    </section>
@endsection

@section('scripts')
    <script>
        $(document).ready(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // $('.toggleStatus').change(function (event) {
            //     event.preventDefault();
            //     var status = $(this).prop('checked') === true ? 1 : 0;
            //     var href = $(this).attr('href');
            //     Swal.fire({
            //         title: '@lang('index.change_status_confirm')',
            //         showDenyButton: true,
            //         confirmButtonText: `@lang('index.yes')`,
            //         denyButtonText: `@lang('index.no')`,
            //         padding: '10px 50px 10px 50px',
            //         allowOutsideClick: false
            //     }).then((result) => {
            //         if (result.isConfirmed) {
            //             window.location.href = href;
            //         } else if (result.isDenied) {
            //             (status === 0) ? $(this).prop('checked', true) : $(this).prop('checked', false)
            //         }
            //     })
            // })

            // $('.deleteRole').click(function (event) {
            //     event.preventDefault();
            //     let href = $(this).data('href');
            //     Swal.fire({
            //         title: '@lang('index.confirm_role_deletion')',
            //         showDenyButton: true,
            //         confirmButtonText: `@lang('index.yes')`,
            //         denyButtonText: `@lang('index.no')`,
            //         padding: '10px 50px 10px 50px',
            //         allowOutsideClick: false
            //     }).then((result) => {
            //         if (result.isConfirmed) {
            //             window.location.href = href;
            //         }
            //     })
            // })
            $(document).on('click', '.deleteRole', function (event) {
                event.preventDefault();
                let form = $(this).closest('form');

                Swal.fire({
                    title: '@lang('index.confirm_permission_deletion')',
                    showDenyButton: true,
                    confirmButtonText: `@lang('index.yes')`,
                    denyButtonText: `@lang('index.no')`,
                    padding: '10px 50px 10px 50px',
                    allowOutsideClick: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });

        });
    </script>
@endsection






