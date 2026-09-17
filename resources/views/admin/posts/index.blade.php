@extends('admin.layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0 text-gray-800">Blog Posts</h1>
            <a href="{{ route('admin.posts.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i> Add New Post
            </a>
        </div>

        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Title</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($posts as $post)
                                <tr>
                                    <td style="width: 100px;">
                                        @if ($post->image)
                                            <img src="{{ $post->image_url }}" alt="{{ $post->title }}"
                                                class="img-fluid rounded" style="max-height: 50px;">
                                        @else
                                            <span class="text-muted">No Image</span>
                                        @endif
                                    </td>
                                    <td>{{ $post->title }}</td>
                                    <td>
                                        @if ($post->published_at && $post->published_at <= now())
                                            <span class="badge bg-success">Published</span>
                                        @else
                                            <span class="badge bg-secondary">Draft</span>
                                        @endif
                                    </td>
                                    <td>{{ $post->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('admin.posts.edit', $post) }}"
                                                class="btn btn-sm btn-info text-white" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('admin.posts.destroy', $post) }}" method="POST"
                                                class="d-inline"
                                                onsubmit="return confirm('Are you sure you want to delete this post?');">
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
                                    <td colspan="5" class="text-center py-4">No posts found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-end">
                    {{ $posts->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
