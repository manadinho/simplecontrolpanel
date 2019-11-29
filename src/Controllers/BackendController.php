<?php

namespace Wikichua\Simplecontrolpanel\Controllers;

use App\Http\Controllers\Controller;

class BackendController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth_admin', 'can:Access Admin Panel'])->except('index');
        $this->middleware('can:Update Settings')->only(['settingsForm', 'settings']);
    }
    public function index()
    {
        return redirect()->route('admin.' . (auth()->check() ? 'dashboard' : 'login'));
    }
    public function locale($locale = 'en')
    {
        \App::setLocale($locale);
        return back()->with(['flash' => ['success', 'Locale Changed to '.$locale.'!']]);
    }
    public function dashboard()
    {
        return view('lap::backend.dashboard');
    }
    public function summernoteImageUpload()
    {
        $path = request()->file('file')->store('public/summernote/images');
        $path = str_replace('public', 'storage', $path);
        return str_replace(['http://','https://'], '//', asset($path));
    }
    public function view_logs()
    {
        $logViewer = new LogViewerController;
        return $logViewer->index();
    }
}

class LogViewerController extends \Rap2hpoutre\LaravelLogViewer\LogViewerController
{
    protected $view_log = 'lap::backend.log';
}