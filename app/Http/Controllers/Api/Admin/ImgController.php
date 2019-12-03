<?php
namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ImgController extends Controller
{
    public function add(Request $request)
    {
        //检查文件是否为空
        if (!$request->hasFile('img')) {
            return response()->json([
                'status' => true,
                'data' => '请上传图片'
            ]);
        }
        //检查是否上传成功
        if (!$request->file('img')->isValid()) {
            return response()->json([
                'status' => false,
                'data' => '上传失败'
            ]);
        }
        //检查文件后缀名
        $files = ['jpeg','png','bmp','gif','svg '];
        $path = $request->file('img')->path();
        $extension = $request->file('img')->extension();
        if(!in_array($extension,$files)){
            return response()->json([
                'status' => true,
                'data' => '上传的文件必须是图片'
            ]);
        }
        $file_name  =$request->file('img')->store('','images');
        return response()->json([
            'status' => true,
            'data' => $file_name
        ]);
    }
}
