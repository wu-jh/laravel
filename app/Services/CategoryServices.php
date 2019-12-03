<?php

namespace App\Services;

use App\Models\Category;

class CategoryServices
{
     //添加商品分类
    public function create(array $data)
    {
        $category = new Category();
        $category->name = $data['name'];
        $category->property = json_encode(explode(',',$data['property']));
        $category->sort = $data['sort'];
        $category->status = $data['status'];
        if(!$category->save()){
            return null;
        }
        return $category;
    }

     //查询单个商品分类
    public function find($id)
    {
        $result = (new Category())->where([['id',$id],['status','!=',config('const.DELETE')]])->get();
        if(!$result->toArray()){
            return null;
        }

        return $result;
    }


     //查询所有商品分类
    public function select()
    {
        $result = (new Category())->where('status','!=',config('const.DELETE'))->orderBy('id', 'desc')->paginate(10);
        return $result;
    }

    //获取分类名称
    public function query(){
        $result = (new Category())->where('status','=',1)->select('id','name','property')->get();
        return $result;
    }

    //删除商品分类
    public function delete($id)
    {
        $category = new Category();
        $result = $category->where('id',$id)->update(['status' => config('const.DELETE')]);
        if(!$result){
            return null;
        };
        return $result;
    }

    //修改商品分类
    public function edit($id,$arr)
    {
        $result = (new Category())->where('id',$id)->update($arr);
        if(!$result){
            return null;
        };
        return $result;
    }


}
