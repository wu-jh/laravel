<?php
namespace App\Services;

use App\Models\Layout;

class LayoutServices
{
    public function add($arr)
    {
        $layout = new Layout();
        $arr['created_at'] = date('Y-m-d H:i:s',time());
        $arr['updated_at'] = date('Y-m-d H:i:s',time());
        $result = $layout->insert($arr);
        if(!$result){
            return false;
        }
        return true;
    }

    public function query($type_id){
        $result = (new Layout())->where('type_id',$type_id)->orderBy('sort','asc')->orderBy('id','desc')->get();
        if(!$result){
            return false;
        }
        return $result;
    }

    public function del($id)
    {
        $result = (new Layout())->destroy($id);
        if(!$result){
            return false;
        }
        return $result;
    }

    public function find($id)
    {
        $result = (new Layout())->find($id);
        return $result;
    }

    public function edit($id,$arr)
    {
        $arr['updated_at'] = date('Y-m-d H:i:s',time());
        $result = (new Layout())->where('id',$id)->update($arr);
        return $result;
    }
}
