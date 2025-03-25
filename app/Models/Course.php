<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Course
 * 
 * @property string $subcode
 * @property string $subname
 * @property int $credits
 * @property string $dept
 * @property int $createdby
 *
 * @package App\Models
 */
class Course extends Model
{
	protected $table = 'courses';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'credits' => 'int',
		'createdby' => 'int'
	];

	protected $fillable = [
		'subcode',
		'subname',
		'credits',
		'dept',
		'createdby'
	];
}
