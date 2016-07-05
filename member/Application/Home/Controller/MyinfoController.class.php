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
class MyinfoController extends HomeController{
	
	
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
			
			
			$data=$_POST;
			$r=D('User')->save($data);
			if($r){
				$this->success('编辑成功', "");
			}else{
				$this->error('编辑失败');
			}
			
			die;
		}
		
		
		//$id = I('get.id');
		//$map['id']=$id;
		$map['id']=$this->user_info[id];
		$info=D('User')->where($map)->find();
		
		
		$this->assign('info', $info);
		$this->meta_title = '我的信息';
		Cookie('__forward__', $_SERVER['REQUEST_URI']);
        $this->display();
		
		//$this->meta_title = '数据管理';
		//$this->assign('__CURRENT_CATEGORY__',0);
      //  Cookie('__forward__', $_SERVER['REQUEST_URI']);
        //$this->display();

    }
	
	

   
}
