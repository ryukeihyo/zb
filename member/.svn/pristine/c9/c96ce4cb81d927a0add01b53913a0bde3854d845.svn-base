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
 * 前台默认控制器
 * @author jry <598821125@qq.com>
 */
class SystemController extends HomeController{
    
	protected function _initialize(){
        parent::_initialize();
        
    }
	
	/**
     * 默认方法
     * @author jry <598821125@qq.com>
     */
    public function index(){
        
		$this->is_login();
		//支付操作
		if($_POST['ajax']){
			
			$username=$_POST['username'];
			$jifen=$_POST['jifen'];
			$fee=$_POST['fee'];
			
			
			//判断当前的商家积分是否足够充值	
			
			if($this->user_info['score']<$jifen){
				//商家积分不足  返回-2  提示请充值
				//exit("-2");
				
			}
			
			
			
			
			
			
			
			
			
			$res=D("User")->where("username='".$username."'")->find();
			
			if(!$res){
				
				$res=D("User")->where("mobile='".$username."'")->find();
				
			}
			
			
			$paymethod=$_POST['paymethod'];
			switch($paymethod){
				
				case 'xianjin':
					$sjinfo=D("User")->where("id=".$this->__USER__[uid])->find();
				
					//用户增加积分
					$res['zebaib']=$res['zebaib']+$jifen;
		
					$r=D("User")->save($res);
					
					$data['uid']=$res['id'];
					$data['addtime']=time();
					$data['jifen']=$jifen;
					$data['money']=$_POST['fee'];
					$data['guanlifei']=$_POST['fee']*$sjinfo['guanlifei']/100;
				//	$data['parentid']=$this->__USER__[uid];
					$data['parentid']=$res['parentid'];//所属商家
					
					$data['payuid']=$this->__USER__[uid];//支付商家
					$data['typeid']=1;//支付类型
					
					D("Log")->add($data);
					//用户增加积分 end
					
					//商家扣积分

					
					$sjinfo['score']=$sjinfo['score']-$jifen-$_POST['fee']*$sjinfo['guanlifei']/100;
					D("User")->save($sjinfo);
					
					//商家扣积分 end

					echo $r;
					exit;
				
				break;
				case 'yucun':
				
					//app pay
					if($_POST['app']){
					//fee:$("input[name='fee']").val(),username:$("#username").val()
						/*
						
						查看10分钟内是否有订单
						
						*/
						
						$map['uid']=$res['id'];
						$map['status']=1;
						$map['addtime']=array("gt",time()-600);
						
						$r=D("App")->where($map)->find();
						
						if($r){
						
							exit('-2');//有订单还没确认
						
						}
						
						
						$data['fee']=$fee;
						$data['type']=1;
						$data['uid']=$res['id'];
						$data['mobile']=$res['mobile'];
						$data['addtime']=time();
						$data['status']=1;
						$data['sid']=$this->__USER__['uid']; //商家ID
						echo  D("App")->add($data);
						
						unset($data['sid']);
						//记录
						
						$data['uid']=$res['id'];
						$data['addtime']=time();
						$data['jifen']=$jifen;
						//$data['money']=$_POST['fee'];
					//	$data['parentid']=$this->__USER__[uid];
						$data['parentid']=$res['parentid'];//所属商家
						
						$data['payuid']=$this->__USER__[uid];//支付商家
						$data['typeid']=2;//支付类型
						
						D("Log")->add($data);
						
						
						
						
						exit;
					
					
					}
					
					
					//by liux  增加单独商家预存功能-消费  start
			
			

					$maps['uid']=$res['id'];//用户ID
					$maps['sid']=$this->__USER__['uid']; //商家ID
					$yucuninfo=D("UserYucun")->where($maps)->find();
					
					if(empty($yucuninfo)){
						exit('-1');//预存金额不足
				
					}else{
						if($yucuninfo['money']<=0||$yucuninfo['money']<$_POST['fee']){
							
							exit('-1');//预存金额不足
							
						}
						//$r=D("UserYucun")->where($maps)->setInc('money',$yucun); // 用户的预存
					}
					$r=D("UserYucun")->where($maps)->setDec('money',$fee); // 用户的预存
					
					//by liux  增加单独商家预存功能  end

					
					
					
					
					$data['uid']=$res['id'];
						$data['addtime']=time();
					//	$data['jifen']=$jifen;
						$data['money']=$_POST['fee'];
					//	$data['parentid']=$this->__USER__[uid];
						$data['parentid']=$res['parentid'];//所属商家
						
						$data['payuid']=$this->__USER__[uid];//支付商家
						$data['typeid']=2;//支付类型
						
						D("Log")->add($data);
					
					
					//$r=D("User")->save($data);
					echo $r;
					
					break;
				case 'zebaib':
				
					//检查商家是否有足够的泽百币
					
					$suser=D("User")->where("id=".$this->__USER__[uid])->find();
					
					if($suser['zebaib']<=0||$suser['zebaib']<$_POST['fee']){
					
					
						exit('-1');//则百币不足
						
					}else{
					
					
						//商家扣则百币

	
						$suser['zebaib']=$suser['zebaib']-$_POST['fee'];
						D("User")->save($suser);
						
						//商家扣则百币 end
						
						//用户扣减
						
						
					
					
					}
					
				
				
				
					//app pay
					if($_POST['app']){
					
					
						//用户扣减
						//$res['zebaib']=$res['zebaib']-$fee;
	
						//$r=D("User")->save($res);
						
						//用户扣减 end
						
						/*
						
						查看10分钟内是否有订单
						
						*/
						
						$map['uid']=$res['id'];
						$map['status']=1;
						$map['addtime']=array("gt",time()-600);
						
						$r=D("App")->where($map)->find();
						
						if($r){
						
							exit('-2');//有订单还没确认
						
						}
						
						
						$data['fee']=$fee;
						$data['type']=2;
						$data['uid']=$res['id'];
						$data['mobile']=$res['mobile'];
						$data['addtime']=time();
						$data['status']=1;
						
						echo  D("App")->add($data);
						
						$data['uid']=$res['id'];
						$data['addtime']=time();
						//$data['jifen']=$jifen;
						$data['money']=$_POST['fee'];
					//	$data['parentid']=$this->__USER__[uid];
						$data['parentid']=$res['parentid'];//所属商家
						
						$data['payuid']=$this->__USER__[uid];//支付商家
						$data['typeid']=3;//支付类型
						
						D("Log")->add($data);
						
						
						exit;
					
					
					}
				
				
				
					if($res['zebaib']<=0||$res['zebaib']<$_POST['fee']){
					
					
						exit('-1');//则百币不足
						
					}else{
						//用户扣减
						$res['zebaib']=$res['zebaib']-$fee;
	
						$r=D("User")->save($res);
						
						//用户扣减 end
						
						
						
						
						
						
						//$data['uid']=$res['id'];
						//$data['addtime']=time();
						//$data['jifen']=$jifen;
						//D("Log")->add($data);
						
						$data['uid']=$res['id'];
						$data['addtime']=time();
						//$data['jifen']=$jifen;
						$data['money']=$_POST['fee'];
					//	$data['parentid']=$this->__USER__[uid];
						$data['parentid']=$res['parentid'];//所属商家
						
						$data['payuid']=$this->__USER__[uid];//支付商家
						$data['typeid']=3;//支付类型
						
						D("Log")->add($data);
						
						
						
						echo $r;
						exit;
						
					}
				
				
				break;

				
			}
			
			
			$jifen=$_POST['jifen'];
			exit();
		}
		
		
		if($_POST){
			if($_POST['act']=='search'){
				
				$res=D("User")->where("username='".trim($_POST['username'])."'")->find();
				
				if(!$res){
					
					$res=D("User")->where("mobile='".$_POST['username']."'")->find();
					
				}
				
				
				$this->assign('res', $res);
				$this->assign('username', $_POST['username']);
				
				//by liux  增加单独商家预存功能  start
			
				$m['uid']=$res['id']; //客户ID
				$m['sid']=$this->__USER__['uid']; //商家ID

				
				$yucuninfo=D("UserYucun")->where($m)->find();
				//print_r($yucuninfo);
				if(empty($yucuninfo)){
				
					$this->assign('yucun_money', "0.00");
				
				}else{
				
					$this->assign('yucun_money', $yucuninfo['money']);
				}
				
				//by liux  增加单独商家预存功能  end
				
				
				
				
				
				
				$uid=$res['id'];
				if(!$uid){
					header("Content-type: text/html; charset=utf-8");
					echo "<script>alert('您查的会员不存在！');history.back(-1);</script>";
					exit("-3");//无会员返回字符
					
				}
				
				
				//当日积分
				$temptime=strtotime(date("Y-m-d",time()));
				$today=D("Log")->where("uid=$uid and addtime>$temptime")->field("sum(jifen) as total")->find();
				$this->assign('today', $today['total']);
				//当月积分
				$temptime=strtotime(date("Y-m-d",time()))-strtotime(date("Y-m",time()));
				$month=D("Log")->where("uid=$uid and addtime>$temptime")->field("sum(jifen) as total")->find();
				$this->assign('month', $month['total']);
				//累计积分
				$all=D("Log")->where("uid=$uid ")->field("sum(jifen) as total")->find();
				$this->assign('all', $all['total']);
				
			}else{
				$data['id']=$_POST['id'];
				$data['rate']=$_POST['rate'];
				$r=D("User")->save($data);
				
				if($r){
					
					$this->success('操作成功', "");
				}
				exit;
				
			}
			
		}
		
		
		
		Cookie('__forward__', $_SERVER['REQUEST_URI']);
		
		$info=D("User")->where("id=".$this->__USER__['uid'])->find();
		

		$this->assign('info', $info);
		$this->assign('nowtime', time());
		
        $this->assign('meta_title', "系统管理");
        $this->display('');
    }/**
     * 默认方法
     * @author jry <598821125@qq.com>
     */
    public function wap(){
			
			$res=D("User")->where("username='".trim($_GET['tel'])."'")->find();
				
				if(!$res){
					
					$res=D("User")->where("mobile='".$_GET['tel']."'")->find();
					
				}
				
				
				$this->assign('res', $res);
				$this->assign('username', $_POST['username']);
			
		
		//支付操作
		if($_POST['ajax']){
			$this->user_info=D("User")->where("mobile='".$_REQUEST['stel']."'")->find();
			
			$username=$_POST['username'];
			$jifen=$_POST['jifen'];
			$fee=$_POST['fee'];
			
			
			//判断当前的商家积分是否足够充值	
			
			if($this->user_info['score']<$jifen){
				//商家积分不足  返回-2  提示请充值
				//exit("-2");
				
			}
			
			
			
			
			
			
			
			
			
			$res=D("User")->where("username='".$username."'")->find();
			
			if(!$res){
				
				$res=D("User")->where("mobile='".$username."'")->find();
				
			}
			
			
			$paymethod=$_POST['paymethod'];
			switch($paymethod){
				
				case 'xianjin':
					//用户增加积分
					$res['zebaib']=$res['zebaib']+$jifen;
		
					$r=D("User")->save($res);
					
					$data['uid']=$res['id'];
					$data['addtime']=time();
					$data['jifen']=$jifen;
					$data['money']=$_POST['fee'];
				//	$data['parentid']=$this->__USER__[uid];
					$data['parentid']=$res['parentid'];//所属商家
					
					$data['payuid']=$this->user_info[id];//支付商家
					$data['typeid']=1;//支付类型
					
					D("Log")->add($data);
					//用户增加积分 end
					
					//商家扣积分

					$sjinfo=D("User")->where("id=".$this->user_info[id])->find();
					$sjinfo['score']=$sjinfo['score']-$jifen;
					D("User")->save($sjinfo);
					
					//商家扣积分 end

					echo $r;
					exit;
				
				break;
				case 'yucun':
				
					//app pay
					if($_POST['app']){
					//fee:$("input[name='fee']").val(),username:$("#username").val()
						/*
						
						查看10分钟内是否有订单
						
						*/
						
						$map['uid']=$res['id'];
						$map['status']=1;
						$map['addtime']=array("gt",time()-600);
						
						$r=D("App")->where($map)->find();
						
						if($r){
						
							exit('-2');//有订单还没确认
						
						}
						
						
						$data['fee']=$fee;
						$data['type']=1;
						$data['uid']=$res['id'];
						$data['mobile']=$res['mobile'];
						$data['addtime']=time();
						$data['status']=1;
						
						$data['sid']=$this->__USER__['uid']; //商家ID
						echo  D("App")->add($data);
						
						unset($data['sid']);
						
						
						//记录
						
						$data['uid']=$res['id'];
						$data['addtime']=time();
						$data['jifen']=$jifen;
						//$data['money']=$_POST['fee'];
					//	$data['parentid']=$this->__USER__[uid];
						$data['parentid']=$res['parentid'];//所属商家
						
						$data['payuid']=$this->user_info[id];//支付商家
						$data['typeid']=2;//支付类型
						
						D("Log")->add($data);
						
						
						
						
						exit;
					
					
					}
					
					
					//by liux  增加单独商家预存功能-消费  start
			
			

					$maps['uid']=$res['id'];//用户ID
					$maps['sid']=$this->__USER__['uid']; //商家ID APP 端无法获取到该ID  需要解决
					$yucuninfo=D("UserYucun")->where($maps)->find();
					
					if(empty($yucuninfo)){
						exit('-1');//预存金额不足
				
					}else{
						if($yucuninfo['money']<=0||$yucuninfo['money']<$_POST['fee']){
							
							exit('-1');//预存金额不足
							
						}
						//$r=D("UserYucun")->where($maps)->setInc('money',$yucun); // 用户的预存
					}
					$r=D("UserYucun")->where($maps)->setDec('money',$fee); // 用户的预存
					
					//by liux  增加单独商家预存功能  end
					
					





					
					$data['uid']=$res['id'];
						$data['addtime']=time();
					//	$data['jifen']=$jifen;
						$data['money']=$_POST['fee'];
					//	$data['parentid']=$this->__USER__[uid];
						$data['parentid']=$res['parentid'];//所属商家
						
						$data['payuid']=$this->user_info[id];//支付商家
						$data['typeid']=2;//支付类型
						
						D("Log")->add($data);
					
					
					//$r=D("User")->save($data);
					echo $r;
					
					break;
				case 'zebaib':
				
					//检查商家是否有足够的泽百币
					
					$suser=D("User")->where("id=".$this->user_info[id])->find();
					
					if($suser['zebaib']<=0||$suser['zebaib']<$_POST['fee']){
					
					
						exit('-1');//则百币不足
						
					}else{
					
					
						//商家扣则百币

	
						$suser['zebaib']=$suser['zebaib']-$_POST['fee'];
						D("User")->save($suser);
						
						//商家扣则百币 end
						
						//用户扣减
						
						
					
					
					}
					
				
				
				
					//app pay
					if($_POST['app']){
					
					
						//用户扣减
						//$res['zebaib']=$res['zebaib']-$fee;
	
						//$r=D("User")->save($res);
						
						//用户扣减 end
						
						/*
						
						查看10分钟内是否有订单
						
						*/
						
						$map['uid']=$res['id'];
						$map['status']=1;
						$map['addtime']=array("gt",time()-600);
						
						$r=D("App")->where($map)->find();
						
						if($r){
						
							exit('-2');//有订单还没确认
						
						}
						
						
						$data['fee']=$fee;
						$data['type']=2;
						$data['uid']=$res['id'];
						$data['mobile']=$res['mobile'];
						$data['addtime']=time();
						$data['status']=1;
						
						echo  D("App")->add($data);
						
						$data['uid']=$res['id'];
						$data['addtime']=time();
						//$data['jifen']=$jifen;
						$data['money']=$_POST['fee'];
					//	$data['parentid']=$this->__USER__[uid];
						$data['parentid']=$res['parentid'];//所属商家
						
						$data['payuid']=$this->user_info[id];//支付商家
						$data['typeid']=3;//支付类型
						
						D("Log")->add($data);
						
						
						exit;
					
					
					}
				
				
				
					if($res['zebaib']<=0||$res['zebaib']<$_POST['fee']){
					
					
						exit('-1');//则百币不足
						
					}else{
						//用户扣减
						$res['zebaib']=$res['zebaib']-$fee;
	
						$r=D("User")->save($res);
						
						//用户扣减 end
						
						
						
						
						
						
						//$data['uid']=$res['id'];
						//$data['addtime']=time();
						//$data['jifen']=$jifen;
						//D("Log")->add($data);
						
						$data['uid']=$res['id'];
						$data['addtime']=time();
						//$data['jifen']=$jifen;
						$data['money']=$_POST['fee'];
					//	$data['parentid']=$this->__USER__[uid];
						$data['parentid']=$res['parentid'];//所属商家
						
						$data['payuid']=$this->user_info[id];//支付商家
						$data['typeid']=3;//支付类型
						
						D("Log")->add($data);
						
						
						
						echo $r;
						exit;
						
					}
				
				
				break;

				
			}
			
			
			$jifen=$_POST['jifen'];
			exit();
		}
		
		
		if($_POST){
			if($_POST['act']=='search'){
				
				$res=D("User")->where("username='".trim($_POST['username'])."'")->find();
				
				if(!$res){
					
					$res=D("User")->where("mobile='".$_POST['username']."'")->find();
					
				}
				
				
				$this->assign('res', $res);
				$this->assign('username', $_POST['username']);
				
				
				
				$uid=$res['id'];
				if(!$uid){
					header("Content-type: text/html; charset=utf-8");
					echo "<script>alert('您查的会员不存在！');history.back(-1);</script>";
					exit("-3");//无会员返回字符
					
				}
				
				
				//当日积分
				$temptime=strtotime(date("Y-m-d",time()));
				$today=D("Log")->where("uid=$uid and addtime>$temptime")->field("sum(jifen) as total")->find();
				$this->assign('today', $today['total']);
				//当月积分
				$temptime=strtotime(date("Y-m-d",time()))-strtotime(date("Y-m",time()));
				$month=D("Log")->where("uid=$uid and addtime>$temptime")->field("sum(jifen) as total")->find();
				$this->assign('month', $month['total']);
				//累计积分
				$all=D("Log")->where("uid=$uid ")->field("sum(jifen) as total")->find();
				$this->assign('all', $all['total']);
				
			}else{
				$data['id']=$_POST['id'];
				$data['rate']=$_POST['rate'];
				$r=D("User")->save($data);
				
				if($r){
					
					$this->success('操作成功', "");
				}
				exit;
				
			}
			
		}
		
		
		
		//Cookie('__forward__', $_SERVER['REQUEST_URI']);
		
		//$info=D("User")->where("id=".$this->__USER__['uid'])->find();
		

		$this->assign('info', $info);
		$this->assign('tel', $_GET['tel']);
		$info=D("User")->where("mobile='".$_GET['stel']."'")->find();
		

		$this->assign('info', $info);
		$this->assign('stel', $_GET['stel']);
		$this->assign('nowtime', time());
		
        $this->assign('meta_title', "系统管理");
        $this->display('');
    }
	
	public function bangka(){
		//$this->is_login();
		//检查是否已经有卡号
		
		$bmobile=$_POST['bmobile'];
		$kahao=$_POST['kahao'];
		
		$info=D("User")->where("mobile='".$bmobile."'")->find();
		if(empty($info)){
		
			echo -1;//no data
			exit;
		}
		
		if(!empty($info['username'])){
		
			echo -2;//is active
			exit;
		}
		$info['username']=$kahao;
		$r=D("User")->save($info);
		echo $r;
	}
	
	
	/**
     * 会员注册
     * @author 
     */
    public function regmember2(){

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
		

		
		if(preg_match("/^13[0-9]{1}[0-9]{8}$|15[0-9]{1}[0-9]{8}$|18[0-9]{1}[0-9]{8}$|189[0-9]{8}$|170[0-9]{8}$|171[0-9]{8}$/",$data['mobile'])){    
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
			
			$uri="http://www.zgxfzb.cn/do/interface_reg.php";
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
		//exit;//13311109222
		

		
			$r=D("User")->add($data);
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
	
}
