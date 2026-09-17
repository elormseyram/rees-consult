@extends('admin.layouts.app')

@section('content')
<div class="container-fluid" id="formBuilderApp">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">{{ isset($customForm) ? 'Edit Form' : 'Create Custom Form' }}</h1>
            <p class="text-muted mb-0 mt-1">Build your form visually — add questions, set types, and publish.</p>
        </div>
        <a href="{{ route('admin.custom_forms.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back to Forms
        </a>
    </div>

    <form id="customFormEl" action="{{ isset($customForm) ? route('admin.custom_forms.update', $customForm) : route('admin.custom_forms.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if(isset($customForm))
            @method('PUT')
        @endif

        <div class="row">
            <!-- Left Column: Form Settings -->
            <div class="col-lg-4 mb-4">
                <div class="card shadow-sm border-0" style="border-radius: 12px; overflow: hidden;">
                    <div class="card-header text-white" style="background: linear-gradient(135deg, #4e73df, #224abe);">
                        <h6 class="m-0 fw-bold"><i class="bi bi-gear-fill me-2"></i>Form Settings</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Form Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-lg" name="title" value="{{ old('title', $customForm->title ?? '') }}" placeholder="e.g. Student Registration" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Description</label>
                            <textarea class="form-control" name="description" rows="3" placeholder="A brief intro shown to users before they fill the form...">{{ old('description', $customForm->description ?? '') }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Banner Image <span class="text-muted small fw-normal">(Optional)</span></label>
                            @if(isset($customForm) && $customForm->bg_image)
                                <div class="mb-2 position-relative" style="border-radius: 8px; overflow: hidden;">
                                    <img src="{{ asset('storage/' . $customForm->bg_image) }}" class="w-100" style="max-height: 120px; object-fit: cover;">
                                </div>
                            @endif
                            <input type="file" class="form-control" name="bg_image" accept="image/*">
                        </div>

                        <div class="mb-3 p-3 rounded" style="background: #f0f4ff;">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_active" id="isActiveSwitch" value="1" {{ old('is_active', $customForm->is_active ?? true) ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold" for="isActiveSwitch">
                                    <i class="bi bi-globe2 me-1"></i> Publish (Visible to public)
                                </label>
                            </div>
                        </div>

                        <!-- Hidden Field for JSON Questions -->
                        <input type="hidden" name="questions_json" id="questionsJsonField">

                        <button type="submit" class="btn btn-success w-100 py-2 fw-bold fs-5 mt-2" id="saveFormBtn">
                            <i class="bi bi-check-circle-fill me-1"></i> {{ isset($customForm) ? 'Update Form' : 'Save & Publish' }}
                        </button>
                    </div>
                </div>

                {{-- Helper Tips --}}
                <div class="card border-0 shadow-sm mt-3" style="border-radius: 12px;">
                    <div class="card-body">
                        <h6 class="fw-bold text-muted"><i class="bi bi-lightbulb-fill text-warning"></i> Quick Tips</h6>
                        <ul class="small text-muted mb-0 ps-3">
                            <li class="mb-1">Use <strong>Short Answer</strong> for names, emails, etc.</li>
                            <li class="mb-1">Use <strong>Multiple Choice</strong> for single-select options.</li>
                            <li class="mb-1">Use <strong>Checkboxes</strong> when users can pick more than one.</li>
                            <li class="mb-1">Add a <strong>Section Break</strong> to split long forms into pages.</li>
                            <li>Toggle <strong>Required</strong> to make a question mandatory.</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Right Column: Builder -->
            <div class="col-lg-8 mb-4">
                <div class="card shadow-sm border-0" style="border-radius: 12px; overflow: hidden;">
                    <div class="card-header text-white d-flex justify-content-between align-items-center py-3" style="background: linear-gradient(135deg, #1cc88a, #13855c);">
                        <h6 class="m-0 fw-bold"><i class="bi bi-bricks me-2"></i>Questions Builder</h6>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-sm btn-light fw-semibold" onclick="addQuestion({type: 'section_break', question_text: '', is_required: false, options: ''})">
                                <i class="bi bi-layout-split"></i> Add Section Break
                            </button>
                            <button type="button" class="btn btn-sm btn-warning fw-semibold" onclick="addQuestion()">
                                <i class="bi bi-plus-circle-fill"></i> Add Question
                            </button>
                        </div>
                    </div>
                    <div class="card-body" id="questionsContainer" style="min-height: 400px; background: #f8f9fc;">
                        <!-- Questions will map here dynamically -->
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Template for a Single Question -->
<template id="questionTemplate">
    <div class="card mb-3 question-block" style="border-radius: 10px; border-left: 4px solid #4e73df; overflow: hidden;">
        <div class="card-header d-flex justify-content-between align-items-center bg-white py-2">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-grip-vertical text-muted drag-handle" style="cursor: move; font-size: 1.2rem;"></i>
                <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-1 fw-bold q-badge">Q<span class="q-index"></span></span>
            </div>
            <button type="button" class="btn btn-sm btn-outline-danger rounded-circle" style="width: 32px; height: 32px; padding: 0;" onclick="removeQuestion(this)" title="Remove this question">
                <i class="bi bi-x-lg" style="font-size: 0.75rem;"></i>
            </button>
        </div>
        <div class="card-body bg-white">
            <div class="row g-3">
                <div class="col-md-8">
                    <label class="form-label text-muted small fw-bold text-uppercase mb-1">Question Text</label>
                    <input type="text" class="form-control q-text" placeholder="e.g. What is your full name?" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label text-muted small fw-bold text-uppercase mb-1">Type</label>
                    <select class="form-select q-type" onchange="handleTypeChange(this)">
                        <option value="text">📝 Short Answer</option>
                        <option value="textarea">📄 Paragraph</option>
                        <option value="radio">🔘 Multiple Choice</option>
                        <option value="checkbox">☑️ Checkboxes</option>
                        <option value="select">📋 Dropdown</option>
                        <option value="section_break">✂️ Section Break</option>
                    </select>
                </div>
            </div>
            
            <div class="options-container mt-3" style="display: none;">
                <label class="form-label text-muted small fw-bold text-uppercase mb-1">Answer Options <span class="fw-normal">(one per line)</span></label>
                <textarea class="form-control q-options" rows="4" placeholder="Option 1&#10;Option 2&#10;Option 3"></textarea>
            </div>

            <hr class="my-3 settings-hr">
            <div class="d-flex justify-content-between align-items-center required-container">
                <span class="text-muted small">Mark as mandatory?</span>
                <div class="form-check form-switch">
                    <input class="form-check-input q-required" type="checkbox" style="width: 2.5em; height: 1.25em;">
                    <label class="form-check-label fw-semibold small">Required</label>
                </div>
            </div>
            <!-- Hidden ID field for updates -->
            <input type="hidden" class="q-id">
        </div>
    </div>
</template>

<script>
    let questionsData = [];

    // On Load, inject existing questions if editing
    document.addEventListener('DOMContentLoaded', () => {
        @if(isset($customForm) && $customForm->questions)
            const initialQuestions = @json($customForm->questions);
            initialQuestions.forEach(q => {
                let optionsString = "";
                if(q.options && Array.isArray(q.options)) {
                    optionsString = q.options.join('\n');
                } else if (q.options) {
                    optionsString = q.options;
                }
                
                addQuestion({
                    id: q.id,
                    question_text: q.question_text,
                    type: q.type,
                    is_required: q.is_required,
                    options: optionsString
                });
            });
        @endif

        // If no questions, add a default one
        if(document.querySelectorAll('.question-block').length === 0) {
            addQuestion();
        }

        // Form Submission Interceptor
        document.getElementById('customFormEl').addEventListener('submit', function(e) {
            serializeQuestions();
        });
    });

    function addQuestion(data = null) {
        const container = document.getElementById('questionsContainer');
        const template = document.getElementById('questionTemplate').content.cloneNode(true);
        const block = template.querySelector('.question-block');
        
        container.appendChild(block);
        
        // Populate if data exists
        if(data) {
            block.querySelector('.q-id').value = data.id || '';
            block.querySelector('.q-text').value = data.question_text || '';
            block.querySelector('.q-type').value = data.type || 'text';
            block.querySelector('.q-required').checked = data.is_required === 1 || data.is_required === true;
            block.querySelector('.q-options').value = data.options || '';
            
            handleTypeChange(block.querySelector('.q-type'));
        }

        // Update visual styling for section breaks
        if(data && data.type === 'section_break') {
            block.style.borderLeftColor = '#e74a3b';
            block.querySelector('.q-badge').classList.remove('bg-primary-subtle', 'text-primary');
            block.querySelector('.q-badge').classList.add('bg-danger-subtle', 'text-danger');
        }

        updateQuestionIndices();
        
        // Smooth scroll to newly added question
        block.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    function removeQuestion(btn) {
        if(document.querySelectorAll('.question-block').length <= 1) {
            Toast.fire({ icon: 'warning', title: 'You must have at least one question.' });
            return;
        }
        const block = btn.closest('.question-block');
        block.style.transition = 'opacity 0.3s, transform 0.3s';
        block.style.opacity = '0';
        block.style.transform = 'translateX(50px)';
        setTimeout(() => {
            block.remove();
            updateQuestionIndices();
        }, 300);
    }

    function handleTypeChange(selectEl) {
        const type = selectEl.value;
        const cardBody = selectEl.closest('.card-body');
        const optionsContainer = cardBody.querySelector('.options-container');
        const requiredContainer = cardBody.querySelector('.required-container');
        const settingsHr = cardBody.querySelector('.settings-hr');
        const qText = cardBody.querySelector('.q-text');
        const questionBlock = selectEl.closest('.question-block');
        const badge = questionBlock.querySelector('.q-badge');
        
        const isChoiceBased = ['radio', 'checkbox', 'select'].includes(type);
        const isSectionBreak = type === 'section_break';
        
        optionsContainer.style.display = isChoiceBased ? 'block' : 'none';

        if (isSectionBreak) {
            requiredContainer.style.display = 'none';
            if(settingsHr) settingsHr.style.display = 'none';
            qText.placeholder = "Section Title (Optional)";
            qText.removeAttribute('required');
            questionBlock.style.borderLeftColor = '#e74a3b';
            badge.classList.remove('bg-primary-subtle', 'text-primary');
            badge.classList.add('bg-danger-subtle', 'text-danger');
        } else {
            requiredContainer.style.display = 'flex';
            if(settingsHr) settingsHr.style.display = 'block';
            qText.placeholder = "e.g. What is your full name?";
            qText.setAttribute('required', 'required');
            questionBlock.style.borderLeftColor = '#4e73df';
            badge.classList.remove('bg-danger-subtle', 'text-danger');
            badge.classList.add('bg-primary-subtle', 'text-primary');
        }
    }

    function updateQuestionIndices() {
        const blocks = document.querySelectorAll('.question-block');
        blocks.forEach((block, index) => {
            block.querySelector('.q-index').innerText = (index + 1);
        });
    }

    function serializeQuestions() {
        const blocks = document.querySelectorAll('.question-block');
        const serialized = [];
        
        blocks.forEach((block) => {
            const id = block.querySelector('.q-id').value;
            const text = block.querySelector('.q-text').value;
            const type = block.querySelector('.q-type').value;
            const required = block.querySelector('.q-required').checked;
            const options = block.querySelector('.q-options').value;
            
            // Only push valid questions or section breaks
            if(text.trim() !== '' || type === 'section_break') {
                serialized.push({
                    id: id ? parseInt(id) : null,
                    question_text: text,
                    type: type,
                    is_required: required,
                    options: options
                });
            }
        });
        
        document.getElementById('questionsJsonField').value = JSON.stringify(serialized);
    }
</script>
@endsection
