<?php
class CategoryAction extends Action{
	
	function view(){
		$id=$_GET['id'];
		if(empty($id) || !is_numeric($id))
			$this->error('请勿进行非法操作!');
		  //取导航数据
	  //实例化模型
	  $art=M('Article');
	  $cat=M('Category');
	  $cmt=M('Comment');
	  $ups=M('Uploads');
	  $lnk=M('Link');
	  $cnf=M('Config');
	  $usr=M('Admin');
	  /*
	  计数
	  */
	  $this->art_nums=$art->count();
	  $this->cat_nums=$cat->count();
	  $this->cmt_nums=$cmt->count();
	  $this->ups_nums=$ups->count();
	  #--------计数完
	  /*
	  菜单
	  */
	  $nav=$cat->where('published = 1')->order('od')->select();
	  foreach($nav as $k=>$v){
		$nav[$k]['nums']=$art->where('cat_id='.$v['id'])->count();
	  }
	  $this->nav=$nav;
	  #------菜单完
	  /*
	  配置
	  */
	  $usr_data=$usr->getById(1);
	  $config=array();
	  $config['SiteName']=$usr_data['sitename'];
	  $config['Mark']=$usr_data['mark'];
	  $config['UserName']=$usr_data['name'];
	  $config['Phone']=$usr_data['phone'];
	  $config['Email']=$usr_data['email'];
	  $this->conf=$config;
	  //配置完
	  /*
	  文章
	  */
	  //随机图片
	  $img_id=array();
	  $img_count=$ups->where("type='jpg'")->count();
	  $img=$ups->field('id')->where("type='jpg'")->select();
	  ///
	  $arr=array();
	  foreach($img as $v){
		array_push($arr,$v['id']);
	  }
	  $articles=new ArticleViewModel();
	  import('ORG.Util.Page');
	  $count=$articles->where('Article.published=1 and Article.cat_id='.$id)->count();
	  $page=new Page($count,4);
	  $list = $articles->where('Article.published=1  and Article.cat_id='.$id)->order('Article.od')->limit($page->firstRow.','.$page->listRows)->select();
	  foreach($list as $k=>$v){
			$tmp=$arr[array_rand($arr)];
			$tmp2=$ups->getById($tmp);
			$list[$k]['img']=$tmp2['savename'];
			$list[$k]['key']=$tmp;
	  }
	  $list2 = $articles->where('Article.published=1')->order('Article.comment desc')->limit(0,5)->select();
	  $this->page=$page->show();
	  $this->article_list=$list;
	  $this->art_list=$list2;
	  //
	  //文章完
	  //评论
	  $this->cmt_data=$cmt->order('time')->select();
	  //友情链接
	  $this->link=$lnk->where('published=1')->order('od')->select();
      $this->title='LeiBlog-基于ThinkPHP框架的个人博客系统';
	  $this->time=time();
	  $this->display();
	}
}
?>