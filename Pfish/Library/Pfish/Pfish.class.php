<?php
/**
 * PfishºËÐÄÀà¿âÎÄ¼þ
 * 
 * 1.引入所需的类库文件
 * 2.自动检测建立项目文件
 * 3.调运加载系统运行文件
 */
namespace Pfish;

class Pfish {
    
    /**
     * 程序开始文件
     * @return void
     */
    static public function start () {
        
        // 注册autoload方法(自动引进所需的类)
        spl_autoload_register('Pfish\Pfish::autoload');    
        
        // 读取模式配置文件
        $mode  =  include is_file(CONF_PATH.'core.php') ? 
            CONF_PATH.'core.php' : MODE_PATH.'common.php';
            
        // 加载函数文件
        foreach ($mode['core'] as $file) {
            if(is_file($file)) {
                include $file;
            }
        }
        
        // 加载配置文件
        foreach ($mode['config'] as $key=>$file){
           is_numeric($key) ? C(load_config($file)) : C($key, load_config($file));
        }
       
        $module = 'Home';
        // 自动创建系统项目文件
        if (!is_dir(APP_PATH.$module)) {
            Build::checkDir($module);
        }
        
        // 系统应用运行
        App::run();

        return ;
    }

    /**
     * 自动加载系统类库文件
     * @param $className 类名称
     * @return void
     */
    static public function autoload($className) {

        if (explode('//', $className)) {
            $className = str_replace( '\\', DIR_SEP, $className);
        }
        
        $filename = PRO_PATH.'Pfish/Library/'.$className.'.class.php';
        if (file_exists($filename) && !class_exists($className)) {
            include_once $filename;
        } else {
            echo "Unable to load class: ".$className;
        }
      
        return false;        
    }

    /**
     * 取得对象实例 支持调用类的静态方法
     * @param string $class 对象类名
     * @param string $method 类的静态方法名
     * @return object
     */
    static public function instance($class, $method='') {
        
        if (class_exists($class)) {
            $o = new $class();
            return $o;
        } 
                
    }
}

?>