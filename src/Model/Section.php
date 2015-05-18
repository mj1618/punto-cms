<?php namespace App\AUI\Model;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

use Illuminate\Database\Eloquent\SoftDeletes;
use Zizaco\Entrust\Traits\EntrustUserTrait;

class Section extends Model {

    protected $table = 'section';

    function template(){
        return $this->belongsTo('App\AUI\Model\Template');
    }
    function items(){
        return $this->hasMany('App\AUI\Model\Item');
    }

    function getHasAttachmentsFormattedAttribute(){
        return $this->has_attachments === 1 || $this->has_attachments === '1'?'Yes':'No';
    }
    function getSingleFormattedAttribute(){
        return $this->single === 1||$this->single === '1'?'Yes':'No';
    }

    protected $appends = array('has_attachments_formatted','single_formatted');
}
