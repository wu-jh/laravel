<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\CategoryServices;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    //新增商品分类
    public function create(Request $request)
    {
        //数据验证
        $status = array_keys(Category::source());
        $validator = Validator::make($request->all(),[
            'name' => 'required|string',
            'property' => 'nullable|string',
            'sort' => 'integer',
            'status' => [Rule::in($status)],
        ],[
            'required' => ':attribute为必填项',
            'filled' => ':attribute不能为空',
            'integer' => ':attribute必须是整数',
            'in' => ':attribute类型错误',
        ],[
            'name' => '分类名称',
            'property' => '分类属性',
            'sort' => '排序值',
            'status' => '状态',
        ]);

        if($validator->fails()){
            return response()->json([
                'status'=>false,
                'data'=>$validator->errors()->first()
            ]);
        }

        //获取参数
        $arr['name'] = $request->input('name');
        $arr['property'] = $request->input('property',null);
        $arr['sort'] = $request->input('sort',0);
        $arr['status'] = $request->input('status','1');
        $result = (new CategoryServices())->create($arr);
        //添加失败
        if(!$result){
            return response()->json([
                'status'=>false,
                'data' => '添加失败'
            ]);
        }

        return response()->json([
            'status' => true,
            'data' => '添加成功'
        ]);
    }

    //查询单个商品分类
    public function find(Request $request)
    {
        //接收数据
        $id = $request->input('id');
        $result = (new CategoryServices())->find($id);
        if(!$result){
            return response()->json([
                'status' => false,
                'data' => '分类id;不存在',
            ]);
        }

        foreach ($result as $res){
            $res->property = json_decode($res->property);
        }
        return response()->json([
                'status' => true,
                'data' => $result,
            ]);

    }

    //查询所有分类
    public function select(Request $request)
    {
        $result = (new CategoryServices())->select();
        foreach ($result as $res){
            $res->property = json_decode($res->property);
        }
        return response()->json([
            'status' => true,
            'data' => $result
        ]);
    }

    //获取分类名称
    public function query(Request $request){
        $result = (new CategoryServices())->query();
        return response()->json([
            'status' => true,
            'data' => $result
        ]);
    }

    //删除分类
    public function delete(Request $request)
    {
        //接收数据
        $id = $request->input('id');
        $result = (new CategoryServices())->delete($id);
        if(!$result){
            return response()->json([
                'status' => false,
                'data' => '分类id不存在'
            ]);
        }

        return response()->json([
                'status' => true,
                'data' => '删除成功'
            ]);
    }

    //编辑分类
    public function edit(Request $request)
    {
        //验证数据
        $status = array_keys(Category::source());
        $validator = Validator::make($request->all(),[
            'name' => 'filled|string',
            'property' => 'nullable|string',
            'sort' => 'integer',
            'status' => [Rule::in($status)],
        ],[
            'filled' => ':attribute不能为空',
            'integer' => ':attribute必须是整数',
            'in' => ':attribute类型错误',
        ],[
            'name' => '分类名称',
            'property' => '分类属性',
            'sort' => '排序值',
            'status' => '状态',
        ]);
        if($validator->fails()){
            return response()->json([
                'status'=>false,
                'data'=>$validator->errors()->first()
            ]);
        }
        //获取数据
        $id = $request->input('id');
        $arr = [];
        if($request->has('name')){
            $arr['name'] =  $request->input('name');
        }

        if($request->has('property')){
            $arr['property'] =  $request->input('property');
            if($arr['property']){
                $arr['property'] = json_encode(explode(',',$arr['property']));
            }
        }

        if($request->has('sort')){
            $arr['sort'] =  $request->input('sort');
        }

        if($request->has('status')){
            $arr['status'] =  $request->input('status');
        }
        $result = (new CategoryServices())->edit($id,$arr);
        if(!$result){
             return response()->json([
                'status'=>false,
                'data'=> '分类id不存在'
            ]);
        }

         return response()->json([
                'status'=> true,
                'data'=>'修改成功'
            ]);
    }
}
