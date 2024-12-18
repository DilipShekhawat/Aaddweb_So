<?php

namespace App\Services;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class DataStreamProcessorService
{
    /**
     * Undocumented function
     *
     * @param [type] $data
     * @return void
     */
    public function stream($data)
    {
        try {
            $stream=$data['stream'];
            $k=$data['k'];
            $top=$data['top'];
            $exclude=$data['exclude']?? [];
            // Generate cache key
            $cacheKey = $this->generateCacheKey($stream, $k, $top, $exclude);

            // Process the stream using sliding window
            $frequencies = [];
            $length = strlen($stream);

            // Check edge case
            if ($length < $k) {
                return [];
            }

            // Create sliding window
            for ($i = 0; $i <= $length - $k; $i++) {
                $subsequence = substr($stream, $i, $k);
                if (in_array($subsequence, $exclude)) {
                    continue;
                }

                $frequencies[$subsequence] = ($frequencies[$subsequence] ?? 0) + 1;
            }
            $result = [];
            foreach (array_slice($frequencies, 0, $top) as $subsequence => $count) {
                $result[] = [
                    'subsequence' => $subsequence,
                    'count' => $count,
                ];
            }
            // Cache the result
            Cache::put($cacheKey, $result, now()->addHours(24));
            // print_r(Cache::get($cacheKey));die;
            return $result;
        } catch (\Exception $e) {
            Log::info("DataStreamProcessorService->ERROR:" . $e->getMessage());
            return false;
        }

    }
    private function generateCacheKey($stream,$k,$top,$exclude)
    {
        return md5($stream . $k . $top . implode('', $exclude));
    }

}
