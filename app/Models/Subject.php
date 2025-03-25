<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Subject
 * 
 * @property int $cid
 * @property string $sub1
 * @property int $sub1fac
 * @property string $sub1stdlist
 * @property string $sub2
 * @property int $sub2fac
 * @property string $sub2stdlist
 * @property string $sub3
 * @property int $sub3fac
 * @property string $sub3stdlist
 * @property string $sub4
 * @property int $sub4fac
 * @property string $sub4stdlist
 * @property string $sub5
 * @property int $sub5fac
 * @property string $sub5stdlist
 * @property string $sub6
 * @property int $sub6fac
 * @property string $sub6stdlist
 * @property string $sub7
 * @property int $sub7fac
 * @property string $sub7stdlist
 *
 * @package App\Models
 */
class Subject extends Model
{
	protected $table = 'subjects';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'cid' => 'int',
		'sub1fac' => 'int',
		'sub2fac' => 'int',
		'sub3fac' => 'int',
		'sub4fac' => 'int',
		'sub5fac' => 'int',
		'sub6fac' => 'int',
		'sub7fac' => 'int'
	];

	protected $fillable = [
		'cid',
		'sub1',
		'sub1fac',
		'sub1stdlist',
		'sub2',
		'sub2fac',
		'sub2stdlist',
		'sub3',
		'sub3fac',
		'sub3stdlist',
		'sub4',
		'sub4fac',
		'sub4stdlist',
		'sub5',
		'sub5fac',
		'sub5stdlist',
		'sub6',
		'sub6fac',
		'sub6stdlist',
		'sub7',
		'sub7fac',
		'sub7stdlist'
	];
}
