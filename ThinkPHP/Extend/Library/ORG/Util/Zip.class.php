<?php
class Zip {
	/**
	 * 生成zip压缩文件的函数
	 *
	 * @param $dir string
	 *        	需要压缩的文件夹名
	 * @param $filename string
	 *        	压缩后的zip文件名 包括zip后缀
	 * @param $missfile array
	 *        	不需要的文件
	 * @param $fromString array
	 *        	自定义压缩文件
	 *        	比如我往里面加一个 内容为 this is my file 的 info.ini 可以这样定义 array(array('info.ini','this is my file'));
	 *        	
	 */
	function __construct(){
		set_time_limit(0);
	}
	function zip($dir, $filename, $autoload = true, $missfile = array(), $addfromString = array()) {
		if (! file_exists ( $dir ) || ! is_dir ( $dir )) {
			die ( ' can not exists dir ' . $dir );
		}
		$array = explode ( '.', $filename );
		if (strtolower ( end ( $array ) ) != 'zip') {
			die ( 'only Support zip files' );
		}
		$dir = str_replace ( '\\', '/', $dir );
		$filename = str_replace ( '\\', '/', $filename );
		if (file_exists ( $filename )) {
			die ( 'the zip file ' . $filename . ' has exists !' );
		}
		$files = array ();
		$this->getfiles ( $dir, $files );
		if (empty ( $files )) {
			die ( ' the dir is empty' );
		}
		
		$zip = new ZipArchive ();
		$res = $zip->open ( $filename, ZipArchive::CREATE );
		if ($res === TRUE) {
			foreach ( $files as $v ) {
				if (! in_array ( str_replace ( $dir . '/', '', $v ), $missfile )) {
					$zip->addFile ( $v, str_replace ( $dir . '/', './', $v ) );
				}
			}
			if (! empty ( $addfromString )) {
				foreach ( $addfromString as $v ) {
					$zip->addFromString ( $v [0], $v [1] );
				}
			}
			$zip->close ();
			if($autoload){
				$this->download($filename);
				unlink($filename);
			}
		}else{
			exit('Failed!');
		}
	}
	
	function getfiles($dir, &$files = array()) {
		if (! file_exists ( $dir ) || ! is_dir ( $dir )) {
			return;
		}
		if (substr ( $dir, - 1 ) == '/') {
			$dir = substr ( $dir, 0, strlen ( $dir ) - 1 );
		}
		$_files = scandir ( $dir );
		foreach ( $_files as $v ) {
			if ($v != '.' && $v != '..') {
				if (is_dir ( $dir . '/' . $v )) {
					$this->getfiles ( $dir . '/' . $v, $files );
				} else {
					$files [] = $dir . '/' . $v;
				}
			}
		}
		return $files;
	}
	
	function download($filename){
		if(!file_exists($filename)){
			exit;
		}
		$fp=fopen($filename,"r");
		$file_size=filesize($filename);
		Header("Content-type: application/octet-stream");
		Header("Accept-Ranges: bytes");
		Header("Accept-Length:".$file_size);
		Header("Content-Disposition: attachment; filename=".$filename);
		$buffer=1024;
		$file_count=0;
		while(!feof($fp) && $file_count<$file_size){
			$file_con=fread($fp,$buffer);
			$file_count+=$buffer;
			echo $file_con;
		}
		fclose($fp);
	}
}
 
 