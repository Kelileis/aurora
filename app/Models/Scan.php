<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Scan extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'media_id',
        'configuration',
        'media_frames_data',
        'anomalies_data',
        'violations_data',
    ];

    /**
     * Get the media associated with the scan.
     */
    public function media(): HasOne
    {
        return $this->hasOne(Media::class);
    }
}
