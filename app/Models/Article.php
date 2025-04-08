<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Article extends Model
{
    protected $fillable = [
        'title',
        'description',
        'cover',
        'content',
        'category_id',
        'slug',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
