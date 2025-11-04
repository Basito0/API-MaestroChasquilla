<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class User
 * 
 * @property int $user_id
 * @property string $first_name
 * @property string $last_name
 * @property string $phone_number
 * @property string $address
 * @property string $email
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $profile_path
 * @property int $score
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|Client[] $clients
 * @property Collection|Review[] $reviews
 * @property Collection|Worker[] $workers
 *
 * @package App\Models
 */
class User extends Authenticatable
{
	use Notifiable;
	protected $table = 'users';
	protected $primaryKey = 'user_id';

	protected $casts = [
		'email_verified_at' => 'datetime',
		'score' => 'int'
	];

	protected $hidden = [
		'password',
		'remember_token'
	];

	protected $fillable = [
		'first_name',
		'last_name',
		'phone_number',
		'address',
		'email',
		'email_verified_at',
		'password',
		'profile_path',
		'score',
		'remember_token'
	];

	public function clients()
	{
		return $this->hasMany(Client::class);
	}

	public function reviews()
	{
		return $this->hasMany(Review::class, 'reviewer_id');
	}

	public function workers()
	{
		return $this->hasMany(Worker::class);
	}
}
