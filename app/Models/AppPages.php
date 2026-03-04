<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AppPages extends Model
{
    use HasFactory;

    protected $table = 'app_pages';

    protected $fillable = [
        'name',
        'url',
        'category_id',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(AppCategories::class, 'category_id');
    }
}
