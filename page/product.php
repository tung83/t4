<?php
class product{
    private $db,$view,$lang,$title;
    function __construct($db,$lang='vi'){
        $this->db=$db;
        $this->db->reset();
        $this->lang=$lang;
        $db->where('id',5);
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
            $item=$this->db->getOne('product','id,title,pId');
            $cate=$this->db->where('id',$item['pId'])->getOne('product_cate','id,title,pId');
            $str.='
            <li>
                <a href="'.myWeb.$this->lang.'/'.$this->view.'/'.common::slug($cate['title']).'-p'.$cate['id'].'">
                '.$cate['title'].'
                </a>
            </li>';
            $str.='
            <li><a href="#">'.$item['title'].'</a></li>';
        }elseif(isset($_GET['pId'])){
            $cate=$this->db->where('id',intval($_GET['pId']))->getOne('product_cate','id,title,pId');
            $str.='           
            <li><a href="#">'.$cate['title'].'</a></li>';
        }
        $str.='
        </ul>
        </div>';
        return $str;
    }
    function product_sale(){
        $str.='
        <section class="product-sale">
            <div class="container-fluid">';
        $list=$this->db->where('active',1)->where('price_reduce',0,'<>')->orderBy('ind','ASC')->orderBy('id')->get('product',6);
        $i=1;
        foreach($list as $item){
            if($i%2==1){
                $str.='
                <div class="row">';
            }
            $title=$this->lang=='en'?$item['e_title']:$item['title'];
            $product_view=$this->lang=='en'?'product':'san-pham';
            $feature=$this->lang=='en'?$item['e_feature']:$item['feature'];
            $percent=100-round(($item['price_reduce']*100)/($item['price']==0?1:$item['price']),0);
            $lnk=myWeb.$this->lang.'/'.$product_view.'/'.common::slug($item['title']).'-i'.$item['id'];
            $str.='                
            <div class="col-md-6 col-middle2-container product-sale-item">
                <div class="row col-middle2">
                    <div class="col-xs-8">
                        <a href="'.$lnk.'">
                            <h2>'.$title.'</2h>
                            <p>'.common::str_cut($feature,200).'</p>
                            <p><s>'.number_format($item['price'],0,',','.').'</s><sup>VNĐ</sup></p>
                            <p><b>'.number_format($item['price_reduce'],0,',','.').'</b><sup>VNĐ</sup></p>
                        </a>
                        <a href="#" class="btn btn-default btn-ind-about"><i class="fa fa-shopping-cart"></i> '.cart.'</a>
                    </div>
                    <div class="col-xs-4">
                        <a href="'.$lnk.'">
                            <img src="'.webPath.$this->first_image($item['id']).'" class="img-responsive"/>
                            <p class="percent">
                                <b>'.$percent.'%</b>
                                OFF
                            </p>
                        </a>
                    </div>
                </div>
            </div>';
            if($i%2==0){
                $str.='
                </div>';
            }
            $i++;
        }
        if($i%2!=1){
            $str.='
            </div>';
        }
        $str.='
            </div>
        </section>';
        return $str;
    }
    function ind_product(){ 
        $str.='
            
        <a name="product-list"> </a>
        <section id="ind-product">
            <div class="container-fluid">
            <h2 class="awesome-title">
                <span>
                    '.$this->title.'
                </span>
            </h2>';
        $list=$this->db->where('active',1)->where('price_reduce',0)->where('home',1)->orderBy('id')->orderBy('id');        
        $this->db->where('active',1)->orderBy('id');
        $this->db->pageLimit=5;
        $page=isset($_GET['page'])?intval($_GET['page']):1;
        $list=$this->db->paginate('product',$page);
        $count=$this->db->totalCount;
        $i=1;   
        foreach($list as $item){
            if($i%5==1){
                $str.='
                <div class="row">';
            }
            $title=$this->lang=='en'?$item['e_title']:$item['title'];
            $lnk=myWeb.$this->lang.'/'.$this->view.'/'.common::slug($item['title']).'-i'.$item['id'];
            $img='thumb_'.$this->first_image($item['id']);
            $str.='
            <div class="col-md-3 col-item5 product-item">
                <a href="'.$lnk.'">
                    <img src="'.webPath.$img.'" class="img-responsive center-block"/>
                    <h2 class="text-center">'.$title.'</2h>
                    <p><b>'.number_format($item['price'],0,',','.').'</b><sup>VNĐ</sup></p>
                    <a href="#" class="btn btn-default btn-product"><i class="fa fa-shopping-cart"></i> '.cart.'</a>
                </a>
            </div>';
            if($i%5==0){
                $str.='
                </div>';
            }
            $i++;
        }
        if($i%5!=1){
            $str.='
            </div>';
        }
        
        $pg = new Pagination();    
        $pg->pagenumber = $page;
        $pg->pagesize = 5;
        $pg->totalrecords = $count;
        $pg->showfirst = true;
        $pg->showlast = true;
        $pg->paginationcss = "pagination-large";
        $pg->paginationstyle = 1; // 1: advance, 0: normal
        if(!$pId || $pId==0){
            $pg->defaultUrl = myWeb.$this->lang.'/';
            $pg->paginationUrl = myWeb.$this->lang.'/'."[p]#product-list";            
        }else{
            $cate=$this->db->where('id',$pId)->getOne('product_cate','id, title');
            
            $pg->defaultUrl = myWeb.$this->lang.'/'.$this->view.'/'.common::slug($cate['title']).'-p'.$cate['id'];
            $pg->paginationUrl = $pg->defaultUrl ."/[p]#product-list";
        }     
        $str.= '<div class="row">
                    <div class= "col-md-12 text-center">
                      <div class="pagination pagination-centered">'
                        .$pg->process()
                    .'</div>
                    </div>
                </div>'; 
        $str.=' 
            </div><!--/.container-->
        </section><!--/#partner-->';
        return $str;        
        
        
    }
    function hot_product(){
        $this->db->reset();
        $this->db->where('active',1)->where('home',1);
        $list=$this->db->get('product',null);
        $i=1;
        foreach($list as $item){
            if($i%4==1){
                $str.='
                <div class="row">';
            }
            $str.=$this->product_item($item);
            if($i%4==0){
                $str.='
                </div>';
            }
            $i++;
        }   
        if($i%4!=1){
            $str.='
            </div>';
        }
        return $str;
    }
    function product_item($item){
        $lnk=myWeb.$this->lang.'/'.$this->view.'/'.common::slug($item['title']).'-i'.$item['id'];
        $img=$this->first_image($item['id']);
        $str.='
        <div class="col-md-3 col-item5 wow fadeInLeft product-item text-center" data-wow-duration="2s">
        <a href="'.$lnk.'">
			<figure>
				<img src="'.webPath.'thumb_'.$img.'" alt="'.$item['title'].'" title="'.$item['title'].'" class="img-responsive center-block">
				<figcaption class="text-center">
					<h3>'.common::str_cut($item['title'],30).'</h3>
					<!--span><b>Giá bán:</b> <em>'.number_format($item['price'],0,'.','.').'VNĐ</em></span-->
				</figcaption>
			</figure>
        </a>
		</div>';
        return $str;
    }
    function product_list_item($item,$type=1){
        $lnk=myWeb.$this->lang.'/'.$this->view.'/'.common::slug($item['title']).'-i'.$item['id'];
        $img=$this->first_image($item['id']);
        if(trim($img)==='') $img='holder.js/400x300';else $img=webPath.$img;
        if($type==1){
            $str='
            <div class="col-md-12 col-sm-6 col-md-3 product-item">';    
        }else{
            $str='
            <div class="col-md-12 col-sm-6 col-md-4 product-item">';
        }        
        $str.='
        <a href="'.$lnk.'">
            <div>
                <p>'.($item['price']==0?contact:number_format($item['price'],0,',','.').' VNĐ').'</p>
                <img src="'.$img.'" class="img-responsive" />
                <p>
                    <h2>'.$item['title'].'</h2>
                    <button class="btn btn-default">'.more.'</button>
                </p>
            </div>
        </a>
        </div>';
        return $str;
    }
    function check_pId(){
        if(isset($_GET['pId'])){
            $pId=intval($_GET['pId']);
        }elseif(isset($_GET['id'])){
            $item=$this->db->where('id',intval($_GET['id']))->getOne('product','pId');
            $pId=$item['pId'];
        }else $pId=0;
        return $pId;
    }
    function category(){
        $pId=$this->check_pId();
        $list=$this->db->where('active',1)->orderBy('ind','ASC')->get('product_cate',null,'id,title');
        $str='
        <div class="row product-category">';
        foreach($list as $item){
            if($item['id']==$pId){
                $active=' class="active"';
            }else{
                $active='';
            }
            $str.='
            <a href="'.myWeb.$this->lang.'/'.$this->view.'/'.common::slug($item['title']).'-p'.$item['id'].'"'.$active.'>
                '.$item['title'].'
            </a>';
        }
        $str.='</div> </div>';
        return $str;
    }
    function product_cate(){
        $pId=$this->check_pId();
        $this->db->reset();
        if($pId>0){
            $lev=$this->db->where('id',$pId)->getOne('product_cate','lev');
            $this->db->where('pId',$pId);
        }
        $this->db->where('active',1)->orderBy('id');
        $this->db->pageLimit=pd_lim;
        $page=isset($_GET['page'])?intval($_GET['page']):1;
        $list=$this->db->paginate('product',$page);
        $count=$this->db->totalCount;
        $i=1;
        foreach($list as $item){
            if($i%5==1){
                $str.='
                <div class="row">';
            }
            $str.=$this->product_item($item);
            if($i%5==0){
                $str.='
                </div>';
            }
            $i++;
        }  
        if($i%5!=1){
           $str.='
           </div>'; 
        }  
        /*$pagenumber = $page;
        $totalrecords = $count;
        $pg=new Pagination(array('limit'=>1,'count'=>20,'page'=>$page,'type'=>0));
        $pg->set_url(array('def'=>'index.php','url'=>'index.php?page=[p]'));
        $str.=$pg->process();*/
        return $str; 
    }
    function product_list($pId,$type=1){
        $page=isset($_GET['page'])?intval($_GET['page']):1;
        $this->db->reset();
        if($pId!=0) $this->db->where('pId',$pId);
        $this->db->where('active',1)->orderBy('id');
        $this->db->pageLimit=limit;
        $list=$this->db->paginate('product',$page,'id,title,price,price_reduce');
        $str='
        <div class="row">';
        foreach($list as $item){
            $str.=$this->product_list_item($item,$type);
        }
        $str.='
        </div>';
        return $str;
    }
    function product_one($id){
        $this->db->where('id',$id);
        $item=$this->db->getOne('product','id,price,price_reduce,title,content,pId,feature,manual,promotion,video');
        $this->db->where('pId',$item['pId'])->where('id',$item['id'],'<>')->where('active',1)->orderBy('rand()');
        $list=$this->db->get('product',5);
        $lnk=domain.'/'.$this->view.'/'.common::slug($item['title']).'-i'.$item['id'];
        $str.='
        <div class="row product-detail clearfix">
            <div class="col-md-3">
                '.$this->product_image_show($item['id']).'
            </div>
            <div class="col-md-9">
                <article class="product-one">
                <h1>'.$item['title'].'</h1>
                <!--b>Giá Bán Lẻ: <em>'.number_format($item['price'],0,',','.').'VNĐ</em></b-->
                <!--form action="javascript:add_cart('.$item['id'].',1)">
                    <button class="btn btn-default"><i class="fa fa-shopping-cart"></i> Mua Hàng</button>
                </form-->
                <p>'.$item['feature'].'</p>
                </article>
                
                <div id="tabs" class="tabs">
                    <ul>
                        <li><a href="#tabs-1">MÔ TẢ CHI TIẾT</a></li>
                        <li><a href="#tabs-2">BÌNH LUẬN</a></li>
                        <!--li><a href="#tabs-3">GHI CHÚ</a></li>
                        <li><a href="#tabs-4">BÌNH LUẬN</a></li-->
                    </ul>
                    <div id="tabs-1">
                        <article>
                            <p>'.$item['content'].'</p>
                        </article>
                    </div>
                    <div id="tabs-2">
                        <article>
                            <p>'.$item['detail'].'</p>
                        </article>
                    </div>
                    <!--div id="tabs-3">
                        <article>
                            <p>'.$item['manual'].'</p>
                        </article>
                    </div>
                    <div id="tabs-4">
                        <div class="fb-comments" data-width="100%" data-href="'.$lnk.'" data-numposts="5"></div>
                    </div-->
                </div>   
            </div>
        </div>';
        if(count($list)>0){
            $str.='
            <div class="wow fadeInDown row">
                <h2 class="title">
                        SẢN PHẨM CÙNG LOẠI
                </h1>
            </div>';
           $i=1;
            foreach($list as $item){
                if($i%5==1){
                    $str.='
                    <div class="row">';
                }
                $str.=$this->product_item($item);
                if($i%5==0){
                    $str.='
                    </div>';
                }
                $i++;
            }  
            if($i%5!=1){
               $str.='
               </div>'; 
            }     
        }        
        return $str;
    }
    function product_image_show($id){
        $this->db->reset();
        $list=$this->db->where('active',1)->where('pId',$id)->orderBy('ind','ASC')->orderBy('id')->get('product_image');
        $temp=$tmp='';
        foreach($list as $item){
            $temp.='
            <li>
                <a href="'.webPath.$item['img'].'" >
                    <img src="'.webPath.$item['img'].'" alt="" title="" class=""/>
                </a>
            </li>';
            $tmp.='
            <li>
                <img src="'.webPath.'thumb_'.$item['img'].'" alt="" title=""/>
            </li>';
        }
        $str.='
        <!-- Place somewhere in the <body> of your page -->
        <div id="image-slider" class="flexslider">
          <ul class="slides popup-gallery">
            '.$temp.'
          </ul>
        </div>
        <div id="carousel" class="flexslider" style="margin-top:-50px;margin-bottom:10px">
          <ul class="slides">
            '.$tmp.'
          </ul>
        </div>
        <script>
        $(window).load(function() {
          // The slider being synced must be initialized first
          $("#carousel").flexslider({
            animation: "slide",
            controlNav: false,
            animationLoop: false,
            slideshow: false,
            itemWidth: 80,
            itemMargin: 5,
            asNavFor: "#image-slider"
          });
         
          $("#image-slider").flexslider({
            animation: "slide",
            controlNav: false,
            animationLoop: false,
            slideshow: false,
            sync: "#carousel"
          });
        });
        </script>';
        return $str;
    }
    function first_image($id){
        $this->db->reset();
        $this->db->where('active',1)->where('pId',$id)->orderBy('ind','ASC')->orderBy('id');
        $img=$this->db->getOne('product_image','img');
        return $img['img'];
    }
}
?>