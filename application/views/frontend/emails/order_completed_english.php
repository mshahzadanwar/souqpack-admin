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
		<img src="<?php echo base_url().'resources/emails/header_completed_en.jpg';?>">
	</div>
	<div class="inner_content" style="float: left;width: 100%;padding: 23px 66px;box-sizing: border-box;">
		<div class="name" style="font-size: 16px;font-weight: bold;margin-bottom: 20px;float: left;width: 100%;">Welcome <span style="color:#f1c30f;">[CUSTOMER_NAME]</span>
		</div>
		<div style="float: left; width: 100%; margin-bottom: 20px;">
			[ORDER_NUMBER] Your order has been Delivered Successfully
		</div>
		<div style="width: 100%;float: left;">
			<div style="float: left;width: 8%; background: #7ecc20; padding: 5px;margin-right: 10px;"></div>
			<div style="float: left;width: 27%; background: #7ecc20; padding: 5px; margin-right: 10px;"></div>
			<div style="float: left;width: 34%; background: #7ecc20; padding: 5px;margin-right: 10px;"></div>
			<div style="float: left;width: 20%; background: #7ecc20; padding: 5px;"></div>
		</div>
		<div style="float: left;width: 100%; margin-top: 15px; margin-bottom: 20px;">
				<span style="color: #7ecc20; font-weight: bold; float: left;">Delivered</span>
				<span style="float: left;margin-left: 10px;"></span>

		</div>
		<div style="float: left;width: 100%;">
			<div style="float: left;width: 42%; margin-right: 1%; background: #f6f6f6; padding: 15px 20px;height: 100px;">
				<b style="font-weight: bold; float: left;width: 100%; margin-bottom: 10px;">Orders Summary</b>
				<div style="float: left;width: 100%; margin-bottom: 10px;">
					<span style="float: left; margin-right: 20px; font-size: 12px;width: 40%;">Order Number: </span>
					<span style="float: left;font-size: 11px;">[ORDER_NUMBER]</span>
				</div>
				<div style="float: left;width: 100%;margin-bottom: 10px;">
					<span style="float: left; margin-right: 20px; font-size: 12px;width: 40%;">Total: </span>
					<span style="float: left;font-size: 11px;">[TOTAL_AMOUNT]</span>
				</div>
				<div style="float: left;width: 100%;margin-bottom: 10px;">
					<span style="float: left; margin-right: 20px; font-size: 12px;width: 40%;">Payment Method: </span>
					<span style="float: left;font-size: 11px;">Credit card</span>
				</div>
			</div>
			<div style="float: left;width: 42%; margin-left: 1%; background: #f6f6f6; padding: 15px 20px;height: 100px;">
				<b style="font-weight: bold; float: left;width: 100%; margin-bottom: 10px;">Shipping Address</b>
				<div style="float: left;width: 100%; margin-bottom: 10px;">
					<span style="float: left; margin-right: 20px; font-size: 12px; font-weight:bold;width: 100%;">[SHIPPING_CUSTOMER_NAME]</span>
				</div>
				<div style="float: left;width: 100%;margin-bottom: 15px;">
					<span style="float: left; margin-right: 20px; font-size: 12px;width: 100%;">[SHIP_ADDR]</span>
				</div>
			</div>
		</div>



		<div style="float: left;width: 100%">
			<div style="border-top: 1px solid #ccc;border-bottom: 1px solid #ccc; padding: 10px; font-weight: bold; text-transform: uppercase; font-size: 16px; margin-bottom: 20px;">
				Confirmed ITEMS
			</div>
		</div>
		<?php 
			foreach ($products_order as $key => $order) {
				$product = $this->db->query("SELECT * FROM products WHERE id = '".$order->product_id."' AND lang_id = 2")->result_object()[0];
				if($product->title == ""){
					$product = $this->db->query("SELECT * FROM products WHERE id = '".$order->product_id."' AND lang_id = 2")->result_object()[0];
				}
		?>
			<div style="float: left;width: 100%; border-bottom: 1px solid #ccc; padding-bottom: 10px; margin-bottom: 10px;">
				<div style="float: left; width: 100px; margin-right: 10px;">
					<img src="<?php echo base_url().'resources/uploads/products/'.$product->image;?>" style="height: 75px;">
				</div>
				<div style="float: left; width: 425px; margin-right: 20px; font-size: 12px;">
					<span style="float: left;width: 100%; margin-bottom: 10px;">
						<?php echo $product->title;?>
					</span>
					<span style="float: left;width: 100%; margin-bottom: 5px;">
						Quantity : <?php echo $order->qty;?>
					</span>
					<!-- <span style="float: left;width: 100%; margin-bottom: 5px; color: #7ecc20">
						Received : Dec 20, 2020
					</span> -->
				</div>
				<div style="float: left; width: 80px; margin-right: 20px; font-size: 12px;">
					<?php echo $order->price;?> SAR
				</div>
			</div>
		<?php } ?>

		



		<div style="float: left;width: 100%; border-top: 1px solid #efefef;border-bottom: 1px solid #efefef; margin-top:10px">
			<p style="margin-bottom: 8px; margin-top: 0; font-size: 13px; float: left;width: 100%; padding-top: 10px;">Did you like the product you purchases, Share Order photos on Instagram #Souqpack_Packaging</p>
			<p style="margin-bottom: 8px; margin-top: 0; font-size: 13px; float: left;width: 100%; ">Always welcome for Shopping from souqpack, Visit us again soon</p>
			
		</div>
		
		<div style="float: left;width: 100%">
			<div style="float: left;width: 99%; border: 1px solid #efefef; background: #f8f8ef; padding: 10px 20px; font-size: 11px;box-sizing: border-box;margin: 10px 0;">
				<p style="margin-bottom: 8px; margin-top: 0; font-size: 13px; font-weight: bold; float: left;width: 100%;">Thank you</p>
				<p style="margin-bottom: 0; margin-top: 0; font-size: 13px; font-weight: bold; float: left;width: 100%;">Souqpack Team</p>
			</div>
		</div>
	</div>
</div>
</div>
</body>