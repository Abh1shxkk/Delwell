@extends('admin.layout')

@section('title', 'User Management')

@section('content')
<div class="container-fluid px-0" style="min-height: calc(100vh - 200px);">
    <div class="card shadow-sm">
        <!-- Header -->
        <div class="card-header border-bottom">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h2 fw-bold text-dark mb-0">User Management</h1>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-outline-secondary btn-sm d-flex align-items-center" id="refreshBtn">
                        <i class="fas fa-sync-alt me-2"></i>Refresh
                    </button>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="card-body border-bottom">
            <div class="row g-3" id="statsCards">
                <div class="col">
                    <div class="card border-0 bg-light h-100">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <div class="text-uppercase small fw-medium text-muted mb-1">Total Users</div>
                                    <div class="h4 fw-bold mb-0" style="color: #6366F1;" id="totalUsers">{{ number_format($stats['total']) }}</div>
                                </div>
                                <div class="ms-3">
                                    <i class="fas fa-users fa-2x" style="color: #6366F1; opacity: 0.75;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col">
                    <div class="card border-0 bg-light h-100">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <div class="text-uppercase small fw-medium text-muted mb-1">Verified</div>
                                    <div class="h4 fw-bold mb-0" style="color: #0D9488;" id="verifiedUsers">{{ number_format($stats['verified']) }}</div>
                                </div>
                                <div class="ms-3">
                                    <i class="fas fa-check-circle fa-2x" style="color: #0D9488; opacity: 0.75;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col">
                    <div class="card border-0 bg-light h-100">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <div class="text-uppercase small fw-medium text-muted mb-1">Unverified</div>
                                    <div class="h4 fw-bold mb-0" style="color: #DC2626;" id="unverifiedUsers">{{ number_format($stats['unverified']) }}</div>
                                </div>
                                <div class="ms-3">
                                    <i class="fas fa-exclamation-circle fa-2x" style="color: #DC2626; opacity: 0.75;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col">
                    <div class="card border-0 bg-light h-100">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <div class="text-uppercase small fw-medium text-muted mb-1">Active</div>
                                    <div class="h4 fw-bold mb-0" style="color: #059669;" id="activeUsers">{{ number_format($stats['active']) }}</div>
                                </div>
                                <div class="ms-3">
                                    <i class="fas fa-user-check fa-2x" style="color: #059669; opacity: 0.75;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col">
                    <div class="card border-0 bg-light h-100">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <div class="text-uppercase small fw-medium text-muted mb-1">Inactive</div>
                                    <div class="h4 fw-bold mb-0" style="color: #D97706;" id="inactiveUsers">{{ number_format($stats['inactive']) }}</div>
                                </div>
                                <div class="ms-3">
                                    <i class="fas fa-user-times fa-2x" style="color: #D97706; opacity: 0.75;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col">
                    <div class="card border-0 bg-light h-100">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <div class="text-uppercase small fw-medium text-muted mb-1">Profile Complete</div>
                                    <div class="h4 fw-bold mb-0" style="color: #10B981;" id="profileComplete">{{ number_format($stats['profile_complete']) }}</div>
                                </div>
                                <div class="ms-3">
                                    <i class="fas fa-check-double fa-2x" style="color: #10B981; opacity: 0.75;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col">
                    <div class="card border-0 bg-light h-100">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <div class="text-uppercase small fw-medium text-muted mb-1">Avg Completion</div>
                                    <div class="h4 fw-bold mb-0" style="color: #3B82F6;" id="avgCompletion">{{ $stats['avg_profile_completion'] }}%</div>
                                </div>
                                <div class="ms-3">
                                    <i class="fas fa-chart-line fa-2x" style="color: #3B82F6; opacity: 0.75;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col">
                    <div class="card border-0 bg-light h-100">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <div class="text-uppercase small fw-medium text-muted mb-1">Blocked</div>
                                    <div class="h4 fw-bold mb-0" style="color: #1F2937;" id="blockedUsers">{{ number_format($stats['blocked']) }}</div>
                                </div>
                                <div class="ms-3">
                                    <i class="fas fa-ban fa-2x" style="color: #1F2937; opacity: 0.75;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col">
                    <div class="card border-0 bg-light h-100">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <div class="text-uppercase small fw-medium text-muted mb-1">Deleted</div>
                                    <div class="h4 fw-bold text-danger mb-0" id="deletedUsers">{{ number_format($stats['deleted']) }}</div>
                                </div>
                                <div class="ms-3">
                                    <i class="fas fa-trash fa-2x text-danger opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="card-body border-bottom bg-light">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label small fw-medium text-muted">Email Status</label>
                    <select id="verifiedFilter" class="form-select form-select-sm">
                        <option value="all">All</option>
                        <option value="verified">Verified</option>
                        <option value="unverified">Unverified</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-medium text-muted">Account Status</label>
                    <select id="statusFilter" class="form-select form-select-sm">
                        <option value="all">All</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-medium text-muted">Blocked</label>
                    <select id="blockedFilter" class="form-select form-select-sm">
                        <option value="all">All</option>
                        <option value="blocked">Blocked Only</option>
                        <option value="not_blocked">Not Blocked</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-medium text-muted">Deleted</label>
                    <select id="deletedFilter" class="form-select form-select-sm">
                        <option value="active" selected>Active Users</option>
                        <option value="deleted">Deleted Only</option>
                        <option value="all">All (Including Deleted)</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-medium text-muted">Profile Completion</label>
                    <select id="profileFilter" class="form-select form-select-sm">
                        <option value="all">All</option>
                        <option value="complete">Complete (100%)</option>
                        <option value="incomplete">Incomplete (&lt;100%)</option>
                        <option value="high">High (75-99%)</option>
                        <option value="medium">Medium (50-74%)</option>
                        <option value="low">Low (&lt;50%)</option>
                    </select>
                </div>

            </div>
        </div>

        <!-- DataTable -->
        <div class="card-body p-4">
            <div class="table-responsive">
                <table id="usersTable" class="table table-striped table-hover">
                    <thead class="table-light">
                        <tr>
                            <th class="text-uppercase small fw-medium text-muted">User</th>
                            <th class="text-uppercase small fw-medium text-muted">Email</th>
                            <th class="text-uppercase small fw-medium text-muted">Status</th>
                            <th class="text-uppercase small fw-medium text-muted">Email Verified</th>
                            <th class="text-uppercase small fw-medium text-muted">Profile</th>
                            <th class="text-uppercase small fw-medium text-muted">Registered</th>
                            <th class="text-uppercase small fw-medium text-muted">Actions</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- View User Modal (Section-wise) -->
<div id="viewModal" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-user me-2"></i>User Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0" id="viewModalBody">
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit User Modal (Comprehensive) -->
<div id="editModal" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-edit me-2"></i>Edit User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editUserForm">
                    <input type="hidden" id="editUserId">
                    
                    <!-- Account Settings -->
                    <div class="mb-4">
                        <h6 class="fw-bold text-primary border-bottom pb-2"><i class="fas fa-cog me-2"></i>Account Settings</h6>
                        <div class="row g-3 mt-2">
                            <div class="col-6 col-sm-6 col-md-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="editActive">
                                    <label class="form-check-label small" for="editActive">Active</label>
                                </div>
                            </div>
                            <div class="col-6 col-sm-6 col-md-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="editEmailVerified">
                                    <label class="form-check-label small" for="editEmailVerified">Verified</label>
                                </div>
                            </div>
                            <div class="col-6 col-sm-6 col-md-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="editIsBlocked">
                                    <label class="form-check-label small" for="editIsBlocked">Blocked</label>
                                </div>
                            </div>
                            <div class="col-6 col-sm-6 col-md-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="editIsPremium">
                                    <label class="form-check-label small" for="editIsPremium">Premium</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Basic Information -->
                    <div class="mb-4">
                        <h6 class="fw-bold text-primary border-bottom pb-2"><i class="fas fa-user me-2"></i>Basic Information</h6>
                        <div class="row g-3 mt-2">
                            <div class="col-md-4">
                                <label class="form-label">Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="editName" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Username <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="editUsername" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="editEmail" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Phone</label>
                                <input type="text" class="form-control" id="editPhone">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Age</label>
                                <input type="number" class="form-control" id="editAge" min="18" max="120">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Gender Identity</label>
                                <select class="form-select" id="editGenderIdentity">
                                    <option value="">Select</option>
                                    <option value="women">Women</option>
                                    <option value="men">Men</option>
                                    <option value="nonbinary">Nonbinary</option>
                                    <option value="prefer_not_to_say">Prefer not to say</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Sexual Orientation</label>
                                <select class="form-select" id="editSexualOrientation">
                                    <option value="">Select</option>
                                    <option value="heterosexual">Heterosexual</option>
                                    <option value="lgbtq+">LGBTQ+</option>
                                    <option value="prefer_not_to_say">Prefer not to say</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Interested In</label>
                                <select class="form-select" id="editInterestedIn">
                                    <option value="">Select</option>
                                    <option value="men">Men</option>
                                    <option value="women">Women</option>
                                    <option value="both">Both</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Looking For</label>
                                <select class="form-select" id="editRelationshipType">
                                    <option value="">Select</option>
                                    <option value="serious">Serious Relationship</option>
                                    <option value="casual">Casual Dating</option>
                                    <option value="friendship">Friendship</option>
                                    <option value="open">Open to Anything</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Min Age Pref</label>
                                <input type="number" class="form-control" id="editAgeMin" min="18" max="100">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Max Age Pref</label>
                                <input type="number" class="form-control" id="editAgeMax" min="18" max="100">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Bio</label>
                                <textarea class="form-control" id="editBio" rows="2" maxlength="1000"></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Relationship Context -->
                    <div class="mb-4">
                        <h6 class="fw-bold text-primary border-bottom pb-2"><i class="fas fa-heart me-2"></i>Relationship Context</h6>
                        <div class="row g-3 mt-2">
                            <div class="col-md-6">
                                <label class="form-label">Relationship Status</label>
                                <select class="form-select" id="editRelationshipStatus">
                                    <option value="">Select</option>
                                    <option value="single">Single</option>
                                    <option value="divorced">Divorced</option>
                                    <option value="separated">Separated</option>
                                    <option value="widowed">Widowed</option>
                                    <option value="in_a_relationship">In a relationship</option>
                                    <option value="it_is_complicated">It's complicated</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Has Children</label>
                                <select class="form-select" id="editHasChildren">
                                    <option value="">Select</option>
                                    <option value="yes">Yes</option>
                                    <option value="no">No</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Lifestyle -->
                    <div class="mb-4">
                        <h6 class="fw-bold text-primary border-bottom pb-2"><i class="fas fa-running me-2"></i>Lifestyle</h6>
                        <div class="row g-3 mt-2">
                            <div class="col-md-3">
                                <label class="form-label">City</label>
                                <input type="text" class="form-control" id="editCity">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">State</label>
                                <input type="text" class="form-control" id="editState">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Distance Preference</label>
                                <select class="form-select" id="editDistancePreference">
                                    <option value="">Select</option>
                                    <option value="10">Within 10 miles</option>
                                    <option value="25">Within 25 miles</option>
                                    <option value="50">Within 50 miles</option>
                                    <option value="long">Open to long-distance</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Physical Activity</label>
                                <select class="form-select" id="editPhysicalActivity">
                                    <option value="">Select</option>
                                    <option value="not_active">Not very active</option>
                                    <option value="occasionally_active">Occasionally active</option>
                                    <option value="active">Active</option>
                                    <option value="fitness_lifestyle">Fitness is part of my lifestyle</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Occupation</label>
                                <select class="form-select" id="editOccupation">
                                    <option value="">Select</option>
                                    <option value="psychologist">Psychologist / Therapist / Counselor</option>
                                    <option value="medical">Medical or Healthcare Professional</option>
                                    <option value="wellness">Wellness / Fitness / Holistic Practitioner</option>
                                    <option value="entrepreneur">Entrepreneur / Business Owner</option>
                                    <option value="finance">Finance / Consulting / Marketing</option>
                                    <option value="software">Software / Product / Data Professional</option>
                                    <option value="engineer">Engineer / Technical Specialist</option>
                                    <option value="artist">Artist / Designer / Writer / Musician</option>
                                    <option value="educator">Educator / Academic / Researcher</option>
                                    <option value="attorney">Attorney / Legal / Government</option>
                                    <option value="real_estate">Real Estate / Architecture / Design</option>
                                    <option value="hospitality">Hospitality / Travel / Event Management</option>
                                    <option value="beauty">Beauty / Lifestyle / Culinary</option>
                                    <option value="student">Student</option>
                                    <option value="parent">Stay-at-Home Parent / Caregiver</option>
                                    <option value="retired">Retired / Career Transition</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Education</label>
                                <select class="form-select" id="editEducation">
                                    <option value="">Select</option>
                                    <option value="less_than_bachelor">Less than Bachelor's degree</option>
                                    <option value="bachelor">Bachelor's</option>
                                    <option value="master">Master's</option>
                                    <option value="doctorate">Doctorate / Professional degree</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Substance Use -->
                    <div class="mb-4">
                        <h6 class="fw-bold text-primary border-bottom pb-2"><i class="fas fa-wine-glass me-2"></i>Substance Use</h6>
                        <div class="row g-3 mt-2">
                            <div class="col-md-4">
                                <label class="form-label">Alcohol Use</label>
                                <select class="form-select" id="editAlcoholUse">
                                    <option value="">Select</option>
                                    <option value="never">Never</option>
                                    <option value="occasionally">Occasionally</option>
                                    <option value="socially">Socially</option>
                                    <option value="regularly">Regularly</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Cannabis Use</label>
                                <select class="form-select" id="editCannabisUse">
                                    <option value="">Select</option>
                                    <option value="never">Never</option>
                                    <option value="occasionally">Occasionally</option>
                                    <option value="regularly">Regularly</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Smoking/Vaping</label>
                                <select class="form-select" id="editSmokingVaping">
                                    <option value="">Select</option>
                                    <option value="never">Never</option>
                                    <option value="occasionally">Occasionally</option>
                                    <option value="regularly">Regularly</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Media Section (Editable) -->
                    <div class="mb-4">
                        <h6 class="fw-bold text-primary border-bottom pb-2"><i class="fas fa-image me-2"></i>Media</h6>
                        <div class="row g-3 mt-2">
                            <div class="col-md-6">
                                <label class="form-label">Profile Image</label>
                                <div class="upload-dropzone" id="profileImageDropzone" onclick="$('#editProfileImage').click()">
                                    <div id="editProfileImagePreview" class="upload-preview">
                                        <i class="fas fa-cloud-upload-alt fa-2x text-muted mb-2"></i>
                                        <p class="mb-1 fw-medium">Drag & drop or click to upload</p>
                                        <small class="text-muted">JPG, PNG, GIF (Max: 5MB)</small>
                                    </div>
                                </div>
                                <input type="file" class="d-none" id="editProfileImage" accept="image/jpeg,image/png,image/gif">
                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="checkbox" id="removeProfileImage">
                                    <label class="form-check-label small text-danger" for="removeProfileImage">Remove current image</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Intro Video</label>
                                <div class="upload-dropzone" id="introVideoDropzone" onclick="$('#editIntroVideo').click()">
                                    <div id="editIntroVideoPreview" class="upload-preview">
                                        <i class="fas fa-video fa-2x text-muted mb-2"></i>
                                        <p class="mb-1 fw-medium">Drag & drop or click to upload</p>
                                        <small class="text-muted">MP4, MOV, AVI, WMV (Max: 50MB)</small>
                                    </div>
                                </div>
                                <input type="file" class="d-none" id="editIntroVideo" accept="video/mp4,video/mov,video/avi,video/wmv">
                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="checkbox" id="removeIntroVideo">
                                    <label class="form-check-label small text-danger" for="removeIntroVideo">Remove current video</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Security Section -->
                    <div class="mb-4">
                        <h6 class="fw-bold text-primary border-bottom pb-2"><i class="fas fa-lock me-2"></i>Security</h6>
                        <div class="row g-3 mt-2">
                            <div class="col-md-6">
                                <label class="form-label">New Password</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="editPassword" placeholder="Leave blank to keep current">
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('editPassword', this)">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <small class="text-muted">Minimum 8 characters</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Confirm Password</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="editPasswordConfirmation" placeholder="Confirm new password">
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('editPasswordConfirmation', this)">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveUserBtn">
                    <i class="fas fa-save me-1"></i>Save Changes
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-body text-center p-4">
                <div class="d-flex justify-content-center align-items-center bg-danger bg-opacity-10 rounded-circle mx-auto mb-3" style="width: 48px; height: 48px;">
                    <i class="fas fa-trash text-danger"></i>
                </div>
                <h5 class="modal-title mb-3">Delete User</h5>
                <p class="text-muted mb-4">Are you sure you want to delete this user?</p>
                <div class="d-flex gap-2 justify-content-center">
                    <button id="confirmDelete" class="btn btn-danger">Delete</button>
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Restore Confirmation Modal -->
<div id="restoreModal" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-body text-center p-4">
                <div class="d-flex justify-content-center align-items-center bg-success bg-opacity-10 rounded-circle mx-auto mb-3" style="width: 48px; height: 48px;">
                    <i class="fas fa-undo text-success"></i>
                </div>
                <h5 class="modal-title mb-3">Restore User</h5>
                <p class="text-muted mb-4">Are you sure you want to restore this user?</p>
                <div class="d-flex gap-2 justify-content-center">
                    <button id="confirmRestore" class="btn btn-success">Restore</button>
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Block Confirmation Modal -->
<div id="blockModal" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-body text-center p-4">
                <div class="d-flex justify-content-center align-items-center rounded-circle mx-auto mb-3" style="width: 48px; height: 48px; background-color: rgba(31, 41, 55, 0.1);">
                    <i class="fas fa-ban" style="color: #1F2937;"></i>
                </div>
                <h5 class="modal-title mb-3">Block User</h5>
                <p class="text-muted mb-4">This user will not be able to login. Are you sure?</p>
                <div class="d-flex gap-2 justify-content-center">
                    <button id="confirmBlock" class="btn text-white" style="background-color: #1F2937;">Block</button>
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Unblock Confirmation Modal -->
<div id="unblockModal" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-body text-center p-4">
                <div class="d-flex justify-content-center align-items-center bg-success bg-opacity-10 rounded-circle mx-auto mb-3" style="width: 48px; height: 48px;">
                    <i class="fas fa-unlock text-success"></i>
                </div>
                <h5 class="modal-title mb-3">Unblock User</h5>
                <p class="text-muted mb-4">This user will be able to login again. Are you sure?</p>
                <div class="d-flex gap-2 justify-content-center">
                    <button id="confirmUnblock" class="btn btn-success">Unblock</button>
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<style>
/* Toast notifications */
.toast { background-color: #030303 !important; color: #ffffff !important; opacity: 0.9 !important; }
.toast-success { background-color: #51a351 !important; }
.toast-error { background-color: #bd362f !important; }
.toast-info { background-color: #2f96b4 !important; }
.toast-warning { background-color: #f89406 !important; }

/* DataTable adjustments */
#usersTable { width: 100% !important; }
#usersTable th, #usersTable td { white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
#usersTable td:nth-child(1) { white-space: normal !important; max-width: 220px; }
#usersTable td:nth-child(2) { white-space: normal !important; max-width: 200px; }
.table-responsive { overflow-x: hidden !important; }

/* Edit Modal fixes */
#editModal .modal-body, #viewModal .modal-body { 
    overflow-x: hidden; 
    overflow-y: auto;
    max-height: 70vh;
    scrollbar-width: none; /* Firefox */
    -ms-overflow-style: none; /* IE/Edge */
}
#editModal .modal-body::-webkit-scrollbar, #viewModal .modal-body::-webkit-scrollbar {
    display: none; /* Chrome/Safari/Opera */
}
#editModal .form-check { display: flex; align-items: center; gap: 8px; }
#editModal .form-check-input { margin: 0; flex-shrink: 0; }
#editModal .form-check-label { margin: 0; white-space: nowrap; }

/* Upload Dropzone */
.upload-dropzone {
    border: 2px dashed #ccc;
    border-radius: 12px;
    padding: 20px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
    background: #fafafa;
    min-height: 140px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.upload-dropzone:hover {
    border-color: #0d6efd;
    background: #f0f7ff;
}
.upload-dropzone.dragover {
    border-color: #0d6efd;
    background: #e8f4ff;
}
.upload-preview {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}
.upload-preview img {
    max-height: 100px;
    max-width: 100%;
    border-radius: 8px;
    object-fit: cover;
}
.upload-preview video {
    max-height: 100px;
    max-width: 100%;
    border-radius: 8px;
}

/* Edit modal dropdown fixes */
#editModal .form-select {
    color: #212529 !important;
    background-color: #fff !important;
}
#editModal .form-select option {
    color: #212529;
}
#editModal .form-select option:checked {
    background-color: #0d6efd;
    color: white;
}

/* Badge styles with high contrast */
.badge-status-active { background-color: #059669 !important; color: white !important; }
.badge-status-inactive { background-color: #D97706 !important; color: white !important; }
.badge-verified { background-color: #0D9488 !important; color: white !important; }
.badge-unverified { background-color: #DC2626 !important; color: white !important; }
.badge-blocked { background-color: #1F2937 !important; color: white !important; }
.badge-deleted { background-color: #7C3AED !important; color: white !important; }
.badge-premium { background-color: #F59E0B !important; color: #1F2937 !important; }

/* View modal tabs */
.view-tab { 
    padding: 0.5rem 1rem; 
    cursor: pointer; 
    border-bottom: 2px solid transparent; 
    transition: all 0.2s;
    color: #6c757d;
}
.view-tab:hover { color: #0d6efd; }
.view-tab.active { color: #0d6efd; border-bottom-color: #0d6efd; font-weight: 600; }
.view-tab-content { display: none; }
.view-tab-content.active { display: block; }

/* User details styling */
.detail-label { font-size: 0.75rem; color: #6c757d; text-transform: uppercase; margin-bottom: 0.25rem; }
.detail-value { font-size: 0.9rem; color: #212529; }
.section-card { background: #f8f9fa; border-radius: 8px; padding: 1rem; margin-bottom: 1rem; }
</style>

<script>
$(document).ready(function() {
    toastr.options = { closeButton: true, progressBar: true, positionClass: "toast-top-right", timeOut: 5000 };

    const API = {
        data: '{{ route("admin.user-management.index") }}',
        base: '{{ url("admin/user-management") }}'
    };
    const CSRF = '{{ csrf_token() }}';
    let currentUserId = null;

    // Initialize DataTable
    let table = $('#usersTable').DataTable({
        processing: true,
        serverSide: true,
        scrollX: false,
        autoWidth: false,
        ajax: {
            url: API.data,
            data: function(d) {
                d.format = 'json';
                d.verified_filter = $('#verifiedFilter').val();
                d.status_filter = $('#statusFilter').val();
                d.blocked_filter = $('#blockedFilter').val();
                d.deleted_filter = $('#deletedFilter').val();
                d.profile_filter = $('#profileFilter').val();
            }
        },
        columns: [
            {
                data: 'name', name: 'name', width: '220px',
                render: function(data, type, row) {
                    let img = row.ai_avatar_path 
                        ? `<img src="{{ url('storage') }}/${row.ai_avatar_path}" class="rounded-circle me-2" style="width:36px;height:36px;object-fit:cover;">` 
                        : row.profile_image 
                            ? `<img src="{{ url('storage') }}/${row.profile_image}" class="rounded-circle me-2" style="width:36px;height:36px;object-fit:cover;">`
                            : `<div class="rounded-circle me-2 bg-light d-inline-flex align-items-center justify-content-center" style="width:36px;height:36px;"><i class="fas fa-user text-muted small"></i></div>`;
                    let badges = '';
                    if (row.deleted_at) badges += '<span class="badge badge-deleted ms-1">Deleted</span>';
                    if (row.is_blocked) badges += '<span class="badge badge-blocked ms-1">Blocked</span>';
                    return `<div class="d-flex align-items-center">${img}<div><div class="fw-medium text-dark">${row.name}${badges}</div><small class="text-muted">@${row.username || ''}</small></div></div>`;
                }
            },
            {
                data: 'email', name: 'email', width: '200px',
                render: function(data) { return `<span class="text-muted small">${data}</span>`; }
            },
            {
                data: 'active', name: 'active', width: '90px',
                render: function(data, type, row) {
                    return data ? '<span class="badge badge-status-active">Active</span>' : '<span class="badge badge-status-inactive">Inactive</span>';
                }
            },
            {
                data: 'email_verified', name: 'email_verified', width: '110px',
                render: function(data, type, row) {
                    if (data) {
                        let date = row.email_verified_at ? new Date(row.email_verified_at).toLocaleDateString('en-US', {month:'short',day:'numeric'}) : '';
                        return `<span class="badge badge-verified"><i class="fas fa-check me-1"></i>Verified</span>${date ? '<br><small class="text-muted">'+date+'</small>' : ''}`;
                    }
                    return '<span class="badge badge-unverified"><i class="fas fa-times me-1"></i>Unverified</span>';
                }
            },
            {
                data: 'profile_completion', name: 'profile_completion', width: '120px',
                render: function(data, type, row) {
                    let percentage = data || 0;
                    let color = percentage === 100 ? '#10B981' : percentage >= 75 ? '#F59E0B' : percentage >= 50 ? '#3B82F6' : '#EF4444';
                    let bgColor = percentage === 100 ? '#D1FAE5' : percentage >= 75 ? '#FEF3C7' : percentage >= 50 ? '#DBEAFE' : '#FEE2E2';
                    return `
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1 me-2">
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar" role="progressbar" style="width: ${percentage}%; background-color: ${color};" aria-valuenow="${percentage}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                            <span class="badge" style="background-color: ${bgColor}; color: ${color}; font-weight: 600;">${percentage}%</span>
                        </div>
                    `;
                }
            },
            {
                data: 'created_at', name: 'created_at', width: '100px',
                render: function(data) {
                    let d = new Date(data);
                    return `<span class="text-muted">${d.toLocaleDateString('en-US',{month:'short',day:'numeric',year:'numeric'})}</span>`;
                }
            },
            {
                data: 'actions', name: 'actions', width: '150px', orderable: false, searchable: false,
                render: function(data, type, row) {
                    let btns = `<button onclick="viewUser(${row.id})" class="btn btn-info btn-sm me-1" title="View"><i class="fas fa-eye"></i></button>`;
                    if (row.deleted_at) {
                        btns += `<button onclick="restoreUser(${row.id})" class="btn btn-success btn-sm" title="Restore"><i class="fas fa-undo"></i></button>`;
                    } else {
                        btns += `<button onclick="editUser(${row.id})" class="btn btn-primary btn-sm me-1" title="Edit"><i class="fas fa-edit"></i></button>`;
                        if (row.is_blocked) {
                            btns += `<button onclick="unblockUser(${row.id})" class="btn btn-success btn-sm me-1" title="Unblock"><i class="fas fa-unlock"></i></button>`;
                        } else {
                            btns += `<button onclick="blockUser(${row.id})" class="btn btn-dark btn-sm me-1" title="Block"><i class="fas fa-ban"></i></button>`;
                        }
                        btns += `<button onclick="deleteUser(${row.id})" class="btn btn-danger btn-sm" title="Delete"><i class="fas fa-trash"></i></button>`;
                    }
                    return `<div class="btn-group">${btns}</div>`;
                }
            }
        ],
        pageLength: 10,
        lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
        order: [[5, 'desc']],
        language: { processing: '<div class="spinner-border spinner-border-sm text-primary" role="status"></div> Loading...' }
    });

    // Filter handlers
    $('#verifiedFilter, #statusFilter, #blockedFilter, #deletedFilter, #profileFilter').on('change', function() { table.ajax.reload(); });
    $('#refreshBtn').on('click', function() { table.ajax.reload(); updateStats(); toastr.info('Data refreshed'); });

    // View User with Tabs
    window.viewUser = function(id) {
        $('#viewModalBody').html('<div class="text-center py-5"><div class="spinner-border text-primary" role="status"></div></div>');
        $('#viewModal').modal('show');
        
        $.get(`${API.base}/${id}`, function(res) {
            if (res.success) {
                const u = res.user;
                let avatar = u.ai_avatar_path ? `{{ url('storage') }}/${u.ai_avatar_path}` : u.profile_image ? `{{ url('storage') }}/${u.profile_image}` : null;
                let avatarHtml = avatar ? `<img src="${avatar}" class="rounded-circle" style="width:80px;height:80px;object-fit:cover;">` : `<div class="rounded-circle bg-light d-flex align-items-center justify-content-center" style="width:80px;height:80px;"><i class="fas fa-user fa-2x text-muted"></i></div>`;
                
                let statusBadges = '';
                if (u.active) statusBadges += '<span class="badge badge-status-active me-1">Active</span>';
                else statusBadges += '<span class="badge badge-status-inactive me-1">Inactive</span>';
                if (u.email_verified) statusBadges += '<span class="badge badge-verified me-1">Verified</span>';
                else statusBadges += '<span class="badge badge-unverified me-1">Unverified</span>';
                if (u.is_blocked) statusBadges += '<span class="badge badge-blocked me-1">Blocked</span>';
                if (u.is_premium) statusBadges += '<span class="badge badge-premium me-1">Premium</span>';
                if (u.deleted_at) statusBadges += '<span class="badge badge-deleted me-1">Deleted</span>';

                let html = `
                    <div class="p-4 border-bottom bg-light">
                        <div class="d-flex align-items-center">
                            ${avatarHtml}
                            <div class="ms-3">
                                <h5 class="mb-1">${u.name}</h5>
                                <p class="text-muted mb-2">@${u.username} | ${u.email}</p>
                                <div>${statusBadges}</div>
                            </div>
                        </div>
                    </div>
                    <div class="border-bottom">
                        <div class="d-flex px-4">
                            <span class="view-tab active" data-tab="profile"><i class="fas fa-user me-1"></i>Profile</span>
                            <span class="view-tab" data-tab="media"><i class="fas fa-image me-1"></i>Media</span>
                            <span class="view-tab" data-tab="account"><i class="fas fa-cog me-1"></i>Account</span>
                        </div>
                    </div>
                    <div class="p-4">
                        <div id="tab-profile" class="view-tab-content active">
                            <div class="section-card">
                                <h6 class="fw-bold mb-3"><i class="fas fa-info-circle me-2 text-primary"></i>Basic Information</h6>
                                <div class="row g-3">
                                    <div class="col-md-4"><div class="detail-label">Age</div><div class="detail-value">${u.age || '-'}</div></div>
                                    <div class="col-md-4"><div class="detail-label">Gender Identity</div><div class="detail-value">${u.formatted_gender_identity || '-'}</div></div>
                                    <div class="col-md-4"><div class="detail-label">Sexual Orientation</div><div class="detail-value">${u.formatted_sexual_orientation || '-'}</div></div>
                                    <div class="col-md-4"><div class="detail-label">Interested In</div><div class="detail-value">${u.interested_in ? u.interested_in.charAt(0).toUpperCase() + u.interested_in.slice(1) : '-'}</div></div>
                                    <div class="col-md-4"><div class="detail-label">Looking For</div><div class="detail-value">${u.formatted_relationship_type || '-'}</div></div>
                                    <div class="col-md-4"><div class="detail-label">Age Preference</div><div class="detail-value">${u.age_min && u.age_max ? u.age_min + ' - ' + u.age_max : '-'}</div></div>
                                    <div class="col-md-4"><div class="detail-label">Phone</div><div class="detail-value">${u.phone || '-'}</div></div>
                                    <div class="col-12"><div class="detail-label">Bio</div><div class="detail-value">${u.bio || '-'}</div></div>
                                </div>
                            </div>
                            <div class="section-card">
                                <h6 class="fw-bold mb-3"><i class="fas fa-heart me-2 text-primary"></i>Relationship Context</h6>
                                <div class="row g-3">
                                    <div class="col-md-6"><div class="detail-label">Relationship Status</div><div class="detail-value">${u.formatted_relationship_status || '-'}</div></div>
                                    <div class="col-md-6"><div class="detail-label">Has Children</div><div class="detail-value">${u.formatted_children || '-'}</div></div>
                                </div>
                            </div>
                            <div class="section-card">
                                <h6 class="fw-bold mb-3"><i class="fas fa-map-marker-alt me-2 text-primary"></i>Lifestyle</h6>
                                <div class="row g-3">
                                    <div class="col-md-6"><div class="detail-label">Location</div><div class="detail-value">${u.city || '-'}${u.state ? ', ' + u.state : ''}</div></div>
                                    <div class="col-md-6"><div class="detail-label">Distance Preference</div><div class="detail-value">${u.formatted_distance || '-'}</div></div>
                                    <div class="col-md-6"><div class="detail-label">Occupation</div><div class="detail-value">${u.formatted_occupation || '-'}</div></div>
                                    <div class="col-md-6"><div class="detail-label">Education</div><div class="detail-value">${u.formatted_education || '-'}</div></div>
                                    <div class="col-md-6"><div class="detail-label">Physical Activity</div><div class="detail-value">${u.formatted_physical_activity || '-'}</div></div>
                                </div>
                            </div>
                            <div class="section-card">
                                <h6 class="fw-bold mb-3"><i class="fas fa-wine-glass me-2 text-primary"></i>Substance Use</h6>
                                <div class="row g-3">
                                    <div class="col-md-4"><div class="detail-label">Alcohol</div><div class="detail-value">${u.formatted_alcohol || '-'}</div></div>
                                    <div class="col-md-4"><div class="detail-label">Cannabis</div><div class="detail-value">${u.formatted_cannabis || '-'}</div></div>
                                    <div class="col-md-4"><div class="detail-label">Smoking/Vaping</div><div class="detail-value">${u.formatted_smoking || '-'}</div></div>
                                </div>
                            </div>
                        </div>
                        <div id="tab-media" class="view-tab-content">
                            <div class="section-card">
                                <h6 class="fw-bold mb-3"><i class="fas fa-camera me-2 text-primary"></i>Profile Image</h6>
                                ${u.profile_image ? `<img src="{{ url('storage') }}/${u.profile_image}" class="rounded" style="max-width:200px;max-height:200px;">` : '<p class="text-muted">No profile image uploaded</p>'}
                            </div>
                            <div class="section-card">
                                <h6 class="fw-bold mb-3"><i class="fas fa-video me-2 text-primary"></i>Intro Video</h6>
                                ${u.intro_video_path ? `<video controls style="max-width:100%;max-height:300px;"><source src="{{ url('storage') }}/${u.intro_video_path}" type="video/mp4"></video>` : '<p class="text-muted">No intro video uploaded</p>'}
                            </div>
                        </div>
                        <div id="tab-account" class="view-tab-content">
                            <div class="section-card">
                                <h6 class="fw-bold mb-3"><i class="fas fa-info-circle me-2 text-primary"></i>Account Details</h6>
                                <div class="row g-3">
                                    <div class="col-md-4"><div class="detail-label">Del Match Code</div><div class="detail-value">${u.del_match_code || '-'}</div></div>
                                    <div class="col-md-4"><div class="detail-label">Premium Status</div><div class="detail-value">${u.is_premium ? 'Premium' + (u.premium_expires_at ? ' (until ' + u.premium_expires_at + ')' : '') : 'Free'}</div></div>
                                    <div class="col-md-4"><div class="detail-label">Last Active</div><div class="detail-value">${u.last_active || '-'}</div></div>
                                    <div class="col-md-4"><div class="detail-label">Email Verified At</div><div class="detail-value">${u.email_verified_at || '-'}</div></div>
                                    <div class="col-md-4"><div class="detail-label">Blocked At</div><div class="detail-value">${u.blocked_at || '-'}</div></div>
                                    <div class="col-md-4"><div class="detail-label">Deleted At</div><div class="detail-value">${u.deleted_at || '-'}</div></div>
                                    <div class="col-md-4"><div class="detail-label">Registered</div><div class="detail-value">${u.created_at}</div></div>
                                    <div class="col-md-4"><div class="detail-label">Last Updated</div><div class="detail-value">${u.updated_at}</div></div>
                                </div>
                            </div>
                        </div>
                    </div>`;
                $('#viewModalBody').html(html);
                
                // Tab click handler
                $('.view-tab').on('click', function() {
                    $('.view-tab').removeClass('active');
                    $(this).addClass('active');
                    $('.view-tab-content').removeClass('active');
                    $(`#tab-${$(this).data('tab')}`).addClass('active');
                });
            } else {
                toastr.error(res.message || 'Failed to load user details');
                $('#viewModal').modal('hide');
            }
        }).fail(function() { toastr.error('Failed to load user details'); $('#viewModal').modal('hide'); });
    };

    // Edit User
    window.editUser = function(id) {
        $.get(`${API.base}/${id}/edit`, function(res) {
            if (res.success) {
                const u = res.user;
                $('#editUserId').val(u.id);
                $('#editName').val(u.name);
                $('#editUsername').val(u.username);
                $('#editEmail').val(u.email);
                $('#editPhone').val(u.phone || '');
                $('#editAge').val(u.age || '');
                $('#editBio').val(u.bio || '');
                $('#editGenderIdentity').val(u.gender_identity || '').trigger('change');
                $('#editSexualOrientation').val(u.sexual_orientation || '').trigger('change');
                $('#editInterestedIn').val(u.interested_in || '').trigger('change');
                $('#editRelationshipType').val(u.relationship_type || '').trigger('change');
                $('#editAgeMin').val(u.age_min || '');
                $('#editAgeMax').val(u.age_max || '');
                $('#editRelationshipStatus').val(u.relationship_status || '').trigger('change');
                $('#editHasChildren').val(u.has_children || '').trigger('change');
                $('#editCity').val(u.city || '');
                $('#editState').val(u.state || '');
                $('#editDistancePreference').val(u.distance_preference || '').trigger('change');
                $('#editOccupation').val(u.occupation || '');
                $('#editEducation').val(u.education || '').trigger('change');
                $('#editPhysicalActivity').val(u.physical_activity || '').trigger('change');
                $('#editAlcoholUse').val(u.alcohol_use || '').trigger('change');
                $('#editCannabisUse').val(u.cannabis_use || '').trigger('change');
                $('#editSmokingVaping').val(u.smoking_vaping || '').trigger('change');
                $('#editActive').prop('checked', u.active);
                $('#editEmailVerified').prop('checked', u.email_verified);
                $('#editIsBlocked').prop('checked', u.is_blocked);
                $('#editIsPremium').prop('checked', u.is_premium);
                
                // Populate media previews
                const storageUrl = '{{ url("storage") }}';
                if (u.profile_image) {
                    $('#editProfileImagePreview').html('<img src="' + storageUrl + '/' + u.profile_image + '"><p class="mb-0 mt-2 small text-success"><i class="fas fa-check-circle me-1"></i>Current image</p>');
                } else {
                    $('#editProfileImagePreview').html('<i class="fas fa-cloud-upload-alt fa-2x text-muted mb-2"></i><p class="mb-1 fw-medium">Drag & drop or click to upload</p><small class="text-muted">JPG, PNG, GIF (Max: 5MB)</small>');
                }
                if (u.intro_video_path) {
                    $('#editIntroVideoPreview').html('<video controls><source src="' + storageUrl + '/' + u.intro_video_path + '" type="video/mp4"></video><p class="mb-0 mt-2 small text-success"><i class="fas fa-check-circle me-1"></i>Current video</p>');
                } else {
                    $('#editIntroVideoPreview').html('<i class="fas fa-video fa-2x text-muted mb-2"></i><p class="mb-1 fw-medium">Drag & drop or click to upload</p><small class="text-muted">MP4, MOV, AVI, WMV (Max: 50MB)</small>');
                }
                
                // Reset file inputs and checkboxes
                $('#editProfileImage').val('');
                $('#editIntroVideo').val('');
                $('#removeProfileImage').prop('checked', false);
                $('#removeIntroVideo').prop('checked', false);
                $('#editPassword').val('');
                $('#editPasswordConfirmation').val('');
                
                $('#editModal').modal('show');
            } else { toastr.error(res.message || 'Failed to load user data'); }
        }).fail(function() { toastr.error('Failed to load user data'); });
    };

    // Save User
    $('#saveUserBtn').on('click', function() {
        const id = $('#editUserId').val();
        
        // Validate password match
        const password = $('#editPassword').val();
        const passwordConfirm = $('#editPasswordConfirmation').val();
        if (password && password !== passwordConfirm) {
            toastr.error('Passwords do not match');
            return;
        }
        if (password && password.length < 8) {
            toastr.error('Password must be at least 8 characters');
            return;
        }
        
        // Create FormData for file uploads
        const formData = new FormData();
        formData.append('_method', 'PUT');
        formData.append('name', $('#editName').val());
        formData.append('username', $('#editUsername').val());
        formData.append('email', $('#editEmail').val());
        formData.append('phone', $('#editPhone').val() || '');
        formData.append('age', $('#editAge').val() || '');
        formData.append('bio', $('#editBio').val() || '');
        formData.append('gender_identity', $('#editGenderIdentity').val() || '');
        formData.append('sexual_orientation', $('#editSexualOrientation').val() || '');
        formData.append('interested_in', $('#editInterestedIn').val() || '');
        formData.append('relationship_type', $('#editRelationshipType').val() || '');
        formData.append('age_min', $('#editAgeMin').val() || '');
        formData.append('age_max', $('#editAgeMax').val() || '');
        formData.append('relationship_status', $('#editRelationshipStatus').val() || '');
        formData.append('has_children', $('#editHasChildren').val() || '');
        formData.append('city', $('#editCity').val() || '');
        formData.append('state', $('#editState').val() || '');
        formData.append('distance_preference', $('#editDistancePreference').val() || '');
        formData.append('occupation', $('#editOccupation').val() || '');
        formData.append('education', $('#editEducation').val() || '');
        formData.append('physical_activity', $('#editPhysicalActivity').val() || '');
        formData.append('alcohol_use', $('#editAlcoholUse').val() || '');
        formData.append('cannabis_use', $('#editCannabisUse').val() || '');
        formData.append('smoking_vaping', $('#editSmokingVaping').val() || '');
        formData.append('active', $('#editActive').is(':checked') ? 1 : 0);
        formData.append('email_verified', $('#editEmailVerified').is(':checked') ? 1 : 0);
        formData.append('is_blocked', $('#editIsBlocked').is(':checked') ? 1 : 0);
        formData.append('is_premium', $('#editIsPremium').is(':checked') ? 1 : 0);
        
        // Password (optional)
        if (password) {
            formData.append('password', password);
            formData.append('password_confirmation', passwordConfirm);
        }
        
        // Media uploads
        const profileImage = $('#editProfileImage')[0].files[0];
        const introVideo = $('#editIntroVideo')[0].files[0];
        if (profileImage) formData.append('profile_image', profileImage);
        if (introVideo) formData.append('intro_video', introVideo);
        
        // Remove media flags
        formData.append('remove_profile_image', $('#removeProfileImage').is(':checked') ? 1 : 0);
        formData.append('remove_intro_video', $('#removeIntroVideo').is(':checked') ? 1 : 0);

        $.ajax({
            url: `${API.base}/${id}`,
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': CSRF },
            data: formData,
            processData: false,
            contentType: false,
            success: function(res) {
                if (res.success) {
                    toastr.success(res.message);
                    $('#editModal').modal('hide');
                    table.ajax.reload(null, false);
                    updateStats();
                } else {
                    if (res.errors) Object.values(res.errors).forEach(e => e.forEach(m => toastr.error(m)));
                    else toastr.error(res.message);
                }
            },
            error: function(xhr) {
                if (xhr.responseJSON?.errors) Object.values(xhr.responseJSON.errors).forEach(e => e.forEach(m => toastr.error(m)));
                else toastr.error('Failed to update user');
            }
        });
    });

    // Delete, Restore, Block, Unblock
    window.deleteUser = function(id) { currentUserId = id; $('#deleteModal').modal('show'); };
    window.restoreUser = function(id) { currentUserId = id; $('#restoreModal').modal('show'); };
    window.blockUser = function(id) { currentUserId = id; $('#blockModal').modal('show'); };
    window.unblockUser = function(id) { currentUserId = id; $('#unblockModal').modal('show'); };

    $('#confirmDelete').on('click', function() {
        if (currentUserId) {
            $.ajax({ url: `${API.base}/${currentUserId}`, method: 'DELETE', headers: { 'X-CSRF-TOKEN': CSRF },
                success: function(res) { res.success ? toastr.success(res.message) : toastr.error(res.message); table.ajax.reload(null, false); updateStats(); $('#deleteModal').modal('hide'); },
                error: function() { toastr.error('Failed to delete user'); $('#deleteModal').modal('hide'); }
            });
        }
    });

    $('#confirmRestore').on('click', function() {
        if (currentUserId) {
            $.ajax({ url: `${API.base}/${currentUserId}/restore`, method: 'POST', headers: { 'X-CSRF-TOKEN': CSRF },
                success: function(res) { res.success ? toastr.success(res.message) : toastr.error(res.message); table.ajax.reload(null, false); updateStats(); $('#restoreModal').modal('hide'); },
                error: function() { toastr.error('Failed to restore user'); $('#restoreModal').modal('hide'); }
            });
        }
    });

    $('#confirmBlock').on('click', function() {
        if (currentUserId) {
            $.ajax({ url: `${API.base}/${currentUserId}/block`, method: 'POST', headers: { 'X-CSRF-TOKEN': CSRF },
                success: function(res) { res.success ? toastr.success(res.message) : toastr.error(res.message); table.ajax.reload(null, false); updateStats(); $('#blockModal').modal('hide'); },
                error: function() { toastr.error('Failed to block user'); $('#blockModal').modal('hide'); }
            });
        }
    });

    $('#confirmUnblock').on('click', function() {
        if (currentUserId) {
            $.ajax({ url: `${API.base}/${currentUserId}/unblock`, method: 'POST', headers: { 'X-CSRF-TOKEN': CSRF },
                success: function(res) { res.success ? toastr.success(res.message) : toastr.error(res.message); table.ajax.reload(null, false); updateStats(); $('#unblockModal').modal('hide'); },
                error: function() { toastr.error('Failed to unblock user'); $('#unblockModal').modal('hide'); }
            });
        }
    });

    // Update Statistics
    function updateStats() {
        $.get(API.data + '?stats_only=1', function(data) {
            if (data.stats) {
                $('#totalUsers').text(new Intl.NumberFormat().format(data.stats.total));
                $('#verifiedUsers').text(new Intl.NumberFormat().format(data.stats.verified));
                $('#unverifiedUsers').text(new Intl.NumberFormat().format(data.stats.unverified));
                $('#activeUsers').text(new Intl.NumberFormat().format(data.stats.active));
                $('#inactiveUsers').text(new Intl.NumberFormat().format(data.stats.inactive));
                $('#blockedUsers').text(new Intl.NumberFormat().format(data.stats.blocked));
                $('#deletedUsers').text(new Intl.NumberFormat().format(data.stats.deleted));
            }
        });
    }
    
    // File preview on selection
    $('#editProfileImage').on('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#editProfileImagePreview').html('<img src="' + e.target.result + '">');
            };
            reader.readAsDataURL(file);
        }
    });
    
    $('#editIntroVideo').on('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#editIntroVideoPreview').html('<video controls><source src="' + e.target.result + '" type="' + file.type + '"></video>');
            };
            reader.readAsDataURL(file);
        }
    });
    
    // Drag and drop handlers
    ['profileImageDropzone', 'introVideoDropzone'].forEach(function(id) {
        const dropzone = document.getElementById(id);
        if (dropzone) {
            dropzone.addEventListener('dragover', function(e) {
                e.preventDefault();
                this.classList.add('dragover');
            });
            dropzone.addEventListener('dragleave', function(e) {
                e.preventDefault();
                this.classList.remove('dragover');
            });
            dropzone.addEventListener('drop', function(e) {
                e.preventDefault();
                this.classList.remove('dragover');
                const inputId = id === 'profileImageDropzone' ? 'editProfileImage' : 'editIntroVideo';
                const input = document.getElementById(inputId);
                if (e.dataTransfer.files.length > 0) {
                    input.files = e.dataTransfer.files;
                    $(input).trigger('change');
                }
            });
        }
    });
});

// Password toggle function
function togglePassword(inputId, btn) {
    const input = document.getElementById(inputId);
    const icon = btn.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}
</script>
@endsection
