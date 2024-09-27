<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function areas()
    {
        return $this->belongsToMany(Area::class,'area_shop');
    }

    public function genres()
    {
        return $this->belongsToMany(Genre::class,'genre_shop');
    }
}
