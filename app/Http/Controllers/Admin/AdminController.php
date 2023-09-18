<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Admin;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{

  function check(Request $request){
      //Validate inputs
      $request->validate([
         'email'=>'required|email|exists:users,email',
         'password'=>'required|min:5|max:30'
      ],[
          'email.exists'=>'This email is not exists on users table',
      ]);

      $creds = $request->only('email','password');
      if( Auth::guard('admin')->attempt($creds) ){
          return redirect()->route('admin.home');
      }else{
          return redirect()->route('admin.login')->with('fail','Incorrect credentials');
      }
  }

  function logout(){
      Auth::guard('admin')->logout();
      return redirect('/');
  }
        
}

