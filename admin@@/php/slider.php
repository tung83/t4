<?php
function mainProcess($db){
	return slider($db);	
}
function slider($db){
	$msg='';
    $act='slider';
    $table='slider';
    if(isset($_POST["Edit"])&&$_POST["Edit"]==1){
		$db->where('id',$_POST['idLoad']);
        $item = $db->getOne($table);
        $btn=array('name'=>'update','value'=>'Update');
        $form = new form($item);
	} else {
        $btn=array('name'=>'addNew','value'=>'Submit');	
        $form = new form();
	}
	if(isset($_POST["addNew"])||isset($_POST["update"])) {
        $lnk=htmlspecialchars($_POST['lnk']);
        $e_lnk=htmlspecialchars($_POST['e_lnk']);
        $ind=intval($_POST['ind']);	   
        $file=time().$_FILES['file']['name'];
        $active=$_POST['active']=='on'?1:0;        
	}
    if(isset($_POST['listDel'])&&$_POST['listDel']!=''){
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
                    'ind'=>$ind,'e_lnk'=>$e_lnk,
                    'active'=>$active,'lnk'=>$lnk
                );
		try{
            $recent = $db->insert($table,$insert);
            if(common::file_check($_FILES['file'])){
                WideImage::load('file')->resize(1180,470,'fill')->saveToFile(myPath.$file);
                $db->where('id',$recent);
                $db->update($table,array('img'=>$file));
            }
            header("location:".$_SERVER['REQUEST_URI'],true); 
        } catch(Exception $e) {
            $msg=mysql_error();
        }			
	}
	if(isset($_POST["update"]))	{
        $update=array(
                    'ind'=>$ind,'e_lnk'=>$e_lnk,
                    'active'=>$active,'lnk'=>$lnk
                );
        if(common::file_check($_FILES['file'])){
            WideImage::load('file')->resize(1180,470, 'fill')->saveToFile(myPath.$file);
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
	if(isset($_POST["Del"])&&$_POST["Del"]==1) {
        try{
            $form->img_remove($_POST['idLoad'],$db,$table);
            $db->where('id',$_POST['idLoad']);
            $db->delete($table);            
            header("location:".$_SERVER['REQUEST_URI'],true);
        } catch(Exception $e) {
            $msg=$e->getErrorMessage();
        }
	}
    $page_head= array(
                    array('#','Home Slider')
                );
	$str=$form->breadcumb($page_head);
	$str.=$form->message($msg);
    
    $str.=$form->search_area($db,$act,'category',$_GET['hint'],0);
    
    $head_title=array('Hình ảnh','Liên kết<code>Vi</code>','Liên kết<code>En</code>','STT','Hiển thị');    
	$str.=$form->table_start($head_title);
    
    $page=isset($_GET["page"])?intval($_GET["page"]):1;
    if(isset($_GET['hint'])) $db->where('title','%'.$_GET['hint'].'%','LIKE');  
    $db->orderBy('id');
    $db->pageLimit=ad_lim;
	$list=$db->paginate($table,$page);    
	$count= $db->totalCount;
	
    if($count!=0){
        foreach($list as $item){
            $item_content = array(
                array(myPath.$item['img'],'image'),                
                array($item['lnk'],'link'),
                array($item['e_lnk'],'link'),
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
                    '.$form->text('lnk',array('label'=>'Liên kết')).'
    			</div>
    			<div class="tab-pane bg-en" id="english">
                    '.$form->text('e_lnk',array('label'=>'Liên kết')).'
    			</div>
    		</div>
        </div>
        <div class="col-lg-12">
            '.$form->file('file',array('label'=>'Hình ảnh <code>( đề nghị: 1180,470 )</code>')).'
            '.$form->number('ind',array('label'=>'Thứ tự')).'
            '.$form->checkbox('active',array('label'=>'Hiển thị','checked'=>true)).'
        </div>
		'.$form->hidden($btn['name'],$btn['value']).'
	</div>
	</form>';	
	return $str;	
}
?>