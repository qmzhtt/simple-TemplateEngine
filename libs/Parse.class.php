<?php
//解析类
class Parse{
	//属性
	private $data = ""; //要解析的数据
	//构造方法
	public function __construct($data){
		$this->data = $data;
	}
	//解析方法
	public function parse(){
		$this->parseVar();
		$this->parseIf();
		$this->parseFor();
		//返回结果
		return $this->data;
	}
	//解析变量
	/*
	源：{$title}
	目标：<?php echo $this->tpl_vars['title'];?>
	 */
	private function parseVar(){
		$pattern = '/\{\$(\w+)\s*\}/';
		if (preg_match($pattern, $this->data)) {
			$this->data = preg_replace($pattern, "<?php echo \$this->tpl_vars['\\1'];?>", $this->data);
		}
	}
	//解析if结构
	/*
	源：{if $love}
			真爱
		{else}
			骗子
		{/if}
	目标：<?php if ($this->tpl_vars['love']):?>
	        真爱
	      <?php else :?>
	      	骗子
	      <?php endif;?>
	 */
	private function parseIf(){
		$patternIf = '/\{if\s+\$(\w+)\s*\}/';
		$patternEnd = '/\{\/if\s*\}/';
		$patternElse = '/\{else\s*\}/';
		if (preg_match($patternIf, $this->data)){
			if (preg_match($patternEnd, $this->data)) {
				$this->data = preg_replace($patternIf, "<?php if (\$this->tpl_vars['\\1']):?>", $this->data);
				$this->data = preg_replace($patternEnd, "<?php endif;?>", $this->data);
				//else是可选的
				if (preg_match($patternElse, $this->data)) {
					$this->data = preg_replace($patternElse, "<?php else :?>", $this->data);
				}
			} else {
				exit('模板语法错误');
			}
		}
	}

	//解析foreach结构
	/*
	源：{foreach $user as $k => $v}
			<li>{@k} --- {@v}</li>
		{/foreach}
	目标:<?php foreach ($this->tpl_vars['user'] as $k => $v) :?>
			<li><?php echo $k;?> --- <?php echo $v;?> </li>
		 <?php endforeach;?>
	 */
	private function parseFor(){
		$patternFor = '/\{foreach\s+\$(\w+)\s+as\s+\$(\w+)\s*=>\s*\$(\w+)\s*\}/';
		$patternEnd = '/\{\/foreach\s*\}/';
		$patternVar = '/\{@(\w+)\}/';
		if (preg_match($patternFor, $this->data)) {
			if (preg_match($patternEnd, $this->data)) {
				$this->data = preg_replace($patternFor, 
					"<?php foreach (\$this->tpl_vars['\\1'] as \$\\2 => \$\\3) :?>", $this->data);
				$this->data = preg_replace($patternEnd, "<?php endforeach;?>", $this->data);
				if (preg_match($patternVar, $this->data)) {
					$this->data = preg_replace($patternVar, "<?php echo \$\\1;?>", $this->data);
				}
			} else {
				exit("模板语法错误");
			}
		}
	}
}
