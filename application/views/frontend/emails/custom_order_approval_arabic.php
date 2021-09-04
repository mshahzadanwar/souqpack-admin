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
		<img src="<?php echo base_url().'resources/emails/approval_custom_ar.jpg';?>" alt="order Confirmation">
	</div>
	<div class="inner_content" style="float: left;width: 100%;padding: 23px 66px;box-sizing: border-box;">
		<div class="name" style="text-align: right;font-size: 16px;font-weight: bold;margin-bottom: 20px;float: right;width: 100%;"><span style="color:#f1c30f;">[CUSTOMER_NAME]</span> هلا 
		</div>
		<div style="float: left; width: 100%; margin-bottom: 20px;text-align: right;direction: rtl;">
			لقد انتهينا من المواصفات الخاصة بك كما تم إرفاقها ونتطلع إلى
قبول طلبك [ORDER_NUMBER] لإكمال عملية تطوير الطلب
		</div>
		
		<div style="width: 100%;float: left;">
			<div style="float: left;width: 8%; background: #7ecc20; padding: 5px;margin-right: 10px;"></div>
			<div style="float: left;width: 18%; background: #7ecc20; padding: 5px; margin-right: 10px;"></div>
			<div style="float: left;width: 8%; background: #f8f8ef; padding: 5px;margin-right: 10px;"></div>
			<div style="float: left;width: 19%; background: #f8f8ef; padding: 5px;margin-right: 10px;"></div>
			<div style="float: left;width: 8%; background: #f8f8ef; padding: 5px;margin-right: 10px;"></div>
			<div style="float: left;width: 19%; background: #f8f8ef; padding: 5px;"></div>
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

			<p style="margin-bottom: 8px; margin-top: 0; font-size: 12px; float: left;width: 100%; text-align: center; padding-top: 10px;direction: rtl;">انت تتلقي هذا البريد الإلكتروني لأن <a href="mailto:[USER_EMAIL]">[USER_EMAIL]</a> مسجل علي <a href="https://souqpack.com" target="_blank">souqpack.com</a></p>	
			<p style="margin-bottom: 8px; margin-top: 0; font-size: 12px; float: left;width: 100%; text-align: center; padding-top: 10px;direction: rtl;">
				<img src="<?php echo base_url();?>resources/emails/badge-download-on-the-app-store.svg" style="width: 90px;">
				<img src="<?php echo base_url();?>resources/emails/google-play-badge.png"  style="width: 100px; border-radius: 5px;">
			</p>
			<p style="padding-bottom: 10px; margin-top: 10px; text-align: center; font-size: 11px;  float: left;width: 100%;">[COPYRIGHT]</p>
		</div>
	</div>
</div>
</div>
</body>