<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkConversation extends Model
{
    protected $table = 'work_conversations';
    protected $primaryKey = 'conversation_id';

    protected $fillable = [
        'work_id', 
        'client_id', 
        'worker_id'
    ];


    public function work()
    {
        return $this->belongsTo(Work::class, 'work_id', 'work_id');
    }

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id', 'client_id');
    }

    public function worker()
    {
        return $this->belongsTo(Worker::class, 'worker_id', 'worker_id');
    }

    public function messages()
    {
        return $this->hasMany(WorkMessage::class, 'conversation_id', 'conversation_id');
    }
}