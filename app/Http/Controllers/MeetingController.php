<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class MeetingController extends Controller
{
    /**
     * Show create meeting form
     */
    public function create(): View
    {
        return view('layanan.InformasiUmum.create-meeting', [
            'title' => 'Buat Meeting Baru'
        ]);
    }

    /**
     * Display the meeting detail page
     */
    public function detail(): View
    {
        // Update meeting statuses first
        $this->updateMeetingStatuses();

        $data = [
            'title' => 'Detail Meeting - Zoom Meeting RW/RT',
            'meeting_schedule' => $this->getMeetingScheduleData(),
            'active_meetings' => $this->getActiveMeetings(),
            'completed_meetings' => $this->getCompletedMeetings(),
        ];

        return view('layanan.InformasiUmum.meeting-detail', $data);
    }

    /**
     * Store new meeting - FIXED VERSION with time format conversion
     */
    public function store(Request $request): RedirectResponse
    {
        // Validasi sederhana
        $request->validate([
            'meeting_title' => 'required|string|max:255',
            'meeting_date' => 'required|date|after:today',
            'meeting_time' => 'required',
            'meet_link' => 'required|url',
            'participants' => 'required|array|min:1',
            'description' => 'nullable|string|max:500'
        ], [
            'meeting_title.required' => 'Judul meeting wajib diisi.',
            'meeting_date.after' => 'Tanggal meeting harus setelah hari ini.',
            'meet_link.required' => 'Link Google Meet wajib diisi.',
            'participants.required' => 'Silakan pilih minimal satu peserta meeting.',
            'participants.min' => 'Silakan pilih minimal satu peserta meeting.',
        ]);

        try {
            // Convert participants array to comma-separated string
            $participantsString = '';
            if ($request->has('participants') && is_array($request->participants)) {
                $cleanParticipants = array_filter($request->participants, function($p) {
                    return !empty(trim($p));
                });
                $participantsString = implode(',', $cleanParticipants);
            }

            // Pastikan ada participants
            if (empty($participantsString)) {
                return redirect()->back()
                               ->withInput()
                               ->withErrors(['participants' => 'Silakan pilih minimal satu peserta meeting.']);
            }

            // Convert time format to 24-hour format for database
            $meetingTime = $this->convertTimeFormat($request->meeting_time);

            // Create meeting
            Meeting::create([
                'user_id' => Auth::id(),
                'title' => $request->meeting_title,
                'meeting_date' => $request->meeting_date,
                'meeting_time' => $meetingTime, // Use converted time
                'meet_link' => $request->meet_link,
                'participants' => $participantsString, // String separated by comma
                'description' => $request->description,
                'status' => 'scheduled'
            ]);

            return redirect()->route('meeting.detail')
                           ->with('success', 'Meeting berhasil dibuat!');

        } catch (\Exception $e) {
            return redirect()->back()
                           ->withInput()
                           ->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Convert time format from various formats to 24-hour format (HH:MM:SS)
     */
    private function convertTimeFormat($timeInput): string
    {
        try {
            // Remove any extra spaces
            $timeInput = trim($timeInput);

            // If already in HH:MM format, just add seconds
            if (preg_match('/^([01]?[0-9]|2[0-3]):([0-5][0-9])$/', $timeInput)) {
                return $timeInput . ':00';
            }

            // If in HH:MM:SS format, return as is
            if (preg_match('/^([01]?[0-9]|2[0-3]):([0-5][0-9]):([0-5][0-9])$/', $timeInput)) {
                return $timeInput;
            }

            // Handle 12-hour format (e.g., "7:45 PM", "7:45PM", "19:45")
            if (preg_match('/(\d{1,2}):(\d{2})\s*(AM|PM)/i', $timeInput, $matches)) {
                $hours = (int) $matches[1];
                $minutes = (int) $matches[2];
                $period = strtoupper($matches[3]);

                // Convert to 24-hour format
                if ($period === 'PM' && $hours !== 12) {
                    $hours += 12;
                } elseif ($period === 'AM' && $hours === 12) {
                    $hours = 0;
                }

                return sprintf('%02d:%02d:00', $hours, $minutes);
            }

            // If no format matches, try Carbon parse
            $carbon = Carbon::createFromFormat('H:i', $timeInput);
            return $carbon->format('H:i:s');

        } catch (\Exception $e) {
            // If all else fails, return a default time
            return '19:00:00';
        }
    }

    /**
     * Update meeting status via API
     */
    public function updateStatus(): JsonResponse
    {
        try {
            // Update meeting statuses first
            $this->updateMeetingStatuses();

            // Get fresh data after update
            $activeMeetings = $this->getActiveMeetings();
            $completedMeetings = $this->getCompletedMeetings();

            return response()->json([
                'success' => true,
                'message' => 'Meeting statuses updated successfully',
                'timestamp' => now()->toISOString(),
                'data' => [
                    'active_meetings' => $activeMeetings,
                    'completed_meetings' => $completedMeetings,
                    'total_active' => count($activeMeetings),
                    'total_completed' => count($completedMeetings)
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Meeting status update failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to update meeting statuses',
                'error' => app()->environment('local') ? $e->getMessage() : 'Internal server error',
                'timestamp' => now()->toISOString()
            ], 500);
        }
    }

    /**
     * Get active meetings via API
     */
    public function getActiveMeetingsApi(): JsonResponse
    {
        $this->updateMeetingStatuses();

        return response()->json([
            'success' => true,
            'data' => $this->getActiveMeetings()
        ]);
    }

    /**
     * Get completed meetings via API
     */
    public function getCompletedMeetingsApi(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->getCompletedMeetings()
        ]);
    }

    /**
     * Delete meeting (for authorized users)
     */
    public function destroy(Meeting $meeting): JsonResponse
    {
        try {
            // Check if user is authorized to delete (owner or admin)
            if ($meeting->user_id !== Auth::id() && !Auth::user()->hasRole('admin')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized to delete this meeting'
                ], 403);
            }

            $meeting->delete();

            return response()->json([
                'success' => true,
                'message' => 'Meeting berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete meeting',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Private method to update meeting statuses
     */
    private function updateMeetingStatuses(): void
    {
        // Get all meetings that need status check
        $meetings = Meeting::whereIn('status', ['scheduled', 'active'])->get();

        foreach ($meetings as $meeting) {
            $oldStatus = $meeting->status;
            $meeting->updateStatus();

            // Log status changes for debugging
            if ($oldStatus !== $meeting->status) {
                \Log::info("Meeting {$meeting->id} status changed: {$oldStatus} → {$meeting->status}");
            }
        }
    }

    /**
     * Private method to get active meetings
     */
    private function getActiveMeetings(): array
    {
        $meetings = Meeting::active()
                          ->with('user')
                          ->orderBy('meeting_date')
                          ->orderBy('meeting_time')
                          ->get();

        return $meetings->map(function ($meeting) {
            // Ensure meeting datetime is properly formatted
            $meetingDateTime = $meeting->meeting_date_time;
            $currentStatus = $meeting->current_status;
            $statusClass = $this->getStatusClass($currentStatus);

            // Debug individual meeting
            \Log::info("Meeting {$meeting->id}: Status={$currentStatus}, Class={$statusClass}");

            return [
                'id' => $meeting->id,
                'title' => $meeting->title,
                'date' => $meeting->meeting_date->format('Y-m-d'),
                'time' => $meeting->meeting_time->format('H:i') . ' WIB',
                'meet_link' => $meeting->meet_link,
                'status' => $meeting->status,
                'status_label' => $currentStatus, // Dynamic status
                'status_class' => $statusClass, // Dynamic status class
                'participants' => $meeting->participants_string,
                'description' => $meeting->description,
                'author' => $meeting->user->name ?? 'Unknown',
                'can_join' => $meeting->isJoinable(),
                'is_active' => $meeting->is_active,
                'formatted_date' => $meeting->meeting_date->format('d F Y'),
                'time_until_joinable' => $meeting->time_until_joinable,
                'meeting_datetime' => $meetingDateTime->format('Y-m-d H:i:s'),
                'meeting_timestamp' => $meetingDateTime->timestamp,
                'current_timestamp' => now()->timestamp,
            ];
        })->toArray();
    }

    /**
     * Get status class based on current status
     */
    private function getStatusClass(string $currentStatus): string
    {
        return match($currentStatus) {
            'Sedang Berlangsung' => 'status-active',
            'Siap Dimulai' => 'status-ready',
            'Belum Dimulai' => 'status-scheduled',
            'Terlewat' => 'status-scheduled',
            'Selesai' => 'status-completed',
            default => 'status-scheduled'
        };
    }

    /**
     * Private method to get completed meetings
     */
    private function getCompletedMeetings(): array
    {
        $meetings = Meeting::completed()
                          ->with('user')
                          ->take(6)
                          ->get();

        return $meetings->map(function ($meeting) {
            $participantCount = count($meeting->participants_array);

            return [
                'id' => $meeting->id,
                'date' => $meeting->meeting_date->format('d M Y'),
                'title' => $meeting->title,
                'participants' => $participantCount . ' orang',
                'status' => 'Selesai',
                'author' => $meeting->user->name ?? 'Unknown',
                'duration' => $meeting->started_at && $meeting->ended_at
                    ? $meeting->started_at->diffForHumans($meeting->ended_at, true)
                    : '1 jam',
            ];
        })->toArray();
    }

    /**
     * Private method to get meeting schedule data
     */
    private function getMeetingScheduleData(): array
    {
        return [
            [
                'title' => 'Rapat Koordinasi RW',
                'schedule' => 'Setiap Senin, 19:00 WIB',
                'status' => 'Aktif',
                'status_class' => 'success'
            ],
            [
                'title' => 'Rapat RT Bulanan',
                'schedule' => 'Minggu ke-2 setiap bulan, 20:00 WIB',
                'status' => 'Terjadwal',
                'status_class' => 'info'
            ],
            [
                'title' => 'Rapat Koordinasi Kelurahan',
                'schedule' => 'Setiap Kamis, 14:00 WIB',
                'status' => 'Pending',
                'status_class' => 'warning'
            ]
        ];
    }
}
