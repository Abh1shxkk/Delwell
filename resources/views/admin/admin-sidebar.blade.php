<nav class="sidebar sidebar-offcanvas" id="sidebar">
          <ul class="nav">
            <li class="nav-item nav-profile">
              <a href="{{ route('admin.profile.settings') }}" class="nav-link">
                <div class="nav-profile-image">
                  @php
                    $admin = Auth::guard('admin')->user();
                    $profileImage = asset('dist/images/faces/face1.jpg');
                    
                    if ($admin && $admin->profile_image) {
                      $profileImage = url('storage/' . $admin->profile_image);
                    }
                  @endphp
                  <img src="{{ $profileImage }}" alt="profile" onerror="this.src='{{ asset('dist/images/faces/face1.jpg') }}'" />
                  <span class="login-status online"></span>
                  <!--change to offline or busy as needed-->
                </div>
                <div class="nav-profile-text d-flex flex-column">
                  <span class="font-weight-bold mb-2">{{ Auth::guard('admin')->user()->username }}</span>
                  <span class="text-secondary text-small">{{ Auth::guard('admin')->user()->email }}</span>
                </div>
                <i class="mdi mdi-bookmark-check text-success nav-profile-badge"></i>
              </a>
            </li>
            
            <li class="nav-item">
              <a class="nav-link" href="{{ route('admin.dashboard') }}">
                <i class="fa fa-th-large menu-icon"></i>
                <span class="menu-title">Dashboard</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="{{ route('admin.email-verification') }}">
                <i class="fa fa-check-circle menu-icon"></i>
                <span class="menu-title">Email Verification</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="{{ route('admin.user-management.index') }}">
                <i class="fa fa-users menu-icon"></i>
                <span class="menu-title">User Management</span>
              </a>
            </li>

            <li class="nav-item">
              <a class="nav-link" data-bs-toggle="collapse" href="#ui-waitlist" aria-expanded="false" aria-controls="ui-waitlist">
                <i class="fa fa-list-alt menu-icon"></i>
                <span class="menu-title">Waitlist</span>
                <i class="menu-arrow"></i>
              </a>
              <div class="collapse" id="ui-waitlist">
                <ul class="nav flex-column sub-menu">
                  <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.waitlist') }}">Applications</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.waitlist-questions.index') }}">Manage Questions</a>
                  </li>
                </ul>
              </div>
            </li>

            <li class="nav-item">
              <a class="nav-link" href="{{ route('admin.quotes.index') }}">
                <i class="fa fa-quote-left menu-icon"></i>
                <span class="menu-title">Manage Quotes</span>
              </a>
            </li>

            <li class="nav-item">
              <a class="nav-link" href="{{ route('admin.contact-messages') }}">
                <i class="fa fa-envelope menu-icon"></i>
                <span class="menu-title">Contact Messages</span>
              </a>
            </li>

            <li class="nav-item">
              <a class="nav-link" href="{{ route('admin.quiz-results.index') }}">
                <i class="fa fa-chart-bar menu-icon"></i>
                <span class="menu-title">Quiz Results</span>
              </a>
            </li>

            <li class="nav-item">
              <a class="nav-link" data-bs-toggle="collapse" href="#ui-register" aria-expanded="false" aria-controls="ui-register">
                <i class="fa fa-user menu-icon"></i>
                <span class="menu-title">Registration</span>
                <i class="menu-arrow"></i>
              </a>
              <div class="collapse" id="ui-register">
                <ul class="nav flex-column sub-menu">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.registration.addsection') }}">Add Section</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.registration.addquiz') }}">Add Quiz</a>
                  </li>
                </ul>
              </div>
            </li> 
          </ul>
        </nav>