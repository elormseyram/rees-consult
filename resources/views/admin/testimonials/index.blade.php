@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Testimonials</h1>
            <small class="text-muted">
                Manage written and video testimonials. Award videos are managed under
                <a href="{{ route('admin.awards.index') }}#videos">Awards &rarr; Award Videos</a>.
            </small>
        </div>
        <a href="{{ route('admin.testimonials.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Add New Testimonial
        </a>
    </div>

    <!-- Filters Card -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('admin.testimonials.index') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label for="search" class="form-label small fw-bold text-muted">Search Name, Role, or Message</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" class="form-control border-start-0 ps-0" id="search" name="search" value="{{ request('search') }}" placeholder="Search...">
                    </div>
                </div>
                <div class="col-md-3">
                    <label for="category" class="form-label small fw-bold text-muted">Category</label>
                    <select class="form-select" id="category" name="category">
                        <option value="">All Categories</option>
                        <option value="testimonial" {{ request('category') === 'testimonial' ? 'selected' : '' }}>Testimonial Video</option>
                        <option value="other" {{ request('category') === 'other' ? 'selected' : '' }}>Other Video</option>
                        <option value="short" {{ request('category') === 'short' ? 'selected' : '' }}>📱 YouTube Short</option>
                        <option value="written" {{ request('category') === 'written' ? 'selected' : '' }}>Written Testimonial</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="status" class="form-label small fw-bold text-muted">Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">All Statuses</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100"><i class="bi bi-funnel-fill me-1"></i> Filter</button>
                    @if(request()->anyFilled(['search', 'category', 'status']))
                        <a href="{{ route('admin.testimonials.index') }}" class="btn btn-outline-secondary w-100" title="Reset Filters">Reset</a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-hover mb-0" id="testimonials-table">
                    <thead class="table-light">
                        <tr>
                            <th width="60" class="text-center">ID</th>
                            <th>Name</th>
                            <th>Role</th>
                            <th width="110">Rating</th>
                            <th width="80" class="text-center">Order</th>
                            <th width="160" class="text-center">Video / Showcase</th>
                            <th width="110" class="text-center">Status</th>
                            <th width="110" class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($testimonials as $testimonial)
                        <tr>
                            <td class="text-center align-middle text-muted">{{ $testimonial->id }}</td>
                            <td class="align-middle font-weight-bold">{{ $testimonial->name }}</td>
                            <td class="align-middle">{{ $testimonial->role }}</td>
                            <td class="align-middle">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="bi bi-star{{ $i <= $testimonial->rating ? '-fill' : '' }} text-warning"></i>
                                @endfor
                            </td>
                            <td class="text-center align-middle">
                                <span class="badge bg-light text-dark border">{{ $testimonial->order }}</span>
                            </td>
                            <td class="text-center align-middle">
                                @if($testimonial->youtube_video_url)
                                    @if($testimonial->is_short)
                                        <span class="badge bg-warning text-dark mb-1" style="font-size:0.7rem;">
                                            📱 Short
                                        </span>
                                    @else
                                        <span class="badge bg-info text-white mb-1" style="font-size:0.7rem;">
                                            {{ ucfirst($testimonial->video_category ?: 'testimonial') }}
                                        </span>
                                    @endif
                                    @if($testimonial->is_showcase)
                                        <span class="badge bg-primary text-white d-block" style="font-size:0.7rem;">
                                            <i class="bi bi-play-circle-fill me-1"></i> Main Showcase
                                        </span>
                                    @endif
                                @else
                                    <span class="text-muted small">&mdash;</span>
                                @endif
                            </td>
                            <td class="text-center align-middle">
                                <form action="{{ route('admin.testimonials.toggle', $testimonial) }}"
                                      method="POST" class="d-inline" title="Click to toggle status">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                            class="btn btn-sm {{ $testimonial->is_active ? 'btn-success' : 'btn-secondary' }} rounded-pill px-3 py-1"
                                            style="font-size:0.75rem;font-weight:600">
                                        {{ $testimonial->is_active ? 'Active' : 'Inactive' }}
                                    </button>
                                </form>
                            </td>
                            <td class="text-center align-middle">
                                <div class="btn-group">
                                    <a href="{{ route('admin.testimonials.edit', $testimonial) }}"
                                       class="btn btn-sm btn-info text-white" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.testimonials.destroy', $testimonial) }}"
                                          method="POST" class="d-inline"
                                          onsubmit="return confirm('Delete this testimonial?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="bi bi-chat-square-quote fs-2 d-block mb-2"></i>
                                No testimonials found. Add your first one!
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($testimonials->hasPages())
        <div class="card-footer d-flex justify-content-between align-items-center">
            <div class="text-muted small">
                Showing {{ $testimonials->firstItem() }} to {{ $testimonials->lastItem() }} of {{ $testimonials->total() }} testimonials
            </div>
            <div>
                {{ $testimonials->links() }}
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
