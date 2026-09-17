{{--
    Follow-up notes timeline. Any staff member can add a note; notes can be
    removed by their author or by an admin.
--}}
<div class="card border-0 mb-4">
    <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
        <h5 class="fw-bold mb-0"><i class="bi bi-journal-text me-2" style="color:#F8A706;"></i>Notes</h5>
        <span class="badge bg-light text-dark">{{ $lead->noteEntries->count() }}</span>
    </div>

    <div class="card-body">
        <form action="{{ route('admin.leads.notes.store', $lead->id) }}" method="POST" class="mb-4">
            @csrf
            <label for="note-body" class="form-label small fw-semibold">Add a note</label>
            <textarea id="note-body"
                      name="body"
                      rows="3"
                      maxlength="5000"
                      required
                      class="form-control @error('body') is-invalid @enderror"
                      placeholder="What was discussed? Objections, next steps, agreed follow-up date…">{{ old('body') }}</textarea>
            @error('body')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <div class="d-flex justify-content-end mt-2">
                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-lg me-1"></i> Add note
                </button>
            </div>
        </form>

        @forelse ($lead->noteEntries as $note)
            <div class="d-flex gap-3 pb-3 mb-3 {{ ! $loop->last ? 'border-bottom' : '' }}">
                <div class="flex-shrink-0">
                    <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold text-white"
                         style="width:38px; height:38px; background:#0b2545; font-size:.8rem;">
                        {{ strtoupper(substr($note->author_label, 0, 2)) }}
                    </div>
                </div>
                <div class="flex-grow-1">
                    <div class="d-flex align-items-center justify-content-between gap-2">
                        <div>
                            <span class="fw-semibold">{{ $note->author_label }}</span>
                            <span class="text-muted small ms-1"
                                  title="{{ $note->created_at->format('M d, Y H:i') }}">
                                {{ $note->created_at->diffForHumans() }}
                            </span>
                        </div>
                        @if (auth()->user()->isAdmin() || $note->user_id === auth()->id())
                            <form action="{{ route('admin.leads.notes.destroy', [$lead->id, $note->id]) }}"
                                  method="POST"
                                  onsubmit="return confirm('Delete this note? This cannot be undone.');">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-link btn-sm text-muted p-0" title="Delete note">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        @endif
                    </div>
                    <div class="mt-1" style="white-space:pre-wrap;">{{ $note->body }}</div>
                </div>
            </div>
        @empty
            <p class="text-muted small mb-0">No notes yet. Add the first one after your next call.</p>
        @endforelse
    </div>
</div>
