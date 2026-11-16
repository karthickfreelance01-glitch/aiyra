<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enquiry extends Model
{
    protected $fillable = ['name', 'phone', 'email', 'service', 'location', 'message', 'source', 'project_id', 'is_read', 'ip'];

    protected $casts = ['is_read' => 'boolean'];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function scopeUnread($q)
    {
        return $q->where('is_read', false);
    }
}
