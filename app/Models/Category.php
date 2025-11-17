<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $primaryKey = 'category_id';
    protected $fillable = ['name', 'description'];


    public function workers()
    {
        return $this->belongsToMany(
            Worker::class,
            'worker_categories',
            'category_id',   // FK de la categoría en la tabla pivote
            'worker_id'      // FK del trabajador en la tabla pivote
        );
    }
}


