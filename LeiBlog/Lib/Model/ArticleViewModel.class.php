<?php
class ArticleViewModel extends ViewModel{
	public $viewFields=array(
	'Article'=>array(
		'id'=>'aid',
		'title'=>'atitle',
		'title_alias'=>'atitle_alias',
		'alias'=>'aalias',
		'content'=>'acontent',
		'hits',
		'od'=>'aod',
		'created',
		'created_by',
		'published'=>'apublished',
		'cat_id',
		'comment'=>'acomment',
		'_type'=>'left',
		),
	'Category'=>array(
		'name'=>'cname',
		'_on'=>'Category.id=Article.cat_id',
		'_type'=>'left',
		),
	'Admin'=>array(
		'name'=>'uname',
		'_on'=>'Admin.id=Article.created_by',
		),
	
	);
}
?>