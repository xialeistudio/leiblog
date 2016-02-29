<?php
class CommentViewModel extends ViewModel{
	public $viewFields=array(
	'Comment'=>array(
		'id',
		'name',
		'content',
		'time',
		),
	'Article'=>array(
		'title',
		'_on'=>'Article.id=Comment.art_id',
		),
	);
}
?>