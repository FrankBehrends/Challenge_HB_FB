<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserToken extends Model
{
	use SoftDeletes;
	
	protected $table = 'user_token';
	
	protected $fillable = [
		'user_id',
		'organization_id',
		'token',
		'valid_until'
	];
}
