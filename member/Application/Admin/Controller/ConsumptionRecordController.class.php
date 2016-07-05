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
 * 消费记录控制器
 * @author liujb
 */
class ConsumptionRecordController extends AdminController
{

    /**
     * 消费记录
     * author liujb
     *
     */
    public function index(){

//       $this->search_record('0','quan','',1,'2016-05-23','2016-06-23');
        if($_POST['ajax']) {
            $jilu_type = $_POST['jilu_type'];
            $area_id   = $_POST['area_id'];
            $area_type = $_POST['area_type'];
            $start_date = $_POST['start_date'];
            $end_date   = $_POST['end_date'];
           // var_dump($_POST);
            //默认
            $p = $_POST['p']?$_POST['p']:1;

            $this->search_record($area_id,$area_type,$jilu_type,$p,$start_date,$end_date);

        }else{
          //  $this->search_record('0','quan','',1);
            $this->display('');
        }
    }

    /**
     * 查询消费记录
     * author Fox
     */
    public function search_record($area_id,$area_type,$jilu_type,$p,$start_date,$end_date){
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
            //echo D('User')->field('id')->where($map_p)->getLastSql();
            $province_str = '';
            foreach ($province_data as $pro) {
                $province_str .= $pro['id'].",";
            }
            $province_str = rtrim($province_str, ",");
            $area_id = $province_str;
        }
        //查询市下面的所有县
        if($area_type == 'city' || $area_type == 'province'|| $area_type == 'quan' ) {
            $map_c['parentid']= array('in',$area_id);
            $city_data = D('User')->field('id')->where($map_c)->select();
            //echo  D('User')->field('id')->where("parentid in ($province_str)")->getLastSql();
            $city_str = '';
            foreach ($city_data as $ct) {
                $city_str .=  $ct['id'].",";
            }
            $city_str = rtrim($city_str, ",");
            $area_id = $city_str;
        }


        //查询县下面的所有业务员
        if($area_type == 'county' || $area_type == 'city' || $area_type == 'province'|| $area_type == 'quan') {
            $map_ty['parentid']=array('in',$area_id);
            $yewuyuan_data = D('User')->field('id')->where($map_ty)->select();
           // echo D('User')->field('id')->where($map_ty)->getLastSql();
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
        $shop_str='';
        foreach($shop_data as $sd){
            $shop_str .= $sd['id'].',';
        }
        $shop_str = rtrim($shop_str,",");

        //无消费记录
        if(!$shop_str){
            echo $this->ajaxReturn('','JSON');
            exit;
        }
        //查询消费记录
        $map = '';
        switch($jilu_type){
            case 'xianjin':  //现金消费记录
                $map['xf.typeid'] = '1';
                break;
            case 'yucun': //预存消费记录
                $map['xf.typeid'] = '2';
                break;
            case 'zebaib':  //泽百币消费记录
                $map['xf.typeid'] = '3';
                break;
            default:  //默认消费总额
                $map['xf.typeid'] = array('neq','null');
                break;
        }

        if($shop_str){ //默认全国
            $map['xf.payuid'] = array('in',$shop_str);
        }

        $stardate = strtotime($start_date);
        $enddate  = strtotime($end_date);

        if($stardate && $enddate){
            $map['xf.addtime'] = array('between',"$stardate,$enddate");
        }else if($stardate){
            $map['xf.addtime'] = array('gt',$stardate);
        }else if ($enddate){
            $map['xf.addtime'] = array('lt',$enddate);
        }
        //查询消费记录
        $xiaofei_record = D('Xiaofei as xf ')
                        ->field('SUM(fmoney) as fmoney,xf.username,xf.uid,u.platform,zbu.realname,max(xf.addtime) addtime')
                        ->join('zb_user u ON xf.payuid = u.id ')
                        ->join('zb_user zbu ON zbu.id = xf.uid')
                        ->group('xf.username,u.platform,xf.uid ')
                        ->having('SUM(fmoney)>0')
                        ->where($map)
                        ->order('addtime  DESC ')
                        ->page("$p,10")
                        ->select();
/*
     echo   D('Xiaofei as xf ')->field('SUM(fmoney) as fmoney,xf.username,xf.uid,u.platform,zbu.realname,xf.addtime')
            ->join('zb_user u ON xf.payuid =u.id ')
            ->join('zb_user zbu ON zbu.id = xf.uid')
            ->group('xf.username,u.platform,xf.uid ')
            ->having('SUM(fmoney)>0')
            ->where($map)
            ->order('u.platform ,xf.uid DESC ')
            ->page("$p,10")->getLastSql();
        exit;*/

        //总金额
        $total = D('Xiaofei xf')->field('SUM(fmoney) as total')
                ->join('zb_user u ON xf.payuid = u.id ')
                ->join('zb_user zbu ON zbu.id = xf.uid')
                ->where($map)
                ->select();

        //分页处理
        $list = D('Xiaofei as xf ')->field('SUM(fmoney) as fmoney,xf.username,xf.uid,u.platform,zbu.realname')
            ->join('zb_user u ON xf.payuid =u.id ')
            ->join('zb_user zbu ON zbu.id = xf.uid')
            ->group('xf.username,u.platform,xf.uid ')
            ->having(' SUM(fmoney)>0')
            ->where($map)
            ->select();

        $count = count($list);
        $page = new \Common\Util\Page(count($list),10);// 实例化分页类 传入总记录数和每页显示的记录数
        $total_page = ceil($count/10);
        $page_show       = $page->show();// 分页显示输出

        $xiaofei_record['total_page'] = $total_page;
        $xiaofei_record['total'] = $total[0]['total'];
        $xiaofei_record['page_show'] = $page_show;

       echo $this->ajaxReturn($xiaofei_record,'JSON');
      //  return $this->ajaxReturn($xiaofei_record,'JSON');
    }




}

?>
