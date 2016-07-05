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
 * 地区导航控制器
 * @author liujb
 */
class AreaNavigationController extends AdminController
{

    /**
     * 地区导航
     * author liujb
     *
     */
    public function index(){
            $area_id   = $_POST['area_id'];
            $area_type = $_POST['area_type'];
            $this->search_area($area_id,$area_type);
        //$this->display('');
    }

    /**
     * 查询地区
     * author Fox
     */
    public function search_area($area_id,$area_type){

        $area_data = '';

        //查询所以省
        if($area_type == 'quan' ){
            $map['usertype'] = '0';
            $map['parentid'] = $area_id;
            $province_data = D('User')->field('id,realname')->where($map)->select();
           // echo  D('User')->field('id,realname')->where($map)->getLastSql();
            $area_data = $province_data;
        }

        //查询省下面所有市id
        if($area_type == 'province') {
            $map['usertype'] = '0';
            $map['parentid'] = $area_id;
            $province_data = D('User')->field('id,realname')->where($map)->select();
          //  echo  D('User')->field('id,realname')->where($map)->getLastSql();

            $province_str = '';
            foreach ($province_data as $pro) {
                $province_str .= "'" . $pro['id'] . "',";
            }
            $province_str = rtrim($province_str, ",");
            $area_id = $province_str;
            $area_data = $province_data;
        }

        //查询市下面的所有县
        if($area_type == 'city' ) {
            $map['usertype'] = '0';
            $map['parentid'] = $area_id;
            $city_data = D('User')->field('id,realname')->where($map)->select();
          //echo  D('User')->field('id,realname')->where($map)->getLastSql();
            $area_data = $city_data;
        }

       echo $this->ajaxReturn($area_data,'JSON');


    }

}