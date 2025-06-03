<?php

namespace App\Layers\Infrastructure\Service;

use App\Layers\Domain\ValueObject\Image\StoreImage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Imagick\Driver;
use Intervention\Image\ImageManager;

class StoreImageUploadService
{
    /**
     * @param StoreImage $image
     * @return string|null
     * @throws \Exception
     */
    public function putFileAs(
        StoreImage $image,
    ): ?string {
        if ($image->empty()) {
            return null;
        }
        if (!$image->hasFile() && $image->hasCurrentUrl()) {
            return $image->getCurrentUrl();
        }
        try {
            $disk = Storage::disk('s3');
            // 既にファイルが存在していたら削除する
            if ($disk->exists($image->getStoragePath())) {
                $disk->delete($image->getStoragePath());
            }

            $request_file = $image->getRequestFile();

            // ランダム文字列を用意
            $random = strtolower(Str::random(20));
            $timestamp = Carbon::now()->format('YmdHis');
            $url = $disk->putFileAs(
                path: $image->getStorageName(),
                file: $request_file,
                name: $timestamp . '-' . $random . '.' . $request_file->guessExtension(),
                options: 'public',
            );
            return $url === false ? null : env('AWS_URL') . '/' . env('AWS_BUCKET') . '/' . $url;
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            throw $e;
        }
    }

    /**
     * @param StoreImage $image
     * @return void
     * @throws \Exception
     */
    public function deleteFile(StoreImage $image): void
    {
        if (!$image->hasCurrentUrl()) {
            return;
        }

        try {
            $disk = Storage::disk('s3');
            if ($disk->exists($image->getStoragePath())) {
                $disk->delete($image->getStoragePath());
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            throw $e;
        }
    }
}
