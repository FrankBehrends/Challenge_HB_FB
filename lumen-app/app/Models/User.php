<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
	use SoftDeletes;
	
	protected $fillable = [
		'name',
		'password',
	];
}
