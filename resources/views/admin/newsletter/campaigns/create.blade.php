@extends('admin.layouts.app')

@section('title', 'Create Campaign')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0 text-gray-800">Create New Campaign</h1>
            <a href="{{ route('admin.newsletter.campaigns.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back to Campaigns
            </a>
        </div>

        <div class="card shadow mb-4">
            <div class="card-body">
                <form id="campaign-form" action="{{ route('admin.newsletter.campaigns.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="subject" class="form-label">Email Subject</label>
                        <input type="text" class="form-control" id="subject" name="subject"
                            value="{{ old('subject') }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="content" class="form-label">Campaign Content</label>
                        <div id="editor" style="height: 400px;"></div>
                        <input type="hidden" name="content" id="content">
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <button type="submit" name="save_draft" class="btn btn-secondary">
                            <i class="bi bi-save"></i> Save as Draft
                        </button>
                        <button type="submit" name="send_now" value="1" class="btn btn-primary" id="btn-send-now">
                            <i class="bi bi-send-fill"></i> Send Now
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    <script>
        var quill = new Quill('#editor', {
            theme: 'snow',
            placeholder: 'Compose your campaign here...',
            modules: {
                toolbar: [
                    [{
                        'header': [1, 2, 3, false]
                    }],
                    ['bold', 'italic', 'underline', 'strike'],
                    ['blockquote', 'code-block'],
                    [{
                        'list': 'ordered'
                    }, {
                        'list': 'bullet'
                    }],
                    [{
                        'script': 'sub'
                    }, {
                        'script': 'super'
                    }],
                    [{
                        'indent': '-1'
                    }, {
                        'indent': '+1'
                    }],
                    [{
                        'align': []
                    }],
                    ['link', 'image'],
                    ['clean']
                ]
            }
        });

        // Set existing content (important for validation errors)
        quill.root.innerHTML = {!! json_encode(old('content')) !!};

        // Sync Quill content with hidden input on form submit
        const form = document.querySelector('#campaign-form');
        form.addEventListener('submit', function() {
            document.querySelector('#content').value = quill.root.innerHTML;
        });

        // SweetAlert2 for Send Now button
        const sendNowBtn = document.querySelector('#btn-send-now');
        if (sendNowBtn) {
            sendNowBtn.addEventListener('click', function(e) {
                e.preventDefault();

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You are about to send this campaign to all active subscribers. This cannot be undone.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, send it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Sync content manually as submit() doesn't trigger event
                        document.querySelector('#content').value = quill.root.innerHTML;

                        // Add hidden input for send_now value
                        const hiddenInput = document.createElement('input');
                        hiddenInput.type = 'hidden';
                        hiddenInput.name = 'send_now';
                        hiddenInput.value = '1';
                        form.appendChild(hiddenInput);

                        form.submit();
                    }
                });
            });
        }
    </script>
@endpush
