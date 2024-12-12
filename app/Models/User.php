<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;
use App\Models\booking;
use App\Models\visa_booking;
class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */


    // Check if the user has a specific role
   
    protected $fillable = [
       'company_name', 'company_persons_name', 'password', 'country','division','district','contact_no',
       'address','currency','email','balance','credit_balance','status','role'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
       'password', 'remember_token',
    ];

    public function hasRole($role)
    {
        return $this->role === $role;
    }

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [

            'id'              => $this->id,
            'name'            => $this->name,
            'email'           => $this->email,
           
        ];
    }

    private static $user;
    public static function saveUser($request){
        if ($request->id){
            self::$user=User::find($request->id);
        }else{
            self::$user=new User();
        }
        self::$user->name=$request->name;
        self::$user->email=$request->email;
        self::$user->password=bcrypt($request->password);
        self::$user->save();
    }


    public static function deleteUser($request){
        self::$user = User::find($request->id);
        self::$user->delete();
    }
public function isAdmin(){
    return $this->role===1;
}
public function isUser(){
    return $this->role===0;
}



public function bookings()
{
    return $this->hasMany(Booking::class);
}

public function visaBookings()
{
    return $this->hasMany(visa_booking::class, 'user_id');
}

}
