<?php
//模板类
class Template {
	//属性
	public $template_dir = "templates";
	public $compile_dir = "templates_c";
	private $tpl_vars = array(); //专用于保存分配过来的变量
	public $caching = false; //是否开启缓存，默认不开启
	public $cache_dir = "cache"; //缓存目录

	//构造方法
	public function __construct(){
		ob_start();
	}
	//分配数据方法
	public function assign($name,$value){
		if (!empty($name)) {
			$this->tpl_vars[$name] = $value;
		} else {
			exit('变量名不能为空！');
		}		
	}

	//载入视图方法
	public function display($file){
		//1.要读入模板文件
		$tplFile = $this->template_dir . "/" .  $file; //模板文件名
		$compileFile = $this->compile_dir . "/" . md5($file) . ".{$file}.php"; //编译文件名
		$cacheFile = $this->cache_dir . "/" . md5($file) . ".{$file}"; //缓存文件名
		//如果缓存文件存在，并且有效,直接访问缓存文件
		if (file_exists($cacheFile) && file_exists($compileFile) && filemtime($tplFile) <= filemtime($compileFile)) {
			echo "走缓存了";
			include $cacheFile;
			return;
		}
		//如果编译文件不存在，或者模板文件被修改，则重新编译
		if (!file_exists($compileFile) || filemtime($tplFile) > filemtime($compileFile) ) {
			//echo "走编译了";
			$data = file_get_contents($tplFile);
			//2.将模板文件中的变量进行替换 --- 解析工作
			//引入parse类
			include "libs/Parse.class.php";
			$parse = new Parse($data);
			$data = $parse->parse();		
			//3.需要生成一个编译文件
			file_put_contents($compileFile, $data);
		}
		
		//载入编译文件
		include $compileFile;
		//判断是否开启缓存
		if ($this->caching) {
			//生成缓存文件
			$data =  ob_get_contents(); //?如何得到			
			file_put_contents($cacheFile, $data);
			ob_end_clean(); //清除缓冲区的内容并关掉它
			//载入缓存文件
			include $cacheFile;
		}
	}
}