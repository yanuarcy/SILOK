<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Antarmuka;
use Illuminate\Support\Facades\Log;

class AntarmukaApiController extends Controller
{
    /**
     * Get active video for display
     */
    public function getActiveVideo()
    {
        try {
            $activeVideo = Antarmuka::where('status', 1)->first();

            if (!$activeVideo) {
                return response()->json([
                    'success' => false,
                    'message' => 'No active video found',
                    'video_url' => null,
                    'video_data' => null
                ]);
            }

            // Determine source type
            $sourceType = $this->getSourceType($activeVideo->sumber);
            $videoUrl = $activeVideo->sumber;

            // For uploaded files, convert to full URL
            if ($sourceType === 'upload') {
                $videoUrl = asset($activeVideo->sumber);
            }

            // For YouTube, ensure we have the full URL, not just embed
            if ($sourceType === 'youtube') {
                // If it's already a watch URL, keep it
                if (strpos($videoUrl, 'youtube.com/watch?v=') !== false) {
                    // Already correct format
                } else if (strpos($videoUrl, 'youtube.com/embed/') !== false) {
                    // Convert embed URL to watch URL
                    $videoId = $this->extractVideoIdFromEmbed($videoUrl);
                    $videoUrl = "https://www.youtube.com/watch?v=" . $videoId;
                } else if (strpos($videoUrl, 'youtu.be/') !== false) {
                    // Convert short URL to watch URL
                    $videoId = $this->extractVideoIdFromShort($videoUrl);
                    $videoUrl = "https://www.youtube.com/watch?v=" . $videoId;
                }
            }

            Log::info('Active video retrieved', [
                'id' => $activeVideo->id_antarmuka,
                'source_type' => $sourceType,
                'original_sumber' => $activeVideo->sumber,
                'processed_url' => $videoUrl,
                'volume' => $activeVideo->volume
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Active video retrieved successfully',
                'video_url' => $videoUrl,
                'video_data' => [
                    'id' => $activeVideo->id_antarmuka,
                    'nama' => $activeVideo->nama,
                    'keterangan' => $activeVideo->keterangan,
                    'durasi_video' => $activeVideo->durasi_video,
                    'volume' => $activeVideo->volume ?? 50,
                    'status' => $activeVideo->status,
                    'source_type' => $sourceType,
                    'is_local_file' => $sourceType === 'upload',
                    'original_sumber' => $activeVideo->sumber,
                    'formatted_duration' => $activeVideo->formatted_duration ?? null
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error retrieving active video', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error retrieving active video: ' . $e->getMessage(),
                'video_url' => null,
                'video_data' => null
            ], 500);
        }
    }

    /**
     * Determine source type from URL/path
     */
    private function getSourceType($sumber)
    {
        if (strpos($sumber, 'youtube.com') !== false || strpos($sumber, 'youtu.be') !== false) {
            return 'youtube';
        } elseif (strpos($sumber, 'vimeo.com') !== false) {
            return 'vimeo';
        } elseif (filter_var($sumber, FILTER_VALIDATE_URL)) {
            return 'url';
        } else {
            return 'upload';
        }
    }

    /**
     * Update video volume (if needed for real-time updates)
     */
    public function updateVideoVolume(Request $request, $id)
    {
        try {
            $request->validate([
                'volume' => 'required|integer|min:0|max:100'
            ]);

            $antarmuka = Antarmuka::findOrFail($id);
            $antarmuka->update(['volume' => $request->volume]);

            return response()->json([
                'success' => true,
                'message' => 'Volume updated successfully',
                'data' => [
                    'id' => $antarmuka->id_antarmuka,
                    'volume' => $antarmuka->volume
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating volume: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate proper embed URL from various video sources
     */
    private function generateEmbedUrl($url)
    {
        // YouTube watch URL
        if (preg_match('/youtube\.com\/watch\?v=([^&]+)/', $url, $matches)) {
            $videoId = $matches[1];
            return "https://www.youtube.com/embed/{$videoId}?autoplay=1&mute=1&loop=1&controls=0&modestbranding=1&playsinline=1&rel=0&enablejsapi=1&playlist={$videoId}";
        }

        // YouTube embed URL (add parameters)
        if (preg_match('/youtube\.com\/embed\/([^?]+)/', $url, $matches)) {
            $videoId = $matches[1];
            return "https://www.youtube.com/embed/{$videoId}?autoplay=1&mute=1&loop=1&controls=0&modestbranding=1&playsinline=1&rel=0&enablejsapi=1&playlist={$videoId}";
        }

        // YouTube short URL
        if (preg_match('/youtu\.be\/([^?]+)/', $url, $matches)) {
            $videoId = $matches[1];
            return "https://www.youtube.com/embed/{$videoId}?autoplay=1&mute=1&loop=1&controls=0&modestbranding=1&playsinline=1&rel=0&enablejsapi=1&playlist={$videoId}";
        }

        // Vimeo URL
        if (preg_match('/vimeo\.com\/(\d+)/', $url, $matches)) {
            $videoId = $matches[1];
            return "https://player.vimeo.com/video/{$videoId}?autoplay=1&muted=1&loop=1&title=0&byline=0&portrait=0";
        }

        // Direct video file or other URLs
        return $url;
    }

    /**
     * Get all videos list
     */
    public function getAllVideos()
    {
        try {
            $videos = Antarmuka::orderBy('created_at', 'desc')->get();

            return response()->json([
                'success' => true,
                'videos' => $videos
            ]);

        } catch (\Exception $e) {
            Log::error('Get all videos error', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil daftar video'
            ], 500);
        }
    }

    /**
     * Extract video ID from YouTube embed URL
     */
    private function extractVideoIdFromEmbed($url)
    {
        if (preg_match('/youtube\.com\/embed\/([^?\/?]+)/', $url, $matches)) {
            return $matches[1];
        }
        return 'tXWuQbGTfxM'; // fallback
    }

    /**
     * Extract video ID from YouTube short URL
     */
    private function extractVideoIdFromShort($url)
    {
        if (preg_match('/youtu\.be\/([^?\/?]+)/', $url, $matches)) {
            return $matches[1];
        }
        return 'tXWuQbGTfxM'; // fallback
    }
}
