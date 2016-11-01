<?php include_once 'function.php';?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="description" content=""/>
    <meta name="author" content=""/>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no"/>
    <title>.:T4:.</title>
	<link rel="icon" type="image/png" href="<?=selfPath?>logo.png"/>   
    <?=common::basic_css()?> 
    <?=common::basic_js()?>
</head>
<body>
    <?=menu($db,$lang,$view)?>
    <?php
    switch($view){
        case 'product':
        case 'san-pham':
            echo product($db,$lang);
            break;
        case 'project':
        case 'du-an':
            echo project($db,$lang);
            break;
        case 'recruitment':
        case 'tuyen-dung':
            echo career($db,$lang);
            break;
        case 'news-event':
        case 'tin-tuc-su-kien':
            echo news($db,$lang);
            break;
        case 'about-us':
        case 'gioi-thieu':
            echo about($db,$lang);
            break;
        case 'lien-he':
        case 'contact':
            echo contact($db,$lang);
            break;
        case 'search':
        case 'tim-kiem':
            echo search($db,$lang);
            break;
        case 'gio-hang':
        case 'cart':
            echo cart($db,$view);
            break;
        default:
            echo home($db,$lang);
            break;
    }
    ?>
    <section class="gmap">
        <?=$basic_config['gmap_script']?>
    </section>
    <footer>
        <div class="container">
            <div class="row footer">
                <div class="col-md-2">
                    <?=foot_menu($db,$lang,$view)?>
                </div>
                <div class="col-md-6">
                    <?=common::qtext($db,$lang,4)?>
                </div>
                <div class="col-md-1">
                    <ul class="soc">
                        <li><a class="soc-facebook" href="#"></a></li>
                        <li><a class="soc-twitter" href="#"></a></li>
                        <li><a class="soc-googleplus" href="#"></a></li>
                        <li><a class="soc-rss soc-icon-last" href="#"></a></li>
                    </ul>  
                </div>
                <div class="col-md-3">
                    <div class="fb-page" 
                      data-href="https://www.facebook.com/t4vietnam/?fref=ts"
                      data-width="380" 
                      data-hide-cover="false"
                      data-show-facepile="true" 
                      data-show-posts="false"></div>
                </div>
            </div>
            <div class="row">
                
            </div>
        </div>
        <section class="copyright">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        Copyright © 2016 <b>True Precison.</b>. All Rights Reserved. Designed by <a>PSmedia.vn</a>
                    </div>
                </div>
            </div>
        </section>
    </footer>
    <div class="float-icon">
        <a href="<?=common::cart_link($lang)?>"><i class="fa fa-shopping-cart"></i><span id="cart-count" class="user-cart-quantity"><?=cart_count($db)?></span></a>
        <a href="#"><i class="fa fa-search"></i></a>
        <a href="tel:<?=common::remove_format_text(common::qtext($db,$lang,2))?>"><i class="fa fa-phone"></i></a>
    </div>
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/vi_VN/sdk.js#xfbml=1&version=v2.7&appId=1526299550957309";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
</body>
</html>