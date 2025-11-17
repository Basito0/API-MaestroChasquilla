<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ClientRequest
 * 
 * @property int $client_request_id
 * @property int|null $client_id
 * @property string $title
 * @property string $description
 * @property int $budget
 * @property string $address
 * 
 * @property Client|null $client
 * @property Collection|Work[] $works
 *
 * @package App\Models
 */
class ClientRequest extends Model
{
	protected $table = 'client_requests';
	protected $primaryKey = 'client_request_id';
	public $timestamps = false;

	protected $casts = [
		'client_id' => 'int',
		'budget' => 'int'
	];

	protected $fillable = [
		'client_id',
		'title',
		'description',
		'budget',
		'address'
	];

	public function client() {
        return $this->belongsTo(Client::class, 'client_id', 'client_id');
    }

	public function works()
	{
		return $this->hasMany(Work::class);
	}
}
