@php
    $locale = \Illuminate\Support\Facades\App::getLocale();
    $authUser = auth()->user();
@endphp
<style>
    /* Notification Sidebar Styles */
    .notification-sidebar {
        position: fixed;
        top: 0;
        right: -400px; /* Hidden by default */
        width: 380px;
        height: 100vh;
        background: #fff;
        z-index: 1060;
        transition: all 0.3s ease;
        box-shadow: -5px 0 20px rgba(0,0,0,0.1);
        display: flex;
        flex-direction: column;
    }
    .notification-sidebar.active {
        right: 0;
    }
    .notif-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.4);
        display: none;
        z-index: 1050;
    }
    .notif-overlay.active {
        display: block;
    }
    /* Original items styling matches your new design */
    .notif-item {
        padding: 15px 20px;
        border-bottom: 1px solid #f1f1f1;
        display: flex;
        gap: 12px;
        transition: background 0.2s;
    }
    .notif-item:hover { background: #f9f9f9; }
    .navbar .search-form .input-group .input-group-text{background: transparent; border: none;color:#fff;}
    
</style>
<nav class="navbar">
    <a href="#" class="sidebar-toggler">
        <i data-feather="menu"></i>
    </a>

    <div class="navbar-content">
        <form class="search-form" style="position: relative;">
            <div class="input-group">
                <span class="input-group-text"><i data-feather="search"></i></span>
                <input type="text" class="form-control" placeholder="Search Menu (ctrl+q)" id="nav-search"
                    autocomplete="off">
            </div>

            <!-- Dropdown results -->
            <div class="card card-admin-search"
                style="
                        position: absolute; 
                        top: 100%; 
                        left: 0; 
                        right: 0; 
                        display: none; 
                        z-index: 9999;
                        max-height: 300px;
                        overflow-y: auto;
                        border: 1px solid #ccc;
                        border-radius: 5px;
                        background: #fff;
                        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
                    ">
                <ul id="nav-search-listing" class="list-group list-group-flush"
                    style="margin:0; padding:0; list-style:none;"></ul>
            </div>
        </form>

        <ul class="navbar-nav">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="langDropdown" role="button" data-bs-toggle="dropdown">
                    <i class="flag-icon flag-icon-{{ $locale === 'en' ? 'us' : $locale }}"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-end p-2 border-0 shadow-lg">
                    @foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                        <a href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}"
                            class="dropdown-item rounded-3">
                            <i class="flag-icon flag-icon-{{ $localeCode === 'en' ? 'us' : $localeCode }} me-2"></i>
                            {{ $properties['native'] }}
                        </a>
                    @endforeach
                </div>
            </li>

            @can('notification')
            <li class="nav-item">
                <a class="nav-link position-relative" href="javascript:void(0);" id="openNotif">
                    <i data-feather="bell"></i>
                    <span
                        class="position-absolute top-2 start-75 translate-middle p-1 bg-danger border border-light rounded-circle"></span>
                </a>
            </li>
            @endcan

            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="profileDropdown" data-bs-toggle="dropdown">
                    <img class="wd-35 ht-35 rounded-circle border"
                        src="{{ isset($authUser->avatar) && $authUser->avatar && str_starts_with($authUser->avatar, 'http') ? $authUser->avatar : (isset($authUser->avatar) && $authUser->avatar ? asset('uploads/admin/avatar/' . $authUser->avatar) : asset('assets/images/img.png')) }}"
                        alt="profile">
                </a>
                <div class="dropdown-menu dropdown-menu-end p-0 border-0 shadow-lg overflow-hidden"
                    style="width: 240px; border-radius: 15px;">
                    <div class="p-3 text-center bg-light border-bottom">
                        <h6 class="fw-bolder mb-0">{{ ucfirst($authUser->name) }}</h6>
                        <small class="text-muted">{{ $authUser->email }}</small>
                    </div>
                    <div class="p-2">
                        <a href="{{ route('admin.profile_edit', $authUser->id) }}"
                            class="dropdown-item d-flex align-items-center gap-2 py-2 rounded-3">
                            <i data-feather="user" style="width: 16px;"></i> Profile
                        </a>
                        <a href="{{ route('admin.logout') }}"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                            class="dropdown-item d-flex align-items-center gap-2 py-2 text-danger rounded-3">
                            <i data-feather="log-out" style="width: 16px;"></i> Logout
                        </a>
                        <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" class="d-none">@csrf
                        </form>
                    </div>
                    <ul class="list-unstyled p-1">
                        <li class="dropdown-item py-2">
                            <a href="{{ route('admin.profile_edit', $authUser->id) }}" class="text-body"><i class="me-2 icon-md" data-feather="user"></i> {{ __('index.profile') }}</a>
                        </li>
                        <li class="dropdown-item py-2">
                            <a href="{{ route('admin.logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="text-body"><i class="me-2 icon-md" data-feather="log-out"></i> {{ __('index.log_out') }}</a>
                            <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">@csrf</form>
                        </li>
                    </ul>
                </div>
            </li>
        </ul>
    </div>
</nav>

<div class="notif-overlay" id="notifOverlay"></div>
<div class="notification-sidebar" id="notifSidebar">
    <div class="p-4 d-flex justify-content-between align-items-center border-bottom">
        <h5 class="fw-bold mb-0">Notifications</h5>
        <button class="btn btn-link text-dark p-0" id="closeNotif"><i data-feather="x"></i></button>
    </div>

    {{-- Tabs Commented out as requested --}}
    {{-- 
    <div class="notif-tabs-header d-flex border-bottom text-center fw-bold" style="font-size: 13px;">
        <div class="py-2 flex-grow-1 border-end text-primary" style="cursor:pointer">All</div>
        <div class="py-2 flex-grow-1" style="cursor:pointer; color: #666;">Mentions</div>
    </div> 
    --}}

    <div class="flex-grow-1 overflow-auto" id="notifContent">
        <div class="p-3"><small class="text-muted fw-bold">New Notification</small></div>

        <div class="notif-item">
            <img src="{{ asset('assets/images/img.png') }}" class="notif-icon-box" style="object-fit: cover;">
            <div class="w-100">
                <div class="notif-msg"><strong>John Doe</strong> has requested a day off. Review it now.</div>
                <div class="notif-meta">02:12 PM</div>
                <div class="d-flex">
                    <button class="btn-notif-action">Declined</button>
                    <button class="btn-notif-action btn-approve">Approved</button>
                </div>
            </div>
            <div style="width: 8px; height: 8px; background: #007bff; border-radius: 50%; margin-top: 5px;"></div>
        </div>

        <div class="notif-item">
            <div class="notif-icon-box bg-primary-subtle"><i data-feather="dollar-sign" class="text-primary"></i></div>
            <div class="w-100">
                <div class="notif-msg"><strong>Payroll December 2024</strong> has been processed successfully.</div>
                <div class="notif-meta">02:12 PM</div>
            </div>
        </div>
    </div>

    <div class="p-3 border-top">
        <a href="{{ route('admin.notifications.index') }}" class="btn btn-primary w-100 text-white shadow-sm" style="border-radius: 8px;">
            {{ __('index.view_all') }}
        </a>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sidebar = document.getElementById('notifSidebar');
        const overlay = document.getElementById('notifOverlay');
        const openBtn = document.getElementById('openNotif');
        const closeBtn = document.getElementById('closeNotif');
        const notifContentArea = document.getElementById('notifications-detail');

        function fetchNotifications() {
    const url = "{{ route('admin.nav-notifications') }}";
    const viewAllUrl = "{{ route('admin.notifications.index') }}"; // Default link agar data mein link na ho

    fetch(url)
        .then(response => response.json())
        .then(result => {
            let html = '';
            
            if (result.data && result.data.length > 0) {
                result.data.forEach(item => {
                    // Check karein agar backend se koi link aa raha hai, warna View All par bhej dein
                    let targetUrl = item.url || item.link || viewAllUrl;
                    
                    html += `
                        <div class="notif-item" 
                             onclick="window.location.href='${targetUrl}'" 
                             style="cursor: pointer; display: flex; padding: 15px; border-bottom: 1px solid #f1f1f1; transition: background 0.2s;">
                            
                            <div class="notif-icon-box bg-light rounded-circle d-flex align-items-center justify-content-center" 
                                 style="width: 40px; height: 40px; min-width: 40px;">
                                <i data-feather="bell" style="width: 16px; color: #fb8233;"></i>
                            </div>

                            <div class="w-100 ms-3">
                                <div class="notif-msg" style="font-size: 13px; color: #333; font-weight: 500;">
                                    ${item.title}
                                </div>
                                <div class="notif-meta" style="font-size: 11px; color: #999; margin-top: 4px; display: flex; align-items: center; gap: 4px;">
                                    <i data-feather="clock" style="width: 10px; height: 10px;"></i> ${item.publish_date}
                                </div>
                            </div>
                        </div>
                    `;
                });
            } else {
                html = '<div class="p-5 text-center text-muted">No new notifications</div>';
            }

            notifContentArea.innerHTML = html;
            feather.replace(); 
        })
        .catch(err => {
            console.error('Error:', err);
            notifContentArea.innerHTML = '<div class="p-3 text-danger text-center">Failed to load notifications</div>';
        });
}

        // Sidebar Open/Close Listeners
        if(openBtn) {
            openBtn.addEventListener('click', () => {
                sidebar.classList.add('active');
                overlay.classList.add('active');
                fetchNotifications();
            });
        }

        const hideSidebar = () => {
            sidebar.classList.remove('active');
            overlay.classList.remove('active');
        };

        closeBtn.addEventListener('click', closeSidebar);
        overlay.addEventListener('click', closeSidebar);

        // Tabs Logic (Frontend Only)
        tabs.forEach(tab => {
            tab.addEventListener('click', () => {
                tabs.forEach(t => t.classList.remove('active'));
                tab.classList.add('active');

                const tabType = tab.getAttribute('data-tab');
                renderDummyData(tabType);
            });
        });

        function renderDummyData(type) {
            let html =
                `<div class="p-3"><small class="text-muted fw-bold">${type.toUpperCase()} NOTIFICATIONS</small></div>`;

            if (type === 'all') {
                html += `
                    <div class="notif-item">
                        <div class="notif-icon-box bg-info-subtle"><i data-feather="info" class="text-info"></i></div>
                        <div class="w-100"><div class="notif-msg">New HR policies updated. Review them here.</div><div class="notif-meta">Just now</div></div>
                    </div>`;
            } else if (type === 'mention') {
                html += `
                    <div class="notif-item">
                        <div class="notif-icon-box bg-warning-subtle"><i data-feather="at-sign" class="text-warning"></i></div>
                        <div class="w-100"><div class="notif-msg"><strong>Sarah</strong> mentioned you in a comment.</div><div class="notif-meta">5 mins ago</div></div>
                    </div>`;
            } else {
                html += `
                    <div class="notif-item">
                        <div class="notif-icon-box bg-danger-subtle"><i data-feather="calendar" class="text-danger"></i></div>
                        <div class="w-100"><div class="notif-msg">Reminder: Weekly Team Meeting at 4:00 PM.</div><div class="notif-meta">1 hour ago</div></div>
                    </div>`;
            }
            contentArea.innerHTML = html;
            feather.replace(); // Re-render icons
        }

        feather.replace();
    });
</script>
