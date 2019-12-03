<?php
namespace App\Services;

use App\Models\Product;
use App\Models\ProductSku;
use App\Models\ProductTag;
use Illuminate\Support\Facades\DB;

class ProductServices
{

    //添加商品
    public function create($arr)
    {
        //开启事务
        DB::beginTransaction();
        //添加商品表内容
        $product = new Product();
        $product->name = $arr['pre']['name'];
        $product->category_id = $arr['pre']['category_id'];
        $product->content = $arr['pre']['content'];
        $product->sort = $arr['pre']['sort'];
        $product->status = $arr['pre']['status'];
        if(!$product->save()){
            DB::rollback();
            return false;
        }
        //添加标签表内容
        $tag = new ProductTag();
        foreach ($arr['tags'] as $k => $v){
            $arr['tags'][$k]['product_id'] = $product->id;
            $arr['tags'][$k]['created_at'] = date('Y-m-d H:i:s',time());
            $arr['tags'][$k]['updated_at'] = date('Y-m-d H:i:s',time());
            //判断标签值是否存在,不存在就剔除
            if(!$arr['tags'][$k]['value']){
                unset($arr['tags'][$k]);
            }
        }
        if(count($arr['tags']) > 0){
            $res1 = $tag->insert($arr['tags']);
            if(!$res1){
                DB::rollback();
                return false;
            }
        }


        //添加sku
        $sku = new ProductSku();
        foreach ($arr['sku'] as $k => $v){
            $arr['sku'][$k]['product_id'] = $product->id;
            $arr['sku'][$k]['created_at'] = date('Y-m-d H:i:s',time());
            $arr['sku'][$k]['updated_at'] = date('Y-m-d H:i:s',time());
        }

        if(count($arr['sku']) > 0){
            $res = $sku->insert($arr['sku']);
            if(!$res){
                DB::rollback();
                return false;
            }
        }

        DB::commit();
        return true;
    }

    //修改商品
    public function basicEdit($arr,$id)
    {
        $result = (new Product())->find($id);
        $result->content = $arr['content'];
        $result->name = $arr['name'];
        $result->sort = $arr['sort'];
        $result->status = $arr['status'];
        if(!$result->save()){
            return null;
        }
        return $result;
    }

    //查询单个商品
    public function find($id)
    {
        $product = (new Product())->where('status','!=',config('const.DELETE'))->with('sku')->with('tag')->with('category')->find($id);
        return $product;
    }

    //查询商品列表
    public function select()
    {
        $product = (new Product())->where('status','!=',config('const.DELETE'))->with('category')->orderBy('sort','asc')->orderBy('id','desc')->paginate(10);
        return $product;
    }

    //删除商品
    public function delete($id)
    {
        $product = (new Product())->find($id);
        $product->status = config('const.DELETE');
        if(!$product->save()){
            return null;
        }

        return $product;
    }

    //修改商品状态
    public function status($id,$status){
        $result = (new Product())->find($id);
        $result->status = $status;
        if(!$result->save()){
            return null;
        }

        return $result;
    }

    //删除商品标签
    public function delTag($id)
    {
        $tag = (new ProductTag())->destroy($id);
        return $tag;
    }

    //删除sku
    public function delSku($id)
    {
        $sku = new ProductSku();
        $sku->find($id);
        $sku->status = config('const.DELETE');
        if(!$sku->save()){
            return null;
        }

        return $sku;
    }

    //修改标签
    public function tagEdit($id,$arr)
    {
        $insert = [];
        $update = [];
        foreach($arr as $v){
            if(!empty($v['id'])){
                array_push($update,$v);
            }else{
                array_push($insert,$v);
            }
        }
        $tag = new ProductTag();
        DB::beginTransaction();
        //添加
        foreach ($insert as $k => $v){
            $insert[$k]['product_id'] = $id;
            $insert[$k]['created_at'] = date('Y-m-d H:i:s',time());
            $insert[$k]['updated_at'] = date('Y-m-d H:i:s',time());
            //判断标签值是否存在,不存在就剔除
            if(!$insert[$k]['value']){
                unset($insert[$k]);
            }
        }
        if(count($insert) > 0){
            $res1 = $tag->insert($insert);
            if(!$res1){
                DB::rollback();
                return false;
            }
        }
        //修改
        foreach ($update as $k => $v){
            $result = $tag->where([['id',$v['id']],['product_id',$id]])->update(['value' => $v['value']]);
            if(!$result){
                return false;
            }
        }
        DB::commit();
        return true;
    }

    //修改sku
    public function skuEdit($id,$arr){
        $sku = new ProductSku();
        foreach ($arr as $k => $v){
            $arr[$k]['product_id'] = $id;
            $arr[$k]['created_at'] = date('Y-m-d H:i:s',time());
            $arr[$k]['updated_at'] = date('Y-m-d H:i:s',time());
        }
        DB::beginTransaction();
        $res = $sku->where('product_id',$id)->delete();
        if(!$res){
            DB::rollback();
            return false;
        }
        $res = $sku->insert($arr);
        if(!$res){
            DB::rollback();
            return false;
        }
        DB::commit();
        return true;
    }
}
