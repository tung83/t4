<?php
common::load('base','page');
class about{
    private $db;
    private $lang;
    private $view,$title;
    function __construct($db,$lang='vi'){
        $this->db=$db;
        $this->db->reset();
        $this->lang=$lang;
        $db->where('id',2);
        $item=$db->getOne('menu');
        if($lang=='en'){
            $this->view=$item['e_view'];
            $this->title=$item['e_title'];
        }else{
            $this->view=$item['view'];
            $this->title=$item['title'];
        }
    }
    function ind_about(){
        $this->db->where('active',1);
        $this->db->orderBy('id','ASC');
        $item=$this->db->getOne('about');
        $lnk=myWeb.$this->lang.'/'.$this->view;
        $title=$this->lang=='vi'?$item['title']:$item['e_title'];
        $sum=$this->lang=='vi'?$item['sum']:$item['e_sum'];
        $str='
        <div class="container-fluid">
        <div class="row ind-about">
            <div class="col-md-6 col-middle2-container">
                <div class="col-middle2">
                    <h2 class="title text-center">'.$title.'</h2>
                    <p class="text-right">'.common::str_cut($sum,400).'</p>
                    <p class="text-right more">
                        <a href="'.myWeb.$this->lang.'/'.$this->view.'" class="btn btn-default btn-ind-about">'.more.'</a>
                    </p>
                </div>
            </div>
            <div class="col-md-6 hidden-xs hidden-sm image-show">
                <img src="'.webPath.$item['img'].'" class="img-responsive" />
            </div>
        </div>
        </div>';
        return $str;
    }
    function breadcrumb(){
        $this->db->reset();
        $str.='
        <div class="container">
        <ul class="breadcrumb clearfix">
        	<li><a href="'.myWeb.$this->lang.'"><i class="fa fa-home"></i></a></li>
            <li><a href="'.myWeb.$this->lang.$this->view.'">'.$this->title.'</a></li>';
        if(isset($_GET['id'])){
            $this->db->where('id',intval($_GET['id']));
            $item=$this->db->getOne('about','id,title');
            $str.='
            <li><a href="#">'.$item['title'].'</a></li>';
        }
        $str.='
        </ul>
        </div>';
        return $str;
    }
    
    function about_all(){
        $page=isset($_GET['page'])?intval($_GET['page']):1;
        $this->db->where('active',1);
        $this->db->orderBy('id');
        $this->db->pageLimit=10;
        $list=$this->db->paginate('about',$page);
        $count=$this->db->totalCount;
        foreach($list as $item){
            $str.=$this->about_item($item);
        }
        
        $pg=new Pagination(array('limit'=>limit,'count'=>$count,'page'=>$page,'type'=>0));
        $pg->set_url(array('def'=>myWeb.$this->lang.'/'.$this->view,'url'=>myWeb.$this->lang.'/'.'[p]/'.$this->view));

        $str.= '<div class="pagination-centered">'.$pg->process().'</div>';
        return $str;
    }
    function about_item($item){
        $lnk=myWeb.$this->lang.'/'.$this->view.'/'.common::slug($item['title']).'-i'.$item['id'];
        $str.='
        <a href="'.$lnk.'" class="about-item clearfix">
            <img src="'.webPath.$item['img'].'" class="img-responsive" alt="" title=""/>
            <div>
                <h2>'.$item['title'].'</h2>
                <span>'.nl2br(common::str_cut($item['sum'],620)).'</span>
            </div>
        </a>';
        return $str;
    }
    
    function about_one(){
        $id=1;
        $item=$this->db->where('id',$id)->getOne('about');
        $title=$this->lang=='vi'?$item['title']:$item['e_title'];
        $content=$this->lang=='vi'?$item['content']:$item['e_content'];
        $str.='  
        <section id="about-us">
        <div class="container">
			<div class="wow fadeInDown row">
            <div class="col-md-12">
                <article>
                    <div class="text-center">
                        <h2 class="page-title">'.$title.'</h2>
                    </div>
                    <p>'.$content.'</p>
                </article>
			</div>
            </div>
        </div>
        </section>';
        return $str;
    }
}


?>
