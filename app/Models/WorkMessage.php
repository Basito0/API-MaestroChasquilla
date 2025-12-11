<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkMessage extends Model
{
    protected $primaryKey = 'message_id';

    protected $fillable = [
        'conversation_id', 
        'sender_id', 
        'content'
    ];

    public function conversation()
    {
        return $this->belongsTo(WorkConversation::class, 'conversation_id', 'conversation_id');
    }
    
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id', 'user_id');
    }
}
