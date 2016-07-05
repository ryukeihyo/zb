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
class MemeberController extends HomeController{
	
	
	protected function _initialize(){
        parent::_initialize();
        $this->is_login();
    }
	
    /**
     * 用户列表
     * @author jry <598821125@qq.com>
     */
    public function index(){

		$info=D("User")->where("id=".$this->__USER__['uid'])->find();
		

		$this->assign('info', $info);
	
		$this->meta_title = '会员管理';
		//$this->assign('__CURRENT_CATEGORY__',0);
        Cookie('__forward__', $_SERVER['REQUEST_URI']);
        $this->display();

    }
	
	/**
     * 会员注册
     * @author 
     */
    public function regmember(){
		
		
		$data['username']=trim(I('post.username'));
		$data['realname']=trim(I('post.realname'));
		$data['mobile']=trim(I('post.mobile'));
		$data['managenumber']=trim(I('post.managenumber'));
		$data['password']=user_md5(substr(trim(I('post.mobile')),5));
		$data['ctime']=time();
		$data['status']=1;
		$data['usertype']=6;

		$s1=substr($data['username'],-3);

		
		$flag=false;
		
		if($s1=='0000'){
			
			$flag=true;
			
		}elseif($s1=='1111'){
			
			$flag=true;
			
		}elseif($s1=='2222'){
			
			$flag=true;
			
		}elseif($s1=='3333'){
			
			$flag=true;
			
		}elseif($s1=='4444'){
			
			$flag=true;
			
		}elseif($s1=='5555'){
			
			$flag=true;
			
		}elseif($s1=='6666'){
			
			$flag=true;
			
		}elseif($s1=='7777'){
			
			$flag=true;
			
		}elseif($s1=='8888'){
			
			$flag=true;
			
		}elseif($s1=='9999'){
			
			$flag=true;
			
		}
	
		//判断特号注册
		if(I('post.tehao')){
			
			$pareninfo=D("User")->where("username='".$data['managenumber']."'")->find();
			
			$data['parentid']=$pareninfo['id'];
			
		}else{
			
			$pareninfo=D("User")->where("username='".$data['managenumber']."'")->find();
			
			$data['parentid']=$pareninfo['id'];
			
			if($flag){    
	  
				$this->assign('msg',"账号尾号包含<font style='color:red;font-size:30px'>".$s1."</font>为系统保留号码！");
				
				if(I('post.ajax')){	
					echo "账号尾号包含【".$s1."】为系统保留号码！";
					exit;
				}
				
				$this->meta_title = '会员管理';
				$this->display();
				die; 
				
			}
			
			
		}
		
		if(preg_match("/^13[0-9]{1}[0-9]{8}$|15[0-9]{1}[0-9]{8}$|18[0-9]{1}[0-9]{8}$|189[0-9]{8}$|17[0-9]{9}$|0[0-9]{10}$|171[0-9]{8}$/",$data['mobile'])){
			//验证通过    
				 
		}else{
			//手机号码格式不对    
			$this->assign('msg',"手机号码不正确！");
			$this->meta_title = '会员管理';
			$this->display();
			die; 
		}
		
		
		
		$r=D("User")->where("username='".$data['username']."'")->find();
		if($r){
			$this->assign('msg',"该账号已经被注册！");
			if(I('post.ajax')){	
				echo "该账号已经被注册！";
				exit;
			}
			
			
		}else{
		
		
			$username=$data['mobile'];
		
			/*check member system has same usernme by liux*/

			/*$uri="http://www.zgxfzb.cn/do/interface_reg.php";
			$res['username']=$username;
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
			
			if($result<0){
			
				echo "该账号已经被注册！";
				exit;
			}

			//add by liujb 20160516 start
			$user_array['username'] = $data['mobile'];
			$user_array['mobile_phone'] = $data['mobile'];
			$user_array['password']      = substr(trim(I('post.mobile')),5);
			$user_array['type']          =  'reguser';
			$re = conn_zebaisystem__interface($user_array,'shopping');
			*///add by liujb 20160516 end

            //add by liujb 20160629 start

            //add by liujb 20160629 end
		
			$r=D("User")->add($data);
            //add by liujb 20160629 start
            $user_data['id']= $r;
            $user_data['username']= $data['username'];
            $user_data['realname']= $data['realname'];
            $user_data['mobile']  = $data['mobile'];
            $user_data['password']= substr(trim(I('post.mobile')),5);;
            $user_data['parentid']= $data['parentid'];
            $user_data['oper_type']= 'insertuser';
            $re = conn_zebaisystem__interface($user_data,'cashiersystem');
            //add by liujb 20160629 end

            if($r){
				$this->assign('msg',"注册成功！");
				if(I('post.ajax')){
				
					echo 1;
					exit;
				}
				$this->assign('forward',Cookie('__forward__') ? : C('HOME_PAGE'));
			
			}
		}
		
		
		$this->meta_title = '会员管理';
		$this->display();

    }
	
	public function f3333(){
		$this->meta_title = '会员管理';
		$this->display();
	}
   
}
