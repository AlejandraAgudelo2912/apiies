<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'photo'];

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($category) {
            $category->products()->delete();
        });
    }


    public function products() : HasMany{
        return $this->hasMany(Product::class);
    }
}
