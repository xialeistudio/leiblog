<?php
class AdminModel extends Model{
	protected $_validate=array(
		array('name','require','姓名不能为空!'),
		array('mark','require','签名不能为空!'),
		array('email','email','邮箱格式错误!'),
		array('phone','checkPhone','手机号码格式错误!',0,'callback',3),
	);
	protected $_auto=array(
	array('login_date','time',3,'function'),
	array('login_ip','get_client_ip',3,'function'),
	array('password','md5',1,'function'),
	);
	function checkPhone(){
		if(!empty($_POST['phone']))
			{
				if(preg_match('/^[\d]{11}/',$_POST['phone'])){
					return true;
					}else{
						return false;
						}
			}else{
				return false;
				}
	}
}
?>