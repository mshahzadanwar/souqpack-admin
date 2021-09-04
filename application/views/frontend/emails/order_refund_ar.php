<style type="text/css">
@import url('https://fonts.googleapis.com/css2?family=Roboto&display=swap');
@import url('https://fonts.googleapis.com/css2?family=Cairo:wght@300&display=swap');
body {
	background: #ffffff none repeat scroll 0 0;
	overflow-x: hidden;
    color: #353535;
	font-family: 'Roboto', 'Cairo' !important;
    font-size: 15px;
    padding: 0;
    margin: 0;
    box-sizing: border-box;

}
</style>
<body>
<div class="mainbox" style="display: flex;justify-content: center;width: 100%;align-items: center;flex-direction: column;">
<div class="wrap" style="width: 793px;">
	<div class="header">
		<img src="<?php echo base_url().'resources/emails/header_refund_ar.jpg';?>" alt="order received">
	</div>
	<div class="inner_content" style="float: left;width: 100%;padding: 23px 66px;box-sizing: border-box;">
		<div class="name" style="text-align: right;font-size: 16px;font-weight: bold;margin-bottom: 20px;float: right;width: 100%;"><span style="color:#f1c30f;">[CUSTOMER_NAME]</span> هلا 
		</div>
		<div style="float: left; width: 100%; margin-bottom: 20px;text-align: right;direction: rtl;">
			لقد تلقينا طلب الإرجاع الخاص بك
 [REFUND_NUMBER] سيتصل بك شخص من فريقنا لترتيب عملية الأستلام
لا تنس أن تبقي المنتجات جاهزة في عبواتها الأصلية فى وقت الأستلام


		</div>
		
		<div style="float: left;width: 100%;">
			<div style="float: right;width: 42%; margin-left: 1%; background: #f6f6f6; padding: 15px 20px;height: 100px; text-align: right;" >
				<b style="font-weight: bold; float: left;width: 100%; margin-bottom: 10px;">طلب الإرجاع</b>
				<div style="float: left;width: 100%; margin-bottom: 5px;">
					<span style="float: right; margin-right: 20px; font-size: 11px;width: 40%;">:رقم الطلبية </span>
					<span style="float: right;font-size: 11px;">[ORDER_NUMBER]</span>
				</div>
				<div style="float: left;width: 100%;margin-bottom: 5px;">
					<span style="float: right; margin-right: 20px; font-size: 11px;width: 40%;">:رقم الشحنة
</span>
					<span style="float: right;font-size: 11px;">[SHIPMENT_NUMBER]</span>
				</div>
				
			</div>
			<div style="float: right;width: 42%; margin-left: 1%; background: #f6f6f6; padding: 15px 20px;height: 100px;">
				<b style="font-weight: bold; float: right;width: 100%; margin-bottom: 10px;     text-align: right;">مكان الأستلام</b>
				<div style="float: left;width: 100%; margin-bottom: 10px;">
					<span style="float: right;    text-align: right; margin-right: 20px; font-size: 11px; font-weight:bold;width: 100%;">[SHIPPING_CUSTOMER_NAME]</span>
				</div>
				<div style="float: left;width: 100%;margin-bottom: 15px;">
					<span style="float: right;    text-align: right; margin-right: 20px; font-size: 11px;width: 100%;">[SHIP_ADDR]</span>
				</div>
			</div>
		</div>
		
		<?php 
			foreach ($products_order as $key => $order) {
				$product_or_en = $this->db->query("SELECT * FROM products WHERE id = '".$order->product_id."'")->result_object()[0];
				$product = $this->db->query("SELECT * FROM products WHERE lparent = '".$order->product_id."' AND lang_id = 1")->result_object()[0];
				
				if($product->title == ""){
					$product = $this->db->query("SELECT * FROM products WHERE id = '".$order->product_id."'")->result_object()[0];
				}
		?>
		<div style="float: left;width: 100%; border-bottom: 1px solid #ccc; padding-bottom: 10px; margin-bottom: 10px;">
			<div style="float: right; width: 100px; margin-right: 10px;    text-align: right;">
				<img src="<?php echo base_url().'resources/uploads/products/'.$product_or_en->image;?>" style="height: 75px;">
			</div>
			<div style="float: right; width: 425px; margin-right: 20px; font-size: 12px; text-align: right;">
				<span style="float: right;width: 100%; margin-bottom: 10px;">
					<?php echo $product->title;?>
					<?php if($order->variation!=""){?>
						<br>
						<?php echo substr($order->variation,1,-1);?>
					<?php } ?>
				</span>
				<span style="float: right;width: 100%; margin-bottom: 5px;">
					 الحد الأدنى للكمية: <?php echo $order->qty;?>
				</span>
				<!-- <span style="float: right;width: 100%; margin-bottom: 5px; color: #7ecc20">
					Received : Dec 20, 2020
				</span> -->
			</div>
			<div style="float: right; width: 80px; margin-right: 20px; font-size: 12px;direction: rtl;">
				<?php echo $order->price;?> رس
			</div>
		</div>
		<?php } ?>
		

		

		<div style="float: left;width: 100%; border-top: 1px solid #efefef;border-bottom: 1px solid #efefef;    text-align: right;">
			
			<p style="margin-bottom: 8px; font-size: 13px;margin-top: 0; float: left;width: 100%;  padding-top: 10px; direction: rtl;">إذا لم تطلب هذا أو احتجت إلى مزيد من المعلومات، فأتصل بفريق خدمة العملاء الودود لدينا على
 [CONTACT_NUMBER]</p>
			<p style="margin-bottom: 8px;margin-top: 0; font-size: 13px; float: left;width: 100%; direction: rtl;">أو تواصل معانا على  [EMAIL_ADDR_COMPANY]</p>
			
		</div>

		<div style="float: left;width: 100%">
			<div style="float: left;width: 99%; border: 1px solid #efefef; background: #f8f8ef; padding: 10px 20px; font-size: 11px;box-sizing: border-box;margin: 10px 0;    text-align: right;">
				<p style="margin-bottom: 8px; margin-top: 0; font-size: 13px; font-weight: bold; float: left;width: 100%; direction: rtl;">شكرا،</p>
				<p style="margin-bottom: 0; margin-top: 0; font-size: 13px; font-weight: bold; float: left;width: 100%;">فريق سوق باك</p>
			</div>
		</div>
	</div>
</div>
</div>
</body>