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
.table {font-size: 11px; width: 100%;margin-left: -15px;direction: rtl;}
.table td {}
.table th {     text-align: right;
    font-size: 11px; padding-top: 6px;
    width: 47%;
    font-weight: normal; }
    .table_ar {font-size: 11px; width: 100%;margin-left: -15px;direction: rtl;}
   .table_ar th {     text-align: right;
    font-size: 11px; padding-top: 15px;
    width: 47%;
    font-weight: normal; }
</style>
<body>
<div class="mainbox" style="display: flex;justify-content: center;width: 100%;align-items: center;flex-direction: column;">
<div class="wrap" style="width: 793px;">
	<div class="header">
		<img src="<?php echo base_url().'resources/emails/corde_receive_ar.jpg';?>" alt="order received">
	</div>
	<div class="inner_content" style="float: left;width: 100%;padding: 23px 66px;box-sizing: border-box;">
		<div class="name" style="text-align: right;font-size: 16px;font-weight: bold;margin-bottom: 20px;float: right;width: 100%;"><span style="color:#f1c30f;">[CUSTOMER_NAME]</span> هلا 
		</div>
		<div style="float: left; width: 100%; margin-bottom: 20px;text-align: right;">
			يعمل قسم التصميم على طلبك[ORDER_NUMBER] كما طلبت
<br>نقوم بإرسال تصميمك الخاص خلال مدة أقصاها 5 ساعات لإعتمادها معك بنجاح
		</div>
		<div style="width: 100%;float: left;">
			<div style="float: left;width: 8%; background: #7ecc20; padding: 5px;margin-right: 10px;"></div>
			<div style="float: left;width: 18%; background: #f8f8ef; padding: 5px; margin-right: 10px;"></div>
			<div style="float: left;width: 8%; background: #f8f8ef; padding: 5px;margin-right: 10px;"></div>
			<div style="float: left;width: 19%; background: #f8f8ef; padding: 5px;margin-right: 10px;"></div>
			<div style="float: left;width: 8%; background: #f8f8ef; padding: 5px;margin-right: 10px;"></div>
			<div style="float: left;width: 19%; background: #f8f8ef; padding: 5px;"></div>
		</div>
		<div style="float: right;width: 100%; margin-top: 10px; margin-bottom: 30px; text-align: right;">
				<span style="color: #7ecc20; font-weight: bold; float: right;">تم استلام طلبكم ,  وتخصيصه للمصمم</span>
		</div>
		<div style="float: left;width: 100%;">
			<div style="float: right;width: 42%; margin-left: 1%; background: #f6f6f6; padding: 15px 20px; text-align: right;" >
				<b style="font-weight: bold; float: left;width: 100%; margin-bottom: 10px;">ملخص الطلب</b>
				<div style="float: left;width: 100%; margin-bottom: 3px;">
					<span style="float: right; margin-right: 20px; font-size: 11px;width: 40%;">:رقم الطلبية </span>
					<span style="float: right;font-size: 11px;">[ORDER_NUMBER]</span>
				</div>
				<div style="float: left;width: 100%;margin-bottom: 3px;">
					<span style="float: right; margin-right: 20px; font-size: 11px;width: 40%;">:إجمالي الطلبية </span>
					<span style="float: right;font-size: 11px; direction: rtl;">[TOTAL_AMOUNT]</span>
				</div>
				<div style="float: left;width: 100%;">
					<span style="float: right;font-size: 11px; width: 100%;">[CUSTOM_DETAILS]</span>
				</div>
			</div>
			<div style="float: right;width: 42%; margin-left: 1%; background: #f6f6f6; padding: 15px 20px;height: 310px;">
				<b style="font-weight: bold; float: right;width: 100%; margin-bottom: 10px;     text-align: right;">معلومات الدفع</b>
				<div style="float: left;width: 100%; margin-bottom: 10px;">
					<span style="float: right;    text-align: right; margin-right: 20px; font-size: 11px; font-weight:bold;width: 100%;">[PAYEMNT_INFOR]</span>
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