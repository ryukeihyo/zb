<?php
// +----------------------------------------------------------------------
// | CoreThink [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.corethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: jry <598821125@qq.com> <http://www.corethink.cn>
// +----------------------------------------------------------------------
namespace Home\Controller;
use Common\Controller\CommonController;
/**
 * 前台公共控制器
 * 为防止多分组Controller名称冲突，公共Controller名称统一使用分组名称
 * @author jry <598821125@qq.com>
 */
class HomeController extends CommonController{
    /**
     * 初始化方法
     * @author jry <598821125@qq.com>
     */
    protected function _initialize(){
        
		//判断必须用谷歌浏览器
		/*
		 if(!strpos($_SERVER["HTTP_USER_AGENT"],"Chrome"))  {
			 header("Content-type: text/html; charset=utf-8"); 
			 echo "该软件必须使用谷歌浏览器来访问，请先下载谷歌浏览器！";die;
		 }
		
		*/
		
		//系统开关
        if(!C('TOGGLE_WEB_SITE')){
            $this->error('站点已经关闭，请稍后访问~');
        }

        $this->assign('meta_keywords', C('WEB_SITE_KEYWORD'));
        $this->assign('meta_description', C('WEB_SITE_DESCRIPTION'));
        $this->assign('__USER__', session('user_auth')); //用户登录信息
        $this->assign('__NEW_MESSAGE__', D('UserMessage')->newMessageCount() ? : null); //获取用户未读消息数量
        $this->assign('__CURRENT_TABLE_ID__', D('PublicComment')->model_type_id(CONTROLLER_NAME)); //根据当前控制器及配置数组获取评论数据表ID
        $this->assign('__CONTROLLER_NAME__', strtolower(CONTROLLER_NAME)); //当前控制器名称
        $this->assign('__ACTION_NAME__', strtolower(ACTION_NAME)); //当前方法名称
		
		//登陆后的会员信息
		$uid=$this->__USER__[uid];
		if($uid){
			$user_info = D('User')->where($con)->find($uid);
			
			switch($user_info['usertype']){
				
				case 0:
					$user_info['usertypes']='分公司';
					break;
				case 1:
					$user_info['usertypes']='代理商';
					break;
				case 2:
					$user_info['usertypes']='事业部';
					break;
				case 3:
					$user_info['usertypes']='业务部';
					break;
				case 4:
					$user_info['usertypes']='业务组';
					break;
				case 5:
					$user_info['usertypes']='商家';
					break;
				case 6:
					$user_info['usertypes']='个人用户';
					break;
				case 7:
					$user_info['usertypes']='财务';
					break;
				case 8:
					$user_info['usertypes']='特号管理员';
					break;
				case 9:
					$user_info['usertypes']='业务员';
					break;
				case 10:
					$user_info['usertypes']='消费顾问';
					break;
					
					
					
					          
				
			}
			
			
			$this->assign('user_info', $user_info);
		}

		if($user_info['usertype']==7){
			//财务的权限
			$arr=array('Finance','Document','User','Index');
			
			if(!in_array(CONTROLLER_NAME,$arr)){
				
				$this->error('无权限操作');
			}

			if($user_info['id']=='41601'){
				if(ACTION_NAME=='f2222'||ACTION_NAME=='f5555'){
					
					$this->error('无权限操作',U('Home/Finance/f1111'));
				}
				$caiwuurl=U('Home/Finance/f1111');
				
			}elseif($user_info['id']=='41602'){
				
				if(ACTION_NAME=='f1111'||ACTION_NAME=='f5555'){
					
					$this->error('无权限操作',U('Home/Finance/f2222')	);
				}
				
				$caiwuurl=U('Home/Finance/f2222');
				
			}
			elseif($user_info['id']=='41604'){
				//echo  11111111;die;
				if(ACTION_NAME=='f1111'||ACTION_NAME=='f2222'){
					
					$this->error('无权限操作',U('Home/Finance/f5555')	);
				}
				
				$caiwuurl=U('Home/Finance/f5555');
				
			}
			elseif($user_info['id']=='70326'){
				//echo  11111111;die;
				if(ACTION_NAME=='f1111'||ACTION_NAME=='f2222'){
					
					$this->error('无权限操作',U('Home/Finance/f6666')	);
				}
				
				$caiwuurl=U('Home/Finance/f6666');
				
			}
			$this->assign('caiwuurl', $caiwuurl);
		}
		
		
		if($user_info['usertype']==8){
			//特号管理员的权限
			$arr=array('Memeber','Document','User','Index');
			
			if(!in_array(CONTROLLER_NAME,$arr)){
				
				$this->error('无权限操作',U('Home/Memeber/f3333'));
			}
			
			
			if($user_info['id']=='8908'){
					
				$tehaourl=U('Home/Memeber/f3333');	
			}
			
			$this->assign('tehaourl', $tehaourl);
		}
		
		//商家无权限查看团队管理

		if($user_info['usertype']==5){
			
			$arr=array('Team');
			
			
			if(in_array(CONTROLLER_NAME,$arr)){
				
				$this->error('无权限操作',U('Home/Index/index'));
			}
			
			
		}
		
		
		
		
		//读取新闻列表 最新咨询
		$map['status'] = array('eq', 1);
		$map['cid'] = array('eq', 31);
		$document_list = D('Document')->order('sort desc,id desc')->where($map)->select();
		
		//获取该分类绑定文档模型的主要字段
		$document_type_object = D('DocumentType');
		$document_type_main_field = $document_type_object->getFieldById($category_info['doc_type'],'main_field');
		$document_type_main_field = D('DocumentAttribute')->getFieldById($document_type_main_field, 'name');

		//获取扩展表的信息
		foreach($document_list as &$doc){
			$doc_type_name = $document_type_object->getFieldById($doc['doc_type'], 'name');
			$temp = array();
			$temp = D('Document'.ucfirst($doc_type_name))->find($doc['id']);
			$doc = array_merge($doc, $temp);

			//给文档主要字段赋值，如：文章标题、商品名称
			$doc['main_field'] = $doc[$document_type_main_field];
		}
		$this->assign('volists31', $document_list);
		
		
		
		//读取新闻列表 促销活动
		$map['status'] = array('eq', 1);
		$map['cid'] = array('eq', 33);
		$document_list = D('Document')->order('sort desc,id desc')->where($map)->select();
		
		//获取该分类绑定文档模型的主要字段
		$document_type_object = D('DocumentType');
		$document_type_main_field = $document_type_object->getFieldById($category_info['doc_type'],'main_field');
		$document_type_main_field = D('DocumentAttribute')->getFieldById($document_type_main_field, 'name');

		//获取扩展表的信息
		foreach($document_list as &$doc){
			$doc_type_name = $document_type_object->getFieldById($doc['doc_type'], 'name');
			$temp = array();
			$temp = D('Document'.ucfirst($doc_type_name))->find($doc['id']);
			$doc = array_merge($doc, $temp);

			//给文档主要字段赋值，如：文章标题、商品名称
			$doc['main_field'] = $doc[$document_type_main_field];
		}
		$this->assign('volists33', $document_list);
		
		//读取新闻列表 文化活动
		$map['status'] = array('eq', 1);
		$map['cid'] = array('eq', 32);
		$document_list = D('Document')->order('sort desc,id desc')->where($map)->select();
		
		//获取该分类绑定文档模型的主要字段
		$document_type_object = D('DocumentType');
		$document_type_main_field = $document_type_object->getFieldById($category_info['doc_type'],'main_field');
		$document_type_main_field = D('DocumentAttribute')->getFieldById($document_type_main_field, 'name');

		//获取扩展表的信息
		foreach($document_list as &$doc){
			$doc_type_name = $document_type_object->getFieldById($doc['doc_type'], 'name');
			$temp = array();
			$temp = D('Document'.ucfirst($doc_type_name))->find($doc['id']);
			$doc = array_merge($doc, $temp);

			//给文档主要字段赋值，如：文章标题、商品名称
			$doc['main_field'] = $doc[$document_type_main_field];
		}
		$this->assign('volists32', $document_list);
		
    }

    /**
     * 用户登录检测
     * @author jry <598821125@qq.com>
     */
    protected function is_login(){
        //用户登录检测
        $uid = is_login();
        if($uid){
            return $uid;
        }else{
            $data['login'] = 1;
			
			if($_SERVER[HTTP_HOST]=='www.xn--sxws6r7pv.cn'){
			
				header("location:".U('Home/User/plogin'));
				
			}else{
			
				header("location:".U('Home/User/login'));
			
			}
			
			
            //$this->error('请先登陆', U('Home/User/login'), $data);
        }
		exit;
    }

    /**
     * 模板显示 调用内置的模板引擎显示方法
     * @access protected
     * @param string $templateFile 指定要调用的模板文件
     * 默认为空 由系统自动定位模板文件
     * @param string $charset 输出编码
     * @param string $contentType 输出类型
     * @param string $content 输出内容
     * @param string $prefix 模板缓存前缀
     * @return void
     * @author jry <598821125@qq.com>
     */
    protected function display($templateFile='', $charset='utf-8', $contentType='', $content='', $prefix='') {
        $controller_name = explode('/', CONTROLLER_NAME); //获取ThinkPHP控制器分级时控制器名称
        if($controller_name[0] === 'Home'){
            $templateFile = $controller_name[1].'/'.ACTION_NAME;
        }
        $this->view->display($templateFile, $charset, $contentType, $content, $prefix);
    }
}
