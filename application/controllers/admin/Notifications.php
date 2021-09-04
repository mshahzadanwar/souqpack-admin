<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Notifications extends ADMIN_Controller {

	function __construct()
	{
		parent::__construct();
		auth();
        $this->data['active'] = 'notifications';
        $this->load->model('notifications_model','notification');
	}

	public function index()
	{

		$this->data['title'] = 'notifications';
        $this->data['sub'] = 'notifications';
        $this->data['js'] = 'listing';
        $this->data['jsfile'] = 'js/notifications_listing';
        $this->data['notifications'] = $this->notification->get_all_notifications();
		$this->data['content'] = $this->load->view('backend/notifications/listing',$this->data,true);
		$this->load->view('backend/common/template',$this->data);
	}
    public function trash()
    {

        $this->data['title'] = 'Trash notifications';
        $this->data['sub'] = 'trash';
        $this->data['js'] = 'listing';
        $this->data['jsfile'] = 'js/notifications_listing';
        $this->data['notifications'] = $this->notification->get_all_trash_notifications();
        $this->data['content'] = $this->load->view('backend/notifications/trash',$this->data,true);
        $this->load->view('backend/common/template',$this->data);
    }
    public function restore($id){
        
        $dbData['deleted_by'] = $this->session->userdata('admin_id');
        $dbData['is_deleted'] = 0;
        $this->db->where('id',$id);
        $this->db->update('notifications', $dbData);
        $this->session->set_flashdata('msg', 'notification restored successfully!');
        redirect('admin/trash-notifications');
    }

	public function add (){

	    $dlang = dlang();
        $langs = langs();
        $input = $dlang->slug."[title]";
        $this->form_validation->set_rules($input,'Title','trim|required');
        $input = $dlang->slug."[sub_title]";
        $this->form_validation->set_rules($input,'Sub-Title','trim|required');
        $input = $dlang->slug."_image";
	    $this->form_validation->set_rules('image','Image','callback_image_not_required['.$input.',20,20]');
        $this->form_validation->set_message('required','This field is required.');
        $this->form_validation->set_message('alpha_numeric_spaces','Only alphabet and numbers are allowed.');
	    if($this->form_validation->run() === false){
            $this->data['title'] = 'Add New notification';
            $this->data['jsfile'] = 'js/add_notification';
            $this->data['sub'] = 'add-notification';
            $this->data['categories'] = $this->db->where('is_deleted',0)
            ->where('parent',0)
            ->where('lparent',0)
            ->get('categories');
            if(isset($_GET['replicate']))
            {
                $this->data['prev'] = $this->db->where('is_deleted',0)
                ->where('id',$_GET['replicate'])
                ->get('notifications')->result_object()[0];
               
            }

            $this->data['meta'] = $this->load->view('backend/common/meta_data',$this->data,true);
            $this->data['content'] = $this->load->view('backend/notifications/add',$this->data,true);
            $this->load->view('backend/common/template',$this->data);
        }else{

            $def_key=0;
            $def_parent=0;
            $fault = false;
            foreach($langs as $key=>$lang){

                $dbData = array();

    	        $input = $lang->slug."[title]";
                $dbData['title'] = $this->input->post($input);

                $input = $lang->slug."[sub_title]";
                $dbData['sub_title'] = $this->input->post($input);

                $input = "link";
                $dbData['link'] = $this->input->post($input);

                

    	        $dbData['created_at'] = date('Y-m-d H:i:s');
    	        $dbData['created_by'] = $this->session->userdata('admin_id');
    	        $dbData['updated_at'] = date('Y-m-d H:i:s');
                $dbData['updated_by'] = $this->session->userdata('admin_id');


                $dbData["lparent"] = $def_key;
                $dbData["lang_id"] = $lang->id;



                $input = $lang->slug."_image";
                if((isset($_FILES[$input]) && $_FILES[$input]['size'] > 0))
    	        $image = $this->image_upload($input,'./resources/uploads/notifications/','jpg|jpeg|png|gif');
    	        if($image['upload'] == true || $_FILES[$input]['size']<1){
                    $image = $image['data'];
                    if((isset($_FILES[$input]) && $_FILES[$input]['size'] > 0)){
                        $dbData['image'] = $image['file_name'];
                        $this->image_thumb($image['full_path'],'./resources/uploads/notifications/actual_size/',20,20);


                        
                    }
                    else{
                    }


                    $this->db->insert('notifications',$dbData);

                    if($def_key==0)
                        $def_key = $this->db->insert_id();
                    
                 }else{
                    $fault=true;
                }


                if($fault){
                    $this->session->set_flashdata('err','An Error occurred durring uploading image, please try again');
                    redirect('admin/add-notification');
                    return;
                } 
            }

            $this->send_push($def_key);


            $this->session->set_flashdata('msg','New notification added successfully!');
            redirect('admin/notifications');

        }
    }
    private function send_push($id)
    {


        $parent_notif = $this->db->where("id",$id)->get("notifications")->result_object()[0];


        
        $users = $this->db->where("status",1)->where("is_deleted",0)->get("users")->result_object();
        foreach($users as $user)
        {
            if($user->push_id!="")
                $final_notifs[] = array("lang_id"=>$user->lang_id,"key"=>$user->push_id);
        }
        

        if(empty($final_notifs))
        {
            
            return false;
        }



        $notif["data"] = (Object) array();



        

        foreach($final_notifs as $final_notif){



            $lang_notif = $this->db->where("lparent",$id)->where("lang_id",$final_notif["lang_id"])->get("notifications")->result_object()[0];



            $notif["tag"] = "Updates";
            $notif["title"] =$lang_notif->title!=""?$lang_notif->title:$parent_notif->title;
            $notif["msg"] =$lang_notif->sub_title!=""?$lang_notif->sub_title:$parent_notif->sub_title;



            try{
                push_notif($final_notif->key,$notif);
            }
            catch(Exception $e)
            {
                
            }
        }

        return true;
    }
    public function view_products_section($id,$product=0)
    {
        view_products_section_helper($id,$product);
    }

    public function check_notification($title){

	    $result = $this->notification->get_notification_by_title($title);
	    if(!empty($title)) {
            if ($result->num_rows() > 0) {
                $this->form_validation->set_message('check_notification', 'This notification already exist.');
                return false;
            } else {
                return true;
            }
        }else{
            $this->form_validation->set_message('check_notification', 'This field is required.');
            return false;
        }
    }


    public function status($id,$status){

        $result = $this->notification->get_notification_by_id($id);

        if(!$result){

            $this->session->set_flashdata('err','Invalid request.');
            redirect('admin/404_page');

        }

        $notification_status = 1;

        if($status == 1){

            $notification_status = 0;

        }

        $dbData['status'] = $notification_status;
        $dbData['updated_at'] = date('Y-m-d H:i:s');
        $dbData['updated_by'] = $this->session->userdata('admin_id');

        $this->db->where('id',$id);
        $this->db->update('notifications',$dbData);
        $this->session->set_flashdata('msg','notification status updated successfully!');
        redirect('admin/notifications');
    }

    public function edit($id){

        $result = $this->notification->get_notification_by_id($id);
        $this->data["the_id"] = $id;

        if(!$result){

            $this->session->set_flashdata('err','Invalid request.');
            redirect('admin/404_page');

        }

        $this->data['data'] = $result;
        $dlang = dlang();
        $langs = langs();
        $input = $dlang->slug."[title]";
        $this->form_validation->set_rules($input,'Title','trim|required');

        $input = $dlang->slug."[sub_title]";
        $this->form_validation->set_rules($input,'Sub-Title','trim|required');

        $input = $dlang->slug."_image";
        $this->form_validation->set_rules('image','Image','callback_image_not_required['.$input.',20,20]');
        $this->form_validation->set_message('required','This field is required.');
        $this->form_validation->set_message('alpha_numeric_spaces','Only alphabet and numbers are allowed.');
        if($this->form_validation->run() === false){
            $this->data['title'] = 'Edit notification';
            $this->data['meta'] = $this->load->view('backend/common/meta_data',$this->data,true);
            $this->data['categories'] = $this->db->where('is_deleted',0)
            ->where('lparent',0)
            ->where('parent',0)
            ->get('categories');
            $this->data['content'] = $this->load->view('backend/notifications/edit',$this->data,true);
             
            $this->load->view('backend/common/template',$this->data);
        }else{

            $def_key=0;
            $def_parent=0;
            $fault = false;
            foreach($langs as $key=>$lang){
                $dbData=array();

                $input = $lang->slug."[row_id]";
                $row_id = $this->input->post($input);


                $input = $lang->slug."[title]";
                $dbData['title'] = $this->input->post($input);

                $input = $lang->slug."[sub_title]";
                $dbData['sub_title'] = $this->input->post($input);

                $input = "link";
                 $dbData['link'] = $this->input->post($input);



                $dbData['updated_at'] = date('Y-m-d H:i:s');
                $dbData['updated_by'] = $this->session->userdata('admin_id');


                $input = $lang->slug."_image";
                if(!empty($_FILES[$input]['name'])) {
                    unlink('./resources/uploads/notifications/'.$this->data['data']->image);
                    unlink('./resources/uploads/notifications/actual_size/'.$this->data['data']->image);
                    $image = $this->image_upload($input, './resources/uploads/notifications/', 'jpg|jpeg|png|gif');
                    if ($image['upload'] == true) {
                        $image = $image['data'];
                        $dbData['image'] = $image['file_name'];
                        $this->image_thumb($image['full_path'], './resources/uploads/notifications/actual_size/', 20, 20);
                    } else {

                        if($lang->is_default==1){
                             $this->session->set_flashdata('err',$error);
                            redirect($_SERVER["HTTP_REFERER"]);
                            return;
                        }
                    }
                }

                $this->db->where("id",$row_id);
                $this->db->update('notifications', $dbData);
            }
            $this->session->set_flashdata('msg', 'notification updated successfully!');
            redirect('admin/notifications');

        }
    }

    public function check_notification_edit($title,$id){

        $result = $this->notification->get_notification_by_title($title);
        if(!empty($title)) {
            if ($result->num_rows() > 0) {
                $result = $result->row();
                if($result->id == $id){
                    return true;
                }else{
                    $this->form_validation->set_message('check_notification_edit', 'This notification already exist.');
                    return false;
                }
            } else {
                return true;
            }
        }else{
            $this->form_validation->set_message('check_notification_edit', 'This field is required.');
            return false;
        }
    }

    public function delete($id){
        $result = $this->notification->get_notification_by_id($id);

        if(!$result){

            $this->session->set_flashdata('err','Invalid request.');
            redirect('admin/404_page');

        }
        $dbData['deleted_by'] = $this->session->userdata('admin_id');
        $dbData['is_deleted'] = 1;
        $this->db->where('id',$id);
        $this->db->update('notifications', $dbData);
        $this->session->set_flashdata('msg', 'notification deleted successfully!');
        redirect('admin/notifications');
    }
}
