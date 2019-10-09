<?php

namespace Wikichua\Simplecontrolpanel\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ApiController extends Controller
{
	public function __construct()
	{
		$this->middleware(['api_logger']);
		$this->middleware(['api_constructor'])->except(['auth']);
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
