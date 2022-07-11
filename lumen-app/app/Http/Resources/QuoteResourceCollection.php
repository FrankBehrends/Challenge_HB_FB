<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Crypt;

class QuoteResourceCollection extends ResourceCollection
{
	
	/**
	 * VaccineResourceCollection constructor.
	 *
	 * @param mixed $resource
	 */
	public function __construct($resource)
	{
		parent::__construct($resource);
	}
	
	/**
	 * Transform the resource collection into an array.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return array
	 */
	public function toArray($request)
	{
		$all_data = [];
		
		foreach ($this as $key => $value){
			
			$data = [
				'id' => $value->id,
				'quote' => Crypt::decrypt($value->quote),
				'character' => Crypt::decrypt($value->character),
				'image' => Crypt::decrypt($value->image),
				'characterDirection' => Crypt::decrypt($value->characterDirection),
				'created_at' => Carbon::parse($value->created_at)->format('Y-m-d H:i:s'),
				'updated_at' => Carbon::parse($value->updated_at)->format('Y-m-d H:i:s')
			];
			
			array_push($all_data, $data);
		}
		
		return $all_data;
	}
}