<?php
include_once 'front.php';
function lang_flag($lang){
    if ($lang == 'vi') {
        $flag = 'United_Kingdom.gif';
        $flag_lnk = common::language_change($_SERVER['REQUEST_URI'],'en');
    } else {
        $flag = 'Vietnam.gif';
        $flag_lnk = common::language_change($_SERVER['REQUEST_URI'],'vi');
    }
    return '
    <a href="' . $flag_lnk . '" class="pull-right">
        <img src="' . selfPath . $flag . '" class="img-responsive" style="max-height:20px" title="" alt=""/>
    </a>';
}
function menu($db,$lang,$view){
    $db->reset();
    $list=$db->where('active',1)->orderBy('ind','ASC')->orderBy('id')->get('menu');
    $str.='
    <header>
    <div class="container">
    <div class="row">
        <nav class="navbar navbar-default">
          <div class="container-fluid">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
              <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
              </button>
              <a class="navbar-brand" href="'.myWeb.$lang.'">
                <img src="'.selfPath.'logo.png" class="img-responsive logo"/>
              </a>
            </div>
        
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
              <ul class="nav navbar-nav">';
    foreach($list as $item){
        $vw=($lang=='en')?$item['e_view']:$item['view'];
        $title=($lang=='en')?$item['e_title']:$item['title'];
        $lnk=myWeb. $lang . '/' .common::slug($vw);
        $active=($view==$vw)?' class="active"':'';        
        $str.='
        <li'.$active.'><a href="'.$lnk.'">'.$title.'</a></li>';
    }
    $str.='
              </ul>
              <ul class="nav navbar-nav navbar-right">
                <li>'.lang_flag($lang).'</li>
            </div><!-- /.navbar-collapse -->
          </div><!-- /.container-fluid -->
        </nav>
    </div>
    </div>
    </header>'  ;
    return $str;
}
function foot_menu($db,$lang,$view){
    $db->reset();
    $list=$db->where('active',1)->orderBy('ind','ASC')->orderBy('id')->get('menu');
    $str.='
    <ul class="foot-menu">';
    foreach($list as $item){
        $title=$lang=='vi'?$item['title']:$item['e_title'];
        $db_view=$lang=='vi'?$item['view']:$item['e_view'];
        $str.='
        <li><a href="'.myWeb.$lang.'/'.$db_view.'">'.$title.'</a></li>';   
    }
    $str.='
    </ul>';
    return $str;
}
function home($db,$lang){
    //common::widget('layer_slider');
    //$layer_slider=new layer_slider($db);
    
    $str='
    <section id="ind-slider">
        <div class="container-fluid">
            <div class="row">
            '.wow_slider($db).'
            </div>
        </div>
    </section>';
    
    common::page('about');
    $about=new about($db,$lang);
    $str.=$about->ind_about();
    
    common::page('product');
    $product=new product($db,$lang);
    $str.=$product->product_sale();
    
    $str.=$product->ind_product();
    
    common::page('news');
    $news=new news($db,$lang);
    $str.=$news->ind_news();
    
    return $str;
}
function wow_slider($db){
    $db->reset();
    $list=$db->where('active',1)->orderBy('ind','ASC')->get('slider');
    $str.='
    <link rel="stylesheet" type="text/css" href="'.myWeb.'engine/style.css" />
	<!-- Start WOWSlider.com BODY section --> <!-- add to the <body> of your page -->
	<div id="wowslider-container1">
	<div class="ws_images"><ul>';
    $i=1;
    foreach($list as $item){
        $img='<img src="'.webPath.$item['img'].'" alt="" title=""/>';
        $lnk=$item['lnk']!=''?'<a href="'.$item['lnk'].'">'.$img.'</a>':$img;
        $str.='
        <li>'.$lnk.'</li>';
        $tmp.='
        <a href="#" title=""><span>'.$i.'</span></a>';
        $i++;
    }
    $str.='
	</ul></div>
	<div class="ws_bullets"><div>
		'.$tmp.'
	</div></div><div class="ws_script" style="position:absolute;left:-99%"></div>
	<div class="ws_shadow"></div>
	</div>	
	<script type="text/javascript" src="'.myWeb.'engine/wowslider.js"></script>
	<script type="text/javascript" src="'.myWeb.'engine/script.js"></script>
	<!-- End WOWSlider.com BODY section -->';
    return $str;
}
function slide($db){
    $db->reset();
    $list=$db->where('active',1)->orderBy('ind','ASC')->get('slider');
    $str.='
    <!-- Start WOWSlider.com BODY section --> <!-- add to the <body> of your page -->
    <link rel="stylesheet" type="text/css" href="'.myWeb.'engine/style.css" />
	<div id="wowslider-container1">
	<div class="ws_images"><ul>';
    $i=1;
    $tmp='';
    foreach($list as $item){
        $img='<img src="'.webPath.$item['img'].'" alt="" title=""/>';
        if($item['lnk']!=''){
            $lnk='<a href="'.$item['lnk'].'">'.$img.'</a>';
        }else{
            $lnk=$img;
        }
        $str.='
        <li>'.$lnk.'</li>';
        $tmp.='
        <a href="#" title="images"><span>'.$img.$i.'</span></a>';
        $i++;
    }
    $str.='
	</ul></div>
	<div class="ws_bullets"><div>
		'.$tmp.'
	</div></div><div class="ws_script" style="position:absolute;left:-99%"></div>
	<div class="ws_shadow"></div>
	</div>	
	<script type="text/javascript" src="'.myWeb.'engine/wowslider.js"></script>
	<script type="text/javascript" src="'.myWeb.'engine/script.js"></script>
	<!-- End WOWSlider.com BODY section -->';
    return $str;
}
function contact($db,$lang){
    $str.='
    <section id="page">';
    common::page('contact');
    $contact=new contact($db,$lang);
    $str.=$contact->breadcrumb();
    $str.=$contact->contact();
    $str.='
    </section>';
    return $str;
}
function career($db,$lang){
    $str.='
    <section id="carrer-page">';
    common::page('career');
    $career=new career($db,$lang);
    $str.=$career->breadcrumb();
    if(isset($_GET['id'])){
        $str.=$career->career_one();    
    }else{
        $str.=$career->career_all();
    }    
    $str.='
    </section>';
    return $str;
}
function project($db,$lang){
    $str.='
    <section id="page">';
    common::page('project');
    $project=new project($db,$lang);
    $str.=$project->breadcrumb();
    if(isset($_GET['id'])){
        $str.=$project->project_one();    
    }else{
        $str.=$project->project_all();
    }    
    $str.='
    </section>';
    return $str;
}
function about($db,$lang){
    $str.='
    <section id="about-page">';
    common::page('about');
    $about=new about($db,$lang);
    $str.=$about->breadcrumb();
    $str.=$about->about_one();
    $str.='
    </section>';
    return $str;    
}
function news($db,$lang){
    $str.='
    <section id="news-page">';
    common::page('news');
    $news=new news($db,$lang);
    $str.=$news->breadcrumb();
    
    $str.='
    </section>';
    return $str;
}

function cart($db, $view)
{
    common::load('product','page');
    $pd=new product($db);
    common::load('cart_show','page');
    $cart = new cart_show($db);
    
    $str.='
    <div class="container cart-list">
        <div class="row">';
        switch($act=$_GET['act']){
            case 'thanh-toan':
            case 'payment':
                $str.=$cart->cart_checkout();
                break;
            default:
                $str.=$cart->cart_output();
                break;
    }
    $str.='           
        </div>
    </div>';
    return $str;
}

function payment($db, $view)
{
    common::load('product','page');
    $pd=new product($db);
    common::load('payment','page');
    $obj = new payment($db);
    
    $str.='
    <div class="container all-i-know">
        <div class="row">'.$obj->breadcrumb().'</div>
        <div class="row">
            <div class="col-md-3">
                '.$pd->category(0).'
            </div>
            <div class="col-md-9">';
            
    if (!isset($_GET['id'])) {
        $str .= $obj->payment_all();
    } else {
        $str .= $obj->payment_one();
    }
            
    $str.='
            </div>
        </div>
    </div>';
    return $str;
}
function manual($db){
    //common::widget('layer_slider');
    //$layer_slider=new layer_slider($db);
    
    $str='
    <section id="ind-slider">
        <div class="container">
            '.wow_slider($db).'
        </div>
    </section>';
    
    common::page('manual');
    $manual=new manual($db);
    //$str=$about->breadcrumb();
    $str.=$manual->manual_one();
    return $str;
}
function product($db,$lang){
    $str.='
    <section id="product-page">';  
    common::page('product');
    $pd=new product($db,$lang);
    $str.=$pd->breadcrumb();
    $str.='
    <div id="category-bar" class="container-fluid">
        <div class="row text-center">'
            .$pd->category()
        .'</div">
    </div>';
    $str.='
    <div class="container">';
    if(isset($_GET['id'])){
        $id=intval($_GET['id']);
        $str.=$pd->product_one($id);
    }else{
        $str.=$pd->product_cate();
    }
    $str.='</div>';
    $str.='
    </section>';
    return $str;
}
function partner($db){
    $list=$db->where('active',1)->orderBy('ind','ASC')->orderBy('id')->get('partner');    
    $str.='
    <section id="partner">
        <div class="container">
            <div class="center wow fadeInDown">
                <h2>Đại Diện Phân Phối</h2>
                <p class="lead">
                    Đại diện chính thức của công ty L&rsquo;avoine Việt Nam
                </p>
            </div>    
            <div class="partners col-md-12">
                <div class="your-class">';
    foreach($list as $item){
        $img=$item['img']==''?selfPath.'square-facebook.png':webPath.$item['img'];
        $str.='
        <div>
        <a href="'.$item['facebook'].'" target="_blank">
            <img src="'.$img.'" class="img-responsive center-block"/>
            <h2 class="text-center">'.$item['title'].'</h2>
        </a>
        </div>';
    }
    $str.='
                </div>
            </div>        
        </div><!--/.container-->
    </section><!--/#partner-->';
    $str.='   
    <script>
    $(".your-class").slick({
      slidesToShow: 5,
      slidesToScroll: 1,
      responsive: [
        {
          breakpoint: 1024,
          settings: {
            slidesToShow: 5,
            slidesToScroll: 3,
            infinite: true,
            dots: true
          }
        },
        {
          breakpoint: 600,
          settings: {
            slidesToShow: 2,
            slidesToScroll: 2
          }
        },
        {
          breakpoint: 480,
          settings: {
            slidesToShow: 1,
            slidesToScroll: 1
          }
        }
      ],
      autoplay: true,
      autoplaySpeed: 2000,
    });
    </script>';
    return $str;
}
function social($db){
    $basic_config=$db->where('id',1)->getOne('basic_config','social_twitter','social_facebook','social_google_plus');
    $str.='
    <div class="social">
        <a href="'.$basic_config['social_twitter'].'"><i class="fa fa-twitter"></i></a>
        <a href="'.$basic_config['social_facebook'].'"><i class="fa fa-facebook"></i></a>
        <a href="'.$basic_config['social_google_plus'].'"><i class="fa fa-google-plus"></i></a>
    </div>';
    return $str;
}
function search($db,$lang){
    $str.='
    <section id="product-page">';  
    common::page('product');
    $pd=new product($db,$lang);
    $str.=$pd->breadcrumb();
    $str.='
    <div id="category-bar" class="container-fluid">
        <div class="row text-center">'
            .$pd->category()
        .'</div">
    </div>';
    $str.='
    <div class="container">';
    $str.=$pd->product_search();
    $str.='</div>';
    $str.='
    </section>';
    return $str;
}

function cart_count($db){
    common::load('cart');
    $obj=new cart($db);
    return $obj->cart_count();
}
?>
