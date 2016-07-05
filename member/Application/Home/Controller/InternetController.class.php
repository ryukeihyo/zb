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
class InternetController extends HomeController{
	
	
	protected function _initialize(){
        parent::_initialize();
        $this->is_login();
    }
	
    /**
     * 用户列表
     * @author jry <598821125@qq.com>
     */
    public function index(){

		$this->meta_title = '互联管理';
		//$this->assign('__CURRENT_CATEGORY__',0);
        Cookie('__forward__', $_SERVER['REQUEST_URI']);
        $this->display();

    }

   
}
