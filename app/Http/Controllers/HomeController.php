<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use App\UserType;

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
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $userTypeId = Auth::user()->user_type_id;
        $userType = UserType::where('id', $userTypeId)->first()->type;

        if ($userType === 'admin') {
            return redirect()->route('moderator.index');
        } elseif ($userType === 'moderator') {
            return redirect()->route('candidate.list');
        } elseif ($userType === 'candidate') {
            return 'No Login <br><a href="/logout">logout</a>';
        } else {
            return redirect()->route('login');
        }
    }
}
