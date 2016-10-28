<?php
/**
 * author: Le Chu An
 * css: layerslider
 * js: layerslider,layerslider.transitions,layerslider.greensock
 * self: 
 *   if(typeof $.fn.layerSlider == "undefined") { lsShowNotice("layerslider_1","jquery"); } 
 *   else { 
 *       $("#layerslider_1").layerSlider({
 *           responsive:false, 
 *           responsiveUnder: 940, 
 *           layersContainer: 940,
 *           pauseOnHover: false, 
 *           forceLoopNum: false, 
 *           autoPlayVideos: false, 
 *           skinsPath: "/file/self/",
 *           skin: "fullwidthdark"
 *       })
 *   } 
 **/
class layer_slider{
    function __construct($db,$lang='vi'){
        $this->db=$db;
        $this->db->reset();
        $this->lang=$lang;   
    }
    function get_list(){
        $this->db->where('active',1)->orderBy('ind','ASC')->orderBy('id');
        return $this->db->get('slider');
    }
    function output(){
        $list = $this->get_list();
        $str='
        <div id="layerslider_1" class="ls-wp-container" style="height:500px;margin:0 auto;padding:0">';
        foreach($list as $item){
            $str.='
            <div class="ls-slide" data-ls="slidedelay:10000;transition2d:11;">
            <img src="" data-src="'.webPath.$item['img'].'" class="ls-bg" alt="Slide background" />';
            if(trim($item['title'])!=''){
                $str.='
                <p class="ls-l title" style="top:100px;left:60px;font-size:20px;font-weight:bold" data-ls="offsetxin:0;durationin:2500;delayin:2000;rotateyin:90;transformoriginin:left 50% 0;offsetxout:0;rotateyout:-90;transformoriginout:left 50% 0;">
                '.$item['title'].'
                </p>';
            }
            if(trim($item['sum'])!=''){
                $str.='
                <p class="ls-l" style="max-width:800px;top:170px;left:60px;font-size:13px;line-height:28px;color:#fff;background:rgba(0,0,0,0.5);padding:10px" data-ls="offsetxin:0;durationin:2000;delayin:3500;">
                '.nl2br(common::str_cut($item['sum'],300)).'
                </p>';
            }
            if(trim($item['lnk'])!=''){
                $str.='
                <p class="ls-l" style="top:350px;left:60px; border-radius: 3px; color: #FFFFFF; cursor: pointer; display: inline-block; line-height: 50px; outline: medium none; position: relative; text-transform: capitalize; transition: all 0.3s ease 0s; z-index: 1; background: none repeat scroll 0 0 #263944; padding: 0 20px; font-size:16px; font-weight:300;white-space: nowrap;" data-ls="offsetxin:0;durationin:400;delayin:4500;easingin:linear;rotateyin:90;transformoriginin:left 50% 0;offsetxout:0;durationout:100;showuntil:5400;easingout:linear;rotateyout:90;transformoriginout:left 50% 0;">
                <a href="http://'.$item['lnk'].'" target="_blank" style="color:#fff">
                Xem Thêm...
                </a>
                </p>';
            }
            $str.='
            </div>';
        }
        $str.='
        </div>';
        return $str;
    }
}

?>