<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    const HIDE = '0';
    const SHOW = '1';
    protected $table = 'pre_product';
    public $timestamps = true;
    public static function source()
    {
        return [
            self::HIDE => '下架',
            self::SHOW => '上架',
        ];
    }

    public function tag()
    {
        return $this->hasMany('App\Models\ProductTag');
    }

    public function sku()
    {
        return $this->hasMany('App\Models\ProductSku');
    }

    public function category()
    {
        return $this->belongsTo('App\Models\Category');
    }


}
