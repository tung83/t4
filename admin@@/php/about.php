<?php
function mainProcess($db)
{
	return about($db);	
}
function about($db)
{
	$msg='';
    $act='about';
    $table='about';
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
        $sum=$_POST['sum'];
        $content=str_replace("'","",$_POST['content']);
        $meta_kw=htmlspecialchars($_POST['meta_keyword']);
        $meta_desc=htmlspecialchars($_POST['meta_description']);
        
        $e_title=htmlspecialchars($_POST['e_title']);	
        $e_sum=$_POST['e_sum'];
        $e_content=str_replace("'","",$_POST['e_content']);
        $e_meta_kw=htmlspecialchars($_POST['e_meta_keyword']);
        $e_meta_desc=htmlspecialchars($_POST['e_meta_description']);
        
        $ind=intval($_POST['ind']);
        $active=$_POST['active']=="on"?1:0;
        $file=time().$_FILES['file']['name'];
	}
    /*if(isset($_POST['listDel'])&&$_POST['listDel']!=''){
        $list = explode(',',$_POST['listDel']);
        foreach($list as $item){
            $form->img_remove(intval($item),$db,$table);
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
            'title'=>$title,'content'=>$content,'ind'=>$ind,
            'active'=>$active,'dates'=>date('Y-m-d H:i:s')
        );
		try{
            $recent = $db->insert($table,$insert);
            if(common::file_check($_FILES['file'])){
                WideImage::load('file')->resize(336,272, 'fill')->saveToFile(myPath.$file);
                $db->where('id',$recent);
                $db->update($table,array('img'=>$file));
            }
            header("location:".$_SERVER['REQUEST_URI'],true); 
        } catch(Exception $e) {
            $msg=mysql_error();
        }			
	}*/
	if(isset($_POST["update"]))	{
	   $update=array(
            'title'=>$title,'content'=>$content,
            'sum'=>$sum,'meta_keyword'=>$meta_kw,
            'meta_description'=>$meta_desc,
            
            'e_title'=>$e_title,'e_content'=>$e_content,
            'e_sum'=>$e_sum,'e_meta_keyword'=>$e_meta_kw,
            'e_meta_description'=>$e_meta_desc,
            
            'ind'=>$ind,'active'=>$active,'dates'=>date('Y-m-d H:i:s')
       );
       if(common::file_check($_FILES['file'])){
            WideImage::load('file')->resize(900,400, 'fill')->saveToFile(myPath.$file);
            WideImage::load('file')->saveToFile(myPath.$file);
            $update = array_merge($update,array('img'=>$file));
            $form->img_remove($_POST['idLoad'],$db,$table);
       }
       try{
            $db->where('id',$_POST['idLoad']);
            $db->update($table,$update);  
            header("location:".$_SERVER['REQUEST_URI'],true);   
       } catch (Exception $e){
            $msg = $e->getErrorMessage();
       }
	}
	
	/*if(isset($_POST["Del"])&&$_POST["Del"]==1) {
        try{
            $form->img_remove($_POST['idLoad'],$db,$table);
            $db->where('id',$_POST['idLoad']);
            $db->delete($table);            
            header("location:".$_SERVER['REQUEST_URI'],true);
        } catch(Exception $e) {
            $msg=$e->getErrorMessage();
        }
	}*/
    $page_head= array(
                    array('#','Quản lý giới thiệu')
                );
	$str=$form->breadcumb($page_head);
	$str.=$form->message($msg);
    
    $str.=$form->search_area($db,$act,'category',$_GET['hint'],0);
    
    $head_title=array('Tiêu đề<code>Vi/En</code>','Hình ảnh','Hiển thị');
	$str.=$form->table_start($head_title);
    
    $page=isset($_GET["page"])?intval($_GET["page"]):1;
    if(isset($_GET['hint'])) $db->where('title','%'.$_GET['hint'].'%','LIKE');  
    $db->orderBy('id');
    $db->pageLimit=$lim=ad_lim;
	$list=$db->paginate($table,$page);    
	$count= $db->totalCount;
	
    if($count!=0){
        foreach($list as $item){
            $item_content = array(                           
                array($item['title'].'<br/><code>'.$item['e_title'].'</code>','text'),
                array(myPath.$item['img'],'image'),
                //array($item['ind'],'number'),
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
                    '.$form->textarea('sum',array('label'=>'Trích Dẫn','required'=>true)).'      
                    '.$form->text('meta_keyword',array('label'=>'Keyword<code>SEO</code>','required'=>true)).'      
                    '.$form->textarea('meta_description',array('label'=>'Meta Description<code>SEO</code>','required'=>true)).'   
                    '.$form->ckeditor('content',array('label'=>'Nội dung','required'=>true)).'
    			</div>
    			<div class="tab-pane bg-en" id="english">
                    '.$form->text('e_title',array('label'=>'Tiêu đề','required'=>true)).'      
                    '.$form->textarea('e_sum',array('label'=>'Trích Dẫn','required'=>true)).'      
                    '.$form->text('e_meta_keyword',array('label'=>'Keyword<code>SEO</code>','required'=>true)).'      
                    '.$form->textarea('e_meta_description',array('label'=>'Meta Description<code>SEO</code>','required'=>true)).'   
                    '.$form->ckeditor('e_content',array('label'=>'Nội dung','required'=>true)).'
    			</div>
    		</div>
        </div>
        <div class="col-lg-12">
            '.$form->file('file',array('label'=>'Hình ảnh<code>900,400</code>')).'
            '.$form->checkbox('active',array('label'=>'Hiển Thị','checked'=>true)).'
            
        </div>
	   '.$form->hidden($btn['name'],$btn['value']).'
	</div>
	</form>
	';	
	return $str;	
}

?>		