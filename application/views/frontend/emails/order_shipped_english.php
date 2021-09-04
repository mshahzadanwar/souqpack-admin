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
<div class="mainbox" style="display: flex;justify-content: center;width: 793px;align-items: center;flex-direction: column;">
<div class="wrap" style="width: 793px;">
	<div class="header">
		<img src="<?php echo base_url().'resources/emails/header_shipped_en.jpg';?>">
	</div>
	<div class="inner_content" style="float: left;width: 100%;padding: 23px 66px;box-sizing: border-box;">
		<div class="name" style="font-size: 16px;font-weight: bold;margin-bottom: 20px;float: left;width: 100%;">Welcome <span style="color:#f1c30f;">[CUSTOMER_NAME]</span>
		</div>
		<div style="float: left; width: 100%; margin-bottom: 20px;">
			Great News, your order from Souqpack No [ORDER_NUMBER] have been Shipped !
		</div>
		<div style="width: 100%;float: left;">
			<div style="float: left;width: 8%; background: #7ecc20; padding: 5px;margin-right: 10px;"></div>
			<div style="float: left;width: 27%; background: #7ecc20; padding: 5px; margin-right: 10px;"></div>
			<div style="float: left;width: 34%; background: #7ecc20; padding: 5px;margin-right: 10px;"></div>
			<div style="float: left;width: 20%; background: #f8f8ef; padding: 5px;"></div>
		</div>
		<div style="float: left;width: 100%; margin-top: 15px; margin-bottom: 20px;">
				<span style="color: #7ecc20; font-weight: bold; float: left;">Shipping</span>
				<span style="float: left;margin-left: 10px;"> [ORDER_DATE]</span>

		</div>
		<div style="float: left;width: 100%;">
			<div style="float: left;width: 42%; margin-right: 1%; background: #f6f6f6; padding: 15px 20px;height: 100px;">
				<b style="font-weight: bold; float: left;width: 100%; margin-bottom: 10px;">Orders Summary</b>
				<div style="float: left;width: 100%; margin-bottom: 10px;">
					<span style="float: left; margin-right: 20px; font-size: 11px;width: 40%;">Order Number: </span>
					<span style="float: left;font-size: 11px;">[ORDER_NUMBER]</span>
				</div>
				<div style="float: left;width: 100%;margin-bottom: 10px;">
					<span style="float: left; margin-right: 20px; font-size: 11px;width: 40%;">Shipment Number: </span>
					<span style="float: left;font-size: 11px;">[SHIPMENT_NUMBER]</span>
				</div>
			</div>
			<div style="float: left;width: 42%; margin-left: 1%; background: #f6f6f6; padding: 15px 20px;height: 100px;">
				<b style="font-weight: bold; float: left;width: 100%; margin-bottom: 10px;">Shipping Address</b>
				<div style="float: left;width: 100%; margin-bottom: 10px;">
					<span style="float: left; margin-right: 20px; font-size: 11px; font-weight:bold;width: 100%;">[SHIPPING_CUSTOMER_NAME]</span>
				</div>
				<div style="float: left;width: 100%;margin-bottom: 15px;">
					<span style="float: left; margin-right: 20px; font-size: 11px;width: 100%;">[SHIP_ADDR]</span>
				</div>
			</div>
		</div>

		<div style="float: left;width: 100%">
			<div style="float: left;width: 99%; border: 1px solid #fff; background: #fff; padding: 2px 10px; font-size: 11px;box-sizing: border-box;margin: 10px 0;display: flex;align-items: center;">
				<img src="<?php echo base_url().'resources/emails/safe_green.jpg';?>"> Keep the Environment Green, No invoice will be sent with your order, Download a copy from your account if necessary
			</div>
		</div>


		<div style="float: left;width: 100%">
			<div style="border-top: 1px solid #ccc;border-bottom: 1px solid #ccc; padding: 10px; font-weight: bold; text-transform: uppercase; font-size: 16px; margin-bottom: 20px;">
				ORDERD ITEMS
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

		<div style="float: left;width: 100%;">
			<div style="float: right; width: 300px;">
				<div style="float: left;width: 100%;clear: both;font-size: 13px; margin-bottom: 10px;">
					<span style="float: left;width: 190px; font-weight: bold;">
						Sub Total
					</span>
					<span style="float: left;width: 100px;text-align: right;">
						[SUB_TOTAL]
					</span>
				</div>
				<div style="float: left;width: 100%;clear: both;font-size: 13px; margin-bottom: 10px;">
					<span style="float: left;width: 190px; font-weight: bold;">
						Shipping Cost
					</span>
					<span style="float: left;width: 100px;text-align: right;">
						[SHIPPING_COST]
					</span>
				</div>
				<div style="float: left;width: 100%;clear: both;font-size: 13px; margin-bottom: 10px; border-top: 1px solid #f0f0f0;padding-top: 6px;">
					<span style="float: left;width: 190px; font-weight: bold;">
						Total (vat)
					</span>
					<span style="float: left;width: 100px;text-align: right;">
						[TOTAL_AMOUNT]
					</span>
				</div>
			</div>
		</div>



		<div style="float: left;width: 100%; border-top: 1px solid #efefef;border-bottom: 1px solid #efefef; margin-top:10px">
			<p style="margin-bottom: 8px; margin-top: 0; font-size: 13px; float: left;width: 100%; padding-top: 10px;">For your safety and for a better Delivery , Kindly Follow healthy and safety procedures</p>
			<p style="margin-bottom: 8px;margin-top: 0; font-size: 13px; float: left;width: 100%; color: #9a9a9a;">Our Faculties are sterilized and keeping hygienic protocols in place around the clock</p>
			
		</div>
		<div style="float: left;width: 100%">
			<div style="float: left;width: 99%; border: 1px solid #ccc; background: #f8f8ef; padding: 10px 20px; font-size: 11px;box-sizing: border-box;margin: 10px 0;">
				Saudi Arabia Government has raised Vat to 15 % Starting July 2020, Accordingly Souqpack will add Vat amounts on your orders, Some items
on your order may not include vat amounts, how ever Total amount shall include all added Vat amounts
			</div>
		</div>
		<div style="float: left;width: 100%; border-top: 1px solid #efefef;border-bottom: 1px solid #efefef;">
			<p style="margin-bottom: 8px; font-size: 13px;margin-top: 0; float: left;width: 100%; padding-top: 10px;">Till your order arrives, Stay safe , Stay Home</p>
			<p style="margin-bottom: 8px; font-size: 13px;margin-top: 0; float: left;width: 100%;">Did you like the product you purchases, Share Order photos on Instagram #Souqpack_Packaging</p>
			<p style="margin-bottom: 8px; font-size: 13px;margin-top: 0; float: left;width: 100%;">Always welcome for Shopping from souqpack, Visit us again soon.</p>
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