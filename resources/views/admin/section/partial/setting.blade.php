@php use App\Helpers\AppHelper; @endphp
@canany([
    'list_router',
    'list_nfc',
    'list_qr',
])
    <li class="nav-item  {{
                   request()->routeIs('admin.routers.*') ||
                   request()->routeIs('admin.qr.*')||
                   request()->routeIs('admin.nfc.*')

                ? 'active' : ''
            }}"
    >
        <a class="nav-link" data-bs-toggle="collapse"
           href="#attendance_method"
           data-href="#"
           role="button" aria-expanded="false" aria-controls="settings">
            <i class="link-icon" data-feather="tool"></i>
            <!-- <span class="link-title"> {{ __('index.attendance_methods') }} </span> -->
            <span class="link-title"> {{ __('advanced_settings') }} </span>
            <i class="link-arrow" data-feather="chevron-down"></i>
        </a>
        <div class="{{
                      request()->routeIs('admin.routers.*') ||
                      request()->routeIs('admin.qr.*') ||
                      request()->routeIs('admin.nfc.*')

                       ? '' : 'collapse'  }} " id="attendance_method">

            <ul class="nav sub-menu">
                {{-- @canany(['list_branch','list_department','list_department'])
                    @if(AppHelper::checkSuperAdmin())
                        <li class="nav-item">
                            <a href="{{ route('admin.company.index') }}"
                               data-href="{{ route('admin.company.index') }}"
                               class="nav-link {{ request()->routeIs('admin.company.*') ? 'active' : '' }}">
                               {{ __('index.company') }}
                            </a>
                        </li>
                    @endif
                @endcanany           --}}
                <!-- @if(\App\Helpers\AppHelper::checkSuperAdmin())
                    <li class="nav-item  {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <a class="nav-link" data-bs-toggle="collapse"
                           href="#user-management"
                           data-href="#"
                           role="button" aria-expanded="false" aria-controls="settings">
                            <i class="link-icon" data-feather="user"></i>
                            <span class="link-title"> {{ __('index.user_management') }} </span>
                            <i class="link-arrow" data-feather="chevron-down"></i>
                        </a>
                        <div class="{{ request()->routeIs('admin.users.*') ? '' : 'collapse'  }} " id="user-management">

                            <ul class="nav sub-menu">
                                <li class="nav-item">
                                    <a
                                        href="{{route('admin.users.index')}}"
                                        data-href="{{route('admin.users.index')}}"
                                        class="nav-link {{request()->routeIs('admin.users.*') ? 'active' : ''}}">{{ __('index.users') }}</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endif                 -->
                 <li class="nav-item">
                        <a
                            href="{{ route('admin.profile_edit', auth()->user()->id) }}"
                            class="nav-link {{ request()->routeIs('admin.profile_edit') ? 'active' : '' }}">
                            {{ __('profile_setting') }}
                        </a>
                  </li>
                {{-- @if(AppHelper::checkSuperAdmin())
                    <li class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <a href="{{ route('admin.users.index') }}"
                           data-href="{{ route('admin.users.index') }}"
                           class="nav-link">
                            <!-- <i class="link-icon" data-feather="user"></i> -->
                            <span class="nav-link">{{ __('index.user_management') }}</span>
                        </a>
                    </li>
                @endif                      --}}
                @if(AppHelper::checkSuperAdmin())
                    <li class="nav-item">
                        <a
                            href="{{route('admin.users.index')}}"
                            class="nav-link {{request()->routeIs('admin.users.*') ? 'active' : ''}}">
                            {{ __('index.user_management') }}
                        </a>
                    </li>
                @endif
                @can('list_router')
                    <li class="nav-item">
                        <a
                            href="{{route('admin.routers.index')}}"
                            data-href="{{route('admin.routers.index')}}"
                            class="nav-link {{request()->routeIs('admin.routers.*') ? 'active' : ''}}">{{ __('attendance_router_setting') }}
                        </a>
                    </li>
                @endcan

                {{-- @can('list_nfc')
                    <li class="nav-item">
                        <a
                            href="{{route('admin.nfc.index')}}"
                            data-href="{{route('admin.nfc.index')}}"
                            class="nav-link {{request()->routeIs('admin.nfc.*') ? 'active' : ''}}">{{ __('index.nfc') }}</a>
                    </li>
                @endcan

                @can('list_qr')
                    <li class="nav-item">
                        <a
                            href="{{route('admin.qr.index')}}"
                            data-href="{{route('admin.qr.index')}}"
                            class="nav-link {{request()->routeIs('admin.qr.*') ? 'active' : ''}}">{{ __('index.qr') }}</a>
                    </li>

                @endcan --}}
            </ul>
        </div>
    </li>
@endcanany


 @canany([
    'role_permission',
    'general_setting',
    'app_setting',
    'feature_control',
    'fiscal_year',
    'payment_currency',
    'notification',
    'theme_setting'
])
    <li class="nav-item  {{
                   request()->routeIs('admin.roles.*') ||
                      request()->routeIs('admin.general-settings.*') ||
                      request()->routeIs('admin.app-settings.*') ||
                      request()->routeIs('admin.notifications.*')||
                      request()->routeIs('admin.payment-currency.*')||
                      request()->routeIs('admin.fiscal_year.*')||
                      request()->routeIs('admin.theme-color-setting.*')||
                      request()->routeIs('admin.feature.index')
                ? 'active' : ''
            }}"
    >
        <a class="nav-link" data-bs-toggle="collapse"
           href="#setting"
           data-href="#"
           role="button" aria-expanded="false" aria-controls="settings">
            <i class="link-icon" data-feather="settings"></i>
            <span class="link-title"> {{ __('index.settings') }} </span>
            <i class="link-arrow" data-feather="chevron-down"></i>
        </a>
        <div class="{{ request()->routeIs('admin.roles.*') ||
                      request()->routeIs('admin.general-settings.*') ||
                      request()->routeIs('admin.app-settings.*') ||
                      request()->routeIs('admin.notifications.*')||
                      request()->routeIs('admin.payment-currency.*')||
                      request()->routeIs('admin.fiscal_year.*')||
                      request()->routeIs('admin.theme-color-setting.*')||
                      request()->routeIs('admin.feature.index')

                       ? '' : 'collapse'  }} " id="setting">

            <ul class="nav sub-menu">
                @if(AppHelper::checkSuperAdmin())
                    <li class="nav-item">
                        <a
                            href="{{route('admin.roles.index')}}"
                            data-href="{{route('admin.roles.index')}}"
                            class="nav-link {{request()->routeIs('admin.roles.*') ? 'active' : ''}}">{{ __('index.roles_permissions') }}</a>
                    </li>
                @endif


                    <li class="nav-item">
                        <a
                            href="{{route('admin.permissions.index', ['slug' => 'a'])}}"
                            data-href="{{route('admin.permissions.index')}}"
                            class="nav-link {{(request()->routeIs('admin.permissions.*') && request()->slug === 'a') ? 'active' : ''}}">{{ __('index.admin_permissions') }}</a>
                    </li>

                    {{-- <li class="nav-item">
                        <a
                            href="{{route('admin.permissions.index', ['slug' => 'e'])}}"
                            data-href="{{route('admin.permissions.index')}}"
                            class="nav-link {{(request()->routeIs('admin.permissions.*') && request()->slug === 'e') ? 'active' : ''}}">Employee Permissions</a>
                    </li> --}}


                {{-- @if(AppHelper::checkSuperAdmin())
                    <li class="nav-item">
                        <a
                            href="{{route('admin.general-settings.index')}}"
                            data-href="{{route('admin.general-settings.index')}}"
                            class="nav-link {{request()->routeIs('admin.general-settings.*') ? 'active' : ''}}">{{ __('index.general_settings') }}</a>
                    </li>
                @endif --}}

                {{-- @if(AppHelper::checkSuperAdmin())
                    <li class="nav-item">
                        <a
                            href="{{route('admin.app-settings.index')}}"
                            data-href="{{route('admin.app-settings.index')}}"
                            class="nav-link {{request()->routeIs('admin.app-settings.*') ? 'active' : ''}}">{{ __('index.app_settings') }}</a>
                    </li>
                @endif --}}

                {{-- @can('notification')
                    <li class="nav-item">
                        <a
                            href="{{route('admin.notifications.index')}}"
                            data-href="{{route('admin.notifications.index')}}"
                            class="nav-link {{request()->routeIs('admin.notifications.*') ? 'active' : ''}}">{{ __('index.notifications') }}</a>
                    </li>
                @endcan --}}

                {{-- @can('payment_currency')
                    <li class="nav-item {{request()->routeIs('admin.payment-currency.*')  ? 'active' : '' }}">
                        <a
                            href="{{route('admin.payment-currency.index')}}"
                            data-href="{{route('admin.payment-currency.index')}}"
                            class="nav-link {{request()->routeIs('admin.payment-currency.*') ? 'active' : ''}}"> {{ __('index.payment_currency') }}</a>
                    </li>

                @endcan --}}
                {{-- @if(AppHelper::checkSuperAdmin())
                    <li class="nav-item {{request()->routeIs('admin.feature.index')  ? 'active' : '' }}">
                        <a
                            href="{{route('admin.feature.index')}}"
                            data-href="{{route('admin.feature.index')}}"
                            class="nav-link {{request()->routeIs('admin.feature.index') ? 'active' : ''}}"> {{ __('index.feature_control') }}</a>
                    </li>
                @endif --}}

                {{-- @can('fiscal_year')
                    <li class="nav-item {{ request()->routeIs('admin.fiscal_year.*')  ? 'active' : '' }}">
                        <a
                            href="{{route('admin.fiscal_year.index')}}"
                            data-href="{{route('admin.fiscal_year.index')}}"
                            class="nav-link {{request()->routeIs('admin.fiscal_year.*') ? 'active' : ''}}"> {{ __('index.fiscal_year') }}</a>
                    </li>
                @endcan --}}
                {{-- @if(AppHelper::checkSuperAdmin())
                    <li class="nav-item {{ request()->routeIs('admin.theme-color-setting.*')  ? 'active' : '' }}">
                        <a
                            href="{{route('admin.theme-color-setting.index')}}"
                            data-href="{{route('admin.theme-color-setting.index')}}"
                            class="nav-link {{request()->routeIs('admin.theme-color-setting.*') ? 'active' : ''}}"> {{ __('index.theme_color') }}</a>
                    </li>
                @endif --}}


            </ul>
        </div>
    </li>
@endcanany
