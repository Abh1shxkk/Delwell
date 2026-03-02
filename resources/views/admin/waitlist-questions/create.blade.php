@extends('admin.layout')

@section('title', 'Create Waitlist Question')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Create New Question</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.waitlist-questions.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Questions
                        </a>
                    </div>
                </div>
                
                <form method="POST" action="{{ route('admin.waitlist-questions.store') }}">
                    @csrf
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="question_text" class="form-label">Question Text <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('question_text') is-invalid @enderror" 
                                              id="question_text" 
                                              name="question_text" 
                                              rows="3" 
                                              placeholder="Enter the question text..."
                                              maxlength="1000"
                                              required>{{ old('question_text') }}</textarea>
                                    @error('question_text')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">
                                        <span id="char-count">0</span>/1000 characters
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="field_name" class="form-label">Field Name <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('field_name') is-invalid @enderror" 
                                           id="field_name" 
                                           name="field_name" 
                                           value="{{ old('field_name') }}" 
                                           placeholder="e.g., draws-you, relationship-with-self"
                                           maxlength="255"
                                           required>
                                    @error('field_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Unique identifier for this question (use lowercase with hyphens).</div>
                                </div>

                                <div class="mb-3">
                                    <label for="question_type" class="form-label">Question Type <span class="text-danger">*</span></label>
                                    <select class="form-select @error('question_type') is-invalid @enderror" 
                                            id="question_type" 
                                            name="question_type" 
                                            required>
                                        <option value="">Select type...</option>
                                        <option value="text" {{ old('question_type') == 'text' ? 'selected' : '' }}>Text Input</option>
                                        <option value="textarea" {{ old('question_type') == 'textarea' ? 'selected' : '' }}>Textarea</option>
                                        <option value="radio" {{ old('question_type') == 'radio' ? 'selected' : '' }}>Radio Buttons</option>
                                        <option value="checkbox" {{ old('question_type') == 'checkbox' ? 'selected' : '' }}>Checkboxes</option>
                                        <option value="email" {{ old('question_type') == 'email' ? 'selected' : '' }}>Email Input</option>
                                    </select>
                                    @error('question_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3" id="optionsContainer" style="display: none;">
                                    <label class="form-label">Options</label>
                                    <div id="optionsList">
                                        <div class="input-group mb-2 option-item">
                                            <input type="text" class="form-control" name="options[]" placeholder="Option 1">
                                            <button type="button" class="btn btn-danger remove-option" disabled>
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-secondary" id="addOption">
                                        <i class="fas fa-plus"></i> Add Option
                                    </button>
                                    <div class="form-text">Add options for radio buttons or checkboxes.</div>
                                </div>

                                <div class="mb-3">
                                    <label for="placeholder" class="form-label">Placeholder Text</label>
                                    <input type="text" 
                                           class="form-control @error('placeholder') is-invalid @enderror" 
                                           id="placeholder" 
                                           name="placeholder" 
                                           value="{{ old('placeholder') }}" 
                                           placeholder="Enter placeholder text..."
                                           maxlength="255">
                                    @error('placeholder')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Optional placeholder text for input fields.</div>
                                </div>

                                <div class="mb-3">
                                    <label for="help_text" class="form-label">Help Text</label>
                                    <textarea class="form-control @error('help_text') is-invalid @enderror" 
                                              id="help_text" 
                                              name="help_text" 
                                              rows="2" 
                                              placeholder="Enter help text..."
                                              maxlength="500">{{ old('help_text') }}</textarea>
                                    @error('help_text')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Optional help text to guide users.</div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="sort_order" class="form-label">Display Order <span class="text-danger">*</span></label>
                                    <input type="number" 
                                           class="form-control @error('sort_order') is-invalid @enderror" 
                                           id="sort_order" 
                                           name="sort_order" 
                                           value="{{ old('sort_order', 0) }}" 
                                           min="0"
                                           required>
                                    @error('sort_order')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Lower numbers appear first.</div>
                                </div>

                                <div class="mb-3" id="maxSelectionsContainer" style="display: none;">
                                    <label for="max_selections" class="form-label">Max Selections</label>
                                    <input type="number" 
                                           class="form-control @error('max_selections') is-invalid @enderror" 
                                           id="max_selections" 
                                           name="max_selections" 
                                           value="{{ old('max_selections') }}" 
                                           min="1">
                                    @error('max_selections')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror>
                                    <div class="form-text">Maximum number of checkboxes that can be selected.</div>
                                </div>

                                <div class="mb-3">
                                    <label for="is_required" class="form-label">Required Question <span class="text-danger">*</span></label>
                                    <select class="form-select @error('is_required') is-invalid @enderror" 
                                            id="is_required" 
                                            name="is_required" 
                                            required>
                                        <option value="1" {{ old('is_required', '1') == '1' ? 'selected' : '' }}>Yes - Required</option>
                                        <option value="0" {{ old('is_required', '1') == '0' ? 'selected' : '' }}>No - Optional</option>
                                    </select>
                                    @error('is_required')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Users must answer this question.</div>
                                </div>

                                <div class="mb-3">
                                    <label for="is_active" class="form-label">Active Question <span class="text-danger">*</span></label>
                                    <select class="form-select @error('is_active') is-invalid @enderror" 
                                            id="is_active" 
                                            name="is_active" 
                                            required>
                                        <option value="1" {{ old('is_active', '1') == '1' ? 'selected' : '' }}>Yes - Active</option>
                                        <option value="0" {{ old('is_active', '1') == '0' ? 'selected' : '' }}>No - Inactive</option>
                                    </select>
                                    @error('is_active')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Only active questions will be displayed.</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Create Question
                        </button>
                        <a href="{{ route('admin.waitlist-questions.index') }}" class="btn btn-secondary">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/waitlist-questions-form.js') }}"></script>
@endsection
