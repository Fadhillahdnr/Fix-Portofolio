<?php

namespace App\Services;

use Cloudinary\Cloudinary;
use Illuminate\Http\UploadedFile;

class CloudinaryMediaService
{
    public function upload(UploadedFile $file, string $folder = 'projects'): array
    {
        if (! config('cloudinary.cloud_url')) {
            throw new \RuntimeException('CLOUDINARY_URL belum dikonfigurasi.');
        }
        $cloudinary = new Cloudinary(config('cloudinary.cloud_url'));
        $type = str_starts_with((string) $file->getMimeType(), 'video/') ? 'video' : 'image';
        $result = $cloudinary->uploadApi()->upload($file->getRealPath(), ['folder' => config('cloudinary.folder').'/'.$folder, 'resource_type' => $type, 'use_filename' => false, 'unique_filename' => true, 'overwrite' => false]);

        return ['path' => $result['secure_url'], 'public_id' => $result['public_id'], 'media_type' => $type, 'width' => $result['width'] ?? null, 'height' => $result['height'] ?? null];
    }

    public function destroy(?string $publicId, string $type = 'image'): void
    {
        if (! $publicId || ! config('cloudinary.cloud_url')) {
            return;
        } (new Cloudinary(config('cloudinary.cloud_url')))->uploadApi()->destroy($publicId, ['resource_type' => $type, 'invalidate' => true]);
    }
}
