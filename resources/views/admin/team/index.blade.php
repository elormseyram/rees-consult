@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Team Members</h1>
        <a href="{{ route('admin.team.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Add Team Member
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Order</th>
                            <th>Photo</th>
                            <th>Name</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($teamMembers as $member)
                        <tr>
                            <td>{{ $member->order }}</td>
                            <td>
                                @if($member->image)
                                    <img src="{{ asset('storage/' . $member->image) }}" alt="{{ $member->name }}" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
                                @else
                                    <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        <i class="bi bi-person text-white"></i>
                                    </div>
                                @endif
                            </td>
                            <td>{{ $member->name }}</td>
                            <td>{{ $member->role }}</td>
                            <td>
                                @if($member->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('admin.team.edit', $member) }}" class="btn btn-sm btn-info text-white">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.team.destroy', $member) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">No team members found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-end">
                {{ $teamMembers->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
