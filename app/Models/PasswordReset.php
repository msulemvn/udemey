<?php

namespace App\Models;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PasswordReset extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'password_resets';
    protected $primaryKey = 'email';
    public $incrementing = false;
    public $timestamps = false;
    protected $fillable = ['email', 'token', 'created_at'];

    public static function createToken($email, $token)
    {
        return self::updateOrCreate(
            ['email' => $email],
            ['token' => $token, 'created_at' => Carbon::now()]
        );
    }

    /**
     * This mutator ensures that passwords are securely stored in the database.
     *
     * @return void
     */
    public function setTokenAttribute($value)
    {
        $this->attributes['token'] = Hash::make($value);
    }
}
