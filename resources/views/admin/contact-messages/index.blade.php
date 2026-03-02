@extends('admin.layout')
@section('title', 'Contact Messages - Admin')

@section('content')
<style>
    /* Fix Toastr styling */
    .toast-success {
        background-color: #51A351 !important;
        color: #ffffff !important;
    }
    .toast-error {
        background-color: #BD362F !important;
        color: #ffffff !important;
    }
    .toast-info {
        background-color: #2F96B4 !important;
        color: #ffffff !important;
    }
    .toast-warning {
        background-color: #F89406 !important;
        color: #ffffff !important;
    }
    #toast-container > div {
        opacity: 1 !important;
    }
</style>
<div class="container-fluid px-0">
    <div class="card shadow-sm">
        <!-- Header -->
        <div class="card-header border-bottom">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h2 fw-bold text-dark mb-0">
                    Contact Messages
                </h1>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-primary btn-sm d-flex align-items-center" id="bulkMarkReadBtn">
                        <i class="fas fa-check me-2"></i>Mark as Read
                    </button>
                    <button type="button" class="btn btn-danger btn-sm d-flex align-items-center" id="bulkDeleteBtn">
                        <i class="fas fa-trash me-2"></i>Delete Selected
                    </button>
                    <button type="button" class="btn btn-info btn-sm d-flex align-items-center" id="refreshBtn">
                        <i class="fas fa-sync me-2"></i>Refresh
                    </button>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="card-body border-bottom">
            <div class="row g-3">
    <div class="col-xl-3 col-sm-6 mb-3">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h6 class="text-muted text-uppercase mb-2" style="font-size: 11px; font-weight: 600;">Total Messages</h6>
                        <h2 class="mb-0" style="font-weight: 700; color: #5a5c69;">{{ $stats['total'] }}</h2>
                    </div>
                    <div class="ms-3">
                        <div style="width: 50px; height: 50px; background: #f8d7da; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                            <i class="fa fa-envelope" style="font-size: 24px; color: #dc3545;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6 mb-3">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h6 class="text-muted text-uppercase mb-2" style="font-size: 11px; font-weight: 600;">Unread</h6>
                        <h2 class="mb-0" style="font-weight: 700; color: #f6c23e;">{{ $stats['unread'] }}</h2>
                    </div>
                    <div class="ms-3">
                        <div style="width: 50px; height: 50px; background: #fff3cd; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                            <i class="fa fa-clock" style="font-size: 24px; color: #f6c23e;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6 mb-3">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h6 class="text-muted text-uppercase mb-2" style="font-size: 11px; font-weight: 600;">Read</h6>
                        <h2 class="mb-0" style="font-weight: 700; color: #36b9cc;">{{ $stats['read'] }}</h2>
                    </div>
                    <div class="ms-3">
                        <div style="width: 50px; height: 50px; background: #d1ecf1; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                            <i class="fa fa-envelope-open" style="font-size: 24px; color: #36b9cc;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6 mb-3">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h6 class="text-muted text-uppercase mb-2" style="font-size: 11px; font-weight: 600;">Replied</h6>
                        <h2 class="mb-0" style="font-weight: 700; color: #1cc88a;">{{ $stats['replied'] }}</h2>
                    </div>
                    <div class="ms-3">
                        <div style="width: 50px; height: 50px; background: #d4edda; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                            <i class="fa fa-check-circle" style="font-size: 24px; color: #1cc88a;"></i>
                        </div>
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
                <table id="messagesTable" class="table table-striped table-hover">
                    <thead class="table-light">
                        <tr>
                            <th class="text-uppercase small fw-medium text-muted">
                                <input type="checkbox" id="selectAll" class="form-check-input">
                            </th>
                            <th class="text-uppercase small fw-medium text-muted">Name</th>
                            <th class="text-uppercase small fw-medium text-muted">Email</th>
                            <th class="text-uppercase small fw-medium text-muted">Subject</th>
                            <th class="text-uppercase small fw-medium text-muted">Status</th>
                            <th class="text-uppercase small fw-medium text-muted">Date</th>
                            <th class="text-uppercase small fw-medium text-muted">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($messages as $msg)
                        <tr>
                            <td><input type="checkbox" class="msg-check" value="{{ $msg->id }}"></td>
                            <td><strong>{{ $msg->name }}</strong></td>
                            <td><a href="mailto:{{ $msg->email }}">{{ $msg->email }}</a></td>
                            <td>{{ Str::limit($msg->subject, 40) }}</td>
                            <td>
                                @if($msg->status === 'unread')
                                <span class="badge badge-warning">Unread</span>
                                @elseif($msg->status === 'read')
                                <span class="badge badge-info">Read</span>
                                @else
                                <span class="badge badge-success">Replied</span>
                                @endif
                            </td>
                            <td>{{ $msg->created_at->format('M d, Y h:i A') }}</td>
                            <td>
                                <a href="{{ route('admin.contact-messages.show', $msg->id) }}" class="btn btn-sm btn-primary" title="View"><i class="fa fa-eye"></i></a>
                                <button class="btn btn-sm btn-success reply-btn" 
                                        data-id="{{ $msg->id }}"
                                        data-email="{{ $msg->email }}" 
                                        data-name="{{ $msg->name }}" 
                                        data-subject="{{ $msg->subject }}" 
                                        data-message="{{ $msg->message }}" 
                                        title="Reply">
                                    <i class="fa fa-reply"></i>
                                </button>
                                <button class="btn btn-sm btn-danger delete-btn" data-id="{{ $msg->id }}" title="Delete"><i class="fa fa-trash"></i></button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Reply Modal -->
<div class="modal fade" id="replyModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="fa fa-reply"></i> Reply to <span id="modalUserName"></span></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label"><strong>To:</strong></label>
                    <input type="text" class="form-control" id="modalUserEmail" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label"><strong>Subject:</strong></label>
                    <input type="text" class="form-control" id="modalSubject" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label"><strong>Your Reply:</strong></label>
                    <textarea class="form-control" id="modalReplyMessage" rows="10" placeholder="Type your reply here..."></textarea>
                </div>
                <div class="alert alert-info">
                    <i class="fa fa-info-circle"></i> This will send an email to <strong><span id="modalEmailPreview"></span></strong> and mark the message as "Replied".
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" id="sendReplyBtn">
                    <i class="fa fa-paper-plane"></i> Send Reply
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Initialize DataTable
    const table = $('#messagesTable').DataTable({
        order: [[5, 'desc']], // Date column (0-indexed: checkbox, name, email, subject, status, date, actions)
        pageLength: 25,
        columnDefs: [
            { orderable: false, targets: [0, 6] } // Checkbox and Actions columns
        ]
    });

    let currentMessageId = null;

    // Initially disable bulk buttons
    $('#bulkDeleteBtn, #bulkMarkReadBtn').prop('disabled', true);

    // Refresh Button
    $('#refreshBtn').on('click', function() {
        location.reload();
    });

    // Select All Checkbox
    $('#selectAll').on('change', function() {
        $('.msg-check').prop('checked', this.checked);
        toggleBulkButtons();
    });

    // Individual Checkbox
    $(document).on('change', '.msg-check', function() {
        toggleBulkButtons();
        updateSelectAll();
    });

    // Toggle bulk action buttons
    function toggleBulkButtons() {
        const checked = $('.msg-check:checked').length;
        if (checked > 0) {
            $('#bulkDeleteBtn, #bulkMarkReadBtn').prop('disabled', false);
        } else {
            $('#bulkDeleteBtn, #bulkMarkReadBtn').prop('disabled', true);
        }
    }

    // Update Select All checkbox
    function updateSelectAll() {
        const total = $('.msg-check').length;
        const checked = $('.msg-check:checked').length;
        $('#selectAll').prop('checked', total === checked && total > 0);
    }

    // Bulk Delete
    $('#bulkDeleteBtn').on('click', function() {
        const ids = [];
        $('.msg-check:checked').each(function() {
            ids.push($(this).val());
        });

        if (ids.length === 0) {
            toastr.warning('Please select messages to delete');
            return;
        }

        if (confirm(`Are you sure you want to delete ${ids.length} message(s)?`)) {
            $.ajax({
                url: '/admin/contact-messages/bulk-delete',
                type: 'POST',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                data: {ids: ids},
                success: function(response) {
                    toastr.success(response.message);
                    setTimeout(() => location.reload(), 1000);
                },
                error: function() {
                    toastr.error('Failed to delete messages');
                }
            });
        }
    });

    // Bulk Mark as Read
    $('#bulkMarkReadBtn').on('click', function() {
        const ids = [];
        $('.msg-check:checked').each(function() {
            ids.push($(this).val());
        });

        if (ids.length === 0) {
            toastr.warning('Please select messages');
            return;
        }

        $.ajax({
            url: '/admin/contact-messages/bulk-update-status',
            type: 'POST',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data: {ids: ids, status: 'read'},
            success: function(response) {
                toastr.success(response.message);
                setTimeout(() => location.reload(), 1000);
            },
            error: function() {
                toastr.error('Failed to update messages');
            }
        });
    });

    // Reply Button - Open Modal
    $('.reply-btn').on('click', function() {
        currentMessageId = $(this).data('id');
        const email = $(this).data('email');
        const name = $(this).data('name');
        const subject = $(this).data('subject');
        const originalMsg = $(this).data('message');
        
        // Populate modal
        $('#modalUserName').text(name);
        $('#modalUserEmail').val(email);
        $('#modalEmailPreview').text(email);
        $('#modalSubject').val('Re: ' + subject);
        
        // Pre-fill reply template
        const template = `Hi ${name},\n\nThank you for contacting us regarding "${subject}".\n\n[Your response here]\n\nBest regards,\nDelWell Team\n\n---\nOriginal Message:\n${originalMsg}`;
        $('#modalReplyMessage').val(template);
        
        // Show modal
        $('#replyModal').modal('show');
    });

    // Send Reply Button
    $('#sendReplyBtn').on('click', function() {
        const replyMessage = $('#modalReplyMessage').val().trim();
        
        if (!replyMessage || replyMessage.length < 10) {
            toastr.error('Please write a reply message (minimum 10 characters)');
            return;
        }
        
        if (!currentMessageId) {
            toastr.error('Message ID not found');
            return;
        }
        
        // Disable button and show loading
        const $btn = $(this);
        const originalText = $btn.html();
        $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Sending...');
        
        $.ajax({
            url: `/admin/contact-messages/${currentMessageId}/reply`,
            type: 'POST',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data: {reply_message: replyMessage},
            success: function(response) {
                toastr.success(response.message);
                $('#replyModal').modal('hide');
                
                // Reload page after 1 second
                setTimeout(() => location.reload(), 1000);
            },
            error: function(xhr) {
                const message = xhr.responseJSON?.message || 'Failed to send reply';
                toastr.error(message);
                $btn.prop('disabled', false).html(originalText);
            }
        });
    });

    // Delete Button
    $('.delete-btn').on('click', function() {
        const id = $(this).data('id');
        if (confirm('Delete this message?')) {
            $.ajax({
                url: `/admin/contact-messages/${id}`,
                type: 'DELETE',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: function() {
                    toastr.success('Message deleted!');
                    location.reload();
                }
            });
        }
    });
});
</script>
@endsection
