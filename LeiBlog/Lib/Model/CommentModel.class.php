<?php
class CommentModel extends Model{
	protected $_validate=array(
	array('name','require','姓名不能为空!'),
	array('content','require','内容不能为空!'),
	array('email','email','邮箱格式错误!'),
	);
	protected $_auto=array(
		array('ip','get_client_ip',3,'function'),
		array('time','time',3,'function'),
	);
}
?>