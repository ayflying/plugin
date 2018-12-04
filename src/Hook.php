<?php
/**
 * 插件钩子类
 * ay@7cuu.com
 * 2017-11-24
 *
钩子使用方法
//引用命名空间
use Ay\auto\Hook;
//添加钩子
Hook::add('ceshi',function(){
    echo '挂载点1<br />';
});
//运行钩子
Hook::listen('ceshi');

*/
namespace Ayflying\Plugin; // 命名空间

// 插件类
class Hook{
    private $hookList;
    //public $hookList;

    /**
     * Hook初始化
     */
    function __construct()
    {
        $dir = __DIR__ . "/plugin/";
        // 获取全部插件
        if(!is_dir($dir)){
            $pluginList = [];
        }else{
            $pluginList=scandir($dir);
        }
        foreach ($pluginList as $k => $v) {
            if ($v=='.' || $v=='..') {
                unset($pluginList[$k]);
            }
            $pluginList[$k] = $dir.$v;
        }
        //print_r($pluginList);
        foreach($pluginList as $val){
            if(!is_file($val."/config.php")) break;
            $config = include $val."/config.php";
            if($config['status'] == 0) continue;
            try{
                is_file($val."/add.php") && include_once $val . "/add.php";
            }catch(Exception $e){
                echo 'Message: ' .$e->getMessage();
            }

        }
    }

    /**
     *  注册添加插件
     * @param $name 钩子名称
     * @param $func 钩子使用的方法
     */
    public static function add($name,$func){
        $GLOBALS['hookList'][$name][]=$func;
        //self::hookList[$name][] = $func;
        //self::hookList()[$name][] = $func;
        //self::$hookList[$name][] = $func;

    }

    /**
     * 监听插件
     * @param $name 钩子名称
     * @param null $params 钩子方法参数
     * @return mixed|void
     */
    public static function listen($name,$params=null){
        if(empty($GLOBALS['hookList'][$name])) {
            //钩子为空
            return;
        }
        //判断是否为数组
        //self::$hookList;
        if(count($GLOBALS['hookList'][$name]) > 1){
            foreach ($GLOBALS['hookList'][$name] as $k => $v){
                $obj[] = call_user_func($v,$params);
            }
        }else{
            $obj[] = call_user_func($GLOBALS['hookList'][$name][0],$params);
        }
        return $obj;
    }

    /**
     * listen的别名方法
     * @param $name
     * @param null $params
     * @return mixed|void
     */
    public static function run($name,$params=null){
        return self::listen($name,$params);
    }


    public function hookList(){
        return $this -> hookList;
    }
}

