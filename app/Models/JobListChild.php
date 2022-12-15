<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobListChild extends Model
{
    use HasFactory;

    protected $table = 'job_list_childs';

    protected $fillable = [
        'title',
        'status',
        'job_list_id'
    ];
}
