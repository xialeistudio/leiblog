<?php
function ubb($string){
	$string = preg_replace('/\[hilitecolor=(.*)\](.*)\[\/hilitecolor\]/U','<span style="background-color:\1">\2</span>',$string);
	//$string = nl2br($string);
	$string = preg_replace('/\[p\](.*)\[\/p\]/U','<p>\1</p>',$string);
	$string = preg_replace('/\[size=(.*)\](.*)\[\/size\]/U','<span style="font-size:\1px">\2</span>',$string);
	$string = preg_replace('/\[b\](.*)\[\/b\]/U','<strong>\1</strong>',$string);
	$string = preg_replace('/\[i\](.*)\[\/i\]/U','<em>\1</em>',$string);
	$string = preg_replace('/\[u\](.*)\[\/u\]/U','<span style="text-decoration:underline">\1</span>',$string);
	$string = preg_replace('/\[s\](.*)\[\/s\]/U','<span style="text-decoration:line-through">\1</span>',$string);
	$string = preg_replace('/\[align=left\](.*)\[\/align\]/U','<p align="left">\1</p>',$string);
	$string = preg_replace('/\[align=center\](.*)\[\/align\]/U','<p align="center">\1</p>',$string);
	$string = preg_replace('/\[align=right\](.*)\[\/align\]/U','<p align="right">\1</p>',$string);
	$string = preg_replace('/\[color=(.*)\](.*)\[\/color\]/U','<span style="color:\1">\2</span>',$string);
	$string = preg_replace('/\[url=(.*)\](.*)\[\/url\]/U','<a href="\1" target="_blank">\2</a>',$string);
	$string = preg_replace('/\[email\](.*)\[\/email\]/U','<a href="mailto:\1">\2</a>',$string);
	$string = preg_replace('/\[img\](.*)\[\/img\]/U','<img src="\1" alt="图片" />',$string);
	$string = preg_replace('/\[flash\](.*)\[\/flash\]/U','<embed style="width:480px;height:400px;" src="\1" />',$string);
	return $string;
}
?>