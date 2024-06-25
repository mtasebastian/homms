<div class="d-flex flex-column flex-shrink-0 sidebar shadow">
    <div class="m-4 mx-auto img-cont shadow border-light rounded-3">
      <a href="/" class="d-flex align-items-center text-decoration-none">
        <img src="{{ asset('images/logo.jpg') }}" class="img-fluid rounded-3">
      </a>
    </div>
    <div class="m-4 mx-auto px-3 profile_name">
      <h6>Welcome back</h6>
      <a href="#" class="text-decoration-none"><h5>{{ auth()->user()->name }}</h5></a>
    </div>
    <ul class="flex-column mb-auto mlist">
      @if($checker->routePermission('dashboard'))
      <li class="nav-item {{ Route::is('dashboard') ? 'active' : '' }}">
        <a href="{{ route('dashboard') }}" class="nav-link">
          <i class="fa-solid fa-chart-line"></i>
          Dashboard
        </a>
      </li>
      @endif
      @if($checker->routePermission('financials.index'))
      <li class="nav-item {{ Route::is('financials.index') ? 'active' : '' }}">
        <a href="{{ route('financials.index') }}" class="nav-link">
          <i class="fa-solid fa-coins"></i>
          Financials
        </a>
      </li>
      @endif
      @if($checker->routePermission('requests.index'))
      <li class="nav-item {{ Route::is('requests.index') ? 'active' : '' }}">
        <a href="{{ route('requests.index') }}" class="nav-link">
          <i class="fa-solid fa-person-circle-question"></i>
          Requests
        </a>
      </li>
      @endif
      @if($checker->routePermission('residents.index'))
      <li class="nav-item {{ Route::is('residents.index') ? 'active' : '' }}">
        <a href="{{ route('residents.index') }}" class="nav-link">
          <i class="fa-solid fa-house"></i>
          Residents
        </a>
      </li>
      @endif
      @if($checker->routePermission('complaints.index'))
      <li class="nav-item {{ Route::is('complaints.index') ? 'active' : '' }}">
        <a href="{{ route('complaints.index') }}" class="nav-link">
          <i class="fa-solid fa-flag"></i>
          Complaints
        </a>
      </li>
      @endif
      @if($checker->routePermission('visitors.index'))
      <li class="nav-item {{ Route::is('visitors.index') ? 'active' : '' }}">
        <a href="{{ route('visitors.index') }}" class="nav-link">
          <i class="fa-solid fa-address-book"></i>
          Visitors
        </a>
      </li>
      @endif
      @if($checker->routePermission('reports.index') || $checker->routePermission('reports.filter'))
      <li class="nav-item {{ Route::is('reports.index') || Route::is('reports.filter') ? 'active' : '' }}">
        <a href="{{ route('reports.index') }}" class="nav-link">
          <i class="fa-solid fa-file-excel"></i>
          Reports
        </a>
      </li>
      @endif
      @if($checker->routePermission('settings.users') || $checker->routePermission('settings.roles'))
      <li>
        <a href="#" class="nav-link collapsible {{ Route::is('settings.users') || Route::is('settings.roles') || Route::is('settings.referentials') || Route::is('settings.system_setup') ? '' : 'collapsed' }}" data-bs-toggle="collapse" data-bs-target="#settings-collapse" aria-expanded="true">
          <i class="fa-solid fa-gear"></i>
          Settings
        </a>
        <div class="collapse {{ Route::is('settings.users') || Route::is('settings.roles') || Route::is('settings.referentials') || Route::is('settings.system_setup') ? 'show' : '' }}" id="settings-collapse">
          <ul class="msublist">
            <li class="{{ Route::is('settings.users') ? 'active' : '' }}">
                <a href="{{ route('settings.users') }}" class="nav-link">
                    <i class="fa-solid fa-users"></i>
                    Users
                </a>
            </li>
            <li class="{{ Route::is('settings.roles') ? 'active' : '' }}">
                <a href="{{ route('settings.roles') }}" class="nav-link">
                    <i class="fa-solid fa-user-lock"></i>
                    User Roles
                </a>
            </li>
            <li class="{{ Route::is('settings.referentials') ? 'active' : '' }}">
                <a href="{{ route('settings.referentials') }}" class="nav-link">
                    <i class="fa-solid fa-circle-info"></i>
                    Referentials
                </a>
            </li>
            <li class="{{ Route::is('settings.system_setup') ? 'active' : '' }}">
                <a href="{{ route('settings.system_setup') }}" class="nav-link">
                    <i class="fa-solid fa-gears"></i>
                    System Setup
                </a>
            </li>
          </ul>
        </div>
      </li>
      @endif
      <li>
        <a href="{{ route('logout') }}" class="nav-link">
          <i class="fa-solid fa-right-from-bracket"></i>
          Log Out
        </a>
      </li>
    </ul>
</div>
<div class="mobile-sidebar shadow-sm">
    <a href="/" class="text-decoration-none sm-img shadow">
      <img src="{{ asset('images/logo.jpg') }}" class="img-fluid rounded-3">
    </a>
    <button class="btn-menu rounded-3" data-bs-toggle="offcanvas" data-bs-target="#offcanvasExample">
      <i class="fa-solid fa-bars"></i>
    </button>
</div>
<div class="offcanvas offcanvas-start w-75" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
  <div class="offcanvas-header mheader">
    <h5 class="offcanvas-title" id="offcanvasExampleLabel">&nbsp;</h5>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body">
    <div class="m-4 mx-auto img-cont shadow border-light rounded-3">
      <a href="/" class="d-flex align-items-center text-decoration-none">
        <img src="{{ asset('images/logo.jpg') }}" class="img-fluid rounded-3">
      </a>
    </div>
    <div class="m-4 mx-auto px-3 profile_name">
      <h6>Welcome back</h6>
      <a href="#" class="text-decoration-none"><h5>{{ auth()->user()->name }}</h5></a>
    </div>
    <ul class="flex-column mb-auto mlist">
      @if($checker->routePermission('dashboard'))
      <li class="nav-item {{ Route::is('dashboard') ? 'active' : '' }}">
        <a href="{{ route('dashboard') }}" class="nav-link">
          <i class="fa-solid fa-chart-line"></i>
          Dashboard
        </a>
      </li>
      @endif
      @if($checker->routePermission('financials.index'))
      <li class="nav-item {{ Route::is('financials.index') ? 'active' : '' }}">
        <a href="{{ route('financials.index') }}" class="nav-link">
          <i class="fa-solid fa-coins"></i>
          Financials
        </a>
      </li>
      @endif
      @if($checker->routePermission('requests.index'))
      <li class="nav-item {{ Route::is('requests.index') ? 'active' : '' }}">
        <a href="{{ route('requests.index') }}" class="nav-link">
          <i class="fa-solid fa-person-circle-question"></i>
          Requests
        </a>
      </li>
      @endif
      @if($checker->routePermission('residents.index'))
      <li class="nav-item {{ Route::is('residents.index') ? 'active' : '' }}">
        <a href="{{ route('residents.index') }}" class="nav-link">
          <i class="fa-solid fa-house"></i>
          Residents
        </a>
      </li>
      @endif
      @if($checker->routePermission('complaints.index'))
      <li class="nav-item {{ Route::is('complaints.index') ? 'active' : '' }}">
        <a href="{{ route('complaints.index') }}" class="nav-link">
          <i class="fa-solid fa-flag"></i>
          Complaints
        </a>
      </li>
      @endif
      @if($checker->routePermission('visitors.index'))
      <li class="nav-item {{ Route::is('visitors.index') ? 'active' : '' }}">
        <a href="{{ route('visitors.index') }}" class="nav-link">
          <i class="fa-solid fa-address-book"></i>
          Visitors
        </a>
      </li>
      @endif
      @if($checker->routePermission('reports.index') || $checker->routePermission('reports.filter'))
      <li class="nav-item {{ Route::is('reports.index') || Route::is('reports.filter') ? 'active' : '' }}">
        <a href="{{ route('reports.index') }}" class="nav-link">
          <i class="fa-solid fa-file-excel"></i>
          Reports
        </a>
      </li>
      @endif
      @if($checker->routePermission('settings.users') || $checker->routePermission('settings.roles'))
      <li>
        <a href="#" class="nav-link collapsible {{ Route::is('settings.users') || Route::is('settings.roles') || Route::is('settings.referentials') || Route::is('settings.system_setup') ? '' : 'collapsed' }}" data-bs-toggle="collapse" data-bs-target="#settings-collapse" aria-expanded="true">
          <i class="fa-solid fa-gear"></i>
          Settings
        </a>
        <div class="collapse {{ Route::is('settings.users') || Route::is('settings.roles') || Route::is('settings.referentials') || Route::is('settings.system_setup') ? 'show' : '' }}" id="settings-collapse">
          <ul class="msublist">
            <li class="{{ Route::is('settings.users') ? 'active' : '' }}">
                <a href="{{ route('settings.users') }}" class="nav-link">
                    <i class="fa-solid fa-users"></i>
                    Users
                </a>
            </li>
            <li class="{{ Route::is('settings.roles') ? 'active' : '' }}">
                <a href="{{ route('settings.roles') }}" class="nav-link">
                    <i class="fa-solid fa-user-lock"></i>
                    User Roles
                </a>
            </li>
            <li class="{{ Route::is('settings.referentials') ? 'active' : '' }}">
                <a href="{{ route('settings.referentials') }}" class="nav-link">
                    <i class="fa-solid fa-circle-info"></i>
                    Referentials
                </a>
            </li>
            <li class="{{ Route::is('settings.system_setup') ? 'active' : '' }}">
                <a href="{{ route('settings.system_setup') }}" class="nav-link">
                    <i class="fa-solid fa-gears"></i>
                    System Setup
                </a>
            </li>
          </ul>
        </div>
      </li>
      @endif
      <li>
        <a href="{{ route('logout') }}" class="nav-link">
          <i class="fa-solid fa-right-from-bracket"></i>
          Log Out
        </a>
      </li>
    </ul>
  </div>
</div>