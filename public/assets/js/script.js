/**
 * assets/js/script.js
 * CertiLap - Hardware Diagnostic & HCI Step-by-Step Walkthrough System
 */

document.addEventListener('DOMContentLoaded', function () {

    // =========================================================================
    // 1. Audio Synthesizer (Web Audio API - Ala Game Console)
    // =========================================================================
    let sfxEnabled = localStorage.getItem('certilap_sfx') !== 'false';
    let audioCtx = null;

    function playSfx(type) {
        if (!sfxEnabled) return;
        try {
            if (!audioCtx) audioCtx = new (window.AudioContext || window.webkitAudioContext)();
            if (audioCtx.state === 'suspended') audioCtx.resume();
            const now = audioCtx.currentTime;
            const osc = audioCtx.createOscillator();
            const gain = audioCtx.createGain();
            osc.connect(gain);
            gain.connect(audioCtx.destination);

            if (type === 'select') {
                osc.type = 'sine';
                osc.frequency.setValueAtTime(560, now);
                osc.frequency.exponentialRampToValueAtTime(840, now + 0.08);
                gain.gain.setValueAtTime(0.12, now);
                gain.gain.exponentialRampToValueAtTime(0.001, now + 0.08);
                osc.start(now);
                osc.stop(now + 0.08);
            } else if (type === 'next') {
                osc.type = 'triangle';
                osc.frequency.setValueAtTime(420, now);
                osc.frequency.exponentialRampToValueAtTime(700, now + 0.09);
                gain.gain.setValueAtTime(0.08, now);
                gain.gain.exponentialRampToValueAtTime(0.001, now + 0.09);
                osc.start(now);
                osc.stop(now + 0.09);
            } else if (type === 'prev') {
                osc.type = 'triangle';
                osc.frequency.setValueAtTime(600, now);
                osc.frequency.exponentialRampToValueAtTime(380, now + 0.09);
                gain.gain.setValueAtTime(0.08, now);
                gain.gain.exponentialRampToValueAtTime(0.001, now + 0.09);
                osc.start(now);
                osc.stop(now + 0.09);
            }
        } catch (e) {
            // Audio context restriction safe
        }
    }

    const btnToggleSound = document.getElementById('btnToggleSound');
    const soundIcon = document.getElementById('soundIcon');
    const soundText = document.getElementById('soundText');

    function updateSoundUI() {
        if (soundIcon && soundText) {
            if (sfxEnabled) {
                soundIcon.className = 'bi bi-volume-up-fill me-1';
                soundText.textContent = 'SFX: ON';
            } else {
                soundIcon.className = 'bi bi-volume-mute-fill me-1 text-muted';
                soundText.textContent = 'SFX: OFF';
            }
        }
    }

    if (btnToggleSound) {
        updateSoundUI();
        btnToggleSound.addEventListener('click', () => {
            sfxEnabled = !sfxEnabled;
            localStorage.setItem('certilap_sfx', sfxEnabled ? 'true' : 'false');
            updateSoundUI();
            if (sfxEnabled) playSfx('select');
        });
    }

    // =========================================================================
    // 2. Interactive Gamified Quiz Deck Engine (Ala Game Kuisioner)
    // =========================================================================
    const quizData = window.CERTILAP_QUIZ_DATA || [];
    let quizCurrentIndex = 0;
    let autoAdvanceTimeout = null;

    const hudCatBadge = document.getElementById('hudCatBadge');
    const hudCatIcon = document.getElementById('hudCatIcon');
    const hudCatTitle = document.getElementById('hudCatTitle');
    const hudQuestionCode = document.getElementById('hudQuestionCode');
    const hudQuestionNumber = document.getElementById('hudQuestionNumber');
    const hudProgressPercent = document.getElementById('hudProgressPercent');
    const hudProgressFill = document.getElementById('hudProgressFill');

    const quizCardBox = document.getElementById('quizCardBox');
    const quizQuestionText = document.getElementById('quizQuestionText');
    const quizClueContent = document.getElementById('quizClueContent');
    const choiceBtns = document.querySelectorAll('.quiz-option-item');

    const btnQuizPrev = document.getElementById('btnQuizPrev');
    const btnQuizNext = document.getElementById('btnQuizNext');
    const btnQuizSkip = document.getElementById('btnQuizSkip');

    const counterEl = document.getElementById('gejalaTerpilih');
    const btnDiagnosa = document.getElementById('btnDiagnosa');
    const btnReset = document.getElementById('btnReset');
    const questMapSummary = document.getElementById('questMapSummary');

    function renderQuizQuestion(direction) {
        if (!quizData || quizData.length === 0) return;
        const q = quizData[quizCurrentIndex];
        if (!q) return;

        // Card Animation
        if (quizCardBox) {
            quizCardBox.classList.remove('anim-next', 'anim-prev');
            void quizCardBox.offsetWidth; // Force CSS reflow
            quizCardBox.classList.add(direction === 'prev' ? 'anim-prev' : 'anim-next');
        }

        // HUD Information
        if (hudCatIcon) hudCatIcon.className = `bi ${q.icon}`;
        if (hudCatTitle) hudCatTitle.textContent = q.cat_title;
        if (hudQuestionCode) hudQuestionCode.textContent = `Gejala ${q.kode}`;
        if (hudQuestionNumber) hudQuestionNumber.textContent = q.index;

        const pct = Math.round(((quizCurrentIndex + 1) / quizData.length) * 100);
        if (hudProgressPercent) hudProgressPercent.textContent = `${pct}% Selesai`;
        if (hudProgressFill) hudProgressFill.style.width = `${pct}%`;

        // Question & Clue Text
        if (quizQuestionText) {
            let clean = q.nama.trim();
            if (!clean.endsWith('?')) clean += '?';
            quizQuestionText.textContent = clean;
        }
        if (quizClueContent) quizClueContent.textContent = q.clue;

        // Active Choice State
        const hiddenInput = document.getElementById(`hidden_gejala_${q.id}`);
        const currentVal = hiddenInput ? (parseFloat(hiddenInput.value) || 0) : 0;

        choiceBtns.forEach(btn => {
            const btnVal = parseFloat(btn.getAttribute('data-cf')) || 0;
            if (Math.abs(btnVal - currentVal) < 0.05) {
                btn.classList.add('active');
            } else {
                btn.classList.remove('active');
            }
        });

        // Prev & Next Buttons
        if (btnQuizPrev) btnQuizPrev.disabled = (quizCurrentIndex === 0);
        if (btnQuizNext) {
            if (quizCurrentIndex === quizData.length - 1) {
                btnQuizNext.innerHTML = '<i class="bi bi-check2-all me-1"></i>Selesai';
            } else {
                btnQuizNext.innerHTML = 'Lanjut <i class="bi bi-arrow-right ms-1"></i>';
            }
        }

        // Highlight Active Node in Quest Map
        document.querySelectorAll('.quest-node-btn').forEach((node, idx) => {
            if (idx === quizCurrentIndex) {
                node.classList.add('current');
            } else {
                node.classList.remove('current');
            }
        });
    }

    function updateOverallFeedback() {
        let totalSelected = 0;
        if (!quizData) return;

        quizData.forEach(q => {
            const hiddenInput = document.getElementById(`hidden_gejala_${q.id}`);
            const val = hiddenInput ? (parseFloat(hiddenInput.value) || 0) : 0;
            const nodeBtn = document.getElementById(`node-btn-${q.id}`);
            const listRow = document.querySelector(`.gejala-row[data-id="${q.id}"]`);

            if (val > 0) {
                totalSelected++;
                if (listRow) listRow.classList.add('selected');
                if (nodeBtn) {
                    nodeBtn.classList.add('answered');
                    const badgeCf = nodeBtn.querySelector('.badge-cf');
                    if (badgeCf) {
                        badgeCf.textContent = `CF ${val}`;
                        badgeCf.style.background = '#FFE4E6';
                        badgeCf.style.color = '#E11D48';
                    }
                }
            } else {
                if (listRow) listRow.classList.remove('selected');
                if (nodeBtn) {
                    nodeBtn.classList.remove('answered');
                    const badgeCf = nodeBtn.querySelector('.badge-cf');
                    if (badgeCf) {
                        badgeCf.textContent = q.kode;
                        badgeCf.style.background = '#E2E8F0';
                        badgeCf.style.color = '#475569';
                    }
                }
            }
        });

        if (counterEl) counterEl.textContent = `${totalSelected} Gejala Terpilih`;
        document.querySelectorAll('.listGejalaTerpilih').forEach(c => c.textContent = totalSelected);
        if (questMapSummary) questMapSummary.textContent = `${totalSelected} dari ${quizData.length} gejala terindikasi kerusakan`;

        const submitBtns = document.querySelectorAll('.btn-submit-diagnosa, #btnDiagnosa, #btnDiagnosaList');
        submitBtns.forEach(btn => {
            if (totalSelected > 0) {
                btn.removeAttribute('disabled');
                btn.classList.remove('opacity-50');
            } else {
                btn.setAttribute('disabled', 'disabled');
                btn.classList.add('opacity-50');
            }
        });
    }

    function selectConfidenceValue(cfVal) {
        const q = quizData[quizCurrentIndex];
        if (!q) return;

        playSfx('select');

        // 1. Update hidden input
        const hiddenInput = document.getElementById(`hidden_gejala_${q.id}`);
        if (hiddenInput) hiddenInput.value = cfVal;

        // 2. Sync with List Mode select
        const listSelect = document.querySelector(`.gejala-list-select[data-id="${q.id}"]`);
        if (listSelect) listSelect.value = cfVal;

        // 3. Update choice button UI
        choiceBtns.forEach(b => {
            const bVal = parseFloat(b.getAttribute('data-cf')) || 0;
            if (Math.abs(bVal - parseFloat(cfVal)) < 0.05) {
                b.classList.add('active');
            } else {
                b.classList.remove('active');
            }
        });

        updateOverallFeedback();

        // 4. Tactile auto-advance smoothly to next question after soft pause
        if (autoAdvanceTimeout) clearTimeout(autoAdvanceTimeout);
        autoAdvanceTimeout = setTimeout(() => {
            if (quizCurrentIndex < quizData.length - 1) {
                quizCurrentIndex++;
                renderQuizQuestion('next');
            }
        }, 320);
    }

    // Choice Cards Click Handler
    choiceBtns.forEach(btn => {
        btn.addEventListener('click', function () {
            const cfVal = this.getAttribute('data-cf');
            selectConfidenceValue(cfVal);
        });
    });

    // Keyboard Arrow Navigation
    document.addEventListener('keydown', function (e) {
        if (document.activeElement && ['INPUT', 'TEXTAREA', 'SELECT'].includes(document.activeElement.tagName)) return;
        const quizView = document.getElementById('quizGameView');
        if (!quizView || quizView.classList.contains('d-none')) return;

        if (e.key === 'ArrowRight') {
            if (btnQuizNext) btnQuizNext.click();
        } else if (e.key === 'ArrowLeft') {
            if (btnQuizPrev && !btnQuizPrev.disabled) btnQuizPrev.click();
        }
    });

    // Navigation Buttons
    if (btnQuizNext) {
        btnQuizNext.addEventListener('click', () => {
            if (autoAdvanceTimeout) clearTimeout(autoAdvanceTimeout);
            if (quizCurrentIndex < quizData.length - 1) {
                playSfx('next');
                quizCurrentIndex++;
                renderQuizQuestion('next');
            }
        });
    }

    if (btnQuizPrev) {
        btnQuizPrev.addEventListener('click', () => {
            if (autoAdvanceTimeout) clearTimeout(autoAdvanceTimeout);
            if (quizCurrentIndex > 0) {
                playSfx('prev');
                quizCurrentIndex--;
                renderQuizQuestion('prev');
            }
        });
    }

    if (btnQuizSkip) {
        btnQuizSkip.addEventListener('click', () => {
            if (autoAdvanceTimeout) clearTimeout(autoAdvanceTimeout);
            if (quizCurrentIndex < quizData.length - 1) {
                playSfx('next');
                quizCurrentIndex++;
                renderQuizQuestion('next');
            }
        });
    }

    // Quest Map Modal Node Jump
    document.querySelectorAll('.quest-node-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const targetIdx = parseInt(this.getAttribute('data-index'));
            if (!isNaN(targetIdx)) {
                if (autoAdvanceTimeout) clearTimeout(autoAdvanceTimeout);
                quizCurrentIndex = targetIdx;
                renderQuizQuestion('next');

                const modalEl = document.getElementById('questMapModal');
                if (modalEl && typeof bootstrap !== 'undefined') {
                    const bsModal = bootstrap.Modal.getInstance(modalEl);
                    if (bsModal) bsModal.hide();
                }
            }
        });
    });

    // Reset All Choices
    // Reset All Choices
    function resetAllChoices() {
        if (confirm('Apakah Anda ingin mereset seluruh pilihan gejala?')) {
            quizData.forEach(q => {
                const hiddenInput = document.getElementById(`hidden_gejala_${q.id}`);
                if (hiddenInput) hiddenInput.value = '0';

                const listSelect = document.querySelector(`.gejala-list-select[data-id="${q.id}"]`);
                if (listSelect) listSelect.value = '0';
            });

            quizCurrentIndex = 0;
            renderQuizQuestion('next');
            updateOverallFeedback();
            playSfx('prev');
        }
    }

    if (btnReset) {
        btnReset.addEventListener('click', resetAllChoices);
    }

    document.querySelectorAll('.btnResetList').forEach(btn => {
        btn.addEventListener('click', resetAllChoices);
    });

    // =========================================================================
    // 3. Alternative View Mode Toggle (Quiz vs List)
    // =========================================================================
    const btnToggleViewMode = document.getElementById('btnToggleViewMode');
    const viewModeText = document.getElementById('viewModeText');
    const quizGameView = document.getElementById('quizGameView');
    const listViewMode = document.getElementById('listViewMode');

    if (btnToggleViewMode && quizGameView && listViewMode) {
        btnToggleViewMode.addEventListener('click', function () {
            if (listViewMode.classList.contains('d-none')) {
                listViewMode.classList.remove('d-none');
                quizGameView.classList.add('d-none');
                if (viewModeText) viewModeText.textContent = 'Mode Kuisioner';

                // Sinkronkan seluruh select di mode daftar dengan nilai hidden input terbaru
                quizData.forEach(q => {
                    const hiddenInput = document.getElementById(`hidden_gejala_${q.id}`);
                    const listSelect = document.querySelector(`.gejala-list-select[data-id="${q.id}"]`);
                    if (hiddenInput && listSelect) {
                        listSelect.value = hiddenInput.value || '0';
                    }
                });
                updateOverallFeedback();
            } else {
                listViewMode.classList.add('d-none');
                quizGameView.classList.remove('d-none');
                if (viewModeText) viewModeText.textContent = 'Mode Daftar';
                renderQuizQuestion('next');
                updateOverallFeedback();
            }
        });
    }

    // List Mode Select Change Handlers
    document.querySelectorAll('.gejala-list-select').forEach(sel => {
        sel.addEventListener('change', function () {
            const id = this.getAttribute('data-id');
            const hiddenInput = document.getElementById(`hidden_gejala_${id}`);
            if (hiddenInput) hiddenInput.value = this.value;

            updateOverallFeedback();
        });
    });

    // List Mode Search Filter & Category Tabs
    const searchInput = document.getElementById('searchGejala');
    const listCategoryTabs = document.querySelectorAll('.symptom-category-tabs .category-tab-btn');
    const listSymptomRows = document.querySelectorAll('.gejala-row');

    if (searchInput) {
        searchInput.addEventListener('input', function () {
            const q = this.value.toLowerCase().trim();
            listSymptomRows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(q) ? 'flex' : 'none';
            });
        });
    }

    if (listCategoryTabs.length > 0) {
        listCategoryTabs.forEach(tab => {
            tab.addEventListener('click', function () {
                listCategoryTabs.forEach(t => t.classList.remove('active'));
                this.classList.add('active');
                const cat = this.getAttribute('data-category');

                listSymptomRows.forEach(row => {
                    if (cat === 'all' || row.getAttribute('data-category') === cat) {
                        row.style.display = 'flex';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        });
    }

    // Initialize Quiz Deck on Load
    if (quizData.length > 0) {
        renderQuizQuestion('next');
        updateOverallFeedback();
    }

    // =========================================================================
    // 5. Certainty Factor Formula Live Preview (Admin Rules Modal)
    // =========================================================================
    const mbInput = document.getElementById('mb');
    const mdInput = document.getElementById('md');
    const cfPreview = document.getElementById('cfPakarPreview');

    function calculatePreview() {
        if (!mbInput || !mdInput || !cfPreview) return;
        const mb = parseFloat(mbInput.value) || 0;
        const md = parseFloat(mdInput.value) || 0;
        const cf = (mb - md).toFixed(2);
        cfPreview.textContent = cf;

        if (parseFloat(cf) >= 0.5) {
            cfPreview.className = 'form-control bg-success bg-opacity-10 text-success fw-bold text-center fs-5';
        } else if (parseFloat(cf) > 0) {
            cfPreview.className = 'form-control bg-warning bg-opacity-10 text-warning fw-bold text-center fs-5';
        } else {
            cfPreview.className = 'form-control bg-danger bg-opacity-10 text-danger fw-bold text-center fs-5';
        }
    }

    if (mbInput) mbInput.addEventListener('input', calculatePreview);
    if (mdInput) mdInput.addEventListener('input', calculatePreview);

    // =========================================================================
    // 6. Delete Action Confirmation
    // =========================================================================
    document.querySelectorAll('.btn-hapus').forEach(btn => {
        btn.addEventListener('click', function (e) {
            if (!confirm('Apakah Anda yakin ingin menghapus data ini? Data yang terhapus tidak dapat dikembalikan.')) {
                e.preventDefault();
            }
        });
    });

    // =========================================================================
    // 7. Auto-Dismiss Floating Alerts
    // =========================================================================
    setTimeout(function () {
        document.querySelectorAll('.alert-auto-dismiss').forEach(el => {
            el.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
            el.style.opacity = '0';
            el.style.transform = 'translateY(-8px)';
            setTimeout(() => el.remove(), 400);
        });
    }, 4500);

    // =========================================================================
    // 8. Print Handler
    // =========================================================================
    const btnCetak = document.getElementById('btnCetak');
    if (btnCetak) {
        btnCetak.addEventListener('click', () => window.print());
    }

    // =========================================================================
    // 9. Interactive Live Certainty Factor Simulator (Landing Page Playground)
    // =========================================================================
    const simCaseBtns = document.querySelectorAll('.sim-case-btn');
    const simSlider = document.getElementById('simConfidenceSlider');
    const simSliderVal = document.getElementById('simConfidenceValue');
    const simFormulaDisplay = document.getElementById('simFormulaDisplay');
    const simPctDisplay = document.getElementById('simPctDisplay');
    const simGaugeFill = document.getElementById('simGaugeFill');
    const simTargetTitle = document.getElementById('simTargetTitle');

    const testScenarios = {
        'ram': {
            title: 'K01 — Kerusakan pada RAM',
            symptoms: [
                { name: 'G01: Bluescreen / Restart Acak', cfPakar: 0.70 },
                { name: 'G03: Laptop Sering Freeze Tiba-tiba', cfPakar: 0.50 }
            ]
        },
        'gpu': {
            title: 'K08 — Kerusakan VGA',
            symptoms: [
                { name: 'G30: Layar Bergaris / Artifak Tekstur', cfPakar: 0.75 },
                { name: 'G32: Laptop Cepat Panas / Overheat', cfPakar: 0.55 }
            ]
        },
        'battery': {
            title: 'K06 — Kerusakan Baterai',
            symptoms: [
                { name: 'G21: Baterai Cepat Drop / Habis', cfPakar: 0.80 },
                { name: 'G22: Baterai Tidak Bisa Mengisi', cfPakar: 0.60 }
            ]
        }
    };

    let activeScenarioKey = 'ram';

    function runSimCalculation() {
        if (!simGaugeFill || !simPctDisplay || !simFormulaDisplay) return;
        const userConf = parseFloat(simSlider ? simSlider.value : 0.8) || 0.8;
        if (simSliderVal) simSliderVal.textContent = userConf.toFixed(1);

        const currentScenario = testScenarios[activeScenarioKey];
        if (!currentScenario) return;

        if (simTargetTitle) {
            simTargetTitle.textContent = currentScenario.title;
        }

        const sym1 = currentScenario.symptoms[0];
        const sym2 = currentScenario.symptoms[1];

        const cf1 = sym1.cfPakar * userConf;
        const cf2 = sym2.cfPakar * userConf;

        const cfCombine = cf1 + cf2 * (1 - cf1);
        const pct = Math.min(Math.round(cfCombine * 1000) / 10, 100);

        simPctDisplay.textContent = pct.toFixed(1) + '%';
        simGaugeFill.style.width = pct + '%';

        simFormulaDisplay.innerHTML = 
            `CF1 = ${sym1.cfPakar.toFixed(2)} × ${userConf.toFixed(1)} = <span class="text-dark fw-bold">${cf1.toFixed(3)}</span><br>` +
            `CF2 = ${sym2.cfPakar.toFixed(2)} × ${userConf.toFixed(1)} = <span class="text-dark fw-bold">${cf2.toFixed(3)}</span><br>` +
            `<span class="text-accent-cyan fw-bold">CF_gabungan</span> = ${cf1.toFixed(3)} + ${cf2.toFixed(3)} × (1 - ${cf1.toFixed(3)}) = <strong class="text-accent-red">${cfCombine.toFixed(4)}</strong>`;
    }

    if (simCaseBtns.length > 0) {
        simCaseBtns.forEach(btn => {
            btn.addEventListener('click', function () {
                simCaseBtns.forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                activeScenarioKey = this.getAttribute('data-case');
                runSimCalculation();
            });
        });
    }

    if (simSlider) {
        simSlider.addEventListener('input', runSimCalculation);
        runSimCalculation();
    }
});
