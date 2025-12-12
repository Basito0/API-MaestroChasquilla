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
	public $timestamps = true;

	protected $casts = [
		'client_id' => 'int',
		'budget' => 'int',
		'category_id' => 'int',
		'selected_worker_id' => 'int',
        'selected_work_id' => 'int'
	];

	protected $fillable = [
		'client_id',
		'title',
		'description',
		'budget',
		'address',
		'category_id',
		'selected_worker_id',
        'selected_work_id'
	];

	public function client() {
        return $this->belongsTo(Client::class, 'client_id', 'client_id');
    }

	public function works()
	{
		return $this->hasMany(Work::class, 'client_request_id', 'client_request_id');
	}

	public function category()
	{
		return $this->belongsTo(Category::class, 'category_id', 'category_id');
	}

	//maestro seleccionado
    public function selectedWorker()
    {
        return $this->belongsTo(Worker::class, 'selected_worker_id', 'worker_id');
    }

    //work seleccionado
    public function selectedWork()
    {
        return $this->belongsTo(Work::class, 'selected_work_id', 'work_id');
    }
}
