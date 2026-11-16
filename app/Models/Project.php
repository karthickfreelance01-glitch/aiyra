<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Project extends Model
{
    protected $fillable = [
        'title', 'slug', 'category', 'status', 'location', 'client', 'area', 'year',
        'short_description', 'description', 'cover_image', 'is_featured', 'is_active', 'sort_order',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
    ];

    const STATUSES = ['completed' => 'Completed', 'ongoing' => 'Ongoing', 'upcoming' => 'Upcoming'];

    const CATEGORIES = ['Residential', 'Commercial', 'Villa', 'Apartment', 'Interior', 'Renovation', 'Industrial'];

    public function images()
    {
        return $this->hasMany(ProjectImage::class)->orderBy('sort_order')->orderBy('id');
    }

    public function enquiries()
    {
        return $this->hasMany(Enquiry::class);
    }

    public function scopeActive($q)
    {
        return $q->where('is_active', true);
    }

    public function scopeOrdered($q)
    {
        return $q->orderBy('sort_order')->latest();
    }

    public function getCoverUrlAttribute(): string
    {
        return img_url($this->cover_image, 'https://images.unsplash.com/photo-1541888946425-d81bb19240f5?w=1200&q=80');
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? ucfirst($this->status);
    }

    public static function uniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title) ?: 'project';
        $slug = $base;
        $i = 2;
        while (static::where('slug', $slug)->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $slug = $base . '-' . $i++;
        }
        return $slug;
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
