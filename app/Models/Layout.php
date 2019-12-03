<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Layout extends Model
{
    const NAV = 1;
    const BANNER = 2;
    const ICON = 3;
    const BOUTIQUE = 4;
    const CATEGORY = 1;
    const PRODUCT = 2;
    const ACTIVITY = 3;
    const SHOP = 4;
    const HIDE = 0;
    const SHOW = 1;
    protected $table = 'pre_nav';
    public $timestamps = true;
    public static function name()
    {
        return [
            self::NAV => '导航',
            self::BANNER => 'banner',
            self::ICON => 'icon',
            self::BOUTIQUE => '精品推荐',
        ];
    }

    public static function link()
    {
        return [
            self::CATEGORY => '分类id',
            self::PRODUCT => '商品id',
            self::ACTIVITY => '活动id',
            self::SHOP => '店铺id',
        ];
    }

    public static function status()
    {
        return [
            self::HIDE => '隐藏',
            self::SHOW => '显示',
        ];
    }
}
