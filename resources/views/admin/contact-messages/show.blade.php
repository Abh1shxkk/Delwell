@extends('admin.layout')
@section('title', 'View Message - Admin')

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
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="page-title"><i class="fa fa-envelope-open"></i> View Message</h3>
            <a href="{{ route('admin.contact-messages') }}" class="btn btn-secondary">
                <i class="fa fa-arrow-left"></i> Back to Messages
            </a>
        </div>
    </div>
</div>

<div class="row">
    <!-- Message Details -->
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fa fa-info-circle"></i> Message Details</h5>
            </div>
            <div class="card-body">
                <!-- Sender Info -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="text-muted">From:</h6>
                        <p class="mb-1"><strong>{{ $message->name }}</strong></p>
                        <p class="mb-0">
                            <a href="mailto:{{ $message->email }}">{{ $message->email }}</a>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Received:</h6>
                        <p class="mb-1">{{ $message->created_at->format('F d, Y') }}</p>
                        <p class="mb-0 text-muted">{{ $message->created_at->format('h:i A') }}</p>
                    </div>
                </div>

                <hr>

                <!-- Subject -->
                <div class="mb-4">
                    <h6 class="text-muted">Subject:</h6>
                    <h5>{{ $message->subject }}</h5>
                </div>

                <hr>

                <!-- Message -->
                <div class="mb-4">
                    <h6 class="text-muted">Message:</h6>
                    <div class="p-3 bg-light rounded">
                        <p class="mb-0" style="white-space: pre-wrap;">{{ $message->message }}</p>
                    </div>
                </div>

                <!-- Meta Info -->
                @if($message->ip_address)
                <div class="mt-4 pt-3 border-top">
                    <small class="text-muted">
                        <i class="fa fa-globe"></i> IP: {{ $message->ip_address }}
                        @if($message->user_agent)
                        <br><i class="fa fa-desktop"></i> {{ Str::limit($message->user_agent, 80) }}
                        @endif
                    </small>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Actions & Notes -->
    <div class="col-lg-4">
        <!-- Quick Actions -->
        <div class="card shadow-sm mb-3">
            <div class="card-header bg-white">
                <h6 class="mb-0"><i class="fa fa-bolt"></i> Quick Actions</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#replyModal">
                        <i class="fa fa-reply"></i> Reply to {{ $message->name }}
                    </button>
                    <button type="button" class="btn btn-info" id="copyEmailBtn">
                        <i class="fa fa-copy"></i> Copy Email Address
                    </button>
                    <button type="button" class="btn btn-danger" id="deleteBtn">
                        <i class="fa fa-trash"></i> Delete Message
                    </button>
                </div>
            </div>
        </div>

        <!-- Status -->
        <div class="card shadow-sm mb-3">
            <div class="card-header bg-white">
                <h6 class="mb-0"><i class="fa fa-tag"></i> Status</h6>
            </div>
            <div class="card-body">
                <select class="form-select" id="statusSelect">
                    <option value="unread" {{ $message->status == 'unread' ? 'selected' : '' }}>Unread</option>
                    <option value="read" {{ $message->status == 'read' ? 'selected' : '' }}>Read</option>
                    <option value="replied" {{ $message->status == 'replied' ? 'selected' : '' }}>Replied</option>
                </select>
            </div>
        </div>

        <!-- Admin Notes -->
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h6 class="mb-0"><i class="fa fa-sticky-note"></i> Admin Notes</h6>
            </div>
            <div class="card-body">
                <textarea class="form-control" id="adminNotes" rows="4" placeholder="Add internal notes...">{{ $message->admin_notes }}</textarea>
                <button type="button" class="btn btn-primary btn-sm mt-2 w-100" id="saveNotesBtn">
                    <i class="fa fa-save"></i> Save Notes
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Reply Modal -->
<div class="modal fade" id="replyModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="fa fa-reply"></i> Reply to {{ $message->name }}</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label"><strong>To:</strong></label>
                    <input type="text" class="form-control" value="{{ $message->email }}" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label"><strong>Subject:</strong></label>
                    <input type="text" class="form-control" id="replySubject" value="Re: {{ $message->subject }}" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label"><strong>Your Reply:</strong></label>
                    <textarea class="form-control" id="replyMessage" rows="10" placeholder="Type your reply here...">Hi {{ $message->name }},

Thank you for contacting us regarding "{{ $message->subject }}".

[Your response here]

Best regards,
DelWell Team

---
Original Message:
{{ $message->message }}</textarea>
                </div>
                <div class="alert alert-info">
                    <i class="fa fa-info-circle"></i> This will send an email to <strong>{{ $message->email }}</strong> and mark the message as "Replied".
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
    const messageId = {{ $message->id }};

    // Update Status
    $('#statusSelect').on('change', function() {
        $.ajax({
            url: `/admin/contact-messages/${messageId}/status`,
            type: 'POST',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data: {status: $(this).val()},
            success: function(response) {
                toastr.success(response.message);
            },
            error: function() {
                toastr.error('Failed to update status');
            }
        });
    });

    // Send Reply Button
    $('#sendReplyBtn').on('click', function() {
        const replyMessage = $('#replyMessage').val().trim();
        
        if (!replyMessage || replyMessage.length < 10) {
            toastr.error('Please write a reply message (minimum 10 characters)');
            return;
        }
        
        // Disable button and show loading
        const $btn = $(this);
        const originalText = $btn.html();
        $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Sending...');
        
        $.ajax({
            url: `/admin/contact-messages/${messageId}/reply`,
            type: 'POST',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data: {reply_message: replyMessage},
            success: function(response) {
                toastr.success(response.message);
                $('#replyModal').modal('hide');
                
                // Update status dropdown
                $('#statusSelect').val('replied').trigger('change');
                
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

    // Copy Email Button
    $('#copyEmailBtn').on('click', function() {
        const email = '{{ $message->email }}';
        
        // Copy to clipboard
        navigator.clipboard.writeText(email).then(function() {
            toastr.success('Email address copied: ' + email);
        }).catch(function() {
            // Fallback for older browsers
            const temp = $('<input>');
            $('body').append(temp);
            temp.val(email).select();
            document.execCommand('copy');
            temp.remove();
            toastr.success('Email address copied: ' + email);
        });
    });

    // Save Notes
    $('#saveNotesBtn').on('click', function() {
        $.ajax({
            url: `/admin/contact-messages/${messageId}/notes`,
            type: 'POST',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data: {admin_notes: $('#adminNotes').val()},
            success: function(response) {
                toastr.success(response.message);
            },
            error: function() {
                toastr.error('Failed to save notes');
            }
        });
    });

    // Delete Message
    $('#deleteBtn').on('click', function() {
        if (confirm('Are you sure you want to delete this message? This action cannot be undone.')) {
            $.ajax({
                url: `/admin/contact-messages/${messageId}`,
                type: 'DELETE',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: function(response) {
                    toastr.success(response.message);
                    setTimeout(() => {
                        window.location.href = '{{ route("admin.contact-messages") }}';
                    }, 1000);
                },
                error: function() {
                    toastr.error('Failed to delete message');
                }
            });
        }
    });
});
</script>
@endsection
