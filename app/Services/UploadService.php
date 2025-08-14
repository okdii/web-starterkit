<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class UploadService
{
    /**
     * Upload a file to a given disk and path.
     *
     * @param  UploadedFile  $file
     * @param  string  $path
     * @param  string  $disk
     * @return string  File path
     */
    public function upload(UploadedFile $file, string $path = 'uploads', string $disk = 'public'): string
    {
        // Store and return the file path
        return $file->store($path, $disk);
    }

    /**
     * Delete a file from a disk.
     *
     * @param string $filePath
     * @param string $disk
     * @return bool
     */
    public function delete(string $filePath, string $disk = 'public'): bool
    {
        return Storage::disk($disk)->delete($filePath);
    }
}
