<?php
defined('BASEPATH') OR exit('No direct script access allowed');
// use Kreait\Firebase\Factory;
// use Kreait\Firebase\ServiceAccount;
use Twilio\Rest\Client;

class Corders extends ADMIN_Controller {

    private $con;
    private $firebase;

	function __construct()
	{
		parent::__construct();
		auth();
        // $this->redirect_role(14);

        $this->data['active'] = 'corders';
        $this->load->model('corders_model','corder');
        $this->load->model("Staff_model");


        try {
            // $this->con = new PDO('mysql:host=localhost;dbname=phpfirebase', 'root', '');
            // $this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // $this->con->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            // This assumes that you have placed the Firebase credentials in the same directory
            // as this PHP file.
            // $serviceAccount = ServiceAccount::fromJsonFile('./my-test-app-91f99-firebase-adminsdk-tftew-34a984e387.json');

            // $this->firebase = (new Factory)
            //      ->withServiceAccount($serviceAccount)
            //      ->create();
        } catch (PDOException $e) {
            // echo 'Exception -> ';
            // var_dump($e->getMessage());
        }
	}

	public function index()
	{

		$this->data['title'] = 'Custom Orders';
        $this->data['sub'] = 'corders';
        $this->data['js'] = 'listing';
        $this->data['jsfile'] = 'js/corders_listing';
        $this->data['corders'] = $this->corder->get_all_corders();
		$this->data['content'] = $this->load->view('backend/corders/listing',$this->data,true);
		$this->load->view('backend/common/template',$this->data);

	}
    // Designer task
    public function designer_tasks($type=0)
    {
        $this->data['active'] = 'my_tasks';
        $this->data['title'] = 'My Tasks';
        $this->data['sub'] = 'my_tasks';
        $this->data['js'] = 'listing';
        $this->data['jsfile'] = 'js/corders_listing';
        if(is_designer()){
            $this->data['tasks'] = $this->corder->get_designer_tasks($type);

            $this->data['content'] = $this->load->view('backend/corders/designer_tasks',$this->data,true);
        }
        
        else
        {
            $this->data['tasks'] = $this->corder->get_production_tasks($type);
            $this->data['content'] = $this->load->view('backend/corders/production_tasks',$this->data,true);

        }
 
        $this->load->view('backend/common/template',$this->data);

    }

    public function view($id)
    {

        $this->data['title'] = 'Custom Orders Details';
        $this->data['sub'] = 'corders';
        $this->data['js'] = 'listing';
        $this->data['jsfile'] = 'js/order_details';
        $this->data['order'] = $this->db->where("id",$id)->get("c_orders")->result_object()[0];
        $this->data['content'] = $this->load->view('backend/corders/details',$this->data,true);
        $this->load->view('backend/common/template',$this->data);


    }
    // Designer
    public function view_task($id)
    {

        $this->data['active'] = 'my_tasks';
        $this->data['title'] = 'View My Task';
        $this->data['sub'] = 'my_tasks';
        $this->data['js'] = 'listing';
        $this->data['jsfile'] = 'js/order_details';
        
        $this->data['task'] = $this->db->where("id",$id)->get("designer_tasks")->result_object()[0];
        
        $this->data['order'] = $this->db->where("id",$this->data['task']->order_id)->get("c_orders")->result_object()[0];

        if(!is_production()){
            $this->data['content'] = $this->load->view('backend/corders/details',$this->data,true);
        }
        else
        {

            $this->data['order'] = $this->db->where("id",$id)->get("c_orders")->result_object()[0];
            $this->data['content'] = $this->load->view('backend/corders/production_details',$this->data,true);
        }

        $this->load->view('backend/common/template',$this->data);

    }
    

    public function status($id,$status){

        $result = $this->corder->get_corder_by_id($id);
        $user_logged = $this->db->where("id",$result->user_id)->get("users")->result_object()[0];

        if(!$result){

            $this->session->set_flashdata('err','Invalid request.');
            redirect('admin/404_page');

        }

        if($status==6)
        {
            $dbData["payment_done"]=1;
        }



        if($status==3)
        {
            $new_text = "Your cOrder has been accepted, we're prepairing the items";
        }

         if($status==4)
        {
            $new_text = "Your cOrder has been shipped and you shall receive it within a week";
        }

         if($status==7)
        {
            $new_text = "Your cOrder has been cancelled";
        }
         if($status==6)
        {
            $new_text = "Your cOrder has been completed, thank you for shopping from SouqPack";
        }


        $mmmsg = "Hi ".$user_logged->first_name. ' '.$user_logged->last_name;
        $mmmsg .= $new_text;
        $mmmsg .="<br><b>cOrder ID:</b> #00P" .$corder_id;
        


        $this->load->library('email');

        $this->email->from(settings()->email, 'SouqPack');
        $this->email->to($user_logged->email);
        $this->email->set_mailtype("html");
        $this->email->subject("New cOrder Received");
        $this->email->message($mmmsg);

        $x = $this->email->send();


        $dbData['status'] = $status;
        $dbData['updated_at'] = date('Y-m-d H:i:s');
        $dbData['updated_by'] = $this->session->userdata('admin_id');

        $this->db->where('id',$id);
        $this->db->update('corders',$dbData);
        $this->session->set_flashdata('msg','Custom Order status updated successfully!');
        redirect('admin/corders');
    }

   

    public function delete($id){
        $this->db->where('id',$id);
        $this->db->delete('corders', $dbData);
        $this->session->set_flashdata('msg', 'Custom  deleted successfully!');
        redirect('admin/corders');
    }

    public function get_online_drivers()
    {
        $order_id = $this->input->post("order_id");
        
        $designers = $this->Staff_model->get_all_staff("designer");
        $this->data["designers"] = $designers;
        $this->data["order_id"] = $order_id;
        echo $this->load->view("backend/corders/online_designers",$this->data,true);
    }

    public function get_online_productions()
    {
        $order_id = $this->input->post("order_id");
        
        $designers = $this->Staff_model->get_all_staff("production");
        $this->data["designers"] = $designers;
        $this->data["order_id"] = $order_id;
        echo $this->load->view("backend/corders/online_designers",$this->data,true);
    }
    public function assign()
    {
        $submit_type = $this->input->post("submit_type");
        if($submit_type=="designer")
        {


            $order_id = $this->input->post("order_id");
            $designer_id = $this->input->post("designer_id");
            $deadline = $this->input->post("deadline");

            $order = $this->db->where("id",$order_id)->get("c_orders")->result_object()[0];
            $designer = $this->db->where("id",$designer_id)->get("admin")->result_object()[0];

            $user_push = $this->db->where("id",$order->user_id)->get("users")->result_object()[0];
            // echo "<pre>";
            // print_r($user_push->push_id);
            // die;
            if(!$order)
            {
                $this->session->set_flashdata('err', 'Order not found');
                $this->redirect_back();
                return;
            }

            if(!$designer)
            {
                $this->session->set_flashdata('err', 'Designer not found');
                $this->redirect_back();
                return;
            }

            $already_assigned = $this->db->where("order_id",$order_id)->where("designer_id",$designer_id)->get("designer_tasks")->result_object()[0];

            if($already_assigned)
            {
                $this->session->set_flashdata('err', 'Task is already assigned to this designer');
                $this->redirect_back();
                return;
            }

            $already_other = $this->db->where("order_id",$order_id)->get("designer_tasks")->result_object()[0];

            if($already_other)
            {
                $this->session->set_flashdata('err', 'Task is already assigned to some other designer');
                $this->redirect_back();
                return;
            }

            $new_task = array(
                "order_id"=>$order_id,
                "designer_id"=>$designer_id,
                "admin_id"=>$this->session->userdata("admin_id"),
                "deadline"=>$deadline,
                "created_at"=>date("Y-m-d H:i:s"),
                "status"=>0
            );

            $this->db->insert("designer_tasks",$new_task);

            $task_id = $this->db->insert_id();

            $this->db->where("id",$order_id)->update("c_orders",array(
                "admin_status"=>2, // assigned to designer
                "status"=>2 // for user, "Under work"
            ));

            // creating a thread
            $thread = array(
                "by"=>"Admin",
                "task_id"=>$task_id,
                "by_id"=>$this->session->userdata("admin_id"),
                "title"=>"Assigned the the order to ".$designer->name,
                "desc"=>"Task has been assigned, admin will set a deadline soon",
                "created_at"=>date("Y-m-d H:i:s")
            );

            $this->db->insert("task_threads",$thread);

             $thread = array(
                "by"=>"Admin",
                "task_id"=>$task_id,
                "by_id"=>$this->session->userdata("admin_id"),
                "title"=>"Fixed the deadline for designer to deliver before ".$deadline,
                "desc"=>"Deadline has been set, work shall be delivered before the assigned date",
                "created_at"=>date("Y-m-d H:i:s")
            );

            $this->db->insert("task_threads",$thread);


             $thread = array(
                "by"=>"Admin",
                "task_id"=>$order_id,
                "by_id"=>$this->session->userdata("admin_id"),
                "title"=>"Order Confirmed",
                "desc"=>"Your order is under design process now, it is expected to be delivered before ".$deadline,
                "title_ar"=>"تم تاكيد الطلب",
                "desc_ar"=>"طلبك قيد التصميم الآن ، ومن المتوقع تسليمه من قبل ".$deadline,
                "for_admin"=>1,
                "created_at"=>date("Y-m-d H:i:s")
            );

            $this->db->insert("corder_threads",$thread);

            // SEND TO CUSTOMER PUSH NOTIFS

            // $notif["data"] = (Object) array();
            $notif["data"] = (Object) array("type"=>"c_order", "id"=>$order_id);
            $notif["tag"] = "Updates";
            $notif["title"] ="Task Assigned to Designer";
            $notif["msg"]   = "Your order is under design process now, it is expected to be delivered before ".$deadline;          
                try{
                    push_notif($user_push->push_id,$notif);
                }
                catch(Exception $e)
                {
                    
                }
         

            $thread = array(
                "by"=>"Admin",
                "order_id"=>$order_id,
                "by_id"=>$this->session->userdata("admin_id"),
                "desc"=> "Admin has assigned you a new task #".$task_id,
                "for_designer"=>1,
                "created_at"=>date("Y-m-d H:i:s")
            );

              
            $this->db->insert("notifications",$thread);


            //SEND NOTIF HERE
            $this->session->set_flashdata('msg', 'Order assigned created successfully!');
            
        }
        else
        {
            $order_id = $this->input->post("order_id");
            $designer_id = $this->input->post("designer_id");
            $deadline = $this->input->post("deadline");

            $order = $this->db->where("id",$order_id)->get("c_orders")->result_object()[0];
            $designer = $this->db->where("id",$designer_id)->get("admin")->result_object()[0];

            if(!$order)
            {
                $this->session->set_flashdata('err', 'Order not found');
                $this->redirect_back();
                return;
            }

            if(!$designer)
            {
                $this->session->set_flashdata('err', 'Production mananger not found');
                $this->redirect_back();
                return;
            }

           
           

            $this->db->where("id",$order_id)->update("c_orders",
                array(
                    "production_id"=>$designer->id,
                    "production_status"=>0, // processing
                    "status"=>4, // sent to production
                    "admin_status"=>5, // sent to production,
                    "production_deadline"=>$deadline
                )
            );

             $thread = array(
                "by"=>"Admin",
                "task_id"=>$order_id,
                "by_id"=>$this->session->userdata("admin_id"),
                "title"=>"Sent order to production",
                "cdesc"=>"Admin has sent your order to production, it is expected to be delivered before ".$deadline,
                "desc"=>"You have sent this order to production, it is expected to be delivered before ".$deadline,
                "title_ar"=>"تم ارسال الطلب الى الانتاج",
                "desc_ar"=>"أرسل المسؤول طلبك إلى الإنتاج ، ومن المتوقع أن يتم تسليمه من قبل".$deadline,
                "for_admin"=>1,
                "created_at"=>date("Y-m-d H:i:s")
            );

            $this->db->insert("corder_threads",$thread);


            $thread = array(
                "by"=>"Admin",
                "order_id"=>$order_id,
                "by_id"=>$this->session->userdata("admin_id"),
                "desc"=> "Admin has assigned you a order #".$order_id,
                "for_production"=>1,
                "created_at"=>date("Y-m-d H:i:s")
            );

              
            $this->db->insert("notifications",$thread);

            //SEND NOTIF HERE
            $this->session->set_flashdata('msg', 'Order assigned created successfully!');
            
        }

         // SEND SMS 
            $order_id = $this->input->post("order_id");
            $order_details = $this->db->query("SELECT * FROM c_orders WHERE id = '".$order_id."'")->result_object()[0];
            $user_logged = $this->db->query("SELECT * FROM users WHERE id = '".$order_details->user_id."'")->result_object()[0];
            if($user_logged->phone!=""){

                if($submit_type=="designer")
                {
                    if($order_details->lang_id ==1){
                        $send_mobile_desc = "SouqPack - تم تعيين طلبك (# ".$order_id.") إلى المصمم. سنقوم بتحديثك بمجرد إنشاء التصميم حسب طلبك.";
                    }else{
                        $send_mobile_desc = "SouqPack - Your Order (#".$order_id.") has been assigned to designer. we will update you once design is created as per your request.";
                    }
                }
                else{
                    if($order_details->lang_id ==1){
                        $send_mobile_desc = "SouqPack - تم تأكيد الدفع الخاص بك وتم تعيين الطلب (# ".$order_id.") للإنتاج. سنقوم بتحديثك بمجرد حدوث بعض التقدم في طلبك.
";
                    }else{
                        $send_mobile_desc = "SouqPack - Your payment has been confirmed & Order (#".$order_id.") has been assigned to production. we will update you once there is some progress in your order.";
                    }
                }
                
                $phone_sms = "+".$user_logged->code.$user_logged->phone;
                $this->send_sms_to_users($phone_sms,$send_mobile_desc);
            }
            $this->redirect_back();
    }


    public function reject_delivery()
    {
        $thread_id = $this->input->post("thread_id");
        $task_id = $this->input->post("task_id");
        $reason = $this->input->post("reason");

        $task = $this->db->where("id",$task_id)->get("designer_tasks")->result_object()[0];
        $order = $this->db->where("id",$task->order_id)->get("c_orders")->result_object()[0];
        $designer = $this->db->where("id",$task->designer_id)->get("admin")->result_object()[0];

        if(!$order)
        {
            $this->session->set_flashdata('err', 'Order not found');
            $this->redirect_back();
            return;
        }

        if(!$designer)
        {
            $this->session->set_flashdata('err', 'Designer not found');
            $this->redirect_back();
            return;
        }

        

       

        $new_task = array(
            "status"=>3
        );

        $this->db->where("id",$task_id);
        $this->db->update("designer_tasks",$new_task);



        $this->db->where("id",$order->id)->update("c_orders",array(
            "admin_status"=>2, // assigned to designer
        ));




        $this->db->where("id",$thread_id)->update("task_threads",array(
            "status"=>2, // rejectred
        ));

        // creating a thread
        $thread = array(
            "by"=>"Admin",
            "task_id"=>$task_id,
            "title"=>"Rejected the delivery",
            "by_id"=>$this->session->userdata("admin_id"),
            "status"=>2,
            "parent_id"=>$thread_id,
            "desc"=>$reason,
            "created_at"=>date("Y-m-d H:i:s")
        );



        $this->db->insert("task_threads",$thread);



        // creating a thread
        $thread = array(
            "by"=>"Admin",
            "task_id"=>$task_id,
            "title"=>"Rejected the delivery",
            "by_id"=>$this->session->userdata("admin_id"),
            "status"=>2,
            "desc"=>"Reason: ".$reason,
            "created_at"=>date("Y-m-d H:i:s")
        );



        $this->db->insert("task_threads",$thread);


        $thread = array(
            "by"=>"Admin",
            "order_id"=>$order->id,
            "by_id"=>$this->session->userdata("admin_id"),
            "desc"=> "Admin has rejectd your delivery for task #".$task_id,
            "for_designer"=>1,
            "created_at"=>date("Y-m-d H:i:s")
        );

              
        $this->db->insert("notifications",$thread);
        //SEND NOTIF HERE
        $this->session->set_flashdata('msg', 'Delivery rejected successfully!');
        $this->redirect_back();
    }

    public function re_assign($order_id)
    {
        

        $order = $this->db->where("id",$order_id)->get("c_orders")->result_object()[0];

        $task = $this->db->where("order_id",$order_id)->get("designer_tasks")->result_object()[0];

        $task_id = $task->id;


        $designer = $this->db->where("id",$task->designer_id)->get("admin")->result_object()[0];

        $last_delivery_thread = $this->db->where("status",1)->where("task_id",$task->id)->order_by("id","DESC")->get("task_threads")->result_object()[0];
        if($last_delivery_thread->parent_id!=0)
        {
            $last_delivery_thread = $this->db->where("id",$last_delivery_thread->id)->get("task_threads")->result_object()[0];
        }

        $thread_id = $last_delivery_thread->id;

        if(!$order)
        {
            $this->session->set_flashdata('err', 'Order not found');
            $this->redirect_back();
            return;
        }

        if(!$designer)
        {
            $this->session->set_flashdata('err', 'Designer not found');
            $this->redirect_back();
            return;
        }

        

       

        $new_task = array(
            "status"=>3
        );

        $this->db->where("id",$task_id);
        $this->db->update("designer_tasks",$new_task);



        $this->db->where("id",$order->id)->update("c_orders",array(
            "admin_status"=>2, // assigned to designer
            "status"=>2, // assigned to designer
        ));




        $this->db->where("id",$thread_id)->update("task_threads",array(
            "status"=>2, // rejectred
        ));

        // creating a thread
        $thread = array(
            "by"=>"Admin",
            "task_id"=>$task_id,
            "title"=>"Re-Assigned the task",
            "by_id"=>$this->session->userdata("admin_id"),
            "status"=>2,
            "parent_id"=>$thread_id,
            "desc"=>"User has asked for modifications",
            "created_at"=>date("Y-m-d H:i:s")
        );



        $this->db->insert("task_threads",$thread);



        // creating a thread
        $thread = array(
            "by"=>"Admin",
            "task_id"=>$task_id,
            "title"=>"Re-Assigned the task",
            "by_id"=>$this->session->userdata("admin_id"),
            "status"=>2,
            "desc"=>"User has asked for modifications",
            "created_at"=>date("Y-m-d H:i:s")
        );



        $this->db->insert("task_threads",$thread);



        $thread = array(
            "by"=>"Admin",
            "task_id"=>$order->id,
            "by_id"=>$this->session->userdata("admin_id"),
            "title"=>"Delivery changes accepted",
            "desc"=>"Your order is under design process now, it is expected to be delivered soon ",
            "title_ar"=>"قبول تغييرات التسليم",
            "desc_ar"=>"طلبك قيد التصميم الآن ، ومن المتوقع تسليمه قريبًا",
            "for_admin"=>1,
            "created_at"=>date("Y-m-d H:i:s")
        );

        $this->db->insert("corder_threads",$thread);


        $thread = array(
            "by"=>"Admin",
            "order_id"=>$order->id,
            "by_id"=>$this->session->userdata("admin_id"),
            "desc"=> "Admin has Re-Assigned you the task #".$task_id,
            "for_designer"=>1,
            "created_at"=>date("Y-m-d H:i:s")
        );

              
        $this->db->insert("notifications",$thread);


        //SEND NOTIF HERE
        $this->session->set_flashdata('msg', 'Delivery re assigned successfully!');
        $this->redirect_back();
    }

    public function send_to_production($order_id)
    {
        

        $order = $this->db->where("id",$order_id)->get("c_orders")->result_object()[0];

        // $task = $this->db->where("order_id",$order_id)->get("designer_tasks")->result_object()[0];

        // $task_id = $task->id;


        // $designer = $this->db->where("id",$task->designer_id)->get("admin")->result_object()[0];

        // $last_delivery_thread = $this->db->where("status",1)->where("task_id",$task->id)->order_by("id","DESC")->get("task_threads")->result_object()[0];
        // if($last_delivery_thread->parent_id!=0)
        // {
        //     $last_delivery_thread = $this->db->where("id",$last_delivery_thread->id)->get("task_threads")->result_object()[0];
        // }

        // $thread_id = $last_delivery_thread->id;

        if(!$order)
        {
            $this->session->set_flashdata('err', 'Order not found');
            $this->redirect_back();
            return;
        }

        if(!$designer)
        {
            $this->session->set_flashdata('err', 'Designer not found');
            $this->redirect_back();
            return;
        }

        

       

        // $new_task = array(
        //     "status"=>3
        // );

        // $this->db->where("id",$task_id);
        // $this->db->update("designer_tasks",$new_task);



        $this->db->where("id",$order->id)->update("c_orders",array(
            "admin_status"=>5, // in production
            "status"=>9, // assigned to designer
        ));




        // $this->db->where("id",$thread_id)->update("task_threads",array(
        //     "status"=>2, // rejectred
        // ));

        // creating a thread
        // $thread = array(
        //     "by"=>"Admin",
        //     "task_id"=>$task_id,
        //     "title"=>"Re-Assigned the task",
        //     "by_id"=>$this->session->userdata("admin_id"),
        //     "status"=>2,
        //     "parent_id"=>$thread_id,
        //     "desc"=>"User has asked for modifications",
        //     "created_at"=>date("Y-m-d H:i:s")
        // );



        // $this->db->insert("task_threads",$thread);



        // // creating a thread
        // $thread = array(
        //     "by"=>"Admin",
        //     "task_id"=>$task_id,
        //     "title"=>"Re-Assigned the task",
        //     "by_id"=>$this->session->userdata("admin_id"),
        //     "status"=>2,
        //     "desc"=>"User has asked for modifications",
        //     "created_at"=>date("Y-m-d H:i:s")
        // );



        // $this->db->insert("task_threads",$thread);



        $thread = array(
            "by"=>"Admin",
            "task_id"=>$order->id,
            "by_id"=>$this->session->userdata("admin_id"),
            "title"=>"Delivery changes accepted",
            "desc"=>"Your order is under design process now, it is expected to be delivered soon ",
            "title_ar"=>"قبول تغييرات التسليم",
                "desc_ar"=>"طلبك قيد التصميم الآن ، ومن المتوقع تسليمه قريبًا",
            "for_admin"=>1,
            "created_at"=>date("Y-m-d H:i:s")
        );

        $this->db->insert("corder_threads",$thread);


        //SEND NOTIF HERE
        $this->session->set_flashdata('msg', 'Delivery re assigned successfully!');
        $this->redirect_back();
    }

    public function update_production_status()
    {

        $order_id = $this->input->post("order_id");
        $status = $this->input->post("status");

        $order = $this->db->where("id",$order_id)->get("c_orders")->result_object()[0];
       

        if(!$order)
        {
            $this->session->set_flashdata('err', 'Order not found');
            $this->redirect_back();
            return;
        }

        

       // echo $status;

        $ar = array(
            "production_status"=>$status, // sent to user
        );

        if($status==4)
        {
            $ar["admin_status"]=10;
            $ar["status"]=7;
        }

        $title = "has updated the order status";
        $title_ar = "قام بتحديث حالة الطلب";

        if($status==1)
        {
            $cdesc = "production has started preparing  your order";
            $desc = "production has started preparing  this order";
            $desc_ar = "بدأ الإنتاج في إعداد طلبك";
        }

        if($status==2)
        {


            $input = "production_file";
            $file_name = $_FILES[$input]['name'];
            if(isset($_FILES[$input]) && $_FILES[$input]["size"]>0){
                $image = $this->file_upload_func($input,'./resources/uploads/orders/','jpg|jpeg|png|gif');
                if($image['upload'] == true){
                    $file = $image['data']["file_name"];
                    $is_file = 1;
                    $dbData["filename"]=$file_name;
                    $dbData["file"]=$file;
                    $dbData["is_image"] = $image["data"]["is_image"];
                    $dbData["file_type"] = $image["data"]["file_ext"];
                    $dbData["file_size"] = $image["data"]["file_size"];
                    $dbData["type_production"]=1;
                    $dbData["task_id"]=$order_id;
                    $dbData["title"]="attached a picture";
                    $dbData["desc"]="production has sent you a picture of ready to deliver package";
                    $dbData["cdesc"]="admin has sent you a picture of ready to deliver package";
                    $dbData["title_ar"] = "إرفاق صورة";
                    $dbData["desc_ar"]  = "أرسل لك المشرف صورة جاهزة لتسليم الحزمة";
                    $dbData["by"]="Production";
                    $dbData["only_admin"]=1;
                    $dbData["for_admin"]=1;
                    $dbData["by_id"]=$this->session->userdata("admin_id");
                    $dbData["created_at"]= date("Y-m-d H:i:s");

                    $this->db->insert("corder_threads",$dbData);

                 }else{
                    
                     $this->session->set_flashdata('err',$image['data']);
                        

                     $this->redirect_back();
                    return;
                }
            }

            // $ar["admin_status"]=7;

            $cdesc = "your order is ready to be delivered, please clear your remaining payments";
            $desc = "production has completed the work, awaiting payment from user to deliver this order";
            $desc_ar = "طلبك جاهز للتسليم ، يرجى مسح المدفوعات المتبقية";

 
            //SEND CUSTON ORDER EMAIL RECEIVED
            $order_id = $order_id;
            $subject = "Custom Order Final Payment Pending #";
            $view_ar = "custom_order_final_payment_ar";
            $view_en = "custom_order_final_payment_english";
            $this->email_custom_sent_souqpack($order_id,$subject,$view_ar,$view_en);
        }

        if($status==3)
        {
            $ar["admin_status"]=8;
            $cdesc = "your order has been sent for delivery";
            $desc = "production has sent this order for delivery";
            $desc_ar = "تم إرسال طلبك للتسليم";

            //SEND CUSTON ORDER EMAIL RECEIVED
            $order_id = $order_id;
            $subject = "Custom Order Shipped #";
            $view_ar = "custom_order_shipped_ar";
            $view_en = "custom_order_shipped_english";
            $this->email_custom_sent_souqpack($order_id,$subject,$view_ar,$view_en);
        }

        if($status==4)
        {
            $ar["admin_status"]=9;
            $title = "Order Completed";
            $cdesc = "your order has been successfully completed, thank you for shopping with us";
            $desc = "this order has been completed successfully";
            $title_ar = "تم اكتمال الطلب";
            $desc_ar = "تم إكمال طلبك بنجاح ، شكرًا لك على التسوق معنا";
        }

        $this->db->where("id",$order->id)->update("c_orders",$ar);

        // creating a thread
        $thread = array(
            "by"=>"Production",
            "task_id"=>$order_id,
            "title"=>$title,
            "by_id"=>$this->session->userdata("admin_id"),
            "desc"=>$desc,
            "cdesc"=>$cdesc,
            "title_ar"=>$title_ar,
            "desc_ar"=>$desc_ar,
            "for_admin"=>1,
            "created_at"=>date("Y-m-d H:i:s")
        );

        $this->db->insert("corder_threads",$thread);



        $thread = array(
            "by"=>"Production",
            "order_id"=>$order_id,
            "by_id"=>$this->session->userdata("admin_id"),
            "desc"=> "Production has updated the status to for order #".$order_id,
            "for_admin"=>1,
            "created_at"=>date("Y-m-d H:i:s")
        );

              
        $this->db->insert("notifications",$thread);
        //SEND NOTIF HERE

            // SEND SMS 
            $order_details = $this->db->query("SELECT * FROM c_orders WHERE id = '".$order_id."'")->result_object()[0];
            $user_logged = $this->db->query("SELECT * FROM users WHERE id = '".$order_details->user_id."'")->result_object()[0];

            if($user_logged->phone!=""){
                if($order_details->lang_id == 1){
                    $send_mobile_desc = "SouqPack - ".$desc_ar;
                }
                else{
                    $send_mobile_desc = "SouqPack - ".$cdesc;
                }
                
                $phone_sms = "+".$user_logged->code.$user_logged->phone;
                $this->send_sms_to_users($phone_sms,$send_mobile_desc);
            }

        $this->session->set_flashdata('msg', 'Order status successfully!');
        $this->redirect_back();
    }
    public function update_admin_status()
    {

        $order_id = $this->input->post("order_id");
        $status = $this->input->post("status");

        $order = $this->db->where("id",$order_id)->get("c_orders")->result_object()[0];
       

        if(!$order)
        {
            $this->session->set_flashdata('err', 'Order not found');
            $this->redirect_back();
            return;
        }

        

       

        $ar = array(
            "admin_status"=>$status, // sent to user
        );

        
        if($status==1)
        {
            $desc = "Order Received";
            $desc_ar = "طلب وارد";
        }

        $title = "Admin has udpated the order status, ";

        if($status==3)
        {
            $desc = "we are waiting for sample approval";
            $desc_ar = "نحن في انتظار موافقة العينة";

            $order_id = $order_id;
            $subject = "Custom Order Sample Approval #";
            $view_ar = "custom_order_approval_arabic";
            $view_en = "custom_order_approval_english";
            $this->email_custom_sent_souqpack($order_id,$subject,$view_ar,$view_en);
        }

        if($status==4)
        {
            $desc = "we are waiting for down payment";
            $desc_ar = "نحن في انتظار الدفعة الأولى";
            //SEND CUSTON ORDER EMAIL PENDING PAYMENT
            $order_id = $order_id;
            $subject = "Custom Order Pending Payment #";
            $view_ar = "custom_order_down_payment_ar";
            $view_en = "custom_order_down_payment_english";
            $this->email_custom_sent_souqpack($order_id,$subject,$view_ar,$view_en);
        }

        if($status==5)
        {
            $desc = "order in production";
            $desc_ar = "طلب في الإنتاج";
        }

        if($status==6)
        {
            $ar["show_payment"]=1;
            $desc = "we are waiting for final payment";
            $desc_ar = "نحن في انتظار الدفعة النهائية";

            //SEND CUSTON ORDER EMAIL RECEIVED
            $order_id = $order_id;
            $subject = "Custom Order Final Payment Pending #";
            $view_ar = "custom_order_final_payment_ar";
            $view_en = "custom_order_final_payment_english";
            $this->email_custom_sent_souqpack($order_id,$subject,$view_ar,$view_en);
        }

        if($status==7)
        {
            $desc = "order is ready for shipment";
            $desc_ar = "الطلب جاهز للشحن";

            // creating a thread
            $thread = array(
                "by"=>"Admin",
                "task_id"=>$order_id,
                "title"=>"Updated the status",
                "by_id"=>$this->session->userdata("admin_id"),
                "desc"=>"Admin marked the order as ready for shipment",
                "cdesc"=>"Admin marked the order as ready for shipment",
                "title_ar"=>"تحديث الحالة",
                "desc_ar"=>"حدد المسؤول الطلب على أنه جاهز للشحن",
                "for_admin"=>1,
                "created_at"=>date("Y-m-d H:i:s")
            );

            $this->db->insert("corder_threads",$thread);
        }

        if($status==8)
        {
            $desc = "order is shipped";
            $desc_ar = "يتم شحن الطلب";
            // creating a thread
            $thread = array(
                "by"=>"Admin",
                "task_id"=>$order_id,
                "title"=>"Updated the status",
                "by_id"=>$this->session->userdata("admin_id"),
                "desc"=>"Admin marked the order as shipped",
                "cdesc"=>"Admin marked the order as shipped",
                "title_ar"=>"تحديث الحالة",
                "desc_ar"=>"وضع المسؤول علامة على الطلب على أنه تم الشحن",
                "for_admin"=>1,
                "created_at"=>date("Y-m-d H:i:s")
            );

            $this->db->insert("corder_threads",$thread);

            //SEND CUSTON ORDER EMAIL RECEIVED
            $order_id = $order_id;
            $subject = "Custom Order Shipped #";
            $view_ar = "custom_order_shipped_ar";
            $view_en = "custom_order_shipped_english";
            $this->email_custom_sent_souqpack($order_id,$subject,$view_ar,$view_en);
        }

        if($status==9)
        {
            $desc = "order is delivered";
             $desc_ar = "يتم تسليم الطلب";
            // creating a thread
            $thread = array(
                "by"=>"Admin",
                "task_id"=>$order_id,
                "title"=>"Updated the status",
                "by_id"=>$this->session->userdata("admin_id"),
                "desc"=>"Admin marked the order as delivered",
                "cdesc"=>"Admin marked the order as delivered",
                "title_ar"=>"تحديث الحالة",
                "desc_ar"=>"وضع المسؤول علامة على الطلب على أنه تم تسليمه",
                "for_admin"=>1,
                "created_at"=>date("Y-m-d H:i:s")
            );

            $this->db->insert("corder_threads",$thread);
        }

        if($status==10)
        {
            $desc = "order is canceled";
            $desc_ar = "تم إلغاء الطلب";

            // creating a thread
            $thread = array(
                "by"=>"Admin",
                "task_id"=>$order_id,
                "title"=>"Updated the status",
                "by_id"=>$this->session->userdata("admin_id"),
                "desc"=>"Admin marked the order as cancelled",
                "cdesc"=>"Admin marked the order as cancelled",
                "title_ar"=>"تحديث الحالة",
                "desc_ar"=>"وضع المسؤول علامة على الطلب على أنه تم إلغاؤه",
                "for_admin"=>1,
                "created_at"=>date("Y-m-d H:i:s")
            );

            $this->db->insert("corder_threads",$thread);


            //SEND CUSTON ORDER EMAIL RECEIVED
            $order_id = $order_id;
            $subject = "Custom Order cancelled #";
            $view_ar = "custom_order_cancelled_ar";
            $view_en = "custom_order_cancelled_english";
            $this->email_custom_sent_souqpack($order_id,$subject,$view_ar,$view_en);
        }


        if($order->lang_id == 1){
            $send_mobile_desc = "SouqPack - قام المسؤول بتحديث حالة طلبك إلى (".$desc_ar.")";
        }else{
            $send_mobile_desc = "SouqPack - Admin has updated your order status to (".$desc.")";
        }

            $user_push = $this->db->where("id",$order->user_id)->get("users")->result_object()[0];
            // SEND SMS 
            if($user_push->phone!=""){
                
                $phone_sms = "+".$user_push->code.$user_push->phone;
                $this->send_sms_to_users($phone_sms,$send_mobile_desc);
            }


            // SEND TO CUSTOMER PUSH NOTIFS
            if($user_push->push_id!=""){
                
                // $notif["data"] = (Object) array();
                $notif["data"] = (Object) array("type"=>"c_order", "id"=>$order_id);
                $notif["tag"] = "Updates";
                $notif["title"] = "Custom Order Status Update";
                $notif["msg"]   = $send_mobile_desc;          
                try{
                    push_notif($user_push->push_id,$notif);
                }
                catch(Exception $e)
                {

                }
            }


        $this->db->where("id",$order->id)->update("c_orders",$ar);


        // creating a thread
        $thread = array(
            "by"=>"Admin",
            "order_id"=>$order_id,
            "by_id"=>$this->session->userdata("admin_id"),
            "desc"=> $title." ".$desc,
            "for_admin"=>1,
            "created_at"=>date("Y-m-d H:i:s")
        );


        $this->db->insert("notifications",$thread);
        //SEND NOTIF HERE
        $this->session->set_flashdata('msg', 'Order status successfully!');
        $this->redirect_back();
    }
    public function accept_delivery($task_id,$thread_id)
    {

        $task = $this->db->where("id",$task_id)->get("designer_tasks")->result_object()[0];
        $order = $this->db->where("id",$task->order_id)->get("c_orders")->result_object()[0];
        $designer = $this->db->where("id",$task->designer_id)->get("admin")->result_object()[0];

        if(!$order)
        {
            $this->session->set_flashdata('err', 'Order not found');
            $this->redirect_back();
            return;
        }

        if(!$designer)
        {
            $this->session->set_flashdata('err', 'Designer not found');
            $this->redirect_back();
            return;
        }

        

       

        $new_task = array(
            "status"=>4
        );

        $this->db->where("id",$task_id);
        $this->db->update("designer_tasks",$new_task);



        $this->db->where("id",$order->id)->update("c_orders",array(
            "admin_status"=>3, // waiting sample approval by user
            "status"=>3, // under rvew
        ));



         $this->db->where("id",$thread_id)->update("task_threads",array(
            "status"=>1, // rejectred
        ));


        // creating a thread
        $thread = array(
            "by"=>"Admin",
            "task_id"=>$task_id,
            "status"=>1,
            "title"=>"Accepted the delivery",
            "by_id"=>$this->session->userdata("admin_id"),
            "parent_id"=>$thread_id,
            "desc"=>"Delivery has been accepted by admin",
            "created_at"=>date("Y-m-d H:i:s")
        );



        $this->db->insert("task_threads",$thread);


        // creating a thread
        $thread = array(
            "by"=>"Admin",
            "task_id"=>$task_id,
            "title"=>"Accepted the delivery",
            "by_id"=>$this->session->userdata("admin_id"),
            "status"=>1,
            "desc"=>"delivery has been accepted by admin and sent to customer for review",
            "created_at"=>date("Y-m-d H:i:s")
        );



        $this->db->insert("task_threads",$thread);


         $delivery_thread = $this->db->where("id",$thread_id)->get("task_threads")->result_object()[0];

        $thread = array(
            "by"=>"Admin",
            "task_id"=>$order->id,
            "by_id"=>$this->session->userdata("admin_id"),
            "is_delivery"=>1,
            "file"=>$delivery_thread->file,
            "file_type"=>$delivery_thread->file_type,
            "file_size"=>$delivery_thread->file_size,
            "filename"=>$delivery_thread->filename,
            "is_image"=>$delivery_thread->is_image,
            "title"=>"Designs Completed",
            "for_admin"=>1,
            "desc"=>"Designs has been completed and delivered, please reivew",
            "title_ar"=>"أنجزت التصاميم",
            "desc_ar" => "تم الانتهاء من التصاميم وتسليمها ، يرجى التأكيد",
            "created_at"=>date("Y-m-d H:i:s")
        );

       

        $this->db->insert("corder_threads",$thread);



        $thread = array(
            "by"=>"Admin",
            "order_id"=>$order->id,
            "by_id"=>$this->session->userdata("admin_id"),
            "desc"=> "Admin has accepted your delivery for task #".$task_id,
            "for_designer"=>1,
            "created_at"=>date("Y-m-d H:i:s")
        );



            // SEND TO CUSTOMER PUSH NOTIFS
            $user_push = $this->db->where("id",$order->user_id)->get("users")->result_object()[0];
            // $notif["data"] = (Object) array();
            $notif["data"] = (Object) array("type"=>"c_order", "id"=>$order->id);
            $notif["tag"] = "Updates";
            $notif["title"] ="Design For your customize order";
            $notif["msg"]   = "Your customize order (#00P".$order->id.") design has been delivered from the designer side, please review the design and provide your comments";          
                try{
                    push_notif($user_push->push_id,$notif);
                }
                catch(Exception $e)
                {
                    
                }

              
        $this->db->insert("notifications",$thread);
        //SEND NOTIF HERE
        $this->session->set_flashdata('msg', 'Delivery accepted successfully!');
        $this->redirect_back();
    }



    public function re_deliver()
    {

        $order_id=$this->input->post("order_id");

        
        $order = $this->db->where("id",$order_id)->get("c_orders")->result_object()[0];
        
        if(!$order)
        {
            $this->session->set_flashdata('err', 'Order not found');
            $this->redirect_back();
            return;
        }

        

       



        $this->db->where("id",$order->id)->update("c_orders",array(
            "admin_status"=>3, // waiting sample approval from user
            "status"=>3, // under rvew
        ));




        $task = $this->db->where("order_id",$order_id)->where("status",4)->order_by("id","DESC")->limit(1)->get("designer_tasks")->result_object()[0];

        $delivery_thread = $this->db->where("task_id",$task->id)->where("is_delivery",1)->order_by("id","DESC")->limit(1)->get("task_threads")->result_object()[0];


        $thread = array(
            "by"=>"Admin",
            "task_id"=>$order->id,
            "by_id"=>$this->session->userdata("admin_id"),
            "is_delivery"=>1,
            "file"=>$delivery_thread->file,
            "file_type"=>$delivery_thread->file_type,
            "file_size"=>$delivery_thread->file_size,
            "filename"=>$delivery_thread->filename,
            "is_image"=>$delivery_thread->is_image,
            "title"=>"Delivered the designs again",
            "for_admin"=>1,
            "desc"=>$this->input->post("desc"),
            "title_ar"=>"سلمت التصاميم مرة أخرى",
            "desc_ar" => "",
            "created_at"=>date("Y-m-d H:i:s")
        );

       

        $this->db->insert("corder_threads",$thread);
        //SEND NOTIF HERE
        $this->session->set_flashdata('msg', 'Delivery sent successfully!');
        $this->redirect_back();
    }

    public function attach_file (){

        $dbData = array();
        $dbData['title'] = "updated the timeline";
        $dbData["is_delivery"] = 0;
        $dbData["desc"] = $this->input->post("desc");
        if(is_designer()){
            $dbData["by"] = "Designer";
            $dbData["is_delivery"] = $this->input->post("delivery")==1?1:0;
        }
        else{
            $dbData["by"] = "Admin"; 
        }

        $dbData["created_at"] = date("Y-m-d H:i:s");
        $dbData["by_id"]=$this->session->userdata("admin_id");
        
        // echo $this->input->post("task_id");exit;
        
        $dbData["task_id"]=$this->input->post("task_id");

        $task = $this->db->where('id',$dbData["task_id"])->get("designer_tasks")->result_object()[0];
        $order = $this->db->where('id',$task->order_id)->get("c_orders")->result_object()[0];

        $is_file =0;
        $input = "task_file";
        $file_name = $_FILES[$input]['name'];

        if(isset($_FILES[$input]) && $_FILES[$input]["size"]>0){
            $image = $this->file_upload_func($input,'./resources/uploads/orders/','jpg|jpeg|png|gif|mp3|vaw|mp4|doc|docx|docs|xls|xlsx|pdf|txt');
            if($image['upload'] == true){
                $file = $image['data']["file_name"];
                $dbData['title'] = "attached a file";
                if(is_designer() && $dbData["is_delivery"]==1){ 
                    $dbData['title'] = "completed the task";
                }
                $is_file = 1;
                $dbData["filename"]=$file_name;
                $dbData["file"]=$file;
                $dbData["is_image"] = $image["data"]["is_image"];
                $dbData["file_type"] = $image["data"]["file_ext"];
                $dbData["file_size"] = $image["data"]["file_size"];

            }else{
                
                $this->session->set_flashdata('err',$image['data']); 
                $this->redirect_back();
                return;
            }
        }

        if($is_file==0 && $dbData["desc"]=="") 
        {
            $this->redirect_back();
            return;
        }

        if(is_designer() && $dbData["is_delivery"]==1){ 
            $dbData['title'] = "completed the task";
            $this->db->where("id",$dbData["task_id"])->update("designer_tasks",array("status"=>1));
        }

        if($this->input->post("is_customer")==1)
        {
            

            // SEND TO CUSTOMER PUSH NOTIFS
            $user_push = $this->db->where("id",$order->user_id)->get("users")->result_object()[0];
            // $notif["data"] = (Object) array();
            $notif["data"] = (Object) array("type"=>"c_order", "id"=>$order->id);
            $notif["tag"] = "Updates";
            $notif["title"] ="Update for order # 00P".$order->id;
            $notif["msg"]   = "Admin has sent an update against your order #00P".$order->id;          
                try{
                    push_notif($user_push->push_id,$notif);
                }
                catch(Exception $e)
                {
                    
                }

            $this->db->where("id",$dbData["task_id"])->get('designer_tasks')->result_object()[0];
            $dbData["for_admin"]=1;
            $dbData["task_id"]=$task->order_id;
            $this->db->insert("corder_threads",$dbData);

        }
        else
            $this->db->insert("task_threads",$dbData);




        if(is_designer())
        {
            $notification = "Timeline has been updated by designer for task #".$dbData["task_id"]." against order #00CP".$task->order_id;

            if($dbData["is_delivery"]==1)
            {
                $notification = "Designer has delivered the designs for task #".$dbData["task_id"]." against order #00CP".$task->order_id;
            }
        }
        else
        {
            $notification = "Admin has added a comment against your designs for task #".$dbData["task_id"]." agains order #00CP".$task->order_id;
        }
        $thread = array(
            "by"=>is_designer()?"Designer":"Admin",
            "order_id"=>$task->order_id,
            "by_id"=>$this->session->userdata("admin_id"),
            "desc"=> $notification,
            "created_at"=>date("Y-m-d H:i:s")
        );

        if(is_designer())
        {
            $thread["for_admin"]=1;
        }
        else
        {
            $thread["for_designer"]=1;
        }


        $this->db->insert("notifications",$thread);


        $this->session->set_flashdata('msg','Thread updated successfully!');
        $this->redirect_back();
    }
    public function download($id)
    {
        $thread = $this->db->where("id",$id)->get("task_threads")->result_object()[0];

        $this->load->helper('download');
        $data = file_get_contents(base_url()."resources/uploads/orders/".$thread->file);
        force_download($thread->filename, $data);
    }
   
    public function download_bank_receipt($id)
    {
        $thread = $this->db->where("id",$id)->get("c")->result_object()[0];

        $this->load->helper('download');
        $data = file_get_contents(base_url()."resources/uploads/orders/".$thread->attach_bank);
        force_download($thread->filename, $data);
    }
    public function checkandFixOtheruser($user_id,$fullname)
    {
        
         $chatUser = $this->db->where("username",$user_id)->limit(1)->get("chat_users")->result_object()[0];

        if ($chatUser) {
        }
        else
        {
           
            $uuid = guid();
            
         

            $this->db->insert("chat_users",array(
                "uuid"=>$uuid,
                "fullname"=>$fullname,
                "username"=>$user_id
            ));



            $chatUser = $this->db->where("id",$this->db->insert_id())->get("chat_users")->result_object()[0];
        }


        return $chatUser->uuid;


    }

    public function loginUser(){


        $project_id = $this->input->post("project_id");

        $designer_task = $this->db->where("id",$project_id)->order_by("id","DESC")->limit(1)->get("designer_tasks")->result_object()[0];

        $my_id = $this->session->userdata('admin_id');

        

        if(is_designer()){
            $other_id = $designer_task->admin_id;
        }
        else
        {
            $other_id = $designer_task->designer_id;
        }

        $other = $this->db->where("id",$other_id)->get("admin")->result_object()[0];
        $me = $this->db->where("id",$my_id)->get("admin")->result_object()[0];
        



        $ar = [];
        // $ar['message'] =  'User Logged in Successfully';
        $ar['user_uid'] = $me->id;

        // $additionalClaims = ['username'=> $admin->username];
        $customToken = $this->firebase->getAuth()->createCustomToken($me->id);

        $ar['token'] = (string)$customToken;
        $ar["other_uid"] = $other_id;
        $ar["other_pic"] = base_url()."resources/uploads/profiles/".$other->admin_profile_pic;
        $ar["my_pic"] =  base_url()."resources/uploads/profiles/".$me->admin_profile_pic;
        
        echo json_encode( array('status'=> 200, 'message'=> $ar) );
    }
    public function connectUser()
    {
        // $user = new User();
        $project_id = $this->input->post("project_id");
        $me_id = $this->session->userdata('admin_id');
        $designer_task = $this->db->where("id",$project_id)->order_by("id","DESC")->limit(1)->get("designer_tasks")->result_object()[0];

        if(is_designer()){
            $other_id = $designer_task->admin_id;
        }
        else
        {
            $other_id = $designer_task->designer_id;
        }

        echo json_encode($this->createChatRecord($me_id, $other_id,$designer_task->id));
    }

    public function createChatRecord($user_1_uuid, $user_2_uuid, $project_id){

        $project_id =  (int)$project_id;
      
        $this->db->select("chat_uuid");

        $this->db->group_start();
        $this->db->where("user_1_uuid",$user_1_uuid);
        $this->db->where("user_2_uuid",$user_2_uuid);
        $this->db->where("project_id",$project_id);
        $this->db->group_end();

        $this->db->or_group_start();
        $this->db->where("user_1_uuid",$user_2_uuid);
        $this->db->where("user_2_uuid",$user_1_uuid);
        $this->db->where("project_id",$project_id);
        $this->db->group_end();

        $this->db->limit(1);
        $chat_record = $this->db->get("chat_record")->result_object()[0];



        $ar = [];

        if (empty($user_1_uuid) || empty($user_2_uuid)) {
            return  array('status' => 303, 'message'=> 'Invalid details');
        }

        $ar['user_1_uuid'] = $user_1_uuid;
        $ar['user_2_uuid'] = $user_2_uuid;

        if ($chat_record) {
            $ar['chat_uuid'] = $chat_record->chat_uuid;
            return array('status'=>200, 'message'=> $ar);
        }else{
            $chat_uuid = guid();
          

            $this->db->insert("chat_record",array(
                "project_id"=>$project_id,
                "user_1_uuid"=>$user_1_uuid,
                "user_2_uuid"=>$user_2_uuid,
                "chat_uuid"=>$chat_uuid
            ));

            $ar['chat_uuid'] = $chat_uuid;
            
            return array('status'=> 200, 'message'=> $ar);
        }
    }
    public function remove_only_admin($id)
    {
        $this->db->where("id",$id)->update('corder_threads',array("only_admin"=>0));
        $this->session->set_flashdata('msg','Picture sent to user successfully!');
        $this->redirect_back();
    }


    public function email_custom_sent_souqpack($order_id,$subject_title,$ar_view, $en_view){
        $order_details = $this->db->query("SELECT * FROM c_orders WHERE id = '".$order_id."'")->result_object()[0];
        $user_details = $this->db->query("SELECT * FROM users WHERE id = '".$order_details->user_id."'")->result_object();
        if(!empty($user_details) || $user_details[0]->email != ""){

            $email_user = $user_details[0]->email;
            // SEND EMAIL IF SUCCCESS
            
            $fromemail=settings()->email;
            $toemail = $email_user;
            //$subject = "Order Received #00".$order_details->id;
            $subject = $subject_title.$order_details->id;
            if($order_details->from_web_mobile == "1"){
                $user_name = $user_details[0]->first_name." ".$user_details[0]->last_name;
            } else {
                 if($user_details[0]->first_name!=""){
                    $user_name = $user_details[0]->first_name." ".$user_details[0]->last_name;
                }else {
                    $user_name = $user_details[0]->phone;
                }
            }
            $order_date = date("F, d Y", strtotime($order_details->created_at));
            $order_id_email = "#".$order_details->id;
            $order_normal = $order_details->id;
            if($order_details->lang_id == 1){
                $currency = "ر.س";
            }else{
                $currency = "SAR";
                }
            $total_amount = $order_details->all_total." ".$currency;
            $down_pay = $order_details->down_payment." ".$currency;
            $shipping_cust_json = json_decode($order_details->address_text);

            $ship_add_det = $this->db->query("SELECT * FROM shipping_addresses WHERE user_id = ".$order_details->user_id)->result_object()[0];
            if(empty($ship_add_det)){

                $ship_address = "No Address Added<br> <a href='https://souqpack.com/#/profile' target='_blank'>Add Your Address</a>";
                $shipping_cust_name = $user_name;
            }else{
                $ship_address = $ship_add_det->street.", ".$ship_add_det->city;
                $shipping_cust_name = $ship_add_det->first_name." ".$ship_add_det->last_name;
            }

            
            
            $shiping_cost = $order_details->shipping_fee." ".$order_details->currency;

            $this->data["products_order"] = $this->db->query("SELECT * FROM order_products WHERE `order_id` = '".$order_details->id."'")->result_object();
            if($order_details->lang_id == 1){
                $mesg = $this->load->view('frontend/emails/'.$ar_view,$this->data,true);
            }else {
                $mesg = $this->load->view('frontend/emails/'.$en_view,$this->data,true);
            }
            if($order_details->status > 4){
                $refund = $this->db->query("SELECT * FROM refund WHERE pID = ".$order_id)->result_object()[0];    
                if(!empty($refund)){
                    $refund_number = "#".$refund->id;
                    $contatc_num = settings()->mobile;
                    $contact_email = "help@souqpack.com";
                }
            }
            $souq = '<a href="https://souqpack.com" style="color:yellow;">SouqPack</a>';
            $search  = array('[CUSTOMER_NAME]','[ORDER_DATE]','[ORDER_NUMBER]','[ORDER_NUMBER_LINK]','[TOTAL_AMOUNT]','[SHIPPING_CUSTOMER_NAME]','[SHIP_ADDR]','[SUB_TOTAL]','[SHIPPING_COST]','[REFUND_NUMBER]','[CONTACT_NUMBER]','[EMAIL_ADDR_COMPANY]','[USER_EMAIL]','[COPYRIGHT]','[FB]','[TW]','[IN]','[YT]','[SC]','[AMOUNT_TO_PAY]',"SouqPack","Souqpack");
            $replace = array($user_name, $order_date, $order_id_email, $order_normal, $total_amount, $shipping_cust_name,$ship_address,$total_amount,$shiping_cost,$refund_number,$contatc_num,$contact_email,$user_details[0]->email,settings()->copy_right,settings()->facebook,settings()->twitter,settings()->instagram,settings()->youtube,settings()->snapchat,$down_pay,$souq,$souq);

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
            $this->email->send();
        }
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
        //print($message->sid);
    }

    public function apprve_payment($id,$recid)
    {
        $order = $this->db->where("id",$id)->get("c_orders")->result_object()[0];

        $user = $this->db->where("id",$order->user_id)->get("users")->result_object()[0];
        $order_id = $order->id;
        $amount = $order->down_payment;

        if($order->payment_done_part_1!=1){
                $update_arr = array(
                    "payment_method_part_1"=>5,
                    "payment_done_part_1"=>1,
                    "payment_arrived_part_1"=>$order->down_payment,
                    "payment_object_part_1"=>"By Bank Transfer",
                    "total_arrived"=>$order->total_arrived + $amount,
                    "payfort_id"=>time()
                    
                );
                $payment_status=1;
                $msg = "A down payment of ".with_currency($amount)." has been verified by SouqPack against your bank transfer for order #".$order_id;

                //SEND CUSTON ORDER EMAIL RECEIVED
                $order_id = $order_id;
                $subject = "Custom Order Payment Received #";
                $view_ar = "custom_order_payment_received_ar";
                $view_en = "custom_order_payment_received_english";
                $this->email_custom_sent_souqpack($order_id,$subject,$view_ar,$view_en);
            }
            else{


                $update_arr = array(
                    "payment_method_part_2"=>4,
                    "payment_done_part_2"=>1,
                    "payment_arrived_part_2"=>$order->down_payment,
                    "payment_object_part_2"=>"By Bank Transfer",
                    "total_arrived"=>$order->total_arrived + $amount,
                    "payfort_id"=>time()


                );
                $payment_status=2;
                $msg = "A remaining payment of ".with_currency($amount)." has been verified by SouqPack against your bank transfer for order #".$order_id;
            }
            $update_arr["show_payment"]=0;

            $this->db->where("id",$order->id)->update("c_orders",$update_arr);

            $thread = array(
                "by"=>"SouqPack",
                "task_id"=>$order->id,
                "by_id"=>$user->id,
                "payment_status"=>$payment_status,
                "title"=>"Payment Verified",
                "desc"=>$msg,
                "for_admin"=>1,
                "title_ar"=>"تم التحقق من الدفع وتأكيده",
                "desc_ar"=>"",
                "created_at"=>date("Y-m-d H:i:s")
            );
            $this->db->insert("corder_threads",$thread);


            $this->db->query("UPDATE bank_payment_recipts SET status = 1 WHERE id = ".$recid);
            $this->redirect_back();
    }


    public function bank_transfer(){
        $this->data['title'] = 'Bank Transfers';
        $this->data['sub'] = 'bank';
        $this->data['js'] = 'listing';
        $this->data['jsfile'] = 'js/corders_listing';
        $this->data['content'] = $this->load->view('backend/corders/bank_transfers',$this->data,true);
        $this->load->view('backend/common/template',$this->data);
    }


    public function update_price_custom_order($id=0,$cat_id=0){
       
        $new_qty = $this->input->post("qty");
        $category = $this->db->query("SELECT * FROM categories WHERE cust = 1 AND id = ".$cat_id)->result_object()[0];
        
        // GET ORDER DETAILS 
        $corder = $this->db->query("SELECT * FROM c_orders WHERE id = ".$id)->result_object()[0];
        $user_logged = $this->db->query("SELECT * FROM users WHERE id = ".$corder->user_id)->result_object()[0];

        $print_face = $corder->print_face_price;
        $base_price = $new_qty*$corder->print_face_price;

        $logo_price = $corder->logo_type==2?$category->logo_price:0;
        $shipping   = $corder->shipping;

        $options = json_decode($corder->options);

        $option_price = 0;
        foreach ($options as $key => $option) {
            $option_price += $option->price;
        }

        $total_price = $base_price + $logo_price + $shipping + $option_price;
        $half_price = $total_price/2;

        $desc_text = $this->input->post("desc");
        $desc = "SouqPack has updated your order quantity from ".$corder->qty." to ".$new_qty.". Don't worry your price has also been reduced according to the updated quantity";
        if($desc_text!=""){
            $desc .= "<br> <b>Reason</b><br>";
            $desc .= $desc_text;
        }
        // SEND NOTIFICATIONS
        $thread = array(
            "by"=>"Admin",
            "task_id"=>$corder->id,
            "by_id"=>$this->session->userdata("admin_id"),
            "is_delivery"=>0,
            "title"=>"Updated your order quantity",
            "for_admin"=>1,
            "desc"=>$desc,
            "title_ar"=>"تم تحديث كمية الطلب",
            "desc_ar" => "",
            "created_at"=>date("Y-m-d H:i:s")
        );
        // print_r($thread);
        $this->db->insert("corder_threads",$thread);

        $old_p = $corder->all_total;
        // UPDATE PRICE AND REASON
        $mmmsg = $desc;
        $this->load->library('email');
        $this->email->from(settings()->email, 'SouqPack');
        $this->email->to($user_logged->email);
        $this->email->set_mailtype("html");
        $this->email->subject("Quanity Update Against Custom Order #".$corder->id);
        $this->email->message($mmmsg);
        $x = $this->email->send();


        $dbData['all_total'] = $total_price;
        $dbData['down_payment'] = $half_price;
        $dbData['total'] = $half_price;
        $dbData['qty'] = $new_qty;
        $dbData['price'] = $total_price;

        $dbData['old_price'] = $old_p;
        $dbData['old_qty'] = $corder->qty;
        $dbData['reason'] = $desc_text;
        $dbData['updated_at'] = date('Y-m-d H:i:s');
        $dbData['updated_by'] = $this->session->userdata('admin_id');
        // die;
        $this->db->where('id',$id);
        $this->db->update('c_orders',$dbData);


        $this->session->set_flashdata('msg', 'Delivery sent successfully!');
        $this->redirect_back();
    }

    public function set_filter(){
        $_SESSION['filter_web_custom'] = 1;
        $_SESSION['status_payment_custom'] = $_POST['status_payment'];
        $this->redirect_back();
    }
    public function reset_filter(){
        unset($_SESSION['filter_web_custom']);
        unset($_SESSION['status_payment_custom']);
        $this->redirect_back();
    }
}
