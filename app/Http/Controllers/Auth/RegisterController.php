<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use App\Models\Location;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
//use Illuminate\Auth\Events\Registered; //handles the import LUST-verification
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::VERIFY;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
        //$this->middleware('auth', ['except' => ['input', 'create', 'validator']]);
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
            'username' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    public function input()
    {
        $locations = Location::all()->sortBy('name');
        $regions = Location::distinct()->get(['region']);
	 	return view('auth.register',['regions'=> $regions, 'locations'=> $locations, 'layout'=>'input']);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        $location_id = NULL;
        if(isset($data['local_id'])) {
            $location_id = $data['local_id'];
        }elseif(isset($data['location_id'])) {
            $location_id = $data['location_id'];
        }

        return User::create([
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'phone' => $data['phone'],
            'location_id' => $location_id,
        ]);

        /*$user = User::find($data['id']);
        $user->first_name = $data['last_name'];
        $user->last_name = $data['first_name'];
        $user->phone = $data['phone'];
        $user->location_id = $data['location_id'];
        $user->save();
    
        $user = User::where('id', $data['id'])
            ->update([   
                'first_name' => $data['first_name'],
                'last_name' => $data['last_
                name'],
                'phone' => $data['phone'],
                'location_id' => $data['location_id']
        ]);*/
        
        return redirect('/about');  //never reach that point //redirect to verify by RouteServiceProvider

        //return response()->json(['message' => 'User registered successfully. Please check your email for verification.'], 201);
    }

}
