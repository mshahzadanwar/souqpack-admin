<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * handles the admins
 * 
 * @since 1.0
 * @author DeDevelopers
 * @copyright Copyright (c) 2019, DeDevelopers, https://dedevelopers.com
 */
class Vendorprofile extends ADMIN_Controller {
    /**
     * constructs ADMIN_Controller as parent object
     * loads the neccassary class
     * checks if current user has the rights to access this class
     * 
     * @since 1.0
     * @author DeDevelopers
     * @copyright Copyright (c) 2019, DeDevelopers, https://dedevelopers.com
     */
	function __construct()
	{
		parent::__construct();
		auth();

        // $this->redirect_role(1);
        $this->data['active'] = 'admins';
        $this->load->model('admins_model','admin');
        $this->load->model('location_model','location');
	}
    /**
     * loads the listing page
     * 
     * @return view listing view
     * 
     * @since 1.0
     * @author DeDevelopers
     * @copyright Copyright (c) 2019, DeDevelopers, https://dedevelopers.com
     */
	public function index()
	{
		$id = $this->session->userdata("admin_id");

		$result = $this->db->where("id",$id)->get("vendors")->result_object()[0];

        if(!$result){

            $this->session->set_flashdata('err','Invalid request.');
            redirect('admin/404_page');

        }

        $this->data['data'] = $result;
        $this->form_validation->set_rules('name','Name','trim|required');
        $this->form_validation->set_rules('phone','Phone','trim|required');
        $this->form_validation->set_rules('email','Email','trim|required|valid_email|callback_check_email_edit['.$id.']');
        $this->form_validation->set_message('required','This field is required.');
        $this->form_validation->set_message('alpha_numeric_spaces','Only alphabet and numbers are allowed.');
        if($this->form_validation->run() === false){


        	

            $this->data['title'] = 'My Profile';
            $this->data['sub'] = 'edit-admin';
            $this->data['jsfile'] = 'add_admin';
            $this->data['content'] = $this->load->view('backend/admins/editvendor',$this->data,true);
            $this->load->view('backend/common/template',$this->data);
        }else{

        	if(!empty($_FILES['profile_pic']['name'])){
				$config['upload_path']          = './resources/uploads/vendors/';
				$config['allowed_types']        = 'gif|jpg|png|jpeg|GIF|JPG|JPEG|PNG';
				$config['file_ext_tolower']        = TRUE;
				$config['encrypt_name']        = TRUE;
				$config['remove_spaces']        = TRUE;
				$this->load->library('upload', $config);
				if ( $this->upload->do_upload('profile_pic'))
				{
					$logo_data = $this->upload->data();
					unlink('./resources/uploads/vendors/'.$this->data['data']->profile_pic);
					$dbData['profile_pic'] = $logo_data['file_name'];
				}
				
			}

            $dbData['name'] = $this->input->post('name');
            $dbData['phone'] = $this->input->post('phone');
            $dbData['email'] = $this->input->post('email');
            $dbData['updated_at'] = date('Y-m-d H:i:s');
            $dbData['updated_by'] = $this->session->userdata('admin_id');
            $this->db->where('id',$id);
            $this->db->update('vendors', $dbData);
            $this->session->set_flashdata('msg', 'Admin updated successfully!');
            redirect('admin/vendor-profile');

        }
	}
    /**
     * validation check
     * 
     * @since 1.0
     * @author DeDevelopers
     * @copyright Copyright (c) 2019, DeDevelopers, https://dedevelopers.com
     */
    public function check_email_edit($email,$id){

        $result = $this->db->where("email",$email)->get("vendors");
        if(!empty($email)) {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->form_validation->set_message('check_email_edit', 'Please enter the valid email address.');
                return false;
            }else{
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
            }

        }else{
            $this->form_validation->set_message('check_email_edit', 'This field is required.');
            return false;
        }
    }
   
}
