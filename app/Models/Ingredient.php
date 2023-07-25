<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'ingredient_name',
        'ingredient_quantity',
    ];

    //一対多
    //Recipeモデルとの関係を定義し、このレシピが所有する食材とわかるようにする
    public function recipe()
    {
        return $this->belongsTo(Recipe::class);
    }
}
