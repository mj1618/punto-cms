<?php namespace App\AUI\Model;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class RoleUser extends Model implements AuthenticatableContract, CanResetPasswordContract {
    use Authenticatable, CanResetPassword;
    protected $table = 'role_user';
    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo('App\AUI\Model\User');
    }
    public function role()
    {
        return $this->belongsTo('App\AUI\Model\Role');
    }
}
