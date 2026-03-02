@extends('admin.layout')

@section('title', 'Create Quote')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Create New Quote</h3>
                        <div class="card-tools">
                            <a href="{{ route('admin.quotes.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back to Quotes
                            </a>
                        </div>
                    </div>
                    
                    <form method="POST" action="{{ route('admin.quotes.store') }}">
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
                                        <label for="title" class="form-label">Quote Title</label>
                                        <input type="text" 
                                               class="form-control @error('title') is-invalid @enderror" 
                                               id="title" 
                                               name="title" 
                                               value="{{ old('title') }}" 
                                               placeholder="Enter a descriptive title for this quote"
                                               maxlength="100"
                                               required>
                                        @error('title')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">This title is for admin reference only and won't be displayed publicly.</div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="quote" class="form-label">Quote Text</label>
                                        <textarea class="form-control @error('quote') is-invalid @enderror" 
                                                  id="quote" 
                                                  name="quote" 
                                                  rows="4" 
                                                  placeholder="Enter the inspirational quote text..."
                                                  maxlength="1000"
                                                  required>{{ old('quote') }}</textarea>
                                        @error('quote')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">
                                            <span id="char-count">0</span>/1000 characters
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="sort_order" class="form-label">Display Order</label>
                                        <input type="number" 
                                               class="form-control @error('sort_order') is-invalid @enderror" 
                                               id="sort_order" 
                                               name="sort_order" 
                                               value="{{ old('sort_order', 1) }}" 
                                               min="0"
                                               required>
                                        @error('sort_order')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">Lower numbers appear first.</div>
                                    </div>

                                    <div class="mb-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" 
                                                   type="checkbox" 
                                                   id="is_active" 
                                                   name="is_active" 
                                                   {{ old('is_active', true) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_active">
                                                Active Quote
                                            </label>
                                        </div>
                                        <div class="form-text">Only active quotes will be displayed on the site.</div>
                                    </div>

                                    <!-- Preview -->
                                    <div class="card bg-light">
                                        <div class="card-header">
                                            <h6 class="card-title mb-0">Preview</h6>
                                        </div>
                                        <div class="card-body">
                                            <blockquote class="blockquote mb-0">
                                                <p id="quote-preview" class="fst-italic">
                                                    Enter quote text to see preview...
                                                </p>
                                            </blockquote>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Create Quote
                            </button>
                            <a href="{{ route('admin.quotes.index') }}" class="btn btn-secondary">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const quoteTextarea = document.getElementById('quote');
            const quotePreview = document.getElementById('quote-preview');
            const charCount = document.getElementById('char-count');

            function updatePreview() {
                const text = quoteTextarea.value.trim();
                quotePreview.textContent = text || 'Enter quote text to see preview...';
                charCount.textContent = text.length;
            }

            quoteTextarea.addEventListener('input', updatePreview);
            updatePreview(); // Initial update
        });
    </script>
@endsection
