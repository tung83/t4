<?php
common::load('base','page');
class payment extends base{
    function __construct($db,$id=6){
        parent::__construct($db,6);
    }
    function ind_payment(){
        $this->db->where('active',1);
        $this->db->orderBy('id','ASC');
        $item=$this->db->getOne('payment');
        $lnk=myWeb.$this->view.'/'.common::slug($item['title']).'-i'.$item['id'];
        $str='
        <div class="container about clearfix">
            <div class="title-tag">Hướng dẫn thanh toán</div>
            <div class="left">
                '.nl2br($item['sum']).'
                <a href="'.$lnk.'">'.more.'</a>
            </div>
            <div class="right">
                <div class="moz-reflect">
                    <img src="'.webPath.$item['img'].'" />
                    <img src="'.webPath.$item['img'].'" />
                </div>
            </div>                
        </div>';
        return $str;
    }
    function breadcrumb(){
        $this->db->reset();
        $str.='
        <ul class="breadcrumb clearfix">
        	<li><a href="'.myWeb.'"><i class="fa fa-home"></i></a></li>
            <li><a href="'.myWeb.$this->view.'">Hướng dẫn thanh toán</a></li>';
        if(isset($_GET['id'])){
            $this->db->where('id',intval($_GET['id']));
            $item=$this->db->getOne('payment','id,title');
            $str.='
            <li><a href="#">'.$item['title'].'</a></li>';
        }
        $str.='
        </ul>';
        return $str;
    }
    function category($id){
        $this->db->reset();
        $this->db->where('active',1)->orderBy('id');
        $list=$this->db->get('payment',null,'id,title');
        $str='
        <div class="row">
        <div class="col-md-12 about-cate">';
        foreach($list as $item){
            if($item['id']==$id) $cls=' class="active"';
            else $cls='';
            $lnk=myWeb.$this->view.'/'.common::slug($item['title']).'-i'.$item['id'];
            $str.='
            <a href="'.$lnk.'"'.$cls.'>
                <i class="fa fa-caret-right"></i>
                <span>'.$item['title'].'</span>
            </a>';
        }
        $str.='
        </div>
        </div>';
        return $str;
    }
    function payment_all(){
        $page=isset($_GET['page'])?intval($_GET['page']):1;
        $this->db->where('active',1);
        $this->db->orderBy('id');
        $this->db->pageLimit=10;
        $list=$this->db->paginate('payment',$page);
        $count=$this->db->totalCount;
        foreach($list as $item){
            $str.=$this->payment_item($item);
        }
        return $str;
    }
    function payment_item($item){
        $lnk=myWeb.$this->view.'/'.common::slug($item['title']).'-i'.$item['id'];
        $str.='
        <div class="row">
        <a href="'.$lnk.'" class="about-item clearfix">
            <div class="col-xs-4">
                <img src="'.webPath.$item['img'].'" class="img-responsive" alt="" title=""/>
            </div>                
            <div class="col-xs-8">
                <h2>'.$item['title'].'</h2>
                <span>'.nl2br(common::str_cut($item['sum'],620)).'</span>
            </div>
        </a>
        </div>';
        return $str;
    }
    
    function payment_one(){
        $id=intval($_GET['id']);
        $item=$this->db->where('id',$id)->getOne('payment');
        $this->db->where('active',1)->where('id',$item['id'],'<>');
        $this->db->orderBy('id');
        $list=$this->db->get('payment',3,'id,title,sum,img');
        $lnk=myWeb.$this->view.'/'.common::slug($item['title']).'-i'.$item['id'];
        $str.='        
        <article>
            <h1>'.$item['title'].'</h1>
            <p>'.$item['content'].'</p>
        </article>
        <div class="hr-custom"></div>
        <h2 class="other">Bài Viết Liên Quan</h2>';
        foreach($list as $item){
            $str.=$this->about_item($item);
        }
        return $str;
    }
}


?>
