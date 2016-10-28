<?php
function mainProcess($db)
{
	return about($db);	
}
function about($db)
{
	$msg='';
    $id=1;
    $act='basic_config';
    $table='basic_config';
    
    $db->where('id',1);
    $list = $db->getOne($table);
    $btn=array('name'=>'update','value'=>'Update');
    $form = new form($list);
        
	if(isset($_POST["addNew"])||isset($_POST["update"])) {
        $smtp_server=htmlspecialchars($_POST['smtp_server']);	
        $smtp_port=htmlspecialchars($_POST['smtp_port']);	
        $smtp_user=htmlspecialchars($_POST['smtp_user']);	
        $smtp_pwd=htmlspecialchars($_POST['smtp_pwd']);
        $smtp_receiver=htmlspecialchars($_POST['smtp_receiver']);
        $gmap_script=str_replace("'",'&rsquo;',$_POST['gmap_script']);	
        $another_script=htmlspecialchars($_POST['another_script']);	
        
        $social_twitter=htmlspecialchars($_POST['social_twitter']);
        $social_facebook=htmlspecialchars($_POST['social_facebook']);
        $social_google_plus=htmlspecialchars($_POST['social_google_plus']);
	}
	if(isset($_POST["update"]))	{
	   $update=array(
            'smtp_server'=>$smtp_server,'smtp_port'=>$smtp_port,'smtp_user'=>$smtp_user,
            'smtp_pwd'=>$smtp_pwd,'smtp_receiver'=>$smtp_receiver,'gmap_script'=>$gmap_script,
            'another_script'=>$another_script,'social_twitter'=>$social_twitter,
            'social_facebook'=>$social_facebook,'social_google_plus'=>$social_google_plus
       );
       try{
            $db->where('id',1);
            $db->update($table,$update);  
            header("location:".$_SERVER['REQUEST_URI'],true);   
       } catch (Exception $e){
            $msg = $e->getMessage();
       }
	}
	
    $page_head= array(
                    array('#','Quản lý thông tin cơ bản')
                );
	$str=$form->breadcumb($page_head);
	$str.=$form->message($msg);
	$str.='	
	<form role="form" id="actionForm" name="actionForm" enctype="multipart/form-data" action="" method="post" data-toggle="validator">
	<div class="row">
        <div class="col-lg-12"><h3>Thông Tin Cơ Bản</h3></div>
        <div class="col-lg-12">
            '.$form->text('smtp_server',array('label'=>'SMTP Server','required'=>true)).'      
            '.$form->text('smtp_port',array('label'=>'SMTP Port','required'=>true)).'      
            '.$form->email('smtp_user',array('label'=>'SMTP User<code>Tài khoản email gửi đi</code>','required'=>true)).'      
            '.$form->text('smtp_pwd',array('label'=>'SMTP password<code>Password email trên</code>','required'=>true)).'   
            '.$form->email('smtp_receiver',array('label'=>'Tài khoản email nhận mail','required'=>true)).'
            '.$form->textarea('gmap_script',array('label'=>'Google Map Script','rows'=>7)).'
            '.$form->textarea('another_script',array('label'=>'Các script khác','rows'=>7)).'
        </div>
        <div class="col-lg-12">
            <h3>Thông Tin mạng xã hội</h3>
        </div>
        <div class="col-lg-12">
            '.$form->text('social_twitter',array('label'=>'<i class="fa fa-twitter"></i> Twitter','required'=>true)).'      
            '.$form->text('social_facebook',array('label'=>'<i class="fa fa-facebook"></i> Facebook','required'=>true)).'      
            '.$form->text('social_google_plus',array('label'=>'<i class="fa fa-google-plus"></i> Google Plus','required'=>true)).'   
        </div>
	   '.$form->hidden($btn['name'],$btn['value']).'
	</div>
	</form>
	';	
	return $str;	
}

?>		