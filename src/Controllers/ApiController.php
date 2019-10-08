<?php

namespace Wikichua\Simplecontrolpanel\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ApiController extends Controller
{
	public function __construct()
	{
		\Storage::append('api/requests'.date('Y-m').'.log', json_encode([date('Y-m-d H:i:s') => request()->all()]));
		if (!in_array(request()->route()->getName(), ['api.auth'])) {
			$api_token = explode(' ', request()->header('authorization'))[1];
			$this->user = app(config('auth.providers.users.model'))->query()->where('api_token',$api_token)->first();
		}
	}
    public function auth(Request $request)
	{
		$failed_response = ['status' => 'failed', 'error' => 'Invalid login credential.']; 
		if (!$request->header('authorization')) {
			return response()->json($failed_response);
		}
		list($username, $password) = explode(':', base64_decode(explode(' ', $request->header('authorization'))[1]));
		if (\Auth::once(['email' => strtolower($username), 'password' => strtolower($password)])) {
			return response()->json(['status' => 'success', 'apiToken' => auth('web')->user()->api_token]);
		}
		return response()->json($failed_response);
	}
}
