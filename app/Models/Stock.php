<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;
    // protected $fillable = ['quantity'];
    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }

    public function product()
{
    return $this->belongsTo(Product::class);
}

}
