<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\VerifyUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;



class UserController extends Controller
{
    function create(Request $request){
        $request->validate([
            'name'=>'required',
            'email'=>'required|email|unique:users,email',
            'password'=>'required|min:5|max:30',
            'cpassword'=>'required|min:5|max:30|same:password'
        ],
        [
            'cpassword.required' =>'Confirm password please.',
            'cpassword.same' =>'Confirm password should be the same as password.'

        ]);
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = \Hash::make($request->password);
        $save = $user->save();
        $last_id = $user->id;
        $token = $last_id.hash('sha256',str::random(120));
        $verifyUrl = route('user.verify',['token'=>$token,'service'=>'Email_verification']);

        VerifyUser::create([
            'user_id'=>$last_id,
            'token'=>$token,
        ]);
        $massage = 'Dear'.$request->name.'Thanks for signing up';
        $mail_data = [
            'recipient' => $request->email,
            'fromEmail' => $request->email,
            'fromName' => $request->name,
            'subject' => 'Email verification',
            'body' => $massage,
            'actionLink' => $verifyUrl
        ];
        mail::send('email-template',$mail_data,function($massage) use($mail_data){
            $massage->to($mail_data['recipient'])->from($mail_data['fromEmail'], $mail_data['fromName'])
            ->subject($mail_data['subject']);
        });

        if( $save ){
            Auth::guard('web')->login($user);
            return redirect()->route('user.home')->with('success', 'Verify your Email');

            // return redirect()->back()->with('success','You are now registered successfully');
        }else{
            return redirect()->back()->with('fail','Something went wrong, failed to register');
        }
  }

  function check(Request $request){
      //Validate inputs
      $request->validate([
         'email'=>'required|email|exists:users,email',
         'password'=>'required|min:5|max:30'
      ],[
          'email.exists'=>'This email is not exists on users table'
      ]);

      $creds = $request->only('email','password');
      if( Auth::guard('web')->attempt($creds) ){
          return redirect()->route('user.home');
      }else{
          return redirect()->route('user.login')->with('fail','Incorrect credentials');
      }
  }

  function logout(){
      Auth::guard('web')->logout();
      return redirect('/');
  }
  function verify(Request $request){
    $token = $request->token;
    $verifyUser = VerifyUser::where('token',$token)->first();
    if(!is_null($verifyUser)){
        $user = $verifyUser->user;

        if(!$user->email_verified){
            $verifyUser->user->email_verified = 1;
            $verifyUser->user->save();
            return redirect()->route('user.login')
            ->with('info','your email is verified')
            ->with('VerifiedEmail',$user->email);
        }else{
            return redirect()->route('user.login')
            ->with('info','your email is already verified')
            ->with('VerifiedEmail',$user->email);
        }
    }
  }
        
}

