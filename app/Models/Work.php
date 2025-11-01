<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Work
 * 
 * @property int $work_id
 * @property int|null $client_request_id
 * @property int|null $client_id
 * @property int|null $worker_id
 * @property string $state
 * 
 * @property Client|null $client
 * @property ClientRequest|null $client_request
 * @property Worker|null $worker
 *
 * @package App\Models
 */
class Work extends Model
{
	protected $table = 'works';
	protected $primaryKey = 'work_id';
	public $timestamps = false;

	protected $casts = [
		'client_request_id' => 'int',
		'client_id' => 'int',
		'worker_id' => 'int'
	];

	protected $fillable = [
		'client_request_id',
		'client_id',
		'worker_id',
		'state'
	];

	public function client()
	{
		return $this->belongsTo(Client::class);
	}

	public function client_request()
	{
		return $this->belongsTo(ClientRequest::class);
	}

	public function worker()
	{
		return $this->belongsTo(Worker::class);
	}
}
