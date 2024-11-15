<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;



class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $cookieToken = $request->cookie('remember_token');
        $dbToken = $user ? $user->remember_token : null;
        $loggedInDuration = $user ? $user->getLoggedInDuration() : null;

        return view('app.index', compact('user', 'cookieToken', 'dbToken', 'loggedInDuration'));
    }
}
