@extends('admin.layout')
@section('title', 'Dashboard')
@section('content')

<div class="container-fluid px-0">
    <div class="card shadow-sm">
        <!-- Header -->
        <div class="card-header border-bottom">
            <div class="d-flex justify-content-between align-items-center">
            <h1 class="h2 fw-bold text-dark mb-0">Quiz Questions Management</h1>
            <button id="addQuestionBtn" class="btn btn-primary d-flex align-items-center">
                <i class="fas fa-plus me-2"></i>
                Add New Question
            </button>
        </div>

        <!-- DataTable -->
        <div class="card-body p-4">
            <div class="table-responsive">
                <table id="questionsTable" class="table table-striped table-hover">
                    <thead class="table-light">
                        <tr>
                            <th class="text-uppercase small fw-medium text-muted">ID</th>
                            <th class="text-uppercase small fw-medium text-muted">Section</th>
                            <th class="text-uppercase small fw-medium text-muted">Question</th>
                            <th class="text-uppercase small fw-medium text-muted">Options</th>
                            <th class="text-uppercase small fw-medium text-muted">Order</th>
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

<!-- Add/Edit Question Modal -->
<div id="questionModal" class="modal fade" tabindex="-1" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Add New Question</h5>
                <button type="button" id="closeModal" class="btn-close" aria-label="Close"></button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body">
                <form id="questionForm">
                    <input type="hidden" id="questionId" name="questionId">

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="question_id" class="form-label fw-medium">Question ID *</label>
                            <input type="text" id="question_id" name="question_id" required
                                class="form-control"
                                placeholder="e.g., q1, q2">
                        </div>
                        <div class="col-md-6">
                            <label for="section" class="form-label fw-medium">Section *</label>
                            <select id="section" name="section" required class="form-select">
                                <option value="">Select Section</option>
                                <!-- Options will be loaded dynamically -->
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="question" class="form-label fw-medium">Question Text *</label>
                        <textarea id="question" name="question" required rows="3"
                            class="form-control"
                            placeholder="Enter the question text"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium">Answer Options *</label>
                        <div id="optionsContainer">
                            <!-- Options will be dynamically added here -->
                        </div>
                        <button type="button" id="addOptionBtn" class="btn btn-outline-primary btn-sm mt-2">
                            <i class="fas fa-plus me-1"></i>Add Option
                        </button>
                    </div>

                    <div class="mb-3">
                        <label for="order" class="form-label fw-medium">Order</label>
                        <input type="number" id="order" name="order" min="0"
                            class="form-control"
                            placeholder="0">
                    </div>

                </form>
            </div>
            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="button" id="cancelBtn" class="btn btn-secondary">
                    Cancel
                </button>
                <button type="submit" id="saveBtn" class="btn btn-primary" form="questionForm">
                    Save Question
                </button>
            </div>
        </div>
    </div>
</div>

<!-- View Question Modal -->
<div id="viewModal" class="modal fade" tabindex="-1" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h5 class="modal-title">View Question</h5>
                <button type="button" id="closeViewModal" class="btn-close" aria-label="Close"></button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body">
                <div id="viewContent">
                    <!-- Content will be loaded here -->
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
                    <i class="fas fa-exclamation-triangle text-danger"></i>
                </div>
                <h5 class="modal-title mb-3">Confirm Delete</h5>
                <p class="text-muted mb-4">Are you sure you want to delete this question? This action cannot be undone.</p>
                <div class="d-flex gap-2 justify-content-center">
                    <button id="confirmDelete" class="btn btn-danger">
                        Delete
                    </button>
                    <button id="cancelDelete" class="btn btn-secondary">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>



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
</style>
<script>
    $(document).ready(function() {
        // Laravel Routes
        const routes = {
            questions: '{{ route("admin.quiz.questions") }}',
            store: '{{ route("admin.quiz.store") }}',
            show: '{{ url("admin/quiz/questions") }}',
            update: '{{ url("admin/quiz/questions") }}',
            destroy: '{{ url("admin/quiz/questions") }}',
            toggle: '{{ url("admin/quiz/questions") }}',
            sections: '{{ route("admin.sections.active") }}'
        };

        // CSRF Token Setup
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            xhrFields: {
                withCredentials: true
            }
        });

        // Toastr Configuration
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": false,
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

        // Load sections for dropdown
        loadSections();

        // Initialize DataTable
        let table = $('#questionsTable').DataTable({
            processing: true,
            serverSide: false,
            ajax: {
                url: routes.questions,
                type: 'GET'
            },
            columns: [{
                    data: 'question_id',
                    name: 'question_id'
                },
                {
                    data: 'section',
                    name: 'section'
                },
                {
                    data: 'question',
                    name: 'question',
                    render: function(data, type, row) {
                        return data.length > 50 ? data.substring(0, 50) + '...' : data;
                    }
                },
                {
                    data: 'options_count',
                    name: 'options_count',
                    render: function(data, type, row) {
                        return data + ' options';
                    }
                },
                {
                    data: 'order',
                    name: 'order'
                },
                {
                    data: 'is_active',
                    name: 'is_active',
                    render: function(data, type, row) {
                        return data ?
                            '<span class="badge bg-success">Active</span>' :
                            '<span class="badge bg-danger">Inactive</span>';
                    }
                },
                {
                    data: 'actions',
                    name: 'actions',
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row) {
                        let statusBtn = row.is_active ?
                            '<button onclick="toggleStatus(' + data + ')" class="btn btn-sm btn-outline-dark me-1" title="Deactivate"><i class="fas fa-toggle-on"></i></button>' :
                            '<button onclick="toggleStatus(' + data + ')" class="btn btn-sm btn-outline-danger me-1" title="Activate"><i class="fas fa-toggle-off"></i></button>';

                        return `
                                <button onclick="viewQuestion(${data})" class="btn btn-sm btn-outline-info me-1" title="View">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button onclick="editQuestion(${data})" class="btn btn-sm btn-outline-primary me-1" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                ${statusBtn}
                                <button onclick="deleteQuestion(${data})" class="btn btn-sm btn-outline-danger" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            `;
                    }
                }
            ],
            pageLength: 10,
            responsive: true,
            order: [
                [4, 'asc']
            ] // Order by 'order' column
        });

        // Modal Functions
        let currentQuestionId = null;
        let optionCounter = 0;

        // Show Add Question Modal
        $('#addQuestionBtn').click(function() {
            resetForm();
            $('#modalTitle').text('Add New Question');
            $('#saveBtn').text('Save Question');
            addOption(); // Add first option
            addOption(); // Add second option
            var questionModal = new bootstrap.Modal(document.getElementById('questionModal'));
            questionModal.show();
        });

        // Close Modals
        $('#closeModal, #cancelBtn').click(function() {
            var questionModal = bootstrap.Modal.getInstance(document.getElementById('questionModal'));
            if (questionModal) {
                questionModal.hide();
            }
        });

        $('#closeViewModal').click(function() {
            var viewModal = bootstrap.Modal.getInstance(document.getElementById('viewModal'));
            if (viewModal) {
                viewModal.hide();
            }
        });

        // Add Option
        $('#addOptionBtn').click(function() {
            addOption();
        });

        // Submit Form
        $('#questionForm').submit(function(e) {
            e.preventDefault();

            let options = getOptions();
            
            if (options.length < 2) {
                toastr.error('Please add at least 2 options');
                return;
            }
            
            let formData = new FormData();
            
            formData.append('question_id', $('#question_id').val());
            formData.append('section', $('#section').val());
            formData.append('question', $('#question').val());
            formData.append('order', $('#order').val() || 0);
            formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
            
            // Append options array
            options.forEach((option, index) => {
                formData.append(`options[${index}][text]`, option.text);
                formData.append(`options[${index}][value]`, option.value);
            });

            if (currentQuestionId) {
                formData.append('_method', 'PUT');
            }

            let url = currentQuestionId ? 
                `${routes.update}/${currentQuestionId}` : 
                routes.store;



            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                        var questionModal = bootstrap.Modal.getInstance(document.getElementById('questionModal'));
                        if (questionModal) {
                            questionModal.hide();
                        }
                        table.ajax.reload();
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        let errorMsg = 'Validation errors:\n';
                        for (let field in errors) {
                            errorMsg += errors[field].join('\n') + '\n';
                        }
                        toastr.error(errorMsg);
                    } else {
                        toastr.error('An error occurred. Please try again.');
                    }
                }
            });
        });

        // Delete Modal
        $('#cancelDelete').click(function() {
            var deleteModal = bootstrap.Modal.getInstance(document.getElementById('deleteModal'));
            if (deleteModal) {
                deleteModal.hide();
            }
        });

        $('#confirmDelete').click(function() {
            if (currentQuestionId) {
                $.ajax({
                    url: `${routes.destroy}/${currentQuestionId}`,
                    type: 'DELETE',
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.message);
                            var deleteModal = bootstrap.Modal.getInstance(document.getElementById('deleteModal'));
                            if (deleteModal) {
                                deleteModal.hide();
                            }
                            table.ajax.reload();
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

        // Helper Functions
        function resetForm() {
            $('#questionForm')[0].reset();
            $('#questionId').val('');
            $('#optionsContainer').empty();
            currentQuestionId = null;
            optionCounter = 0;
        }

        function addOption() {
            optionCounter++;
            let optionHtml = `
                    <div class="d-flex align-items-center mb-2 option-row">
                        <input type="text" name="option_text_${optionCounter}" placeholder="Option text" required
                               class="form-control me-2">
                        <input type="text" name="option_value_${optionCounter}" placeholder="Value" required
                               class="form-control me-2" style="max-width: 100px;">
                        <button type="button" onclick="removeOption(this)" class="btn btn-outline-danger btn-sm">
                            <i class="fas fa-minus-circle"></i>
                        </button>
                    </div>
                `;
            $('#optionsContainer').append(optionHtml);
        }

        function getOptions() {
            let options = [];
            $('.option-row').each(function() {
                let text = $(this).find('input[name^="option_text_"]').val();
                let value = $(this).find('input[name^="option_value_"]').val();
                if (text && value) {
                    options.push({
                        text: text,
                        value: value
                    });
                }
            });
            return options;
        }

        // Global Functions
        window.viewQuestion = function(id) {
            $.ajax({
                url: `${routes.show}/${id}`,
                type: 'GET',
                success: function(response) {
                    if (response.success) {
                        let question = response.data;
                        let optionsHtml = '';
                        question.options.forEach(function(option, index) {
                            optionsHtml += `<p class="mb-1"><strong>${index + 1}.</strong> ${option.text} <span class="text-muted">(${option.value})</span></p>`;
                        });

                        $('#viewContent').html(`
                                <div class="row g-3">
                                    <div class="col-12"><strong>Question ID:</strong> ${question.question_id}</div>
                                    <div class="col-12"><strong>Section:</strong> ${question.section}</div>
                                    <div class="col-12"><strong>Question:</strong> ${question.question}</div>
                                    <div class="col-12"><strong>Options:</strong></div>
                                    <div class="col-12 ps-4">${optionsHtml}</div>
                                    <div class="col-12"><strong>Order:</strong> ${question.order}</div>
                                    <div class="col-12"><strong>Status:</strong> ${question.is_active ? 'Active' : 'Inactive'}</div>
                                </div>
                            `);
                        var viewModal = new bootstrap.Modal(document.getElementById('viewModal'));
                        viewModal.show();
                    }
                }
            });
        };

        window.editQuestion = function(id) {
            $.ajax({
                url: `${routes.show}/${id}`,
                type: 'GET',
                success: function(response) {
                    if (response.success) {
                        let question = response.data;
                        resetForm();

                        currentQuestionId = id;
                        $('#modalTitle').text('Edit Question');
                        $('#saveBtn').text('Update Question');
                        $('#question_id').val(question.question_id);
                        $('#section').val(question.section);
                        $('#question').val(question.question);
                        $('#order').val(question.order);

                        // Add options
                        $('#optionsContainer').empty();
                        optionCounter = 0;
                        if (question.options && question.options.length) {
                            question.options.forEach(function(option) {
                                optionCounter++;
                                let optionHtml = `
                                    <div class="d-flex align-items-center mb-2 option-row">
                                        <input type="text" name="option_text_${optionCounter}" value="${option.text}" placeholder="Option text" required
                                               class="form-control me-2">
                                        <input type="text" name="option_value_${optionCounter}" value="${option.value}" placeholder="Value" required
                                               class="form-control me-2" style="max-width: 100px;">
                                        <button type="button" onclick="removeOption(this)" class="btn btn-outline-danger btn-sm">
                                            <i class="fas fa-minus-circle"></i>
                                        </button>
                                    </div>
                                `;
                                $('#optionsContainer').append(optionHtml);
                            });
                        }

                        var questionModal = new bootstrap.Modal(document.getElementById('questionModal'));
                        questionModal.show();
                    }
                }
            });
        };

        window.deleteQuestion = function(id) {
            currentQuestionId = id;
            var deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
            deleteModal.show();
        };

        window.toggleStatus = function(id) {
            $.ajax({
                url: `${routes.toggle}/${id}/toggle-status`,
                type: 'POST',
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                        table.ajax.reload();
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function() {
                    toastr.error('An error occurred. Please try again.');
                }
            });
        };

        window.removeOption = function(button) {
            $(button).closest('.option-row').remove();
        };

        // Load sections function
        function loadSections() {
            $.ajax({
                url: routes.sections,
                type: 'GET',
                success: function(response) {
                    if (response.success) {
                        let sectionSelect = $('#section');
                        sectionSelect.find('option:not(:first)').remove(); // Keep "Select Section" option
                        
                        response.data.forEach(function(section) {
                            sectionSelect.append(`<option value="${section.name}">${section.name}</option>`);
                        });
                    }
                },
                error: function() {
                    toastr.error('Failed to load sections');
                }
            });
        }
    });
</script>
@endsection

@endsection