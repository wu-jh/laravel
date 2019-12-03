<?php
namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\Layout;
use App\Services\LayoutServices;

class LayoutController extends Controller
{
    //添加
    public function add(Request $request)
    {
        //验证数据
        $type = array_keys(Layout::name());
        $link = array_keys(Layout::link());
        $status = array_keys(Layout::status());
        $validator = Validator::make($request->all(),[
            'type_id' => ['required',Rule::in($type)],
            'sort' => 'nullable|integer',
            'title' => 'Required|string',
            'picture' => 'nullable|string',
            'link_type' => ['required',Rule::in($link)],
            'link_target' => 'Required|string',
            'status' => [Rule::in($status)],
        ],[
            'required' => ':attribute为必填项',
            'in' => ':attribute的值不规范',
            'integer' => ':attribute必须为整数',
            'string' => ':attribute必须是字符串',
        ],[
            'type_id' => '类型',
            'sort' => '排序',
            'title' => '标题名称',
            'picture' => '图片地址',
            'link_type' => '链接类型',
            'link_target' => '链接目标',
            'status' => '状态',
        ]);
        if($validator->fails()){
            return response()->json([
                'status' => false,
                'data' => $validator->errrs()->first()
            ]);
        }
        //接收数据
        $arr = [
            'type_id' => $request->post('type_id'),
            'sort' => $request->post('sort',0),
            'title' => $request->post('title'),
            'picture' => $request->post('picture',null),
            'link_type' => $request->post('link_type'),
            'link_target' => $request->post('link_target'),
            'status' => $request->post('status',1),
        ];
        $result = (new LayoutServices())->add($arr);
        if(!$result){
            return response()->json([
                'status' => false,
                'data' => '添加失败'
            ]);
        }
        return response()->json([
            'status' => true,
            'data' => '添加成功'
        ]);
    }
    //多查询
    public function query(Request $request)
    {
        //验证数据
        $type = array_keys(Layout::name());
        $arr = ['type_id'=>$request->input('type_id')];
        $validator = Validator::make($arr,[
            'type_id' => ['required',Rule::in($type)]
        ],[
            'required' => ':attribute参数不能为空',
            'in' => ':attribute类型不正确',
        ],[
            'type_id' => '类型id'
        ]);
        if($validator->fails()){
            return response()->json([
                'status' =>false,
                'data' => $validator->errors()->first(),
            ]);
        }
        $type_id = $request->input('type_id');
        $result = (new LayoutServices())->query($type_id);
        if(!$result){
            return response()->json([
                'status' => false,
                'data' => []
            ]);
        }
        return response()->json([
            'status' => true,
            'data' => $result
        ]);
    }
    //删除
    public function del(Request $request)
    {
        $id = $request->input('id');
        $result = (new LayoutServices())->del($id);
        if(!$result){
            return response()->json([
                'status' => false,
                'data' => '删除失败'
            ]);
        }
        return response()->json([
            'status' => true,
            'data' => '删除成功'
        ]);
    }
    //查询单个
    public function find(Request $request)
    {
        $id = $request->input('id');
        $result = (new LayoutServices())->find($id);
        if(!$result){
            return response()->json([
                'status' => false,
                'data' => []
            ]);
        }
        return response()->json([
            'status' => true,
            'data' => $result
        ]);
    }
    //修改
    public function edit(Request $request)
    {
         //验证数据
        $type = array_keys(Layout::name());
        $link = array_keys(Layout::link());
        $status = array_keys(Layout::status());
        $validator = Validator::make($request->all(),[
            'type_id' => ['required',Rule::in($type)],
            'sort' => 'nullable|integer',
            'title' => 'Required|string',
            'picture' => 'nullable|string',
            'link_type' => ['required',Rule::in($link)],
            'link_target' => 'Required|string',
            'status' => [Rule::in($status)],
        ],[
            'required' => ':attribute为必填项',
            'in' => ':attribute的值不规范',
            'integer' => ':attribute必须为整数',
            'string' => ':attribute必须是字符串',
        ],[
            'type_id' => '类型',
            'sort' => '排序',
            'title' => '标题名称',
            'picture' => '图片地址',
            'link_type' => '链接类型',
            'link_target' => '链接目标',
            'status' => '状态',
        ]);
        if($validator->fails()){
            return response()->json([
                'status' => false,
                'data' => $validator->errrs()->first()
            ]);
        }
        //接收数据
        $arr = [
            'type_id' => $request->post('type_id'),
            'sort' => $request->post('sort',0),
            'title' => $request->post('title'),
            'picture' => $request->post('picture',null),
            'link_type' => $request->post('link_type'),
            'link_target' => $request->post('link_target'),
            'status' => $request->post('status',1),
        ];
        $id = $request->input('id');
        $result = (new LayoutServices())->edit($id,$arr);
        if(!$result){
            return response()->json([
                'status' => false,
                'data' => '修改失败'
            ]);
        }
        return response()->json([
            'status' => true,
            'data' => '修改成功'
        ]);
    }
}
