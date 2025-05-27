<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    protected $fillable = [
        'title',
        'description',
        'client_id',
        'deadline',
        'status',
    ];

    /**
     * Get the client that owns the Project
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Get all of the time_logs for the Project
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function time_logs(): HasMany
    {
        return $this->hasMany(TimeLog::class);
    }
}
