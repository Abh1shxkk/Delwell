$(document).ready(function() {
    let currentQuestionId = null;
    
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
        "hideMethod": "fadeOut",
        "opacity": 1
    };
    
    // Initialize DataTable
    const table = $('#questionsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: window.location.pathname,
            type: 'GET',
            data: function(d) {
                d.format = 'json';
            }
        },
        columns: [
            { 
                data: 'sort_order',
                render: function(data) {
                    return `<span class="badge bg-secondary">${data}</span>`;
                }
            },
            { 
                data: 'question_text',
                render: function(data, type, row) {
                    const truncated = data.length > 80 ? data.substring(0, 80) + '...' : data;
                    return `<a href="#" class="text-decoration-none view-details" data-id="${row.id}">${truncated}</a>`;
                }
            },
            { 
                data: 'question_type',
                render: function(data) {
                    const badges = {
                        'text': '<span class="badge bg-info">Text</span>',
                        'textarea': '<span class="badge bg-info">Textarea</span>',
                        'radio': '<span class="badge bg-primary">Radio</span>',
                        'checkbox': '<span class="badge bg-primary">Checkbox</span>',
                        'email': '<span class="badge bg-success">Email</span>'
                    };
                    return badges[data] || data;
                }
            },
            { 
                data: 'field_name',
                render: function(data) {
                    return `<code class="bg-light px-2 py-1 rounded">${data}</code>`;
                }
            },
            { 
                data: 'is_required',
                render: function(data) {
                    return data ? '<span class="badge bg-warning text-dark">Required</span>' : '<span class="badge bg-secondary">Optional</span>';
                }
            },
            { 
                data: 'is_active',
                render: function(data) {
                    return data ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-secondary">Inactive</span>';
                }
            },
            {
                data: 'actions',
                orderable: false,
                searchable: false,
                render: function(data, type, row) {
                    let buttons = '';
                    
                    buttons += `<button class="btn btn-sm me-1 ${row.is_active ? 'btn-warning' : 'btn-success'} toggle-status-btn" data-id="${row.id}" title="${row.is_active ? 'Deactivate' : 'Activate'}">
                                  <i class="fas fa-${row.is_active ? 'eye-slash' : 'eye'}"></i>
                                </button>`;
                    
                    buttons += `<a href="/admin/waitlist-questions/${row.id}/edit" class="btn btn-primary btn-sm me-1" title="Edit">
                                  <i class="fas fa-edit"></i>
                                </a>`;
                    
                    buttons += `<button class="btn btn-primary btn-sm me-1 view-details" data-id="${row.id}" title="View Details">
                                  <i class="fas fa-eye"></i>
                                </button>`;
                    
                    buttons += `<button class="btn btn-danger btn-sm delete-btn" data-id="${row.id}" title="Delete">
                                  <i class="fas fa-trash"></i>
                                </button>`;
                    
                    return buttons;
                }
            }
        ],
        order: [[0, 'asc']],
        pageLength: 25,
        responsive: true,
        language: {
            processing: '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>',
            emptyTable: 'No questions found',
            zeroRecords: 'No matching questions found'
        }
    });

    // View Details
    $(document).on('click', '.view-details', function(e) {
        e.preventDefault();
        const questionId = $(this).data('id');
        
        // Get row data from DataTable
        const rowData = table.row($(this).closest('tr')).data();
        
        let detailsHtml = `
            <div class="row">
                <div class="col-md-6">
                    <h6 class="fw-bold">Field Name:</h6>
                    <p><code>${rowData.field_name}</code></p>
                </div>
                <div class="col-md-6">
                    <h6 class="fw-bold">Question Type:</h6>
                    <p>${rowData.question_type}</p>
                </div>
            </div>
            <hr>
            <div class="mb-3">
                <h6 class="fw-bold">Question Text:</h6>
                <p class="text-muted">${rowData.question_text}</p>
            </div>
        `;
        
        if (rowData.placeholder) {
            detailsHtml += `
                <div class="mb-3">
                    <h6 class="fw-bold">Placeholder:</h6>
                    <p class="text-muted">${rowData.placeholder}</p>
                </div>
            `;
        }
        
        if (rowData.help_text) {
            detailsHtml += `
                <div class="mb-3">
                    <h6 class="fw-bold">Help Text:</h6>
                    <p class="text-muted">${rowData.help_text}</p>
                </div>
            `;
        }
        
        if (rowData.options && rowData.options.length > 0) {
            detailsHtml += `
                <div class="mb-3">
                    <h6 class="fw-bold">Options:</h6>
                    <ul class="list-group">
                        ${rowData.options.map(opt => `<li class="list-group-item">${opt}</li>`).join('')}
                    </ul>
                </div>
            `;
        }
        
        if (rowData.max_selections) {
            detailsHtml += `
                <div class="mb-3">
                    <h6 class="fw-bold">Max Selections:</h6>
                    <p class="text-muted">${rowData.max_selections}</p>
                </div>
            `;
        }
        
        detailsHtml += `
            <div class="row">
                <div class="col-md-4">
                    <h6 class="fw-bold">Sort Order:</h6>
                    <p>${rowData.sort_order}</p>
                </div>
                <div class="col-md-4">
                    <h6 class="fw-bold">Required:</h6>
                    <p>${rowData.is_required ? 'Yes' : 'No'}</p>
                </div>
                <div class="col-md-4">
                    <h6 class="fw-bold">Status:</h6>
                    <p>${rowData.is_active ? 'Active' : 'Inactive'}</p>
                </div>
            </div>
        `;
        
        $('#questionDetails').html(detailsHtml);
        $('#detailsModal').modal('show');
    });

    // Toggle Status
    $(document).on('click', '.toggle-status-btn', function() {
        const questionId = $(this).data('id');
        
        $.post(`/admin/waitlist-questions/${questionId}/toggle-status`, {
            _token: $('meta[name="csrf-token"]').attr('content')
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
            toastr.error('Failed to update question status');
        });
    });

    // Delete Question
    $(document).on('click', '.delete-btn', function() {
        currentQuestionId = $(this).data('id');
        $('#deleteModal').modal('show');
    });

    $('#confirmDelete').on('click', function() {
        if (!currentQuestionId) return;
        
        $.ajax({
            url: `/admin/waitlist-questions/${currentQuestionId}`,
            type: 'DELETE',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            }
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
            toastr.error('Failed to delete question');
        })
        .always(function() {
            $('#deleteModal').modal('hide');
            currentQuestionId = null;
        });
    });

    // Refresh button
    $('#refreshBtn').on('click', function() {
        table.ajax.reload();
        updateStats();
    });

    // Update statistics
    function updateStats() {
        $.get(window.location.pathname, { stats_only: true })
        .done(function(response) {
            if (response.stats) {
                $('#totalQuestions').text(response.stats.total.toLocaleString());
                $('#activeQuestions').text(response.stats.active.toLocaleString());
                $('#inactiveQuestions').text(response.stats.inactive.toLocaleString());
            }
        });
    }

    // Modal event handlers
    $('.modal').on('hidden.bs.modal', function() {
        currentQuestionId = null;
    });

    $('#cancelDelete').on('click', function() {
        $('#deleteModal').modal('hide');
    });
});
