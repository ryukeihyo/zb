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
class FinanceController extends HomeController{
	
	
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
			
			if(count(array_filter($_POST))<7){
				
				$this->error('请把资料填写完整后提交！');
				exit;
			}
			
			$_POST['uid']=$this->__USER__['uid'];
			$_POST['addtime']=time();
			
			$r=D("Jifenchongzhi")->add($_POST);
			
			if($r){
				$this->success('操作成功', "");
			}else{
				$this->error('操作失败');
			}
			exit;
		}
		$map['uid']=$this->__USER__['uid'];
		$list=D("Jifenchongzhi")->where($map)->page(!empty($_GET["p"])?$_GET["p"]:1, 4)->order("id desc")->select();
		$page = new \Common\Util\Page(D('Jifenchongzhi')->where($map)->count(), 4);
		
		$this->assign('volist',$list);
		$this->assign('empty','<tr><td colspan="8">暂无数据</td></tr>');
        $this->assign('page', $page->show());
		$this->meta_title = '商家积分充值';
		//$this->assign('__CURRENT_CATEGORY__',0);
        Cookie('__forward__', $_SERVER['REQUEST_URI']);
        $this->display();

    }
	
	 public function f1111(){
		 
		 if($_POST['act']=='edit'){
			 
			 $data['id']=$_POST['id'];
			 $data['isallow']=1;
			 $r=D("Jifenchongzhi")->save($data);
			 
			 if($r){
				 
				echo 1; 
			 }else{
				 
				echo 0;
			 }
			 
			 
			 exit;
		 }
		
		//当日新增业绩
		
		$dtotal=D("Jifenchongzhi")->where("addtime>".strtotime(date('Y-m-d',time())))->field("sum(money) as total")->find();
		
		
		$this->assign('dtotal', $dtotal['total']>0?$dtotal['total']:0);
		//月累计新增业绩
		$mtotal=D("Jifenchongzhi")->where("addtime>".strtotime(date('Y-m',time())))->field("sum(money) as total")->find();
		
		$this->assign('mtotal', $mtotal['total']>0?$mtotal['total']:0);
		
		//总累计新增业绩
		
		$total=D("Jifenchongzhi")->field("sum(money) as total")->find();

		$this->assign('total', $total['total']);
		
		
		if($_GET['starttime']&&$_GET['endtime']){
			
			$map['addtime'] = array(between,array(strtotime($_GET['starttime']),strtotime($_GET['endtime'])));
		}
		 
		$list=D("Jifenchongzhi")->where($map)->page(!empty($_GET["p"])?$_GET["p"]:1, 8)->order("id desc")->select();
		
		foreach($list as $key=>$val){
			
			
			 if($_GET['realname']){
			 
				 $maps['realname']=$_GET['realname'];

				 
			 }
			 $maps['id']=$val['uid'];
			
			$u=D("User")->where( $maps)->find();
			if($u){
				$list[$key]['username']=$u['username'];
				$list[$key]['mingcheng']=$u['realname'];
				$newlist[]=$list[$key];
			}
			
		}
		
		
		$page = new \Common\Util\Page(D('Jifenchongzhi')->where($map)->count(), 8);

		 
		$this->assign('volist',$newlist);
		$this->assign('empty','<tr><td colspan="8">暂无数据</td></tr>');
        $this->assign('page', $page->show());
		$this->meta_title = '积分申请';
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
	 
	 
	 public function f2222(){
		 
		 
		 if($_POST['act']=='edit'){
			 
			 $data['id']=$_POST['id'];
			 $data['status']=1;
			 $r=D("Jifenchongzhi")->save($data);
			 
			 if($r){
				 
				 //给商户增加积分
				 
				 $jifen=D("Jifenchongzhi")->where("id=".$data['id'])->find();
				 $uinfo=D("User")->where("id=".$jifen['uid'])->find();
				 
				 $sumjifen=$jifen['money']+$uinfo['score'];
				 $uinfo['score']=$sumjifen;
				 
				 D("User")->save($uinfo);
				 
				echo 1; 
			 }else{
				 
				echo 0;
			 }
			 
			 
			 exit;
		 }
		 
		 
		 
		 
		 //当日新增业绩
		
		$dtotal=D("Jifenchongzhi")->where("addtime>".strtotime(date('Y-m-d',time())))->field("sum(money) as total")->find();
		
		
		$this->assign('dtotal', $dtotal['total']>0?$dtotal['total']:0);
		//月累计新增业绩
		$mtotal=D("Jifenchongzhi")->where("addtime>".strtotime(date('Y-m',time())))->field("sum(money) as total")->find();
		
		$this->assign('mtotal', $mtotal['total']>0?$mtotal['total']:0);
		
		//总累计新增业绩
		
		$total=D("Jifenchongzhi")->field("sum(money) as total")->find();

		$this->assign('total', $total['total']);
		 
		 
		 
		 
		 
		 
		 $map['isallow']=1;
		 
		 $list=D("Jifenchongzhi")->where($map)->page(!empty($_GET["p"])?$_GET["p"]:1, 8)->order("id desc")->select();
		
		foreach($list as $key=>$val){
			
			if($_GET['realname']){
			 
				 $maps['realname']=$_GET['realname'];

				 
			 }
			 $maps['id']=$val['uid'];
			
			$u=D("User")->where( $maps)->find();
			if($u){
				$list[$key]['username']=$u['username'];
				$list[$key]['mingcheng']=$u['realname'];
				$newlist[]=$list[$key];
			}
			
		}
		
		
		$page = new \Common\Util\Page(D('Jifenchongzhi')->where($map)->count(), 8);

		 
		$this->assign('volist',$newlist);
		$this->assign('empty','<tr><td colspan="8">暂无数据</td></tr>');
        $this->assign('page', $page->show());
		$this->meta_title = '积分申请';
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
	 
	 
	 
	 public function f5555(){
		 
		 
		 if($_POST['act']=='edit'){
			 
			 $data['id']=$_POST['id'];
			 $data['status']=1;
			 $data['uptime']=time();
			 $r=D("Paylog")->save($data);
			 
			 if($r){
				
				 
				echo 1; 
			 }else{
				 
				echo 0;
			 }
			 
			 
			 exit;
		 }
		 
		 
		 
		 
		 $map['isallow']=1;
		 
		 $list=D("Paylog")->where($map)->page(!empty($_GET["p"])?$_GET["p"]:1, 8)->order("id desc")->select();
		
		foreach($list as $key=>$val){
			
			$u=D("User")->where("id=".$val['uid'])->find();
			$list[$key]['username']=$u['username'];
			$list[$key]['mingcheng']=$u['realname'];
			
		}
		
		
		$page = new \Common\Util\Page(D('Paylog')->where($map)->count(), 8);

		 
		$this->assign('volist',$list);
		$this->assign('empty','<tr><td colspan="8">暂无数据</td></tr>');
        $this->assign('page', $page->show());
		$this->meta_title = '提现管理';
		//$this->assign('__CURRENT_CATEGORY__',0);
        Cookie('__forward__', $_SERVER['REQUEST_URI']);
		 
		 
		 
		 
		 
		 
		 
		 $this->display();
	 }
	 
	 
	 public function f6666(){
		 
		 
		 if($_POST['act']=='search'){
			 
			 $map['username']=$_POST['username'];
			 
			$info=D("User")->where($map)->find();
			 
			$this->assign('info', $info);

		 }
		 
		 if($_POST['act']=='chongzhi'){
			 
			$map['username']=$_POST['username'];
			 
			$info=D("User")->where($map)->find();
			 
			$data['suname']=$_POST['username'];
			$data['number']=$_POST['number'];
			$data['yue']=$info['zebaib']+$_POST['number'];
			$data['uid']=$info['id'];
			$data['is_confirm']=0;
			$data['status']=0;
			$data['add_time']=time();
			 
			 $r=D("Zebaib")->add($data);
			
			
			if($r){
				$this->success('操作成功', "");
			}else{
				$this->error('操作失败');
			}
			exit;

		 }
		 

		 
		 $list=D("Zebaib")->page(!empty($_GET["p"])?$_GET["p"]:1, 8)->order("id desc")->select();
		
		
		
		
		$page = new \Common\Util\Page(D('Zebaib')->count(), 8);

		 
		$this->assign('volist',$list);
		$this->assign('empty','<tr><td colspan="8">暂无数据</td></tr>');
        $this->assign('page', $page->show());
		$this->meta_title = '泽百币充值管理';
		//$this->assign('__CURRENT_CATEGORY__',0);
        Cookie('__forward__', $_SERVER['REQUEST_URI']);
		 
		 
		 
		 
		 
		 
		 
		 $this->display();
	 }
	 
	public function zebaib(){
	 
	 
	 
		$map['uid']=$this->__USER__['uid'];
		$list=D("Zebaib")->where($map)->page(!empty($_GET["p"])?$_GET["p"]:1, 4)->order("id desc")->select();
		$page = new \Common\Util\Page(D('Zebaib')->where($map)->count(), 4);
		
		$this->assign('volist',$list);
		$this->assign('empty','<tr><td colspan="8">暂无数据</td></tr>');
        $this->assign('page', $page->show());
		$this->meta_title = '泽百币充值';
		//$this->assign('__CURRENT_CATEGORY__',0);
        Cookie('__forward__', $_SERVER['REQUEST_URI']);
        $this->display();
	 

	}
	public function confirm(){
	 
	 
	 
		$map['id']=$_GET['id'];
		$map['status']=0;
		$info=D("Zebaib")->where($map)->find();
		if($info){
			$info['status']=1;
			$info['is_confirm']=1;
			$info['confirm_time']=time();
			
			D("Zebaib")->save($info);
			
			$maps['id']=$info['uid'];
			
			$r=D("User")->where($maps)->setInc("zebaib",$info['number']);
			
			
			if($r){
				$this->success('操作成功', "");
			}else{
				$this->error('操作失败');
			}
			exit;
			
		}else{
		
			$this->error('已经确认，无须再次确认');
			exit;
		}

	}
}
