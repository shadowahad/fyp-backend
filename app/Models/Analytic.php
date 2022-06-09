<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Analytic extends Model
{
    use HasFactory;
    protected $fillable = [
        'product_name',
        'manufacturer',
        'price',
        'number_available_in_stock',
        'number_of_reviews',
        'average_review_rating',
        'sold',
        'entry_date',
        "file_id"
    ];
}
