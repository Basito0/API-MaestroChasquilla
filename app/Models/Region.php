<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    protected $table = 'regions';
    protected $primaryKey = 'region_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = ['name'];

    public function communes()
    {
        return $this->hasMany(Commune::class, 'region_id', 'region_id');
    }
}
