<?php
// +----------------------------------------------------------------------
// | CoreThink [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.corethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: liujb
// +----------------------------------------------------------------------
namespace Admin\Controller;
use Think\Controller;
/**
 * 充值及余额控制器
 * @author liujb
 */
class RechargeBalanceController extends AdminController
{

    /**
     * 充值及余额控制器
     * author liujb 20160617
     */
    public function index(){
      //  $this->search_money('zebaib_yue','38078','city','2');

        if($_POST['ajax']) {
            $money_type = $_POST['money_type'];
            $area_id   = $_POST['area_id'];
            $area_type = $_POST['area_type'];
            $p = $_GET['p']?$_GET['p']:1;
            $stardate = $_POST['start_date'];
            $enddate  = $_POST['end_date'];

           $this->search_money($money_type,$area_id,$area_type,$p,$stardate,$enddate);

        }else{
         $this->display('');
        }
    }

    /**
     * 金额查询
     * @author liujb 20160617
     */
    public function search_money($money_type,$area_id,$area_type,$p,$stardate,$enddate){
        //查询全国下面所有省id
        if( $area_type == 'quan') {
            $map_p['parentid']= array('in',$area_id);
            $quan_data = D('User')->field('id')->where($map_p)->select();
            //echo D('User')->field('id')->where($map_p)->getLastSql();
            $quan_str = '';
            foreach ($quan_data as $qu) {
                $quan_str .= $qu['id'].",";
            }
            $quan_str = rtrim($quan_str, ",");
            $area_id = $quan_str;
        }
        //查询省下面所有市id
        if($area_type == 'province' || $area_type == 'quan') {
            $map_p['parentid']= array('in',$area_id);
            $province_data = D('User')->field('id')->where($map_p)->select();
           // echo  D('User')->field('id')->where($map_p)->getLastSql();
            $province_str = '';
            foreach ($province_data as $pro) {
                $province_str .=  $pro['id'].",";
            }
            $province_str = rtrim($province_str, ",");
            $area_id = $province_str;
        }
        //查询市下面的所有县
        if($area_type == 'city' || $area_type == 'province'|| $area_type == 'quan' ) {
            $map_c['parentid']= array('in',$area_id);
            $city_data = D('User')->field('id')->where($map_c)->select();
          // echo  D('User')->field('id')->where("parentid in ($province_str)")->getLastSql();
            $city_str = '';
            foreach ($city_data as $ct) {
                $city_str .= $ct['id'].",";
            }
            $city_str = rtrim($city_str, ",");
            $area_id = $city_str;
        }
        //查询县下面的业务员
        if($area_type == 'county' || $area_type == 'city' || $area_type == 'province' || $area_type == 'quan') {
            $map_ty['parentid']= array('in',$area_id);
            $yewuyuan_data = D('User')->field('id')->where($map_ty)->select();
            //echo D('User')->field('id')->where($map_ty)->getLastSql();
            $yewuyuan_str = '';
            foreach ($yewuyuan_data as $ye) {
                $yewuyuan_str .=  $ye['id'].",";
            }
            $yewuyuan_str = rtrim($yewuyuan_str, ",");
            $area_id = $yewuyuan_str;
        }
        //查询业务员下面的所有商铺
        if($area_id){
            $map_sh['parentid']= array('in',$area_id);
            $shop_data = D('User')->field('id')->where($map_sh)->select();
        }
        //echo D('User')->field('id')->where($map_sh)->getLastSql();
        $shop_str='';
        foreach($shop_data as $sd){
            $shop_str .= $sd['id'].',';
        }
        $shop_str = rtrim($shop_str,",");

        //无充值记录
        if(!$shop_str){
            echo $this->ajaxReturn('','JSON');
            exit;
        }

        $stardate = strtotime($stardate);
        $enddate  = strtotime($enddate);



        //查询金额
        switch($money_type){

            case 'jifen_total':  //积分总额
                $map['jf.isallow'] = '1';
                if($shop_str){
                    $map['jf.uid'] = array('in',$shop_str);
                }
                if($stardate && $enddate){
                    $map['addtime'] = array('between',"$stardate,$enddate");
                }else if($stardate){
                    $map['addtime'] = array('gt',$stardate);
                }else if ($enddate){
                    $map['addtime'] = array('lt',$enddate);
                }

                $money_data = D('Jifenchongzhi jf')->field('u.id,u.platform,sum(jf.money)as money,addtime')
                              ->join('zb_user u on jf.uid = u.id')
                              ->where($map)
                              ->group('u.platform')
                              ->order(' addtime desc ')
                              ->page("$p,10")
                              ->select();


                //分页
                $page_list = D('Jifenchongzhi jf')->field('u.platform,sum(jf.money),addtime')
                            ->join('zb_user u on jf.uid = u.id')
                            ->where($map)
                            ->group('u.platform')
                            ->select();

                //总额
                $total = D('Jifenchongzhi jf')->field('sum(jf.money) as total')
                        ->join('zb_user u on jf.uid = u.id')
                        ->where($map)
                        ->select();
                break;

            case 'jifen_yue':  //积分余额

                $map['status'] = '1';
                if($shop_str){
                    $map['id'] = array('in',$shop_str);
                }

                $money_data = D('User ')->field('id,platform,score')
                              ->where($map)
                              ->order(' score DESC')
                              ->page("$p,10")
                              ->select();

                //分页
                $page_list = D('User ')->field('platform,score')
                            ->where($map)
                            ->order(' score DESC')
                            ->select();
                //总额
                $total = D('User ')->field('sum(score) as tatal')
                        ->where($map)
                        ->select();

               //echo  D('User ')->field('platform,score')->where($map)->order(' score DESC')->getLastSql();
                break;

            case 'zebaib_yue':  //泽百币余额
                if($shop_str){
                    $map['parentid'] = array('in',$shop_str);
                }
                $map['status'] = '1';

                $money_data = D('User ')->field('id,mobile,username,realname,zebaib ')
                              ->where($map)
                              ->order(' zebaib DESC')
                              ->page("$p,10")
                              ->select();
                //分页
                $page_list = D('User ')->field('mobile,username,realname,zebaib ')
                            ->where($map)
                            ->order(' zebaib DESC')
                            ->select();

                //总额
                $total = D('User ')->field('sum(zebaib) total ')
                        ->where($map)
                        ->select();
                break;
            default:
                //$map['xf.typeid'] = array('NEQ','null');
                break;
        }
        $count = count($page_list);


        $page = new \Common\Util\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数
        $money_data['money_sum'] = $total[0]['total'];
        $money_data['page_show'] = $page->show();

        echo $this->ajaxReturn($money_data,'JSON');
//          var_dump($money_data);





    }
}
?>