<?php
$css=array(
    'font-awesome',
    'animate',    
    'bootstrap.min',
    'jquery-ui',
    'magnific-popup',
    'jAlert-v3',
    'layerslider',
    'slick',
    'slick-theme',
    'flexslider',
    'responsive',
    'default',
    'component',
    'ribbon',
    'nice-tabs',
    'navbar-custom',
    'socicon',
    'self'
);
$js=array(
    'jquery',
    'jquery-ui',
    'jquery-migrate',
    'bootstrap.min',
    'validator.min',
    'jquery.magnific-popup',
    'jAlert-v3',
    'jAlert-functions',
    'jquery.isotope.min',
    'wow',
    'slick.min',
    'jquery.flexslider',
    'modernizr.custom.new',
    'toucheffects',
    'self',
    'functions'
);
include_once 'config.php';
include_once 'lang/'.$lang.'.php';
define('limit',10);
define('pd_lim',12);

$basic_config=$db->getOne('basic_config');
?>