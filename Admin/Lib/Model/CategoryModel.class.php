<?php
class CategoryModel extends Model{
	protected $_validate=array(
	array('name','require','分类不能为空!'),
	array('od','checkod','排序数据错误!',1,'callback',3),
	);
	function checkod(){
		if(is_numeric($_POST['od']))
			return true;
		else
			return false;	
	}
}
?>