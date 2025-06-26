<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'author',
        'isbn',
        'description',
        'publisher',
        'publication_date',
        'pages',
        'language',
        'stock_quantity',
        'available_quantity',
        'location',
        'cover_image',
        'category_id',
    ];

    protected $casts = [
        'publication_date' => 'date',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function loans()
    {
        return $this->hasMany(Loan::class);
    }

    public function activeLoans()
    {
        return $this->hasMany(Loan::class)->where('status', 'active');
    }

    public function isAvailable()
    {
        return $this->available_quantity > 0;
    }

    public function scopeAvailable($query)
    {
        return $query->where('available_quantity', '>', 0);
    }
}
