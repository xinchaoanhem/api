<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    use HasFactory;

//    protected $table = 'cards';
//
//    protected $primaryKey = 'directory_id';

    protected $fillable = [
        'title',
        'description',
        'status',
        'index',
        'deadline',
        'directory_id'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class,'card_has_user', 'card_id', 'user_id');
    }

    public function labels()
    {
        return $this->belongsToMany(Label::class,'label_has_card', 'card_id', 'label_id');
    }

    public function files(){
        return $this->hasMany(File::class, 'card_id');
    }

    public function jobLists(){
        return $this->hasMany(JobList::class, 'card_id');
    }

    public function directory(){
        return $this->belongsTo(Directory::class);
    }
}
