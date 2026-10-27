<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'category_id',
        'title',
        'slug' ,
        'sku' ,
        'base_price',
        'stock_quantity',
        'description',
    ];

    protected function casts(): array
    {
        return[
            'base_price'        => 'decimal:2',
            'stock_quantity'    => 'integer',        
        ];    
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
