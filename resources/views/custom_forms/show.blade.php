@extends('layouts.app')

@section('title', $customForm->title)

@push('styles')
<style>
    .google-form-container {
        max-width: 770px;
        margin: 0 auto;
        padding-top: 2rem;
        padding-bottom: 4rem;
    }
    .form-header-card {
        border-top: 10px solid #673ab7; 
        border-radius: 8px;
        background: #fff;
        box-shadow: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24);
        margin-bottom: 1rem;
        padding: 2rem;
    }
    
    .form-header-card.with-bg {
        border-top: none;
    }

    .form-bg-image {
        width: 100%;
        height: 250px;
        object-fit: cover;
        border-radius: 8px 8px 0 0;
    }

    .question-card {
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24);
        margin-bottom: 1rem;
        padding: 1.5rem 2rem;
        transition: box-shadow 0.3s;
    }

    .question-card:focus-within {
        box-shadow: 0 3px 6px rgba(0,0,0,0.16), 0 3px 6px rgba(0,0,0,0.23);
        border-left: 6px solid #4285f4;
    }

    .question-title {
        font-size: 1.1rem;
        font-weight: 500;
        margin-bottom: 1rem;
        color: #202124;
    }

    .required-asterisk {
        color: #d93025;
    }

    .g-input {
        border: none;
        border-bottom: 1px solid #dadce0;
        border-radius: 0;
        padding-left: 0;
        background-color: transparent !important;
    }
    .g-input:focus {
        box-shadow: none;
        border-bottom: 2px solid #673ab7;
    }

    body {
        background-color: #f0ebf8; 
    }
    
    .form-page {
        display: none;
        animation: fadeIn 0.4s ease-in-out;
    }
    .form-page.active {
        display: block;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endpush

@section('content')
<div class="google-form-container">
    @if(session('form_success'))
        <div class="form-header-card text-center py-5">
            <h2 class="fw-bold">{{ $customForm->title }}</h2>
            <p class="text-muted mt-3">{{ session('form_success') }}</p>
            <a href="{{ url()->current() }}" class="text-decoration-none mt-4 d-inline-block">Submit another response</a>
        </div>
        <script>
            // Clear local storage upon receiving success session
            localStorage.removeItem('autosave_form_{{ $customForm->id }}');
        </script>
    @else
        <form action="{{ route('custom_forms.store', $customForm->slug) }}" method="POST" id="customFormEl">
            @csrf

            <!-- Form Header & Description (Always visible on first page) -->
            <div class="form-header-card {{ $customForm->bg_image ? 'with-bg p-0' : '' }}" id="mainFormHeader">
                @if($customForm->bg_image)
                    <img src="{{ asset('storage/' . $customForm->bg_image) }}" class="form-bg-image align-top" alt="Banner">
                @endif
                <div class="{{ $customForm->bg_image ? 'p-4' : '' }}">
                    <h1 class="display-5 fw-bold mb-3">{{ $customForm->title }}</h1>
                    @if($customForm->description)
                        <p class="fs-5 text-muted" style="white-space: pre-wrap;">{{ $customForm->description }}</p>
                    @endif
                    <hr>
                    <p class="text-danger small mb-0">* Indicates required question</p>
                    <p class="text-info small mb-0"><i class="bi bi-cloud-check"></i> Draft automatically saved</p>
                </div>
            </div>

            @php
                $pages = [];
                $currentPage = 0;
                $pages[0] = ['questions' => []];
                
                foreach($customForm->questions as $question) {
                    if ($question->type === 'section_break') {
                        $currentPage++;
                        $pages[$currentPage] = ['break' => $question, 'questions' => []];
                    } else {
                        $pages[$currentPage]['questions'][] = $question;
                    }
                }
            @endphp

            @foreach($pages as $index => $page)
                <div class="form-page {{ $index === 0 ? 'active' : '' }}" id="page-{{ $index }}">
                    
                    @if(isset($page['break']) && $page['break']->question_text)
                        <div class="form-header-card p-4 mt-4" style="border-top: 10px solid #4285f4;">
                            <h3 class="mb-0">{{ $page['break']->question_text }}</h3>
                        </div>
                    @endif

                    @foreach($page['questions'] as $question)
                        @php
                            $fieldName = "q_{$question->id}";
                        @endphp
                        <div class="question-card text-start">
                            <div class="question-title">
                                {{ $question->question_text }}
                                @if($question->is_required)
                                    <span class="required-asterisk">*</span>
                                @endif
                            </div>

                            @if($question->type === 'text')
                                <input type="text" class="form-control g-input w-50 save-input" name="{{ $fieldName }}" placeholder="Your answer" value="{{ old($fieldName) }}" {{ $question->is_required ? 'required' : '' }}>
                                @error($fieldName)
                                    <div class="text-danger mt-1 small">{{ $message }}</div>
                                @enderror

                            @elseif($question->type === 'textarea')
                                <textarea class="form-control g-input w-100 save-input" name="{{ $fieldName }}" rows="3" placeholder="Your answer" {{ $question->is_required ? 'required' : '' }}>{{ old($fieldName) }}</textarea>
                                @error($fieldName)
                                    <div class="text-danger mt-1 small">{{ $message }}</div>
                                @enderror

                            @elseif($question->type === 'radio')
                                @foreach($question->options as $optIndex => $option)
                                    <div class="form-check mb-2">
                                        <input class="form-check-input save-input" type="radio" name="{{ $fieldName }}" id="{{ $fieldName }}_{{ $optIndex }}" value="{{ $option }}" {{ old($fieldName) == $option ? 'checked' : '' }} {{ $question->is_required ? 'required' : '' }}>
                                        <label class="form-check-label" for="{{ $fieldName }}_{{ $optIndex }}">
                                            {{ $option }}
                                        </label>
                                    </div>
                                @endforeach
                                @error($fieldName)
                                    <div class="text-danger mt-1 small">{{ $message }}</div>
                                @enderror

                            @elseif($question->type === 'checkbox')
                                @foreach($question->options as $optIndex => $option)
                                    <div class="form-check mb-2">
                                        <input class="form-check-input save-input" type="checkbox" name="{{ $fieldName }}[]" id="{{ $fieldName }}_{{ $optIndex }}" value="{{ $option }}" {{ is_array(old($fieldName)) && in_array($option, old($fieldName)) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="{{ $fieldName }}_{{ $optIndex }}">
                                            {{ $option }}
                                        </label>
                                    </div>
                                @endforeach
                                @error($fieldName)
                                    <div class="text-danger mt-1 small">{{ $message }}</div>
                                @enderror

                            @elseif($question->type === 'select')
                                <select class="form-select g-input w-50 save-input" name="{{ $fieldName }}" {{ $question->is_required ? 'required' : '' }}>
                                    <option value="">Choose</option>
                                    @foreach($question->options as $option)
                                        <option value="{{ $option }}" {{ old($fieldName) == $option ? 'selected' : '' }}>{{ $option }}</option>
                                    @endforeach
                                </select>
                                @error($fieldName)
                                    <div class="text-danger mt-1 small">{{ $message }}</div>
                                @enderror
                            @endif
                        </div>
                    @endforeach

                    <div class="d-flex justify-content-between align-items-center mt-4">
                        @if($index > 0)
                            <button type="button" class="btn btn-secondary px-4 py-2" onclick="showPage({{ $index - 1 }})">Back</button>
                        @else
                            <div></div>
                        @endif

                        @if($index < count($pages) - 1)
                            <button type="button" class="btn btn-primary px-4 py-2" onclick="if(validatePage({{ $index }})) showPage({{ $index + 1 }});">Next</button>
                        @else
                            <button type="submit" class="btn text-white px-4 py-2" style="background-color: #673ab7; font-weight: 500;">Submit</button>
                        @endif
                    </div>
                </div>
            @endforeach
        </form>
    @endif
</div>

@push('scripts')
<script>
    const formId = "{{ $customForm->id }}";
    const storageKey = 'autosave_form_' + formId;
    
    // Multi-step logic
    function showPage(index) {
        document.querySelectorAll('.form-page').forEach(page => {
            page.classList.remove('active');
        });
        document.getElementById('page-' + index).classList.add('active');
        
        // Hide Main Header on subsequent pages to make it feel like real sections
        const mainHeader = document.getElementById('mainFormHeader');
        if(mainHeader) {
            mainHeader.style.display = index === 0 ? 'block' : 'none';
        }

        window.scrollTo(0, 0);
    }

    // Basic HTML5 validation before advancing page
    function validatePage(index) {
        const pageDOM = document.getElementById('page-' + index);
        const inputs = pageDOM.querySelectorAll('input[required], textarea[required], select[required]');
        
        for (let input of inputs) {
            // For radio buttons, check if any in the group is checked
            if(input.type === 'radio') {
                const radios = pageDOM.querySelectorAll(`input[name="${input.name}"]`);
                const isSelected = Array.from(radios).some(r => r.checked);
                if(!isSelected) {
                    input.reportValidity();
                    return false;
                }
            } else if (!input.checkValidity()) {
                input.reportValidity();
                return false;
            }
        }
        return true;
    }

    // Auto-save logic
    document.addEventListener('DOMContentLoaded', function() {
        const formEl = document.getElementById('customFormEl');
        if (!formEl) return;

        // Restore Data
        const savedData = JSON.parse(localStorage.getItem(storageKey) || '{}');
        Object.keys(savedData).forEach(name => {
            const inputs = formEl.querySelectorAll(`[name="${name}"]`);
            if (!inputs.length) return;

            const inputType = inputs[0].type;
            const value = savedData[name];

            if (inputType === 'radio' || inputType === 'checkbox') {
                inputs.forEach(input => {
                    if (Array.isArray(value)) { // check if value is array for checkbox []
                        input.checked = value.includes(input.value);
                    } else {
                        input.checked = (input.value === value);
                    }
                });
            } else {
                inputs[0].value = value;
            }
        });

        // Save Data on Input
        formEl.addEventListener('input', function(e) {
            if (e.target.classList.contains('save-input')) {
                const currentData = JSON.parse(localStorage.getItem(storageKey) || '{}');
                const name = e.target.name;
                
                if (e.target.type === 'checkbox') {
                    const checkboxes = formEl.querySelectorAll(`input[name="${name}"]:checked`);
                    currentData[name] = Array.from(checkboxes).map(cb => cb.value);
                } else if (e.target.type === 'radio') {
                    currentData[name] = e.target.value;
                } else {
                    currentData[name] = e.target.value;
                }
                
                localStorage.setItem(storageKey, JSON.stringify(currentData));
            }
        });

        // Clear Storage on Submit
        formEl.addEventListener('submit', function() {
            localStorage.removeItem(storageKey);
        });
    });
</script>
@endpush
@endsection

