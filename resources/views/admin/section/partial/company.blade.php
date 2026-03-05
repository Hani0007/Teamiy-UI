@php use App\Helpers\AppHelper; @endphp
{{-- @canany(['view_company','list_branch','list_department','list_department']) --}}
@canany(['list_branch','list_department','list_department'])
                         <!-- request()->routeIs('admin.company.*') ||  -->
    <li class="nav-item  {{

                           request()->routeIs('admin.branch.*') ||
                           request()->routeIs('admin.departments.*') ||
                           request()->routeIs('admin.posts.*')
                        ? 'active' : ''
                        }}   ">
        <a class="nav-link" data-bs-toggle="collapse"
           href="#company_management"
           data-href="#"
           role="button" aria-expanded="false" aria-controls="company">
            <i class="link-icon" data-feather="align-justify"></i>
            <span class="link-title">{{ __('index.company_management') }}</span>
            <i class="link-arrow" data-feather="chevron-down"></i>
        </a>
                      <!-- request()->routeIs('admin.company.*') || -->
        <div class="{{

                           request()->routeIs('admin.branch.*') ||
                           request()->routeIs('admin.departments.*') ||
                           request()->routeIs('admin.posts.*')   ?'' : 'collapse'  }} " id="company_management">
            <ul class="nav sub-menu">
                @if(AppHelper::checkSuperAdmin())
                    <li class="nav-item">
                        <a href="{{route('admin.company.index')}}"
                           data-href="{{route('admin.company.index')}}"
                           class="nav-link {{request()->routeIs('admin.company.*') ? 'active' : ''}}">{{ __('company_profile') }}</a>
                    </li>
                @endif

                @if(AppHelper::checkSuperAdmin())
                    <li class="nav-item">
                        <a href="{{route('admin.branch.index')}}"
                           data-href="{{route('admin.branch.index')}}"
                           class="nav-link {{request()->routeIs('admin.branch.*') ? 'active' : ''}}">{{ __('branches') }}</a>
                    </li>
                @endif

                @can('list_department')
                    <li class="nav-item">
                        <a href="{{route('admin.departments.index')}}"
                           data-href="{{route('admin.departments.index')}}"
                           class="nav-link {{request()->routeIs('admin.departments.*') ? 'active' : ''}}">{{ __('departments') }}</a>
                    </li>
                @endcan

                @can('list_post')
                    <li class="nav-item">
                        <a href="{{route('admin.posts.index')}}"
                           data-href="{{route('admin.posts.index')}}"
                           class="nav-link {{request()->routeIs('admin.posts.*') ? 'active' : ''}}">{{ __('designations') }}</a>
                    </li>
                @endcan
            </ul>
        </div>
    </li>
@endcanany

