<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
        <img src="{{ asset('/admin/dist/img/AdminLTELogo.png') }}" alt="AdminLTE Logo"
            class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">Adminku</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            @php
                $photo = auth()->user()->photo;
            @endphp
            @if ($photo != null)
                <div class="image">
                    <img src="{{ asset("foto_user/$photo") }}" class="img-circle elevation-2"
                        style="width: 40px;height:40px;object-fit:cover" alt="User Image">
                </div>
            @else
                <div class="image">
                    <img src="{{ 'https://picsum.photos/' . 100 }}" class="img-circle elevation-2"
                        style="width: 40px;height:40px;object-fit:cover" alt="User Image">
                </div>
            @endif
            <div class="info">
                <a href="#" class="d-block">{{ auth()->user()->email }}</a>
            </div>
        </div>

        <!-- SidebarSearch Form -->
        <div class="form-inline">
            <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sidebar" type="search" placeholder="Search"
                    aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-sidebar">
                        <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar nav-collapse-hide-child nav-child-indent flex-column"
                data-widget="treeview" role="menu" data-accordion="false" id="list-sidebar">
                <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
                {{-- Dashboard --}}
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}"
                        class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-chart-line bkg-blue"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>
                {{-- Data Pegawai --}}
                @if (auth()->user()->role == 'admin')
                    <li
                        class="nav-item 
                    {{ request()->routeIs('admin.data-user') ? 'menu-open' : '' }}
                    {{ request()->routeIs('admin.data-user-create') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-user-friends  bkg-green"></i>
                            <p>
                                Data Admin
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('admin.data-user') }}"
                                    class="nav-link {{ request()->routeIs('admin.data-user') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Daftar User</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.data-user-create') }}"
                                    class="nav-link {{ request()->routeIs('admin.data-user-create') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Tambah User</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif

                @if (auth()->user()->role == 'user')
                    <li class="nav-item">
                        <a href="{{ route('user.profile') }}"
                            class="nav-link {{ request()->routeIs('user.profile') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-chart-line bkg-blue"></i>
                            <p>
                                Profile
                            </p>
                        </a>
                    </li>
                @endif

                <li class="nav-item">
                    <a href="{{ route('logout') }}" class="nav-link " onclick="logout(event)">
                        <i class="nav-icon fas fa-sign-out-alt bkg-red"></i>
                        <p>
                            Logout
                        </p>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </li>
            </ul>
        </nav>
    </div>
    <!-- /.sidebar -->
</aside>
