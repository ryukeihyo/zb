<?php
// +----------------------------------------------------------------------
// | CoreThink [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.corethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: jry <598821125@qq.com> <http://www.corethink.cn>
// +----------------------------------------------------------------------
namespace Admin\Controller;
use Think\Controller;
/**
 * 后台默认控制器
 * @author jry <598821125@qq.com>
 */
class IndexController extends AdminController{
    /**
     * 默认方法
     * @author jry <598821125@qq.com>
     */
    public function index(){
        //计算统计图日期
        $today = strtotime(date('Y-m-d', time())); //今天
        $start_date = I('get.start_date') ? I('get.start_date')/1000 : $today-14*86400;
        $end_date   = I('get.end_date') ? (I('get.end_date')+1)/1000 : $today+86400;
        $count_day  = ($end_date-$start_date)/86400; //查询最近n天
        $user_object = D('User');
        for($i = 0; $i < $count_day; $i++){
            $day = $start_date + $i*86400; //第n天日期
            $day_after = $start_date + ($i+1)*86400; //第n+1天日期
            $map['ctime'] = array(
                array('egt', $day),
                array('lt', $day_after)
            );
            $user_reg_date[] = date('m月d日', $day);
            $user_reg_count[] = (int)$user_object->where($map)->count();
        }

        $this->assign('start_date', date('Y年m月d日', $start_date));
        $this->assign('end_date', date('Y年m月d日', $end_date-1));
        $this->assign('count_day', $count_day);
        $this->assign('user_reg_date', json_encode($user_reg_date));
        $this->assign('user_reg_count', json_encode($user_reg_count));
        $this->assign('meta_title', "首页");
        $this->display('');
    }

    /**
     * 完全删除指定文件目录
     * @author jry <598821125@qq.com>
     */
    public function rmdirr($dirname = RUNTIME_PATH){
        $file = new \Common\Util\File();
        $result = $file->del_dir($dirname);
        if($result){
            $this->success("缓存清理成功");
        }else{
            $this->error("缓存清理失败");
        }
    }
	
	
	
	/**
     * 结算 规则  从上次结算的时间开始  到当前时间的消费赠送积分进行结算
     * @author 
     */
    public function pay(){
		

		
		if($_POST){

			
			
			$type=$_POST['type'];
			
			
			if($type==1){
			
				/*个人结算代码*/
				$usertype=$_POST['usertype'];
				
				$list=D("User")->where("usertype=$usertype")->select();
				
				
				//上次结算时间
				$max=D("Checkout")->where("type=$type")->field("max(addtime) as lasttime")->find();
				$lasttime=$max['lasttime'];
				if(!$lasttime){
					
					$lasttime=0;
					
				}
				//本次时间
				$nowtime=time();
				
				
				foreach($list as $key=>$val){
					
					$r=D("Log")->where("uid=".$val['id']." and addtime>$lasttime and addtime<$nowtime ")->field("sum(jifen) as total")->find();
					$fee=$r['total']*0.3;//计算出结算的金额
						
					//更新到会员余额里
					
					$val['money']=$val['money']+$fee;
					
					D("User")->save($val);
					
					$data['uid']=$val['id'];
					$data['money']=$fee;//只存储本次结算的金额
					$data['addtime']=time();
					
					D("Fenhonglog")->add($data);
					unset($data);
					

				}
					
				
				
				
				/*结算成功后做记录*/
				$data['type']=$_POST['type'];
				$data['addtime']=time();
				$r=D("Checkout")->add($data);
				
				if($r){
					
					echo date("Y-m-d H:i:s",$data['addtime']);
					
				}
			
			}elseif($type==2){
				
				/*商家结算代码*/
				$usertype=$_POST['usertype'];
				
				$list=D("User")->where("usertype=$usertype")->select();
				
				
				//上次结算时间
				$max=D("Checkout")->where("type=$type")->field("max(addtime) as lasttime")->find();
				$lasttime=$max['lasttime'];
				if(!$lasttime){
					
					$lasttime=0;
					
				}
				//本次时间
				$nowtime=time();
				
				
				
				
				foreach($list as $key=>$val){
					
					$r=D("Log")->where("parentid=".$val['id']." and addtime>$lasttime and addtime<$nowtime ")->field("sum(jifen) as total")->find();
					$fee=$r['total']*0.05;//计算出结算的金额
						
					//更新到会员余额里
					
					$val['money']=$val['money']+$fee;
					
					D("User")->save($val);
					
					$data['uid']=$val['id'];
					$data['money']=$fee;//只存储本次结算的金额
					$data['addtime']=time();
					
					D("Fenhonglog")->add($data);
					unset($data);
					

				}
					
				
				
				
				/*结算成功后做记录*/
				$data['type']=$_POST['type'];
				$data['addtime']=time();
				$r=D("Checkout")->add($data);
				
				if($r){
					
					echo date("Y-m-d H:i:s",$data['addtime']);
					
				}
				
				
			}elseif($type==3){
				
				/*业务员结算代码*/
				$usertype=$_POST['usertype'];
				
				$list=D("User")->where("usertype=$usertype")->select();
				
				
				//上次结算时间
				$max=D("Checkout")->where("type=$type")->field("max(addtime) as lasttime")->find();
				$lasttime=$max['lasttime'];
				if(!$lasttime){
					
					$lasttime=0;
					
				}
				//本次时间
				$nowtime=time();
				
				
				
				
				foreach($list as $key=>$val){
					//查找代理商
					
					
					$dlslist=D("User")->where("managenumber='".$val['username']."'")->select();
					$fee=0;
					foreach($dlslist as $k=>$vs){
						
						$r=D("Log")->where("parentid=".$vs['id']." and addtime>$lasttime and addtime<$nowtime ")->field("sum(jifen) as total")->find();
						$fee+=$r['total']*0.05;//计算出结算的金额
						
					}
					
					
						
					//更新到会员余额里
					
					$val['money']=$val['money']+$fee;
					
					D("User")->save($val);
					
					$data['uid']=$val['id'];
					$data['money']=$fee;//只存储本次结算的金额
					$data['addtime']=time();
					
					D("Fenhonglog")->add($data);
					unset($data);
					

				}
					
				
				
				
				/*结算成功后做记录*/
				$data['type']=$_POST['type'];
				$data['addtime']=time();
				$r=D("Checkout")->add($data);
				
				if($r){
					
					echo date("Y-m-d H:i:s",$data['addtime']);
					
				}
				
				
			}
			
			exit;
			
		}
		//上次个人
		$max=D("Checkout")->where("type=1")->field("max(addtime) as lasttime")->find();

		$this->assign('lasttime', date("Y-m-d H:i:s",$max['lasttime']));
		//上次商家
		$max2=D("Checkout")->where("type=2")->field("max(addtime) as lasttime")->find();

		$this->assign('slasttime', date("Y-m-d H:i:s",$max2['lasttime']));	
		
		//上次业务员
		$max3=D("Checkout")->where("type=3")->field("max(addtime) as lasttime")->find();

		$this->assign('ylasttime', date("Y-m-d H:i:s",$max3['lasttime']));	
		
        $this->assign('meta_title', "分红结算");	
		$this->display();
    }

}
