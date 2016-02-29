<?php
class PublicAction extends Action{
	function uploads(){
		import('ORG.Net.UploadFile');

		$upload = new UploadFile();// 实例化上传类

		$upload->maxSize  = 1024*1024*2 ;// 设置附件上传大小

		$upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg','txt','zip');// 设置附件上传类型

		$upload->savePath =  './Public/uploads/';// 设置附件上传目录
		
		$upload->saveRule=date('YmdHis');

		$upload->thumb=true;

		$upload->thumbMaxWidth=140;

		$upload->thumbMaxHeight=144;

		$upload->thumbFile=date('YmdHis');

		$upload->thumbRemoveOrigin=true;

		if(!$upload->upload()) {// 上传错误提示错误信息

		$this->error($upload->getErrorMsg());

			}else{// 上传成功 获取上传文件信息
	
		$info =  $upload->getUploadFileInfo();

 	}
		$data=$info[0];
// 保存表单数据 包括附件数据
		$uploads=M('Uploads');
		$file['type']=$data['extension'];
		$file['savepath']=$data['savepath'];
		$file['savename']=$data['savename'];
		$file['truename']=$data['name'];
		if($uploads->add($file)){
			echo '上传成功~';
			}
		}
	}
?>