<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Advisor
 * 
 * @property int $cid
 * @property string $dept
 * @property string $sec
 * @property int $year
 * @property int $advisorid
 *
 * @package App\Models
 */
class Advisor extends Model
{
	protected $table = 'advisor';
	protected $primaryKey = 'cid';
	public $timestamps = false;

	protected $casts = [
		'year' => 'int',
		'advisorid' => 'int'
	];

	protected $fillable = [
		'dept',
		'sec',
		'year',
		'advisorid'
	];
}
