@extends('admin.layout')

@section('title', 'Waitlist Management')

@section('content')
<div class="container-fluid px-0">
    <div class="card shadow-sm">
        <!-- Header -->
        <div class="card-header border-bottom">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h2 fw-bold text-dark mb-0">Waitlist Management</h1>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-success btn-sm d-flex align-items-center" id="bulkApproveBtn">
                        <i class="fas fa-check me-2"></i>Bulk Approve
                    </button>
                    <button type="button" class="btn btn-info btn-sm d-flex align-items-center" id="refreshBtn">
                        <i class="fas fa-sync me-2"></i>Refresh
                    </button>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="card-body border-bottom">
            <div class="row g-3" id="statsCards">
                <div class="col-xl-3 col-md-4 col-sm-6">
                    <div class="card border-0 bg-light">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <div class="text-uppercase small fw-medium text-muted mb-1">Total Applications</div>
                                    <div class="h4 fw-bold text-primary mb-0" id="totalApplications">{{ number_format($stats['total']) }}</div>
                                </div>
                                <div class="ms-3">
                                    <i class="fas fa-clipboard-list fa-2x text-primary opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-2 col-md-4 col-sm-6">
                    <div class="card border-0 bg-light">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <div class="text-uppercase small fw-medium text-muted mb-1">Pending</div>
                                    <div class="h4 fw-bold text-warning mb-0" id="pendingApplications">{{ number_format($stats['pending']) }}</div>
                                </div>
                                <div class="ms-3">
                                    <i class="fas fa-clock fa-2x text-warning opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-2 col-md-4 col-sm-6">
                    <div class="card border-0 bg-light">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <div class="text-uppercase small fw-medium text-muted mb-1">Approved</div>
                                    <div class="h4 fw-bold text-success mb-0" id="approvedApplications">{{ number_format($stats['approved']) }}</div>
                                </div>
                                <div class="ms-3">
                                    <i class="fas fa-check-circle fa-2x text-success opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-2 col-md-4 col-sm-6">
                    <div class="card border-0 bg-light">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <div class="text-uppercase small fw-medium text-muted mb-1">Rejected</div>
                                    <div class="h4 fw-bold text-danger mb-0" id="rejectedApplications">{{ number_format($stats['rejected']) }}</div>
                                </div>
                                <div class="ms-3">
                                    <i class="fas fa-times-circle fa-2x text-danger opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-2 col-md-4 col-sm-6">
                    <div class="card border-0 bg-light">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <div class="text-uppercase small fw-medium text-muted mb-1">Invited</div>
                                    <div class="h4 fw-bold text-info mb-0" id="invitedApplications">{{ number_format($stats['invited']) }}</div>
                                </div>
                                <div class="ms-3">
                                    <i class="fas fa-envelope fa-2x text-info opacity-75"></i>
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
                <table id="waitlistTable" class="table table-striped table-hover">
                    <thead class="table-light">
                        <tr>
                            <th class="text-uppercase small fw-medium text-muted">
                                <input type="checkbox" id="selectAll" class="form-check-input">
                            </th>
                            <th class="text-uppercase small fw-medium text-muted">Email</th>
                            <th class="text-uppercase small fw-medium text-muted">Status</th>
                            <th class="text-uppercase small fw-medium text-muted">Applied</th>
                            <th class="text-uppercase small fw-medium text-muted">Invite Code</th>
                            <th class="text-uppercase small fw-medium text-muted">Invite Sent</th>
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

<!-- Application Details Modal -->
<div id="detailsModal" class="modal fade" tabindex="-1" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Application Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="applicationDetails">
                    <!-- Details will be loaded here -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Approve Application Modal -->
<div id="approveModal" class="modal fade" tabindex="-1" style="display: none;">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-body text-center p-4">
                <div class="d-flex justify-content-center align-items-center bg-success bg-opacity-10 rounded-circle mx-auto mb-3" style="width: 48px; height: 48px;">
                    <i class="fas fa-check text-success"></i>
                </div>
                <h5 class="modal-title mb-3">Approve Application</h5>
                <p class="text-muted mb-4">This will approve the application and send an invitation code to the applicant's email.</p>
                <div class="d-flex gap-2 justify-content-center">
                    <button id="confirmApprove" class="btn btn-success">
                        <span class="btn-text">Approve & Send Invite</span>
                        <span class="btn-spinner d-none">
                            <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                            Processing...
                        </span>
                    </button>
                    <button id="cancelApprove" class="btn btn-secondary">Cancel</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reject Application Modal -->
<div id="rejectModal" class="modal fade" tabindex="-1" style="display: none;">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-body text-center p-4">
                <div class="d-flex justify-content-center align-items-center bg-danger bg-opacity-10 rounded-circle mx-auto mb-3" style="width: 48px; height: 48px;">
                    <i class="fas fa-times text-danger"></i>
                </div>
                <h5 class="modal-title mb-3">Reject Application</h5>
                <p class="text-muted mb-4">Are you sure you want to reject this application? This action cannot be undone.</p>
                <div class="d-flex gap-2 justify-content-center">
                    <button id="confirmReject" class="btn btn-danger">Reject</button>
                    <button id="cancelReject" class="btn btn-secondary">Cancel</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Resend Invite Modal -->
<div id="resendModal" class="modal fade" tabindex="-1" style="display: none;">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-body text-center p-4">
                <div class="d-flex justify-content-center align-items-center bg-info bg-opacity-10 rounded-circle mx-auto mb-3" style="width: 48px; height: 48px;">
                    <i class="fas fa-envelope text-info"></i>
                </div>
                <h5 class="modal-title mb-3">Resend Invitation</h5>
                <p class="text-muted mb-4">Are you sure you want to resend the invitation email to this applicant?</p>
                <div class="d-flex gap-2 justify-content-center">
                    <button id="confirmResend" class="btn btn-info">
                        <span class="btn-text">Resend Invite</span>
                        <span class="btn-spinner d-none">
                            <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                            Sending...
                        </span>
                    </button>
                    <button id="cancelResend" class="btn btn-secondary">Cancel</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bulk Action Modal -->
<div id="bulkModal" class="modal fade" tabindex="-1" style="display: none;">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-body text-center p-4">
                <div class="d-flex justify-content-center align-items-center bg-success bg-opacity-10 rounded-circle mx-auto mb-3" style="width: 48px; height: 48px;">
                    <i class="fas fa-users text-success"></i>
                </div>
                <h5 class="modal-title mb-3">Bulk Approve Applications</h5>
                <p class="text-muted mb-4">This will approve selected applications and send invitation codes to all applicants.</p>
                <div class="d-flex gap-2 justify-content-center">
                    <button id="confirmBulk" class="btn btn-success">
                        <span class="btn-text">Approve All</span>
                        <span class="btn-spinner d-none">
                            <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                            Processing...
                        </span>
                    </button>
                    <button id="cancelBulk" class="btn btn-secondary">Cancel</button>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<style>
/* Enhanced toastr notifications styling */
.toast {
    background-color: #030303 !important;
    color: #ffffff !important;
    border-radius: 8px !important;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important;
    font-weight: 500 !important;
}

.toast-success {
    background-color: #28a745 !important;
}

.toast-error {
    background-color: #dc3545 !important;
}

.toast-info {
    background-color: #17a2b8 !important;
}

.toast-warning {
    background-color: #ffc107 !important;
    color: #212529 !important;
}

.toast-title {
    font-weight: 600 !important;
    margin-bottom: 4px !important;
}

.toast-message {
    font-size: 14px !important;
    line-height: 1.4 !important;
}

/* Button loading states */
.btn:disabled {
    opacity: 0.7;
    cursor: not-allowed;
}
</style>
<script>
$(document).ready(function() {
    let currentApplicationId = null;
    
    // Toastr Configuration
    toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "preventDuplicates": true,
        "onclick": null,
        "showDuration": "400",
        "hideDuration": "1000",
        "timeOut": "6000",
        "extendedTimeOut": "2000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut",
        "tapToDismiss": true,
        "escapeHtml": false
    };
    
    // Initialize DataTable
    const table = $('#waitlistTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ url("admin/waitlist") }}',
            type: 'GET',
            data: function(d) {
                d.format = 'json';
            }
        },
        columns: [
            {
                data: 'id',
                orderable: false,
                searchable: false,
                render: function(data) {
                    return `<input type="checkbox" class="form-check-input row-checkbox" value="${data}">`;
                }
            },
            { 
                data: 'email',
                render: function(data, type, row) {
                    return `<a href="#" class="text-decoration-none view-details" data-id="${row.id}">${data}</a>`;
                }
            },
            { 
                data: 'status',
                render: function(data) {
                    const badges = {
                        'pending': '<span class="badge bg-warning text-dark">Pending</span>',
                        'approved': '<span class="badge bg-success">Approved</span>',
                        'rejected': '<span class="badge bg-danger">Rejected</span>'
                    };
                    return badges[data] || data;
                }
            },
            { 
                data: 'created_at',
                render: function(data) {
                    return new Date(data).toLocaleDateString();
                }
            },
            { 
                data: 'invite_code',
                render: function(data) {
                    return data ? `<code class="bg-light px-2 py-1 rounded">${data}</code>` : '-';
                }
            },
            { 
                data: 'invite_sent_at',
                render: function(data) {
                    return data ? new Date(data).toLocaleDateString() : '-';
                }
            },
            {
                data: 'actions',
                orderable: false,
                searchable: false,
                render: function(data, type, row) {
                    let buttons = '';
                    
                    if (row.status === 'pending') {
                        buttons += `<button class="btn btn-success btn-sm me-1 approve-btn" data-id="${row.id}" title="Approve">
                                      <i class="fas fa-check"></i>
                                    </button>
                                    <button class="btn btn-danger btn-sm me-1 reject-btn" data-id="${row.id}" title="Reject">
                                      <i class="fas fa-times"></i>
                                    </button>`;
                    }
                    
                    if (row.status === 'approved' && row.invite_code) {
                        buttons += `<button class="btn btn-info btn-sm me-1 resend-btn" data-id="${row.id}" title="Resend Invite">
                                      <i class="fas fa-envelope"></i>
                                    </button>`;
                    }
                    
                    buttons += `<button class="btn btn-primary btn-sm view-details" data-id="${row.id}" title="View Details">
                                  <i class="fas fa-eye"></i>
                                </button>`;
                    
                    return buttons;
                }
            }
        ],
        order: [[3, 'desc']],
        pageLength: 25,
        responsive: true,
        language: {
            processing: '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>',
            emptyTable: 'No waitlist applications found',
            zeroRecords: 'No matching applications found'
        }
    });

    // Select All functionality
    $('#selectAll').on('change', function() {
        $('.row-checkbox').prop('checked', this.checked);
        updateBulkButtons();
    });

    $(document).on('change', '.row-checkbox', function() {
        updateBulkButtons();
    });

    function updateBulkButtons() {
        const checkedCount = $('.row-checkbox:checked').length;
        $('#bulkApproveBtn').prop('disabled', checkedCount === 0);
    }

    // View Details
    $(document).on('click', '.view-details', function(e) {
        e.preventDefault();
        const applicationId = $(this).data('id');
        
        // Get row data from DataTable
        const rowData = table.row($(this).closest('tr')).data();
        
        let detailsHtml = `
            <div class="row">
                <div class="col-md-6">
                    <h6 class="fw-bold">Email:</h6>
                    <p>${rowData.email}</p>
                </div>
                <div class="col-md-6">
                    <h6 class="fw-bold">Status:</h6>
                    <p>${rowData.status}</p>
                </div>
            </div>
            <hr>
        `;
        
        // Display responses from JSON if available, otherwise fall back to old format
        if (rowData.responses && typeof rowData.responses === 'object') {
            for (const [fieldName, value] of Object.entries(rowData.responses)) {
                const displayName = fieldName.split('-').map(word => 
                    word.charAt(0).toUpperCase() + word.slice(1)
                ).join(' ');
                
                let displayValue = 'N/A';
                if (Array.isArray(value)) {
                    displayValue = value.join(', ');
                } else if (value) {
                    displayValue = value;
                }
                
                detailsHtml += `
                    <div class="mb-3">
                        <h6 class="fw-bold">${displayName}:</h6>
                        <p class="text-muted">${displayValue}</p>
                    </div>
                `;
            }
        } else {
            // Fallback to old format
            detailsHtml += `
                <div class="mb-3">
                    <h6 class="fw-bold">What draws you to DelWell right now?</h6>
                    <p class="text-muted">${rowData.draws_you || 'N/A'}</p>
                </div>
                <div class="mb-3">
                    <h6 class="fw-bold">Relationship with yourself:</h6>
                    <p class="text-muted">${rowData.relationship_with_self || 'N/A'}</p>
                </div>
                <div class="mb-3">
                    <h6 class="fw-bold">Values in relationship:</h6>
                    <p class="text-muted">${rowData.values ? rowData.values.join(', ') : 'N/A'}</p>
                </div>
                <div class="mb-3">
                    <h6 class="fw-bold">Statement about you:</h6>
                    <p class="text-muted">${rowData.statement || 'N/A'}</p>
                </div>
                <div class="mb-3">
                    <h6 class="fw-bold">Community values commitment:</h6>
                    <p class="text-muted">${rowData.community_values || 'N/A'}</p>
                </div>
            `;
        }
        
        $('#applicationDetails').html(detailsHtml);
        $('#detailsModal').modal('show');
    });

    // Approve Application
    $(document).on('click', '.approve-btn', function() {
        currentApplicationId = $(this).data('id');
        $('#approveModal').modal('show');
    });

    $('#confirmApprove').on('click', function() {
        if (!currentApplicationId) return;
        
        const $btn = $(this);
        const $btnText = $btn.find('.btn-text');
        const $btnSpinner = $btn.find('.btn-spinner');
        
        // Show loading state
        $btn.prop('disabled', true);
        $btnText.addClass('d-none');
        $btnSpinner.removeClass('d-none');
        $('#cancelApprove').prop('disabled', true);
        
        $.post(`{{ url('admin/waitlist/approve') }}/${currentApplicationId}`, {
            _token: '{{ csrf_token() }}'
        })
        .done(function(response) {
            if (response.success) {
                toastr.success(response.message || 'Application approved and invitation sent successfully!', 'Success');
                table.ajax.reload();
                updateStats();
            } else {
                toastr.error(response.message || 'Failed to approve application', 'Error');
            }
        })
        .fail(function(xhr) {
            let errorMessage = 'Failed to approve application';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            }
            toastr.error(errorMessage, 'Error');
        })
        .always(function() {
            // Reset button state
            $btn.prop('disabled', false);
            $btnText.removeClass('d-none');
            $btnSpinner.addClass('d-none');
            $('#cancelApprove').prop('disabled', false);
            
            $('#approveModal').modal('hide');
            currentApplicationId = null;
        });
    });

    // Reject Application
    $(document).on('click', '.reject-btn', function() {
        currentApplicationId = $(this).data('id');
        $('#rejectModal').modal('show');
    });

    $('#confirmReject').on('click', function() {
        if (!currentApplicationId) return;
        
        $.post(`{{ url('admin/waitlist/reject') }}/${currentApplicationId}`, {
            _token: '{{ csrf_token() }}'
        })
        .done(function(response) {
            if (response.success) {
                toastr.success(response.message);
                table.ajax.reload();
                updateStats();
            } else {
                toastr.error(response.message);
            }
        })
        .fail(function() {
            toastr.error('Failed to reject application');
        })
        .always(function() {
            $('#rejectModal').modal('hide');
            currentApplicationId = null;
        });
    });

    // Resend Invite
    $(document).on('click', '.resend-btn', function() {
        currentApplicationId = $(this).data('id');
        $('#resendModal').modal('show');
    });

    $('#confirmResend').on('click', function() {
        if (!currentApplicationId) return;
        
        const $btn = $(this);
        const $btnText = $btn.find('.btn-text');
        const $btnSpinner = $btn.find('.btn-spinner');
        
        // Show loading state
        $btn.prop('disabled', true);
        $btnText.addClass('d-none');
        $btnSpinner.removeClass('d-none');
        $('#cancelResend').prop('disabled', true);
        
        $.post(`{{ url('admin/waitlist/resend-invite') }}/${currentApplicationId}`, {
            _token: '{{ csrf_token() }}'
        })
        .done(function(response) {
            if (response.success) {
                toastr.success(response.message || 'Invitation resent successfully!', 'Success');
                table.ajax.reload();
            } else {
                toastr.error(response.message || 'Failed to resend invitation', 'Error');
            }
        })
        .fail(function(xhr) {
            let errorMessage = 'Failed to resend invitation';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            }
            toastr.error(errorMessage, 'Error');
        })
        .always(function() {
            // Reset button state
            $btn.prop('disabled', false);
            $btnText.removeClass('d-none');
            $btnSpinner.addClass('d-none');
            $('#cancelResend').prop('disabled', false);
            
            $('#resendModal').modal('hide');
            currentApplicationId = null;
        });
    });

    // Bulk Approve
    $('#bulkApproveBtn').on('click', function() {
        const selectedIds = $('.row-checkbox:checked').map(function() {
            return this.value;
        }).get();
        
        if (selectedIds.length === 0) {
            toastr.warning('Please select applications to approve');
            return;
        }
        
        $('#bulkModal').modal('show');
    });

    $('#confirmBulk').on('click', function() {
        const selectedIds = $('.row-checkbox:checked').map(function() {
            return this.value;
        }).get();
        
        const $btn = $(this);
        const $btnText = $btn.find('.btn-text');
        const $btnSpinner = $btn.find('.btn-spinner');
        
        // Show loading state
        $btn.prop('disabled', true);
        $btnText.addClass('d-none');
        $btnSpinner.removeClass('d-none');
        $('#cancelBulk').prop('disabled', true);
        
        $.post('{{ url("admin/waitlist/bulk-approve") }}', {
            _token: '{{ csrf_token() }}',
            application_ids: selectedIds
        })
        .done(function(response) {
            if (response.success) {
                toastr.success(response.message || `${selectedIds.length} applications approved and invitations sent successfully!`, 'Bulk Approval Success');
                table.ajax.reload();
                updateStats();
                $('#selectAll').prop('checked', false);
                $('.row-checkbox').prop('checked', false);
                updateBulkButtons();
            } else {
                toastr.error(response.message || 'Failed to bulk approve applications', 'Error');
            }
        })
        .fail(function(xhr) {
            let errorMessage = 'Failed to bulk approve applications';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            }
            toastr.error(errorMessage, 'Error');
        })
        .always(function() {
            // Reset button state
            $btn.prop('disabled', false);
            $btnText.removeClass('d-none');
            $btnSpinner.addClass('d-none');
            $('#cancelBulk').prop('disabled', false);
            
            $('#bulkModal').modal('hide');
        });
    });

    // Refresh button
    $('#refreshBtn').on('click', function() {
        table.ajax.reload();
        updateStats();
    });

    // Update statistics
    function updateStats() {
        $.get('{{ url("admin/waitlist") }}', { stats_only: true })
        .done(function(response) {
            if (response.stats) {
                $('#totalApplications').text(response.stats.total.toLocaleString());
                $('#pendingApplications').text(response.stats.pending.toLocaleString());
                $('#approvedApplications').text(response.stats.approved.toLocaleString());
                $('#rejectedApplications').text(response.stats.rejected.toLocaleString());
                $('#invitedApplications').text(response.stats.invited.toLocaleString());
            }
        });
    }

    // Modal event handlers
    $('.modal').on('hidden.bs.modal', function() {
        currentApplicationId = null;
    });

    $('#cancelApprove, #cancelReject, #cancelResend, #cancelBulk').on('click', function() {
        $(this).closest('.modal').modal('hide');
    });

    // Initialize bulk buttons state
    updateBulkButtons();
});
</script>
@endsection
