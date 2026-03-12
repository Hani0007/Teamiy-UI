@php
    $locale = \Illuminate\Support\Facades\App::getLocale();
    $authUser = auth()->user();
@endphp

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
                <a class="nav-link dropdown-toggle" href="#" id="langDropdown" data-bs-toggle="dropdown">
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

            <li class="nav-item">
                <a class="nav-link position-relative" href="javascript:void(0);" id="openNotif">
                    <i data-feather="bell"></i>
                    <span
                        class="position-absolute top-2 start-75 translate-middle p-1 bg-danger border border-light rounded-circle"></span>
                </a>
            </li>

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
                </div>
            </li>
        </ul>
    </div>
</nav>

<div class="notif-overlay" id="notifOverlay"></div>
<div class="notification-sidebar" id="notifSidebar">
    <div class="p-4 d-flex justify-content-between align-items-center border-bottom">
        <h5 class="fw-bold mb-0">Notification</h5>
        <button class="btn btn-link text-dark p-0" id="closeNotif"><i data-feather="x"></i></button>
    </div>

    <div class="notif-tabs-header">
        <div class="notif-tab-item active" data-tab="all">All</div>
        <div class="notif-tab-item" data-tab="mention">Mention</div>
        <div class="notif-tab-item" data-tab="reminder">Reminder</div>
    </div>

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
    <div class="notif-footer">
        <a href="{{ route('admin.notifications.index') }}" class="btn-view-all">
            View all Notifications
        </a>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sidebar = document.getElementById('notifSidebar');
        const overlay = document.getElementById('notifOverlay');
        const openBtn = document.getElementById('openNotif');
        const closeBtn = document.getElementById('closeNotif');
        const tabs = document.querySelectorAll('.notif-tab-item');
        const contentArea = document.getElementById('notifContent');

        // Sidebar Open/Close
        openBtn.addEventListener('click', () => {
            sidebar.classList.add('active');
            overlay.classList.add('active');
        });

        const closeSidebar = () => {
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
