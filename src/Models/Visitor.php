<?php

namespace LaravelShared\Core\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Visitor extends Model
{
    protected $fillable = [
        'xwms_id',
        'user_id',
        'session_id',
        'ip_address',
        'first_page',
        'app',
        'refer_url',
        'user_agent',
        'device',
        'browser',
        'browser_version',
        'os',
        'country',
        'city',
        'region',
        'location',
        'pages_visited',
        'session_duration',
        'is_new_visitor',
    ];

    protected $dates = [
        'updated_at',
        'created_at',
    ];

    protected $casts = [
        'location' => 'array',
    ];

    /**
     * Relatie naar de gebruiker die de subcategorie heeft aangemaakt.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
