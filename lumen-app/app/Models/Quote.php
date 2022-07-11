<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Quote extends Model
{
	use SoftDeletes;
	
	protected $fillable = [
		'quote',
		'character',
		'image',
		'characterDirection',
	];
	
}
