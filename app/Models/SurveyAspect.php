<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SurveyAspect extends Model
{
    protected $fillable = [
        'name',
        'icon',
        'status'
    ];
}