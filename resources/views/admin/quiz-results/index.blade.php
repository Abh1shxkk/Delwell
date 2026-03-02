@extends('admin.layout')

@section('title', 'Quiz Results Management')

@section('content')
<div class="container-fluid px-0">
    <div class="card shadow-sm">
        <!-- Header -->
        <div class="card-header border-bottom">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <h1 class="h2 fw-bold text-dark mb-0">Quiz Results Management</h1>
                <div class="d-flex gap-2 align-items-center">
                    <div class="input-group" style="width: 350px;">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input type="text" 
                               id="liveSearchInput" 
                               class="form-control border-start-0" 
                               placeholder="Search by name, email, username, or code..." 
                               autocomplete="off">
                        <button class="btn btn-outline-secondary d-none" type="button" id="clearSearchBtn">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div id="searchLoader" class="spinner-border spinner-border-sm text-primary d-none" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="card-body border-bottom">
            <div class="row g-3">
                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 bg-light">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <div class="text-uppercase small fw-medium text-muted mb-1">Total Users</div>
                                    <div class="h4 fw-bold text-primary mb-0">{{ number_format($stats['total_users']) }}</div>
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
                                    <div class="text-uppercase small fw-medium text-muted mb-1">With Results</div>
                                    <div class="h4 fw-bold text-success mb-0">{{ number_format($stats['users_with_results']) }}</div>
                                    <div class="small text-muted">{{ $stats['total_users'] > 0 ? round(($stats['users_with_results'] / $stats['total_users']) * 100, 1) : 0 }}%</div>
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
                                    <div class="text-uppercase small fw-medium text-muted mb-1">Without Results</div>
                                    <div class="h4 fw-bold text-warning mb-0">{{ number_format($stats['users_without_results']) }}</div>
                                    <div class="small text-muted">{{ $stats['total_users'] > 0 ? round(($stats['users_without_results'] / $stats['total_users']) * 100, 1) : 0 }}%</div>
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
                                    <div class="text-uppercase small fw-medium text-muted mb-1">Del Match Codes</div>
                                    <div class="h4 fw-bold text-info mb-0">{{ number_format($stats['users_with_del_match']) }}</div>
                                </div>
                                <div class="ms-3">
                                    <i class="fas fa-fingerprint fa-2x text-info opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Results Cards -->
        <div class="card-body p-4">
            <div class="row g-4" id="resultsContainer">
                @include('admin.quiz-results.partials.results-cards')
            </div>

            <!-- Pagination -->
            <div id="paginationContainer" class="mt-4">
                @include('admin.quiz-results.partials.pagination')
            </div>
        </div>
    </div>
</div>

<!-- View Results Modal -->
<div id="resultsModal" class="modal fade" tabindex="-1" style="display: none;">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <div class="d-flex align-items-center">
                    <div id="modalUserAvatar" class="rounded-circle me-3 bg-gradient d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);">
                        <i class="fas fa-user text-white"></i>
                    </div>
                    <div>
                        <h5 class="modal-title mb-0" id="modalUserName">User Name</h5>
                        <small class="text-muted" id="modalUserEmail">user@email.com</small>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>
            <div class="modal-body p-4">
                <!-- Del Match Code Section -->
                <div id="delMatchSection" class="mb-4" style="display: none;">
                    <div class="card bg-primary-subtle border-0">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-fingerprint fa-2x text-primary me-3"></i>
                                <div>
                                    <div class="small fw-medium text-primary text-uppercase">Del Match Code</div>
                                    <div class="h5 fw-bold text-primary mb-0" id="modalDelMatchCode">-</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quiz Results Section -->
                <div class="mb-4">
                    <h6 class="fw-bold text-dark text-uppercase mb-3">
                        <i class="fas fa-clipboard-check me-2 text-primary"></i>Quiz Responses
                    </h6>
                    <div id="quizResultsContainer">
                        <!-- Results will be populated here -->
                    </div>
                </div>

                <!-- Timestamps Section -->
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="card bg-light border-0">
                            <div class="card-body p-3">
                                <div class="small fw-medium text-muted text-uppercase">Registered</div>
                                <div class="fw-medium text-dark" id="modalCreatedAt">-</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card bg-light border-0">
                            <div class="card-body p-3">
                                <div class="small fw-medium text-muted text-uppercase">Last Updated</div>
                                <div class="fw-medium text-dark" id="modalUpdatedAt">-</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-top">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Results History Modal -->
<div id="historyModal" class="modal fade" tabindex="-1" style="display: none;">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header border-bottom" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
                        <i class="fas fa-history text-white"></i>
                    </div>
                    <div>
                        <h5 class="modal-title mb-0">Results History</h5>
                        <small class="text-muted" id="historyUserName">User Name</small>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-primary rounded-pill" id="historyCount">0 submissions</span>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>
            <div class="modal-body p-4" id="historyModalBody">
                <div class="text-center py-4">
                    <i class="fas fa-spinner fa-spin fa-2x text-primary"></i>
                    <p class="text-muted mt-2">Loading history...</p>
                </div>
            </div>
            <div class="modal-footer border-top">
                <button type="button" class="btn btn-outline-primary" onclick="backToCurrentResults()">
                    <i class="fas fa-arrow-left me-1"></i>Back to Current Results
                </button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete History Confirmation Modal -->
<div id="deleteHistoryModal" class="modal fade" tabindex="-1" style="display: none;">
    <div class="modal-dialog modal-sm" style="margin-top: 80px;">
        <div class="modal-content">
            <div class="modal-body text-center p-4">
                <div class="mb-3">
                    <div class="rounded-circle mx-auto d-flex align-items-center justify-content-center" style="width: 64px; height: 64px; background-color: #fee2e2;">
                        <i class="fas fa-trash-alt fa-xl" style="color: #dc2626;"></i>
                    </div>
                </div>
                <h5 class="fw-bold mb-2">Delete History Entry?</h5>
                <p class="text-muted mb-4">This quiz submission will be permanently deleted. This action cannot be undone.</p>
                <div class="d-flex gap-2 justify-content-center">
                    <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger px-4" id="confirmDeleteBtn" onclick="executeDeleteHistory()">
                        <i class="fas fa-trash-alt me-1"></i>Delete
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<style>
    .result-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.12) !important;
    }

    .quiz-response-item {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 16px;
        margin-bottom: 12px;
        border-left: 4px solid #6366f1;
        transition: all 0.2s ease;
    }

    .quiz-response-item:hover {
        background: #f1f3f5;
    }

    .quiz-response-item.unanswered {
        border-left-color: #d1d5db;
        background: #fafafa;
        opacity: 0.8;
    }

    .quiz-response-item .question {
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 8px;
    }

    .quiz-response-item .answer {
        color: #4b5563;
        padding-left: 12px;
        border-left: 2px solid #e5e7eb;
    }

    .section-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 20px;
    }

    .section-header h6 {
        margin-bottom: 0;
    }

    .section-questions {
        max-height: 400px;
        overflow-y: auto;
    }

    .section-questions::-webkit-scrollbar {
        width: 6px;
    }

    .section-questions::-webkit-scrollbar-thumb {
        background: #d1d5db;
        border-radius: 3px;
    }

    .history-card {
        transition: all 0.2s ease;
        border-radius: 12px !important;
        overflow: hidden;
    }

    .history-card:hover {
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1) !important;
    }

    .history-chevron {
        transition: transform 0.2s ease;
        color: #6b7280;
    }

    .pagination {
        gap: 6px;
    }

    .page-link {
        border-radius: 8px !important;
        border: 1px solid #e5e7eb;
        padding: 8px 14px;
        color: #6b7280;
        background: #fff;
        transition: all 0.2s ease;
    }

    .page-link:hover {
        background: #f3f4f6;
        color: #4b5563;
        border-color: #d1d5db;
    }

    .page-item.active .page-link {
        background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
        border-color: transparent;
        color: #fff;
        box-shadow: 0 2px 8px rgba(99, 102, 241, 0.3);
    }

    .page-item.active .page-link:hover {
        background: linear-gradient(135deg, #5558e3 0%, #7c4fe0 100%);
    }

    .page-item.disabled .page-link {
        background: #f9fafb;
        color: #d1d5db;
        border-color: #e5e7eb;
    }
</style>

<script>
    function viewResults(userId) {
        currentHistoryUserId = userId;
        // Show loading state
        $('#quizResultsContainer').html('<div class="text-center py-4"><i class="fas fa-spinner fa-spin fa-2x text-primary"></i><p class="text-muted mt-2">Loading results...</p></div>');
        $('#resultsModal').modal('show');

        // Fetch user data
        $.ajax({
            url: '{{ url("admin/quiz-results") }}/' + userId,
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    const user = response.user;
                    const sections = response.sections || [];
                    const totalAnswers = response.total_answers || 0;
                    const totalQuestions = response.total_questions || 39;
                    
                    // Update user info
                    $('#modalUserName').text(user.name);
                    $('#modalUserEmail').text(user.username ? '@' + user.username : user.email);
                    
                    // Update avatar
                    if (user.ai_avatar_path) {
                        let avatarSrc = user.ai_avatar_path.startsWith('http') ? user.ai_avatar_path : '{{ url("storage") }}/' + user.ai_avatar_path;
                        $('#modalUserAvatar').html('<img src="' + avatarSrc + '" class="rounded-circle" style="width: 48px; height: 48px; object-fit: cover;" onerror="this.parentElement.innerHTML=\'<i class=\\\'fas fa-user text-white\\\'></i>\'">');
                    } else if (user.profile_image) {
                        let avatarSrc = user.profile_image.startsWith('http') ? user.profile_image : '{{ url("storage") }}/' + user.profile_image;
                        $('#modalUserAvatar').html('<img src="' + avatarSrc + '" class="rounded-circle" style="width: 48px; height: 48px; object-fit: cover;" onerror="this.parentElement.innerHTML=\'<i class=\\\'fas fa-user text-white\\\'></i>\'">');
                    } else {
                        $('#modalUserAvatar').html('<i class="fas fa-user text-white"></i>');
                    }
                    
                    // Update Del Match Code
                    if (user.del_match_code) {
                        $('#delMatchSection').show();
                        $('#modalDelMatchCode').text(user.del_match_code);
                    } else {
                        $('#delMatchSection').hide();
                    }
                    
                    // Update timestamps
                    $('#modalCreatedAt').text(user.created_at);
                    $('#modalUpdatedAt').text(user.updated_at);
                    
                    // Build section-wise results HTML
                    let resultsHtml = '';
                    
                    // Add total answers badge
                    resultsHtml += `
                        <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
                            <span class="text-muted">Total Questions Answered</span>
                            <span class="badge bg-success rounded-pill px-3 py-2">${totalAnswers} / ${totalQuestions}</span>
                        </div>
                    `;
                    
                    if (sections.length > 0) {
                        sections.forEach(function(section, sectionIndex) {
                            // Filter only answered questions
                            const answeredQuestions = section.questions.filter(q => q.answer !== null);
                            
                            // Skip section if no answered questions
                            if (answeredQuestions.length === 0) {
                                return;
                            }
                            
                            resultsHtml += `
                                <div class="section-card mb-4">
                                    <div class="section-header d-flex justify-content-between align-items-center mb-3">
                                        <div>
                                            <h6 class="fw-bold text-primary mb-1">
                                                <i class="fas fa-layer-group me-2"></i>${escapeHtml(section.section_name)}
                                            </h6>
                                            ${section.section_description ? '<small class="text-muted">' + escapeHtml(section.section_description) + '</small>' : ''}
                                        </div>
                                        <span class="badge bg-light text-dark border">${answeredQuestions.length}/${section.questions.length}</span>
                                    </div>
                                    <div class="section-questions">
                            `;
                            
                            // Only show answered questions
                            answeredQuestions.forEach(function(q, qIndex) {
                                resultsHtml += `
                                    <div class="quiz-response-item">
                                        <div class="question">
                                            <span class="badge bg-secondary me-2">${qIndex + 1}</span>
                                            ${escapeHtml(q.question)}
                                        </div>
                                        <div class="answer">
                                            <i class="fas fa-check-circle text-success me-2"></i>
                                            ${escapeHtml(String(q.answer))}
                                        </div>
                                    </div>
                                `;
                            });
                            
                            resultsHtml += `
                                    </div>
                                </div>
                            `;
                        });
                    } else {
                        resultsHtml = '<div class="text-center py-4 text-muted"><i class="fas fa-inbox fa-2x mb-2"></i><p>No quiz responses available</p></div>';
                    }
                    
                    $('#quizResultsContainer').html(resultsHtml);
                    
                    // History is now shown on cards directly
                } else {
                    $('#quizResultsContainer').html('<div class="alert alert-danger">Failed to load results</div>');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error loading results:', error);
                $('#quizResultsContainer').html('<div class="alert alert-danger">An error occurred while loading results. Please try again.</div>');
            }
        });
    }
    
    function escapeHtml(text) {
        if (text === null || text === undefined) return '';
        const div = document.createElement('div');
        div.textContent = String(text);
        return div.innerHTML;
    }

    // Live Search Functionality
    let searchTimeout = null;
    let currentPage = 1;
    let currentSearch = '';

    $(document).ready(function() {
        const searchInput = $('#liveSearchInput');
        const clearBtn = $('#clearSearchBtn');
        const loader = $('#searchLoader');
        const resultsContainer = $('#resultsContainer');
        const paginationContainer = $('#paginationContainer');

        // Live search on input
        searchInput.on('input', function() {
            const query = $(this).val().trim();
            currentSearch = query;
            currentPage = 1;

            // Show/hide clear button
            if (query.length > 0) {
                clearBtn.removeClass('d-none');
            } else {
                clearBtn.addClass('d-none');
            }

            // Debounce the search
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(function() {
                performSearch(query, 1);
            }, 300);
        });

        // Clear search
        clearBtn.on('click', function() {
            searchInput.val('');
            currentSearch = '';
            currentPage = 1;
            clearBtn.addClass('d-none');
            performSearch('', 1);
        });

        // Handle keyboard shortcuts
        searchInput.on('keydown', function(e) {
            if (e.key === 'Escape') {
                if ($(this).val()) {
                    clearBtn.click();
                }
            }
        });
    });

    function performSearch(query, page) {
        const loader = $('#searchLoader');
        const resultsContainer = $('#resultsContainer');
        const paginationContainer = $('#paginationContainer');

        // Show loader
        loader.removeClass('d-none');

        // Add loading state to results
        resultsContainer.css('opacity', '0.5');

        $.ajax({
            url: '{{ route("admin.quiz-results.search") }}',
            method: 'GET',
            data: {
                search: query,
                page: page
            },
            success: function(response) {
                if (response.success) {
                    // Update results
                    resultsContainer.html(response.html);
                    
                    // Update pagination
                    paginationContainer.html(response.pagination);

                    // Update URL without reload
                    const url = new URL(window.location);
                    if (query) {
                        url.searchParams.set('search', query);
                    } else {
                        url.searchParams.delete('search');
                    }
                    if (page > 1) {
                        url.searchParams.set('page', page);
                    } else {
                        url.searchParams.delete('page');
                    }
                    window.history.replaceState({}, '', url);
                }
            },
            error: function(xhr, status, error) {
                console.error('Search error:', error);
                resultsContainer.html('<div class="col-12"><div class="alert alert-danger">An error occurred while searching. Please try again.</div></div>');
            },
            complete: function() {
                // Hide loader
                loader.addClass('d-none');
                resultsContainer.css('opacity', '1');
            }
        });
    }

    function loadPage(page) {
        currentPage = page;
        performSearch(currentSearch, page);
        
        // Scroll to top of results
        $('html, body').animate({
            scrollTop: $('#resultsContainer').offset().top - 100
        }, 300);
    }

    // ========== Results History ==========
    let currentHistoryUserId = null;
    let historyData = null;

    function viewResultsHistory(userId) {
        if (userId) currentHistoryUserId = userId;
        if (!currentHistoryUserId) return;

        // Close any open modal and open history modal
        $('#resultsModal').modal('hide');
        
        setTimeout(function() {
            $('#historyModalBody').html('<div class="text-center py-4"><i class="fas fa-spinner fa-spin fa-2x text-primary"></i><p class="text-muted mt-2">Loading history...</p></div>');
            $('#historyModal').modal('show');

            $.ajax({
                url: '{{ url("admin/quiz-results") }}/' + currentHistoryUserId + '/history',
                method: 'GET',
                success: function(response) {
                    if (response.success) {
                        historyData = response;
                        $('#historyUserName').text(response.user.name + (response.user.username ? ' (@' + response.user.username + ')' : ''));
                        $('#historyCount').text(response.total_submissions + ' past submission' + (response.total_submissions !== 1 ? 's' : ''));

                        if (response.history.length === 0) {
                            $('#historyModalBody').html('<div class="text-center py-5 text-muted"><i class="fas fa-inbox fa-3x mb-3"></i><p class="h5">No past quiz submissions found</p><p>This user has only submitted the quiz once.</p></div>');
                            return;
                        }

                        let html = '';
                        
                        response.history.forEach(function(entry, idx) {
                            const submittedAt = entry.submitted_at ? new Date(entry.submitted_at).toLocaleString('en-US', { year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' }) : 'Unknown';
                            const archivedAt = entry.archived_at ? new Date(entry.archived_at).toLocaleString('en-US', { year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' }) : null;
                            
                            html += `
                                <div class="card mb-3 border shadow-sm history-card">
                                    <div class="card-header d-flex justify-content-between align-items-center" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); cursor: pointer;" onclick="toggleHistoryEntry(${idx})">
                                        <div class="d-flex align-items-center">
                                            <div class="rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);">
                                                <span class="text-white fw-bold small">#${entry.index}</span>
                                            </div>
                                            <div>
                                                <h6 class="mb-0 fw-bold">Submission #${entry.index}</h6>
                                                <div class="d-flex gap-3 mt-1">
                                                    <small class="text-muted"><i class="fas fa-clock me-1"></i>Submitted: ${submittedAt}</small>
                                                    ${archivedAt ? '<small class="text-muted"><i class="fas fa-archive me-1"></i>Archived: ' + archivedAt + '</small>' : ''}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center gap-2">
                                            ${entry.del_match_code ? '<span class="badge rounded-pill px-2 py-1" style="background-color: #6366f1; color: #fff;">' + escapeHtml(entry.del_match_code) + '</span>' : ''}
                                            <span class="badge rounded-pill px-2 py-1" style="background-color: #0ea5e9; color: #fff;">${entry.total_answers}/${entry.total_questions} answered</span>
                                            <button type="button" class="btn btn-outline-danger btn-sm" onclick="event.stopPropagation(); confirmDeleteHistory(${idx})" title="Delete this submission">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                            <i class="fas fa-chevron-down history-chevron" id="chevron-${idx}"></i>
                                        </div>
                                    </div>
                                    <div class="card-body p-0" id="historyEntry-${idx}" style="display: none;">
                                        <div class="p-4">
                            `;

                            // Quiz summary badges
                            if (entry.quiz_summary) {
                                html += '<div class="d-flex flex-wrap gap-2 mb-3">';
                                if (entry.quiz_summary.attachment_style) {
                                    html += '<span class="badge bg-info-subtle text-info border"><i class="fas fa-brain me-1"></i>Attachment: ' + escapeHtml(entry.quiz_summary.attachment_style) + '</span>';
                                }
                                if (entry.quiz_summary.primary_value) {
                                    html += '<span class="badge bg-warning-subtle text-warning border"><i class="fas fa-heart me-1"></i>Value: ' + escapeHtml(entry.quiz_summary.primary_value) + '</span>';
                                }
                                if (entry.quiz_summary.ready_for_dating) {
                                    html += '<span class="badge ' + (entry.quiz_summary.ready_for_dating === 'Yes' ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger') + ' border"><i class="fas fa-check-circle me-1"></i>Ready: ' + escapeHtml(entry.quiz_summary.ready_for_dating) + '</span>';
                                }
                                html += '</div>';
                            }

                            // Sections with questions
                            if (entry.sections && entry.sections.length > 0) {
                                entry.sections.forEach(function(section) {
                                    const answeredQuestions = section.questions.filter(q => q.answer !== null);
                                    if (answeredQuestions.length === 0) return;

                                    html += `
                                        <div class="section-card mb-3">
                                            <div class="section-header d-flex justify-content-between align-items-center mb-2">
                                                <h6 class="fw-bold text-primary mb-0 small">
                                                    <i class="fas fa-layer-group me-1"></i>${escapeHtml(section.section_name)}
                                                </h6>
                                                <span class="badge bg-light text-dark border small">${answeredQuestions.length}/${section.questions.length}</span>
                                            </div>
                                            <div class="section-questions">
                                    `;

                                    answeredQuestions.forEach(function(q, qIndex) {
                                        html += `
                                            <div class="quiz-response-item" style="padding: 10px 14px; margin-bottom: 8px;">
                                                <div class="question" style="font-size: 0.9rem;">
                                                    <span class="badge bg-secondary me-1" style="font-size: 0.7rem;">${qIndex + 1}</span>
                                                    ${escapeHtml(q.question)}
                                                </div>
                                                <div class="answer" style="font-size: 0.85rem;">
                                                    <i class="fas fa-check-circle text-success me-1"></i>
                                                    ${escapeHtml(String(q.answer))}
                                                </div>
                                            </div>
                                        `;
                                    });

                                    html += `
                                            </div>
                                        </div>
                                    `;
                                });
                            }

                            html += `
                                        </div>
                                    </div>
                                </div>
                            `;
                        });

                        $('#historyModalBody').html(html);
                    } else {
                        $('#historyModalBody').html('<div class="alert alert-danger">Failed to load history</div>');
                    }
                },
                error: function() {
                    $('#historyModalBody').html('<div class="alert alert-danger">An error occurred while loading history.</div>');
                }
            });
        }, 400);
    }

    function toggleHistoryEntry(idx) {
        const entry = $('#historyEntry-' + idx);
        const chevron = $('#chevron-' + idx);
        
        if (entry.is(':visible')) {
            entry.slideUp(200);
            chevron.removeClass('fa-chevron-up').addClass('fa-chevron-down');
        } else {
            entry.slideDown(200);
            chevron.removeClass('fa-chevron-down').addClass('fa-chevron-up');
        }
    }

    function backToCurrentResults() {
        $('#historyModal').modal('hide');
        setTimeout(function() {
            if (currentHistoryUserId) {
                viewResults(currentHistoryUserId);
            }
        }, 400);
    }

    function deleteHistoryEntry(idx) {
        if (!currentHistoryUserId) return;

        $('#confirmDeleteBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i>Deleting...');

        $.ajax({
            url: '{{ url("admin/quiz-results") }}/' + currentHistoryUserId + '/history/' + idx,
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            success: function(response) {
                deleteWasExecuted = true;
                $('#deleteHistoryModal').modal('hide');
                if (response.success) {
                    if (response.remaining === 0) {
                        setTimeout(function() {
                            $('#historyModal').modal('show');
                            $('#historyModalBody').html('<div class="text-center py-5 text-muted"><i class="fas fa-inbox fa-3x mb-3"></i><p class="h5">No past quiz submissions</p><p>All history entries have been deleted.</p></div>');
                            $('#historyCount').text('0 submissions');
                        }, 300);
                    } else {
                        // Reload and reopen history modal
                        setTimeout(function() {
                            viewResultsHistory(currentHistoryUserId);
                        }, 300);
                    }
                } else {
                    alert(response.message || 'Failed to delete history entry.');
                }
            },
            error: function() {
                deleteWasExecuted = true;
                $('#deleteHistoryModal').modal('hide');
                setTimeout(function() {
                    $('#historyModal').modal('show');
                }, 300);
                alert('An error occurred while deleting the history entry.');
            },
            complete: function() {
                $('#confirmDeleteBtn').prop('disabled', false).html('<i class="fas fa-trash-alt me-1"></i>Delete');
            }
        });
    }

    let pendingDeleteIdx = null;

    function confirmDeleteHistory(idx) {
        pendingDeleteIdx = idx;
        // Hide the history modal behind
        $('#historyModal').modal('hide');
        setTimeout(function() {
            $('#deleteHistoryModal').modal('show');
        }, 300);
    }

    // Reopen history modal when delete modal closes (if not deleted)
    $('#deleteHistoryModal').on('hidden.bs.modal', function() {
        if (!deleteWasExecuted) {
            $('#historyModal').modal('show');
        }
        deleteWasExecuted = false;
    });

    let deleteWasExecuted = false;

    function executeDeleteHistory() {
        if (pendingDeleteIdx !== null) {
            deleteHistoryEntry(pendingDeleteIdx);
            pendingDeleteIdx = null;
        }
    }

    function loadHistoryContent() {
        // Re-use the full viewResultsHistory logic but skip closing/reopening the modal
        if (!currentHistoryUserId) return;
        $.ajax({
            url: '{{ url("admin/quiz-results") }}/' + currentHistoryUserId + '/history',
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    historyData = response;
                    $('#historyCount').text(response.total_submissions + ' past submission' + (response.total_submissions !== 1 ? 's' : ''));
                    if (response.history.length === 0) {
                        $('#historyModalBody').html('<div class="text-center py-5 text-muted"><i class="fas fa-inbox fa-3x mb-3"></i><p class="h5">No past quiz submissions</p><p>All history entries have been deleted.</p></div>');
                        return;
                    }
                    // Re-trigger full render
                    viewResultsHistory();
                }
            }
        });
    }
</script>
@endsection
