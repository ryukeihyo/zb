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
class DataController extends HomeController{
	
	
	protected function _initialize(){
        parent::_initialize();
        $this->is_login();
    }
	
    /**
     * 用户列表
     * @author jry <598821125@qq.com>
     */
    public function index(){
		$parent_id=$this->__USER__[uid];

	
		//当日新增开通
		$dstart=time()-60*60*24;
		$dend=time();
		$mapss['ctime']  = array('between',"$dstart,$dend");
		$mapss['parentid']=$parent_id;
		$total=D('User')->where($mapss)->field("count(*) as dnum")->find();
		$this->assign('dnum', $total['dnum']);
		
		
		//月开通数量累计
		$ystart=time()-60*60*24*30;
		$yend=time();
		$mapss['ctime']  = array('between',"$ystart,$yend");
		$mapss['parentid']=$parent_id;
		$total=D('User')->where($mapss)->field("count(*) as ynum")->find();
		D('User')->getlastsql();
		$this->assign('ynum', $total['ynum']);
		
		
		if($_POST['username']){
			
			$map['username']=array('like',"%".$_POST['username']."%");
			
		}

		
		//总开通数量累计
		$total=D('User')->where("parentid=$parent_id")->field("count(*) as num")->find();

		$this->assign('num', $total['num']);
	
		
		$map['parentid']=$parent_id;
	

		$list = D('User')->page(!empty($_GET["p"])?$_GET["p"]:1, 8)
                                                      ->order('sort desc,id desc')
                                                      ->where($map)
                                                      ->select();
		$page = new \Common\Util\Page(D('User')->where($map)->count(), 8);
		
		$this->assign('volist', $list);
		
		$this->assign('empty','<tr><td colspan="9">暂无数据</td></tr>');
        $this->assign('page', $page->show());
		$this->meta_title = '数据管理';
		//$this->assign('__CURRENT_CATEGORY__',0);
        Cookie('__forward__', $_SERVER['REQUEST_URI']);
        $this->display();

    }
	
	/**
     * 编辑商家信息
     * @author liux <1991881268@qq.com>
     */
    public function edit(){
		
		if($_POST){
			
			
			$data=$_POST;
			$r=D('User')->save($data);
			if($r){
				$this->success('编辑成功', "");
			}else{
				$this->error('编辑失败');
			}
			
			die;
		}
		
		
		$id = I('get.id');
		$map['id']=$id;
		$map['parentid']=$this->user_info[id];
		$info=D('User')->where($map)->find();
		
		
		$this->assign('info', $info);
		$this->meta_title = '信息编辑';
		Cookie('__forward__', $_SERVER['REQUEST_URI']);
        $this->display();
		
	}
	
	/**
     * 编辑商家信息
     * @author liux <1991881268@qq.com>
     */
    public function ajax(){
		
		
		$_POST['status'];
		$_POST['id'];
		$_POST['parentid']=$this->user_info[id];
		$data=$_POST;
		$r=D('User')->save($data);
		if($r){
			$status=1;
		}else{
			$status=0;
		}
		
		echo  $status;
		exit;
		
	}

   
}
