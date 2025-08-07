<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Media extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
       'path',
       'description',
    ];

    /**
     * Get the scans associated with the media.
     */
    public function scans(): HasMany
    {
        return $this->hasMany(Scan::class);
    }
}
