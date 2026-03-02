@extends('admin.layout')

@section('title', 'Waitlist Questions Management')

@section('content')
<div class="container-fluid px-0">
    <div class="card shadow-sm">
        <!-- Header -->
        <div class="card-header border-bottom">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h2 fw-bold text-dark mb-0">Waitlist Questions Management</h1>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.waitlist-questions.create') }}" class="btn btn-primary btn-sm d-flex align-items-center">
                        <i class="fas fa-plus me-2"></i>Add New Question
                    </a>
                    <button type="button" class="btn btn-info btn-sm d-flex align-items-center" id="refreshBtn">
                        <i class="fas fa-sync me-2"></i>Refresh
                    </button>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="card-body border-bottom">
            <div class="row g-3" id="statsCards">
                <div class="col-xl-4 col-md-4 col-sm-6">
                    <div class="card border-0 bg-light">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <div class="text-uppercase small fw-medium text-muted mb-1">Total Questions</div>
                                    <div class="h4 fw-bold text-primary mb-0" id="totalQuestions">{{ number_format($stats['total']) }}</div>
                                </div>
                                <div class="ms-3">
                                    <i class="fas fa-question-circle fa-2x text-primary opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-md-4 col-sm-6">
                    <div class="card border-0 bg-light">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <div class="text-uppercase small fw-medium text-muted mb-1">Active</div>
                                    <div class="h4 fw-bold text-success mb-0" id="activeQuestions">{{ number_format($stats['active']) }}</div>
                                </div>
                                <div class="ms-3">
                                    <i class="fas fa-check-circle fa-2x text-success opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-md-4 col-sm-6">
                    <div class="card border-0 bg-light">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <div class="text-uppercase small fw-medium text-muted mb-1">Inactive</div>
                                    <div class="h4 fw-bold text-secondary mb-0" id="inactiveQuestions">{{ number_format($stats['inactive']) }}</div>
                                </div>
                                <div class="ms-3">
                                    <i class="fas fa-times-circle fa-2x text-secondary opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- DataTable -->
        <div class="card-body">
            <div class="table-responsive">
                <table id="questionsTable" class="table table-striped table-hover">
                    <thead class="table-light">
                        <tr>
                            <th class="text-uppercase small fw-medium text-muted">Order</th>
                            <th class="text-uppercase small fw-medium text-muted">Question</th>
                            <th class="text-uppercase small fw-medium text-muted">Type</th>
                            <th class="text-uppercase small fw-medium text-muted">Field Name</th>
                            <th class="text-uppercase small fw-medium text-muted">Required</th>
                            <th class="text-uppercase small fw-medium text-muted">Status</th>
                            <th class="text-uppercase small fw-medium text-muted">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- DataTable will populate this -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- View Details Modal -->
<div id="detailsModal" class="modal fade" tabindex="-1" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Question Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="questionDetails">
                    <!-- Details will be loaded here -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="modal fade" tabindex="-1" style="display: none;">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-body text-center p-4">
                <div class="d-flex justify-content-center align-items-center bg-danger bg-opacity-10 rounded-circle mx-auto mb-3" style="width: 48px; height: 48px;">
                    <i class="fas fa-trash text-danger"></i>
                </div>
                <h5 class="modal-title mb-3">Delete Question</h5>
                <p class="text-muted mb-4">Are you sure you want to delete this question? This action cannot be undone.</p>
                <div class="d-flex gap-2 justify-content-center">
                    <button id="confirmDelete" class="btn btn-danger">Delete</button>
                    <button id="cancelDelete" class="btn btn-secondary">Cancel</button>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<style>
/* Fix toastr transparency and styling */
#toast-container > div {
    opacity: 1 !important;
}

.toast-success {
    background-color: #51A351 !important;
    opacity: 1 !important;
}

.toast-error {
    background-color: #BD362F !important;
    opacity: 1 !important;
}

.toast-info {
    background-color: #2F96B4 !important;
    opacity: 1 !important;
}

.toast-warning {
    background-color: #F89406 !important;
    opacity: 1 !important;
}

.toast {
    opacity: 1 !important;
}
</style>
<script src="{{ asset('js/waitlist-questions.js') }}"></script>
<script>
    // Show success message from session if exists
    @if(session('success'))
        $(document).ready(function() {
            toastr.success('{{ session('success') }}');
        });
    @endif
</script>
@endsection
