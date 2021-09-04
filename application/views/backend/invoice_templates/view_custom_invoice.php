<?php
$invoice_template = $this->db->where($where_invoice)->get('invoice_templates')->result_object()[0];
$order = $this->db->where($where)->get('c_orders')->result_object();

$order = $order[0];

$category = $this->db->query("SELECT * FROM categories WHERE id = ".$order->cat_id)->result_object()[0];
$user = $this->db->where('id',$order->user_id)->get("users")->result_object()[0];
    $loop_instance = str_replace(
        array(
            "[ITEM]",
            "[PRICE]",
            "[QTY]",
            "[SUBTOTAL]"

        ),
        array(
            $category->title."/".$order->c_title." (Custom Order)",
            number_format($order->all_total,2)." SAR ",
            $order->qty,
            number_format($order->all_total,2)." SAR "
        ),
        $invoice_template->products_list);
        $products_html .= $loop_instance;
        $name = $user->first_name.' '.$user->last_name;
        $name .= "<br>+".$user->code. ' '.$user->phone;
      
       
        if($down_remain == "down"){
            $order_status = "<span style='color:green;'>Down Payment Done</span>";
        } else {
            $order_status .= "<br><span style='color:green;'>Remaining Payment Done</span>";
        }

        // if($order->payment_done_part_2 == "1"){
        //     $order_status .= "<br><span style='color:green;'>Remaining Payment Done</span>";
        // } 
        // else {
        //     $order_status .= "<br><span style='color:purple;'>Remaining Payment Pending</span>";
        // }

        if($down_remain == "down"){
            $total = number_format($order->down_payment,2)."SAR (Down Payment)";
        }else{
            $total = number_format($order->all_total-$order->total_arrived,2)."SAR (Remaining Payment)";
        }
        


    
            $main_values = str_replace(
                array(
                    "[LOGO]",
                    "[INVOICENO]",
                    "[CREATEDAT]",
                    "[DUEAT]",
                    "[ADDRESS]",
                    "[NAME]",
                    "[EMAIL]",
                    "[ITEMSLIST]",
                    "[INVOICESTATUS]",
                    "[TOTAL]",
                    "[TAX]",
                    "[SHIPPING]",
                    "[GRANDTOTAL]",
                    "[TERMS_TEXT]"
                ),
                array(
                    base_url()."resources/uploads/logo/".$this->data["settings"]->site_logo,
                    "SOUQ".$order->id,
                    date("F d, Y",strtotime($order->created_at)),
                    date("F d, Y",strtotime($order->created_at)),
                    $address,
                    $name,
                    "",
                    $products_html,
                    $order_status,
                    $total." ".$order->currency,
                    0,
                    0,
                    $total,
                    $order->lang_id == 2 ?$invoice_template->description_en:$invoice_template->description_ar



                ),
                $invoice_template->content);
            echo $main_values;
?>

<?php //echo $invoce_template_content; ?>

<?php if(isset($_GET['type'])){?>
	<style type="text/css">
		body,html {
			font-family: arial;

		}
	.invoice-box {
	    background: #fff;
	    margin-top: 30px;
	    margin-bottom: 50px;
	}
	
	</style>
<?php } ?>
<style>
	.invoice_text {
        text-align: center;
	    margin-top: 18px;
	    font-size: 11px;
    }
	body,html {
			font-family: arial;
			font-size: 12px;
			padding: 0;
			 color: #555;
		}
		<?php if($show_border==1) {?>
		.td-text-right {width: 100%; margin:0 auto;}
		img {max-width:220px !important;}
		<?php } ?>
		.item {width: 100%;}
		@page {
            margin: 10mm;
            margin-header: 5mm;
            margin-footer: 5mm;
        }
        .heading {width: 100%; clear: both;    margin: 0 auto;}
        
    .invoice-box {
        
        
        
        <?php if($show_border!=1) {?>
	        box-shadow: 0 0 10px rgba(0, 0, 0, .15);
	        max-width: 800px;
         padding: 30px;
         border: 1px solid #eee;
         margin: auto;
    	<?php } else{ ?>
    		width:100%;
    	<?php } ?>
        font-size: 16px;
        line-height: 24px;
        
       
    }
    
    .invoice-box table {
        width: 100%;
        line-height: inherit;
        text-align: left;
    }
    
    .invoice-box table td {
        padding: 5px;
        vertical-align: top;
    }
    
    .invoice-box table tr td:nth-child(2) {
        text-align: right;
    }
    
    .invoice-box table tr.top table td {
        padding-bottom: 20px;
    }
    
    .invoice-box table tr.top table td.title {
        font-size: 45px;
        line-height: 45px;
        color: #333;
    }
    
    .invoice-box table tr.information table td {
        padding-bottom: 40px;
    }
    
    .invoice-box table tr.heading td {
        background: #eee;
        border-bottom: 1px solid #ddd;
        font-weight: bold;
    }
    
    .invoice-box table tr.details td {
        padding-bottom: 20px;
    }
    
    .invoice-box table tr.item td{
        border-bottom: 1px solid #eee;
    }
    
    .invoice-box table tr.item.last td {
        border-bottom: none;
    }
    
    .invoice-box table tr.total td:nth-child(2) {
        border-top: 2px solid #eee;
        font-weight: bold;
    }
    
    @media only screen and (max-width: 600px) {
        .invoice-box table tr.top table td {
            width: 100%;
            display: block;
            text-align: center;
        }
        
        .invoice-box table tr.information table td {
            width: 100%;
            display: block;
            text-align: center;
        }
    }
    
    /** RTL **/
    .rtl {
        direction: rtl;
        font-family: Tahoma, 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
    }
    
    .rtl table {
        text-align: right;
    }
    
    .rtl table tr td:nth-child(2) {
        text-align: left;
    }
    .td-text-right td{
        text-align: right;
    }
    </style>