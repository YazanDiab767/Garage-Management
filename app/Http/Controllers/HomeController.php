<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Customer;
use \App\Part;

class HomeController extends Controller
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
        $customers = Customer::all();
        $parts = Part::all();

        return view('home',[
            'customers' => $customers,
            'parts' => $parts
        ]);
    }
}
