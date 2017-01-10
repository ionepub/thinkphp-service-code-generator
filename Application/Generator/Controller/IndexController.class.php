<?php
namespace Generator\Controller;
use Think\Controller;
class IndexController extends Controller {
	/**
	 * 显示表单
	 */
    public function index(){
    	$actionUrl = U(MODULE_NAME . "/Index/generate");
    	$this->assign('actionUrl', $actionUrl);
    	$this->assign('funcList', C('FUNCTION_LIST'));
        $this->display("index");
    }

    /**
     * 构建
     */
    public function generate(){
    	// 所属模块
        $module = I('post.module');

        if(!$module){
        	$module = 'Common'; // 默认在Common/Common下
        }

        // 类名
        $name = I('post.name');

        if(!$name){
        	$this->error('类名不能为空');
        	exit();
        }

        $name = ucfirst($name); // 首字母大写（如果非首字母大写了不会改为小写）

        // 作者
        $author = I('post.author');
        if(!$author){
        	$author = 'ionepub';
        }

        // 数据表名
        $tableName = I('post.table');

        if(!$tableName){
        	$this->error('数据表名不能为空');
        	exit();
        }

        // function list
        $funcList = I('post.func');

        if(!is_array($funcList)){
        	$funcList = array();
        }
        
        // 在$module下自动创建action/service/dao文件夹

        if(!is_dir(APP_PATH . 'Common/' . $module . '/Action')){
        	@mkdir(APP_PATH . 'Common/' . $module . '/Action');
        }
        if(!is_dir(APP_PATH . 'Common/' . $module . '/Service')){
        	@mkdir(APP_PATH . 'Common/' . $module . '/Service');
        }
        if(!is_dir(APP_PATH . 'Common/' . $module . '/Dao')){
        	@mkdir(APP_PATH . 'Common/' . $module . '/Dao');
        }

        // create action file
        $actionContent = $this->getActionContent($module, $name, $author, $funcList);

        @file_put_contents(APP_PATH . 'Common/' . $module . '/Action/' . $name . 'Action.class.php', $actionContent);

        // create service file
        $serviceContent = $this->getServiceContent($module, $name, $author, $funcList);
        
        @file_put_contents(APP_PATH . 'Common/' . $module . '/Service/' . $name . 'Service.class.php', $serviceContent);

        // create dao file
        $daoContent = $this->getDaoContent($module, $name, $author, $tableName, $funcList);

        @file_put_contents(APP_PATH . 'Common/' . $module . '/Dao/' . $name . 'Dao.class.php', $daoContent);

        $this->success('生成成功');
        exit();
    }

    /**
     * 返回action内容
     */
    private function getActionContent($module, $name, $author, $funcList){
    	$template = @file_get_contents(APP_PATH . MODULE_NAME . "/Template/action/Base.tpl");

		// get function content
		$funcContent = $this->getFunctionContent('action', $funcList);

		return sprintf($template, $module, $module, $name, $name, $author, $name, $name, $funcContent);
    }

    /**
     * 返回service内容
     */
    private function getServiceContent($module, $name, $author, $funcList){
    	$template = @file_get_contents(APP_PATH . MODULE_NAME . "/Template/service/Base.tpl");

    	// get function content
		$funcContent = $this->getFunctionContent('service', $funcList);

		return sprintf($template, $module, $module, $name, $name, $author, $name, $name, $funcContent);
    }

    /**
     * 返回dao内容
     */
    private function getDaoContent($module, $name, $author, $tableName, $funcList){
    	$template = @file_get_contents(APP_PATH . MODULE_NAME . "/Template/dao/Base.tpl");

    	// get function content
		$funcContent = $this->getFunctionContent('dao', $funcList);

		return sprintf($template, $module, $name, $author, $name, $tableName, $funcContent);
    }

    /**
     * 返回action function内容
     * findById($id)
     * addInfo($data)
     * updateInfo($where,$data)
     * deleteInfo($where)
     * findAll($where,$pagesize,$page)
     * getCount($where)  总记录数
     */
    private function getFunctionContent($type, $funcList){
    	$funcContent = "";
		for ($i=0; $i < count($funcList); $i++) { 
			$item = $funcList[$i];
			// eg. actionFindById.tpl
			$fileName = strtolower($type) . "/" . $item . ".tpl";
			$template = @file_get_contents(APP_PATH . MODULE_NAME . "/Template/" . $fileName);
			$funcContent .= $template;
		}
		return $funcContent;
    }
}