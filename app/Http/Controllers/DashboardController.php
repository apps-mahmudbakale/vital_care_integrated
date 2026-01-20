<?php

namespace App\Http\Controllers;

use Jajo\NG;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('dashboard');
    }

    public function getLga($state)
    {
        $ng = new NG();
        $Lgas = $ng->getLGA($state);

        return response()->json($Lgas);
    }
}

