<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;


    public function postLogin() {
        //validate the login credentials
        $rules = array (
            'email' => 'required|email', //ensures email is an actual email
            'password' => 'required|alphaNum|min:3' //password can only be alphanumeric and has to be greater than 3 characters
        );
        
        //run the validator on the inputs from form
        $login_validator = Validator::make(Input::all(),$rules);
        
        if($login_validator->fails()) {
            return Redirect::to('/auth/login')
                ->withErrors($login_validator)
                ->withInput(Input::except('password'));
        }
        else {
            $userdata = array(
                'email' => Input::get('email'),
                'password' => Input::get('password')
            );
        }
        
        if(Auth::attempt($userdata)) {
            //validation successful
            return Redirect::to('/api');
        }
        else {
            return Redirect::to('/auth/login');
        }
    }
    
    public function getLogout() {
        if(Auth::check()) {
            Auth::logout();
            Session::flush();
            return Redirect::to('/');    
        }
    }
    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/api';

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'fullname' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }

}
