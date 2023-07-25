<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CookingProcess extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'process',
    ];

    //一対多
    //Recipeモデルとの関係を定義し、このレシピが所有する料理工程とわかるようにする
    public function recipe()
    {
        return $this->belongsTo(Recipe::class);
    }
}
