<a class="nav-link" href="#" data-toggle="dropdown" id="profileDropdown">
  <span class="nav-profile-name">
    {{ Auth::guard('manager')->user()->username }}
  </span>
</a>

<br>
<br>

<a class="dropdown-item" href="{{ route('manager.logout'); }}">
<i class="typcn typcn-eject text-primary"></i>
Logout
</a>