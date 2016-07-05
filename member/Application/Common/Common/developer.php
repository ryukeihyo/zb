<?php
// +----------------------------------------------------------------------
// | CoreThink [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.corethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: jry <598821125@qq.com> <http://www.corethink.cn>
// +----------------------------------------------------------------------

//开发者二次开发公共函数统一写入此文件，不要修改function.php以便于系统升级。

function conn_zebaisystem__interface($user_array,$type){
    
    $json_data = json_encode($user_array);
    if($type=='shopping') {
        //$url = 'http://www.zebaiwang.cn/api/member_interface.php';//服务器接收数据地址
        $url = 'http://localhost/zeibai/api/member_interface.php';//本地接收数据地址
    }else if( $type == 'cashiersystem'){
        //$url = 'http://www.zebaiwang.cn/cashierSystemInterface';//服务器接收数据地址
        $url = 'http://localhost/member/cashierSystemInterface';//本地接收数据地址
    }
    $header = array('Content-Type: application/json','Content-Length:'.strlen($json_data));
    $ch = curl_init(); //初始化curl
    curl_setopt($ch, CURLOPT_URL, $url);//设置链接
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//设置是否返回信息
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);//设置HTTP头
    curl_setopt($ch, CURLOPT_POST, 1);//设置为POST方式
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);//POST数据
    $response = curl_exec($ch);//接收返回信息
    if(curl_errno($ch))
    {
        //出错则显示错误信息
        return curl_error($ch);
    }
    curl_close($ch); //关闭curl链接
    return $response;//显示返回信息
}

function insert_xiaofei(){
    $map['mobile']='000051828';
    //查询会员信息
    $res=D("User")->where($map)->find();
    var_dump($res);
}