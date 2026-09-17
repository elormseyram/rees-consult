@extends('admin.layouts.app')

@push('styles')
<style>
    .award-thumb {
        width: 56px;
        height: 56px;
        object-fit: cover;
        border-radius: 8px;
        border: 1px solid #dee2e6;
    }
    .award-thumb-placeholder {
        width: 56px;
        height: 56px;
        background: #f1f3f8;
        border-radius: 8px;
        border: 1px solid #dee2e6;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #adb5bd;
        font-size: 1.4rem;
    }
    .video-thumb-wrap {
        width: 80px;
        height: 46px;
        flex-shrink: 0;
        border-radius: 6px;
        overflow: hidden;
        background: #0f1b2d;
        position: relative;
    }
    .video-thumb-wrap img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        opacity: .85;
    }
    .video-thumb-wrap .play-icon {
        position: absolute;
        inset: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 1.1rem;
        pointer-events: none;
    }
    /* Active tab highlight */
    .nav-tabs .nav-link.active { font-weight: 700; }
</style>
@endpush

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Awards</h1>
            <small class="text-muted">Manage accolades, award photos, and award videos in one place.</small>
        </div>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#bulkUploadModal">
                <i class="bi bi-cloud-upload"></i> Bulk Upload Images
            </button>
            <a href="{{ route('admin.awards.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i> Add Award
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- ── Tabs ── --}}
    <ul class="nav nav-tabs mb-4" id="awardsTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link {{ !request()->has('tab') || request('tab') === 'photos' ? 'active' : '' }}"
                    id="photos-tab" data-bs-toggle="tab" data-bs-target="#tab-photos"
                    type="button" role="tab">
                <i class="bi bi-images me-1"></i> Award Photos
                <span class="badge bg-secondary ms-1">{{ $awards->total() }}</span>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link {{ request('tab') === 'videos' ? 'active' : '' }}"
                    id="videos-tab" data-bs-toggle="tab" data-bs-target="#tab-videos"
                    type="button" role="tab" id="videos">
                <i class="bi bi-play-circle-fill me-1 text-danger"></i> Award Videos
                <span class="badge bg-danger ms-1">{{ $awardVideos->total() }}</span>
            </button>
        </li>
    </ul>

    <div class="tab-content" id="awardsTabContent">

        {{-- ══════════════════════════════════════
             TAB 1 — Award Photos (existing)
             ══════════════════════════════════════ --}}
        <div class="tab-pane fade {{ !request()->has('tab') || request('tab') === 'photos' ? 'show active' : '' }}"
             id="tab-photos" role="tabpanel">

            <!-- Photos Filter Card -->
            <div class="card shadow mb-4">
                <div class="card-body">
                    <form action="{{ route('admin.awards.index') }}" method="GET" class="row g-3 align-items-end">
                        <input type="hidden" name="tab" value="photos">
                        <div class="col-md-6">
                            <label for="search" class="form-label small fw-bold text-muted">Search Title, Awarded By, or Description</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                                <input type="text" class="form-control border-start-0 ps-0" id="search" name="search" value="{{ request('search') }}" placeholder="Search...">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label for="status" class="form-label small fw-bold text-muted">Status</label>
                            <select class="form-select" id="status" name="status">
                                <option value="">All Statuses</option>
                                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                        <div class="col-md-3 d-flex gap-2">
                            <button type="submit" class="btn btn-primary w-100"><i class="bi bi-funnel-fill me-1"></i> Filter</button>
                            @if(request()->anyFilled(['search', 'status']))
                                <a href="{{ route('admin.awards.index') }}" class="btn btn-outline-secondary w-100">Reset</a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            <!-- Photos Table -->
            <div class="card shadow mb-4">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th width="60" class="text-center">ID</th>
                                    <th width="70">Photo</th>
                                    <th>Title</th>
                                    <th>Awarded By</th>
                                    <th width="120">Date</th>
                                    <th width="80" class="text-center">Order</th>
                                    <th width="100" class="text-center">Status</th>
                                    <th width="120" class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($awards as $award)
                                <tr>
                                    <td class="text-center align-middle text-muted">{{ $award->id }}</td>
                                    <td class="align-middle">
                                        @if($award->photo)
                                            <img src="{{ asset('storage/' . $award->photo) }}"
                                                 alt="{{ $award->title }}" class="award-thumb">
                                        @else
                                            <div class="award-thumb-placeholder">
                                                <i class="bi bi-trophy"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="align-middle fw-semibold">{{ $award->title }}</td>
                                    <td class="align-middle text-muted">{{ $award->awarded_by ?? '—' }}</td>
                                    <td class="align-middle">{{ $award->formatted_date ?? '—' }}</td>
                                    <td class="text-center align-middle">
                                        <span class="badge bg-light text-dark border">{{ $award->order }}</span>
                                    </td>
                                    <td class="text-center align-middle">
                                        <form action="{{ route('admin.awards.toggle', $award) }}" method="POST" class="d-inline">
                                            @csrf @method('PATCH')
                                            <button type="submit"
                                                class="badge border-0 {{ $award->is_active ? 'bg-success' : 'bg-secondary' }}"
                                                title="{{ $award->is_active ? 'Click to deactivate' : 'Click to activate' }}">
                                                {{ $award->is_active ? 'Active' : 'Inactive' }}
                                            </button>
                                        </form>
                                    </td>
                                    <td class="text-center align-middle">
                                        <a href="{{ route('admin.awards.edit', $award) }}"
                                           class="btn btn-sm btn-outline-primary me-1" title="Edit">
                                            <i class="bi bi-pencil-fill"></i>
                                        </a>
                                        <form action="{{ route('admin.awards.destroy', $award) }}" method="POST" class="d-inline"
                                              onsubmit="return confirm('Delete this award?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                                <i class="bi bi-trash-fill"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-4">
                                        No awards yet. <a href="{{ route('admin.awards.create') }}">Add the first one.</a>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($awards->hasPages())
                <div class="card-footer d-flex justify-content-between align-items-center">
                    <div class="text-muted small">
                        Showing {{ $awards->firstItem() }} to {{ $awards->lastItem() }} of {{ $awards->total() }} awards
                    </div>
                    <div>{{ $awards->links() }}</div>
                </div>
                @endif
            </div>
        </div>

        {{-- ══════════════════════════════════════
             TAB 2 — Award Videos
             ══════════════════════════════════════ --}}
        <div class="tab-pane fade {{ request('tab') === 'videos' ? 'show active' : '' }}"
             id="tab-videos" role="tabpanel">

            <div class="alert alert-info d-flex align-items-start gap-2 py-2 mb-3" role="alert">
                <i class="bi bi-info-circle-fill mt-1 flex-shrink-0"></i>
                <div>
                    These are YouTube videos whose <strong>Video Category</strong> is set to <em>Award</em>.
                    They are pulled from the same testimonials pool — just set the category to <strong>Award</strong>
                    in <a href="{{ route('admin.testimonials.index') }}">Testimonials</a> to move a video here,
                    or edit it directly below.
                </div>
            </div>

            <!-- Videos Filter Card -->
            <div class="card shadow mb-4">
                <div class="card-body">
                    <form action="{{ route('admin.awards.index') }}" method="GET" class="row g-3 align-items-end">
                        <input type="hidden" name="tab" value="videos">
                        <div class="col-md-5">
                            <label for="video_search" class="form-label small fw-bold text-muted">Search Name or Role</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                                <input type="text" class="form-control border-start-0 ps-0" id="video_search" name="video_search"
                                       value="{{ request('video_search') }}" placeholder="Search...">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label for="video_status" class="form-label small fw-bold text-muted">Status</label>
                            <select class="form-select" id="video_status" name="video_status">
                                <option value="">All Statuses</option>
                                <option value="active" {{ request('video_status') === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ request('video_status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                        <div class="col-md-4 d-flex gap-2">
                            <button type="submit" class="btn btn-primary w-100"><i class="bi bi-funnel-fill me-1"></i> Filter</button>
                            @if(request()->anyFilled(['video_search', 'video_status']))
                                <a href="{{ route('admin.awards.index') }}?tab=videos" class="btn btn-outline-secondary w-100">Reset</a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            <!-- Videos Table -->
            <div class="card shadow mb-4">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th width="60" class="text-center">ID</th>
                                    <th width="90">Preview</th>
                                    <th>Name / Title</th>
                                    <th>Role</th>
                                    <th width="120" class="text-center">Showcase</th>
                                    <th width="100" class="text-center">Status</th>
                                    <th width="110" class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($awardVideos as $video)
                                <tr>
                                    <td class="text-center align-middle text-muted">{{ $video->id }}</td>
                                    <td class="align-middle">
                                        @php
                                            $vid = $video->youtube_video_id
                                                ?? (preg_match('/[A-Za-z0-9_-]{11}/', $video->youtube_video_url ?? '', $m) ? $m[0] : null);
                                        @endphp
                                        @if($vid)
                                            <div class="video-thumb-wrap">
                                                <img src="https://img.youtube.com/vi/{{ $vid }}/mqdefault.jpg"
                                                     alt="{{ $video->name }}" loading="lazy">
                                                <div class="play-icon"><i class="bi bi-play-circle-fill"></i></div>
                                            </div>
                                        @else
                                            <div class="video-thumb-wrap d-flex align-items-center justify-content-center">
                                                <i class="bi bi-play-circle text-white-50 fs-4"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="align-middle fw-semibold">{{ $video->name }}</td>
                                    <td class="align-middle text-muted small">{{ $video->role }}</td>
                                    <td class="text-center align-middle">
                                        @if($video->is_showcase)
                                            <span class="badge bg-primary" style="font-size:.7rem;">
                                                <i class="bi bi-play-circle-fill me-1"></i>Main Showcase
                                            </span>
                                        @else
                                            <span class="text-muted small">&mdash;</span>
                                        @endif
                                    </td>
                                    <td class="text-center align-middle">
                                        <form action="{{ route('admin.testimonials.toggle', $video) }}"
                                              method="POST" class="d-inline">
                                            @csrf @method('PATCH')
                                            <button type="submit"
                                                    class="btn btn-sm {{ $video->is_active ? 'btn-success' : 'btn-secondary' }} rounded-pill px-3 py-1"
                                                    style="font-size:.75rem;font-weight:600;">
                                                {{ $video->is_active ? 'Active' : 'Inactive' }}
                                            </button>
                                        </form>
                                    </td>
                                    <td class="text-center align-middle">
                                        <a href="{{ route('admin.testimonials.edit', $video) }}"
                                           class="btn btn-sm btn-info text-white" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('admin.testimonials.destroy', $video) }}"
                                              method="POST" class="d-inline"
                                              onsubmit="return confirm('Delete this award video?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">
                                        <i class="bi bi-play-circle fs-2 d-block mb-2"></i>
                                        No award videos yet. Edit a video testimonial and set its category to
                                        <strong>Award</strong> to move it here.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($awardVideos->hasPages())
                <div class="card-footer d-flex justify-content-between align-items-center">
                    <div class="text-muted small">
                        Showing {{ $awardVideos->firstItem() }} to {{ $awardVideos->lastItem() }} of {{ $awardVideos->total() }} award videos
                    </div>
                    <div>{{ $awardVideos->links() }}</div>
                </div>
                @endif
            </div>

        </div>{{-- end tab-videos --}}
    </div>{{-- end tab-content --}}
</div>

<!-- Bulk Upload Modal -->
<div class="modal fade" id="bulkUploadModal" tabindex="-1" aria-labelledby="bulkUploadModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.awards.bulk-upload') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="bulkUploadModalLabel">Bulk Upload Award Images</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="files" class="form-label fw-semibold">Select Award Images</label>
                        <input type="file" class="form-control" id="files" name="files[]" accept="image/*" multiple required>
                        <div class="form-text mt-2 text-muted">
                            <i class="bi bi-info-circle-fill me-1"></i> You can select multiple images at once.
                            The award title will be automatically generated from the filename.
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-cloud-upload me-1"></i> Upload Images</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Auto-activate the videos tab when URL has #videos hash or tab=videos param
document.addEventListener('DOMContentLoaded', function () {
    const params = new URLSearchParams(window.location.search);
    const hash   = window.location.hash;
    if (params.get('tab') === 'videos' || hash === '#videos') {
        const btn = document.getElementById('videos-tab');
        if (btn) bootstrap.Tab.getOrCreateInstance(btn).show();
    }
});
</script>
@endpush
