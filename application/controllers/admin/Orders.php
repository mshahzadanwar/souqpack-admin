<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Twilio\Rest\Client;
class Orders extends ADMIN_Controller {

	function __construct()
	{
		parent::__construct();
		auth();
        $this->redirect_role(14);

        $this->data['active'] = 'orders';
        $this->load->model('orders_model','order');
	}

	public function index()
	{

		$this->data['title'] = 'Orders';
        $this->data['sub'] = 'orders';
        $this->data['js'] = 'listing';
        $this->data['jsfile'] = 'js/orders_listing';
        $this->data['orders'] = $this->order->get_all_orders();
		$this->data['content'] = $this->load->view('backend/orders/listing',$this->data,true);
		$this->load->view('backend/common/template',$this->data);

	}
    public function trash()
    {

        $this->data['title'] = 'Trash Orders';
        $this->data['sub'] = 'trash';
        $this->data['js'] = 'listing';
        $this->data['jsfile'] = 'js/orders_listing';
        $this->data['orders'] = $this->order->get_all_trash_orders();
        $this->data['content'] = $this->load->view('backend/orders/trash',$this->data,true);
        $this->load->view('backend/common/template',$this->data);

    }
    public function restore($id){
        
        $dbData['deleted_by'] = $this->session->userdata('admin_id');
        $dbData['is_deleted'] = 0;
        $this->db->where('id',$id);
        $this->db->update('orders', $dbData);
        $this->session->set_flashdata('msg', 'order restored successfully!');
        redirect('admin/trash-orders');
    }

	public function add (){

	    $this->form_validation->set_rules('title','Title','trim|required|alpha_numeric_spaces|callback_check_order');
	    //$this->form_validation->set_rules('description','Description','trim|required');
	    $this->form_validation->set_rules('image','Image','callback_image_not_required[image,200,200]');
        $this->form_validation->set_message('required','This field is required.');
        $this->form_validation->set_message('alpha_numeric_spaces','Only alphabet and numbers are allowed.');
	    if($this->form_validation->run() === false){
            $this->data['title'] = 'Add New Order';
            $this->data['sub'] = 'add-order';
            $this->data['meta'] = $this->load->view('backend/common/meta_data',$this->data,true);
            $this->data['content'] = $this->load->view('backend/orders/add',$this->data,true);
            $this->load->view('backend/common/template',$this->data);
        }else{

	        $dbData['title'] = $this->input->post('title');
	        $dbData['slug'] = slug($this->input->post('title'));
	        $dbData['description'] = $this->input->post('description');
	        $dbData['meta_title'] = $this->input->post('meta_title');
	        $dbData['meta_keywords '] = $this->input->post('meta_keys');
	        $dbData['meta_description'] = $this->input->post('meta_desc');
	        $dbData['created_at'] = date('Y-m-d H:i:s');
	        $dbData['created_by'] = $this->session->userdata('admin_id');
	        $dbData['updated_at'] = date('Y-m-d H:i:s');
            $dbData['updated_by'] = $this->session->userdata('admin_id');


            if((isset($_FILES['image']) && $_FILES['image']['size'] > 0))
	        $image = $this->image_upload('image','./resources/uploads/orders/','jpg|jpeg|png|gif');
	        if($image['upload'] == true || $_FILES['image']['size']<1){
                $image = $image['data'];
                if((isset($_FILES['image']) && $_FILES['image']['size'] > 0)){
                    $dbData['image'] = $image['file_name'];
                    $this->image_thumb($image['full_path'],'./resources/uploads/orders/actual_size/',200,200);
                }
                $this->db->insert('orders',$dbData);
                $this->session->set_flashdata('msg','New order added successfully!');
                redirect('admin/orders');
            }else{
                print_r($image);exit;

	            $this->session->set_flashdata('err','An Error occurred durring uploading image, please try again');
	            redirect('admin/add-order');
            }

        }
    }

    public function check_order($title){

	    $result = $this->order->get_order_by_title($title);
	    if(!empty($title)) {
            if ($result->num_rows() > 0) {
                $this->form_validation->set_message('check_order', 'This order already exist.');
                return false;
            } else {
                return true;
            }
        }else{
            $this->form_validation->set_message('check_order', 'This field is required.');
            return false;
        }
    }


public function refund_status($id,$status){

        $result = $this->db->query("SELECT * FROM refund WHERE id = ".$id)->result_object()[0];
        $user_logged = $this->db->where("id",$result->uID)->get("users")->result_object()[0];

        if(!$result){

            $this->session->set_flashdata('err','Invalid request.');
            redirect('admin/404_page');

        }

        if($status==1)
        {
            $new_text = "Your Refund Request has been accepted by SouqPack";
        }

         if($status==2)
        {
            $new_text = "Amount has been transferred against your Refund Request";
        }

         if($status==3)
        {
            $new_text = "Refund Request has been completed, and amount has been transferred to your bank account";
        }
         if($status==4)
        {
            $new_text = "Your Refund Request rejected by SouqPack. Reason:(".$this->input->post('reason_rejection').") ";
            $dbData['cancel_reason'] = $this->input->post('reason_rejection');
        }

        if($user_logged->phone!=""){
        // SEND SMS 
            $send_mobile_desc = "SouqPack - ".$new_text;
            $phone_sms = "+".$user_logged->code.$user_logged->phone;
            $this->send_sms_to_users($phone_sms,$send_mobile_desc);
        }
        // SEND TO CUSTOMER PUSH NOTIFS
        if($user_logged->push_id!=""){
            
            // $notif["data"] = (Object) array();
            $notif["data"] = (Object) array("type"=>"order", "id"=>$order_details->id);
            $notif["tag"] = "Updates";
            $notif["title"] = "Refund Status Update";
            $notif["msg"]   = $new_text." against order #".$result->pID;          
            try{
                push_notif($user_logged->push_id,$notif);
            }
            catch(Exception $e)
            {

            }
        }

        $mmmsg = "Hi ".$user_logged->first_name. ' '.$user_logged->last_name;
        $mmmsg .= $new_text;
        $mmmsg .="<br><b>Order ID:</b> #" .$order_id;
        
        $this->load->library('email');

        $this->email->from(settings()->email, 'SouqPack');
        $this->email->to($user_logged->email);
        $this->email->set_mailtype("html");
        $this->email->subject("Refund Request Status Update");
        $this->email->message($mmmsg);

        $x = $this->email->send();

        $dbData['status'] = $status;
        
        $this->db->where('id',$id);
        $this->db->update('refund',$dbData);
        
        $this->session->set_flashdata('msg','Refund Request status updated successfully!');
        redirect('admin/refund-request');
    }

    public function send_sms_to_users($phone=0,$desc=""){
        $twillio_db = $this->db->where("id",1)->get("settings")->result_object()[0];
        $sid = $twillio_db->twillio_pub;
        $token = $twillio_db->twillio_sec;
    
        $twilio = new Client($sid, $token);
        try{
            $message = $twilio->messages
                          ->create($phone, // to
                                   ["body" => $desc, "from" => "+13233100736", "locale" => "ar"]
                          );
        }catch(\Exception $e) { return get_class($e); }
        // $message = $twilio->messages
        //                   ->create($phone, // to
        //                            ["body" => $desc, "from" => "+13233100736"]
        //                   );
        //print($message->sid);
    }
    public function status($id,$status){

        $result = $this->order->get_order_by_id($id);
        $user_logged = $this->db->where("id",$result->user_id)->get("users")->result_object()[0];

        if(!$result){

            $this->session->set_flashdata('err','Invalid request.');
            redirect('admin/404_page');

        }

        if($status==6)
        {
            $dbData["payment_done"]=1;
        }



        


        $order_details = $this->db->query("SELECT * FROM orders WHERE id = '".$id."'")->result_object()[0];
            $user_details = $this->db->query("SELECT * FROM users WHERE id = '".$order_details->user_id."'")->result_object();
            if(!empty($user_details) || $user_details[0]->email != ""){

                $email_user = $user_details[0]->email;

                // SEND EMAIL IF SUCCCESS
                 $fromemail=settings()->email;
                 $toemail = $email_user;
                
                if($order_details->from_web_mobile == "1"){
                    $user_name = $user_details[0]->first_name." ".$user_details[0]->last_name;
                } else {
                     if($user_details[0]->first_name!=""){
                        $user_name = $user_details[0]->first_name." ".$user_details[0]->last_name;
                    }else {
                       $user_name = $user_details[0]->phone;
                    }
                }
                $order_date = date("F, d Y", strtotime(date("Y-m-d")));
                $order_id_email = "#".$order_details->id;
                $total_amount = $order_details->total." ".$order_details->currency;
                $shipping_cust_json = json_decode($order_details->address_text);
                $shipping_cust_name = $shipping_cust_json->firstname." ".$shipping_cust_json->lastname;
                $ship_address = $shipping_cust_json->address.", ".$shipping_cust_json->city;
                $shiping_cost = $order_details->shipping_fee." ".$order_details->currency;

                $this->data["products_order"] = $this->db->query("SELECT * FROM order_products WHERE `order_id` = '".$order_details->id."'")->result_object();


                if($status == 4){
                    $subject = "Order Shipped #".$order_details->id;
                    if($order_details->lang_id == 1){
                        $mesg = $this->load->view('frontend/emails/order_shipped_ar',$this->data,true);
                    }else {
                        $mesg = $this->load->view('frontend/emails/order_shipped_english',$this->data,true);
                    }

                    $this->db->query("UPDATE orders SET shipping_number = '".$this->input->post("shipp_num")."' WHERE id = ".$order_details->id);
                }

                // IF ORDER IS DELIVERED AND COMPLETED
                 if($status == 6){
                    $subject = "Order Completed #".$order_details->id;
                    if($order_details->lang_id == 1){
                        $mesg = $this->load->view('frontend/emails/order_completed_ar',$this->data,true);
                    }else {
                        $mesg = $this->load->view('frontend/emails/order_completed_english',$this->data,true);
                    }
                }
                $souq = '<a href="https://souqpack.com" style="color:yellow;">SouqPack</a>';
                $search  = array('[CUSTOMER_NAME]','[ORDER_DATE]','[ORDER_NUMBER]','[TOTAL_AMOUNT]','[SHIPPING_CUSTOMER_NAME]','[SHIP_ADDR]','[SUB_TOTAL]','[SHIPPING_COST]','[SHIPMENT_NUMBER]',"SouqPack","Souqpack");
                $replace = array($user_name, $order_date, $order_id_email, $total_amount, $shipping_cust_name,$ship_address,$total_amount,$shiping_cost,$this->input->post("shipp_num"),$souq,$souq);
                $new_html = str_replace($search,$replace,$mesg);
                
                $config=array(
                'charset'=>'UTF-8',
                'wordwrap'=> TRUE,
                'mailtype' => 'html'
                );

                $this->load->library('email', $config);
                $this->email->set_newline("\r\n");

                //$this->email->initialize($config);

                $this->email->to($toemail);
                $this->email->from($fromemail, 'SouqPack');
                $this->email->subject($subject);
                $this->email->message($new_html);
                if($status == 4 || $status == 6){
                    $mail = $this->email->send();
                }
            }

            if($status==3)
            {
                $new_text = "has been accepted, SouqPack is preparing your items";
                $new_text_ar = "تم قبوله ، تقوم SouqPack بإعداد أغراضك";
            }

             if($status==4)
            {
                $new_text = "has been shipped by SouqPack. Shipping #".$this->input->post('shipp_num');
                $new_text_ar = "تم شحنه بواسطة SouqPack. الشحن # ". $this->input->post('shipp_num');
            }

             if($status==7)
            {
                $new_text = "has been canceled by the admin";
                $new_text_ar = "تم إلغاؤه من قبل المشرف";
            }
             if($status==6)
            {
                $new_text = "has been completed, thank you for shopping with SouqPack";
                $new_text_ar = "اكتمل ، شكرًا لك على التسوق مع SouqPack";
            }

            if($user_details[0]->phone!=""){
                // SEND SMS 
                if($order_details->lang_id == 1){
                    $send_mobile_desc = "SouqPack - طلبك   ".$order_id_email." ".$new_text_ar;
                } else {
                    $send_mobile_desc = "SouqPack - Your order ".$order_id_email." ".$new_text;
                }
                $phone_sms = "+".$user_details[0]->code.$user_details[0]->phone;
                $this->send_sms_to_users($phone_sms,$send_mobile_desc);
            }
            // SEND TO CUSTOMER PUSH NOTIFS
            if($user_details[0]->push_id!=""){
                if($order_details->lang_id == 1){
                    $push_text =  "طلبك   ".$order_id_email." ".$new_text_ar;
                }else {
                   $push_text =  "Your order ".$order_id_email." ".$new_text;
                }
                $notif["data"] = (Object) array("type"=>"order", "id"=>$order_details->id);
                $notif["tag"] = "Updates";
                $notif["title"] = "Order Status Update";
                $notif["msg"]   = "Your order ".$order_id_email." ".$new_text;          
                try{
                    push_notif($user_details[0]->push_id,$notif);
                }
                catch(Exception $e)
                {

                }
            }

        // $mmmsg = "Hi ".$user_logged->first_name. ' '.$user_logged->last_name;
        // $mmmsg .= $new_text;
        // $mmmsg .="<br><b>Order ID:</b> #00P" .$order_id;
        


        // $this->load->library('email');

        // $this->email->from(settings()->email, 'SouqPack');
        // $this->email->to($user_logged->email);
        // $this->email->set_mailtype("html");
        // $this->email->subject("New Order Received");
        // $this->email->message($mmmsg);

        // $x = $this->email->send();


        $dbData['status'] = $status;
        $dbData['updated_at'] = date('Y-m-d H:i:s');
        $dbData['updated_by'] = $this->session->userdata('admin_id');

        $this->db->where('id',$id);
        $this->db->update('orders',$dbData);
        $this->session->set_flashdata('msg','order status updated successfully!');
        redirect('admin/orders');
    }

    public function edit($id){

        $result = $this->order->get_order_by_id($id);

        if(!$result){

            $this->session->set_flashdata('err','Invalid request.');
            redirect('admin/404_page');
        }

        $this->data['data'] = $result;
        $this->form_validation->set_rules('title','Title','trim|required|alpha_numeric_spaces|callback_check_order_edit['.$id.']');
        //$this->form_validation->set_rules('description','Description','trim|required');
        $this->form_validation->set_rules('image','Image','callback_image_not_required[image,200,200]');
        $this->form_validation->set_message('required','This field is required.');
        $this->form_validation->set_message('alpha_numeric_spaces','Only alphabet and numbers are allowed.');
        if($this->form_validation->run() === false){
            $this->data['title'] = 'Edit Order';
            $this->data['meta'] = $this->load->view('backend/common/meta_data',$this->data,true);
        
            $this->data['content'] = $this->load->view('backend/orders/edit',$this->data,true);
             
            $this->load->view('backend/common/template',$this->data);
        }else{

            $dbData['title'] = $this->input->post('title');
            $dbData['description'] = $this->input->post('description');
            $dbData['meta_title'] = $this->input->post('meta_title');
            $dbData['meta_keywords '] = $this->input->post('meta_keys');
            $dbData['meta_description'] = $this->input->post('meta_desc');
            $dbData['updated_at'] = date('Y-m-d H:i:s');
            $dbData['updated_by'] = $this->session->userdata('admin_id');

            if(!empty($_FILES['image']['name'])) {
                unlink('./resources/uploads/orders/'.$this->data['data']->image);
                unlink('./resources/uploads/orders/actual_size/'.$this->data['data']->image);
                $image = $this->image_upload('image', './resources/uploads/orders/', 'jpg|jpeg|png|gif');
                if ($image['upload'] == true) {
                    $image = $image['data'];
                    $dbData['image'] = $image['file_name'];
                    $this->image_thumb($image['full_path'], './resources/uploads/orders/actual_size/', 1400, 438);
                } else {

                    $this->session->set_flashdata('err', 'An Error occurred durring uploading image, please try again');
                    redirect('admin/add-order');
                }
            }
            $this->db->where('id',$id);
            $this->db->update('orders', $dbData);
            $this->session->set_flashdata('msg', 'order updated successfully!');
            redirect('admin/orders');

        }
    }

    public function check_order_edit($title,$id){

        $result = $this->order->get_order_by_title($title);
        if(!empty($title)) {
            if ($result->num_rows() > 0) {
                $result = $result->row();
                if($result->id == $id){
                    return true;
                }else{
                    $this->form_validation->set_message('check_order_edit', 'This order already exist.');
                    return false;
                }
            } else {
                return true;
            }
        }else{
            $this->form_validation->set_message('check_order_edit', 'This field is required.');
            return false;
        }
    }

    public function delete($id){
        $result = $this->order->get_order_by_id($id);

        if(!$result){

            $this->session->set_flashdata('err','Invalid request.');
            redirect('admin/404_page');

        }
        $dbData['deleted_by'] = $this->session->userdata('admin_id');
        $dbData['is_deleted'] = 1;
        $this->db->where('id',$id);
        $this->db->update('orders', $dbData);
        $this->session->set_flashdata('msg', 'order deleted successfully!');
        redirect('admin/orders');
    }

    public function set_filter(){
        $_SESSION['filter_web'] = 1;
        $_SESSION['status_web_change'] = $_POST['status_web_change'];
        $_SESSION['status_payment'] = $_POST['status_payment'];
        $this->redirect_back();
    }
    public function reset_filter(){
        unset($_SESSION['status_web_change']);
        unset($_SESSION['filter_web']);
        unset($_SESSION['status_payment']);
        $this->redirect_back();
    }
    public function set_refund_filter(){
        $_SESSION['filter_web_ref'] = 1;
        $_SESSION['status_refund'] = $_POST['status_refund'];
        $this->redirect_back();
    }
    public function reset_filter_ref(){
        unset($_SESSION['filter_web_ref']);
        unset($_SESSION['status_refund']);
        $this->redirect_back();
    }
    
    public function refund_requests(){
        $this->data['title'] = 'Refund Requests';
        $this->data['sub'] = 'refund';
        $this->data['js'] = 'listing';
        $this->data['jsfile'] = 'js/orders_listing';
        if(isset($_SESSION['filter_web_ref'])){
            $query = $this->db->query("SELECT * FROM refund WHERE status =".$_SESSION['status_refund']);
        }
        else {
            $query = $this->db->query("SELECT * FROM refund");
        }
        
        $this->data['refund'] = $query->result_object();
        $this->data['content'] = $this->load->view('backend/refund/listing',$this->data,true);
        $this->load->view('backend/common/template',$this->data);
    }
}
