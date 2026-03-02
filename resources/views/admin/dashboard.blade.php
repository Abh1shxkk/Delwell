@extends('admin.layout')
@section('title', 'Dashboard')
@section('content')


<div class="page-header">
  <h3 class="page-title">
  <span class="page-title-icon bg-gradient-primary text-white me-2">
    <i class="mdi mdi-home"></i>
  </span> Dashboard
  </h3>
  <nav aria-label="breadcrumb">
    <ul class="breadcrumb">
      <li class="breadcrumb-item active" aria-current="page">
        <span></span>Overview <i class="mdi mdi-alert-circle-outline icon-sm text-primary align-middle"></i>
      </li>
    </ul>
  </nav>
</div>
<!-- Bento Grid Layout with Bootstrap -->
<div class="row g-3 g-lg-4">
  <!-- Large Card: User Statistics -->
  <div class="col-12 col-lg-8">
    <div class="card card-gradient-primary border-0 shadow-sm h-100">
      <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-start mb-3">
          <div>
            <h4 class="card-title text-white mb-1 fw-semibold">Total Users</h4>
            <p class="text-white-50 mb-0 small">Active members</p>
          </div>
          <div class="icon-circle bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
            <i class="mdi mdi-account-multiple text-white fs-3"></i>
          </div>
        </div>
        <h2 class="text-white mb-3 display-4 fw-bold">{{ \App\Models\User::count() }}</h2>
        <div class="d-flex justify-content-between text-white-50 small">
          <span><i class="mdi mdi-arrow-up"></i> +{{ \App\Models\User::whereDate('created_at', today())->count() }} today</span>
          <span>{{ \App\Models\User::where('created_at', '>=', now()->subDays(7))->count() }} this week</span>
        </div>
      </div>
    </div>
  </div>

  <!-- Medium Card: Quiz Sections -->
  <div class="col-12 col-sm-6 col-lg-4">
    <div class="card card-gradient-success border-0 shadow-sm h-100">
      <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-start mb-2">
          <h5 class="card-title text-white mb-0 fw-semibold">Quiz Sections</h5>
          <i class="mdi mdi-file-document-outline text-white fs-3"></i>
        </div>
        <h3 class="text-white mb-2 fw-bold">{{ \App\Models\QuizSection::count() }}</h3>
        <p class="text-white-50 mb-3 small">{{ \App\Models\QuizQuestion::count() }} total questions</p>
        <a href="{{ route('admin.quiz') }}" class="btn btn-light btn-sm">Manage Quizzes</a>
      </div>
    </div>
  </div>

  <!-- Small Card: Waitlist -->
  <div class="col-6 col-sm-6 col-lg-3">
    <div class="card card-gradient-warning border-0 shadow-sm h-100">
      <div class="card-body p-3 p-lg-4">
        <i class="mdi mdi-clock-outline text-white fs-2 mb-2"></i>
        <h6 class="text-white mb-1 fw-semibold">Waitlist</h6>
        <h3 class="text-white mb-0 fw-bold">{{ \App\Models\WaitlistApplication::count() }}</h3>
      </div>
    </div>
  </div>

  <!-- Small Card: Contact Messages -->
  <div class="col-6 col-sm-6 col-lg-3">
    <div class="card card-gradient-info border-0 shadow-sm h-100">
      <div class="card-body p-3 p-lg-4">
        <i class="mdi mdi-email-outline text-white fs-2 mb-2"></i>
        <h6 class="text-white mb-1 fw-semibold">Messages</h6>
        <h3 class="text-white mb-0 fw-bold">{{ \App\Models\ContactMessage::count() }}</h3>
      </div>
    </div>
  </div>

  <!-- Medium Card: User Matches -->
  <div class="col-12 col-sm-6 col-lg-3">
    <div class="card card-gradient-danger border-0 shadow-sm h-100">
      <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-start mb-2">
          <h6 class="card-title text-white mb-0 fw-semibold">User Matches</h6>
          <i class="mdi mdi-heart-multiple text-white fs-3"></i>
        </div>
        <h3 class="text-white mb-2 fw-bold">{{ \App\Models\UserMatch::count() }}</h3>
        <p class="text-white-50 mb-0 small">Total connections</p>
      </div>
    </div>
  </div>

  <!-- Medium Card: Circle Invitations -->
  <div class="col-12 col-sm-6 col-lg-3">
    <div class="card card-gradient-purple border-0 shadow-sm h-100">
      <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-start mb-2">
          <h6 class="card-title text-white mb-0 fw-semibold">Circle Invites</h6>
          <i class="mdi mdi-account-group text-white fs-3"></i>
        </div>
        <h3 class="text-white mb-2 fw-bold">{{ \App\Models\CircleInvitation::count() }}</h3>
        <p class="text-white-50 mb-0 small">{{ \App\Models\CircleMember::count() }} members</p>
      </div>
    </div>
  </div>

  <!-- Wide Card: Quick Actions -->
  <div class="col-12 col-lg-6">
    <div class="card border-0 shadow-sm h-100">
      <div class="card-body p-4">
        <h5 class="card-title mb-3 fw-semibold">
          <i class="mdi mdi-lightning-bolt text-warning"></i> Quick Actions
        </h5>
        <div class="row g-2 g-lg-3">
          <div class="col-6 col-xl-3">
            <a href="{{ route('admin.email-verification') }}" class="quick-action-btn d-flex flex-column align-items-center justify-content-center text-decoration-none p-3 rounded-3 text-white">
              <i class="mdi mdi-account-multiple fs-2 mb-2"></i>
              <span class="small fw-medium text-center">Manage Users</span>
            </a>
          </div>
          <div class="col-6 col-xl-3">
            <a href="{{ route('admin.quiz') }}" class="quick-action-btn d-flex flex-column align-items-center justify-content-center text-decoration-none p-3 rounded-3 text-white">
              <i class="mdi mdi-file-document fs-2 mb-2"></i>
              <span class="small fw-medium text-center">Edit Quizzes</span>
            </a>
          </div>
          <div class="col-6 col-xl-3">
            <a href="{{ route('admin.quotes.index') }}" class="quick-action-btn d-flex flex-column align-items-center justify-content-center text-decoration-none p-3 rounded-3 text-white">
              <i class="mdi mdi-format-quote-close fs-2 mb-2"></i>
              <span class="small fw-medium text-center">Manage Quotes</span>
            </a>
          </div>
          <div class="col-6 col-xl-3">
            <a href="{{ route('admin.waitlist') }}" class="quick-action-btn d-flex flex-column align-items-center justify-content-center text-decoration-none p-3 rounded-3 text-white">
              <i class="mdi mdi-clipboard-list fs-2 mb-2"></i>
              <span class="small fw-medium text-center">View Waitlist</span>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Tall Card: Recent Activity -->
  <div class="col-12 col-lg-6">
    <div class="card border-0 shadow-sm h-100">
      <div class="card-body p-4">
        <h5 class="card-title mb-3 fw-semibold">
          <i class="mdi mdi-history text-primary"></i> Recent Activity
        </h5>
        <div class="activity-list overflow-auto" style="max-height: 300px;">
          @php
            $recentUsers = \App\Models\User::latest()->take(5)->get();
          @endphp
          @forelse($recentUsers as $user)
          <div class="d-flex align-items-center py-3 border-bottom">
            <div class="activity-icon bg-gradient-primary rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0" style="width: 40px; height: 40px;">
              <i class="mdi mdi-account-plus text-white"></i>
            </div>
            <div class="flex-grow-1">
              <p class="mb-0 fw-medium"><strong>{{ $user->name }}</strong> joined</p>
              <small class="text-muted">{{ $user->created_at->diffForHumans() }}</small>
            </div>
          </div>
          @empty
          <p class="text-muted mb-0">No recent activity</p>
          @endforelse
        </div>
      </div>
    </div>
  </div>

  <!-- Wide Card: System Overview -->
  <div class="col-12">
    <div class="card border-0 shadow-sm">
      <div class="card-body p-4">
        <h5 class="card-title mb-4 fw-semibold">
          <i class="mdi mdi-chart-line text-success"></i> System Overview
        </h5>
        <div class="row g-3 g-lg-4">
          <div class="col-6 col-md-3">
            <div class="d-flex align-items-center gap-3">
              <div class="stat-icon bg-primary bg-opacity-10 rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 50px; height: 50px;">
                <i class="mdi mdi-format-quote-close text-primary fs-4"></i>
              </div>
              <div>
                <h4 class="mb-0 fw-semibold">{{ \App\Models\AdminQuote::count() }}</h4>
                <p class="text-muted mb-0 small">Admin Quotes</p>
              </div>
            </div>
          </div>
          <div class="col-6 col-md-3">
            <div class="d-flex align-items-center gap-3">
              <div class="stat-icon bg-success bg-opacity-10 rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 50px; height: 50px;">
                <i class="mdi mdi-email-check text-success fs-4"></i>
              </div>
              <div>
                <h4 class="mb-0 fw-semibold">{{ \App\Models\ContactMessage::where('created_at', '>=', now()->subDays(7))->count() }}</h4>
                <p class="text-muted mb-0 small">Messages (7d)</p>
              </div>
            </div>
          </div>
          <div class="col-6 col-md-3">
            <div class="d-flex align-items-center gap-3">
              <div class="stat-icon bg-warning bg-opacity-10 rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 50px; height: 50px;">
                <i class="mdi mdi-account-clock text-warning fs-4"></i>
              </div>
              <div>
                <h4 class="mb-0 fw-semibold">{{ \App\Models\WaitlistApplication::whereDate('created_at', today())->count() }}</h4>
                <p class="text-muted mb-0 small">New Waitlist</p>
              </div>
            </div>
          </div>
          <div class="col-6 col-md-3">
            <div class="d-flex align-items-center gap-3">
              <div class="stat-icon bg-danger bg-opacity-10 rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 50px; height: 50px;">
                <i class="mdi mdi-heart text-danger fs-4"></i>
              </div>
              <div>
                <h4 class="mb-0 fw-semibold">{{ \App\Models\UserMatch::where('created_at', '>=', now()->subDays(7))->count() }}</h4>
                <p class="text-muted mb-0 small">Matches (7d)</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@push('styles')
<style>
/* Card Gradients - Professional & Minimal */
.card-gradient-primary {
  background: linear-gradient(135deg, #4F46E5 0%, #6366F1 100%);
}

.card-gradient-success {
  background: linear-gradient(135deg, #059669 0%, #10B981 100%);
}

.card-gradient-warning {
  background: linear-gradient(135deg, #EC4899 0%, #F472B6 100%);
}

.card-gradient-info {
  background: linear-gradient(135deg, #0EA5E9 0%, #38BDF8 100%);
}

.card-gradient-danger {
  background: linear-gradient(135deg, #EF4444 0%, #F87171 100%);
}

.card-gradient-purple {
  background: linear-gradient(135deg, #8B5CF6 0%, #A78BFA 100%);
}

/* Quick Actions */
.quick-action-btn {
  background: linear-gradient(135deg, #6366F1 0%, #8B5CF6 100%);
  transition: all 0.3s ease;
  min-height: 100px;
}

.quick-action-btn:hover {
  transform: translateY(-5px);
  box-shadow: 0 10px 20px rgba(99, 102, 241, 0.25);
  color: white !important;
}

/* Card Hover Effect */
.card {
  transition: all 0.3s ease;
}

.card:hover {
  transform: translateY(-2px);
}

/* Scrollbar Styling */
.activity-list::-webkit-scrollbar {
  width: 6px;
}

.activity-list::-webkit-scrollbar-track {
  background: #f1f1f1;
  border-radius: 10px;
}

.activity-list::-webkit-scrollbar-thumb {
  background: #888;
  border-radius: 10px;
}

.activity-list::-webkit-scrollbar-thumb:hover {
  background: #555;
}

/* Responsive adjustments for quick actions */
@media (max-width: 1199px) {
  .quick-action-btn {
    min-height: 90px;
  }
}

@media (max-width: 575px) {
  .quick-action-btn {
    min-height: 80px;
  }
  .quick-action-btn i {
    font-size: 1.5rem !important;
  }
}
</style>
@endpush

@endsection




