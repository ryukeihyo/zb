<?php
/**
 * User: Administrator
 */
namespace Home\Controller;
use Think\Controller;
/**
 * 用户控制器
 * @author jry <598821125@qq.com>
 */
class TestController extends HomeController
{
    public function index()
    {
       $uri="http://localhost/member/cashierSystemInterface";
       // $uri="http://cs.xn--sxws6r7pv.com/cashierSystemInterface";
        /*  //        $uri="http://cs.xn--sxws6r7pv.com/cashierSystemInterface";
          $user_data['id']= '123121';
          $user_data['username']= '016556998';
          $user_data['realname']= '刘';
          $user_data['mobile']  = '13681267777';
          $user_data['password']= 'e10adc3949ba59abbe56e057f20f883e';
          $user_data['parentid']= '41636';
          $user_data['oper_type']= 'insertuser';
          $re = conn_zebaisystem__interface($user_data,'cashiersystem');
       //   echo $re;
          exit;*/
        $res['cardno']='000051828';
        $res['mobile']  = '13681261884';
        $res['amount']='200';
        $res['optdate']='2016-07-05';
        $res['integral']='50';
        $res['branchid']='41623';
        $res['paytype']='XJ-50;YL-20;JF-85;YC-45;';
        $res['oper_type'] ='insertlog';
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
        var_dump($result);exit;
        if($result){
            echo "插入成功！ ";
            exit;
        }else{
            echo "插入失败！ ";
            exit;
        }
    }
}
?>