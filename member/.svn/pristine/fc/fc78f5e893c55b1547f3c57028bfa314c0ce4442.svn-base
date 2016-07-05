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
class BranchController extends HomeController{
	
	
	protected function _initialize(){
        parent::_initialize();
        $this->is_login();
    }
	
    /**
     * 用户列表
     * @author jry <598821125@qq.com>
     */
	
    public function index(){
	
		if($this->user_info['usertype']!=9){
			$this->error('你没有权限', "");
			
		}
		if($_POST){
			$data=$_POST;
			$data['parentid']=$parent_id=$this->__USER__[uid];

			$data['password']=user_md5(substr($_POST['username'],3));
			$data['ctime']=time();
			$data['status']=1;
			$data['usertype']=$_POST['usertype'];
			$r=D('User')->add($data);
			if($r){
			
			
				$username=$data['mobile'];
		
				/*check member system has same usernme by liux*/
				
				$uri="http://www.zgxfzb.cn/do/interface_reg.php";
				$res['username']=$username;
				$res['grouptype']=1;
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
				

			
			
			
			
			
			
			
			
				$this->success('注册成功', "");
			}else{
				$this->success('注册成功', "");
			}
			die;
		}
	
		$sn="S".substr(time(),2);
		$this->assign('sn',$sn);//编号
		$this->meta_title = '分支管理';
		//$this->assign('__CURRENT_CATEGORY__',0);
        Cookie('__forward__', $_SERVER['REQUEST_URI']);
        $this->display();

    }
	
	
	public function yewuyuan(){

		if($_POST){
			$data=$_POST;
			$data['parentid']=$parent_id=$this->__USER__[uid];

			$data['password']=user_md5(substr($_POST['username'],3));
			$data['ctime']=time();
			$data['status']=1;
			$data['usertype']=$_POST['usertype'];
		
		
		
		
		
			$r=D('User')->add($data);
			if($r){
				$this->success('注册成功', "");
				
				

				
				
				
			}else{
				$this->success('注册成功', "");
			}
			die;
		}
	
		$sn="Y".substr(time(),2);
		$this->assign('sn',$sn);//编号
		$this->meta_title = '分支管理';
		//$this->assign('__CURRENT_CATEGORY__',0);
        Cookie('__forward__', $_SERVER['REQUEST_URI']);
        $this->display();

    }

   
}
