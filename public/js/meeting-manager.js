class MeetingManager {
    constructor() {
        this.updateInterval = null;
        this.isUpdating = false;
        this.init();
    }

    init() {
        // Start auto-update every 30 seconds
        this.startAutoUpdate();

        // Update on page load
        this.updateMeetingStatus();

        // Add event listeners
        this.addEventListeners();
    }

    addEventListeners() {
        // Refresh button
        document.addEventListener('click', (e) => {
            if (e.target && e.target.matches('[data-refresh-meetings]')) {
                e.preventDefault();
                this.updateMeetingStatus(true);
            }
        });

        // Join meeting buttons
        document.addEventListener('click', (e) => {
            if (e.target && (e.target.matches('.btn-join-meeting') || e.target.closest('.btn-join-meeting'))) {
                const btn = e.target.matches('.btn-join-meeting') ? e.target : e.target.closest('.btn-join-meeting');
                if (btn) {
                    this.handleJoinMeeting(btn);
                }
            }
        });

        // Visibility change to update when tab becomes active
        document.addEventListener('visibilitychange', () => {
            if (!document.hidden) {
                this.updateMeetingStatus();
            }
        });
    }

    startAutoUpdate() {
        this.updateInterval = setInterval(() => {
            this.updateMeetingStatus();
        }, 30000); // Update every 30 seconds
    }

    stopAutoUpdate() {
        if (this.updateInterval) {
            clearInterval(this.updateInterval);
            this.updateInterval = null;
        }
    }

    async updateMeetingStatus(showIndicator = false) {
        if (this.isUpdating) return;

        this.isUpdating = true;

        if (showIndicator) {
            this.showRefreshIndicator();
        }

        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            if (!csrfToken) {
                console.warn('CSRF token not found');
                return;
            }

            const response = await fetch('/api/meeting/update-status', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken.getAttribute('content')
                }
            });

            if (!response.ok) {
                throw new Error('Failed to update meeting status');
            }

            const data = await response.json();

            if (data.success) {
                this.updateActiveMeetingsUI(data.data.active_meetings);
                this.updateCompletedMeetingsUI(data.data.completed_meetings);
            }
        } catch (error) {
            console.error('Error updating meeting status:', error);
        } finally {
            this.isUpdating = false;
            if (showIndicator) {
                this.hideRefreshIndicator();
            }
        }
    }

    updateActiveMeetingsUI(meetings) {
        const container = document.querySelector('.active-meetings-container');
        if (!container) return;

        if (meetings.length === 0) {
            container.innerHTML = `
                <div class="empty-meetings">
                    <i class="bi bi-calendar-x"></i>
                    <h5>Tidak Ada Meeting Aktif</h5>
                    <p>Saat ini tidak ada meeting yang sedang berlangsung atau terjadwal.</p>
                </div>
            `;
            return;
        }

        const meetingsHtml = meetings.map(meeting => `
            <div class="meeting-active-item" data-meeting-id="${meeting.id}">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <h5 class="meeting-title">${meeting.title}</h5>
                        <div class="meeting-details">
                            <div class="detail-item">
                                <i class="bi bi-calendar3 detail-icon"></i>
                                <span class="detail-label">Tanggal:</span>
                                <span class="detail-value">${meeting.formatted_date}</span>
                            </div>
                            <div class="detail-item">
                                <i class="bi bi-clock detail-icon"></i>
                                <span class="detail-label">Waktu:</span>
                                <span class="detail-value">${meeting.time}</span>
                            </div>
                            <div class="detail-item">
                                <i class="bi bi-people detail-icon"></i>
                                <span class="detail-label">Peserta:</span>
                                <span class="detail-value">${meeting.participants}</span>
                            </div>
                            <div class="detail-item">
                                <i class="bi bi-person-check detail-icon"></i>
                                <span class="detail-label">Dibuat oleh:</span>
                                <span class="detail-value">${meeting.author}</span>
                            </div>
                        </div>
                        <span class="meeting-status-badge ${meeting.status_class}">
                            ${meeting.status_label}
                        </span>
                    </div>
                    <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                        ${this.generateJoinButton(meeting)}
                    </div>
                </div>
            </div>
        `).join('');

        container.innerHTML = meetingsHtml;
    }

    generateJoinButton(meeting) {
        if (meeting.can_join && meeting.status === 'active') {
            return `
                <a href="${meeting.meet_link}" target="_blank" class="btn-join-meeting" data-meeting-id="${meeting.id}">
                    <i class="bi bi-camera-video"></i>Join Meeting
                </a>
                <br><br>
                <small class="text-muted">Klik untuk bergabung via Google Meet</small>
            `;
        } else if (meeting.can_join && meeting.status === 'scheduled') {
            return `
                <a href="${meeting.meet_link}" target="_blank" class="btn-join-meeting" data-meeting-id="${meeting.id}">
                    <i class="bi bi-camera-video"></i>Join Meeting
                </a>
                <br><br>
                <small class="text-success">Meeting sudah bisa dimulai</small>
            `;
        } else {
            return `
                <button class="btn-waiting" disabled>
                    <i class="bi bi-clock"></i>Belum Dimulai
                </button>
                <br><br>
                <small class="text-muted">Meeting akan aktif sesuai jadwal</small>
            `;
        }
    }

    updateCompletedMeetingsUI(meetings) {
        const tbody = document.querySelector('.history-table tbody');
        if (!tbody) return;

        if (meetings.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="4" class="text-center text-muted">
                        <i class="bi bi-clock-history me-2"></i>Belum ada riwayat meeting
                    </td>
                </tr>
            `;
            return;
        }

        const meetingsHtml = meetings.map(meeting => `
            <tr data-meeting-id="${meeting.id}">
                <td>${meeting.date}</td>
                <td>${meeting.title}</td>
                <td>${meeting.participants}</td>
                <td><span class="history-status">${meeting.status}</span></td>
            </tr>
        `).join('');

        tbody.innerHTML = meetingsHtml;
    }

    handleJoinMeeting(button) {
        if (!button) return;

        // Add loading state
        if (button.classList) {
            button.classList.add('loading');
        }
        button.disabled = true;

        // Remove loading state after 2 seconds
        setTimeout(() => {
            if (button.classList) {
                button.classList.remove('loading');
            }
            button.disabled = false;
        }, 2000);
    }

    showRefreshIndicator() {
        let indicator = document.querySelector('.auto-refresh-indicator');
        if (!indicator) {
            indicator = document.createElement('div');
            indicator.className = 'auto-refresh-indicator';
            indicator.innerHTML = '<i class="bi bi-arrow-clockwise me-2"></i>Memperbarui data meeting...';
            document.body.appendChild(indicator);
        }

        if (indicator.classList) {
            indicator.classList.add('show');
        }
    }

    hideRefreshIndicator() {
        const indicator = document.querySelector('.auto-refresh-indicator');
        if (indicator) {
            if (indicator.classList) {
                indicator.classList.remove('show');
            }
            setTimeout(() => {
                if (indicator.parentNode) {
                    indicator.parentNode.removeChild(indicator);
                }
            }, 300);
        }
    }

    destroy() {
        this.stopAutoUpdate();
    }
}

// Safe jQuery check and Select2 initialization
function initializeSelect2() {
    // Check if jQuery exists
    if (typeof jQuery === 'undefined' || typeof $ === 'undefined') {
        console.warn('jQuery not loaded, Select2 initialization skipped');
        return;
    }

    // Check if Select2 exists
    if (typeof $.fn.select2 === 'undefined') {
        console.warn('Select2 not loaded, initialization skipped');
        return;
    }

    try {
        const select2Elements = document.querySelectorAll('.select2');
        if (select2Elements.length > 0) {
            $('.select2').select2({
                placeholder: "Pilih peserta meeting...",
                allowClear: true,
                width: '100%',
                language: {
                    noResults: function() {
                        return "Tidak ada hasil ditemukan";
                    },
                    searching: function() {
                        return "Mencari...";
                    }
                }
            });
        }
    } catch (error) {
        console.error('Select2 initialization failed:', error);
    }
}

// Safe modal Select2 reinitialization
function reinitializeSelect2InModal() {
    if (typeof jQuery === 'undefined' || typeof $.fn.select2 === 'undefined') {
        return;
    }

    try {
        // Destroy existing Select2 if it exists
        $('.select2').each(function() {
            if ($(this).hasClass('select2-hidden-accessible')) {
                $(this).select2('destroy');
            }
        });

        // Reinitialize
        $('.select2').select2({
            placeholder: "Pilih peserta meeting...",
            allowClear: true,
            width: '100%',
            dropdownParent: $('#createMeetingModal'),
            language: {
                noResults: function() {
                    return "Tidak ada hasil ditemukan";
                },
                searching: function() {
                    return "Mencari...";
                }
            }
        });
    } catch (error) {
        console.error('Select2 modal reinitialization failed:', error);
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Initialize meeting manager
    window.meetingManager = new MeetingManager();

    // Initialize Select2 safely
    initializeSelect2();

    // Fix participants validation
    const participantsSelect = document.getElementById('participants');
    if (participantsSelect) {
        // Remove old validation message
        const oldError = participantsSelect.parentNode.querySelector('.invalid-feedback');
        if (oldError && oldError.textContent.includes('string')) {
            oldError.remove();
        }

        // Add custom validation
        participantsSelect.addEventListener('change', function() {
            const selectedValues = Array.from(this.selectedOptions).map(option => option.value);

            if (selectedValues.length === 0) {
                if (this.classList) {
                    this.classList.add('is-invalid');
                }
                let feedback = this.parentNode.querySelector('.invalid-feedback.custom-participants');
                if (!feedback) {
                    feedback = document.createElement('div');
                    feedback.className = 'invalid-feedback custom-participants';
                    feedback.textContent = 'Silakan pilih minimal satu peserta meeting.';
                    this.parentNode.appendChild(feedback);
                }
            } else {
                if (this.classList) {
                    this.classList.remove('is-invalid');
                }
                const feedback = this.parentNode.querySelector('.invalid-feedback.custom-participants');
                if (feedback) {
                    feedback.remove();
                }
            }
        });
    }

    // Form submission enhancement
    const createMeetingForm = document.getElementById('createMeetingForm');
    if (createMeetingForm) {
        createMeetingForm.addEventListener('submit', function(e) {
            // Validate participants before submit
            const participants = document.getElementById('participants');
            if (participants) {
                const selectedValues = Array.from(participants.selectedOptions).map(option => option.value);

                if (selectedValues.length === 0) {
                    e.preventDefault();
                    if (participants.classList) {
                        participants.classList.add('is-invalid');
                    }

                    let feedback = participants.parentNode.querySelector('.invalid-feedback.custom-participants');
                    if (!feedback) {
                        feedback = document.createElement('div');
                        feedback.className = 'invalid-feedback custom-participants';
                        feedback.textContent = 'Silakan pilih minimal satu peserta meeting.';
                        participants.parentNode.appendChild(feedback);
                    }

                    participants.focus();
                    return false;
                }
            }

            // Add loading state to submit button
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn && submitBtn.classList) {
                submitBtn.classList.add('loading');
                submitBtn.disabled = true;
            }
        });
    }

    // Modal enhancements
    const modal = document.getElementById('createMeetingModal');
    if (modal) {
        modal.addEventListener('shown.bs.modal', function() {
            // Re-initialize Select2 in modal context safely
            reinitializeSelect2InModal();

            // Focus on first input
            const titleInput = document.getElementById('meeting_title');
            if (titleInput) {
                titleInput.focus();
            }
        });

        modal.addEventListener('hidden.bs.modal', function() {
            // Reset form validation states
            const form = document.getElementById('createMeetingForm');
            if (form) {
                form.querySelectorAll('.is-invalid').forEach(el => {
                    if (el.classList) {
                        el.classList.remove('is-invalid');
                    }
                });
                form.querySelectorAll('.invalid-feedback.custom').forEach(el => {
                    el.remove();
                });
                form.querySelectorAll('.invalid-feedback.custom-participants').forEach(el => {
                    el.remove();
                });

                // Reset submit button
                const submitBtn = form.querySelector('button[type="submit"]');
                if (submitBtn && submitBtn.classList) {
                    submitBtn.classList.remove('loading');
                    submitBtn.disabled = false;
                }
            }
        });
    }

    // Auto-hide success alerts
    const successAlert = document.querySelector('.alert-success');
    if (successAlert) {
        setTimeout(function() {
            successAlert.style.opacity = '0';
            setTimeout(function() {
                if (successAlert.parentNode) {
                    successAlert.parentNode.removeChild(successAlert);
                }
            }, 300);
        }, 5000);
    }

    // Show modal if there are validation errors
    const hasErrors = document.querySelector('.invalid-feedback') ||
                     document.querySelector('.alert-danger') ||
                     document.querySelector('.is-invalid');

    if (hasErrors) {
        // Check if Bootstrap modal exists
        if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
            const modalElement = document.getElementById('createMeetingModal');
            if (modalElement) {
                const createMeetingModal = new bootstrap.Modal(modalElement);
                createMeetingModal.show();
            }
        }
    }

    // Set default values
    const dateInput = document.getElementById('meeting_date');
    if (dateInput && !dateInput.value) {
        const tomorrow = new Date();
        tomorrow.setDate(tomorrow.getDate() + 1);
        dateInput.value = tomorrow.toISOString().split('T')[0];
        dateInput.setAttribute('min', new Date().toISOString().split('T')[0]);
    }

    const timeInput = document.getElementById('meeting_time');
    if (timeInput && !timeInput.value) {
        timeInput.value = '19:00';
    }

    // Google Meet URL validation
    const meetLinkInput = document.getElementById('meet_link');
    if (meetLinkInput) {
        meetLinkInput.addEventListener('input', function() {
            const url = this.value;
            const meetPattern = /^https:\/\/meet\.google\.com\/[a-z]{3}-[a-z]{4}-[a-z]{3}$/;

            if (url && !meetPattern.test(url)) {
                if (this.classList) {
                    this.classList.add('is-invalid');
                }
                let existingFeedback = this.parentNode.querySelector('.invalid-feedback.custom-meet');
                if (existingFeedback) {
                    existingFeedback.remove();
                }
                const feedback = document.createElement('div');
                feedback.className = 'invalid-feedback custom-meet';
                feedback.textContent = 'Format link Google Meet tidak valid. Contoh: https://meet.google.com/abc-defg-hij';
                this.parentNode.appendChild(feedback);
            } else {
                if (this.classList) {
                    this.classList.remove('is-invalid');
                }
                const customFeedback = this.parentNode.querySelector('.invalid-feedback.custom-meet');
                if (customFeedback) {
                    customFeedback.remove();
                }
            }
        });

        // Add generate link button
        const existingBtn = meetLinkInput.parentNode.querySelector('.btn-outline-primary');
        if (!existingBtn) {
            const generateBtn = document.createElement('button');
            generateBtn.type = 'button';
            generateBtn.className = 'btn btn-outline-primary btn-sm mt-2';
            generateBtn.innerHTML = '<i class="bi bi-magic me-1"></i>Generate Sample Link';
            generateBtn.onclick = function() {
                const chars = 'abcdefghijklmnopqrstuvwxyz';
                const part1 = Array.from({length: 3}, () => chars[Math.floor(Math.random() * chars.length)]).join('');
                const part2 = Array.from({length: 4}, () => chars[Math.floor(Math.random() * chars.length)]).join('');
                const part3 = Array.from({length: 3}, () => chars[Math.floor(Math.random() * chars.length)]).join('');
                meetLinkInput.value = `https://meet.google.com/${part1}-${part2}-${part3}`;
                meetLinkInput.dispatchEvent(new Event('input'));
            };
            meetLinkInput.parentNode.appendChild(generateBtn);
        }
    }

    // Real-time form validation
    const requiredInputs = document.querySelectorAll('#createMeetingForm input[required], #createMeetingForm textarea[required]');
    requiredInputs.forEach(input => {
        if (!input) return;

        input.addEventListener('blur', function() {
            if (this.value.trim() === '') {
                if (this.classList) {
                    this.classList.add('is-invalid');
                }
            } else {
                if (this.classList) {
                    this.classList.remove('is-invalid');
                }
            }
        });

        input.addEventListener('input', function() {
            if (this.classList && this.classList.contains('is-invalid') && this.value.trim() !== '') {
                this.classList.remove('is-invalid');
            }
        });
    });

    // Smooth scroll animations
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting && entry.target) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);

    document.querySelectorAll('.meeting-active-item, .instruction-step').forEach(el => {
        if (el) {
            el.style.opacity = '0';
            el.style.transform = 'translateY(20px)';
            el.style.transition = 'all 0.6s ease';
            observer.observe(el);
        }
    });
});

// Cleanup on page unload
window.addEventListener('beforeunload', function() {
    if (window.meetingManager) {
        window.meetingManager.destroy();
    }
});
