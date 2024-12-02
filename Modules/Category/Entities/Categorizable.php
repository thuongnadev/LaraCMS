<?php

namespace Modules\Category\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categorizable extends Model
{
    protected $table = 'categorizables';

    protected $fillable = [
        'category_id',
        'categorizable_id',
        'categorizable_type',
    ];

    // Định nghĩa quan hệ với Category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Định nghĩa quan hệ đa hình với các mô hình khác (Post, Product, etc.)
    public function categorizable()
    {
        return $this->morphTo();
    }
}