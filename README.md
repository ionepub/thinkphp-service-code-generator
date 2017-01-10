# ThinkPHP 自动化代码生成工具

## 功能

> **ThinkPHP版本：3.2.3**

根据指定的类名和方法，自动生成`Action/Service/Dao`代码

生成的文件举例（tree of `/Application/Common/`）：

```
+--- Common
|   +--- Action
|   |   +--- TestAction.class.php
|   +--- Dao
|   |   +--- TestDao.class.php
|   +--- Service
|   |   +--- TestService.class.php

```

TestAction.class.php文件（示例）：
```
<?php
namespace Common\Common\Action;
use Common\Common\Service\TestService;

/**
 * TestAction
 * @author https://github.com/ionepub
 */
class TestAction
{
	private $service = null;

	public function __construct(){
		$this->service = new TestService();
	}
}
```

## 使用

### #1 拷贝文件并运行

将`Generator`文件夹复制到`APP_PATH`下，访问`http://demo.com/index.php/Generator`可以看到代码生成表单，界面如下：（demo.com为本地测试域名，需自行调整）

![界面](https://dn-shimo-image.qbox.me/XVierRuZELwzPl7v/image.png "界面")

### #2 填入参数

在表单中填入类名（必填）和数据表名（必填），点击生成按钮，即可自动生成代码。

| 参数  | 说明  |
| ------------ | ------------ |
|  模型名称 | 所属的外层文件夹名称，如填`Api`则所有生成的文件将在`APP_PATH/Common/Api/`下，默认为`Common`，一般为`/Application/Common/Common/`  |
|  类名 | 需要生成的类的类名，如填`Test`则将在`APP_PATH/Common/Api/Action/`下生成`TestAction.class.php`文件，文件中的类名即为`TestAction`，Service和Dao也相同  |
| 数据表名 | 如果在数据库连接配置项中配置了表前缀，则此处不需加入表前缀，如表名为`think_user`则填入`user`即可。 |
| 作者 | 填入自己的名字即可，用在类的注释中 |
| 函数列表 | 可供选择的函数，如果一个都没有选，则默认生成的类中只包含构造函数 |

生成成功的话，将在`模型名称`文件夹下自动创建`Action`、`Service`、`Dao`三个文件夹，并在其中分别放置`类名Action.class.php`、`类名Service.class.php`、`类名Dao.class.php`三个文件。如果文件夹或文件已存在，则覆盖。


### #3 预定义的方法说明

现有的几个方法：

1、`findById`：根据主键id查询单条数据

```php
/**
* 根据主键id查询单条数据
* @param int $id 查询条件
*/
public function findById($id=0){}
```

2、`addInfo`：向表中插入数据

```php
/**
* 向表中插入数据
* @param array $data 待插入数据数组
*/	
public function addInfo($data=array()){}
```

3、`updateInfo`：根据条件更新表中数据

```php
/**
* 根据条件更新表中数据
* @param array $where 查询条件数组
* @param array $data 待插入数据数组
*/	
public function updateInfo($where=array(), $data=array()){}
```

4、`deleteInfo`：根据条件删除表中数据

```php
/**
* 根据条件删除表中数据
* @param array $where 查询条件数组
*/	
public function deleteInfo($where=array()){}
```

5、`findAll`：根据条件查询表中数据（可分页）

```php
/**
* 根据条件查询表中数据
* @param array $where 查询条件数组
* @param int $pagesize 分页大小，为0则不分页
* @param int $page 当前页码
* @param string $order 排序方式
*/	
public function findAll($where=array(), $pagesize=0, $page=1, $order=""){}
```

6、`getCount`：根据条件获取表中数据总数

```php
/**
* 根据条件获取表中数据总数
* @param array $where 查询条件数组
*/	
public function getCount($where=array()){}
```

### #4 扩展自定义方法

只需要两步即可扩展自定义方法：

1、在`Application/Generator/Conf/config.php`中，找到`FUNCTION_LIST`配置项数组，向数组中添加自定义的方法名

2、在`Application/Generator/Template/`文件夹中，分别在`action`、`service`、`dao`中添加同名的tpl模板文件，并在模板文件中写入方法即可

举例：

假设需要增加根据用户名获取用户信息的方法：`getInfoByUsername`

Application/Generator/Conf/config.php

```php
<?php
return array(
	//'配置项'=>'配置值'
	'TMPL_FILE_DEPR'	=>	'_',  //设置模板分隔符
	'FUNCTION_LIST'		=>	array(
		'findById'		=>	'根据主键id查询单条数据',
		'addInfo'		=>	'向表中插入数据',
		'updateInfo'	=>	'根据条件更新表中数据',
		'deleteInfo'	=>	'根据条件删除表中数据',
		'findAll'		=>	'根据条件查询表中数据(分页)',
		'getCount'		=>	'根据条件获取表中数据总数',
		'getInfoByUsername'=>'根据用户名获取用户信息',
	),
);
```

Application/Generator/Template/action/getInfoByUsername.tpl

```
	
	/**
     * 根据用户名获取用户信息
     * @param string $userName 待查询用户名
     */	
	public function getInfoByUsername($userName=''){
		return $this->service->getInfoByUsername($userName);
	}
	
```

Application/Generator/Template/service/getInfoByUsername.tpl

```
	
	/**
     * 根据用户名获取用户信息
     * @param string $userName 待查询用户名
     */	
	public function getInfoByUsername($userName=''){
		return $this->dao->getInfoByUsername($userName);
	}
	
```

Application/Generator/Template/dao/getInfoByUsername.tpl

```
	
	/**
     * 根据用户名获取用户信息
     * @param string $userName 待查询用户名
     */	
	public function getInfoByUsername($userName=''){
		$where = array(
			'userName'	=>	$userName,
		);
		return $this->model->where($where)->find();
	}
	
```

再次运行：`http://demo.com/index.php/Generator`即可看到页面上函数列表中多了一个`根据用户名获取用户信息`的选项，勾选，点击生成按钮即可。

### #5 在Controller中调用

```php
<?php
namespace Home\Controller;
use Think\Controller;
use Common\Common\Action\TestAction;

class IndexController extends Controller {
	public function index(){
		$TestAction = new TestAction();
		$infoId = 10;
		$info = $TestAction->findById($infoId);
		dump($info);

		// $data = array(
        // 	'no'	=>	'abds'
        // );
        // dump($TestAction->addInfo($data));

        // $where = array(
        // 	'id'	=>	10
        // );
        // $data = array(
        // 	'no'	=>	'ahahahaha'
        // );
        // dump($TestAction->updateInfo($where, $data));

        // $where = array(
        // 	'id'	=>	236301
        // );
        // dump($TestAction->deleteInfo($where));

        // $where = array(
        // 	'id'	=>	array('gt', 236290)
        // );
        // dump($TestAction->findAll($where, 5, 2, 'id desc'));

        // $where = array(
        // 	'id'	=>	array('gt', 236290)
        // );
        // dump($TestAction->getCount($where));

	}
}
```

## 注意

1. 仅建议在初始化某个模块时使用，如果已经存在`TestAction`类，不建议再次使用此工具生成同名类，因为每次生成代码都会覆盖原有内容（除非你执意如此）

2. 自定义的方法建议是多个模块可以共用的，因为所有的模块生成时，默认勾选所有方法，如果不注意的话，可能会有多余的代码

3. 本代码仅在`ThinkPHP 3.2.3`版本下调试通过，其他版本未测试

4. 在自定义方法时，请尽可能遵守规则，在`action`、`service`和`dao`中添加合适的内容，以免造成本身代码的混乱