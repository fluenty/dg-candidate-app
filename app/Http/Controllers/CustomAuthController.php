<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\User;
use Auth;
use Mail;
use Config;
use Crypt;
use Session;
use Redirect;
use Validator;
use Carbon\Carbon;

class CustomAuthController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function replaceAuth($user_id)
    {
        // If user exists
        $user = User::where('id', $user_id)->get();
        if($user->count()){
            $user = User::where('id', $user_id)->first();
            // Log out as Admin
            Auth::logout();
            // Log in
            Auth::login($user);
            // Redirect to home
            return redirect()->route('home');
        }
        // If no user exists
        return redirect()->back()->with(['error' => 'No user!']);
    }
}
