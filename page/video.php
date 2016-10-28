<?php
class video{
    private $db;
    private $lang;
    private $view;
    function __construct($db,$lang='vi'){
        $this->db=$db;
        $this->db->where('id',6);
        $item=$this->db->getOne('menu');
        if($lang=='en'){
            $this->view=$item['e_view'];
        }else{
            $this->view=$item['view'];
        }
        $this->lang=$lang;
        //$this->db=$db;
    }
    function breadcrumb(){
        $this->db->reset();
        $str.='
        <ul class="breadcrumb clearfix">
        	<li><a href="'.myWeb.$this->lang.'/"><i class="fa fa-home"></i></a></li>
            <li><a href="'.myWeb.$this->lang.'/'.$this->view.'">Video Clips</a></li>';
        if(isset($_GET['id'])){
            $this->db->where('id',intval($_GET['id']));
            $item=$this->db->getOne('video','id,title');
            $str.='
            <li><a href="#">'.$item['title'].'</a></li>';
        }
        $str.='
        </ul>';
        return $str;
    }
    function video_cate($db,$id=0){
        $str='
        <div class="color paint-drop-blue bg-right">
        <div class="container">
        '.$this->breadcrumb().'
        <h1 class="ind-title">Video Clips</h1>';
        if($id!=0) $db->where('id',$id);
        $db->where('active',1)->orderBy('id');
        $item=$db->getOne('video','id,video');
        $str.='
        <div class="video-show">';
        $str.='
        <iframe width="100%" height="500" src="https://www.youtube.com/embed/'.$item['video'].'" frameborder="0" allowfullscreen></iframe>';
        $str.='
        </div>
        </div>
        </div>';
        $page=isset($_GET['page'])?intval($_GET['page']):1;
        if($pId!=0) $db->where('pId',$pId);
        $db->where('id',$item['id'],'<>')->orderBy('id');
        $db->pageLimit=15;
        $list=$db->paginate('video',$page);
        $str.='
        <div class="color access-bg">
            <div class="container paint-drop-grey">
                <p class="subtitle fancy"><span>Video CLips Khác</span></p>
                <ul class="project-list clearfix">';
        foreach($list as $item){
            $str.='<li>'.$this->one_ind_video($item).'</li>';
        }
        $str.='
                </ul>
            </div>
        </div>';
        return $str;  
    }
    function one_ind_video($item){
        $lnk=myWeb.$this->lang.'/'.$this->view.'/'.common::slug($item['title']).'-i'.$item['id'];
        $str='
        <div class="ind-project">
            <a href="'.$lnk.'">
                <img src="http://img.youtube.com/vi/'.$item['video'].'/0.jpg" alt="" title="'.$item['title'].'"/>
                <h2>'.$item['title'].'</h2>
            </a>
        </div>';
        return $str;
    }
    function video_one($db,$id=0){
        $db->where('active',1)->where('id',$id)->orderBy('id');
        $item=$db->getOne('video','id,video,pId');
        $str='
        <div class="player_content">
        <div class="container">
            <div class="col-md-3">
                '.$this->categories($db,$item['pId']).'
            </div>';
        $str.='
            <div class="col-md-9">';
        $str.='
        <iframe width="100%" height="500" src="https://www.youtube.com/embed/'.$item['video'].'" frameborder="0" allowfullscreen></iframe>';
        $str.='
            </div>
        </div>
        </div>';
        $page=isset($_GET['page'])?intval($_GET['page']):1;
        $db->where('pId',$item['pId']);
        $db->where('id',$item['id'],'<>')->orderBy('id');
        $db->pageLimit=15;
        $list=$db->paginate('video',$page);
        $str.='
        <div class="white listvideo" style="background:#fff">
            <div class="container">';
        foreach($list as $item){
            if($this->lang=='en'){
                $title=$item['e_title'];
            }else{
                $title=$item['title'];
            }
            $lnk=myWeb.$this->lang.'/'.$this->view.'/'.common::slug($title).'-i'.$item['id'].'.html';
            $str.='
            <div class="col-sm-3 list_row">
                <a href="'.$lnk.'" title="Video">
                    <img src="http://img.youtube.com/vi/'.$item['video'].'/2.jpg" class="img-responsive img-video" />
                    <span class="bk_player"></span>
                </a>
                <a href="'.$lnk.'" title="Video" class="titleVideo">'.$title.'</a>
            </div>';  
        }
        $str.='
            </div>
        </div>';
        return $str;  
    }
    function ind_video($db){
        $db->where('active',1)->orderBy('id');
        $item=$db->getOne('video','video,id');
        $str='
        <div class="col-md-4">
            <h3>'.video.'</h3>
            <hr class="hr_title" />
            <div>
                <iframe width="100%" height="250" src="https://www.youtube.com/embed/'.$item['video'].'" frameborder="0" allowfullscreen></iframe>
            </div>
            <div>
                <ul class="listNews">';
        $db->where('active',1)->where('id',$item['id'],'<>')->orderBy('id');
        $list=$db->get('video',null,'id,title,e_title');
        foreach($list as $item){
            if($this->lang=='en'){
                $title=$item['e_title'];
            }else{
                $title=$item['title'];
            }
            $lnk=myWeb.$this->lang.'/'.$this->view.'/'.common::slug($title).'-i'.$item['id'].'.html';
            $str.='
            <li><a href="'.$lnk.'">'.$title.'</a></li>';
        }
        $str.='
                </ul>
            </div>
        </div>';
        return $str;
    }
}


?>