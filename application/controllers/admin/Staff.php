<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * handles the admins
 * 
 * @since 1.0
 * @author DeDevelopers
 * @copyright Copyright (c) 2019, DeDevelopers, https://dedevelopers.com
 */
class Staff extends ADMIN_Controller {
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

        $this->redirect_role(31);
        $this->data['active'] = 'staff';
        $this->load->model('staff_model','staff');
        // $this->load->model('location_model','location');
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
	public function index($l)
	{

		$this->data['title'] = 'Designers';
        $this->data['sub'] = 'designers';
        $this->data['js'] = 'listing';
        $this->data['type'] = $l;
        $this->data['jsfile'] = 'js/clients_listing';
        $this->data['admins'] = $this->staff->get_all_staff($l);
		$this->data['content'] = $this->load->view('backend/staff/listing', $this->data,true);
		$this->load->view('backend/common/template',$this->data);
	}
    
    /**
     * loads the add view, then handles the submitted data
     * 
     * @since 1.0
     * @author DeDevelopers
     * @copyright Copyright (c) 2019, DeDevelopers, https://dedevelopers.com
     */
	public function add (){

	    $this->form_validation->set_rules('fname','Name','trim|required');
	    // $this->form_validation->set_rules('email','Email','trim|required|valid_email|callback_check_email');
        $this->form_validation->set_rules('password','Password','trim|required');

        $this->form_validation->set_message('required','This field is required.');
        $this->form_validation->set_message('alpha_numeric_spaces','Only alphabet and numbers are allowed.');
	    if($this->form_validation->run() === false){
            $this->data['title'] = 'Add New Staff';
            $this->data['sub'] = 'add-staff';
            
            $this->data['content'] = $this->load->view('backend/staff/add',$this->data,true);
            $this->load->view('backend/common/template',$this->data);
        }else{
	        $dbData['name'] = $this->input->post('fname');
            $dbData['email'] = $this->input->post('email');
            // if(is_region())
            // {
            //     $dbData['region_id'] = region_id();
            // }
            // else
            // {
            //     if($this->input->post('region_id')!="")
            //     {
            //         $dbData['region_id'] = $this->input->post('region_id');
            //     }
            // }
            $dbData['password'] = md5($this->input->post('password'));
	        $dbData['satff'] = 1;
            $dbData['status'] = 1;
	        $dbData['created_at'] = date('Y-m-d H:i:s');
	        $dbData['created_by'] = $this->session->userdata('admin_id');
	        $dbData['updated_at'] = date('Y-m-d H:i:s');
            $dbData['updated_by'] = $this->session->userdata('admin_id');
            $this->db->insert('admin',$dbData);

            $id = $this->db->insert_id();



            $this->db->insert("admin_roles",array(
                "admin_id"=>$id,
                "role"=>$this->input->post("type"),
                "role_name"=>ucfirst(s_role_to_text($this->input->post("type")))
            ));
            $this->session->set_flashdata('msg','New Staff Added Successfully!');
            redirect('admin/staff/'.s_role_to_text($_POST["type"]));

        }
    }
    /**
     * validation check
     * 
     * @since 1.0
     * @author DeDevelopers
     * @copyright Copyright (c) 2019, DeDevelopers, https://dedevelopers.com
     */
    public function check_email($email){

	    $result = $this->staff->get_staff_by_email($email);
	    if(!empty($email)) {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->form_validation->set_message('check_email', 'Please enter the valid email address.');
                return false;
            }else{
                if ($result->num_rows() > 0) {
                    $this->form_validation->set_message('check_email', 'This email already exist.');
                    return false;
                } else {
                    return true;
                }
            }

        }else{
            $this->form_validation->set_message('check_email', 'This field is required.');
            return false;
        }
    }



    /**
     * changes status of given id row in database
     * 
     * @param  integer $id     id of row in database
     * @param  integer $status new status to set
     * @return redirect        redirects to sucess page
     * 
     * @since 1.0
     * @author DeDevelopers
     * @copyright Copyright (c) 2019, DeDevelopers, https://dedevelopers.com
     */
    public function status($id,$status){

        $result = $this->staff->get_staff_by_id($id);

        if(!$result){

            $this->session->set_flashdata('err','Invalid request.');
            redirect('admin/404_page');

        }

        $client_status = 1;

        if($status == 1){

            $client_status = 0;

        }

        $dbData['status'] = $client_status;
        $dbData['updated_at'] = date('Y-m-d H:i:s');
        $dbData['updated_by'] = $this->session->userdata('admin_id');

        $this->db->where('id',$id);
        $this->db->update('admin',$dbData);
        $this->session->set_flashdata('msg','Staff status updated successfully!');
        $this->redirect_back();
    }
    /**
     * loads the add view, then handles the submitted data
     * 
     * @param integer $id id of row in database
     * 
     * @since 1.0
     * @author DeDevelopers
     * @copyright Copyright (c) 2019, DeDevelopers, https://dedevelopers.com
     */
    public function edit($id){

        $result = $this->staff->get_staff_by_id($id);
        if(!$result){

            $this->session->set_flashdata('err','Invalid request.');
            redirect('admin/404_page');

        }

        $this->data['data'] = $result;
        $this->form_validation->set_rules('fname','Name','trim|required');
        $this->form_validation->set_rules('email','Email','trim|required|valid_email|callback_check_email_edit['.$id.']');
        $this->form_validation->set_message('required','This field is required.');
        $this->form_validation->set_message('alpha_numeric_spaces','Only alphabet and numbers are allowed.');
        if($this->form_validation->run() === false){
            $this->data['title'] = 'Edit Admin';
            $this->data['sub'] = 'edit-admin';
            $this->data['jsfile'] = 'add_admin';
            $this->data['content'] = $this->load->view('backend/staff/edit',$this->data,true);
            $this->load->view('backend/common/template',$this->data);
        }else{

            $dbData['name'] = $this->input->post('fname');
            if(!is_region() && $this->input->post('region_id')!="")
            {
                $dbData['region_id'] = $this->input->post('region_id');
            }

            $password = $this->input->post('password');
            if($password != ""){
                $dbData['password'] = md5($password);
            }
            $dbData['email'] = $this->input->post('email');
            $dbData['updated_at'] = date('Y-m-d H:i:s');
            $dbData['updated_by'] = $this->session->userdata('admin_id');


            $input = "profile_pic";
            if((isset($_FILES[$input]) && $_FILES[$input]['size'] > 0))
            $image = $this->image_upload($input,'./resources/uploads/profiles/','jpg|jpeg|png|gif');
            if($image['upload'] == true || $_FILES[$input]['size']<1){
                $image = $image['data'];
                if((isset($_FILES[$input]) && $_FILES[$input]['size'] > 0)){
                    $dbData['admin_profile_pic'] = $image['file_name'];
                   
                }
            }else{
                
            }
            $this->db->where('id',$id);
            $this->db->update('admin', $dbData);
            $this->session->set_flashdata('msg', 'Admin updated successfully!');
            redirect('admin/staff/'.s_role_to_text($_POST["type"]));

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

        $result = $this->staff->get_staff_by_email($email);
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

    /**
     * deletes the row in database and moves it to trash
     * 
     * @param  integer $id id of row to move to trash
     * @return redirect     back to listing page
     * 
     * @since 1.0
     * @author DeDevelopers
     * @copyright Copyright (c) 2019, DeDevelopers, https://dedevelopers.com
     */
    public function delete($id){
        $result = $this->staff->get_staff_by_id($id);

        if(!$result){

            $this->session->set_flashdata('err','Invalid request.');
            redirect('admin/404_page');

        }
  
        $this->db->where('id',$id);
        $this->db->delete('admin');

        $this->db->where("admin_id",$id)->delete("admin_roles");

        $this->session->set_flashdata('msg', 'Staff deleted successfully!');
        $this->redirect_back();
    }
}
