<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $table = 'reviews';
    protected $primaryKey = 'review_id';
    public $timestamps = false;

    protected $fillable = [
        'reviewer_id',
        'reviewed_id',
        'work_id',
        'title',
        'description',
        'score'
    ];

    // quien realiza la reseña
    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id', 'user_id');
    }

    // quien recibe la reseña
    public function reviewed()
    {
        return $this->belongsTo(User::class, 'reviewed_id', 'user_id');
    }

    // a qué trabajo pertenece la reseña
    public function work()
    {
        return $this->belongsTo(Work::class, 'work_id', 'work_id');
    }
}
