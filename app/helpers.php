<?php

use App\Models\Setting;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

if (!function_exists('setting')) {
    function setting(string $key, $default = null)
    {
        return Setting::get($key, $default);
    }
}

if (!function_exists('setting_json')) {
    function setting_json(string $key, array $default = []): array
    {
        return Setting::json($key, $default);
    }
}

if (!function_exists('img_url')) {
    /** Turn a stored path (uploads/..) or a full URL into a usable URL. */
    function img_url(?string $path, ?string $fallback = null): string
    {
        if (!$path) {
            return $fallback ?? asset('assets/images/placeholder.jpg');
        }
        if (Str::startsWith($path, ['http://', 'https://', '//'])) {
            return $path;
        }
        return asset($path);
    }
}

if (!function_exists('upload_image')) {
    /** Save an uploaded image into public/uploads/{folder} and return its relative path. */
    function upload_image(UploadedFile $file, string $folder): string
    {
        $dir = public_path('uploads/' . $folder);
        File::ensureDirectoryExists($dir);
        $name = date('YmdHis') . '-' . Str::random(6) . '.' . strtolower($file->getClientOriginalExtension());
        $file->move($dir, $name);
        return 'uploads/' . $folder . '/' . $name;
    }
}

if (!function_exists('delete_upload')) {
    function delete_upload(?string $path): void
    {
        if ($path && Str::startsWith($path, 'uploads/') && File::exists(public_path($path))) {
            File::delete(public_path($path));
        }
    }
}

if (!function_exists('wa_link')) {
    function wa_link(?string $number, string $text = ''): string
    {
        $n = preg_replace('/\D+/', '', (string) $number);
        if (strlen($n) === 10) {
            $n = '91' . $n;
        }
        return 'https://wa.me/' . $n . ($text ? '?text=' . rawurlencode($text) : '');
    }
}
