<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Moderators
 * 
 * @property int $mod_id
 * @property int|null $user_id
 * 
 * @property User|null $user
 *
 * @package App\Models
 */
class Moderator extends Model
{
	protected $table = 'moderators';
	protected $primaryKey = 'mod_id';
	public $timestamps = true;

	protected $casts = [
		'mod_id' => 'int'
	];

	protected $fillable = [
		'mod_id'
	];

	public function user() {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
	}
}
