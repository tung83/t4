<?php
/**
* Author: Le Chu An
* Css:retina-menu
* js:modernizr.custom
* demo:web.e1shopping.com
**/
class retina_menu{
    private $db,$list;
    function __construct($db,$lang='vi'){
        $this->db=$db;
        $this->db->reset();
        $this->get_list();
    }
    function get_list(){
        $this->db->where('active',1)->orderBy('ind','ASC')->orderBy('id');
        $this->list=$this->db->get('menu');
    }
    function output(){
        $str.='
        <div class="main clearfix">
			<nav id="retina-menu" class="nav">					
				<ul>';
        foreach($this->list as $item){
            $str.='
            <li>
				<a href="#">
					<span class="icon">
						<i aria-hidden="true" class="icon-'.$item['icon'].'"></i>
					</span>
					<span>'.$item['title'].'</span>
				</a>
			</li>';
        }
        $str.='
				</ul>
			</nav>
		</div>';
        return $str;
    }
}
?>