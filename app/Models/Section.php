<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Section
 * 
 * @property string $dept
 * @property string $sections
 *
 * @package App\Models
 */
class Section extends Model
{
	protected $table = 'sections';
	public $incrementing = false;
	public $timestamps = false;

	protected $fillable = [
		'dept',
		'sections'
	];
}
