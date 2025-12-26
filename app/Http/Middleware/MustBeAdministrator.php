<?php namespace App\Http\Middleware;

use Closure;

class MustBeAdministrator {

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next){ 	
		//If user is logged in and is an admin//
		//$user = $request->user();
		
		//if (auth()->$user && auth()->$user->role == 'admin') { 	//orginal
		//if (isset(Auth::user()->role_id) && Auth::user()->role_id == 1)	//same as dowm
		if (auth()->user() && auth()->user()->id === 1) { 	//'admin' MY change from auth()->user()->role_id == 1 to ...user()->id == 1

			return $next($request);
		}

		return redirect('/decline');
	}  
	

}
