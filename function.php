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
    $str.=gmap();
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
    $str.=gmap();
    return $str;
}
function career($db,$lang){
    $str.='
    <section id="carrer-page">';
    common::page('career');
    $career=new career($db,$lang);
    $str.=$career->breadcrumb();
    $str.=$career->career_unique(); 
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
    if(isset($_GET['id'])){
        $str.=$news->news_one(intval($_GET['id']));    
    }else{
        $str.=$news->news_cate();
    }      
    $str.='
    </section>';
    return $str;
}

function promotion($db, $lang)
{
    common::load('promotion','page');
    $obj = new promotion($db, $lang);
    
    $str.='
    <div class="container promotion-page">
        <div class="row">'.$obj->breadcrumb().'</div>
        <div class="wow fadeInDown row">';
            
    if (isset($_GET['id'])) {
        $str .= $obj->promotion_one($db, intval($_GET['id']));
    } else {
        $pId=isset($_GET['pId'])?intval($_GET['pId']):0;
        $str .= $obj->promotion_cate($pId);
    }            
    $str.='
        </div>
    </div>';
    return $str;
}

function cart($db, $lang, $view)
{
    common::load('cart_show','page');
    $cart = new cart_show($db, $view, $lang);
    
    $str.='
    <div class="container cart-list">
        <div class="row">';
        switch($act=$_GET['act']){
            case 'thanh-toan':
            case 'payment':
                $str.=$cart->cart_checkout();
                break;
            default:
                $str.=$cart->cart_output($db);
                break;
    }
    $str.='           
        </div>
    </div>';
    return $str;
}

function payment($db, $lang)
{
    common::load('payment','page');
    $obj = new payment($db, $lang);
    
    $str.='
    <div class="container all-i-know">
        <div class="row">'.$obj->breadcrumb().'</div>
        <div class="row">';
            
    if (!isset($_GET['id'])) {
        $str .= $obj->payment_all();
    } else {
        $str .= $obj->payment_one();
    }
            
    $str.='
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
function cart_update_multi($db){
    common::load('cart');
    $obj=new cart($db);        
             
    if ( isset( $_POST['productItems'] ) )  {   
        foreach ( $_POST['productItems'] as $item )
        { 
            $obj->cart_update($item['id'],$item['qty']);
        }
        return true;
    }
    return false;
}

function gmap(){      
    return '<div id="google-map"></div>
        <script>   
            function initMap() {
                var lequangdinh = {lat: 10.798732, lng: 106.687669};
                var lequangdinhCenter = {lat: 10.798799, lng: 106.687669};
                var map = new google.maps.Map(document.getElementById("google-map"), {
                  zoom: 20,
                  mapTypeControl: false,
                  fullscreenControl: true,
                  streetViewControl: false,
                  center: lequangdinhCenter
                });
                var marker = new google.maps.Marker({
                  position: lequangdinh,
                  map: map,
                  title: "336 PHAN XÍCH LONG, PHƯỜNG 7, QUẬN PHÚ NHUẬN"
                });
                var lequangdinhContentString = 
                      "<h4 style=\"color: #2aa498\">T4 Vietnam - Tea</h4>" +
                      "<p>336 PHAN XÍCH LONG, PHƯỜNG 7, QUẬN PHÚ NHUẬN</p>" +
                      "<a  target=\"_blank\" href=\"https://www.google.com/maps/place/10%C2%B047\'55.4%22N+106%C2%B041\'15.6%22E/@10.7987615,106.6874546,19.5z/data=!4m5!3m4!1s0x0:0x0!8m2!3d10.798732!4d106.687669\">Get direction</a>";

                  var infowindow = new google.maps.InfoWindow({
                    content: lequangdinhContentString
                  });
                  infowindow.open(map, marker);
              }
        </script>
        <script async defer
          src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAVWAnZRS56JnP5Nr5otnuzg47TsmJoKBM&callback=initMap">
        </script>';
    
    return $str;
}
?>
