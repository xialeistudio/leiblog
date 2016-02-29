<?php
class ArticleAction extends Action{
	function view(){
	$id=$_GET['id'];
	//取导航数据
	  //实例化模型
	  $art=M('Article');
	  $cat=M('Category');
	  $cmt=M('Comment');
	  $ups=M('Uploads');
	  $lnk=M('Link');
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
	  $count=$articles->where('Article.published=1')->count();
	  $page=new Page($count,4);
	  $list = $articles->where('Article.published=1')->order('Article.od')->limit($page->firstRow.','.$page->listRows)->select();
	  foreach($list as $k=>$v){
			$tmp=$arr[array_rand($arr)];
			$tmp2=$ups->getById($tmp);
			$list[$k]['img']=$tmp2['savename'];
			$list[$k]['key']=$tmp;
	  }
	  $this->new_art=$articles->where('Article.published=1')->order('Article.created desc')->limit(0,6)->select();//最新日志
	  $list2 = $articles->where('Article.published=1')->order('Article.comment desc')->limit(0,6)->select();
	  $art_list=$art->select();
	  $art_list2=array();
	  foreach($art_list as $v){
		  array_push($art_list2,$v['id']);
		 }
	  $art_xxx=array();
	  $art_rand=array_rand($art_list2,$count%6);//随机出下标
	  foreach($art_rand as $k=>$v){
		  array_push($art_xxx,$art_list2[$v]);
	  }
	  //$art_xxx存的是文章id
	  $in=implode(',',$art_xxx);
	  $this->rand_art=$articles->where('Article.published=1 and Article.id in( '.$in.') ')->order('Article.comment desc')->limit(0,6)->select();
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
	  //////////查询文章和评论信息
	  $attt=new ArticleViewModel();
	  $data=$attt->getByAid($id);
	  if(!$data)
		  $this->error('文章不存在!');
		  /*缓存设置*/
	  $cacheData = S('data'.$id);
	  if(empty($cacheData))
	  {
		  S('data'.$id,$data);
	  }
	  $data = S('data'.$id);
	  $this->data=$data;
	  $this->ccc=$cmt->where("art_id=$id")->count();
	  $this->cmtt=$cmt->where("art_id=$id")->select();
	  //通过判断SESSION来实现文章点击量
		if(empty($_SESSION['hits']))
		{	
		$art->where("id=$id")->setInc('hits');
		$_SESSION['hits']=1;
		}
	  $this->display();
	}
	function comment(){
		$cmt=D('Comment');	
		//读取文章编号和ip
		$ip=get_client_ip();
		$id=$_POST['art_id'];
		$tm=time();
		$ck=$cmt->where("ip='$ip' and art_id=$id")->find();
		if($tm<$ck['time']+3600)//时间过短
		{
				$this->ajaxReturn('','您一小时内评论过啦!',0);
		}
		$data=$cmt->create();
		if($data){
			if($cid=$cmt->add())
			{
				$art_id=$data['art_id'];
				$art=M('Article');
				$art->where("id=$art_id")->setInc('comment');
				//查询该留言时间
				$tm=$cmt->getById($cid);
				//组织ajax返回数据
				$rs['name']=$data['name'];
				$rs['content']=$data['content'];
				$rs['hrefcontent']='<a href="'.U('Article/'.$data['art_id']).'">'.$data['content'].'</a>';
				$rs['time']=date('Y-m-d H:i:s',$tm['time']);
				$rs['nums']=$art->where("id={$data['art_id']}")->getField('comment');
				$this->ajaxReturn($rs,'',1);
			}else{
				$this->ajaxReturn('','评论失败!',0);
				}
		}else{
			$this->ajaxReturn('',$cmt->getError(),0);	
		}
	}
}
?>