<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Review
 * 
 * @property int $review_id
 * @property int|null $reviewer_id
 * @property int|null $reviewed_id
 * @property string $title
 * @property string $description
 * @property int $score
 * 
 * @property User|null $user
 *
 * @package App\Models
 */
class Review extends Model
{
	protected $table = 'reviews';
	protected $primaryKey = 'review_id';
	public $timestamps = false;

	protected $casts = [
		'reviewer_id' => 'int',
		'reviewed_id' => 'int',
		'score' => 'int'
	];

	protected $fillable = [
		'reviewer_id',
		'reviewed_id',
		'title',
		'description',
		'score'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'reviewer_id');
	}
}
