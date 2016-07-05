<?php

namespace Home\Controller;
use Think\Controller;
use Think\Model;

class CashierSystemInterfaceController extends HomeController {


    public  function  index(){

        $post_data = file_get_contents('php://input', 'r');
      //  $post_data=iconv('gbk','utf-8', $post_data);
        $re_data = json_decode($post_data);

      //  $model = new Model();
       // $test= $model->db(1,"sqlsrv://wdsa:weida888@182.92.97.83:1433/wd")->query("select count(*) as cnt  from t_listRule");

      // echo $re_data->oper_type;
        $result = false ;
        if($re_data->oper_type =='insertlog'){
            $result = $this->insertlog($re_data);
        }else if ($re_data->oper_type =='insertuser'){
            $this->insert_user($re_data);
        }else{
        //   $this->insert_user($re_data);
        }
        echo $result;
    }

    /**
     * 插入消费记录
     * @param $re_data  json数据
     * author liujb
     */
    public  function insertlog($re_data){
        $model = new Model();
        $map['username'] =$re_data->cardno;
        //查询会员信息
        $res = D("User")->where($map)->find();
      //  echo D("User")->where($map)->getLastSql();exit;
        //查询商家信息
        $map_bus['id'] = $re_data->branchid;
        $res_bus = D('User')->where($map_bus)->find();

        $paytype = urldecode($re_data->paytype);
        $pay_data = explode(';', $paytype);

        $pay_money = array();
        $pay_cnt = count($pay_data);
        for ($i = 0; $i<$pay_cnt; $i++) {
            $pay_t = $pay_data[$i];
            $pay = explode('-', $pay_t);
            if ($pay[0] == 'XJ' ||$pay[0] == 'YL') //现金/银联
            {
                $pay_money[1] += $pay_data[1]+$pay[1];
                $pay_type[1] = 1;
            }

            if ($pay[0] == 'JF') {  //积分
                $pay_money[3] += $pay[1];
                $pay_type[3] = 3;
            }
            if ($pay[0] == 'YC') {  //预存
                $pay_money[2] += $pay[1];
                $pay_type[2] = 2;
            }
        }

        $money_cnt = count($pay_money);
        for ($j = 1; $j <=3; $j++) {
            $jifen=0;
            //现金记录
            if($pay_type[$j] == '1' && $pay_money[$j] > 0){
                $map_user['money'] = $pay_money[$j];
                $map_user['typeid'] = 1;
                $jifen = $pay_money[$j] * $res_bus['rate']/100; //赠送泽百币

                //用户增加泽百币
                $res_user['zebaib']= $res['zebaib']+$jifen;
                $res_user['id']    = $res['id'];
                $r=D("User")->save($res_user);
                $integral= $res_user['zebaib'];
                //收银机系统增加积分
                $test=$model->db(1,"sqlsrv://wdsa:weida888@182.92.97.83:1433/wd")->query("update t_member set canuseintegral=$integral,totalintegral=totalintegral+$jifen where cardno='$re_data->cardno' and mobile='$re_data->mobile'");
                echo $test;
                //商家扣积分
                $map_bus['score'] = $res_bus['score']-$jifen-$pay_money[$j]*$res_bus['guanlifei']/100;

                D("User")->save($map_bus);

            }
            //预存记录
            if($pay_type[$j] == '2' && $pay_money[$j] > 0){
                $map_user['money'] = $pay_money[$j];
                $map_user['typeid'] = 2;
                //扣除用户预存金额
                $maps_yucun['uid'] = $res['id'];
                $maps_yucun['sid'] = $res_bus['id'];
                $yucuninfo=D("UserYucun")->where($maps_yucun)->find();
                if(!empty($yucuninfo)&&$yucuninfo['money']>0||$yucuninfo['money']>$pay_money[$j]){
                    $r=D("UserYucun")->where($maps_yucun)->setDec('money',$pay_money[$j]); // 扣除用户的预存
                }
            }

            //泽百币记录
            if($pay_type[$j] == '3' && $pay_money[$j] > 0){
                $map_user['money'] = $pay_money[$j];
                $map_user['typeid'] = 3;

                //检查商家是否有足够的泽百币
                $suser=D("User")->where("id=$re_data->branchid")->find();
                if($suser['zebaib']<=0||$suser['zebaib']< $pay_money[$j]){
                    exit('-3');//商家泽百币不足
                }else {
                    //商家扣则百币
                    $suser['zebaib']=$suser['zebaib']-$pay_money[$j];
                    D("User")->save($suser);
                }

                //检查用户是否有足够的泽百币
                if($res['zebaib']<=0||$res['zebaib']<$pay_money[$j]){
                    exit('-4');//用户泽百币不足
                }else {
                    //用户泽百币扣减
                    $res['zebaib'] = $res['zebaib'] - $pay_money[$j];
                    $r = D("User")->save($res);
                    $integral_jian = $res['zebaib'];
                    //收银机系统减少积分
                    $test=$model->db(1,"sqlsrv://wdsa:weida888@182.92.97.83:1433/wd")->query("update t_member set canuseintegral=$integral_jian where cardno='$re_data->cardno' and mobile='$re_data->mobile'");
                }
            }

            //消费记录log
            if(  $map_user['money']>0) {
                $map_user['uid'] = $res['id'];
                $map_user['addtime'] = time();
                $map_user['jifen'] = $jifen;
                $map_user['guanlifei'] = $res_bus['guanlifei'];
                $map_user['parentid'] = $res['parentid'];
                $map_user['payuid'] = $res_bus['id'];
                $code = D("Log")->add($map_user); //插入消费记录
            }
            unset($map_user);
        }

        if($code){
            return TRUE;
        }else{
            return FALSE;
        }

    }

    /**
     * 向收银系统添加用户
     * @param $data
     * author liujb
     */
    public  function  insert_user($data){
        $model = new Model();
       /*  $test= $model->db(1,"sqlsrv://wdsa:weida888@182.92.97.83:1433/wd")
                    ->query("insert into t_member(guid,code,cardno,cardtypeId,mobile,name,sex,branchid,password,state,cardstate,indate,opterid,optdate)
values ('1','000051828','000051828',1,'13681261884','刘','男','41623','e10adc3949ba59abbe56e057f20f883e',0,0,getdate(),'1',getdate())");*/
        $id  =  $data->id;
        $code  = $data->username;
        $mobile  = $data->mobile;
        $password = md5($data->password);
        $branchid =$data->parentid;
        $name     = $data->realname;
       // echo "insert into t_member(guid,code,cardno,cardtypeId,mobile,name,branchid,password,state,cardstate,indate,opterid,optdate)values ('$id','$code','$code',1,'$mobile','$name','$branchid','$password',0,0,getdate(),'1',getdate())";
        $test=$model->db(1,"sqlsrv://wdsa:weida888@182.92.97.83:1433/wd")
            ->query("insert into t_member(guid,code,cardno,cardtypeId,mobile,name,branchid,password,state,cardstate,indate,opterid,optdate)values ('$id','$code','$code',1,'$mobile','$name','$branchid','$password',0,0,getdate(),'1',getdate())");

    }

    /**
     * 更新收银系统的用户积分
     * @param $data
     * author liujb
     */
    public function update_integral($data){
        $model = new Model();
        $code     = $data->username;
        $mobile   = $data->mobile;
        $integral = $data->zebaib;
        $oper_type = $data->opertype;
        if($oper_type == 'jia') {
            $res = $model->db(1, "sqlsrv://wdsa:weida888@182.92.97.83:1433/wd")
                ->query("update t_member set canuseintegral=5,totalintegral=totalintegral+5 where cardno='000051828' and mobile='13681261884'");
        }else if ($oper_type == 'jian') {

        }
    }


}