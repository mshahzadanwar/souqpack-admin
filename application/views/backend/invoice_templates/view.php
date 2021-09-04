<?php
	
            $invoice_template = $this->db->where($where_invoice)->get('invoice_templates')->result_object()[0];
            //$invoice_template = $this->data['invoice_template'];
            
            $order = $this->db->where($where)->get('orders')->result_object();

            if(empty($order))
            {
                redirect(base_url()."admin");
                exit;
            }
            $order = $order[0];
            $products_order = $this->db->where('order_id',$order->id)->get('order_products')->result_object();


            $user = $this->db->where('id',$order->uid)->get("users")->result_object()[0];

            $prod_ids = array();

            foreach($products_order as $product){
                $prod_ids[] = $product->product_id;

            }

            $products = $this->db->where_in('id',$prod_ids)->where('is_deleted',0)->get('products')->result_object();

            $products_html = "";
            $total = 0;
            foreach($products as $key=>$product)
            {
                $products_order_dis = $this->db->where('order_id',$order->id)->where('product_id',$product->id)->get('order_products')->result_object()[0];
                $region_price = $this->db->where("region_id",$order->region_id)->where('product_id',$product->id)->get("product_units")->result_object()[0];
               
                
               
                if($product->discount_type==1)
                {
                    $discount = $product->discount;
                    $discount_text = $discount." ".$order->currency;
                }
                if($product->discount_type==2)
                {
                    $discount_text = $product->discount."%";

                    $discount = ($product->discount/100) * $region_price->price;
                }

                if($product->discount_type==0)
                {
                    $discount = 0;
                    $discount_text = "N/A";
                }
                // $sub_total = ($region_price->price - $discount ) * $products_order[$key]->qty;
                $sub_total = $products_order[$key]->price * $products_order[$key]->qty;

                $total += $sub_total;
                $varisss = substr($products_order_dis->variation, 1, -1);
                //$varisss =  preg_replace('/[{}]/', '', $varisss);
                //$varisss =  str_replace(search, replace, subject)
                $loop_instance = str_replace(
                    array(
                        "[ITEM]",
                        "[PRICE]",
                        "[QTY]",
                        // "[DISCOUNT]",
                        "[SUBTOTAL]"

                    ),
                    array(
                        $product->title."<br>".$varisss,
                        $products_order[$key]->price." ".$order->currency,
                        $products_order[$key]->qty,
                        // $discount_text,
                        $sub_total." ".$order->currency
                    ),
                    $invoice_template->products_list);

                $products_html .= $loop_instance;
            }

            $data_object = json_decode($order->address_text);

            if($data_object->id!="")
            {
                $name = $data_object->first_name.' '.$data_object->last_name;
                $address = "<br>".$data_object->street. ", ".$data_object->city;
                $address .= "<br>".$data_object->state. ", ".$data_object->zip;
                $address .= "<br>".$data_object->country;
            }

            
            else
            {
                $name = $data_object->firstname.' '.$data_object->lastname;
                $address = $data_object->address. ', '.$data_object->address_2;
                $address .= "<br>".$data_object->city. ", ".$data_object->state;
                $address .= "<br>".$data_object->zip. ", ".$data_object->country;
            }

            if($order->status == "8"){
                $reund = $this->db->query("SELECT * FROM refund WHERE pID = ".$order->id)->result_object()[0];
                if($reund->status == 0){$sts_ref = "(RECEIVED)";}
                if($reund->status == 1){$sts_ref = "(REFUND REQUEST ACCEPTED)";}
                if($reund->status == 2){$sts_ref = "(MONEY TRANSFERRED)";}
                if($reund->status == 3){$sts_ref = "(REQUEST COMPLETED)";}
                if($reund->status == 4){$sts_ref = "(REFUND REQUEST DECLINED)";}

                $order_status = "<br><span style=''><i style='color:red;'>Refund Request Initiated By User</i> <br> <b>REASON:</b><br>".$reund->refund_reason."</span><br><br>
                Refund Status:<br>".$sts_ref;
            } else {
            if($order->status == "7"){
                $order_status = "<br><span style='color:red;'>Order Cancelled By User <br> <b>REASON:</b><br>".$order->reason."</span>";
            } else{
                if($order->payment_done == "1"){
                    $order_status = "<span style='color:green;'>Payment Done</span>";
                } else if($order->payment_done == "2"){
                    $order_status = "<span style='color:red;'>Payment Error<br>".$order->payment_reason_rejct."</span>";
                }else {
                    $order_status = "<span style='color:purple;'>Pending</span>";
                }
            }
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
                    $order->tax." ".$order->currency,
                    $order->shipping_fee." ".$order->currency,
                    ($total + $order->tax + $order->shipping_fee)." ".$order->currency,
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