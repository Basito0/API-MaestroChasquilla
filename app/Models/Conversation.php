<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    protected $table = 'conversations';
	protected $primaryKey = 'conversation_id';
	public $timestamps = false;

	protected $casts = [
		'user_id' => 'int'
	];

	protected $fillable = [
		'user_id',
        'mod_id'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'user_id', 'user_id');
	}

	public function moderator()
	{
		return $this->belongsTo(Moderator::class, 'mod_id', 'mod_id');
	}

	public function messages()
    {
        return $this->hasMany(Message::class, 'conversation_id', 'conversation_id');
    }
}
