<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ProductTag extends Model
{
    const HIDE = '0';
    const SHOW = '1';
    const VALIDITY = 1;
    const TIPS = 2;
    const SLOGAN = 3;
    protected $table = 'pre_product_tag';
    public $timestamps = true;
    public static function source()
    {
        return [
            self::HIDE => '隐藏',
            self::SHOW => '显示',
        ];
    }

    public static function type()
    {
        return [
            self::VALIDITY => '有效期',
            self::TIPS => '温馨提示',
            self::SLOGAN => '促销宣传语',
        ];
    }
}
