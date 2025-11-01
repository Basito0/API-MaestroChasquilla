<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Client
 * 
 * @property int $client_id
 * @property int|null $user_id
 * 
 * @property User|null $user
 * @property Collection|ClientRequest[] $client_requests
 * @property Collection|Work[] $works
 *
 * @package App\Models
 */
class Client extends Model
{
	protected $table = 'clients';
	protected $primaryKey = 'client_id';
	public $timestamps = false;

	protected $casts = [
		'user_id' => 'int'
	];

	protected $fillable = [
		'user_id'
	];

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function client_requests()
	{
		return $this->hasMany(ClientRequest::class);
	}

	public function works()
	{
		return $this->hasMany(Work::class);
	}
}
