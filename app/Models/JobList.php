<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobList extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'card_id'
    ];

    public function jobListChilds(){
        return $this->hasMany(JobListChild::class, 'job_list_id');
    }
}
