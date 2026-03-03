<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WissensPortalCategories extends Model
{
    use HasFactory;

    protected $table = 'wissensportal_categories';

    protected $fillable = [
        'name',
        'value',
    ];
}
