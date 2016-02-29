<?php
class LinkModel extends Model{
	protected $_validate=array(
		array('name','require','链接名称不能为空!'),
		array('url','checkurl','URL格式错误!',1,'callback',3),
		array('od','checkod','排序格式错误!',1,'callback',3),
	);
	function checkurl(){
		$url=$_POST['url'];
		if(!empty($url)){
			if(preg_match('/^http:[\/]{2}[a-z]+[.]{1}[a-z\d\-]+[.]{1}[\w]{2,5}/',$url)){
				return true;
			}else{
				return false;
				}
		}else{
			return false;
			}	
	}
	function checkod(){
		if(is_numeric($_POST['od'])){
			return true;
			}else{
				return false;
				}
	}
}
?>