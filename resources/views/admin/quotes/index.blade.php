@extends('admin.layout')

@section('title', 'Manage Quotes')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title">Admin Quotes</h3>
                        <a href="{{ route('admin.quotes.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Add New Quote
                        </a>
                    </div>
                    
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if($quotes->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped table-hover table-bordered">
                                    <thead class="table-dark">
                                        <tr>
                                            <th style="width: 8%;">Order</th>
                                            <th style="width: 22%;">Title</th>
                                            <th style="width: 40%;">Quote</th>
                                            <th style="width: 10%;">Status</th>
                                            <th style="width: 12%;">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($quotes as $quote)
                                            <tr>
                                                <td>
                                                    <span class="badge bg-secondary">{{ $quote->sort_order }}</span>
                                                </td>
                                                <td class="title-cell">
                                                    <strong>{{ $quote->title }}</strong>
                                                </td>
                                                <td>
                                                    <div class="quote-preview">
                                                        <span class="text-muted fst-italic">
                                                            "{{ Str::limit($quote->quote, 80) }}..."
                                                        </span>
                                                    </div>
                                                </td>
                                                <td>
                                                    @if($quote->is_active)
                                                        <span class="badge bg-success">Active</span>
                                                    @else
                                                        <span class="badge bg-secondary">Inactive</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="d-flex gap-1">
                                                        <a href="{{ route('admin.quotes.edit', $quote) }}" 
                                                           class="btn btn-sm btn-primary" 
                                                           title="Edit Quote">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        
                                                        <form method="POST" 
                                                              action="{{ route('admin.quotes.toggle-active', $quote) }}" 
                                                              class="d-inline">
                                                            @csrf
                                                            <button type="submit" 
                                                                    class="btn btn-sm btn-{{ $quote->is_active ? 'warning' : 'success' }}"
                                                                    title="{{ $quote->is_active ? 'Deactivate' : 'Activate' }} Quote">
                                                                <i class="fas fa-{{ $quote->is_active ? 'eye-slash' : 'eye' }}"></i>
                                                            </button>
                                                        </form>
                                                        
                                                        <form method="POST" 
                                                              action="{{ route('admin.quotes.destroy', $quote) }}" 
                                                              class="d-inline"
                                                              onsubmit="return confirm('Are you sure you want to delete this quote?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" 
                                                                    class="btn btn-sm btn-danger"
                                                                    title="Delete Quote">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-quote-left fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">No quotes found</h5>
                                <p class="text-muted">Create your first inspirational quote to display on the site.</p>
                                <a href="{{ route('admin.quotes.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Add First Quote
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .table {
            table-layout: auto;
            width: 100%;
        }
        
        .title-cell {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            max-width: 200px;
        }
        
        .quote-preview {
            line-height: 1.3;
            word-wrap: break-word;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        
        .table td {
            vertical-align: middle;
            padding: 12px 8px;
        }
        
        .table th {
            vertical-align: middle;
            padding: 12px 8px;
            font-weight: 600;
            font-size: 0.9rem;
        }
        
        .d-flex.gap-1 {
            gap: 0.25rem !important;
        }
        
        .btn-sm {
            padding: 0.25rem 0.4rem;
            font-size: 0.8rem;
        }
        
        .badge {
            font-size: 0.75em;
        }
        
        .table-responsive {
            overflow-x: auto;
        }
    </style>
@endsection
