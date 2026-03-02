<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>@yield('title', 'Admin Dashboard')</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="{{ asset('dist/vendors/mdi/css/materialdesignicons.min.css') }}">
  <link rel="stylesheet" href="{{ asset('dist/vendors/ti-icons/css/themify-icons.css') }}">
  <link rel="stylesheet" href="{{ asset('dist/vendors/css/vendor.bundle.base.css') }}">
  <link rel="stylesheet" href="{{ asset('dist/vendors/font-awesome/css/font-awesome.min.css') }}">
  <!-- endinject -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <!-- Plugin css for this page -->
  <link rel="stylesheet" href="{{ asset('dist/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- DataTables CSS -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
  <!-- Toastr CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <!-- endinject -->
  <!-- Layout styles -->
  <link rel="stylesheet" href="{{ asset('dist/css/style.css') }}">
  <link rel="stylesheet" href="{{ asset('css/admin-style.css') }}">
  <!-- End layout styles -->
  <link rel="shortcut icon" href="{{ asset('dist/images/favicon.png') }}" />
  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  @stack('styles')
</head>

<body>

  <div class="container-scroller">

    <!-- partial:partials/_navbar.html -->
    @include('admin.admin-header')
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      <!-- partial:partials/_sidebar.html -->

      @include('admin.admin-sidebar')

      <!-- partial -->
      <div class="main-panel">
        <div class="content-wrapper">
          @yield('content')
        </div>
        <!-- content-wrapper ends -->
        <!-- partial:partials/_footer.html -->

        @include('admin.admin-footer')

        <!-- partial -->
      </div>
      <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->
  <!-- plugins:js -->
  <script src="{{ asset('dist/vendors/js/vendor.bundle.base.js') }}"></script>
  <!-- endinject -->
  <!-- Bootstrap JS (loaded early to ensure dropdown functionality) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <!-- Plugin js for this page -->
  <script src="{{ asset('dist/vendors/chart.js/chart.umd.js') }}"></script>
  <script src="{{ asset('dist/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
  <!-- End plugin js for this page -->
  <!-- inject:js -->
  <script src="{{ asset('dist/js/off-canvas.js') }}"></script>
  <script src="{{ asset('dist/js/misc.js') }}"></script>
  <script src="{{ asset('dist/js/settings.js') }}"></script>
  <script src="{{ asset('dist/js/todolist.js') }}"></script>
  <script src="{{ asset('dist/js/jquery.cookie.js') }}"></script>
  <!-- endinject -->
  
  <!-- DataTables JS -->
  <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
  <!-- Toastr JS -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

  <!-- Custom js for this page -->
  @if(Route::currentRouteName() !== 'admin.addquiz')
  <script src="{{ asset('dist/js/dashboard.js') }}"></script>
  @endif
  
  <!-- Custom Dropdown Handler -->
  <script src="{{ asset('js/admin-dropdown.js') }}"></script>

  @yield('scripts')
  @stack('scripts')
  <!-- End custom js for this page -->
</body>

</html>