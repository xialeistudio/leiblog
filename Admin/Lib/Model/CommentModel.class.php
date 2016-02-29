<?php
class CommentModel extends Model{
	protected $_validate=array(
		array('name','require','名称不能为空!',1,'regex',3),
		array('content','require','内容不能为空!',1,'regex',3),
		array('email','email','Email格式错误!',1,'regex',3),
	);
	protected $_auto=array(
		array('ip','get_client_ip',3,'function'),
		array('time','time',3,'function'),
	);
}
?>