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
class BonusController extends HomeController{
	
	
	protected function _initialize(){
        parent::_initialize();
        $this->is_login();
    }
	
    /**
     * 用户列表
     * @author jry <598821125@qq.com>
     */
    public function index(){

	
	
		if($_POST){
			
			$money=$_POST['money'];
			
			$oldmoney=$this->user_info['money'];
			
			if($oldmoney<$money){
				header("Content-type: text/html; charset=utf-8"); 
				echo '<script>alert("您的分红余额不足！");history.back(-1)</script>';
				exit;
			}else{
				
				$data['money']=$this->user_info['money']-$money;
				$data['zebaib']=$this->user_info['zebaib']+$money;
				$data['id']=$this->user_info['id'];
				
				$r=D("User")->save($data);
				
				if($r){
					
					$datas['uid']=$this->user_info['id'];
					$datas['yue']=$data['money'];
					$datas['money']=$money;
					$datas['addtime']=time();
					D("Moneylog")->add($datas);
					
					
					$this->success("转入成功");
					
					
					
					
					
				}else{
					$this->error("转入失败");
				}
				exit;
						
			}
			
			
			
		}
	
	
	
		
		//上次分红
		//查询上次分红的时间  个人
		
		if($this->user_info['usertype']==6){
			
				$type=1;
		}elseif($this->user_info['usertype']==9){
			
				$type=3;
		}else{
			
				$type=2;
		}
		
		
		
		$max=D("Checkout")->where("type=$type")->field("max(addtime) as lasttime")->find();
		$lasttime=$max['lasttime'];//上次最后结算时间
		if($lasttime){
			$info=D("Fenhonglog")->where("addtime=".$lasttime." and uid=".$this->user_info['id'])->find();
		}
		//累计分红
		
		$infototal=D("Fenhonglog")->where("uid=".$this->user_info['id'])->field("sum(money) as total")->find();
	
		
		
		
		

	
		$this->assign('user_info',$this->user_info);//
		$this->assign('money',$info[money]);//上次分红
		$this->assign('infototal',$infototal['total']);//累计分红
	
		//查询转账记录
	
	
	
		$map['uid']=$this->__USER__['uid'];
		$list=D("Moneylog")->where($map)->page(!empty($_GET["p"])?$_GET["p"]:1, 6)->order("id desc")->select();
		


		$page = new \Common\Util\Page(D('Moneylog')->where($map)->count(), 6);
		
		$this->assign('volist',$list);
		$this->assign('empty','<tr><td colspan="6">暂无数据</td></tr>');
        $this->assign('page', $page->show());
	

	
		$this->meta_title = '奖金管理';
		//$this->assign('__CURRENT_CATEGORY__',0);
        Cookie('__forward__', $_SERVER['REQUEST_URI']);
        $this->display();

    }
	
	
	public function pay(){
		
		
		if($_POST){
			
			
			
			
			
			$money=$_POST['money'];
			
			if($money%100>0){
				header("Content-type: text/html; charset=utf-8"); 
				echo '<script>alert("您的提现金额必须是100的整数倍！");history.back(-1)</script>';
				exit;
				
			}
			
			
			$oldmoney=$this->user_info['money'];
			
			if($oldmoney<$money){
				header("Content-type: text/html; charset=utf-8"); 
				echo '<script>alert("您的分红余额不足！");history.back(-1)</script>';
				exit;
			}else{
				
				$data['money']=$this->user_info['money']-$money;
				//$data['zebaib']=$this->user_info['zebaib']+$money;
				$data['id']=$this->user_info['id'];
				
				$r=D("User")->save($data);
				
				if($r){
					
					$datas['uid']=$this->user_info['id'];
					//$datas['yue']=$data['money'];
					$datas['money']=$money;

					$datas['addtime']=time();
					D("Paylog")->add($datas);
					
					
					$this->success("提现申请成功");
					
					
					
					
					
				}else{
					$this->error("提现申请失败");
				}
				exit;
						
			}
			
			
			
		}
		
		
		
		
		
		//查询提现记录
	
	
	
		$map['uid']=$this->__USER__['uid'];
		$list=D("Paylog")->where($map)->page(!empty($_GET["p"])?$_GET["p"]:1, 4)->order("id desc")->select();
		


		$page = new \Common\Util\Page(D('Paylog')->where($map)->count(), 4);
		
		$this->assign('volist',$list);
		$this->assign('empty','<tr><td colspan="7">暂无数据</td></tr>');
        $this->assign('page', $page->show());
		
		
		

		$this->meta_title = '提现管理';
		//$this->assign('__CURRENT_CATEGORY__',0);
        Cookie('__forward__', $_SERVER['REQUEST_URI']);
        $this->display();

    }

   
}
