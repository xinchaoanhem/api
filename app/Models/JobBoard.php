<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobBoard extends Model
{
    use HasFactory;

    protected $table = 'job_boards';

    protected $fillable = [
        'title',
        'image',
        'user_id'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class,'job_board_has_user', 'job_board_id', 'user_id');
    }
}
