<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $table = 'messages';
	protected $primaryKey = 'message_id';
	public $timestamps = false;

    protected $fillable = [
        'conversation_id',
        'sender_id',
        'content'
    ];

	public function conversation()
    {
        return $this->belongsTo(Conversation::class, 'conversation_id', 'conversation_id');
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id', 'user_id');
    }
}
