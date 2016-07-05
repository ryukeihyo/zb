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
class RechargeController extends HomeController{
	
	
	protected function _initialize(){
        parent::_initialize();
        $this->is_login();
    }
	
    /**
     * 用户列表
     * @author jry <598821125@qq.com>
     */
    public function index(){
		$this->meta_title = '商家现金充值';
		if($_GET){
			
			$mobile=$_GET['mobile'];
			$maps['mobile']=$mobile;
			
			$uinfo=D("User")->where($maps)->find();

			$this->assign('uinfo', $uinfo);
			
			//by liux  增加单独商家预存功能  start
			
			$m['uid']=$uinfo['id']; //客户ID
			$m['sid']=$this->__USER__['uid']; //商家ID

			
			$yucuninfo=D("UserYucun")->where($m)->find();
			//print_r($yucuninfo);
			if(empty($yucuninfo)){
			
				$this->assign('yucun_money', "0.00");
			
			}else{
			
				$this->assign('yucun_money', $yucuninfo['money']);
			}
			
			//by liux  增加单独商家预存功能  end
			
			
			
			
			
			
		
		}

		
		if($_POST){
			
			//充值帐户预存
			
			$yucun=intval($_POST['fee']);
			
			if(empty($yucun)){
				$this->error('请输入正确的金额！');
				exit;
			}
			
			
			//by liux  增加单独商家预存功能-充值  start
			
			
			$id=intval($_POST['id']);
			$maps['uid']=$id;//用户ID
			$maps['sid']=$this->__USER__['uid']; //商家ID
			$yucuninfo=D("UserYucun")->where($maps)->find();
			
			if(empty($yucuninfo)){
				$data['uid']=$id;//用户ID
				$data['sid']=$this->__USER__['uid']; //商家ID
				$data['money']=$yucun;//预存金额
				$r=D("UserYucun")->add($data); // 第一次要增加记录
			
			}else{
			
				$r=D("UserYucun")->where($maps)->setInc('money',$yucun); // 用户的预存
			}

			//by liux  增加单独商家预存功能  end
			
			
			
			
			
			if($r){
			
				$data['uid']=$this->__USER__['uid'];
				$data['money']=$yucun;
				$data['beizhu']=$_POST['beizhu'];
				$data['realname']=$_POST['realname'];
				$data['mobile']=$_POST['mobile'];
				$data['yue']=$_POST['yue']+$yucun;
				$data['addtime']=time();
				D("Recharge")->add($data);
				
			}
			
			
			
			
			$this->success('充值成功！');
			
			
			
			exit;
		}
		$map['uid']=$this->__USER__['uid'];
		$list=D("Recharge")->where($map)->page(!empty($_GET["p"])?$_GET["p"]:1, 2)->order("id desc")->select();
		$page = new \Common\Util\Page(D('Recharge')->where($map)->count(), 2);
		
		$this->assign('volist',$list);
		$this->assign('empty','<tr><td colspan="8">暂无数据</td></tr>');
        $this->assign('page', $page->show());
		
		//$this->assign('__CURRENT_CATEGORY__',0);
        Cookie('__forward__', $_SERVER['REQUEST_URI']);
        $this->display();

    }
	
	 

   
}
