<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class ShopRep extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    protected $guarded = ['id'];

    protected $table = 'shop_reps';

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
