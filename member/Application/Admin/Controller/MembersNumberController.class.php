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
 * 会员数量控制器
 * @author liujb
 */
class MembersNumberController extends AdminController
{
    /**
     * 会员数量
     * author liujb
     *
     */
    public function index(){
        //$this->statisticsList();
     //  $this->searchViewdetails('business','0','1');
        if($_POST['ajax']) {
            $operation_type = $_POST['operation_type'];
            $area_type = $_POST['area_type'];
            $area_id = $_POST['area_id'];
            $p = $_GET['p']?$_GET['p']:1;
            switch($operation_type){
                case 'statistical':
                    $this->statisticsList();
                    break;
                case 'view_details':
                    $this->searchViewdetails($area_type,$area_id,$p);
                    break;
            }
        }else {
            $this->display('');
        }
    }

    /**
     * 统计列表数量
     * author Fox
     */
    public  function  statisticsList(){
        $statistics_list = array();
         //分公司数量
        $map['parentid'] = '41600';
        $fen_array = $this->serach_areadata($map);
        $statistics_list['fengongsi_count']= $fen_array[1];
        //代理商数量
        $map_d['parentid'] = array("in",$fen_array[2]);
        $dailishang_array = $this->serach_areadata($map_d);
        $statistics_list['daili_count']= $dailishang_array[1];
        unset($fen_array);
        //事业部数量
        $map_s['parentid'] = array("in",$dailishang_array[2]);
        $shyebu_array = $this->serach_areadata($map_s);
        $statistics_list['shiye_count']= $shyebu_array[1];
        unset($dailishang_array);
        //业务员数量
        $map_y['parentid'] = array("in",$shyebu_array[2]);
        $yewuyuan_array = $this->serach_areadata($map_y);
        $statistics_list['yewu_count']= $yewuyuan_array[1];
        unset($shyebu_array);
        //商家数量
        $map_sj['parentid'] = array("in",$yewuyuan_array[2]);
        $shangjia_array = $this->serach_areadata($map_sj);
        $statistics_list['shangjia_count']= $shangjia_array[1];
        unset($yewuyuan_array);
        //消费会员数量
        $map_h['parentid'] = array("in",$shangjia_array[2]);
        $huiyuan_array = $this->serach_areadata($map_h);
        $statistics_list['huiyuan_count']= $huiyuan_array[1];
        unset($shangjia_array);
        echo $this->ajaxReturn($statistics_list,'JSON');
        //var_dump($statistics_list);
    }

    /**
     * 查看会员详细
     * author liujb
     */

    public function  searchViewdetails($area_type,$area_id,$p){

        if($area_type=='province'){  //查询分公司
            if($area_id != '0'){
                $area_id = $area_id;
            }else{
                $map['parentid'] = '0';
                $area_id = $area_id;
            }
        }

        if($area_type=='city'){  //查询代理商
            if($area_id != '0'){
                $area_id = $area_id;
            }else{
                $map['parentid'] = '0';
                $pro_array = $this->serach_areadata($map);
                $area_id = $pro_array[2];
            }
        }
        if($area_type =='county'){  //查询事业部
            if($area_id != '0'){
                $area_id = $area_id;
            }else{
                $map['parentid'] = '0';
                $pro_array = $this->serach_areadata($map);
                $map_c['parentid'] = array("in",$pro_array[2]);
                $city_array = $this->serach_areadata($map_c);
                $area_id = $city_array[2];
            }
        }

        if($area_type=='salesman'){ //查询业务员
            if($area_id != '0'){
                $area_id = $area_id;
            }else{
                $map['parentid'] = '0';
                $pro_array = $this->serach_areadata($map);
                $map_c['parentid'] = array("in",$pro_array[2]);
                $city_array = $this->serach_areadata($map_c);
                $map_sa['parentid'] = array("in",$city_array[2]);
                $county_array = $this->serach_areadata($map_sa);
                $area_id = $county_array[2];
            }
        }
        if($area_type=='business'){ //查看商家
            if($area_id != '0'){
                $area_id = $area_id;
            }else{
                $map['parentid'] = '0';
                $pro_array = $this->serach_areadata($map);
                $map_c['parentid'] = array("in",$pro_array[2]);
                $city_array = $this->serach_areadata($map_c);
                $map_sa['parentid'] = array("in",$city_array[2]);
                $county_array = $this->serach_areadata($map_sa);
                $map_b['parentid'] = array("in",$county_array[2]);
                $business_array = $this->serach_areadata($map_b);
                $area_id = $business_array[2];
            }
        }

        if($area_type=='consumer'){ //查看消费会员
            if($area_id != '0'){
                $area_id = $area_id;
            }else{
                $map['parentid'] = '0';
                $pro_array = $this->serach_areadata($map);
                $map_c['parentid'] = array("in",$pro_array[2]);
                $city_array = $this->serach_areadata($map_c);
                $map_sa['parentid'] = array("in",$city_array[2]);
                $county_array = $this->serach_areadata($map_sa);
                $map_b['parentid'] = array("in",$county_array[2]);
                $business_array = $this->serach_areadata($map_b);
                $map_b['parentid'] = array("in",$business_array[2]);
                $consumer_array = $this->serach_areadata($map_b);
                $area_id = $consumer_array[2];
            }
        }


        $map['parentid'] = array('in',$area_id);

        $data = D('User')->where($map)->page("$p,10")->order("ctime desc")->select();

//        echo  D('User')->where($map)->page("$p,10")->getLastSql();
        //分页处理
        $data_count = D('User')->field('count(*) as cnt')->where($map)->select();
        $count = $data_count[0]['cnt'];
        $page = new \Common\Util\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数
        $page_show       = $page->show();// 分页显示输出
        $huiyuan_exp =array();

        foreach($data as $key=>$da){
            $map_count['parentid'] =$da['id'];
            $data_count = D('User')->field("count(*) as cnt")->where($map_count)->select();
            $huiyuan_exp[$key]['data_count'] = $data_count[0]['cnt'];

            if($area_type=='quan'){  //查询分公司
                $huiyuan_exp[$key]['area_type']= 'province';
            }
            if($area_type=='province'){  //查询代理商
                $huiyuan_exp[$key]['area_type']= 'city';
            }
            if($area_type=='city'){  //查询事业部
                $huiyuan_exp[$key]['area_type']= 'county';
            }
            if($area_type=='county'){  //查询业务员
                $huiyuan_exp[$key]['area_type']= 'salesman';
            }
            if($area_type=='salesman'){ //查看商家
                $huiyuan_exp[$key]['area_type']= 'business';
            }
            if($area_type=='business'){ //查看消费会员
                $huiyuan_exp[$key]['area_type']= 'consumer';
            }


            $huiyuan_exp[$key]['id'] = $da['id'];
            if($area_type == 'salesman') {
                $huiyuan_exp[$key]['jigou_name'] = $da['platform'];
            }else{
                $huiyuan_exp[$key]['jigou_name'] =  $da['realname'];
            }

        }
        $huiyuan_exp['page_show'] = $page_show;
      // var_dump($huiyuan_exp);
        echo $this->ajaxReturn($huiyuan_exp,'JSON');

    }

    /**
     * 查询会员数量
     * @param $map
     * @return array
     * author liujb
     */
    public  function  serach_areadata($map){
        $data = D('User')->where($map)->select();

       //  echo  D('User')->where($map)->getLastSql();
        $area_id = array();
        foreach($data as $d){
            $area_id[]=$d['id'];
        }
        $area_data[]= array();
        $area_data[] = count($data);
        $area_data[] = implode(",",$area_id);
        unset($data);

        return $area_data;
    }


   }
