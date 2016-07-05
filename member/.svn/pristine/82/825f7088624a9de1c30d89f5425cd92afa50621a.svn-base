<?php
// +----------------------------------------------------------------------
// | CoreThink [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.corethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: liujb<http://http://www.zebaiwang.cn/>
// +----------------------------------------------------------------------
namespace Home\Controller;
use Think\Controller;
/**
 * 泽百商城接口处理程序
 * @author liujb
 */
class WebShopInterfaceController extends HomeController{
    
    /**
     * 默认方法
     * @author liujb
     */
    public function index(){

    }
    
    /**
     * 验证手机是否已经注册
     */
    function validata_mobile(){
        $raw_post_data = file_get_contents('php://input', 'r');
         
        $re_data=json_decode($raw_post_data);
        $map['mobile']= $re_data->mobile;
        $map['usertype'] = 6;
        $r=D("User")->where($map)->find();

        if($r){
            //可以注册
            echo true;
        }else{
            //已经注册
            echo '0';
        }
    }
    
    /**
     * 商城会员注册
     * @author  liujb  
     */
    function reguser(){

        $raw_post_data = file_get_contents('php://input', 'r');
       
        $re_data=json_decode($raw_post_data);

        $data['username']=$re_data->mobile;
        $data['mobile'] = $re_data->mobile;
        $data['realname'] = "泽百通";
        $data['password'] = user_md5($re_data->password);
        $data['status'] = 1;
        $data['usertype'] = 6;
        if($re_data->is_pc){
            $data['platform'] = '网上商城注册用户';
        }else{
            $data['platform'] = '手机版网上商城注册用户';
        }
        //调用app接口注册
        $uri="http://www.zgxfzb.cn/do/interface_reg.php";
        $res['username']=$re_data->mobile;
        $res=json_encode($res);
        	
        $ch = curl_init($uri);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS,$res);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($res))
            );
        	
        $result = curl_exec($ch);
     
        $user_object = D('User');
    
        echo $id = $user_object->add($data);
    }
    
    /**
     *  登录
     *  @author liujb
     */
    function  login(){
        $raw_post_data = file_get_contents('php://input', 'r');
         
        $re_data=json_decode($raw_post_data);
         
        $map['mobile'] = $re_data->mobile;
        $map['password'] = user_md5($re_data->password);
        $r=D("User")->where($map)->find();
        if($r){
            echo true;
        }else{
            echo false;
        }
    }
    
    /**
     * 泽百币查询
     * @author liujb
     */
    function zebaib(){
        
        $raw_post_data = file_get_contents('php://input', 'r');
        $re_data       = json_decode($raw_post_data);
        
        $map['mobile']   = $re_data->mobile;
        $map['usertype'] = 6;
        $r=D("User")->where($map)->find();

        if($r){
            $res['zebaib']=$r['zebaib'];
            echo $r['zebaib'];
    
        }else{
            echo '0.00';
        }
    
    }
    
    /**
     * 泽百币消费
     * @author liujb
     */
    function  update_zebaib(){
        $raw_post_data = file_get_contents('php://input', 'r');
        
        $re_data         = json_decode($raw_post_data);
        $data['mobile']  = $re_data->mobile;
        $map['usertype'] = 6;
        $consume_zebaib = $re_data->consume_zebaib;
        
        $suser = D("User")->where($data)->find();
        $suser['zebaib'] = $suser['zebaib']-$consume_zebaib;
   
        D("User")->save($suser);
        
        echo $suser['zebaib'];
    }
    
    /**
     *  密码修改
     *  @author  liujb
     */
     function update_password(){
         $raw_post_data = file_get_contents('php://input', 'r');
          
         $re_data=json_decode($raw_post_data);
         
         $data['mobile']  = $re_data->mobile;
         $data['usertype'] = 6;
         $suser = D("User")->where($data)->find();
         
         $suser['password'] = user_md5($re_data->password);
         D("User")->save($suser);
         
         echo $suser['mobile'];
     }
    /**
     *  用户信息修改
     *  @author  liujb
     */
     function update_user(){
         $raw_post_data = file_get_contents('php://input', 'r');
         
         $re_data=json_decode($raw_post_data);
          
         $data['mobile'] = $re_data->mobile;
         $data['usertype'] = 6;
         $suser = D("User")->where($data)->find();
         
         $suser['mobile'] = $re_data->new_mobile;
         D("User")->save($suser);
          
         echo $suser['mobile'];
     }
     
     /**
      *  插入商城的消费记录
      *  @param parentid  商家id
      *  @param payuid    商家id
      *  @author  liujb
      */
     function insert_log(){
         $raw_post_data = file_get_contents('php://input', 'r');
          
         $re_data=json_decode($raw_post_data);
         $data['mobile']   = $re_data->mobile;
         $data['usertype'] = 6;
         $user = D("User")->where($data)->find();
         $data['uid']      = $user['id'];
         $data['addtime']  = time();
         $data['money']    = $re_data->goods_amount;
         $data['parentid'] = '71493';//所属商家   
         $data['payuid']   = '71493'; //支付商家
         $data['typeid']   = $re_data->order_amount_type; //支付类型
         D("Log")->add($data);
         echo $user['id'];
     }
}