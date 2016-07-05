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
class SettlementController extends AdminController{
        //文档类型切换触发操作JS
        private $extra_html = <<<EOF
        <script type="text/javascript">
            //选择模型时页面元素改变
            $(function(){
                $('input[name="doc_type"]').change(function(){
                    var model_id = $(this).val();
                    if(model_id == 1){ //超链接
                        $('.item_url').removeClass('hidden');
                        $('.item_content').addClass('hidden');
                        $('.item_index_template').addClass('hidden');
                        $('.item_detail_template').addClass('hidden');
                    }else if(model_id == 2){ //单页文档
                        $('.item_url').addClass('hidden');
                        $('.item_content').removeClass('hidden');
                        $('.item_index_template').addClass('hidden');
                        $('.item_detail_template').removeClass('hidden');
                    }else{
                        $('.item_url').addClass('hidden');
                        $('.item_content').addClass('hidden');
                        $('.item_index_template').removeClass('hidden');
                        $('.item_detail_template').removeClass('hidden');
                    }
                });
            });
        </script>
EOF;

    /**
     * 分类列表
     * @author jry <598821125@qq.com>
     */
    public function index($group = 1){
        
		
		
	
	
		$this->assign('meta_title', "系统结算");
		$this->display('');
	
	}
	
	
}
