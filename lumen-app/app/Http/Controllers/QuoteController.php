<?php

namespace App\Http\Controllers;

use App\Http\Resources\QuoteResourceCollection;
use App\Models\Quote;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;

class QuoteController extends Controller
{
	/**
	 * @param Request $request
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function index(Request $request)
	{
		try{
			$newQuoteError = false;
			$newQuote = $this->getTheSimpsonQuote();
			
			if(!is_array($newQuote)){
				$newQuoteError = true;
			}
			
			if(empty($newQuote)){
				$newQuoteError = true;
			}
			
			if(!$newQuoteError){
				$insertQuode = $this->insertQuote(reset($newQuote));
			}
			
			$fiveQuotes = Quote::orderBy('id', 'DESC')->take(5)->get();
			
			$idsToKeep = $fiveQuotes->pluck('id')->toArray();
			Quote::whereNotIn('id', $idsToKeep)->delete();
			
			return response()->json(new QuoteResourceCollection($fiveQuotes), 200);
			
		}
		catch(ModelNotFoundException $e){
			return response()->json(['statusCode' => 20000,
									 'status' => 'error',
									 'message' => 'Unexpected server errors!'
									], 500);
		}
	}
	
	/**
	 * @param $request
	 *
	 * @return bool
	 */
	public function insertQuote($request)
	{
		try{
			Quote::create([
							   'quote' => Crypt::encrypt($request['quote']),
							   'character' => Crypt::encrypt($request['character']),
							   'image' => Crypt::encrypt($request['image']),
							   'characterDirection' => Crypt::encrypt($request['characterDirection']),
							   'created_at' => DB::raw('NOW()'),
							   'updated_at' => DB::raw('NOW()')
						   ]);

			return true;
		}
		catch(ModelNotFoundException $e){
			return false;
		}
		
	}
	
	/**
	 * @return bool|mixed
	 */
	public function getTheSimpsonQuote() {
		try {
			$curl = curl_init();
			$url = 'https://thesimpsonsquoteapi.glitch.me/quotes?count=1';
			
			curl_setopt_array($curl, array(
				CURLOPT_URL => $url,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => '',
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 0,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => 'GET',
				CURLOPT_HTTPHEADER => [
					"User-Agent: lumen-app"
				]
			));
			
			$response = curl_exec($curl);
			$jsonData = json_decode($response, true);
			
			curl_close($curl);
			
			return $jsonData;
			
		} catch(ModelNotFoundException $e) {
			return false;
		}
	}
}
