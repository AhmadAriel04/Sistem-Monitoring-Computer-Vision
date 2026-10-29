<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\CvSession;

class Employee extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'position',
        'photo',
        'status',
        'counter_code'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function surveys()
    {
        return $this->hasMany(Survey::class);
    }

    /**
 * Sesi Computer Vision milik pegawai.
 */
    public function cvSessions(): HasMany
    {
        return $this->hasMany(CvSession::class);
    }
}