<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    const HIDE = 0;
    const SHOW = 1;
    protected $table = 'pre_category';
    public $timestamps = true;
    public static function source()
    {
        return [
            self::HIDE => '隐藏',
            self::SHOW => '显示',
        ];
    }
}
