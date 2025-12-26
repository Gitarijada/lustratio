<?php

namespace App\Http\Controllers;
use App\Providers\RouteServiceProvider;

class DeclineController extends Controller
{

    //** Where to redirect users after decline.
    protected $redirectTo = RouteServiceProvider::DECLINE;

    public function __construct()
    {
        $this->middleware('guest');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showpage()
    {     
        return view('decline');
    }

}
