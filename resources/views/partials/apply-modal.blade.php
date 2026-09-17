<!-- ===================== Apply Now — Multi-step Qualification Modal ===================== -->
<div class="modal fade apply-modal" id="applyModal" tabindex="-1" aria-labelledby="applyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content apply-modal-content">

            <!-- Header / progress -->
            <div class="apply-modal-header">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h5 class="apply-modal-title" id="applyModalLabel">Start Your Application</h5>
                        <p class="apply-modal-subtitle" id="applyStepHint">Tell us what you want to achieve.</p>
                    </div>
                    <button type="button" class="apply-close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
                <div class="apply-progress" data-total="5">
                    <div class="apply-progress-bar" id="applyProgressBar"></div>
                </div>
                <div class="apply-steplabel" id="applyStepLabel">Step 1 of 5</div>
            </div>

            <form id="applyForm" action="{{ route('apply.store') }}" method="POST" novalidate>
                @csrf

                {{-- Spam trap — do not remove. Honeypot is hidden from humans; the
                     hidden tokens are populated by JavaScript to prove a real
                     browser submitted the form. See ApplyController::looksLikeSpam(). --}}
                <div aria-hidden="true" style="position:absolute; left:-5000px; top:auto; width:1px; height:1px; overflow:hidden;">
                    <label>Website (leave this field empty)</label>
                    <input type="text" name="website" tabindex="-1" autocomplete="off">
                </div>
                <input type="hidden" name="form_loaded_at" value="">
                <input type="hidden" name="elapsed_ms" value="">
                <input type="hidden" name="js_token" value="">

                <div class="apply-modal-body">

                    <!-- ===== STEP 1: GOAL ===== -->
                    <div class="apply-step active" data-step="1">
                        <h6 class="apply-q">What are you looking to do?</h6>
                        <div class="apply-options apply-options-goal">
                            <label class="apply-card">
                                <input type="radio" name="goal" value="test_prep" required>
                                <span class="apply-card-inner">
                                    <i class="bi bi-mortarboard"></i>
                                    <span class="apply-card-title">Test Preparation</span>
                                    <span class="apply-card-desc">IELTS, GRE, SAT, TOEFL, OET &amp; more</span>
                                </span>
                            </label>
                            <label class="apply-card">
                                <input type="radio" name="goal" value="study_abroad">
                                <span class="apply-card-inner">
                                    <i class="bi bi-globe-americas"></i>
                                    <span class="apply-card-title">Study Abroad</span>
                                    <span class="apply-card-desc">School &amp; university applications</span>
                                </span>
                            </label>
                            <label class="apply-card">
                                <input type="radio" name="goal" value="work_abroad">
                                <span class="apply-card-inner">
                                    <i class="bi bi-briefcase"></i>
                                    <span class="apply-card-title">Work Abroad</span>
                                    <span class="apply-card-desc">Job placement &amp; relocation</span>
                                </span>
                            </label>
                        </div>
                    </div>

                    <!-- ===== STEP 2: TAILORED DETAILS ===== -->
                    <div class="apply-step" data-step="2">
                        <!-- Test prep -->
                        <div class="apply-goal-block" data-goal="test_prep" hidden>
                            <h6 class="apply-q">About your test</h6>
                            <div class="mb-3">
                                <label class="apply-label">Which test are you preparing for?</label>
                                <select name="test" class="form-select apply-input">
                                    <option value="">Select a test…</option>
                                    <option>IELTS</option>
                                    <option>GRE</option>
                                    <option>SAT</option>
                                    <option>TOEFL</option>
                                    <option>G-MAT</option>
                                    <option>TESOL</option>
                                    <option>NCLEX</option>
                                    <option>OET</option>
                                    <option>Not sure yet</option>
                                </select>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="apply-label">Target score (optional)</label>
                                    <input type="text" name="target_score" class="form-control apply-input" placeholder="e.g. IELTS 7.5">
                                </div>
                                <div class="col-md-6">
                                    <label class="apply-label">When is your exam / deadline?</label>
                                    <input type="text" name="exam_deadline" class="form-control apply-input" placeholder="e.g. August 2026">
                                </div>
                            </div>
                        </div>

                        <!-- Study abroad -->
                        <div class="apply-goal-block" data-goal="study_abroad" hidden>
                            <h6 class="apply-q">About your study plans</h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="apply-label">Level of study</label>
                                    <select name="education_level" class="form-select apply-input">
                                        <option value="">Select…</option>
                                        <option>Undergraduate</option>
                                        <option>Postgraduate / Masters</option>
                                        <option>PhD</option>
                                        <option>Diploma / Foundation</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="apply-label">Course of interest</label>
                                    <input type="text" name="course_of_interest" class="form-control apply-input" placeholder="e.g. Nursing, Computer Science">
                                </div>
                                <div class="col-md-6">
                                    <label class="apply-label">Target countries</label>
                                    <input type="text" name="target_countries" class="form-control apply-input" placeholder="e.g. UK, Canada">
                                </div>
                                <div class="col-md-6">
                                    <label class="apply-label">Highest qualification</label>
                                    <input type="text" name="highest_qualification" class="form-control apply-input" placeholder="e.g. WASSCE, BSc">
                                </div>
                                <div class="col-md-6">
                                    <label class="apply-label">Do you have a passport?</label>
                                    <select name="has_passport" class="form-select apply-input">
                                        <option value="">Select…</option>
                                        <option>Yes</option>
                                        <option>No</option>
                                        <option>Applying for one</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Work abroad -->
                        <div class="apply-goal-block" data-goal="work_abroad" hidden>
                            <h6 class="apply-q">About your work plans</h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="apply-label">Target country</label>
                                    <input type="text" name="target_countries" class="form-control apply-input" placeholder="e.g. UK, Germany">
                                </div>
                                <div class="col-md-6">
                                    <label class="apply-label">Sector / role of interest</label>
                                    <input type="text" name="target_sector" class="form-control apply-input" placeholder="e.g. Healthcare, Hospitality">
                                </div>
                                <div class="col-md-6">
                                    <label class="apply-label">Years of experience</label>
                                    <select name="experience_years" class="form-select apply-input">
                                        <option value="">Select…</option>
                                        <option>0 – 1 years</option>
                                        <option>2 – 4 years</option>
                                        <option>5 – 9 years</option>
                                        <option>10+ years</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="apply-label">Current occupation</label>
                                    <input type="text" name="current_occupation" class="form-control apply-input" placeholder="e.g. Registered Nurse">
                                </div>
                                <div class="col-md-6">
                                    <label class="apply-label">Highest qualification</label>
                                    <input type="text" name="highest_qualification" class="form-control apply-input" placeholder="e.g. BSc Nursing">
                                </div>
                                <div class="col-md-6">
                                    <label class="apply-label">Do you have a passport?</label>
                                    <select name="has_passport" class="form-select apply-input">
                                        <option value="">Select…</option>
                                        <option>Yes</option>
                                        <option>No</option>
                                        <option>Applying for one</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ===== STEP 3: QUALIFICATION (BANT) ===== -->
                    <div class="apply-step" data-step="3">
                        <h6 class="apply-q">A few quick questions so we can help you properly</h6>

                        <div class="apply-field">
                            <label class="apply-label">How soon do you want to get started?</label>
                            <div class="apply-pills" data-name="timeline">
                                <label class="apply-pill"><input type="radio" name="timeline" value="within_1_month" required><span>Within 1 month</span></label>
                                <label class="apply-pill"><input type="radio" name="timeline" value="1_3_months"><span>1 – 3 months</span></label>
                                <label class="apply-pill"><input type="radio" name="timeline" value="3_6_months"><span>3 – 6 months</span></label>
                                <label class="apply-pill"><input type="radio" name="timeline" value="6_12_months"><span>6 – 12 months</span></label>
                                <label class="apply-pill"><input type="radio" name="timeline" value="exploring"><span>Just exploring</span></label>
                            </div>
                        </div>

                        <div class="apply-field">
                            <label class="apply-label">Have you set aside a budget for this?</label>
                            <div class="apply-pills" data-name="budget_status">
                                <label class="apply-pill"><input type="radio" name="budget_status" value="ready_now" required><span>Yes, ready now</span></label>
                                <label class="apply-pill"><input type="radio" name="budget_status" value="within_weeks"><span>Ready in a few weeks</span></label>
                                <label class="apply-pill"><input type="radio" name="budget_status" value="saving"><span>Still saving</span></label>
                                <label class="apply-pill"><input type="radio" name="budget_status" value="not_yet"><span>Not yet</span></label>
                            </div>
                        </div>

                        <div class="apply-field">
                            <label class="apply-label">How will you fund this?</label>
                            <div class="apply-pills" data-name="funding_source">
                                <label class="apply-pill"><input type="radio" name="funding_source" value="self" required><span>Self / family savings</span></label>
                                <label class="apply-pill"><input type="radio" name="funding_source" value="sponsor"><span>Sponsor</span></label>
                                <label class="apply-pill"><input type="radio" name="funding_source" value="loan"><span>Loan</span></label>
                                <label class="apply-pill"><input type="radio" name="funding_source" value="scholarship_hope"><span>Hoping for scholarship</span></label>
                                <label class="apply-pill"><input type="radio" name="funding_source" value="not_sure"><span>Not sure yet</span></label>
                            </div>
                        </div>

                        <div class="apply-field">
                            <label class="apply-label">How ready are you to begin?</label>
                            <div class="apply-pills" data-name="commitment">
                                <label class="apply-pill"><input type="radio" name="commitment" value="pay_now" required><span>Ready to enrol &amp; pay now</span></label>
                                <label class="apply-pill"><input type="radio" name="commitment" value="few_weeks"><span>Ready in a few weeks</span></label>
                                <label class="apply-pill"><input type="radio" name="commitment" value="researching"><span>Researching options</span></label>
                                <label class="apply-pill"><input type="radio" name="commitment" value="curious"><span>Just curious</span></label>
                            </div>
                        </div>
                    </div>

                    <!-- ===== STEP 4: ABOUT YOU (demographics) ===== -->
                    <div class="apply-step" data-step="4">
                        <h6 class="apply-q">A bit about you</h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="apply-label">Sex *</label>
                                <select name="sex" class="form-select apply-input" required>
                                    <option value="">Select…</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                    <option value="prefer_not">Prefer not to say</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="apply-label">Age range *</label>
                                <select name="age_range" class="form-select apply-input" required>
                                    <option value="">Select…</option>
                                    <option value="under_18">Under 18</option>
                                    <option value="18_24">18 – 24</option>
                                    <option value="25_34">25 – 34</option>
                                    <option value="35_44">35 – 44</option>
                                    <option value="45_plus">45+</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="apply-label">Country of residence *</label>
                                <input type="text" name="country" class="form-control apply-input" placeholder="e.g. Ghana" required>
                            </div>
                            <div class="col-md-6">
                                <label class="apply-label">City *</label>
                                <input type="text" name="city" class="form-control apply-input" placeholder="e.g. Accra" required>
                            </div>
                            <div class="col-md-6">
                                <label class="apply-label">Nationality *</label>
                                <input type="text" name="nationality" class="form-control apply-input" placeholder="e.g. Ghanaian" required>
                            </div>
                        </div>
                    </div>

                    <!-- ===== STEP 5: CONTACT ===== -->
                    <div class="apply-step" data-step="5">
                        <h6 class="apply-q">Where should we reach you?</h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="apply-label">First name *</label>
                                <input type="text" name="first_name" class="form-control apply-input" required>
                            </div>
                            <div class="col-md-6">
                                <label class="apply-label">Last name *</label>
                                <input type="text" name="last_name" class="form-control apply-input" required>
                            </div>
                            <div class="col-md-6">
                                <label class="apply-label">Email *</label>
                                <input type="email" name="email" class="form-control apply-input" required>
                            </div>
                            <div class="col-md-6">
                                <label class="apply-label">Phone / WhatsApp *</label>
                                <input type="tel" name="phone" class="form-control apply-input" required>
                            </div>
                            <div class="col-md-6">
                                <label class="apply-label">Preferred contact method</label>
                                <select name="contact_method" class="form-select apply-input">
                                    <option value="">No preference</option>
                                    <option>WhatsApp</option>
                                    <option>Phone call</option>
                                    <option>Email</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="apply-label">Anything else we should know? (optional)</label>
                                <textarea name="notes" class="form-control apply-input" rows="2" placeholder="Tell us briefly about your goal…"></textarea>
                            </div>
                        </div>
                        <p class="apply-privacy"><i class="bi bi-shield-check"></i> Your details are kept private and only used to contact you about your application.</p>
                    </div>

                    <!-- ===== SUCCESS ===== -->
                    <div class="apply-step apply-success" data-step="success">
                        <div class="apply-success-icon"><i class="bi bi-check-lg"></i></div>
                        <h5>Application received!</h5>
                        <p id="applySuccessMsg">Thank you. Our team will review your details and reach out to you shortly.</p>
                        <button type="button" class="btn btn-apply-primary" data-bs-dismiss="modal">Done</button>
                    </div>

                    <!-- Error alert -->
                    <div class="apply-error" id="applyError" hidden></div>
                </div>

                <!-- Footer nav -->
                <div class="apply-modal-footer" id="applyFooter">
                    <button type="button" class="btn apply-btn-back" id="applyBack" hidden><i class="bi bi-arrow-left"></i> Back</button>
                    <div class="ms-auto">
                        <button type="button" class="btn apply-btn-next" id="applyNext">Continue <i class="bi bi-arrow-right"></i></button>
                        <button type="submit" class="btn apply-btn-submit" id="applySubmit" hidden>Submit Application <i class="bi bi-send"></i></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const modalEl = document.getElementById('applyModal');
    if (!modalEl) return;

    // Auto-open modal if URL contains ?apply=true or #apply (useful for Facebook Ads landing pages)
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('apply') === 'true' || urlParams.get('apply') === '1' || window.location.hash === '#apply') {
        const bootstrapModal = new bootstrap.Modal(modalEl);
        bootstrapModal.show();
    }

    const form       = document.getElementById('applyForm');
    const steps      = Array.from(form.querySelectorAll('.apply-step[data-step]')).filter(s => s.dataset.step !== 'success');
    const successEl  = form.querySelector('.apply-step[data-step="success"]');
    const backBtn    = document.getElementById('applyBack');
    const nextBtn    = document.getElementById('applyNext');
    const submitBtn  = document.getElementById('applySubmit');
    const footer     = document.getElementById('applyFooter');
    const progress   = document.getElementById('applyProgressBar');
    const stepLabel  = document.getElementById('applyStepLabel');
    const stepHint   = document.getElementById('applyStepHint');
    const errorBox   = document.getElementById('applyError');

    const hints = {
        1: 'Tell us what you want to achieve.',
        2: 'A little context helps us prepare for you.',
        3: 'This helps us match you with the right plan.',
        4: 'A few quick details about you.',
        5: 'Almost done — how can we reach you?'
    };

    const TOTAL = 5;
    let current = 1;

    // ---- Spam trap: record when the form became available, and refresh on open.
    const pageLoadedAt = Date.now();
    const loadedField = form.querySelector('[name="form_loaded_at"]');
    if (loadedField) loadedField.value = pageLoadedAt;

    function showStep(n) {
        steps.forEach(s => s.classList.toggle('active', Number(s.dataset.step) === n));
        successEl.classList.remove('active');

        // Step 2 — reveal the block matching the chosen goal
        if (n === 2) syncGoalBlocks();

        backBtn.hidden   = (n === 1);
        nextBtn.hidden   = (n === TOTAL);
        submitBtn.hidden = (n !== TOTAL);

        progress.style.width = ((n - 1) / (TOTAL - 1) * 100) + '%';
        stepLabel.textContent = 'Step ' + n + ' of ' + TOTAL;
        stepHint.textContent = hints[n] || '';
        errorBox.hidden = true;
        modalEl.querySelector('.apply-modal-body').scrollTop = 0;
    }

    function goalValue() {
        const g = form.querySelector('input[name="goal"]:checked');
        return g ? g.value : null;
    }

    function syncGoalBlocks() {
        const goal = goalValue();
        form.querySelectorAll('.apply-goal-block').forEach(block => {
            const match = block.dataset.goal === goal;
            block.hidden = !match;
            // Disable inputs in hidden blocks so duplicate names (e.g. target_countries) don't clash on submit
            block.querySelectorAll('input, select, textarea').forEach(el => { el.disabled = !match; });
        });
    }

    function showError(msg) {
        errorBox.textContent = msg;
        errorBox.hidden = false;
    }

    function validateStep(n) {
        errorBox.hidden = true;
        if (n === 1) {
            if (!goalValue()) { showError('Please choose what you would like to do.'); return false; }
        }
        if (n === 3) {
            const required = ['timeline', 'budget_status', 'funding_source', 'commitment'];
            for (const name of required) {
                if (!form.querySelector('input[name="' + name + '"]:checked')) {
                    showError('Please answer all four questions so we can help you best.');
                    return false;
                }
            }
        }
        if (n === 4) {
            const fields = ['sex', 'age_range', 'country', 'city', 'nationality'];
            for (const name of fields) {
                const el = form.querySelector('[name="' + name + '"]');
                if (el && !el.value.trim()) { showError('Please answer all the questions on this step.'); el.focus(); return false; }
            }
        }
        if (n === 5) {
            const fields = ['first_name', 'last_name', 'email', 'phone'];
            for (const name of fields) {
                const el = form.querySelector('[name="' + name + '"]');
                if (el && !el.value.trim()) { showError('Please fill in your name, email and phone.'); el.focus(); return false; }
            }
            const email = form.querySelector('[name="email"]');
            if (email && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value.trim())) {
                showError('Please enter a valid email address.'); email.focus(); return false;
            }
        }
        return true;
    }

    nextBtn.addEventListener('click', function () {
        if (!validateStep(current)) return;
        if (current < TOTAL) { current++; showStep(current); }
    });

    backBtn.addEventListener('click', function () {
        if (current > 1) { current--; showStep(current); }
    });

    // Auto-advance from goal selection for a snappier feel
    form.querySelectorAll('input[name="goal"]').forEach(input => {
        input.addEventListener('change', function () {
            if (current === 1) { current = 2; showStep(2); }
        });
    });

    // Submit via AJAX so we can show the success step in-place
    form.addEventListener('submit', function (e) {
        e.preventDefault();
        if (!validateStep(TOTAL)) return;

        const original = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Submitting…';

        // ---- Spam trap: prove a real browser ran JS and took a human amount of time.
        const loadedAt = Number(loadedField && loadedField.value) || pageLoadedAt;
        const elapsedField = form.querySelector('[name="elapsed_ms"]');
        if (elapsedField) elapsedField.value = Date.now() - loadedAt;
        const tokenField = form.querySelector('[name="js_token"]');
        const csrfMeta = document.querySelector('meta[name="csrf-token"]');
        if (tokenField && csrfMeta) tokenField.value = btoa(csrfMeta.content).slice(0, 16);

        // Generate client-side UUID for Lead event deduplication
        const eventId = (typeof crypto !== 'undefined' && crypto.randomUUID)
            ? crypto.randomUUID()
            : 'uuid-' + Date.now() + '-' + Math.random().toString(36).substring(2, 15);

        const data = new FormData(form);
        data.append('event_id', eventId);

        // Forward all URL query parameters (like fbclid, utm_source) so the server can access them
        const urlParams = new URLSearchParams(window.location.search);
        urlParams.forEach((value, key) => {
            data.append(key, value);
        });

        fetch(form.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: data
        })
        .then(r => r.json().then(j => ({ ok: r.ok, body: j })))
        .then(({ ok, body }) => {
            if (ok && body.success) {
                // Trigger Meta Pixel events if present
                if (window.triggerMetaPixelEvents && body.fb_events) {
                    window.triggerMetaPixelEvents(body.fb_events);
                }
                steps.forEach(s => s.classList.remove('active'));
                successEl.classList.add('active');
                footer.hidden = true;
                progress.style.width = '100%';
                stepLabel.textContent = 'Complete';
                stepHint.textContent = 'Thank you!';
                document.getElementById('applySuccessMsg').textContent = body.message;
            } else {
                showError(body.message || 'Something went wrong. Please try again.');
            }
        })
        .catch(() => showError('Network error. Please check your connection and try again.'))
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = original;
        });
    });

    // Reset to step 1 whenever the modal is reopened
    modalEl.addEventListener('show.bs.modal', function () {
        current = 1;
        footer.hidden = false;
        successEl.classList.remove('active');
        if (loadedField) loadedField.value = Date.now();
        showStep(1);

        // Dynamic CSRF Token Refresh to bypass cache constraints (LiteSpeed/Cloudflare)
        fetch('/csrf-token')
            .then(r => r.json())
            .then(data => {
                if (data.token) {
                    const meta = document.querySelector('meta[name="csrf-token"]');
                    if (meta) meta.setAttribute('content', data.token);
                    const inputToken = form.querySelector('input[name="_token"]');
                    if (inputToken) inputToken.value = data.token;
                    // Refresh the js_token field with the fresh token
                    const tokenField = form.querySelector('[name="js_token"]');
                    if (tokenField) tokenField.value = btoa(data.token).slice(0, 16);
                }
            })
            .catch(err => console.error('Failed to refresh CSRF token:', err));
    });
});
</script>
@endpush
