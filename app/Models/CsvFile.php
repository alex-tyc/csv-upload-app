<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CsvFile extends Model
{
    protected $fillable = [
        "id",
        "product_title",
        "product_description",
        "style",
        "sanmar_mainframe_color",
        "size",
        "color_name",
        "piece_price",
    ];
}
