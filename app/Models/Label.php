<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Label extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'color'
    ];

    public function cards()
    {
        return $this->belongsToMany(Card::class,'label_has_card', 'label_id', 'card_id');
    }

}
