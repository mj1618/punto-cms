<?php namespace App\AUI\Model;

use DateTime;
use DateTimeZone;
use Illuminate\Database\Eloquent\Model;
class StoreProduct extends Model {

    protected $table = 'store_product';

    public function storeType()
    {
        return $this->belongsTo('App\AUI\Model\StoreType');
    }
    public function storeSelect()
    {
        return $this->belongsTo('App\AUI\Model\StoreSelect');
    }
}
