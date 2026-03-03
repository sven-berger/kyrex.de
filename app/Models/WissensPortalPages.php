<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class WissensPortalPages extends Model
{
    use HasFactory;

    protected $table = 'wissensportal_pages';

    protected $fillable = [
        'name',
        'url',
        'content',
        'snippet_1_title',
        'snippet_1',
        'snippet_2_title',
        'snippet_2',
        'snippet_3_title',
        'snippet_3',
        'snippet_4_title',
        'snippet_4',
        'snippet_5_title',
        'snippet_5',
        'category_id',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(WissensPortalCategories::class, 'category_id');
    }
}
