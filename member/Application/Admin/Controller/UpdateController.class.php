<?php
// +----------------------------------------------------------------------
// | CoreThink [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.corethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: jry <598821125@qq.com> <http://www.corethink.cn>
// +----------------------------------------------------------------------
namespace Admin\Controller;
use Think\Controller;
/**
 * 更新市和县的parentid
 * @author jry <598821125@qq.com>
 */
class UpdateController extends AdminController{
    public function index(){
        set_time_limit(0);
        $sheng_id='0000';
        $sheng = D('User')->field('id,username,realname,parentid')->where("SUBSTRING(username,4,4) ='0000' AND usertype=0")->select();
        D('User')->execute("UPDATE  zb_user  SET parentid='0'  WHERE  SUBSTRING(username,4,4) ='0000' AND usertype=0");

        foreach($sheng as $sg){
            $sg_id=$sg['id'];
            $sg_name=$sg['username'];
            $shi = D('User')->field('id,username,realname,parentid ')->where("SUBSTRING(username,2,2)=SUBSTRING('$sg_name',2,2) AND SUBSTRING(username,6,2) ='00' AND SUBSTRING(username,4,4) !='0000' AND usertype=0")->select();
            //更新所有市
           D('User')->execute("update zb_user  set parentid='$sg_id'  WHERE  SUBSTRING(username,2,2)=SUBSTRING('$sg_name',2,2) AND SUBSTRING(username,6,2) ='00' AND SUBSTRING(username,4,4) !='0000'");

            foreach($shi as $si){
                $si_id=$si['id'];
                $si_name=$si['username'];
              //  echo substr($si_name,0,3);
                if( substr($si_name,0,3) == 'F11' || substr($si_name,0,3) =='F12'|| substr($si_name,0,3) =='F31'|| substr($si_name,0,3) =='F50' ) {
                    $si_rename=$si['realname'];

                    //echo  $si_rename;
                    //  echo $si_name;
                //    exit;
                    var_dump($xian);
                }else{

                    $si_id=$si['id'];
                    $si_name=$si['username'];
                    $si_rename=$si['realname'];
                    $xian = D('User')->field('id,username,realname,parentid')->where("SUBSTRING(username,2,2)=SUBSTRING('$si_name',2,2) AND SUBSTRING(username,4,2) =SUBSTRING('$si_name',4,2) AND SUBSTRING(username,6,2) !='00' AND usertype=0")->select();
                    //更新所有县
                    D('User')->execute("update zb_user  set parentid='$si_id'  WHERE  SUBSTRING(username,2,2)=SUBSTRING('$si_name',2,2) AND SUBSTRING(username,4,2) =SUBSTRING('$si_name',4,2) AND SUBSTRING(username,6,2) !='00' AND usertype=0");
                   var_dump($si_id);
                   var_dump($xian);
//                    exit;
                }
            }
            var_dump($shi);
        }
    }
}