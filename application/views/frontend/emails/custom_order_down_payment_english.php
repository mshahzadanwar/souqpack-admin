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
		<img src="<?php echo base_url().'resources/emails/corde_partial_en.jpg';?>">
	</div>
	<div class="inner_content" style="float: left;width: 100%;padding: 23px 66px;box-sizing: border-box;">
		<div class="name" style="font-size: 16px;font-weight: bold;margin-bottom: 20px;float: left;width: 100%;">Welcome, <span style="color:#f1c30f;">[CUSTOMER_NAME]</span>
		</div>
		<div style="float: left; width: 100%; margin-bottom: 20px;">
			Finally, your own custom order [ORDER_NUMBER] acceptance is received, and
the production department is now planning your demand directly.
Please confirm the payment to start with your order in the production phase.
		</div>
		<div style="width: 100%; float: left;margin-bottom: 15px;text-align: right;">
			<a href="https://souqpack.com/#/view-custom-order/[ORDER_NUMBER_LINK]" target="_blank">
				<button type="button" style="background: #f1c30e;border: 1px solid #f1c30e;color: #fff;padding: 7px 20px;border-radius: 2px; cursor: pointer;">Click To Pay</button>
			</a>
		</div>
		<div style="width: 100%;float: left;">
			<div style="float: left;width: 8%; background: #7ecc20; padding: 5px;margin-right: 10px;"></div>
			<div style="float: left;width: 18%; background: #7ecc20; padding: 5px; margin-right: 10px;"></div>
			<div style="float: left;width: 8%; background: #7ecc20; padding: 5px;margin-right: 10px;"></div>
			<div style="float: left;width: 19%; background: #f8f8ef; padding: 5px;margin-right: 10px;"></div>
			<div style="float: left;width: 8%; background: #f8f8ef; padding: 5px;margin-right: 10px;"></div>
			<div style="float: left;width: 19%; background: #f8f8ef; padding: 5px;"></div>
		</div>


		<div style="float: left;width: 100%; margin-top: 15px; margin-bottom: 20px;">
				<span style="color: #7ecc20; font-weight: bold; float: left;">Waiting for Payment!</span>
		</div>
		<div style="float: left;width: 100%;">
			<div style="float: left;width: 42%; margin-right: 1%; background: #f6f6f6; padding: 15px 20px;height: 100px;">
				<b style="font-weight: bold; float: left;width: 100%; margin-bottom: 10px;">Orders Summary</b>
				<div style="float: left;width: 100%; margin-bottom: 10px;">
					<span style="float: left; margin-right: 20px; font-size: 11px;width: 40%;">Order Number: </span>
					<span style="float: left;font-size: 11px;">[ORDER_NUMBER]</span>
				</div>
				<div style="float: left;width: 100%;margin-bottom: 10px;">
					<span style="float: left; margin-right: 20px; font-size: 11px;width: 40%;">Amount To Pay: </span>
					<span style="float: left;font-size: 11px;">[AMOUNT_TO_PAY]</span>
				</div>
				<div style="float: left;width: 100%;margin-bottom: 10px;">
					<span style="float: left; margin-right: 20px; font-size: 11px;width: 40%;">Payment Method: </span>
					<span style="float: left;font-size: 11px;">Credit card</span>
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


		<div style="float: left;width: 100%; border-top: 1px solid #efefef;border-bottom: 1px solid #efefef; margin-top:10px">

			<p style="margin-bottom: 5px; margin-top: 0; font-size: 12px; float: left;width: 100%; text-align: center; padding-top: 10px;">
				<a href="[FB]" target="_blank">
					<img src="<?php echo base_url();?>resources/emails/fb.png" style="width: 35px;">
				</a>
				<a href="[TW]" target="_blank">
				<img src="<?php echo base_url();?>resources/emails/tw.png"  style="width: 35px;">
				</a>
				<a href="[IN]" target="_blank">
					<img src="<?php echo base_url();?>resources/emails/in.png"  style="width: 35px;">
				</a>
				<a href="[YT]" target="_blank">
					<img src="<?php echo base_url();?>resources/emails/yt.png"  style="width: 35px;">
				</a>
				<a href="[SC]" target="_blank">
					<img src="<?php echo base_url();?>resources/emails/sc.png"  style="width: 35px;">
				</a>
			</p>

			<p style="margin-bottom: 8px; margin-top: 0; font-size: 12px; float: left;width: 100%; text-align: center; padding-top: 10px;">You are receiving this email because <a href="mailto:[USER_EMAIL]">[USER_EMAIL]</a> registered on <a href="https://souqpack.com" target="_blank">souqpack.com</a></p>	
			<p style="margin-bottom: 8px; margin-top: 0; font-size: 12px; float: left;width: 100%; text-align: center; padding-top: 10px;">
				<img src="<?php echo base_url();?>resources/emails/badge-download-on-the-app-store.svg" style="width: 90px;">
				<img src="<?php echo base_url();?>resources/emails/google-play-badge.png"  style="width: 100px; border-radius: 5px;">
			</p>
			<p style="padding-bottom: 10px; margin-top: 10px; text-align: center; font-size: 11px;  float: left;width: 100%;">[COPYRIGHT]</p>
		</div>
		
	</div>
</div>
</div>
</body>