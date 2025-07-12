<?php

namespace LaravelShared\Core\Models;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $fillable = [
        'xwms_id',
        'user_id',
        'level',
        'category',
        'message',
        'context',
        'adress_data'
    ];

    protected $dates = [
        'updated_at',
        'created_at',
    ];

    protected $casts = [
        'adress_data' => 'array',
        'context' => 'array',
    ];

    /**
     * Relatie naar de gebruiker die de subcategorie heeft aangemaakt.
     */
    public function user()
    {
        return $this->belongsTo(UserFromXwms::class, 'user_id');
    }
}
