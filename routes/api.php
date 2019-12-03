<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//添加商品分类
Route::post('admin/category/create','Api\Admin\CategoryController@create');
//查询单个商品分类
Route::get('admin/category/find','Api\Admin\CategoryController@find');
//查询商品分类列表
Route::get('admin/category/select','Api\Admin\CategoryController@select');
//获取分类名称和id
Route::get('admin/category/query','Api\Admin\CategoryController@query');
//删除商品分类
Route::post('admin/category/delete','Api\Admin\CategoryController@delete');
//修改商品分类
Route::post('admin/category/edit','Api\Admin\CategoryController@edit');
//添加商品
Route::post('admin/product/create','Api\Admin\ProductController@create');
//查询单个商品
Route::get('admin/product/find','Api\Admin\ProductController@find');
//查询商品列表
Route::get('admin/product/select','Api\Admin\ProductController@select');
//删除商品
Route::post('admin/product/delete','Api\Admin\ProductController@delete');
//删除商品标签
Route::post('admin/product/deleteTag','Api\Admin\ProductController@deleteTag');
//修改商品
Route::post('admin/product/basicEdit','Api\Admin\ProductController@basicEdit');
//修改商品状态
Route::post('admin/product/status','Api\Admin\ProductController@status');
//修改商品标签
Route::post('admin/product/tagEdit','Api\Admin\ProductController@tagEdit');
//删除商品sku
Route::post('admin/product/deleteSku','Api\Admin\ProductController@deleteSku');
//修改sku
Route::post('admin/product/skuEdit','Api\Admin\ProductController@skuEdit');
//添加菜单导航
Route::post('admin/layoutAdd','Api\Admin\LayoutController@add');
//查询菜单导航
Route::get('admin/layoutQuery','Api\Admin\LayoutController@query');
//查询单个
Route::get('admin/layoutFind','Api\Admin\LayoutController@find');
//删除菜单导航
Route::post('admin/layoutDel','Api\Admin\LayoutController@del');
//修改
Route::post('admin/layoutEdit','Api\Admin\LayoutController@edit');
//添加图片
Route::post('/img','Api\Admin\ImgController@add');
