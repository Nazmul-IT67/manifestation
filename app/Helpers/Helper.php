<?php

use App\Models\SystemSetting;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


// get_setting
if (!function_exists('get_setting')) {
    function get_setting($key, $default = null)
    {
        return SystemSetting::where('key', $key)->value('value') ?? $default;
    }
}

// Set env 
if (!function_exists('setEnv')) {
    function setEnv(array $data)
    {
        $envPath = base_path('.env');
        $env = file_get_contents($envPath);

        foreach ($data as $key => $value) {
            if ($value === null) continue;

            $value = '"' . trim($value) . '"';

            if (preg_match("/^{$key}=.*/m", $env)) {
                $env = preg_replace("/^{$key}=.*/m", "{$key}={$value}", $env);
            } else {
                $env .= "\n{$key}={$value}";
            }
        }

        file_put_contents($envPath, $env);
        return true;
    }
}


/**
 * Upload a file to the specified folder within public directory.
 *
 * @param UploadedFile $file The uploaded file instance.
 * @param string $folder Folder path relative to the `public` directory.
 * @param string|null $customName Optional custom file name without extension.
 * @return string|null Relative file path or null on failure.
 */
function uploadFile(UploadedFile $file, string $folder, ?string $customName = null): ?string
{
    try {
        $folderPath = public_path($folder);

        if (! File::exists($folderPath)) {
            File::makeDirectory($folderPath, 0755, true);
        }

        $fileName = $customName
            ? $customName . '.' . $file->getClientOriginalExtension()
            : time() . '.' . $file->getClientOriginalExtension();

        $file->move($folderPath, $fileName);

        return '/' . trim($folder, '/') . '/' . $fileName;
    } catch (Exception $e) {

        return $e;
    }
}

/**
 * Delete a file from the public path or storage path.
 *
 * @param string|null $filePath Path to the file (relative to public/ or storage/)
 * @param bool $isPublic Whether the path is from the public folder (default: true)
 * @return bool True if deleted, false otherwise
 */
function deleteFile(?string $filePath, bool $isPublic = true): bool
{
    if (! $filePath) {
        return false;
    }

    try {
        $fullPath = $isPublic ? public_path($filePath) : storage_path($filePath);

        if (File::exists($fullPath)) {
            File::delete($fullPath);
            Log::info(message: "File deleted: {$fullPath}");
            return true;
        }
    } catch (\Exception $e) {
        Log::error("File deletion failed: {$filePath} - " . $e->getMessage());
    }

    return false;
}

/**
 * Delete multiple files from public or storage path.
 *
 * @param array $filePaths Array of file paths (relative to public/ or storage/)
 * @param bool $isPublic Whether the paths are from the public folder (default: true)
 * @return array An array with 'deleted' and 'failed' file paths
 */
function deleteFiles(array $filePaths, bool $isPublic = true): array
{
    $deleted = [];
    $failed  = [];

    foreach ($filePaths as $filePath) {
        if (! $filePath) {
            continue;
        }

        try {
            $fullPath = $isPublic ? public_path($filePath) : storage_path($filePath);

            if (File::exists($fullPath)) {
                File::delete($fullPath);
                $deleted[] = $filePath;
                Log::info("File deleted: {$fullPath}");
            } else {
                $failed[] = $filePath;
                Log::warning("File not found: {$fullPath}");
            }
        } catch (\Exception $e) {
            $failed[] = $filePath;
            Log::error("File deletion failed: {$filePath} - " . $e->getMessage());
        }
    }

    return [
        'deleted' => $deleted,
        'failed'  => $failed,
    ];
}

function getFileType(string $path): string
{
    $extension = pathinfo($path, PATHINFO_EXTENSION);

    return match (strtolower($extension)) {
        'jpg', 'jpeg', 'png', 'gif', 'webp' => 'image',
        'mp4', 'mov', 'avi', 'mkv' => 'video',
        'mp3', 'wav', 'ogg' => 'audio',
        'pdf', 'doc', 'docx', 'xls', 'xlsx', 'txt' => 'file',
        default => 'file',
    };
}

/**
 * Upload to Public Folder
 * Upload an image and return its URL.
 *
 * @param  \Illuminate\Http\UploadedFile  $image
 * @param  string  $directory
 * @return string
 */
function uploadImage($file, $folder)
{
    if (! $file->isValid()) {
        return null;
    }

    $imageName = Str::slug(time()) . rand() . '.' . $file->extension();
    $path      = public_path('uploads/' . $folder);
    if (! file_exists($path)) {
        mkdir($path, 0755, true);
    }
    $file->move($path, $imageName);
    return 'uploads/' . $folder . '/' . $imageName;
}

function generateCode(int $length = 6): string
{
    $letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $numbers = '0123456789';

    // Ensure at least 1 letter and 1 number
    $code = $letters[random_int(0, strlen($letters) - 1)]
        . $numbers[random_int(0, strlen($numbers) - 1)];

    // Fill remaining characters
    $all = $letters . $numbers;

    for ($i = 2; $i < $length; $i++) {
        $code .= $all[random_int(0, strlen($all) - 1)];
    }

    // Shuffle to avoid predictable positions
    return str_shuffle($code);
}
