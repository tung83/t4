<?php
function mainProcess($db)
{
    switch($_GET['type']){
        case 'product_cate':
            return product_cate($db);
            break;
        case 'product_cate_2':
            return product_cate_2($db);
            break;
        default:
            if(isset($_GET['id'])) return product_image($db);
            else return product($db);
            break;
    }
}
function product_cate($db)
{
	$msg='';
    $act='product';
    $type='product_cate';
    $table='product_cate';
    $lev=1;
    if(isset($_POST["Edit"])&&$_POST["Edit"]==1){
		$db->where('id',$_POST['idLoad']);
        $list = $db->getOne($table);
        $btn=array('name'=>'update','value'=>'Update');
        $form = new form($list);
	} else {
        $btn=array('name'=>'addNew','value'=>'Submit');	
        $form = new form();
	}
	if(isset($_POST["addNew"])||isset($_POST["update"])) {
        $title=htmlspecialchars($_POST['title']);	   
        $meta_kw=htmlspecialchars($_POST['meta_keyword']);
        $meta_desc=htmlspecialchars($_POST['meta_description']);
        $e_title=htmlspecialchars($_POST['e_title']);	   
        $e_meta_kw=htmlspecialchars($_POST['e_meta_keyword']);
        $e_meta_desc=htmlspecialchars($_POST['e_meta_description']);
        $active=$_POST['active']=="on"?1:0;
        $ind=intval($_POST['ind']);
	}
    if(isset($_POST['listDel'])&&$_POST['listDel']!=''){
        $list = explode(',',$_POST['listDel']);
        foreach($list as $item){           
            $db->where('id',intval($item));
            try{
               $db->delete($table); 
            } catch(Exception $e) {
                $msg=$e->getMessage();
            }   
        }
        header("location:".$_SERVER['REQUEST_URI'],true);
    }
	if(isset($_POST["addNew"])) {
        $insert = array(
                    'title'=>$title,'meta_keyword'=>$meta_kw,
                    'meta_description'=>$meta_desc,
                    'e_title'=>$e_title,'e_meta_keyword'=>$e_meta_kw,
                    'e_meta_description'=>$e_meta_desc,
                    'ind'=>$ind,
                    'lev'=>$lev,
                    'active'=>$active
                );
		try{
            $recent = $db->insert($table,$insert);
            header("location:".$_SERVER['REQUEST_URI'],true); 
        } catch(Exception $e) {
            $msg=$e->getMessage();
        }			
	}
	if(isset($_POST["update"]))	{
	   $update=array(
                    'title'=>$title,'meta_keyword'=>$meta_kw,
                    'meta_description'=>$meta_desc,
                    'e_title'=>$e_title,'e_meta_keyword'=>$e_meta_kw,
                    'e_meta_description'=>$e_meta_desc,
                    'ind'=>$ind,
                    'lev'=>$lev,
                    'active'=>$active
                );
        try{
            $db->where('id',$_POST['idLoad']);
            $db->update($table,$update);  
            header("location:".$_SERVER['REQUEST_URI'],true);   
        } catch (Exception $e){
            $msg=$e->getMessage();
        }
	}
	
	if(isset($_POST["Del"])&&$_POST["Del"]==1) {
        $db->where('id',$_POST['idLoad']);
        try{
            if($_POST['idLoad']!=8&&$_POST['idLoad']!=9&&$_POST['idLoad']!=10){
                $db->delete($table); 
                header("location:".$_SERVER['REQUEST_URI'],true);
            }
        } catch(Exception $e) {
            $msg=$e->getMessage();
        }
	}
    $page_head= array(
                    array('#','Danh mục sản phẩm')
                );
	$str=$form->breadcumb($page_head);
	$str.=$form->message($msg);
    
    $str.=$form->search_area($db,$act,'',$_GET['hint'],0);
    
    $head_title=array('Tiêu đề<code>Vi/En</code>','Thứ tự','Hiển thị');
	$str.=$form->table_start($head_title);
	
    $page=isset($_GET["page"])?intval($_GET["page"]):1;
    if(isset($_GET['hint'])) $db->where('title','%'.$_GET['hint'].'%','LIKE');  
    $db->where('lev',1)->orderBy('id');
    $db->pageLimit=ad_lim;
    $list=$db->paginate($table,$page);

    if($db->count!=0){
        foreach($list as $item){
            $item_content = array(
                array($item['title'].'<code>Vi</code><br/>'.$item['e_title'].'<code>En</code>','text'),
                array($item['ind'],'text'),
                array($item['active'],'bool')
            );
            $str.=$form->table_body($item['id'],$item_content);      
        }
    }                               
	$str.=$form->table_end();                            
    $str.=$form->pagination($page,ad_lim,$count);
	$str.='			
	<form role="form" id="actionForm" name="actionForm" enctype="multipart/form-data" action="" method="post" data-toggle="validator">
	<div class="row">
    	<div class="col-lg-12"><h3>Cập nhật - Thêm mới thông tin</h3></div>
        <div class="col-lg-12 admin-tabs">
            <ul class="nav nav-tabs">
    			<li class="active"><a href="#vietnamese" data-toggle="tab">Việt Nam</a></li>
    			<li><a href="#english" data-toggle="tab">English</a></li>
    		</ul>
    		<div class="tab-content">
    			<div class="tab-pane bg-vi active" id="vietnamese">
                    '.$form->text('title',array('label'=>'Tiêu đề','required'=>true)).'
                    '.$form->text('meta_keyword',array('label'=>'Keyword <code>SEO</code>')).'
                    '.$form->textarea('meta_description',array('label'=>'Description <code>SEO</code>')).'
    			</div>
    			<div class="tab-pane bg-en" id="english">
                    '.$form->text('e_title',array('label'=>'Tiêu đề','required'=>true)).'
                    '.$form->text('e_meta_keyword',array('label'=>'Keyword <code>SEO</code>')).'
                    '.$form->textarea('e_meta_description',array('label'=>'Description <code>SEO</code>')).'
    			</div>
    		</div>
        </div>
        <div class="col-lg-12">
            '.$form->number('ind',array('label'=>'Thứ tự','required'=>true)).'
            '.$form->checkbox('active',array('label'=>'Hiển Thị','checked'=>true)).'
        </div>
    	'.$form->hidden($btn['name'],$btn['value']).'
	</div>
	</form>
	';	
	return $str;
}
function product_cate_2($db){
    $msg='';
    $act='product';
    $type='product_cate_2';
    $table='product_cate';
    $lev=2;
    if(isset($_POST["Edit"])&&$_POST["Edit"]==1){
		$db->where('id',$_POST['idLoad']);
        $list = $db->getOne($table);
        $btn=array('name'=>'update','value'=>'Update');
        $form = new form($list);
	} else {
        $btn=array('name'=>'addNew','value'=>'Submit');	
        $form = new form();
	}
	if(isset($_POST["addNew"])||isset($_POST["update"])) {
        $title=htmlspecialchars($_POST['title']);	   
        $meta_kw=htmlspecialchars($_POST['meta_keyword']);
        $meta_desc=htmlspecialchars($_POST['meta_description']);
        $active=$_POST['active']=="on"?1:0;
        $ind=intval($_POST['ind']);
        $pId=intval($_POST['frm_cate_1']);
	}
    if(isset($_POST['listDel'])&&$_POST['listDel']!=''){
        $list = explode(',',$_POST['listDel']);
        foreach($list as $item){
            $db->where('id',intval($item));
            try{
               $db->delete($table); 
            } catch(Exception $e) {
                $msg=$e->getMessage();
            }
        }
        header("location:".$_SERVER['REQUEST_URI'],true);
    }
	if(isset($_POST["addNew"])) {
        $insert = array(
                    'title'=>$title,'lev'=>$lev,'pId'=>$pId,
                    'active'=>$active,'meta_keyword'=>$meta_kw,
                    'meta_description'=>$meta_desc,'ind'=>$ind
                );
		try{
            $recent = $db->insert($table,$insert);
            header("location:".$_SERVER['REQUEST_URI'],true); 
        } catch(Exception $e) {
            $msg=$e->getMessage();
        }			
	}
	if(isset($_POST["update"]))	{
	   $update=array(
                    'title'=>$title,'lev'=>$lev,'pId'=>$pId,
                    'active'=>$active,'meta_keyword'=>$meta_kw,
                    'meta_description'=>$meta_desc,'ind'=>$ind
                );
        try{
            $db->where('id',$_POST['idLoad']);
            $db->update($table,$update);  
            header("location:".$_SERVER['REQUEST_URI'],true);   
        } catch (Exception $e){
            $msg=$e->getMessage();
        }
	}
	
	if(isset($_POST["Del"])&&$_POST["Del"]==1) {
        $db->where('id',$_POST['idLoad']);
        try{
           $db->delete($table); 
           header("location:".$_SERVER['REQUEST_URI'],true);
        } catch(Exception $e) {
            $msg=$e->getMessage();
        }
	}
    $page_head= array(
                    array('#','Danh mục sản phẩm cấp 2')
                );
	$str=$form->breadcumb($page_head);
	$str.=$form->message($msg);
    
    $str.=$form->search_area($db,$act,'product_cate',$_GET['hint'],1);
    
    $head_title=array('Tiêu đề','Thuộc danh mục','Thứ tự','Hiển thị');
	$str.=$form->table_start($head_title);
	
    $page=isset($_GET["page"])?intval($_GET["page"]):1;
    if(isset($_GET['cate_lev_1'])&&intval($_GET['cate_lev_1'])!=0) $db->where('pId',intval($_GET['cate_lev_1']));
    if(isset($_GET['hint'])) $db->where('title','%'.$_GET['hint'].'%','LIKE');
    $db->where('lev',2)->orderBy('id');
    $db->pageLimit=ad_lim;
    $list=$db->paginate($table,$page);

    if($db->count!=0){
        foreach($list as $item){
            $cate=$db->where('id',$item['pId'])->getOne('product_cate','id,title');
            $item_content = array(
                array($item['title'],'text'),
                array(array($cate),'cate'),
                array($item['ind'],'text'),
                array($item['active'],'bool')
            );
            $str.=$form->table_body($item['id'],$item_content);      
        }
    }                               
	$str.=$form->table_end();                            
    $str.=$form->pagination($page,ad_lim,$count);
	$str.='			
	<form role="form" id="actionForm" name="actionForm" enctype="multipart/form-data" action="" method="post" data-toggle="validator">
	<div class="row">
    	<div class="col-lg-12"><h3>Cập nhật - Thêm mới thông tin</h3></div>
        <div class="col-lg-12">
            '.$form->text('title',array('label'=>'Tiêu đề','required'=>true)).'
            '.$form->cate_group($db,$table='product_cate',1).'
            '.$form->text('meta_keyword',array('label'=>'Keyword <code>SEO</code>')).'
            '.$form->textarea('meta_description',array('label'=>'Description <code>SEO</code>')).'
            '.$form->number('ind',array('label'=>'Thứ tự','required'=>true)).'
            '.$form->checkbox('active',array('label'=>'Hiển Thị','checked'=>true)).'
        </div>
    	'.$form->hidden($btn['name'],$btn['value']).'
	</div>
	</form>';	
	return $str;
}
function product($db){
    $msg='';
    $act='product';
    $type='product';
    $table='product';
    if(isset($_POST["Edit"])&&$_POST["Edit"]==1){
		$db->where('id',$_POST['idLoad']);
        $list = $db->getOne($table);
        $btn=array('name'=>'update','value'=>'Update');
        $form = new form($list);
	} else {
        $btn=array('name'=>'addNew','value'=>'Submit');
        $form = new form();
	}
	if(isset($_POST["addNew"])||isset($_POST["update"])) {
        $title=htmlspecialchars($_POST['title']);
        //$price=intval($_POST['price']);
        //$price_reduce=intval($_POST['price_reduce']);
        $meta_kw=htmlspecialchars($_POST['meta_keyword']);
        $meta_desc=htmlspecialchars($_POST['meta_description']);
        $content=str_replace("'","",$_POST['content']);     
        $feature=str_replace("'","",$_POST['feature']);
        $detail=str_replace("'","",$_POST['detail']);
        $manual=str_replace("'","",$_POST['manual']);
        
        $e_title=htmlspecialchars($_POST['e_title']);
        $price=intval($_POST['price']);
        $price_reduce=intval($_POST['price_reduce']);
        $e_meta_kw=htmlspecialchars($_POST['e_meta_keyword']);
        $e_meta_desc=htmlspecialchars($_POST['e_meta_description']);
        $e_content=str_replace("'","",$_POST['e_content']);     
        $e_feature=str_replace("'","",$_POST['e_feature']);
        $e_detail=str_replace("'","",$_POST['e_detail']);
        $e_manual=str_replace("'","",$_POST['e_manual']);

        $active=$_POST['active']=="on"?1:0;
        $home=$_POST['home']=='on'?1:0;
        $ind=intval($_POST['ind']);
        $pId=intval($_POST['frm_cate_1']);
	}
    if(isset($_POST['listDel'])&&$_POST['listDel']!=''){
        $list = explode(',',$_POST['listDel']);
        foreach($list as $item){
            $db->where('id',intval($item));
            try{
               $db->delete($table);
            } catch(Exception $e) {
                $msg=mysql_error();
            }
        }
        header("location:".$_SERVER['REQUEST_URI'],true);
    }
	if(isset($_POST["addNew"])) {
        $insert = array(
                    'title'=>$title,'content'=>$content,
                    'detail'=>$detail,'manual'=>$manual,
                    'feature'=>$feature,'meta_keyword'=>$meta_kw,
                    'meta_description'=>$meta_desc,
                    
                    'e_title'=>$e_title,'e_content'=>$e_content,
                    'e_detail'=>$e_detail,'e_manual'=>$e_manual,
                    'e_feature'=>$e_feature,'e_meta_keyword'=>$e_meta_kw,
                    'e_meta_description'=>$e_meta_desc,
                    
                    'price'=>$price,'price_reduce'=>$price_reduce,                    
                    'home'=>$home,'active'=>$active,'pId'=>$pId,'ind'=>$ind
                );
		try{
            $db->insert($table,$insert);
            header("location:".$_SERVER['REQUEST_URI'],true);
        } catch(Exception $e) {
            $msg=$e->getMessage();
        }
	}
	if(isset($_POST["update"]))	{
	   $update=array(
                    'title'=>$title,'content'=>$content,
                    'detail'=>$detail,'manual'=>$manual,
                    'feature'=>$feature,'meta_keyword'=>$meta_kw,
                    'meta_description'=>$meta_desc,
                    
                    'e_title'=>$e_title,'e_content'=>$e_content,
                    'e_detail'=>$e_detail,'e_manual'=>$e_manual,
                    'e_feature'=>$e_feature,'e_meta_keyword'=>$e_meta_kw,
                    'e_meta_description'=>$e_meta_desc,
                    
                    'price'=>$price,'price_reduce'=>$price_reduce,                    
                    'home'=>$home,'active'=>$active,'pId'=>$pId,'ind'=>$ind
                );
        try{
            $db->where('id',$_POST['idLoad']);
            $db->update($table,$update);
            header("location:".$_SERVER['REQUEST_URI'],true);
        } catch (Exception $e){
            $msg=$e->getMessage();
        }
	}

	if(isset($_POST["Del"])&&$_POST["Del"]==1) {
        $db->where('id',$_POST['idLoad']);
        try{
           $db->delete($table);
           header("location:".$_SERVER['REQUEST_URI'],true);
        } catch(Exception $e) {
            $msg=$e->getMessage();
        }
	}
    
    $page_head= array(
                    array('#','Danh sách sản phẩm')
                );

	$str=$form->breadcumb($page_head);
	$str.=$form->message($msg);
    
    $str.=$form->search_area($db,$act,'product_cate',$_GET['hint'],1);

    $head_title=array('Tên SP<code>Vi/En</code>','Đơn Giá(VNĐ)','Giá KM(VNĐ)','Danh mục','Hình ảnh','Trang chủ','Hiển thị','Thứ tự');
	$str.=$form->table_start($head_title);
    
    $page=isset($_GET["page"])?intval($_GET["page"]):1;
    if(isset($_GET['hint'])) $db->where('title','%'.$_GET['hint'].'%','LIKE'); 
    if(isset($_GET['cate_lev_1'])&&intval($_GET['cate_lev_1'])>0){
        $db->where('pId',intval($_GET['cate_lev_1']));
    }
    $db->orderBy('id');
    $db->pageLimit=ad_lim;
    $list=$db->paginate($table,$page);
    $count=$db->totalCount;
    if($db->count!=0){
        $db_sub=$db;
        foreach($list as $item){
            $cate_1=$db->where('id',$item['pId'])->where('lev',1)->getOne('product_cate','id,title,pId');
            //$cate_1=$db->where('id',$cate_2['pId'])->where('lev',1)->getOne('product_cate','id,title');
            $img=$db->where('pId',$item['id'])->orderBy('ind','asc')->getOne('product_image','img');
            if(trim($img['img'])==='') $img='holder.js/130x100';else $img=myPath.$img['img'];   
            $item_content = array(
                array($item['title'].'<br/><code>'.$item['e_title'].'</code>','text'),
                //array($item['code'],'text'),
                array($item['price'],'number'),
                array($item['price_reduce'],'number'),  
                array(array($cate_1),'cate'),                              
                array($img,'image'),
                
                array($item['home'],'bool'),
                array($item['active'],'bool'),
                array($item['ind'],'text')
            );
            $addition=array(
                array('variable'=>array('act'=>$act,'type'=>$type,'id'=>$item['id']),'icon'=>'upload')
            );
            $str.=$form->table_body($item['id'],$item_content,$addition);
        }
    }
	$str.=$form->table_end();                            
    $str.=$form->pagination($page,ad_lim,$count);
	$str.='
	<form role="form" class="form" id="actionForm" name="actionForm" enctype="multipart/form-data" action="" method="post" data-toggle="validator">
	<div class="row">
    	<div class="col-lg-12"><h3>Cập nhật - Thêm mới thông tin</h3></div>
        <div class="col-lg-12">
            '.$form->cate_group($db,'product_cate',1).'
        </div>
        <div class="col-lg-12 admin-tabs">
            <ul class="nav nav-tabs">
    			<li class="active"><a href="#vietnamese" data-toggle="tab">Việt Nam</a></li>
    			<li><a href="#english" data-toggle="tab">English</a></li>
    		</ul>
    		<div class="tab-content">
    			<div class="tab-pane bg-vi active" id="vietnamese">
                    '.$form->text('title',array('label'=>'Tên SP','required'=>true)).'
                    '.$form->text('meta_keyword',array('label'=>'Keyword <code>SEO</code>')).'
                    '.$form->textarea('meta_description',array('label'=>'Description <code>SEO</code>')).'
                    '.$form->textarea('feature',array('label'=>'Điểm nổi bật')).'            
                    '.$form->ckeditor('content',array('label'=>'Mô tả chi tiết')).'
    			</div>
    			<div class="tab-pane bg-en" id="english">
                    '.$form->text('e_title',array('label'=>'Tên SP','required'=>true)).'
                    '.$form->text('e_meta_keyword',array('label'=>'Keyword <code>SEO</code>')).'
                    '.$form->textarea('e_meta_description',array('label'=>'Description <code>SEO</code>')).'
                    '.$form->textarea('e_feature',array('label'=>'Điểm nổi bật')).'            
                    '.$form->ckeditor('e_content',array('label'=>'Mô tả chi tiết')).'                   
    			</div>
    		</div>
        </div>
        <div class="col-lg-12">
            '.$form->number('price',array('label'=>'Đơn giá','required'=>true)).'
            '.$form->number('price_reduce',array('label'=>'Đơn giá(Khuyến mãi)')).'
            '.$form->checkbox('active',array('label'=>'Hiển Thị','checked'=>true)).'
            '.$form->checkbox('home',array('label'=>'Trang chủ')).'
            
            '.$form->number('ind',array('label'=>'Thứ tự')).'
    	</div>
        
    	'.$form->hidden($btn['name'],$btn['value']).'
	</div>
	</form>
	';
	return $str;
}
function product_image($db){
    $msg='';
    $act='product';
    $type='product';
    $table='product_image';
    $pId=intval($_GET['id']);
    
    if(isset($_POST["Edit"])&&$_POST["Edit"]==1){
		$db->where('id',$_POST['idLoad']);
        $list = $db->getOne($table);
        $btn=array('name'=>'update','value'=>'Update');
        $form = new form($list);
	} else {
        $btn=array('name'=>'addNew','value'=>'Submit');
        $form = new form();
	}
	if(isset($_POST["addNew"])||isset($_POST["update"])) {
        $ind=intval($_POST['ind']);
        $active=$_POST['active']=="on"?1:0;
        $file=time().$_FILES['file']['name'];
	}
    if(isset($_POST['listDel'])&&$_POST['listDel']!=''){
        $list = explode(',',$_POST['listDel']);
        foreach($list as $item){
            $db->where('id',intval($item));
            try{
               $db->delete($table);
            } catch(Exception $e) {
                $msg=$e->getMessage();
            }
        }
        header("location:".$_SERVER['REQUEST_URI'],true);
    }
	if(isset($_POST["addNew"])) {
        $insert = array('ind'=>$ind,'active'=>$active,'pId'=>$pId);
		try{
            $recent = $db->insert($table,$insert);
            if(common::file_check($_FILES['file'])){
                WideImage::load('file')->resize(320,620, 'fill')->saveToFile(myPath.$file);
                WideImage::load(myPath.$file)->resize(160,310, 'fill')->saveToFile(myPath.'thumb_'.$file);
                $db->where('id',$recent);
                $db->update($table,array('img'=>$file));
            }
            header("location:".$_SERVER['REQUEST_URI'],true);
        } catch(Exception $e) {
            $msg=$e->getMessage();
        }
	}
	if(isset($_POST["update"]))	{
	   $update=array('ind'=>$ind,'active'=>$active);
       if(common::file_check($_FILES['file'])){
            WideImage::load('file')->resize(320,620, 'fill')->saveToFile(myPath.$file);
            WideImage::load(myPath.$file)->resize(160,310, 'fill')->saveToFile(myPath.'thumb_'.$file);
            $update = array_merge($update,array('img'=>$file));
            $db->where('id',$_POST['idLoad']);
            $last_img = $db->getOne($table,'img');
            if($last_img['img']!='') unlink(myPath.$last_img['img']);
        }
        try{
            $db->where('id',$_POST['idLoad']);
            $db->update($table,$update);
            header("location:".$_SERVER['REQUEST_URI'],true);
        } catch (Exception $e){
            $msg=$e->getMessage();
        }
	}

	if(isset($_POST["Del"])&&$_POST["Del"]==1) {
        $db->where('id',$_POST['idLoad']);
        try{
           $db->delete($table);
           header("location:".$_SERVER['REQUEST_URI'],true);
        } catch(Exception $e) {
            $msg=mysql_error();
        }
	}
    $db->where('id',$pId);
    $pd=$db->getOne('product','id,title,pId');
    $db->where('id',$pd['pId']);
    $cate=$db->getOne('product_cate','id,title');

    $page_head= array(
                    array('#','Hình ảnh sản phẩm'),
                    array('main.php?act='.$act.'&type='.$type,$pd['title'].' <code><i class="fa fa-backward"></i></code>')
                );
	$str=$form->breadcumb($page_head);
	$str.=$form->message($msg);
    $head_title=array('Hình ảnh','Thứ tự','Hiển thị');
	$str.=$form->table_start($head_title);
    
    $page=isset($_GET["page"])?intval($_GET["page"]):1;
	$db->where('pId',$pId);
    $db->pageLimit=ad_lim;
	$list=$db->paginate($table,$page);
	$count= $db->totalCount;
	
	
    if($count>0){
        foreach($list as $item){
            $item_content = array(
                array(myPath.$item['img'],'image'),
                array($item['ind'],'text'),
                array($item['active'],'bool')
            );
            $str.=$form->table_body($item['id'],$item_content);
        }
    }
    $str.=$form->table_end();                            
    $str.=$form->pagination($page,ad_lim,$count);
	$str.='
	<form role="form" id="actionForm" name="actionForm" enctype="multipart/form-data" action="" method="post" data-toggle="validator">
	<div class="row">
    	<div class="col-lg-12"><h3>Cập nhật - Thêm mới thông tin</h3></div>
        <div class="col-lg-12">
            '.$form->file('file',array('label'=>'Hình ảnh <code>( 320,620 )</code>')).'
            '.$form->number('ind',array('label'=>'Thứ tự','required'=>true)).'
            '.$form->checkbox('active',array('label'=>'Hiển Thị','checked'=>true)).'
        </div>
    	'.$form->hidden($btn['name'],$btn['value']).'
	</div>
	</form>
	';
	return $str;
}
?>
