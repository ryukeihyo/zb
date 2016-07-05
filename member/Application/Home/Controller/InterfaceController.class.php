<?php
// +----------------------------------------------------------------------
// | CoreThink [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.corethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: jry <598821125@qq.com> <http://www.corethink.cn>
// +----------------------------------------------------------------------
namespace Home\Controller;
use Think\Controller;
/**
 * 用户控制器
 * @author jry <598821125@qq.com>
 */
class InterfaceController extends HomeController{
	

	
    /**
     * 用户列表
     * @author jry <598821125@qq.com>
     */
    public function index(){

		/*
		
		
		*/
		$raw_post_data = file_get_contents('php://input', 'r'); 
		
		$re_data=json_decode($raw_post_data);
		
		$map['mobile']=$re_data->mobile;
		$map['status']=1;
		$map['addtime']=array("gt",time()-600);
		
		$r=D("App")->where($map)->find();


		if($r){
		
			$res['status']=$r['status'];
			$res['type']=$r['type'];
			$res['money']=$r['fee'];
			header('Content-type: application/json');
			echo json_encode($res);
				//	exit('-2');//有订单还没确认
			exit;
		
		}else{
			$res['status']=0;
			$res['type']=0;
			$res['money']=0;
			header('Content-type: application/json');
			echo json_encode($res);
				//	exit('-2');//有订单还没确认
			exit;
		}
		
		
		//$data['fee']=$fee;
		//$data['type']=1;
		//$data['uid']=$res['id'];
		//$data['addtime']=time();
		//$data['status']=1;
		
		//echo  D("App")->add($data);


    }
	
	 public function pay(){
	 
	
		$raw_post_data = file_get_contents('php://input', 'r'); 
		
		$re_data=json_decode($raw_post_data);
		
		$map['mobile']=$re_data->mobile;
		$map['status']=1;
		$map['addtime']=array("gt",time()-600);
		
		$r=D("App")->where($map)->find();
		
		if($r){
		
			$r['status']=0;
	
			$rs=D("App")->save($r);
			
			$res['status']=$rs;
			
			$maps['mobile']=$re_data->mobile;
			if($r['type']==1){
				//$rs=D("User")->where($maps)->setDec('yucun',$r['fee']); // 用户的预存
				$m['uid']=$r['uid'];
				$m['sid']=$r['sid'];
				$r=D("UserYucun")->where($m)->setDec('money',$r['fee']); // 用户的预存
				
			}elseif($r['type']==2){
				$rs=D("User")->where($maps)->setDec('zebaib',$r['fee']); // 用户的泽百币
			}
			
			
			
			
			header('Content-type: application/json');
			echo json_encode($res);
			
			exit;
		
		}
	}	
	
	public function unpay(){
	 
		$raw_post_data = file_get_contents('php://input', 'r'); 
		
		$re_data=json_decode($raw_post_data);
		
		$map['mobile']=$re_data->mobile;
		$map['status']=1;
		$map['addtime']=array("gt",time()-600);
		
		$r=D("App")->where($map)->find();
		
		if($r){
		
			$r['status']=2;
	
			$rs=D("App")->save($r);
			
			$res['status']=$rs;
			
			header('Content-type: application/json');
			echo json_encode($res);
			
			exit;
		
		}
	 
	 }
	
	
	
	function process(){
	
	
				
		$map['mobile']=$_REQUEST['mobile'];
		//$map['status']=0;
		$map['addtime']=array("gt",time()-600);
		
		$r=D("App")->where($map)->order("id desc")->find();

		
		if($r){

			
			header('Content-type: application/json');
			echo json_encode($r);
			
			exit;
		
		}
	
	
	
	}
	
	
	
	function process_scuess(){
	
	
				
		$map['mobile']=$_REQUEST['mobile'];
		
		$map['addtime']=array("gt",time()-600);
		
		$data['process_scuess']=1;
		
		$r=D("App")->where($map)->save($data);

		
		if($r){

			
			header('Content-type: application/json');
			echo json_encode($r);
			
			exit;
		
		}
	
	
	
	}
	
	function log(){
		$maps['mobile']=$_GET['mobile'];
		$us=D("User")->where($maps)->find();
		$map['typeid']=$_GET['id'];
		$map['uid']=$us['id'];
		
		
		
		$list=D("Xiaofei")->where($map)->page(!empty($_GET["p"])?$_GET["p"]:1, 15)->order("id desc")->select();
		$page = ceil(D('Xiaofei')->where($map)->count()/15);
		
		//查询被赠送积分的会员信息
		foreach($list as $key=>$val){
			if($val['uid']){
				$lists[$key]['time']=date("m-d",$val['addtime']);
				$lists[$key]['fmoney']=$val['fmoney'];
				
				$sj=D("User")->where("id=".$val['parentid'])->find();
				
				$lists[$key]['shangjia']=$sj['realname'];
			}
		}
		$arr['data']=$lists;
		$arr['number']=count($lists);
		$arr['page']=$page>0?$page:1;
			
		
       echo $_GET['jsoncallback'] . "(".json_encode($arr).")"; 


	
	
	}
	
	
	//app check username
	
	function checkusername(){
	
		$raw_post_data = file_get_contents('php://input', 'r'); 
		
		$re_data=json_decode($raw_post_data);

	
		$map['username']=$re_data->username;

		$r=D("User")->where($map)->find();
		if($r){

			
			header('Content-type: application/json');
			echo json_encode($r);
			
			exit;
		
		}
	
	
	
	}
	
	//app reg user
	function reguser(){
	
		$raw_post_data = file_get_contents('php://input', 'r'); 
		
		$re_data=json_decode($raw_post_data);

		$data['username']=$re_data->username;
		$data['mobile'] = $re_data->mobile;
		$data['realname'] = "泽百通";
		$data['password'] = user_md5($re_data->password);
		$data['status'] = 1;
		$data['usertype'] = 6;
		
		//add by liujb 20160516 start
		$user_array['username'] = $re_data->mobile;
		$user_array['mobile_phone'] = $re_data->mobile;
		$user_array['password']      = substr(trim($re_data->password),5);
		$user_array['type']          =  'reguser';
		$re = conn_zebaisystem__interface($user_array);
		//add by liujb 20160516 end
		
		$user_object = D('User');


        echo $id = $user_object->add($data);


	
	
	
	}
	
	
	
	
	function zebaibi(){
	
		
				
		$map['mobile']=$_REQUEST['mobile'];
		

		
		$r=D("User")->where($map)->find();

		
		if($r){
			//$res['status']=1;
			$res['zebaib']=$r['zebaib'];
			echo 'document.write("'.$r['zebaib'].'")';
			//header('Content-type: application/json');
			//echo json_encode($res);
			
			//exit;
		
		}else{
		
			echo 'document.write("0.00")';
		
		}
	
	
	
	}
	
	
	
	
	
	
	 
   
}
