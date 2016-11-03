<?php
class cart_show{
    private $cart;
    function __construct($db,$view='gio-hang',$lang='vi'){
        $this->db=$db;
        $this->cart=$_SESSION['cart'];
        $this->view=$view;
        $this->lang=$lang;
    }
    function breadcrumb(){
        
    }
    function cart_checkout(){
        $this->db->reset();
        if(isset($_POST['contact_send'])){
            $name=htmlspecialchars($_POST['name']);
            $adds=htmlspecialchars($_POST['adds']);
            $phone=htmlspecialchars($_POST['phone']);
            $content=htmlspecialchars($_POST['content']);
            $insert=array(
                'name'=>$name,'adds'=>$adds,'phone'=>$phone,
                'notice'=>$content,
                'dates'=>date("Y-m-d H:i:s")
            );
            try{
                //$this->send_mail($insert);
                $recent=$this->db->insert('cart',$insert); 
                foreach($this->cart as $val){
                    $item=$this->db->where('id',$val['id'])->getOne('product','id,title,price,price_reduce');
                    $price=($item['price_reduce']>0&&$item['price_reduce']<$item['price'])?$item['price_reduce']:$item['price'];
                    $insert=array(
                        'cart_id'=>$recent,'product_id'=>$item['id'],'product_title'=>$item['title'],
                        'product_price'=>$price,
                        'product_qty'=>$val['qty']
                    );
                    $this->db->insert('cart_detail',$insert);    
                    $_SESSION['cart']=NULL;            
                }
                echo '
                    <script>
                        /*var msg="Thông tin của bạn đã được gửi đi, Chúng tôi sẽ liên lạc với bạn sớm nhất có thể, Xin cám ơn!";
                        $.jAlert({
                            "title":"Thông báo"
                            "content":"abc",
                            onClose:function(){
                                location.href="'.myWeb.'" 
                            }
                        }) */             
                        location.href="'.myWeb.'"         
                    </script>';
            }catch(Exception $e){
                echo $e->getErrorMessage;
            }
        }
        $str='
        <div class="col-md-6 clearfix">
            <p><i>Vui lòng điền thông tin giao hàng: </i></p>
            <form role="form" data-toggle="validator" method="post">
                <div class="form-group">
                    <input type="text" name="name" placeholder="Họ tên..." class="form-control" data-error="Vui lòng nhập họ tên" required/>
                    <div class="help-block with-errors"></div>
                </div>
                <div class="form-group">
                    <input type="text" name="adds" placeholder="Địa chỉ..." class="form-control" data-error="Vui lòng nhập địa chỉ của bạn" required/>
                    <div class="help-block with-errors"></div>
                </div>
                <div class="form-group">
                    <input type="text" name="phone" placeholder="Điện thoại..." class="form-control" data-error="Vui lòng nhập số phone của bạn" required/>
                    <div class="help-block with-errors"></div>
                </div>
                <div class="form-group">
                    <textarea name="content" placeholder="Ghi chú..." class="form-control" rows="3" data-error="Vui lòng nhập nội dung" required></textarea>
                    <div class="help-block with-errors"></div>
                </div>
                <button type="submit" name="contact_send" class="btn btn-default">Gửi</button> 
                <button type="reset" class="btn btn-default">Xoá</button>
            </form>
        </div>';
        return $str;
    }
    function cart_output($db){
        if(cart_update_multi($db)){
            $this->cart=$_SESSION['cart'];            
        }
        $str.='<form action="" method="post">';
        $str.='
        <section id="cart_items">
			<div class="table-responsive cart_info">
				<table class="table table-condensed table-hover">					
                                        <thead class="thead-default">
						<tr class="cart_menu">
							<th class="image">Hình Ảnh</th>
							<th class="description">Tên SP</th>
							<th class="price">Đơn Giá(VNĐ)</th>
							<th class="quantity">SL</th>
							<th class="total">Thành Tiền</th>
							<th class="action-clear">Xóa</th>
						</tr>
					</thead>
					<tbody>';
     $set=0;
     foreach($this->cart as $key=>$val){
        $item=$this->db->where('id',$val['id'])->getOne('product','id,title,price,price_reduce');
        common::load('product','page');
        $pd=new product($this->db);
        $img=$pd->first_image($val['id']);
        $lnk=myWeb.$this->lang.'/'.product_view.'/'.common::slug($item['title']).'-i'.$item['id'];
        $price=($item['price_reduce']>0&&$item['price_reduce']<$item['price'])?$item['price_reduce']:$item['price'];
        $total=$price*$val['qty'];
        $set+=$total;
        $str.='
        <tr>
			<td class="cart_product">
				<a href="'.$lnk.'"><img src="'.webPath.$img.'" class="img-responsive" style="max-width:50px" alt="" title=""/></a>
			</td>
			<td class="cart_description">
				<h4><a href="'.$lnk.'">'.$item['title'].'</a></h4>
			</td>
			<td class="cart_price">
				<p>'.number_format($price,0,',','.').'&nbsp;₫</p>
			</td>
			<td class="cart_quantity">
                            <div class="number-spinner-container">
                                <div class="input-group number-spinner ">
                                        <span class="input-group-btn">
                                                <button type="button" class="btn btn-default" data-dir="dwn"><span class="glyphicon glyphicon-minus"></span></button>
                                        </span>
                                        <input type="text" name="productItems['.$key.'][qty]" id="amount" class="form-control text-center" value="'.$val['qty'].'">
                                        <span class="input-group-btn">
                                                <button type="button" class="btn btn-default" data-dir="up"><span class="glyphicon glyphicon-plus"></span></button>
                                        </span>
                                </div>
                            </div>
				
			</td>
			<td class="cart_price">
				<p>'.number_format($total,0,',','.').'&nbsp;₫</p>
			</td>
			<td class="action-clear">
				<a href=""><i class="fa fa-trash-o"></i></a>
			</td>
                        <input type="hidden"  name="productItems['.$key.'][id]" value="'.$val['id'].'" />
		</tr>';
     }
     $str.='
					</tbody>
				</table>
			</div>    	
    	</section> <!--/#cart_items-->
    
    	<section id="do_action">			
			<div class="row ">
				<div class="col-sm-12">
                                <div class="total_area clearfix">
					<p class="pull-right">                                        
                                        <b>Tổng tiền: <span id="span-price">'.number_format($set,0,',','.').'&nbsp;₫</span></b>
                                        </p>
                                </div>
                        <div class="chose_area">						
    						<a class="btn btn-default btn-product" href="'.myWeb.'">Tiếp tục mua sắm</a>
                            <button type="submit"  name="submit" class="btn btn-default btn-product">Cập nhật</button>
							<a class="btn btn-default btn-product" href="'.myWeb.$this->lang.'/'.$this->view.'/'.payment_view.'">Gửi đơn hàng</a>
    					</div>							
					</div>
				</div>
			</div>
    	</section><!--/#do_action-->
        </form>';
        $str.='
        
        <script>
            $(document).on("click", ".number-spinner button", function () {    
            var btn = $(this),
                    oldValue = btn.closest(".number-spinner").find("input").val().trim(),
                    newVal = 0;

            if (btn.attr("data-dir") == "up") {
                    newVal = parseInt(oldValue) + 1;
            } else {
                    if (oldValue > 1) {
                            newVal = parseInt(oldValue) - 1;
                    } else {
                            newVal = 1;
                    }
            }
            btn.closest(".number-spinner").find("input").val(newVal);           

        });
            $("#size-product").change(function() {
                alert("tung" +$(this).val());
              });
        </script>';
        return $str;
    }
}