<?php
class career{
    private $db,$view,$lang,$title;
    function __construct($db,$lang='vi'){
        $this->db=$db;
        $this->db->reset();
        $this->lang=$lang;
        $db->where('id',11);
        $item=$db->getOne('menu');
        if($lang=='en'){
            $this->view=$item['e_view'];
            $this->title=$item['e_title'];
        }else{
            $this->view=$item['view'];
            $this->title=$item['title'];
        }
    }
    function breadcrumb(){
        $this->db->reset();
        $str.='
        <div class="container">
        <ul class="breadcrumb clearfix">
        	<li><a href="'.myWeb.$this->lang.'"><i class="fa fa-home"></i></a></li>
            <li><a href="'.myWeb.$this->lang.'/'.$this->view.'">'.$this->title.'</a></li>';
        if(isset($_GET['id'])){
            $this->db->where('id',intval($_GET['id']));
            $item=$this->db->getOne('career','id,title');
            $str.='
            <li><a href="#">'.$item['title'].'</a></li>';
        }
        $str.='
        </ul>
        <h2 class="page-title">'.$this->title.'</h2>
        </div>';
        return $str;
    }
    function ind_career(){
        $this->db->where('active',1)->orderBy('id');
        $list=$this->db->get('career',null,'id,title');
        $str='
        <div class="color" style="background: #ededed;">
            <div class="container career clearfix">
                <div class="title-tag">
                    '.$this->title.'
                </div>
                <div class="left">
                    <img src="'.selfPath.'career.png" alt="" title=""/>
                </div>
                <div class="right">';
        
        $str.='
                </div>
            </div>
        </div>';
        return $str;
    }
    function career_all(){
        $page=isset($_GET['page'])?intval($_GET['page']):1;
        $this->db->where('active',1);
        $this->db->orderBy('id');
        $this->db->pageLimit=limit;
        $list=$this->db->paginate('career',$page);
        $count=$this->db->totalCount;
        
        $str.='
        <div class="container">';
        foreach($list as $item){
            $str.=$this->career_item($item);
        }
        
        $pagenumber = $page;
        $totalrecords = $count;
        $pg=new Pagination(array('limit'=>limit,'count'=>$count,'page'=>$page,'type'=>0));
        $pg->set_url(
            array(
                'def'=>myWeb.$this->lang.'/'.$this->view,
                'url'=>myWeb.$this->lang.'/p[p]/'.$this->view
            )
        );
        $str.='<div class="text-center">'.$pg->process().'</div>';
        
        $str.='
        </div>';
        return $str;
    }
    function career_item($item){
        $lnk=myWeb.$this->lang.'/'.$this->view.'/'.common::slug($item['title']).'-i'.$item['id'];
        $str.='        
        <div class="row news-item">
        <a href="'.$lnk.'">
            <div class="col-xs-3">
                <img src="'.webPath.$item['img'].'" alt="" title="" class="img-responsive"/>
            </div>
            <div class="col-xs-9">
                <h2>'.$item['title'].'</h2>
                <span>'.nl2br(common::str_cut($item['sum'],620)).'</span>
            </div>
        </a>
        </div>';
        return $str;
    }
    
    function career_one(){
        $id=intval($_GET['id']);
        $item=$this->db->where('id',$id)->getOne('career');
        $str='
        <div class="container">
            <div class="row article">
                <article class="article">
                    <h1 class="article">'.$item['title'].'</h1>
                    <p>'.$item['content'].'</p>
                </article>
            </div>
            <h2 class="page-title">'.other_article.'</h2>';
        $this->db->where('active',1)->where('id',$id,'<>');
        $this->db->orderBy('id');
        $list=$this->db->get('career',limit);
        foreach($list as $item){
            $str.=$this->career_item($item);
        }
        $str.='
        </div>';
        return $str;
    }
}
?>
