<?php
/**
 * 插件钩子类
 * ay@7cuu.com
 * 2017-11-24
 *
 */
namespace Ayflying\Plugin;

// 插件类
class Hook{
    private $hookList;

    /**
     * Hook初始化
     */
    function __construct($dir)
    {
        
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
    public static function add($name,$func)
    {
        $GLOBALS['hookList'][$name][]=$func;
    }

    /**
     * 监听插件
     * @param $name 钩子名称
     * @param null $params 钩子方法参数
     * @return mixed|void
     */
    public static function listen($name,&$params=null)
    {
        if(empty($GLOBALS['hookList'][$name])) {
            //钩子为空
            return;
        }

        //$params为传递变量的指针
        foreach ($GLOBALS['hookList'][$name] as $k => $v){
            $params = call_user_func($v,$params);
        }

    }

 

    public function hookList()
    {
        return $this -> hookList;
    }
}

