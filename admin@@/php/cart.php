<?php
function mainProcess($db)
{
    if(isset($_GET['cart_id'])){
        return cart_view($db);
    } else {
        return cart($db);
    }
}

function cart($db)
{
	$msg='';
    $table='cart';
	if(isset($_POST["addNew"])||isset($_POST["update"]))
	{
		$title=str_replace("'",'&rsquo;',$_POST["title"]);
		$detail=str_replace("'",'',$_POST["detail"]);
		$active=$_POST["active"]=="on"?1:0;
	}
	if(isset($_POST["Del"])&&$_POST["Del"]==1)
	{
		$db->where('id',$_POST['idLoad']);
        try{
           $db->delete($table); 
           $db->where('cart_id',$_POST['idLoada']);
           $db->delete('cart_detail');
           header("location:".$_SERVER['REQUEST_URI'],true);
        } catch(Exception $e) {
            $msg=mysql_error();
        }
	}
	$str='
	<!-- Page Heading -->
	<div class="row">
		<div class="col-lg-12">
			<ol class="breadcrumb">
				<li class="active">
					<i class="fa fa-dashboard"></i> Giỏ hàng
				</li>
			</ol>
		</div>
	</div>';
	if($msg!='')
	{
		$str.='<div class="alert alert-danger" role="alert" style="margin-top:10px">'.$sUpdate.'</div>';	
	}
	$str.='
	<!-- Row -->
	<div class="row">
		 <div class="col-lg-12">
			<div class="table-responsive">
				<table class="table table-bordered table-hover table-striped">
					<thead>
						<tr>
							<th>ID</th>							
							<th>Họ&Tên</th>
							<th>Địa chỉ</th>	
                            <th>Điện thoại</th>						
							<th>Số lượng</th>
							<th>Tổng tiền</th>
							<th>Nội dung</th>
							<th style="width:10% !important">Options</th>
						</tr>
					</thead>
					<tbody>
					';
	$page=isset($_GET["page"])?intval($_GET["page"]):1;
    $db->pageLimit=ad_lim;
    $db->orderBy('id');
    $list=$db->paginate($table,$page);
    
	foreach($list as $item)
	{
        $db->reset();
        $db->where('cart_id',$item['id']);
        $cart_detail=$db->get('cart_detail',null,'id,product_id,product_title,product_price,product_qty');
        $qty=$total=0;
        foreach($cart_detail as $val){
            $qty+=$val['product_qty'];
            $total+=($val['product_price']*$val['product_qty']);
        }
		$str.='
		<tr>
			<td>'.$item['id'].'</td>
			<td>'.$item['name'].'</td>
			<td>'.$item['adds'].'</td>
            <td>'.$item['phone'].'</td>
			<td>'.$qty.'</td>
			<td>'.$total.'</td>
			<td>'.nl2br($item['notice']).'</td>
			<td align="center">
		';
	
	
	$str.='
    <a href="main.php?act=cart&cart_id='.$item['id'].'" class="glyphicon glyphicon-eye-open" aria-hidden="true"></a>
	<a href="javascript:operationFrm('.$item['id'].",'D'".')" class="glyphicon glyphicon-trash" aria-hidden="true"></a>			  
			</td>
		</tr>
		';	
	}                                 
	$str.='					
					</tbody>
				</table>
				</div>';
	$pg = new Pagination();
    $pg->pagenumber = $page;
    $pg->pagesize = ad_lim;
    $pg->totalrecords = $db->totalCount;
    $pg->paginationstyle = 1; // 1: advance, 0: normal
    $pg->defaultUrl = "main.php?act=$act";
    $pg->paginationUrl = "main.php?act=$act&page=[p]";
    $str.= $pg->process();
	$str.='
			</div>
		</div>
		<!-- Row -->
		<form role="form" name="actionForm" enctype="multipart/form-data" action="" method="post">
		
		<div class="col-lg-12">
			<input type="hidden" name="idLoad" value="'.$_POST["idLoad"].'"/>
			<input type="hidden" name="Edit"/>
			<input type="hidden" name="Del"/>	
		</div>
	</div>
	</form>
	';	
	return $str;		
}
function cart_view($db){
    $cart_id=intval($_GET['cart_id']);
    $msg='';
    $table='cart_detail';
    $cart=$db->where('id',$cart_id)->getOne('cart');
	if(isset($_POST["Del"])&&$_POST["Del"]==1)
	{
		$db->where('id',$_POST['idLoad']);
        try{
           $db->delete($table);
           header("location:".$_SERVER['REQUEST_URI'],true);
        } catch(Exception $e) {
            $msg=mysql_error();
        }
	}
	$str='
	<!-- Page Heading -->
	<div class="row">
		<div class="col-lg-12">
			<ol class="breadcrumb">
				<li class="active">
					<i class="fa fa-dashboard"></i> Giỏ hàng
				</li>
                <li>
					<a href="main.php?act=cart"><i class="fa fa-caret-left"></i> Về đặt hàng</a>
				</li>
			</ol>
            <p>
            <code><strong>Họ tên:</strong> '.$cart['name'].'</code><br/>
            <code><strong>Địa chỉ:</strong> '.$cart['adds'].'</code><br/>
            <code><strong>Điện thoại:</strong> '.$cart['phone'].'</code><br/>
            <code><strong>Ghi chú:</strong> '.nl2br($cart['notice']).'</code>
            </p>
		</div>
	</div>';
	if($msg!='')
	{
		$str.='<div class="alert alert-danger" role="alert" style="margin-top:10px">'.$sUpdate.'</div>';	
	}
	$str.='
	<!-- Row -->
	<div class="row">
		 <div class="col-lg-12">
			<div class="table-responsive">
				<table class="table table-bordered table-hover table-striped">
					<thead>
						<tr>
							<th>ID</th>							
							<th>ID-Tên SP</th>
							<th>Số Lượng</th>	
                            <th>Đơn Giá</th>						
							<th>Thành Tiền</th>
							<th style="width:10% !important">Options</th>
						</tr>
					</thead>
					<tbody>
					';
    $db->where('cart_id',$cart_id);
	$page=isset($_GET["page"])?intval($_GET["page"]):1;
    $db->pageLimit=ad_lim;
    $db->orderBy('id');
    $list=$db->paginate($table,$page);
	foreach($list as $item)
	{        
        $qty=$total=0;
        $qty+=$item['product_qty'];
        $total+=($item['product_price']*$item['product_qty']);
		$str.='
		<tr>
			<td>'.$item['id'].'</td>
			<td>'.$item['product_id'].'-'.$item['product_title'].'</td>
			<td>'.$item['product_qty'].'</td>
            <td>'.$item['product_price'].'</td>
			<td>'.$total.'</td>
			<td align="center">
		';
	
	
	$str.='
	<a href="javascript:operationFrm('.$item['id'].",'D'".')" class="glyphicon glyphicon-trash" aria-hidden="true"></a>			  
			</td>
		</tr>
		';	
	}                                 
	$str.='					
					</tbody>
				</table>
				</div>';
	$pg = new Pagination();
    $pg->pagenumber = $page;
    $pg->pagesize = ad_lim;
    $pg->totalrecords = $db->totalCount;
    $pg->paginationstyle = 1; // 1: advance, 0: normal
    $pg->defaultUrl = "main.php?act=$act";
    $pg->paginationUrl = "main.php?act=$act&page=[p]";
    $str.= $pg->process();
	$str.='
			</div>
		</div>
		<!-- Row -->
		<form role="form" name="actionForm" enctype="multipart/form-data" action="" method="post">
		
		<div class="col-lg-12">
			<input type="hidden" name="idLoad" value="'.$_POST["idLoad"].'"/>
			<input type="hidden" name="Edit"/>
			<input type="hidden" name="Del"/>	
		</div>
	</div>
	</form>
	';	
	return $str;
}
?>