<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    const CACHE_KEY = 'aiyra_settings';

    /** All settings as key => value (cached). */
    public static function allCached(): array
    {
        return Cache::rememberForever(self::CACHE_KEY, function () {
            if (!Schema::hasTable('settings')) {
                return [];
            }
            return static::query()->pluck('value', 'key')->toArray();
        });
    }

    public static function get(string $key, $default = null)
    {
        $all = static::allCached();
        return array_key_exists($key, $all) && $all[$key] !== null && $all[$key] !== '' ? $all[$key] : $default;
    }

    /** Decode a JSON setting into an array. */
    public static function json(string $key, array $default = []): array
    {
        $val = static::get($key);
        if (!$val) {
            return $default;
        }
        $decoded = json_decode($val, true);
        return is_array($decoded) ? $decoded : $default;
    }

    public static function put(string $key, $value): void
    {
        if (is_array($value)) {
            $value = json_encode(array_values($value), JSON_UNESCAPED_UNICODE);
        }
        static::updateOrCreate(['key' => $key], ['value' => $value]);
        Cache::forget(self::CACHE_KEY);
    }

    public static function putMany(array $data): void
    {
        foreach ($data as $k => $v) {
            if (is_array($v)) {
                $v = json_encode(array_values($v), JSON_UNESCAPED_UNICODE);
            }
            static::updateOrCreate(['key' => $k], ['value' => $v]);
        }
        Cache::forget(self::CACHE_KEY);
    }
}
