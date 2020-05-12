<?php

namespace Wikichua\Simplecontrolpanel\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Html\Builder;
use Illuminate\Support\Str;


class SocialiteController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth_admin', 'can:Access Admin Panel']);
        $this->middleware('intend_url')->only(['index', 'read']);
        $this->middleware('can:Read Socialite Users')->only(['index', 'read']);
        $this->middleware('can:Delete Socialite Users')->only('delete');
    }

    public function index(Builder $builder)
    {
        if (request()->ajax()) {
            $users = app(config('lap.models.socialite'))->with('roles');
            $datatable = datatables($users)
                ->editColumn('roles', function ($user) {
                    return $user->roles->sortBy('name')->implode('name', ', ');
                })
                ->editColumn('actions', function ($user) {
                    return view('lap::socialites.datatable.actions', compact('user'));
                })
                ->rawColumns(['actions']);

            return $datatable->toJson();
        }

        $html = $builder->columns([
            ['title' => 'Name', 'data' => 'name'],
            ['title' => 'Email Address', 'data' => 'email'],
            ['title' => '', 'data' => 'actions', 'searchable' => false, 'orderable' => false],
        ]);
        $html->setTableAttribute('id', 'users_datatable');

        return view('lap::socialites.index', compact('html'));
    }

    public function read($id)
    {
        $user = app(config('lap.models.socialite'))->findOrFail($id);

        return view('lap::socialites.read', compact('user'));
    }

    public function delete($id)
    {
        $user = app(config('lap.models.socialite'))->findOrFail($id);
        $user->delete();

        activity('Deleted User: ' . $user->name, $user->toArray());
        $flash = ['success', 'User deleted!'];

        if (request()->input('_submit') == 'reload_datatables') {
            return response()->json([
                'flash' => $flash,
                'reload_datatables' => true,
            ]);
        }
        else {
            flash($flash);

            return redirect()->route('admin.users');
        }
    }
}