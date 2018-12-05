## plugin是一个自动插件类，可以不依赖其他框架在页面中插入钩子


## 下载
~~~
composer require ayflying/plugin
~~~

## 使用
~~~
use Ayflying\Plugin;
new Hook(__DIR__.'/plugin');
~~~

## 添加钩子
~~~
Hook::add('ceshi',function(){
    echo '挂载点1<br />';
});
~~~

## 运行钩子
~~~
Hook::listen('ceshi');
~~~