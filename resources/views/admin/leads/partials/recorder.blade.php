{{--
    Browser call recorder. Captures the device microphone via MediaRecorder
    while the staff member speaks to the lead on loudspeaker, then uploads the
    blob to the private disk. Requires HTTPS (or localhost) for getUserMedia.
--}}
<div class="card border-0 mb-4">
    <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
        <h5 class="fw-bold mb-0"><i class="bi bi-mic me-2" style="color:#F8A706;"></i>Call Recordings</h5>
        <span class="badge bg-light text-dark">{{ $lead->recordings->count() }}</span>
    </div>

    <div class="card-body">
        {{-- Recorder control panel --}}
        <div id="recorder-panel"
             class="border rounded-3 p-3 mb-4"
             data-store-url="{{ route('admin.leads.recordings.store', $lead->id) }}">

            <div id="recorder-unsupported" class="alert alert-warning mb-0 d-none">
                <i class="bi bi-exclamation-triangle me-1"></i>
                <span id="recorder-unsupported-text">Your browser can’t record audio here.</span>
            </div>

            <div id="recorder-ui">
                <div class="d-flex align-items-center flex-wrap gap-2">
                    <button type="button" id="btn-record" class="btn btn-danger">
                        <i class="bi bi-record-circle me-1"></i> Record call
                    </button>
                    <button type="button" id="btn-stop" class="btn btn-outline-secondary d-none">
                        <i class="bi bi-stop-fill me-1"></i> Stop &amp; save
                    </button>
                    <button type="button" id="btn-cancel" class="btn btn-outline-danger d-none">
                        Discard
                    </button>

                    <span id="recorder-status" class="ms-1 small text-muted">
                        Put the call on loudspeaker, then press Record.
                    </span>

                    <span id="recorder-timer" class="ms-auto fw-bold d-none" style="font-variant-numeric:tabular-nums;">
                        <span class="text-danger">●</span> <span id="timer-text">0:00</span>
                    </span>
                </div>

                {{-- Live input level so the user can confirm audio is arriving --}}
                <div id="level-wrap" class="progress mt-3 d-none" style="height:6px;">
                    <div id="level-bar" class="progress-bar bg-danger" style="width:0%;"></div>
                </div>

                <div class="mt-3">
                    <label for="recording-summary" class="form-label small fw-semibold mb-1">
                        Call summary (optional — saved with the recording)
                    </label>
                    <input type="text" id="recording-summary" class="form-control form-control-sm"
                           maxlength="2000" placeholder="e.g. Discussed IELTS timeline, wants to start in September">
                </div>

                <div id="recorder-alert" class="alert mt-3 mb-0 d-none"></div>
            </div>
        </div>

        {{-- Existing recordings --}}
        @forelse ($lead->recordings as $recording)
            <div class="border rounded-3 p-3 {{ ! $loop->last ? 'mb-3' : '' }}">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-2">
                    <div class="small">
                        <span class="fw-semibold">{{ $recording->recorded_by_label }}</span>
                        <span class="text-muted ms-1" title="{{ $recording->created_at->format('M d, Y H:i') }}">
                            · {{ $recording->created_at->diffForHumans() }}
                        </span>
                        <span class="text-muted ms-1">· {{ $recording->duration_label }} · {{ $recording->size_label }}</span>
                        @if ($recording->consent_confirmed)
                            <span class="badge bg-success-subtle text-success border border-success-subtle ms-1"
                                  title="Staff confirmed the lead was notified before recording">
                                <i class="bi bi-shield-check"></i> Consent confirmed
                            </span>
                        @endif
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.leads.recordings.download', [$lead->id, $recording->id]) }}"
                           class="btn btn-sm btn-outline-secondary" title="Download">
                            <i class="bi bi-download"></i>
                        </a>
                        @if (auth()->user()->isAdmin() || $recording->user_id === auth()->id())
                            <form action="{{ route('admin.leads.recordings.destroy', [$lead->id, $recording->id]) }}"
                                  method="POST"
                                  onsubmit="return confirm('Delete this recording permanently?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        @endif
                    </div>
                </div>

                <audio controls preload="none" class="w-100"
                       src="{{ route('admin.leads.recordings.stream', [$lead->id, $recording->id]) }}"></audio>

                <form action="{{ route('admin.leads.recordings.update', [$lead->id, $recording->id]) }}"
                      method="POST" class="d-flex gap-2 mt-2">
                    @csrf @method('PATCH')
                    <input type="text" name="summary" maxlength="2000"
                           class="form-control form-control-sm"
                           value="{{ $recording->summary }}"
                           placeholder="Add a summary of this call…">
                    <button type="submit" class="btn btn-sm btn-outline-primary flex-shrink-0">Save</button>
                </form>
            </div>
        @empty
            <p class="text-muted small mb-0">No recordings yet.</p>
        @endforelse
    </div>
</div>

{{-- Consent gate --}}
<div class="modal fade" id="consentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-shield-check me-2 text-success"></i>Before you record</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="mb-3">
                    Recording a call without telling the other person is unlawful in many places.
                    Tell {{ $lead->first_name ?: 'the lead' }} the call is being recorded and get their agreement
                    <strong>before</strong> you start.
                </p>
                <div class="alert alert-light border small mb-3">
                    Suggested wording: “Just to let you know, I’m recording this call so my colleagues
                    can follow up accurately. Is that okay with you?”
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="consent-check">
                    <label class="form-check-label" for="consent-check">
                        I have told {{ $lead->first_name ?: 'the lead' }} that this call is being recorded and they agreed.
                    </label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="btn-consent-start" disabled>
                    <i class="bi bi-record-circle me-1"></i> Start recording
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
(function () {
    const panel = document.getElementById('recorder-panel');
    if (!panel) return;

    const storeUrl   = panel.dataset.storeUrl;
    const csrf       = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const btnRecord  = document.getElementById('btn-record');
    const btnStop    = document.getElementById('btn-stop');
    const btnCancel  = document.getElementById('btn-cancel');
    const status     = document.getElementById('recorder-status');
    const timer      = document.getElementById('recorder-timer');
    const timerText  = document.getElementById('timer-text');
    const levelWrap  = document.getElementById('level-wrap');
    const levelBar   = document.getElementById('level-bar');
    const alertBox   = document.getElementById('recorder-alert');
    const summaryEl  = document.getElementById('recording-summary');
    const consentBox = document.getElementById('consent-check');
    const consentGo  = document.getElementById('btn-consent-start');
    const consentModal = new bootstrap.Modal(document.getElementById('consentModal'));

    let recorder = null, stream = null, chunks = [], startedAt = 0;
    let tick = null, audioCtx = null, rafId = null, discarded = false;

    // --- capability check -------------------------------------------------
    const secure = window.isSecureContext || location.hostname === 'localhost';
    if (!secure || !navigator.mediaDevices || !window.MediaRecorder) {
        document.getElementById('recorder-ui').classList.add('d-none');
        const box = document.getElementById('recorder-unsupported');
        document.getElementById('recorder-unsupported-text').textContent = !secure
            ? 'Audio recording needs a secure (HTTPS) connection. Open the admin panel over https:// to record calls.'
            : 'This browser does not support audio recording. Use a recent Chrome, Edge, Firefox or Safari.';
        box.classList.remove('d-none');
        return;
    }

    function showAlert(message, type) {
        alertBox.className = 'alert mt-3 mb-0 alert-' + type;
        alertBox.textContent = message;
        alertBox.classList.remove('d-none');
    }

    function clearAlert() {
        alertBox.classList.add('d-none');
    }

    // Pick a container the browser actually supports.
    function pickMimeType() {
        const candidates = [
            'audio/webm;codecs=opus', 'audio/webm',
            'audio/ogg;codecs=opus', 'audio/mp4', 'audio/aac',
        ];
        for (const type of candidates) {
            if (MediaRecorder.isTypeSupported(type)) return type;
        }
        return ''; // let the browser choose
    }

    function startTimer() {
        startedAt = Date.now();
        timer.classList.remove('d-none');
        tick = setInterval(function () {
            const s = Math.floor((Date.now() - startedAt) / 1000);
            timerText.textContent = Math.floor(s / 60) + ':' + String(s % 60).padStart(2, '0');
        }, 500);
    }

    // Visual level meter — confirms the mic is actually picking the call up.
    function startMeter(mediaStream) {
        try {
            audioCtx = new (window.AudioContext || window.webkitAudioContext)();
            const source = audioCtx.createMediaStreamSource(mediaStream);
            const analyser = audioCtx.createAnalyser();
            analyser.fftSize = 512;
            source.connect(analyser);
            const data = new Uint8Array(analyser.frequencyBinCount);

            levelWrap.classList.remove('d-none');
            (function draw() {
                analyser.getByteFrequencyData(data);
                let sum = 0;
                for (let i = 0; i < data.length; i++) sum += data[i];
                const level = Math.min(100, (sum / data.length) * 2.2);
                levelBar.style.width = level + '%';
                rafId = requestAnimationFrame(draw);
            })();
        } catch (e) {
            /* meter is cosmetic — ignore failures */
        }
    }

    function teardown() {
        if (tick) { clearInterval(tick); tick = null; }
        if (rafId) { cancelAnimationFrame(rafId); rafId = null; }
        if (audioCtx) { audioCtx.close().catch(function () {}); audioCtx = null; }
        if (stream) { stream.getTracks().forEach(function (t) { t.stop(); }); stream = null; }
        levelWrap.classList.add('d-none');
        levelBar.style.width = '0%';
        timer.classList.add('d-none');
        timerText.textContent = '0:00';
    }

    function resetButtons() {
        btnRecord.classList.remove('d-none');
        btnStop.classList.add('d-none');
        btnCancel.classList.add('d-none');
    }

    async function beginRecording() {
        clearAlert();
        discarded = false;
        chunks = [];

        try {
            stream = await navigator.mediaDevices.getUserMedia({
                audio: {
                    echoCancellation: false,  // keep the far-end speaker audio
                    noiseSuppression: false,
                    autoGainControl: true,
                },
            });
        } catch (err) {
            showAlert('Microphone access was blocked (' + err.name + '). Allow the mic for this site and try again.', 'danger');
            return;
        }

        const mimeType = pickMimeType();
        try {
            recorder = mimeType ? new MediaRecorder(stream, { mimeType: mimeType }) : new MediaRecorder(stream);
        } catch (err) {
            showAlert('Could not start the recorder: ' + err.message, 'danger');
            teardown();
            return;
        }

        recorder.ondataavailable = function (e) {
            if (e.data && e.data.size > 0) chunks.push(e.data);
        };

        recorder.onstop = function () {
            const seconds = Math.round((Date.now() - startedAt) / 1000);
            const blob = new Blob(chunks, { type: recorder.mimeType || 'audio/webm' });
            teardown();
            resetButtons();

            if (discarded) {
                status.textContent = 'Recording discarded.';
                return;
            }
            if (blob.size < 1024) {
                showAlert('Nothing was captured — the recording was too short.', 'warning');
                status.textContent = 'Put the call on loudspeaker, then press Record.';
                return;
            }
            upload(blob, seconds);
        };

        recorder.start(1000); // flush every second so long calls stream to memory safely
        startTimer();
        startMeter(stream);

        btnRecord.classList.add('d-none');
        btnStop.classList.remove('d-none');
        btnCancel.classList.remove('d-none');
        status.textContent = 'Recording… keep the phone on loudspeaker near the mic.';
    }

    function upload(blob, seconds) {
        const ext = (blob.type.indexOf('ogg') > -1) ? 'ogg'
                  : (blob.type.indexOf('mp4') > -1 || blob.type.indexOf('aac') > -1) ? 'm4a'
                  : 'webm';

        const form = new FormData();
        form.append('audio', blob, 'call-' + Date.now() + '.' + ext);
        form.append('duration', seconds);
        form.append('consent', '1');
        form.append('summary', summaryEl.value || '');

        status.textContent = 'Uploading…';
        btnRecord.disabled = true;

        fetch(storeUrl, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
            body: form,
            credentials: 'same-origin',
        })
        .then(function (res) {
            return res.json().catch(function () { return {}; }).then(function (data) {
                if (!res.ok) throw new Error(data.message || ('Upload failed (HTTP ' + res.status + ')'));
                return data;
            });
        })
        .then(function () {
            showAlert('Recording saved. Reloading…', 'success');
            setTimeout(function () { window.location.reload(); }, 800);
        })
        .catch(function (err) {
            btnRecord.disabled = false;
            status.textContent = 'Put the call on loudspeaker, then press Record.';
            showAlert(err.message, 'danger');
        });
    }

    // --- wiring -----------------------------------------------------------
    btnRecord.addEventListener('click', function () {
        consentBox.checked = false;
        consentGo.disabled = true;
        consentModal.show();
    });

    consentBox.addEventListener('change', function () {
        consentGo.disabled = !consentBox.checked;
    });

    consentGo.addEventListener('click', function () {
        consentModal.hide();
        beginRecording();
    });

    btnStop.addEventListener('click', function () {
        if (recorder && recorder.state !== 'inactive') recorder.stop();
    });

    btnCancel.addEventListener('click', function () {
        if (!confirm('Discard this recording without saving?')) return;
        discarded = true;
        if (recorder && recorder.state !== 'inactive') recorder.stop();
    });

    // Don't let a page close silently bin an in-progress recording.
    window.addEventListener('beforeunload', function (e) {
        if (recorder && recorder.state === 'recording') {
            e.preventDefault();
            e.returnValue = '';
        }
    });
})();
</script>
@endpush
