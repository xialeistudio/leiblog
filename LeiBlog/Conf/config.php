<?php
$cm=require './common.php';
$ts=array(
	//'配置项'=>'配置值'	
	'DATA_CACHE_TYPE'=>'File',
	'DATA_CACHE_TIME'=>3600,
	'URL_MODEL'=>2,
	'URL_PATHINFO_DEPR'=>'-',
	'URL_HTML_SUFFIX'=>'.shtml',
	'URL_ROUTER_ON'=>true,
	'URL_ROUTE_RULES'=>array(
	'Category/:id\d'=>'Category/view',
	'Article/:id\d'=>'Article/view',
	),
);
return array_merge($cm,$ts);
?>