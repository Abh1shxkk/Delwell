@if($results->count() > 0)
    @foreach($results as $user)
        <div class="col-xl-4 col-lg-6 col-md-6">
            <div class="card h-100 shadow-sm border-0 result-card" style="transition: all 0.3s ease;">
                <div class="card-body p-4">
                    <!-- User Header -->
                    <div class="d-flex align-items-center mb-3">
                        @php
                            $avatarUrl = null;
                            if (!empty($user->ai_avatar_path)) {
                                $avatarUrl = url('storage/' . $user->ai_avatar_path);
                            } elseif (!empty($user->profile_image)) {
                                if (str_starts_with($user->profile_image, 'http')) {
                                    $avatarUrl = $user->profile_image;
                                } else {
                                    $avatarUrl = url('storage/' . $user->profile_image);
                                }
                            }
                        @endphp
                        
                        @if($avatarUrl)
                            <img src="{{ $avatarUrl }}" 
                                 class="rounded-circle me-3" 
                                 style="width: 56px; height: 56px; object-fit: cover;" 
                                 alt="{{ $user->name }}"
                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <div class="rounded-circle me-3 overflow-hidden" style="display: none; width: 56px; height: 56px; background: #f0f0f0;">
                                <x-profile-placeholder color="#A3B18A" />
                            </div>
                        @else
                            <div class="rounded-circle me-3 overflow-hidden" style="width: 56px; height: 56px; background: #f0f0f0;">
                                <x-profile-placeholder color="#A3B18A" />
                            </div>
                        @endif
                        <div class="flex-grow-1">
                            <h5 class="fw-bold text-dark mb-0">{{ $user->name }}</h5>
                            <small class="text-muted">{{ $user->username ? '@' . $user->username : $user->email }}</small>
                        </div>
                    </div>

                    <!-- Del Match Code -->
                    @if($user->del_match_code)
                        <div class="mb-3">
                            <div class="d-flex align-items-center">
                                <span class="badge rounded-pill px-3 py-2" style="background-color: #6366f1; color: #fff;">
                                    <i class="fas fa-fingerprint me-1"></i>
                                    Del Match: {{ $user->del_match_code }}
                                </span>
                            </div>
                        </div>
                    @endif

                    <!-- Quiz Results Preview -->
                    <div class="mb-3">
                        <div class="small fw-medium text-muted text-uppercase mb-2">Quiz Results</div>
                        @php
                            $quizResults = $user->quiz_results;
                            $resultCount = 0;
                            
                            if (!empty($quizResults)) {
                                if (is_array($quizResults)) {
                                    $resultCount = count($quizResults);
                                } elseif (is_object($quizResults)) {
                                    $resultCount = count((array) $quizResults);
                                } elseif (is_string($quizResults)) {
                                    $decoded = json_decode($quizResults, true);
                                    if (is_array($decoded)) {
                                        $resultCount = count($decoded);
                                    }
                                }
                            }
                        @endphp
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge rounded-pill px-2 py-1" style="background-color: #10b981; color: #fff;">
                                <i class="fas fa-clipboard-check me-1"></i>
                                {{ $resultCount }} Response{{ $resultCount != 1 ? 's' : '' }}
                            </span>
                        </div>
                    </div>

                    <!-- Timestamp -->
                    <div class="d-flex align-items-center text-muted small mb-3">
                        <i class="fas fa-clock me-2"></i>
                        <span>Updated {{ $user->updated_at->diffForHumans() }}</span>
                    </div>

                    <!-- Actions -->
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-primary btn-sm flex-grow-1" onclick="viewResults({{ $user->id }})" style="font-size: 0.8rem; padding: 6px 10px;">
                            <i class="fas fa-eye me-1"></i>View Details
                        </button>
                        @php
                            $historyData = $user->quiz_results_history;
                            $hasHistory = false;
                            if (!empty($historyData)) {
                                $decoded = is_string($historyData) ? json_decode($historyData, true) : $historyData;
                                $hasHistory = is_array($decoded) && count($decoded) > 0;
                            }
                        @endphp
                        @if($hasHistory)
                            <button type="button" class="btn btn-outline-warning btn-sm" onclick="viewResultsHistory({{ $user->id }})" style="font-size: 0.8rem; padding: 6px 10px;" title="View past quiz submissions">
                                <i class="fas fa-history me-1"></i>History
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@else
    <div class="col-12">
        <div class="text-center py-5">
            <div class="mb-4">
                <i class="fas fa-search fa-4x text-muted opacity-50"></i>
            </div>
            <h4 class="text-muted mb-2">No Results Found</h4>
            <p class="text-muted">No users match your search criteria. Try a different search term.</p>
        </div>
    </div>
@endif
