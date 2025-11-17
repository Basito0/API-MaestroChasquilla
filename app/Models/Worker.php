<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Worker
 * 
 * @property int $worker_id
 * @property int|null $user_id
 * 
 * @property User|null $user
 * @property Collection|WorkerRequest[] $worker_requests
 * @property Collection|Work[] $works
 *
 * @package App\Models
 */
class Worker extends Model
{
	protected $table = 'workers';
	protected $primaryKey = 'worker_id';
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

	public function worker_requests()
	{
		return $this->hasMany(WorkerRequest::class);
	}

	public function works()
	{
		return $this->hasMany(Work::class);
	}

	public function categories()
    {
        return $this->belongsToMany(
            Category::class,        // modelo relacionado
            'worker_categories',    // tabla pivote
            'worker_id',            // FK del modelo actual en la pivote
            'category_id'           // FK del modelo relacionado en la pivote
        );
    }



}
