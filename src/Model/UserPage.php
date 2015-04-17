<?php namespace App\AUI\Model;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class UserPage extends Model{

    protected $table = 'user_page';

    public function user()
    {
        return $this->belongsTo('App\AUI\Model\User');
    }
    public function page()
    {
        return $this->belongsTo('App\AUI\Model\Page');
    }
}
