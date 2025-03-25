<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Faculty
 * 
 * @property int $fid
 * @property string $name
 * @property string $dept
 * @property string $role
 * @property string $password
 *
 * @package App\Models
 */
class Faculty extends Model
{
	protected $table = 'faculty';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'fid' => 'int'
	];

	protected $hidden = [
		'password'
	];

	protected $fillable = [
		'fid',
		'name',
		'dept',
		'role',
		'password'
	];
}
