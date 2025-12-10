<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Region extends Model
{
    use HasFactory;

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
