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
use Think\Model;

/**
 * 收入统计控制器
 * @author liujb
 */
class IncomeStatisticsController extends AdminController
{

    /**
     * 收入统计
     * author liujb
     *
     */
    public function index(){

       // $this->searchSettlementRecords();
       // $this->settlementRecords('2017-06-01 11:18:54','2017-06-17 11:18:54');
      //   $this->selectIncome('16','zong','41600');
      // $this->selectIncome('14','province','39579');
        //$this->selectIncome('7','city','39659');
        //$this->selectIncome('7','county','39672');
        if($_POST['ajax']) {
            $stardate       = $_POST['accounting_start_date'];
            $enddate        = $_POST['accounting_end_date'];
            $operation_type = $_POST['operation_type'];
            $data_id        = $_POST['jiesuan_id'];
            if(!$_POST['area_id']){
                $area_id = '0';
            }else {
                $area_id = $_POST['area_id'];
            }

            if( $_POST['area_type'] ==''){
                $area_type= 'quan';
            }else{
                $area_type= $_POST['area_type'];
            }


            $p = $_GET['p']?$_GET['p']:1;

            switch($operation_type){
                case "jiesuan":  //生成结算记录
                    $this->settlementRecords($stardate,$enddate);
                    break;
                case "jiesuan_history":  //查询结算历史记录
                    $this->searchSettlementRecords($p);
                    break;
                case "view_details":  //查看详情
                    $this->selectIncome($data_id,$area_type,$area_id);
                    break;
            }
        }else{
           $this->display('');
        }

    }

    /**
     * 生成结算记录
     * @param $stardate  结算开始时间
     * @param $enddate   结算结束时间
     * author liujb 20160621
     */
    public function  settlementRecords($stardate,$enddate){

        /*$res = M('Xiaofei')->field('count(*) as cnt')
            ->where("addtime  BETWEEN UNIX_TIMESTAMP('$stardate') and UNIX_TIMESTAMP('$enddate') ")->select();*/
        $stardate = strtotime($stardate);
        $enddate  = strtotime($enddate);

        $map['addtime'] = array('between',array($stardate,$enddate));

        $res = M('Xiaofei')->field('count(*) as cnt')->where($map)->select();
        //echo  M('Xiaofei')->field('count(*) as cnt')->where($map)->getLastSql();
        $result = "";
        if($res[0]['cnt'] > 0 ){

            $data['settlement_start_date'] = $stardate;
            $data['settlement_end_date']   = $enddate;
            $result = D('Settlement_history')->add($data);
        }
        if($result){
            echo 'true';
        }else{
            echo 'false';
        }
    }

    /**
     * 查询收入统计
     * @param $stardate
     * @param $enddate
     * author Fox
     */
    public function  selectIncome($date_id,$area_type,$area_id){
        $map_date['id'] = $date_id;
        $date = M('Settlement_history')->where($map_date)->select();
        $stardate = $date[0]['settlement_start_date'];
        $enddate  = $date[0]['settlement_end_date'];

        $model = new Model();
       // echo  M('Settlement_history')->where($map_date)->getLastSql();
     //   var_dump($date);
        //查询总公司
        if($area_type == 'zong'){
            $map_z['id'] = $area_id;
            $data_quan = D('User')->where($map_z)->select();
            $income_data = array();
            foreach($data_quan as $key => $da ){
                $area_id = $da['id'];
                //省份查询
                $data = $model->query("SELECT  SUM(xf.fmoney) as money,xf.payuid,u.guanlifei, u.platform
                                            FROM  zb_xiaofei xf  INNER JOIN zb_user u  ON xf.payuid = u.id
                                            WHERE u.usertype = '5' AND typeid='1'
                                            AND addtime BETWEEN $stardate AND $enddate
                                          GROUP BY u.platform
                                          ");

                $guanlifei_tatol='0';
                $money='0';

                foreach($data as $d){
                    $money += $d['money'];
                    $guanlifei_tatol += $d['money']*$d['guanlifei']/100;
                }
                $ticheng = $guanlifei_tatol * 0.52;
                $income_data[$key]['id'] = '41600';
                $income_data[$key]['jisuan_name'] = $da['realname']."(总公司)";
                $income_data[$key]['jiesuan_bili'] = '0.52';
                $income_data[$key]['jisuan_ticheng'] = $ticheng;
                $income_data[$key]['guanlifei_tatol'] = $guanlifei_tatol;
                $income_data[$key]['money_total'] = $money?$money:0;
                $income_data[$key]['area_type'] = 'quan';
            }
        }
        //查询全部省（分公司）
        if($area_type == 'quan'){

            $map_q['parentid'] = $area_id;
            $data_quan = D('User')->where($map_q)->select();

            $income_data = array();
            foreach($data_quan as $key => $da ){
                $area_id = $da['id'];
                    //省份查询
                    $data = $model->query("SELECT  SUM(xf.fmoney) as money,xf.payuid,u.guanlifei, u.platform
                                            FROM  zb_xiaofei xf  INNER JOIN zb_user u  ON xf.payuid = u.id
                                            WHERE u.usertype = '5' AND typeid='1' AND payuid IN (
                                            SELECT id FROM zb_user
                                            WHERE parentid IN (  SELECT id FROM zb_user
                                            WHERE parentid IN ( SELECT id FROM zb_user
                                            WHERE parentid IN (SELECT id FROM zb_user
                                            WHERE parentid IN ($area_id))))
                                          ) AND addtime BETWEEN $stardate AND $enddate
                                          GROUP BY u.platform
                                          ");

                    $guanlifei_tatol='0';
                    $money='0';
                    foreach($data as $d){
                        $money += $d['money'];
                        $guanlifei_tatol += $d['money']*$d['guanlifei']/100;
                    }
                    $ticheng = $guanlifei_tatol * 0.03;
                    $income_data[$key]['id'] = $da['id'];
                    $income_data[$key]['jisuan_name'] = $da['realname']."(分公司)";
                    $income_data[$key]['jiesuan_bili'] = '0.03';
                    $income_data[$key]['jisuan_ticheng'] = $ticheng;
                    $income_data[$key]['guanlifei_tatol'] = $guanlifei_tatol;
                    $income_data[$key]['money_total'] = $money?$money:0;
                    $income_data[$key]['area_type'] = 'province';
                }
            }


        //查询省(代理商)
        if($area_type == 'province'){
            $map_pro['parentid'] = $area_id;
            $data_province = D('User')->where($map_pro)->select();
            //echo  D('User')->where($map_pro)->getLastSql();

            $stardate = $date[0]['settlement_start_date'];
            $enddate  = $date[0]['settlement_end_date'];
            $income_data = array();
            foreach($data_province as $key => $da ){
                $area_id = $da['id'];
                $data=$model->query("SELECT  SUM(xf.fmoney) as money,xf.payuid,u.guanlifei, u.platform
                                        FROM  zb_xiaofei xf  INNER JOIN zb_user u  ON xf.payuid = u.id
                                        WHERE u.usertype = '5' AND typeid='1' AND payuid IN
                                        (SELECT id FROM zb_user
                                        WHERE parentid IN ( SELECT id FROM zb_user
                                        WHERE parentid IN (SELECT id FROM zb_user
                                        WHERE parentid IN ($area_id)))
                                        ) AND addtime BETWEEN $stardate AND $enddate
                                        GROUP BY u.platform
                                  ");
                $guanlifei_tatol='0';
                $money='0';
                foreach($data as $d){
                    $money += $d['money'];
                    if($d['money'] > 0 && $d['guanlifei']>0) {
                        $guanlifei_tatol += $d['money'] * $d['guanlifei'] / 100;
                    }
                }
                //var_dump($data);
                $ticheng = $guanlifei_tatol * 0.1;
                $income_data[$key]['id'] = $da['id'];
                $income_data[$key]['jisuan_name'] = $da['realname']."(代理商)";
                $income_data[$key]['jiesuan_bili'] = '0.1';
                $income_data[$key]['jisuan_ticheng'] = $ticheng;
                $income_data[$key]['guanlifei_tatol'] = $guanlifei_tatol;
                $income_data[$key]['money_total'] = $money?$money:0;
                $income_data[$key]['area_type'] = 'city';
            }
        }

        //查询市（事业部）
        if($area_type == 'city'){
            $map_city['parentid'] = $area_id;
            $data_city = D('User')->where($map_city)->select();
            $stardate = $date[0]['settlement_start_date'];
            $enddate  = $date[0]['settlement_end_date'];
            $income_data = array();
            foreach($data_city as $key => $da ){
                $area_id = $da['id'];
                $data=$model->query("SELECT  SUM(xf.fmoney) as money,xf.payuid,u.guanlifei, u.platform
                                      FROM  zb_xiaofei xf  INNER JOIN zb_user u  ON xf.payuid = u.id
                                      WHERE u.usertype = '5' AND
                                      typeid='1' AND payuid IN (SELECT id FROM zb_user
                                      WHERE parentid IN (SELECT id FROM zb_user
                                      WHERE parentid IN ($area_id))
                                        )  AND addtime BETWEEN $stardate AND $enddate
                                       GROUP BY u.platform
                                  ");
              //  echo '<br/>'.$area_id.'---'.$stardate.'---'.$enddate;
                $guanlifei_tatol='0';
                $money ='0';
                foreach($data as $d){
                    $money += $d['money'];
                    $guanlifei_tatol += $d['money']*$d['guanlifei']/100;
                }
                $ticheng = $guanlifei_tatol * 0.15;
                $income_data[$key]['id'] = $da['id'];
                $income_data[$key]['jisuan_name'] = $da['realname']."(事业部)";
                $income_data[$key]['jiesuan_bili'] = '0.15';
                $income_data[$key]['jisuan_ticheng'] = $ticheng;
                $income_data[$key]['guanlifei_tatol'] = $guanlifei_tatol;
                $income_data[$key]['money_total'] = $money?$money:0;
                $income_data[$key]['area_type'] = 'county';
            }
        }

        //查询县（业务员）
        if($area_type == 'county'){
            $map_c['parentid'] = $area_id;
            $data_county = D('User')->where($map_c)->select();
            $stardate = $date[0]['settlement_start_date'];
            $enddate  = $date[0]['settlement_end_date'];
            $income_data = array();
            foreach($data_county as $key => $da ){
                $area_id = $da['id'];
                $data=$model->query("SELECT  SUM(xf.fmoney) as money,xf.payuid,u.guanlifei, u.platform
                                     FROM  zb_xiaofei xf  INNER JOIN zb_user u  ON xf.payuid = u.id
                                     WHERE u.usertype = '5' AND typeid='1' AND
                                     payuid IN (SELECT id FROM zb_user
                                     WHERE parentid IN ($area_id)
                                      )  AND addtime BETWEEN $stardate AND $enddate
                                      GROUP BY u.platform
                                  ");
                $guanlifei_tatol='0';
                $money='0';
                foreach($data as $d){
                    $money += $d['money'];
                    $guanlifei_tatol += $d['money']*$d['guanlifei']/100;
                }
                $ticheng = $guanlifei_tatol * 0.2;
                $income_data[$key]['id'] = $da['id'];
                $income_data[$key]['jisuan_name'] = $da['realname']."(业务员)";
                $income_data[$key]['jiesuan_bili'] = '0.2';
                $income_data[$key]['jisuan_ticheng'] = $ticheng;
                $income_data[$key]['guanlifei_tatol'] = $guanlifei_tatol;
                $income_data[$key]['money_total'] = $money?$money:0;
                $income_data[$key]['area_type'] = '';
            }
        }

        echo $this->ajaxReturn($income_data,'JSON');
        //var_dump( $income_data);

    }

    /**
     * 查询结束历史记录
     * author liujb
     *
     */
    public  function searchSettlementRecords($p){
        $data = D('Settlement_history')
            ->field('id,FROM_UNIXTIME(settlement_start_date) as start_date,FROM_UNIXTIME(settlement_end_date) as end_date')
            ->order('id DESC ')
            ->page("$p,3")
            ->select();

        //分页
        $page_list = D('Settlement_history')
            ->field('id,FROM_UNIXTIME(settlement_start_date) as start_date,FROM_UNIXTIME(settlement_end_date) as end_date')
            ->order('id DESC ')
            ->select();

        $count = count($page_list);
        $page = new \Common\Util\Page($count,3);// 实例化分页类 传入总记录数和每页显示的记录数
        $data['page_show'] = $page->show();
        if($data){
            $i=count($data)-1;
            $data['end_date'] = $data[0]['end_date'];
          echo $this->ajaxReturn($data,'JSON');
        }else{
            $data['end_date'] =  date("Y-m-d H:i:s", time());
            echo $this->ajaxReturn('','JSON');
        }
    }

    }