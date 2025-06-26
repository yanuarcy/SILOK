<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class RemoveBgService
{
    protected $client;
    protected $apiKey;

    public function __construct()
    {
        $this->client = new Client();
        $this->apiKey = config('services.remove_bg.api_key');
    }

    public function removeBackground($imagePath, $size = 'auto')
    {
        try {
            if (!$this->apiKey) {
                throw new \Exception('Remove.bg API key not configured');
            }

            // Get full path to the image
            $fullPath = Storage::disk('public')->path($imagePath);

            if (!file_exists($fullPath)) {
                throw new \Exception('Image file not found: ' . $fullPath);
            }

            $response = $this->client->post('https://api.remove.bg/v1.0/removebg', [
                'multipart' => [
                    [
                        'name'     => 'image_file',
                        'contents' => fopen($fullPath, 'r')
                    ],
                    [
                        'name'     => 'size',
                        'contents' => $size
                    ]
                ],
                'headers' => [
                    'X-Api-Key' => $this->apiKey
                ]
            ]);

            // Generate new filename with _no_bg suffix
            $pathInfo = pathinfo($imagePath);
            $newFileName = $pathInfo['dirname'] . '/' . $pathInfo['filename'] . '_no_bg.png';

            // Save the processed image
            $processedImagePath = Storage::disk('public')->path($newFileName);
            $fp = fopen($processedImagePath, "wb");
            fwrite($fp, $response->getBody());
            fclose($fp);

            // Delete original image
            Storage::disk('public')->delete($imagePath);

            Log::info('Successfully removed background from image: ' . $imagePath);

            return $newFileName;

        } catch (RequestException $e) {
            Log::error('Remove.bg API error: ' . $e->getMessage());

            // If API fails, return original image path
            return $imagePath;

        } catch (\Exception $e) {
            Log::error('Remove background error: ' . $e->getMessage());

            // If processing fails, return original image path
            return $imagePath;
        }
    }

    public function isConfigured()
    {
        return !empty($this->apiKey);
    }
}
