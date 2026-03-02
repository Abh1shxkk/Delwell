@extends('admin.layout')
@section('title', 'Quiz Section Management')
@section('content')

<div class="container-fluid px-0">
    <div class="card shadow-sm">
        <!-- Header -->
        <div class="card-header border-bottom">
            <div class="d-flex justify-content-between align-items-center">
            <h1 class="h2 fw-bold text-dark mb-0">Quiz Section Management</h1>
            <button id="addSectionBtn" class="btn btn-primary d-flex align-items-center">
                <i class="fas fa-plus me-2"></i>
                Add New Section
            </button>
        </div>

        <!-- DataTable -->
        <div class="card-body p-4">
            <div class="table-responsive">
                <table id="sectionsTable" class="table table-striped table-hover">
                    <thead class="table-light">
                        <tr>
                            <th class="text-uppercase small fw-medium text-muted">ID</th>
                            <th class="text-uppercase small fw-medium text-muted">Name</th>
                            <th class="text-uppercase small fw-medium text-muted">Description</th>
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

<!-- Add/Edit Section Modal -->
<div id="sectionModal" class="modal fade" tabindex="-1" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Add New Section</h5>
                <button type="button" id="closeModal" class="btn-close" aria-label="Close"></button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body">
                <form id="sectionForm">
                    <input type="hidden" id="sectionId" name="sectionId">

                    <div class="mb-3">
                        <label for="name" class="form-label fw-medium">Section Name *</label>
                        <input type="text" id="name" name="name" required
                            class="form-control"
                            placeholder="e.g., Del Match Code™, Attachment Style">
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label fw-medium">Description</label>
                        <textarea id="description" name="description" rows="3"
                            class="form-control"
                            placeholder="Enter section description (optional)"></textarea>
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
                <button type="submit" id="saveBtn" class="btn btn-primary" form="sectionForm">
                    Save Section
                </button>
            </div>
        </div>
    </div>
</div>

<!-- View Section Modal -->
<div id="viewModal" class="modal fade" tabindex="-1" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h5 class="modal-title">View Section</h5>
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
                <p class="text-muted mb-4">Are you sure you want to delete this section? This action cannot be undone.</p>
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
        // Laravel Routes - TODO: Update these routes when backend is created
        const routes = {
            sections: '{{ route("admin.sections.index") ?? "/admin/sections" }}',
            store: '{{ route("admin.sections.store") ?? "/admin/sections" }}',
            show: '{{ url("admin/sections") }}',
            update: '{{ url("admin/sections") }}',
            destroy: '{{ url("admin/sections") }}',
            toggle: '{{ url("admin/sections") }}'
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

        // Initialize DataTable
        let table = $('#sectionsTable').DataTable({
            processing: true,
            serverSide: false,
            ajax: {
                url: routes.sections,
                type: 'GET'
            },
            columns: [{
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'description',
                    name: 'description',
                    render: function(data, type, row) {
                        if (!data) return '<span class="text-muted">No description</span>';
                        return data.length > 50 ? data.substring(0, 50) + '...' : data;
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
                                <button onclick="viewSection(${data})" class="btn btn-sm btn-outline-info me-1" title="View">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button onclick="editSection(${data})" class="btn btn-sm btn-outline-primary me-1" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                ${statusBtn}
                                <button onclick="deleteSection(${data})" class="btn btn-sm btn-outline-danger" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            `;
                    }
                }
            ],
            pageLength: 10,
            responsive: true,
            order: [
                [3, 'asc']
            ] // Order by 'order' column
        });

        // Modal Functions
        let currentSectionId = null;

        // Show Add Section Modal
        $('#addSectionBtn').click(function() {
            resetForm();
            $('#modalTitle').text('Add New Section');
            $('#saveBtn').text('Save Section');
            var sectionModal = new bootstrap.Modal(document.getElementById('sectionModal'));
            sectionModal.show();
        });

        // Close Modals
        $('#closeModal, #cancelBtn').click(function() {
            var sectionModal = bootstrap.Modal.getInstance(document.getElementById('sectionModal'));
            if (sectionModal) {
                sectionModal.hide();
            }
        });

        $('#closeViewModal').click(function() {
            var viewModal = bootstrap.Modal.getInstance(document.getElementById('viewModal'));
            if (viewModal) {
                viewModal.hide();
            }
        });

        // Submit Form
        $('#sectionForm').submit(function(e) {
            e.preventDefault();
            
            let formData = new FormData();
            
            formData.append('name', $('#name').val());
            formData.append('description', $('#description').val());
            formData.append('order', $('#order').val() || 0);
            formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

            if (currentSectionId) {
                formData.append('_method', 'PUT');
            }

            let url = currentSectionId ? 
                `${routes.update}/${currentSectionId}` : 
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
                        var sectionModal = bootstrap.Modal.getInstance(document.getElementById('sectionModal'));
                        if (sectionModal) {
                            sectionModal.hide();
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
            if (currentSectionId) {
                $.ajax({
                    url: `${routes.destroy}/${currentSectionId}`,
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
            $('#sectionForm')[0].reset();
            $('#sectionId').val('');
            currentSectionId = null;
        }

        // Global Functions
        window.viewSection = function(id) {
            $.ajax({
                url: `${routes.show}/${id}`,
                type: 'GET',
                success: function(response) {
                    if (response.success) {
                        let section = response.data;

                        $('#viewContent').html(`
                                <div class="row g-3">
                                    <div class="col-12"><strong>ID:</strong> ${section.id}</div>
                                    <div class="col-12"><strong>Name:</strong> ${section.name}</div>
                                    <div class="col-12"><strong>Description:</strong> ${section.description || 'No description'}</div>
                                    <div class="col-12"><strong>Order:</strong> ${section.order}</div>
                                    <div class="col-12"><strong>Status:</strong> ${section.is_active ? 'Active' : 'Inactive'}</div>
                                </div>
                            `);
                        var viewModal = new bootstrap.Modal(document.getElementById('viewModal'));
                        viewModal.show();
                    }
                }
            });
        };

        window.editSection = function(id) {
            $.ajax({
                url: `${routes.show}/${id}`,
                type: 'GET',
                success: function(response) {
                    if (response.success) {
                        let section = response.data;
                        resetForm();

                        currentSectionId = id;
                        $('#modalTitle').text('Edit Section');
                        $('#saveBtn').text('Update Section');
                        $('#name').val(section.name);
                        $('#description').val(section.description);
                        $('#order').val(section.order);

                        var sectionModal = new bootstrap.Modal(document.getElementById('sectionModal'));
                        sectionModal.show();
                    }
                }
            });
        };

        window.deleteSection = function(id) {
            currentSectionId = id;
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
    });
</script>
@endsection

@endsection