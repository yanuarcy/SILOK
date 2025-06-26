<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Queue;
use App\Models\Setting;

class QueueDisplayController extends Controller
{
    /**
     * Display the queue screen
     */
    public function index()
    {
        return view('Queue.index');
    }

    /**
     * Get current queue statistics for display
     */
    public function getStats()
    {
        try {
            $stats = [
                'pending' => Queue::pending()->count(),
                'processing' => Queue::processing()->count(),
                'total_today' => Queue::whereDate('created_at', today())->count()
            ];

            return response()->json([
                'success' => true,
                'stats' => $stats
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil statistik'
            ], 500);
        }
    }
}
