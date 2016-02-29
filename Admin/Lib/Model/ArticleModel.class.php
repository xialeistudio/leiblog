<?php
class ArticleModel extends Model{
	protected $_validate=array(
		array('title','require','文章标题不能为空!'),
		array('content','require','文章内容不能为空!'),
	);
	protected $_auto=array(
		array('title_alias','getTitleAlias',3,'callback'),
		array('alias','getAlias',3,'callback'),
		array('created','time',1,'function'),
		array('created_by','getUser',1,'callback'),
	);
	function getAlias(){
		if(empty($_POST['title_alias'])){
			return date('Y-m-d-H-i-s');
			}else{
				return $_POST['title_alias'];
				}
		}
	function getTitleAlias(){
		return substr(strip_tags($_POST['content']),0,255);
	}
	function getUser(){
		if(!empty($_SESSION['id'])){
			return $_SESSION['id'];
			}else{
				return 0;
			}
	}
}
?>