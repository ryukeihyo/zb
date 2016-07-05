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
class LogController extends HomeController{
	
	
	protected function _initialize(){
        parent::_initialize();
        $this->is_login();
    }
	
    /**
     * 用户列表
     * @author jry <598821125@qq.com>
     */
    public function index(){
		
		if($POST['username']){
			
			//$map['username']=$POST['username'];
			
		}
		
		if($this->user_info[usertype]==6){
			//个人 6
			$map['uid']=$this->__USER__['uid'];
			
				
		}else{
			//商家 5
			$map['payuid']=$this->__USER__['uid'];
			
		}
		

		
		
		
		
		$list=D("Xiaofei")->where($map)->page(!empty($_GET["p"])?$_GET["p"]:1, C('ADMIN_PAGE_ROWS'))->order("id desc")->select();
		
		
		//查询被赠送积分的会员信息
		foreach($list as $key=>$val){
			if($val['uid']){
				$list[$key]['memeber']=D("User")->where("id=".$val['uid'])->find();
			}
		}
		
		$page = new \Common\Util\Page(D('Xiaofei')->where($map)->count(), 8);
		
		$this->assign('volist',$list);
		$this->assign('empty','<tr><td colspan="8">暂无数据</td></tr>');
        $this->assign('page', $page->show());
		$this->meta_title = '消费记录列表';
		//$this->assign('__CURRENT_CATEGORY__',0);
        Cookie('__forward__', $_SERVER['REQUEST_URI']);
        $this->display();

    }
	
	
	public function shop(){

		if($_GET['username']){
			
			$map['username']=$_GET['username'];
			
		}
	
		$map['managenumber']=$this->__USER__['username'];
		
		
		
		
		$list=D("User")->where($map)->page(!empty($_GET["p"])?$_GET["p"]:1, C('ADMIN_PAGE_ROWS'))->order("id desc")->select();
	
		//查询被赠送积分的会员信息
		foreach($list as $key=>$val){
			
				$r=D("User")->where("parentid=".$val['id'])->field("count(*) as total")->find();
				
				$list[$key]['memebers']=$r['total'];
				//消费积分
				
				$rs=D("Log")->where("parentid=".$val['id'])->field("sum(jifen) as total")->find();
			
				$list[$key]['jifen']=$rs['total'];
				
				
			
		}
		
		$page = new \Common\Util\Page(D('User')->where($map)->count(), 8);
		
		$this->assign('volist',$list);
		$this->assign('empty','<tr><td colspan="8">暂无数据</td></tr>');
        $this->assign('page', $page->show());
		$this->meta_title = '消费记录列表';
		//$this->assign('__CURRENT_CATEGORY__',0);
        Cookie('__forward__', $_SERVER['REQUEST_URI']);
		
        if($_GET['act']){
			header("Content-type:application/vnd.ms-excel");
			header("Content-Disposition:attachment;filename=".date('Y-m-d',time())."导出.xls");
			$this->display('output');
			
		 }else{
			
				$this->display();			
			 
		 }

    }
	
	
	
	
	/**
     * 
     * @author jry <598821125@qq.com>
     */
    public function type(){
		
		if($POST['username']){
			
			//$map['username']=$POST['username'];
			
		}
		
		if($this->user_info[usertype]==6){
			//个人 6
			$map['uid']=$this->__USER__['uid'];
			
				
		}else{
			//商家 5
			$map['payuid']=$this->__USER__['uid'];
			
		}

	
		$this->assign('typeid',$_GET['id']);
		
		$map['typeid']=$_GET['id'];
		
		
		$list=D("Xiaofei")->where($map)->page(!empty($_GET["p"])?$_GET["p"]:1, C('ADMIN_PAGE_ROWS'))->order("id desc")->select();
		
		
		
		
		
		
		//查询被赠送积分的会员信息
		foreach($list as $key=>$val){
			if($val['uid']){
				$list[$key]['memeber']=D("User")->where("id=".$val['uid'])->find();
			}
		}
		
		$page = new \Common\Util\Page(D('Xiaofei')->where($map)->count(), 8);
		
		$this->assign('volist',$list);
		
		
		$this->assign('zebaibi',$this->user_info['zebaib']);

		
		
		$this->assign('volist',$list);
		
		
		
		$this->assign('empty','<tr><td colspan="8">暂无数据</td></tr>');
        $this->assign('page', $page->show());
		$this->meta_title = '消费记录列表';
		//$this->assign('__CURRENT_CATEGORY__',0);
        Cookie('__forward__', $_SERVER['REQUEST_URI']);
        $this->display("index");

    }
	
	

   
}
