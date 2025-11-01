<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class WorkerRequest
 * 
 * @property int $worker_request_id
 * @property int|null $worker_id
 * @property string $title
 * @property string $description
 * 
 * @property Worker|null $worker
 *
 * @package App\Models
 */
class WorkerRequest extends Model
{
	protected $table = 'worker_requests';
	protected $primaryKey = 'worker_request_id';
	public $timestamps = false;

	protected $casts = [
		'worker_id' => 'int'
	];

	protected $fillable = [
		'worker_id',
		'title',
		'description'
	];

	public function worker()
	{
		return $this->belongsTo(Worker::class);
	}
}
