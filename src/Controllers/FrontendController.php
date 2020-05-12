<?php

namespace Wikichua\Simplecontrolpanel\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FrontendController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:social');
    }

    public function index(Request $request)
    {
        return 'Hi '.Auth::guard('social')->user()->name.'<br>Welcome to Simple Control Panel. Click <a href="'.route('social.logout').'">here to logout</a>';
    }
}
