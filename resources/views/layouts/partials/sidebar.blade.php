<aside id="layout-menu" class="layout-menu menu-vertical menu">
    @inject('settings', 'App\Settings\SystemSettings')

    <div class="app-brand demo">
        <a href="{{ route('app.dashboard') }}" class="app-brand-link">
            <span class="app-brand-logo demo">
                <span class="text-primary">
                    <svg width="32" height="22" viewBox="0 0 32 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <!-- SVG logo path same as original -->
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M0.00172773 0V6.85398C0.00172773 6.85398 -0.133178 9.01207 1.98092 10.8388L13.6912 21.9964L19.7809 21.9181L18.8042 9.88248L16.4951 7.17289L9.23799 0H0.00172773Z" fill="currentColor" />
                        <path opacity="0.06" fill-rule="evenodd" clip-rule="evenodd" d="M7.69824 16.4364L12.5199 3.23696L16.5541 7.25596L7.69824 16.4364Z" fill="#161616" />
                        <path opacity="0.06" fill-rule="evenodd" clip-rule="evenodd" d="M8.07751 15.9175L13.9419 4.63989L16.5849 7.28475L8.07751 15.9175Z" fill="#161616" />
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M7.77295 16.3566L23.6563 0H32V6.88383C32 6.88383 31.8262 9.17836 30.6591 10.4057L19.7824 22H13.6938L7.77295 16.3566Z" fill="currentColor" />
                    </svg>
                </span>
            </span>
            <span class="app-brand-text demo menu-text fw-bold ms-3">Vital Care</span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <i class="icon-base ti menu-toggle-icon d-none d-xl-block"></i>
            <i class="icon-base ti tabler-x d-block d-xl-none"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        <li class="menu-item {{ request()->is('app/dashboard*') ? 'active' : '' }}">
            <a href="{{ route('app.dashboard') }}" class="menu-link">
                <i class="icon-base ti tabler-home icon-22px me-3"></i>
                <div data-i18n="Dashboard">Dashboard</div>
            </a>
        </li>
        @unlessrole('patient')
        @can('read-users')
            <li class="menu-item {{ request()->is('app/users*') || request()->is('app/roles*') ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="icon-base ti tabler-lock icon-22px me-3"></i>
                    <div>Authentication</div>
                </a>

                <ul class="menu-sub">
                    <li class="menu-item {{ request()->is('app/users*') ? 'active' : '' }}">
                        <a href="{{ route('app.users.index') }}" class="menu-link">
                            <div>Users</div>
                        </a>
                    </li>
                    <li class="menu-item {{ request()->is('app/roles*') ? 'active' : '' }}">
                        <a href="{{ route('app.roles.index') }}" class="menu-link">
                            <div>Roles</div>
                        </a>
                    </li>
                </ul>
            </li>
        @endcan
        @endunlessrole

        <li class="menu-item {{ request()->is('app/appointments*') ? 'active' : '' }}">
            <a href="{{ route('app.appointments.index') }}" class="menu-link">
                <i class="icon-base ti tabler-calendar icon-22px me-3"></i>
                <div>Appointments</div>
            </a>
        </li>

        @unlessrole('patient')
        @if($settings->show_patients || $settings->show_admission || $settings->show_appointments)
            @if($settings->show_patients)
                @can('read-patients')
                <li class="menu-item {{ request()->is('app/patients*') ? 'active' : '' }}">
                    <a href="{{ route('app.patients.index') }}" class="menu-link">
                        <i class="icon-base ti tabler-users icon-22px me-3"></i>
                        <div>Patients</div>
                    </a>
                </li>
                @endcan 
            @endif
        @endif
        @endunlessrole

        <li class="menu-item {{ request()->is('app/lab-requests*') ? 'active' : '' }}">
            <a href="{{ route('app.lab-requests.index') }}" class="menu-link">
                <i class="icon-base ti tabler-flask icon-22px me-3"></i>
                <div>Laboratory Requests</div>
            </a>
        </li>
        <li class="menu-item {{ request()->is('app/radiology-requests*') ? 'active' : '' }}">
            <a href="{{ route('app.radiology-requests.index') }}" class="menu-link">
                <i class="icon-base ti tabler-radioactive icon-22px me-3"></i>
                <div>Radiology Requests</div>
            </a>
        </li>

        @unlessrole('patient')
        <li class="menu-item {{ request()->is('app/billing*') ? 'active' : '' }}">
            <a href="{{ route('app.billing.index') }}" class="menu-link">
                <i class="icon-base ti tabler-receipt icon-22px me-3"></i>
                <div>Billing</div>
            </a>
        </li>
        @endunlessrole

        @unlessrole('patient')
        @can('read-settings')
            <li class="menu-item {{ request()->is('app/settings*') ? 'active' : '' }}">
                <a href="{{ route('app.settings.index') }}" class="menu-link">
                    <i class="icon-base ti tabler-settings icon-22px me-3"></i>
                    <div>Settings</div>
                </a>
            </li>
        @endcan
        @endunlessrole
    </ul>
</aside>

<div class="menu-mobile-toggler d-xl-none rounded-1">
    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large text-bg-secondary p-2 rounded-1">
        <i class="ti tabler-menu icon-base"></i>
        <i class="ti tabler-chevron-right icon-base"></i>
    </a>
</div>
