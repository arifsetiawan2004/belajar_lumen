<?php

namespace App\Models;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Model implements AuthenticatableContract, AuthorizableContract, JWTSubject, MustVerifyEmail
{
    use Authenticatable, Authorizable, HasFactory, SoftDeletes;

    protected $table = 'user';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var string[]
     */
    protected $hidden = [
        'password',
    ];

    // /**
    //  * Get the identifier that will be stored in the subject claim of the JWT.
    //  */
    public function getJWTIdentifier()
    {
        return $this->getKey(); // Mengembalikan primary key pengguna
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     */
    public function getJWTCustomClaims()
    {
        return []; // Tambahkan klaim kustom jika diperlukan
    }

    /** 
     * Determine if the user has verified their email address.
     * 
     * @return bool
    */
    public function hasVerifiedEmail() {
        if ($this->email_verified_at)
            return TRUE;
        return FALSE;
    }

    /**
     * Mark the given user's email as verified.
     * 
     * @return bool
     */
    
    public function markEmailAsVerified()
    {
        $this->email_verified_at = date('Y-m-d H:i:s');
        $this->save();
    }

    /**
     * Send the email verification notifiacation.
     * 
     * @return void
     */
    public function sendEmailVerificationNotification(){
        // Kirim email
    }

    /**
     * Get the email address that should be used for verification.
     * 
     * @return string
     */
    public function getEmailForVerification()
    {
        return $this->email;
    }

}
