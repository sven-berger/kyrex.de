<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AppCategories extends Model
{
    use HasFactory;

    protected $table = 'app_categories';

    protected $fillable = [
        'name',
        'value',
        'area',
        'sort_order',
    ];

    public function pages(): HasMany
    {
        return $this->hasMany(AppPages::class, 'category_id');
    }
}
