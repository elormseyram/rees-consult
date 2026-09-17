@extends('admin.layouts.app')

@section('title', 'Newsletter Campaigns')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0 text-gray-800">Newsletter Campaigns</h1>
            <a href="{{ route('admin.newsletter.campaigns.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i> Create Campaign
            </a>
        </div>



        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 fw-bold text-primary">Campaign History</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Subject</th>
                                <th>Status</th>
                                <th>Sent At</th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($campaigns as $campaign)
                                <tr>
                                    <td>{{ $campaign->subject }}</td>
                                    <td>
                                        @if ($campaign->sent_at)
                                            <span class="badge bg-success">Sent</span>
                                        @else
                                            <span class="badge bg-warning text-dark">Draft</span>
                                        @endif
                                    </td>
                                    <td>{{ $campaign->sent_at ? $campaign->sent_at->format('M d, Y h:i A') : '-' }}</td>
                                    <td>{{ $campaign->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            @if (!$campaign->sent_at)
                                                <form action="{{ route('admin.newsletter.campaigns.send', $campaign) }}"
                                                    method="POST" class="d-inline"
                                                    onsubmit="return confirm('Are you sure you want to send this campaign to all active subscribers?');">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-primary"
                                                        title="Send Now">
                                                        <i class="bi bi-send"></i>
                                                    </button>
                                                </form>
                                            @endif
                                            <form action="{{ route('admin.newsletter.campaigns.destroy', $campaign) }}"
                                                method="POST" class="d-inline ms-1"
                                                onsubmit="return confirm('Are you sure you want to delete this campaign?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">No campaigns found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $campaigns->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
