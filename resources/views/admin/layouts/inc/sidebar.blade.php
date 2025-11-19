  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->

    <a href="#" class="brand-link text-sm">
      <img src="/vendor/adminlte/dist/img/AdminLTELogo.png" alt="Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">{!! config('lte3.logo') !!}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        @if(config('lte3.view.sidebar.search'))
        <!-- SidebarSearch Form -->
        <div class="form-inline">
            <div class="input-group" data-widget="sidebar-search">
            <input class="form-control form-control-sidebar" type="search" placeholder="Пошук" aria-label="Search">
            <div class="input-group-append">
                <button class="btn btn-sidebar">
                <i class="fas fa-search fa-fw"></i>
                </button>
            </div>
            </div>
        </div>
        @endif

        @include('admin.layouts.inc.sidebar-menu.shop')

        @include('admin.layouts.inc.sidebar-menu.system')
    </div>
    <!-- /.sidebar -->
  </aside>
