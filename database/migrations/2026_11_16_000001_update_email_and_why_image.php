<?php

use App\Models\Setting;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Cache;

return new class extends Migration
{
    private string $oldImage = 'https://images.unsplash.com/photo-1581094794329-c8112a89af12?w=1000&q=80';
    private string $newImage = 'https://images.unsplash.com/photo-1429497419816-9ca5cfb4571a?w=1000&q=80';

    public function up(): void
    {
        Setting::put('email', 'info@aiyraconstructions.in');

        // Replace the "Why choose us" image only if it is still the old demo photo
        $current = Setting::where('key', 'why_image')->value('value');
        if (!$current || $current === $this->oldImage) {
            Setting::put('why_image', $this->newImage);
        }

        Cache::forget(Setting::CACHE_KEY);
    }

    public function down(): void
    {
        Setting::put('email', 'info@aiyraconstructions.com');

        if (Setting::where('key', 'why_image')->value('value') === $this->newImage) {
            Setting::put('why_image', $this->oldImage);
        }

        Cache::forget(Setting::CACHE_KEY);
    }
};
