<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produit extends Model
{
    use HasFactory;
    protected $guarded = ['id', 'user_id'];
    public function category(){
        return $this->belongsToMany(Category::class);
    }
    public function collection(){
        return $this->belongsTo(Collection::class);
    }
    public function user(){
        return $this->belongsTo(User::class);
    }

}
