<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Queue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class QueueApiController extends Controller
{
    /**
     * Add queue to system
     */
    public function addToQueue(Request $request)
    {
        try {
            // Log request data for debugging
            Log::info('Queue add request received', [
                'data' => $request->all(),
                'loket_type' => gettype($request->loket),
                'loket_value' => $request->loket
            ]);

            $request->validate([
                'antrian' => 'required|string',
                'loket' => 'required', // Remove string requirement, accept any type
                'nama' => 'nullable|string',
                'whatsapp' => 'nullable|string'
            ]);

            // Convert loket to string to ensure consistency
            $loket = (string) $request->loket;

            $queue = Queue::create([
                'antrian' => $request->antrian,
                'loket' => $loket,
                'nama' => $request->nama ?? '',
                'whatsapp' => $request->whatsapp ?? '',
                'status' => 'pending'
            ]);

            Log::info('Queue added successfully', [
                'queue_id' => $queue->id,
                'antrian' => $request->antrian,
                'loket' => $loket
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Antrian ditambahkan',
                'data' => $queue
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Queue validation error', [
                'errors' => $e->errors(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            Log::error('Add queue error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menambahkan antrian: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get next queue in line
     */
    public function getNext()
    {
        try {
            DB::beginTransaction();

            // Get next pending queue with lock
            $nextQueue = Queue::pending()
                ->orderBy('id', 'ASC')
                ->lockForUpdate()
                ->first();

            if (!$nextQueue) {
                DB::commit();
                return response()->json([
                    'status' => 'empty',
                    'message' => 'Tidak ada antrian'
                ]);
            }

            // Mark as processing
            $nextQueue->markAsProcessing();

            DB::commit();

            Log::info('Next queue processed', ['queue_id' => $nextQueue->id, 'antrian' => $nextQueue->antrian]);

            return response()->json([
                'id' => $nextQueue->id,
                'antrian' => $nextQueue->antrian,
                'loket' => $nextQueue->loket,
                'nama' => $nextQueue->nama,
                'whatsapp' => $nextQueue->whatsapp,
                'status' => $nextQueue->status
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Get next queue error', ['error' => $e->getMessage()]);

            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil antrian berikutnya'
            ], 500);
        }
    }

    /**
     * Mark queue as complete and delete
     */
    public function completeQueue(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required|integer|exists:queue,id'
            ]);

            DB::beginTransaction();

            $queue = Queue::find($request->id);

            if (!$queue) {
                DB::rollback();
                return response()->json([
                    'status' => 'error',
                    'message' => 'Antrian tidak ditemukan'
                ], 404);
            }

            // Mark as completed
            $queue->markAsCompleted();

            // Delete the queue
            $queue->delete();

            DB::commit();

            Log::info('Queue completed and deleted', ['queue_id' => $request->id]);

            return response()->json([
                'status' => 'success',
                'message' => 'Antrian selesai dan dihapus'
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Complete queue error', ['error' => $e->getMessage()]);

            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menandai antrian selesai'
            ], 500);
        }
    }

    /**
     * Get next queues for display (up to 8)
     */
    public function getNextList()
    {
        try {
            $nextQueues = Queue::pending()
                ->orderBy('id', 'ASC')
                ->limit(8)
                ->get(['antrian']);

            return response()->json([
                'success' => true,
                'queues' => $nextQueues
            ]);

        } catch (\Exception $e) {
            Log::error('Get next list error', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil daftar antrian'
            ], 500);
        }
    }

    /**
     * Get queue statistics
     */
    public function getStats()
    {
        try {
            $stats = [
                'pending' => Queue::pending()->count(),
                'processing' => Queue::processing()->count(),
                'completed' => Queue::completed()->whereDate('created_at', today())->count(),
                'total_today' => Queue::whereDate('created_at', today())->count()
            ];

            return response()->json([
                'success' => true,
                'stats' => $stats
            ]);

        } catch (\Exception $e) {
            Log::error('Get stats error', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil statistik'
            ], 500);
        }
    }
}
