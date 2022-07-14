<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Analytic extends Model
{
    use HasFactory;
    protected $dates  = [
        'entry_date'
    ];
    protected $fillable = [
        'product_name',
        'manufacturer',
        'price',
        'number_available_in_stock',
        'number_of_reviews',
        'average_review_rating',
        'entry_date',
        "file_id",
        "sellingPrice",
        "soldAmountProducts",
        "old_cus",
        "new_cus",
        "pending_orders",
    ];
}