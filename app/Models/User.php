<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

//use Illuminate\Auth\Password\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;

//class User extends Authenticatable
class User extends Authenticatable implements MustVerifyEmail   //LUST-verification
{
    //use HasApiTokens, HasFactory, Notifiable;
    use HasApiTokens, HasFactory, Notifiable, Authorizable; //MY change add ,Authenticatable, Authorizable; // CanResetPassword;

    /**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['username', 'email', 'password', 
                        'first_name', 'last_name', 'phone', 'location_id', 'email_verified_at'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    //SERG added
    /*public function role() {
		return $this->hasMany( 'App\Models\Role' );
	}*/
   
    /*public function post() {
		return $this->hasMany( 'App\Post' );
	}*/

	/*public function equipment() {
		return $this->hasMany( 'App\Models\Equipment');
	}*/
	
	public function isAdministrator() {

		//return true;
		return (int) $this->admin == 1;
	}
    /**
	* http://laravel.com/docs/4.2/mail
	*/
	/*public function sendWelcomeEmail() {
        # Create an array of data, which will be passed/available in the view
        $data = array('user' => Auth::user());
        Mail::send('emails.welcome', $data, function($message) {
        $recipient_email = $this->email;
        $recipient_name = $this->first_name.' '.$this->last_name;
        $subject = 'Welcome '.$this->first_name.'!';
        $message->to($recipient_email, $recipient_name)->subject($subject);
        });
    }*/
    
    /**
     * Determine if the user has verified their email address.
     * //LUST-verification
     * @return bool
     */
    public function hasVerifiedEmail()  
    {
        return ! is_null($this->email_verified_at);
    }

    /**
     * Mark the given user's email as verified.
     * //LUST-verification
     * @return bool
     */
    public function markEmailAsVerified()
    {
        return $this->forceFill([
            'email_verified_at' => $this->freshTimestamp(),
        ])->save();
    }
}
