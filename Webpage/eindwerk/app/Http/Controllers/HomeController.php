<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Mail;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    
/*    public function __construct()
    {
        $this->middleware('auth');
    }*/

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }
    
    public function storeMail(Request $request)
    {
        
        DB::table('mails')->insert(
            ['email' => $request->email]
        );
        
        return redirect('/')->with('message', "Thanks for submitting, we'll keep you updated!");
    }
}