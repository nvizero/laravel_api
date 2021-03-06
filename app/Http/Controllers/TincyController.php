<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Redis;

class TincyController extends Controller
{
    /**
     * 
     */
    public function updateImage(Request $request)
    {
        //{ location : "/demo/image/1.jpg" }
        // echo "s{{{{}}wwww";
        $accepted_origins = array("http://localhost", "http://192.168.1.1", "http://example.com", env('APP_URL'));

        /*********************************************
         * 设置图片保存的文件夹 *
         *********************************************/
        $imageFolder = "uploads/";

        reset($_FILES);
        $temp = current($_FILES);
        if (!is_uploaded_file($temp['tmp_name'])) {
            // 通知编辑器上传失败
            header("HTTP/1.1 500 Server Error");
            exit;
        }

        if (isset($_SERVER['HTTP_ORIGIN'])) {
            // 验证来源是否在白名单内
            if (in_array($_SERVER['HTTP_ORIGIN'], $accepted_origins)) {
                header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
            } else {
                header("HTTP/1.1 403 Origin Denied");
                exit;
            }
        }

        /*
        如果脚本需要接收cookie，在init中加入参数 images_upload_credentials : true
        并加入下面两个被注释掉的header内容
        */
        // header('Access-Control-Allow-Credentials: true');
        // header('P3P: CP="There is no P3P policy."');

        // 简单的过滤一下文件名是否合格
        if (preg_match("/([^\w\s\d\-_~,;:\[\]\(\).])|([\.]{2,})/", $temp['name'])) {
            header("HTTP/1.1 400 Invalid file name.");
            exit;
        }

        // 验证扩展名
        if (!in_array(strtolower(pathinfo($temp['name'], PATHINFO_EXTENSION)), array("gif", "jpg", "png"))) {
            header("HTTP/1.1 400 Invalid extension.");
            exit;
        }

        // 都没问题，就将上传数据移动到目标文件夹，此处直接使用原文件名，建议重命名
        $filetowrite = $imageFolder . $temp['name'];
        move_uploaded_file($temp['tmp_name'], $filetowrite);

        // 返回JSON格式的数据
        // 形如下一行所示，使用location存放图片URL
        // { location : '/your/uploaded/image/file.jpg'}
        echo json_encode(array('location' => env('APP_URL') . '/' .   $filetowrite));
    }
}