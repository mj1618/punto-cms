<?php namespace App\AUI\Model;

use DateTime;
use DateTimeZone;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Zizaco\Entrust\Traits\EntrustUserTrait;
use File;
class StoreSubcategory extends Model {

    protected $table = 'store_subcategory';

    public function products(){
        return $this->belongsToMany('App\AUI\Model\StoreProduct','store_product_subcategory','store_subcategory_id','store_product_id');
    }
}
