@extends('admin.layout')

@section('title', 'Email Verification Management')

@section('content')
<div class="container-fluid px-0">
    <div class="card shadow-sm">
        <!-- Header -->
        <div class="card-header border-bottom">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h2 fw-bold text-dark mb-0">Email Verification Management</h1>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-success btn-sm d-flex align-items-center" id="bulkVerifyBtn">
                        <i class="fas fa-check me-2"></i>Bulk Verify
                    </button>
                    <button type="button" class="btn btn-primary btn-sm d-flex align-items-center" id="bulkResendBtn">
                        <i class="fas fa-envelope me-2"></i>Bulk Resend
                    </button>
                    <button type="button" class="btn btn-warning btn-sm d-flex align-items-center" id="cleanExpiredBtn">
                        <i class="fas fa-broom me-2"></i>Clean Expired
                    </button>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="card-body border-bottom">
            <div class="row g-3" id="statsCards">
                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 bg-light">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <div class="text-uppercase small fw-medium text-muted mb-1">Total Users</div>
                                    <div class="h4 fw-bold text-primary mb-0" id="totalUsers">{{ number_format($stats['total']) }}</div>
                                </div>
                                <div class="ms-3">
                                    <i class="fas fa-users fa-2x text-primary opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 bg-light">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <div class="text-uppercase small fw-medium text-muted mb-1">Verified</div>
                                    <div class="h4 fw-bold text-success mb-0" id="verifiedUsers">{{ number_format($stats['verified']) }}</div>
                                    <div class="small text-muted" id="verifiedPercent">{{ $stats['total'] > 0 ? round(($stats['verified'] / $stats['total']) * 100, 1) : 0 }}%</div>
                                </div>
                                <div class="ms-3">
                                    <i class="fas fa-check-circle fa-2x text-success opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 bg-light">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <div class="text-uppercase small fw-medium text-muted mb-1">Unverified</div>
                                    <div class="h4 fw-bold text-warning mb-0" id="unverifiedUsers">{{ number_format($stats['unverified']) }}</div>
                                    <div class="small text-muted" id="unverifiedPercent">{{ $stats['total'] > 0 ? round(($stats['unverified'] / $stats['total']) * 100, 1) : 0 }}%</div>
                                </div>
                                <div class="ms-3">
                                    <i class="fas fa-exclamation-circle fa-2x text-warning opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 bg-light">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <div class="text-uppercase small fw-medium text-muted mb-1">Expired Tokens</div>
                                    <div class="h4 fw-bold text-danger mb-0" id="expiredTokens">{{ number_format($stats['expired']) }}</div>
                                </div>
                                <div class="ms-3">
                                    <i class="fas fa-clock fa-2x text-danger opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- DataTable -->
        <div class="card-body p-4">
            <div class="table-responsive">
                <table id="usersTable" class="table table-striped table-hover">
                    <thead class="table-light">
                        <tr>
                            <th class="text-uppercase small fw-medium text-muted">
                                <input type="checkbox" id="selectAll" class="form-check-input">
                            </th>
                            <th class="text-uppercase small fw-medium text-muted">User</th>
                            <th class="text-uppercase small fw-medium text-muted">Email</th>
                            <th class="text-uppercase small fw-medium text-muted">Status</th>
                            <th class="text-uppercase small fw-medium text-muted">Registered</th>
                            <th class="text-uppercase small fw-medium text-muted">Token</th>
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

<!-- Verify User Confirmation Modal -->
<div id="verifyModal" class="modal fade" tabindex="-1" style="display: none;">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-body text-center p-4">
                <div class="d-flex justify-content-center align-items-center bg-success bg-opacity-10 rounded-circle mx-auto mb-3" style="width: 48px; height: 48px;">
                    <i class="fas fa-check text-success"></i>
                </div>
                <h5 class="modal-title mb-3">Verify Email</h5>
                <p class="text-muted mb-4">Are you sure you want to manually verify this user's email?</p>
                <div class="d-flex gap-2 justify-content-center">
                    <button id="confirmVerify" class="btn btn-success">Verify</button>
                    <button id="cancelVerify" class="btn btn-secondary">Cancel</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Resend Verification Confirmation Modal -->
<div id="resendModal" class="modal fade" tabindex="-1" style="display: none;">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-body text-center p-4">
                <div class="d-flex justify-content-center align-items-center bg-primary bg-opacity-10 rounded-circle mx-auto mb-3" style="width: 48px; height: 48px;">
                    <i class="fas fa-envelope text-primary"></i>
                </div>
                <h5 class="modal-title mb-3">Resend Verification</h5>
                <p class="text-muted mb-4">Are you sure you want to resend the verification email to this user?</p>
                <div class="d-flex gap-2 justify-content-center">
                    <button id="confirmResend" class="btn btn-primary">Send Email</button>
                    <button id="cancelResend" class="btn btn-secondary">Cancel</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bulk Action Confirmation Modal -->
<div id="bulkModal" class="modal fade" tabindex="-1" style="display: none;">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-body text-center p-4">
                <div class="d-flex justify-content-center align-items-center bg-info bg-opacity-10 rounded-circle mx-auto mb-3" style="width: 48px; height: 48px;">
                    <i class="fas fa-users text-info"></i>
                </div>
                <h5 class="modal-title mb-3" id="bulkActionTitle">Bulk Action</h5>
                <p class="text-muted mb-4" id="bulkActionMessage">Are you sure you want to perform this action?</p>
                <div class="d-flex gap-2 justify-content-center">
                    <button id="confirmBulk" class="btn btn-info">Confirm</button>
                    <button id="cancelBulk" class="btn btn-secondary">Cancel</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Clean Expired Tokens Confirmation Modal -->
<div id="cleanModal" class="modal fade" tabindex="-1" style="display: none;">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-body text-center p-4">
                <div class="d-flex justify-content-center align-items-center bg-warning bg-opacity-10 rounded-circle mx-auto mb-3" style="width: 48px; height: 48px;">
                    <i class="fas fa-broom text-warning"></i>
                </div>
                <h5 class="modal-title mb-3">Clean Expired Tokens</h5>
                <p class="text-muted mb-4">Are you sure you want to clean all expired verification tokens? This action cannot be undone.</p>
                <div class="d-flex gap-2 justify-content-center">
                    <button id="confirmClean" class="btn btn-warning">Clean Tokens</button>
                    <button id="cancelClean" class="btn btn-secondary">Cancel</button>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<style>
/* Ensure toastr notifications are visible */
.toast {
    background-color: #030303 !important;
    color: #ffffff !important;
    opacity: 0.9 !important;
    box-shadow: 0 0 12px rgba(0,0,0,0.4) !important;
}

.toast-success {
    background-color: #51a351 !important;
    color: #ffffff !important;
}

.toast-error {
    background-color: #bd362f !important;
    color: #ffffff !important;
}

.toast-info {
    background-color: #2f96b4 !important;
    color: #ffffff !important;
}

.toast-warning {
    background-color: #f89406 !important;
    color: #ffffff !important;
}

.toast-title {
    font-weight: bold !important;
    color: #ffffff !important;
}

.toast-message {
    color: #ffffff !important;
    line-height: 18px !important;
}

/* DataTable responsive adjustments */
#usersTable {
    width: 100% !important;
}

#usersTable th,
#usersTable td {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

#usersTable td:nth-child(2) {
    white-space: normal !important;
    word-wrap: break-word;
}

#usersTable td:nth-child(3) {
    white-space: normal !important;
    word-wrap: break-word;
    max-width: 200px;
}

.table-responsive {
    overflow-x: hidden !important;
}

@media (max-width: 768px) {
    #usersTable td:nth-child(3) {
        max-width: 150px;
    }
}
</style>
<script>
    $(document).ready(function() {
        // Toastr Configuration
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };

        // Define API endpoints
        const API_ENDPOINTS = {
            verify: '{{ url("admin/email-verification/verify") }}',
            resend: '{{ url("admin/email-verification/resend") }}',
            bulkVerify: '{{ url("admin/email-verification/bulk-verify") }}',
            bulkResend: '{{ url("admin/email-verification/bulk-resend") }}',
            cleanExpired: '{{ url("admin/email-verification/clean-expired") }}',
            data: '{{ route("admin.email-verification") }}'
        };
        
        const CSRF_TOKEN = '{{ csrf_token() }}';

        // Initialize DataTable
        let table = $('#usersTable').DataTable({
            processing: true,
            serverSide: true,
            scrollX: false,
            autoWidth: false,
            ajax: {
                url: API_ENDPOINTS.data,
                data: function(d) {
                    d.format = 'json';
                }
            },
            columns: [
                {
                    data: 'id',
                    name: 'id',
                    width: '50px',
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row) {
                        return `<input type="checkbox" class="user-checkbox form-check-input" value="${data}">`;
                    }
                },
                {
                    data: 'user_info',
                    name: 'name',
                    width: '250px',
                    render: function(data, type, row) {
                        let profileImage = '';
                        if (row.ai_avatar_path) {
                            profileImage = `<img src="{{ url('storage') }}/${row.ai_avatar_path}" class="rounded-circle me-3" style="width: 40px; height: 40px; object-fit: cover;">`;
                        } else if (row.profile_image) {
                            profileImage = `<img src="{{ url('storage') }}/${row.profile_image}" class="rounded-circle me-3" style="width: 40px; height: 40px; object-fit: cover;">`;
                        } else {
                            profileImage = `<div class="rounded-circle me-3 bg-light d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;"><i class="fas fa-user text-muted"></i></div>`;
                        }
                        
                        const username = row.username ? `@${row.username}` : 'No username';
                        
                        return `
                            <div class="d-flex align-items-center">
                                ${profileImage}
                                <div>
                                    <div class="fw-medium text-dark">${row.name}</div>
                                    <small class="text-muted">${username}</small>
                                </div>
                            </div>
                        `;
                    }
                },
                {
                    data: 'email',
                    name: 'email',
                    width: '200px',
                    render: function(data, type, row) {
                        return `<span class="text-muted small">${data}</span>`;
                    }
                },
                {
                    data: 'verification_status',
                    name: 'email_verified',
                    width: '120px',
                    render: function(data, type, row) {
                        if (row.email_verified) {
                            let verifiedDate = '';
                            if (row.email_verified_at) {
                                const date = new Date(row.email_verified_at);
                                verifiedDate = `<br><small class="text-muted mt-1 d-block">${date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}</small>`;
                            }
                            return `<span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2 py-1"><i class="fas fa-check-circle me-1"></i>Verified</span>${verifiedDate}`;
                        } else {
                            return '<span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-2 py-1"><i class="fas fa-exclamation-circle me-1"></i>Unverified</span>';
                        }
                    }
                },
                {
                    data: 'created_at',
                    name: 'created_at',
                    width: '120px',
                    render: function(data, type, row) {
                        const date = new Date(data);
                        const formattedDate = date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
                        
                        // Calculate time ago without moment.js
                        const now = new Date();
                        const diffInMs = now - date;
                        const diffInMinutes = Math.floor(diffInMs / (1000 * 60));
                        const diffInHours = Math.floor(diffInMs / (1000 * 60 * 60));
                        const diffInDays = Math.floor(diffInMs / (1000 * 60 * 60 * 24));
                        
                        let timeAgo;
                        if (diffInDays > 0) {
                            timeAgo = diffInDays === 1 ? '1 day ago' : `${diffInDays} days ago`;
                        } else if (diffInHours > 0) {
                            timeAgo = diffInHours === 1 ? '1 hour ago' : `${diffInHours} hours ago`;
                        } else if (diffInMinutes > 0) {
                            timeAgo = diffInMinutes === 1 ? '1 minute ago' : `${diffInMinutes} minutes ago`;
                        } else {
                            timeAgo = 'Just now';
                        }
                        
                        return `<span class="text-muted">${formattedDate}<br><small class="text-muted">${timeAgo}</small></span>`;
                    }
                },
                {
                    data: 'token_status',
                    name: 'email_verification_token',
                    width: '100px',
                    render: function(data, type, row) {
                        if (row.email_verification_token) {
                            const createdAt = new Date(row.created_at);
                            const expiryTime = new Date(createdAt.getTime() + (24 * 60 * 60 * 1000));
                            const now = new Date();
                            
                            if (now > expiryTime) {
                                return '<span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-2 py-1"><i class="fas fa-clock me-1"></i>Expired</span>';
                            } else {
                                return '<span class="badge bg-info-subtle text-info border border-info-subtle rounded-pill px-2 py-1"><i class="fas fa-hourglass-half me-1"></i>Active</span>';
                            }
                        } else {
                            return '<span class="badge bg-secondary-subtle text-dark border border-secondary-subtle rounded-pill px-2 py-1">No Token</span>';
                        }
                    }
                },
                {
                    data: 'actions',
                    name: 'actions',
                    width: '120px',
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row) {
                        if (!row.email_verified) {
                            return `
                                <div class="btn-group" role="group">
                                    <button onclick="verifyUser(${row.id})" class="btn btn-success btn-sm" title="Manually verify email">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    <button onclick="resendVerification(${row.id})" class="btn btn-primary btn-sm" title="Resend verification email">
                                        <i class="fas fa-envelope"></i>
                                    </button>
                                </div>
                            `;
                        } else {
                            return '<span class="text-success small fw-medium"><i class="fas fa-check-circle me-1"></i>Verified</span>';
                        }
                    }
                }
            ],
            pageLength: 10,
            responsive: true,
            order: [[4, 'desc']], // Order by created_at
            drawCallback: function() {
                updateSelectAllCheckbox();
            }
        });

        // Global variables for modals
        let currentUserId = null;
        let currentAction = null;
        let selectedUsers = [];

        // Select All functionality
        $('#selectAll').on('change', function() {
            const isChecked = $(this).is(':checked');
            $('.user-checkbox').prop('checked', isChecked);
            updateSelectAllCheckbox();
        });

        // Individual checkbox change
        $(document).on('change', '.user-checkbox', function() {
            updateSelectAllCheckbox();
        });

        function updateSelectAllCheckbox() {
            const totalCheckboxes = $('.user-checkbox').length;
            const checkedCheckboxes = $('.user-checkbox:checked').length;
            
            $('#selectAll').prop('indeterminate', checkedCheckboxes > 0 && checkedCheckboxes < totalCheckboxes);
            $('#selectAll').prop('checked', checkedCheckboxes === totalCheckboxes && totalCheckboxes > 0);
        }

        function getSelectedUsers() {
            return $('.user-checkbox:checked').map(function() {
                return $(this).val();
            }).get();
        }

        // Modal Event Handlers
        $('#verifyModal').on('hidden.bs.modal', function() {
            currentUserId = null;
        });

        $('#resendModal').on('hidden.bs.modal', function() {
            currentUserId = null;
        });

        $('#bulkModal').on('hidden.bs.modal', function() {
            selectedUsers = [];
            currentAction = null;
        });

        // Button Event Handlers
        $('#cleanExpiredBtn').on('click', function() {
            $('#cleanModal').modal('show');
        });

        $('#bulkVerifyBtn').on('click', function() {
            selectedUsers = getSelectedUsers();
            if (selectedUsers.length === 0) {
                toastr.warning('Please select at least one user.');
                return;
            }
            currentAction = 'verify';
            $('#bulkActionTitle').text('Bulk Verify Users');
            $('#bulkActionMessage').text(`Are you sure you want to verify ${selectedUsers.length} selected user(s)?`);
            $('#confirmBulk').removeClass().addClass('btn btn-success').text('Verify Users');
            $('#bulkModal').modal('show');
        });

        $('#bulkResendBtn').on('click', function() {
            selectedUsers = getSelectedUsers();
            if (selectedUsers.length === 0) {
                toastr.warning('Please select at least one user.');
                return;
            }
            currentAction = 'resend';
            $('#bulkActionTitle').text('Bulk Resend Verification');
            $('#bulkActionMessage').text(`Are you sure you want to resend verification emails to ${selectedUsers.length} selected user(s)?`);
            $('#confirmBulk').removeClass().addClass('btn btn-primary').text('Send Emails');
            $('#bulkModal').modal('show');
        });

        // Confirmation handlers
        $('#confirmVerify').on('click', function() {
            if (currentUserId) {
                performVerifyUser(currentUserId);
                $('#verifyModal').modal('hide');
            }
        });

        $('#confirmResend').on('click', function() {
            if (currentUserId) {
                performResendVerification(currentUserId);
                $('#resendModal').modal('hide');
            }
        });

        $('#confirmBulk').on('click', function() {
            if (currentAction && selectedUsers.length > 0) {
                performBulkAction(currentAction, selectedUsers);
                $('#bulkModal').modal('hide');
            }
        });

        $('#confirmClean').on('click', function() {
            performCleanExpiredTokens();
            $('#cleanModal').modal('hide');
        });

        // Cancel handlers
        $('#cancelVerify, #cancelResend, #cancelBulk, #cancelClean').on('click', function() {
            $(this).closest('.modal').modal('hide');
        });

        // Update statistics
        function updateStatistics() {
            $.get(API_ENDPOINTS.data + '?stats_only=1', function(data) {
                if (data.stats) {
                    $('#totalUsers').text(new Intl.NumberFormat().format(data.stats.total));
                    $('#verifiedUsers').text(new Intl.NumberFormat().format(data.stats.verified));
                    $('#unverifiedUsers').text(new Intl.NumberFormat().format(data.stats.unverified));
                    $('#expiredTokens').text(new Intl.NumberFormat().format(data.stats.expired));
                    
                    // Update percentages
                    const total = data.stats.total;
                    const verifiedPercent = total > 0 ? Math.round((data.stats.verified / total) * 100 * 10) / 10 : 0;
                    const unverifiedPercent = total > 0 ? Math.round((data.stats.unverified / total) * 100 * 10) / 10 : 0;
                    
                    $('#verifiedPercent').text(verifiedPercent + '%');
                    $('#unverifiedPercent').text(unverifiedPercent + '%');
                }
            });
        }

        // API Functions
        window.verifyUser = function(userId) {
            currentUserId = userId;
            $('#verifyModal').modal('show');
        };

        window.resendVerification = function(userId) {
            currentUserId = userId;
            $('#resendModal').modal('show');
        };

        function performVerifyUser(userId) {
            $.ajax({
                url: `${API_ENDPOINTS.verify}/${userId}`,
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                    'Content-Type': 'application/json'
                },
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                        table.ajax.reload(null, false);
                        updateStatistics();
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function() {
                    toastr.error('An error occurred. Please try again.');
                }
            });
        }

        function performResendVerification(userId) {
            $.ajax({
                url: `${API_ENDPOINTS.resend}/${userId}`,
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                    'Content-Type': 'application/json'
                },
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                        table.ajax.reload(null, false);
                        updateStatistics();
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function() {
                    toastr.error('An error occurred. Please try again.');
                }
            });
        }

        function performBulkAction(action, userIds) {
            const endpoint = action === 'verify' ? API_ENDPOINTS.bulkVerify : API_ENDPOINTS.bulkResend;
            
            $.ajax({
                url: endpoint,
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                    'Content-Type': 'application/json'
                },
                data: JSON.stringify({
                    user_ids: userIds
                }),
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                        table.ajax.reload(null, false);
                        updateStatistics();
                        // Clear selections
                        $('.user-checkbox').prop('checked', false);
                        $('#selectAll').prop('checked', false).prop('indeterminate', false);
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function() {
                    toastr.error('An error occurred. Please try again.');
                }
            });
        }

        function performCleanExpiredTokens() {
            $.ajax({
                url: API_ENDPOINTS.cleanExpired,
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                    'Content-Type': 'application/json'
                },
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                        table.ajax.reload(null, false);
                        updateStatistics();
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function() {
                    toastr.error('An error occurred. Please try again.');
                }
            });
        }
    });
</script>
@endsection