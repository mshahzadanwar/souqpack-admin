<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Emails extends ADMIN_Controller {

	function __construct()
	{
		parent::__construct();
		auth();
        $this->redirect_role(11);
        $this->data['active'] = 'email';
        $this->load->model('emails_model','email');
	}

	public function index()
	{

			$this->data['title'] = 'emails';
            $this->data['sub'] = 'emails';
            $this->data['js'] = 'listing';
            $this->data['jsfile'] = 'js/emails_listing';
            $this->data['emails'] = $this->email->get_all_emails();
			$this->data['content'] = $this->load->view('backend/emails/listing',$this->data,true);
			$this->load->view('backend/common/template',$this->data);

	}
    // public function trash()
    // {

    //         $this->data['title'] = 'Trash emails';
    //         $this->data['sub'] = 'trash';
    //         $this->data['js'] = 'listing';
    //         $this->data['jsfile'] = 'js/emails_listing';
    //         $this->data['emails'] = $this->email->get_all_trash_emails();
    //         $this->data['content'] = $this->load->view('backend/emails/trash',$this->data,true);
    //         $this->load->view('backend/common/template',$this->data);

    // }
 //    public function restore($id){
        
 //        $dbData['deleted_by'] = $this->session->userdata('admin_id');
 //        $dbData['is_deleted'] = 0;
 //        $this->db->where('id',$id);
 //        $this->db->update('emails', $dbData);
 //        $this->session->set_flashdata('msg', 'email restored successfully!');
 //        redirect('admin/trash-emails');
 //    }

	// public function add (){

	//     $this->form_validation->set_rules('title','Title','trim|required|alpha_numeric_spaces|callback_check_email');
	//     //$this->form_validation->set_rules('description','Description','trim|required');
	//     $this->form_validation->set_rules('image','Image','callback_image_not_required[image,200,200]');
 //        $this->form_validation->set_message('required','This field is required.');
 //        $this->form_validation->set_message('alpha_numeric_spaces','Only alphabet and numbers are allowed.');
	//     if($this->form_validation->run() === false){
 //            $this->data['title'] = 'Add New email';
 //            $this->data['sub'] = 'add-email';
 //            $this->data['meta'] = $this->load->view('backend/common/meta_data',$this->data,true);
 //            $this->data['content'] = $this->load->view('backend/emails/add',$this->data,true);
 //            $this->load->view('backend/common/template',$this->data);
 //        }else{

	//         $dbData['title'] = $this->input->post('title');
	//         $dbData['slug'] = slug($this->input->post('title'));
	//         $dbData['description'] = $this->input->post('description');
	//         $dbData['meta_title'] = $this->input->post('meta_title');
	//         $dbData['meta_keywords '] = $this->input->post('meta_keys');
	//         $dbData['meta_description'] = $this->input->post('meta_desc');
	//         $dbData['created_at'] = date('Y-m-d H:i:s');
	//         $dbData['created_by'] = $this->session->userdata('admin_id');
	//         $dbData['updated_at'] = date('Y-m-d H:i:s');
 //            $dbData['updated_by'] = $this->session->userdata('admin_id');


 //            if((isset($_FILES['image']) && $_FILES['image']['size'] > 0))
	//         $image = $this->image_upload('image','./resources/uploads/emails/','jpg|jpeg|png|gif');
	//         if($image['upload'] == true || $_FILES['image']['size']<1){
 //                $image = $image['data'];
 //                if((isset($_FILES['image']) && $_FILES['image']['size'] > 0)){
 //                    $dbData['image'] = $image['file_name'];
 //                    $this->image_thumb($image['full_path'],'./resources/uploads/emails/actual_size/',200,200);
 //                }
 //                $this->db->insert('emails',$dbData);
 //                $this->session->set_flashdata('msg','New email added successfully!');
 //                redirect('admin/emails');
 //            }else{
 //                print_r($image);exit;

	//             $this->session->set_flashdata('err','An Error occurred durring uploading image, please try again');
	//             redirect('admin/add-email');
 //            }

 //        }
 //    }

    public function check_email($title){

	    $result = $this->email->get_email_by_title($title);
	    if(!empty($title)) {
            if ($result->num_rows() > 0) {
                $this->form_validation->set_message('check_email', 'This email already exist.');
                return false;
            } else {
                return true;
            }
        }else{
            $this->form_validation->set_message('check_email', 'This field is required.');
            return false;
        }
    }


    public function status($id,$status){

        $result = $this->email->get_email_by_id($id);

        if(!$result){

            $this->session->set_flashdata('err','Invalid request.');
            redirect('admin/404_page');

        }

        $email_status = 1;

        if($status == 1){

            $email_status = 0;

        }

        $dbData['status'] = $email_status;
        $dbData['updated_at'] = date('Y-m-d H:i:s');
        $dbData['updated_by'] = $this->session->userdata('admin_id');

        $this->db->where('id',$id);
        $this->db->update('email_template',$dbData);
        $this->session->set_flashdata('msg','email status updated successfully!');
        redirect('admin/emails');
    }

    public function edit($id){

        $result = $this->email->get_email_by_id($id);
        $this->data["the_id"] = $id;

        if(!$result){

            $this->session->set_flashdata('err','Invalid request.');
            redirect('admin/404_page');

        }

        $this->data['data'] = $result;


        $dlang = dlang();
        $langs = langs();
        $input = $dlang->slug."[name]";
        $this->form_validation->set_rules($input,'Title','trim|required|callback_check_email_edit['.$id.']');
        $input = $dlang->slug."[subject]";
        $this->form_validation->set_rules($input,'Subject','trim|required');
        $this->form_validation->set_rules('code','Code','trim|required');
        $this->form_validation->set_rules('email','Email','trim|required');

        $input = $dlang->slug."[content]";

        $this->form_validation->set_rules($input,'Content','trim|required');
       
        $this->form_validation->set_message('required','This field is required.');
        $this->form_validation->set_message('alpha_numeric_spaces','Only alphabet and numbers are allowed.');
        if($this->form_validation->run() === false){
            $this->data['title'] = 'Edit email';
          
            $this->data['content'] = $this->load->view('backend/emails/edit',$this->data,true);
             
            $this->load->view('backend/common/template',$this->data);
        }else{
            $def_key=0;
            $def_parent=0;
            $fault = false;
            foreach($langs as $key=>$lang){
                $dbData=array();
                $input = $lang->slug."[row_id]";
                $row_id = $this->input->post($input);

                $input = $lang->slug."[name]";
                $dbData['name'] = $this->input->post($input);

                $input = $lang->slug."[subject]";

                $dbData['subject'] = $this->input->post($input);
                $dbData['code'] = $this->input->post('code');
                $dbData['email'] = $this->input->post('email');


                $input = $lang->slug."[content]";

                $dbData['content'] = $this->input->post($input);
               
                $dbData['updated_at'] = date('Y-m-d H:i:s');
                $dbData['updated_by'] = $this->session->userdata('admin_id');

     
                $this->db->where('id',$row_id);
                $this->db->update('email_template', $dbData);
            }
            $this->session->set_flashdata('msg', 'email updated successfully!');
            redirect('admin/emails');
        }
    }

    public function check_email_edit($title,$id){

        $result = $this->email->get_email_by_title($title);
        if(!empty($title)) {
            if ($result->num_rows() > 0) {
                $result = $result->row();
                if($result->id == $id){
                    return true;
                }else{
                    $this->form_validation->set_message('check_email_edit', 'This email already exist.');
                    return false;
                }
            } else {
                return true;
            }
        }else{
            $this->form_validation->set_message('check_email_edit', 'This field is required.');
            return false;
        }
    }

    // public function delete($id){
    //     $result = $this->email->get_email_by_id($id);

    //     if(!$result){

    //         $this->session->set_flashdata('err','Invalid request.');
    //         redirect('admin/404_page');

    //     }
    //     $dbData['deleted_by'] = $this->session->userdata('admin_id');
    //     $dbData['is_deleted'] = 1;
    //     $this->db->where('id',$id);
    //     $this->db->update('emails', $dbData);
    //     $this->session->set_flashdata('msg', 'email deleted successfully!');
    //     redirect('admin/emails');
    // }
}
