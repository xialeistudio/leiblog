<?php
// 本类由系统自动生成，仅供测试用途
class IndexAction extends Action {
	function _initialize(){
	//判断是否是login,login_do操作
	if(!in_array(ACTION_NAME,array('login','login_do','doImage'))){
			if(empty($_SESSION['id'])){
				$this->error('请登录!',U('login'));	
			}
		}
	
	}
	
    public function index(){
		$this->title='LeiBlog-基于ThinkPHP框架的个人博客系统';
       $this->display();
    }
	
	function clearCache(){
		$cache = Cache::getInstance('Memcache');
		$cache->clear();
		header('Location:'.__APP__);
	}
	
	function login(){
		$this->title='LeiBlog-基于ThinkPHP框架的个人博客系统';
		 $this->display();
	}
	function login_do(){
		$admin=D('Admin');
		$data=$admin->create();
		if($data){
			$user=$admin->getByUsername($data['username']);
			if($user){
				if($user['password']==$data['password']){
						$_SESSION['id']=$user['id'];
						$admin->where('id='.$user['id'])->save($data);
						$this->ajaxReturn('','',1);
					}else{
						$this->ajaxReturn('','密码错误!',0);
						}
				}else{
					$this->ajaxReturn('','用户不存在!',0);
					}
			}else{
				$this->ajaxReturn('',$admin->getError(),0);
				}
		}
	function logout(){
		unset($_SESSION['id']);
		session_destroy();
		$this->success('注销成功!',U('login'));
	}
	
	function main(){
		//实例化模型
		/*$article=M('Article');
		$category=M('Category');
		$comment=M('Comment');
		$link=M('Link');
		$count=array(
			'article'=>$article->count(),
			'category'=>$category->count(),
			'comment'=>$comment->count(),
			'link'=>$link->count(),
		);
		$this->count=$count;
		*/
		$this->display();
		
	}
	//top
	function top(){
		//获取ID
		$id=$_SESSION['id'];
		$admin=M('Admin');
		$this->admin=$admin->getById($id);
		$this->display();	
	}
	function footer(){
		//查询IP地址
		//导入库文件
		import('ORG.Net.IpLocation');
		$ip=new IpLocation();
		$this->addr=$ip->getlocation(get_client_ip());
		$this->ip=get_client_ip();
		$this->time=time();
		$this->display();
		}
	//个人信息
	function person(){
		$admin=M('Admin');
		$this->data=$admin->getById($_SESSION['id']);
		$this->display();
	}
	
	//处理个人信息
	function doPerson(){
		$uid=$_SESSION['id'];
		$admin=D('Admin');
			$data=$admin->create();
			if($data){
				$opwd=$_POST['opwd'];
				$data['password']=empty($data['password'])?$opwd:md5($data['password']);
				if($admin->save($data)){
					$this->ajaxReturn('','个人信息更新成功!',1);
				}else{
					$this->ajaxReturn('','个人信息更新失败!'.$admin->getDbError(),0);
					}
			}else{
				$this->ajaxReturn('',$admin->getError(),0);
				}
	}
	
	//站点信息
	function site(){
		$this->think_version=THINK_VERSION;
		$this->display();
	}
	//添加分类
	function categoryAdd(){
		$this->display();
	}
	//处理添加
	function categoryAddDo(){
		$cat=D('Category');
		$count=$cat->where('show_nav=1')->count();
		$data=$cat->create();
		if($data){
			if($data['show_nav']==1)
			{
				if($count>=4)
					$this->ajaxReturn('','导航栏菜单数达到上限!',0);	
			}
			if($cat->add()){
				$this->ajaxReturn('','',1);	
			}else{
				$this->ajaxReturn('','添加失败!',0);
				}
		}else{
			$this->ajaxReturn('',$cat->getError(),0);
			}
	}
	//分类列表
	function categoryList(){
		$cat=M('Category');
		$count=$cat->count();
		import('ORG.Util.Page');
		$page=new Page($count,8);
		$show=$page->show();
		$list=$cat->order('od')->limit($page->firstRow.','.$page->listRows)->select();
		$this->page=$show;
		$this->list=$list;
		$this->display();	
	}
	//编辑分类
	function categoryEdit(){
		$id=$_GET['id'];
		if(is_numeric($id)){
			$cat=M('Category');
			$data=$cat->getById($id);
			$this->data=$data;
			$this->display();
		}else{
			$this->error('数据错误!');
			}	

	}
	//处理编辑分类
	function categoryEditDo(){
		$cat=D('Category');
		$data=$cat->create();
		if($data){
			if($cat->save()){
				$this->ajaxReturn('','',1);	
			}else{
				$this->ajaxReturn('','更新失败!',0);
				}
		}else{
			$this->ajaxReturn('',$cat->getError(),0);
			}
	}
	//处理分类是否有效
	function categoryPublished(){
		$id=$_POST['id'];
		$value=$_POST['value'];
		$cat=M('Category');
		$data['id']=$id;
		$data['published']=$value;
		$cat->save($data);
		//更新文章状态
		$art=M('Article');
		$art->where("cat_id=$id")->setField('published',$value);
	}
	//是否显示在导航栏
	function categoryShowNav(){
		$id=$_POST['id'];
		$value=$_POST['value'];
		$cat=M('Category');
		if($value==0)
		{
			$data['id']=$id;
			$data['show_nav']=$value;
			$cat->save($data);
			$this->ajaxReturn('','',1);
		}
		$count=$cat->where('show_nav=1')->count();
		if($count<4)
		{
		$data['id']=$id;
		$data['show_nav']=$value;
		$cat->save($data);
		$this->ajaxReturn('','',1);
		}else{
			$this->ajaxReturn('','显示在导航栏的分类达到上限!',0);
			}
	}
	//分类排序
	function categoryOd(){
		$id=$_POST['id'];
		$od=$_POST['od'];
		if(is_numeric($od)){
			$cat=M('Category');
			$cat->where("id=$id")->setField('od',$od);
			$this->ajaxReturn('','',1);
		}else{
			$this->ajaxReturn('','排序必须是数字!',0);
			}	
	}
	//删除分类
	function categoryDelete(){
		$id=$_POST['id'];
		$did=implode(',',$id);
		//in
		$in='in('.$did.')';
		//查询分类下是否有文章
		$article=M('Article');
		$data=$article->where("cat_id ".$in)->select();
		if(!$data){
			//删除分类
				$where='id in('.$did.')';
				$cat=M('Category');
				if($cat->where($where)->delete()){
					$this->success('删除成功!');
					}else{
					$this->error('删除失败!');	
					}
		}else{
			$this->error('分类下面有文章!不可删除!');
			}
	}
	//添加文章
	function articleAdd(){
		//取分类数据
		$cat=M('Category');
		$this->cat=$cat->where('published=1')->order('od')->select();
		$this->display();
	}
	//处理添加文章
	function articleAddDo(){
		$art=D('Article');
		$data=$art->create();
		if($data){
			if($id=$art->add()){
				$this->ajaxReturn($id,'',1);	
			}else{
				$this->ajaxReturn('','添加失败!',0);
				}
		}else{
			$this->ajaxReturn('',$art->getError(),0);
			}	
	}
	//文章编辑
	function articleEdit(){
		$id=$_GET['id'];
		if(is_numeric($id)){
		//分类	
		$cat=M('Category');
		$this->cat=$cat->where('published=1')->order('od')->select();
		$art=M('Article');
		$this->data=$art->getById($id);
		$this->display();
		}else{
			$this->error('数据错误!');
			}
		
	}
	//处理文章编辑
	function articleEditDo(){
		$art=D('Article');
		$data=$art->create();
		if($data){
			if($id=$art->save()){
				$this->ajaxReturn($id,'',1);	
			}else{
				$this->ajaxReturn('','编辑失败!',0);
				}
		}else{
			$this->ajaxReturn('',$art->getError(),0);
			}	
			
	}
	//文章列表
	function articleList(){
		$art=new ArticleViewModel();
		$count=$art->count();
		import('ORG.Util.Page');
		$page=new Page($count,8);
		$show=$page->show();
		$list=$art->order('Article.cat_id')->limit($page->firstRow.','.$page->listRows)->select();
		$this->page=$show;
		$this->list=$list;
		$this->display();	
	}
		//处理文章是否有效
	function articlePublished(){
		$id=$_POST['id'];
		$value=$_POST['value'];
		$art=M('Article');
		$data['id']=$id;
		$data['published']=$value;
		$art->save($data);
	}
		//文章排序
	function articleOd(){
		$id=$_POST['id'];
		$od=$_POST['od'];
		if(is_numeric($od)){
			$art=M('Article');
			$art->where("id=$id")->setField('od',$od);
			$this->ajaxReturn('','',1);
		}else{
			$this->ajaxReturn('','排序必须是数字!',0);
			}	
	}
	//删除文章
	function articleDelete(){
		$id=$_POST['id'];
		$did=implode(',',$id);
		//in
		$in='in('.$did.')';
		//查询文章下是否有评论
		$cmt=M('Comment');
		$data=$cmt->where("art_id ".$in)->select();
		if(!$data){
			//删除文章
				$where='id in('.$did.')';
				$art=M('Article');
				if($art->where($where)->delete()){
					$this->success('删除成功!');
					}else{
					$this->error('删除失败!');	
					}
		}else{
			//删除文章及评论
				$cmt->where("art_id ".$in)->delete();
				$where='id in('.$did.')';
				$art=M('Article');
				if($art->where($where)->delete()){
					$this->success('删除成功!');
					}else{
					$this->error('删除失败!');	
					}
			}
	}
	//文章图片
	function articleImage(){
		$this->display();
	}
	//评论列表
	function commentList(){
		$cmt=new CommentViewModel();
		$count=$cmt->count();
		import('ORG.Util.Page');
		$page=new Page($count,8);
		$show=$page->show();
		$list=$cmt->limit($page->firstRow.','.$page->listRows)->select();
		$this->page=$show;
		$this->list=$list;
		$this->display();
	}
	//删除评论
	function commentDelete(){
		//传过来的是评论ID
		$id=$_POST['id'];
		if(!empty($id) && is_array($id)){
		$cmt=M('Comment');
		$art=M('Article');
		foreach($id as $value){
			$ar=$cmt->getById($value);
			$art_id=$ar['art_id'];
			$cmt->where("id=$value")->delete();
			$art->where("id=$art_id")->setDec('comment');
		}
		$this->success('删除成功!');
	}else{
		$this->error('请选择要删除的评论!');
	}
		
	}
	//添加友链
	function linkAdd(){
		$this->display();
	}
	//处理添加友链
	function linkAddDo(){
		$lnk=D('Link');	
		$data=$lnk->create();
		if($data){
			if($lnk->add()){
				$this->ajaxReturn('','',1);
				}else{
					$this->ajaxReturn('','添加失败!',0);
				}
		}else{
			$this->ajaxReturn('',$lnk->getError(),0);
			}
	}
	//友链列表
	function linkList(){
		$lnk=D('Link');
		$count=$lnk->count();
		import('ORG.Util.Page');
		$page=new Page($count,8);
		$show=$page->show();
		$list=$lnk->order('od')->limit($page->firstRow.','.$page->listRows)->select();
		$this->page=$show;
		$this->list=$list;
		$this->display();	
	}
	//删除链接
	function linkDelete(){
		$id=$_POST['id'];
		$did=implode(',',$id);
		$lnk=M('Link');
		if($lnk->where("id in(".$did.")")->delete()){
			$this->success('删除成功!');	
		}else{
			$this->error($lnk->getLastSql());
			}
		
	}
	//链接是否有效
	function linkPublished(){
		$id=$_POST['id'];
		$value=$_POST['value'];
		$art=M('Link');
		$data['id']=$id;
		$data['published']=$value;
		$art->save($data);
	}
			//链接排序
	function linkOd(){
		$id=$_POST['id'];
		$od=$_POST['od'];
		if(is_numeric($od)){
			$art=M('Link');
			$art->where("id=$id")->setField('od',$od);
			$this->ajaxReturn('','',1);
		}else{
			$this->ajaxReturn('','排序必须是数字!',0);
			}	
	}
	//编辑链接
	function linkEdit(){
		$id=$_GET['id'];
		if(is_numeric($id)){
			$lnk=M('Link');
			$this->data=$lnk->getById($id);
			$this->display();
		}else{
			$this->error('数据错误!');
		}	
	}
	function linkEditDo(){
		$lnk=D('Link');
		$data=$lnk->create();
		if($data){
			if($lnk->save()){
				$this->ajaxReturn('','',1);	
			}else{
				$this->ajaxReturn('','编辑失败!',0);
				}
		}else{
			$this->ajaxReturn('',$lnk->getError(),0);
			}		
	}
	function zipDownload(){
		ob_start();
		import('ORG.Util.Zip');
		$down = date('YmdHis',time()).'.zip';
		$zip  = new Zip();
		$zip->zip('./',$down);
		
	}
}
