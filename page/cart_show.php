<?php
class cart_show{
    private $cart;
    function __construct($db,$view='gio-hang',$lang='vi'){
        $this->db=$db;
        $this->cart=$_SESSION['cart'];
        $this->view=$view;
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
    function cart_output(){
        $str.='
        <section id="cart_items">
			<div class="table-responsive cart_info">
				<table class="table table-condensed">
					<thead>
						<tr class="cart_menu">
							<td class="image">Hình Ảnh</td>
							<td class="description">Tên SP</td>
							<td class="price">Đơn Giá(VNĐ)</td>
							<td class="quantity">SL</td>
							<td class="total">Thành Tiền</td>
							<td></td>
						</tr>
					</thead>
					<tbody>';
     $set=0;
     foreach($this->cart as $val){
        $item=$this->db->where('id',$val['id'])->getOne('product','id,title,price,price_reduce');
        common::load('product','page');
        $pd=new product($this->db);
        $img=$pd->first_image($val['id']);
        $price=($item['price_reduce']>0&&$item['price_reduce']<$item['price'])?$item['price_reduce']:$item['price'];
        $total=$price*$val['qty'];
        $set+=$total;
        $str.='
        <tr>
			<td class="cart_product">
				<a href=""><img src="'.webPath.$img.'" class="img-responsive" style="max-width:50px" alt="" title=""/></a>
			</td>
			<td class="cart_description">
				<h4><a href="">'.$item['title'].'</a></h4>
			</td>
			<td class="cart_price">
				<p>'.number_format($price,0,',','.').'</p>
			</td>
			<td class="cart_quantity">
				<div class="cart_quantity_button">
					<a class="cart_quantity_up" href=""> + </a>
					<input class="cart_quantity_input" type="text" name="quantity" value="'.$val['qty'].'" autocomplete="off" size="2">
					<a class="cart_quantity_down" href=""> - </a>
				</div>
			</td>
			<td class="cart_price">
				<p>'.number_format($total,0,',','.').'</p>
			</td>
			<td class="cart_delete">
				<a class="cart_quantity_delete" href=""><i class="fa fa-times"></i></a>
			</td>
		</tr>';
     }
     $str.='
					</tbody>
				</table>
			</div>    	
    	</section> <!--/#cart_items-->
    
    	<section id="do_action">
			<div class="heading" style="margin-bottom:5px">
				<h3>Bạn muốn làm gì tiếp theo?</h3>
			</div>
			<div class="row">
				<div class="col-sm-12">
					<div class="total_area">
						<ul>
							<li>Tổng tiền <span>'.number_format($set,0,',','.').' VNĐ</span></li>
						</ul>
                        <div class="chose_area">						
    						<a class="btn btn-default check_out" href="'.myWeb.'">Tiếp tục mua sắm</a>
                            <a class="btn btn-default update" href="">Cập nhật</a>
							<a class="btn btn-default check_out" href="'.myWeb.$this->view.'/thanh-toan">Gửi đơn hàng</a>
    					</div>							
					</div>
				</div>
			</div>
    	</section><!--/#do_action-->';
        return $str;
    }
}