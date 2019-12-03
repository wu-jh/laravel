<?php
namespace  App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductSku;
use App\Models\ProductTag;
use App\Services\ProductServices;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    //添加商品
    public function create(Request $request)
    {
        //验证数据
        $product_status = array_keys(Product::source());
        $tag_status = array_keys(ProductTag::source());
        $tag_type = array_keys(ProductTag::type());
        $sku_status = array_keys(ProductSku::source());
        $validator = Validator::make($request->post(),[
            'product_name' => 'required|string',
            'category_id' => 'required|integer',
            'content' => 'nullable|string',
            'product_sort' => 'integer',
            'product_status' => [Rule::in($product_status)],
            'tags' => 'json',
            'sku' => 'json'
        ],[
            'required' => ':attribute不能为空',
            'integer' => ':attribute必须是整数',
            'required_with' => '标签存在时:attribute为必填项',
            'max' => ':attribute最多为255个字符',
            'in' => ':attribute类型不规范',
            'string' => ':attribute必须是字符串',
            'json' => ':attribute必须是json串',
        ],[
            'product_name' => '商品名称',
            'category_id' => '分类',
            'content' => '商品的描述',
            'product_sort' => '商品排序',
            'product_status' => '商品状态',
            'tags' => '标签',
            'sku' => 'sku',
        ]);
        if($validator->fails()){
            return response()->json([
                'status'=>false,
                'data'=>$validator->errors()->first()
            ]);
        }

         //标签
        $tagList = json_decode($request->post('tags'),true);
        $tags = [];
        foreach ($tagList as $v){
            $validator2 = Validator::make($v,[
                'tag_id' => [Rule::in($tag_type)],
                'value' => 'nullable|string|max:255',
                'status' => [Rule::in($tag_status)],
            ],[
                'in' => ':attribute不规范',
                'string' => ':attribute必须是字符串',
                'max' => ':attribute字数超过最大限制'
            ],[
                'tag_id' => '标签类型',
                'value' => '标签值',
                'status' => '标签状态',
            ]);
            if($validator2->fails()){
                return response()->json([
                    'status'=>false,
                    'data'=>$validator2->errors()->first()
                ]);
            }
            $tags[] = [
                'tag_id' => $v['tag_id'],
                'value' => $v['value'],
                'status' => $v['status'],
            ];
        }

        //sku
        $skuList = json_decode($request->post('sku'),true);
        $sku = [];
        foreach ($skuList as $v){
            $validator1 = Validator::make($v,[
                'original_price' => 'required|numeric|min:0',
                'price' => 'required|numeric|min:0',
                'attr1' => 'nullable|string',
                'attr2' => 'nullable|string',
                'attr3' => 'nullable|string',
                'quantity' => 'integer|min:0',
                'status' => [Rule::in($sku_status)],
            ],[
                'required' => ':attribute不能为空',
                'integer' => ':attribute必须是整数',
                'min' => ':attribute最小为0',
                'in' => ':attribute类型不规范',
                'string' => ':attribute必须是字符串',
            ],[
                'original_price' => '原价',
                'price' => '售价',
                'attr1' => '商品的属性1',
                'attr2' => '商品的属性2',
                'attr3' => '商品的属性3',
                'quantity' => '商品的库存',
                'status' => 'sku状态',
            ]);
            if($validator1->fails()){
                return response()->json([
                    'status'=>false,
                    'data'=>$validator1->errors()->first()
                ]);
            }
            $sku[] = [
                'original_price' => $v['original_price'],
                'price' => $v['price'],
                'attr1' => $v['attr1'],
                'attr2' => $v['attr2'],
                'attr3' => $v['attr3'],
                'quantity' => $v['quantity']?$v['quantity']:0,
                'status' => $v['status'],
            ];
        }
        //接收数据
        $arr = [
            'pre' => [
                'name' => $request->input('product_name'),
                'category_id' => $request->input('category_id'),
                'content' => $request->input('content',null),
                'sort' => $request->input('product_sort',0),
                'status' => $request->input('product_status','1'),
            ],
            'tags' => $tags,
            'sku' => $sku,
        ];
        $result = (new ProductServices())->create($arr);
        if(!$result){
            return response()->json([
                'status'=> false,
                'data'=>  '添加失败'
            ]);
        }

        return response()->json([
            'status' => true,
            'data' => '添加成功'
        ]);
    }

    //查询单个商品
    public function find(Request $request)
    {
        //接收数据
        $id = $request->get('id');

        $result = (new ProductServices())->find($id);

        if(!$result){
            return response()->json([
                'status'=> false,
                'data'=>  '商品id不存在'
            ]);
        }

        return response()->json([
            'status' => true,
            'data' => $result
        ]);
    }

    //查询商品列表
    public function select(Request $request)
    {
        $result = (new ProductServices())->select();
        return response()->json([
            'status' => true,
            'data' => $result
        ]);
    }

    //删除商品
    public function delete(Request $request)
    {
        $id = $request->get('id');
        $result = (new ProductServices())->delete($id);
        if(!$result){
            return response()->json([
                'status' => true,
                'data' => '商品id不存在'
            ]);
        }
        return response()->json([
            'status' => true,
            'data' => '删除成功'
        ]);
    }

    //删除商品标签
    public function deleteTag(Request $request)
    {
        $id = $request->input('id');
        $result = (new ProductServices())->delTag($id);
        if(!$result){
            return response()->json([
                'status' => true,
                'data' => '标签id不存在'
            ]);
        }
        return response()->json([
            'status' => true,
            'data' => '删除成功'
        ]);
    }

    //删除商品sku
    public function deleteSku(Request $request)
    {
        $id = $request->post('id');
        $result = (new ProductServices())->delSku($id);
        if(!$result){
            return response()->json([
                'status' => fasle,
                'data' => '库存id不存在'
            ]);
        }
        return response()->json([
            'status' => true,
            'data' => '删除成功'
        ]);
    }

    //修改商品
    public function basicEdit(Request $request)
    {
        //验证数据
        $product_status = array_keys(Product::source());
        $validator = Validator::make($request->post(),[
            'product_name' => 'string',
            'content' => 'nullable|string|max:255',
            'product_sort' => 'integer',
            'product_status' => [Rule::in($product_status)],
        ],[
            'integer' => ':attribute必须是整数',
            'max' => ':attribute最多为255个字符',
            'in' => ':attribute类型不规范',
            'string' => ':attribute必须是字符串',
        ],[
            'product_name' => '商品名称',
            'content' => '商品的描述',
            'product_sort' => '商品排序',
            'product_status' => '商品状态',
        ]);
        if($validator->fails()){
            return response()->json([
                'status'=>false,
                'data'=>$validator->errors()->first()
            ]);
        }

        //接收数据
        $arr = [
            'name' => $request->input('name'),
            'content' => $request->input('content',null),
            'sort' => $request->input('sort',0),
            'status' => $request->input('status','1'),
        ];
        $id  = $request->get('id');
        $result = (new ProductServices())->basicEdit($arr,$id);
        if(!$result){
            return response()->json([
                'status'=> false,
                'data'=>  '商品id不存在'
            ]);
        }

        return response()->json([
            'status' => true,
            'data' => '修改成功'
        ]);
    }

    //修改商品状态
    public function status(Request $request)
    {
        $status = array_keys(Product::source());
        $validator = Validator::make($request->all(),[
            'status' => ['required',Rule::in($status)],
        ],[
            'required' => ":attribute为必要参数",
            'in' => ":attribute只能是1或0"
        ],[
            'status' => '状态'
        ]);
        if($validator->fails()){
            return response()->json([
                'status'=>false,
                'data'=>$validator->errors()->first()
            ]);
        }
        $id = $request->input('id');
        $status = $request->input('status');
        $result = (new ProductServices())->status($id,$status);
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

    //修改商品标签
    public function tagEdit(Request $request){
        $tag_status = array_keys(ProductTag::source());
        $tag_type = array_keys(ProductTag::type());
        $tagList = json_decode($request->post('tags'),true);
        foreach ($tagList as $v){
            $validator = Validator::make($v,[
                'tag_id' => [Rule::in($tag_type)],
                'value' => 'nullable|string|max:255',
                'status' => [Rule::in($tag_status)],
            ],[
                'in' => ':attribute不规范',
                'string' => ':attribute必须是字符串',
                'max' => ':attribute字数超过最大限制'
            ],[
                'tag_id' => '标签类型',
                'value' => '标签值',
                'status' => '标签状态',
            ]);
            if($validator->fails()){
                return response()->json([
                    'status'=>false,
                    'data'=>$validator->errors()->first()
                ]);
            }
        }
        $id = $request->input('id');
        $result = (new ProductServices())->tagEdit($id,$tagList);

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

    //修改商品sku
    public function skuEdit(Request $request)
    {
        //验证数据
        $sku_status = array_keys(ProductSku::source());
        $validator = Validator::make($request->all(),[
            'sku' => 'Array'
        ],[
            'Array' => ':attribute必须是一个数组'
        ],[
            'sku' => 'sku'
        ]);
        if($validator->fails()){
            return response()->json([
                'status' => false,
                'data' => $validator->errors()->first()
            ]);
        }
        $sku = [];
        foreach ($request->input('sku') as $v){
            $validator1 = Validator::make($v,[
                'original_price' => 'required|numeric|min:0',
                'price' => 'required|numeric|min:0',
                'attr1' => 'nullable|string',
                'attr2' => 'nullable|string',
                'attr3' => 'nullable|string',
                'quantity' => 'integer|min:0',
                'status' => [Rule::in($sku_status)],
            ],[
                'required' => ':attribute不能为空',
                'integer' => ':attribute必须是整数',
                'min' => ':attribute最小为0',
                'in' => ':attribute类型不规范',
                'string' => ':attribute必须是字符串',
            ],[
                'original_price' => '原价',
                'price' => '售价',
                'attr1' => '商品的属性1',
                'attr2' => '商品的属性2',
                'attr3' => '商品的属性3',
                'quantity' => '商品的库存',
                'status' => 'sku状态',
            ]);
            if($validator1->fails()){
                return response()->json([
                    'status'=>false,
                    'data'=>$validator1->errors()->first()
                ]);
            }
            $sku[] = [
                'original_price' => $v['original_price'],
                'price' => $v['price'],
                'attr1' => $v['attr1'],
                'attr2' => $v['attr2'],
                'attr3' => $v['attr3'],
                'quantity' => $v['quantity']?$v['quantity']:0,
                'status' => $v['status'],
            ];
        }
        $id = $request->input('id');
        $result = (new ProductServices())->skuEdit($id,$sku);
        if(!$result){
            return response()->json([
                'status'=> false,
                'data'=>  '修改失败'
            ]);
        }

        return response()->json([
            'status' => true,
            'data' => '修改成功'
        ]);
    }
}
