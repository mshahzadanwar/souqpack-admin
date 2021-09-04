<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * handles the vendors
 * 
 * @since 1.0
 * @author DeDevelopers
 * @copyright Copyright (c) 2019, DeDevelopers, https://dedevelopers.com
 */
class Vendors extends ADMIN_Controller {
    /**
     * constructs vendor_Controller as parent object
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

        $this->redirect_role(1);
        $this->data['active'] = 'vendors';
        $this->load->model('vendors_model','vendor');
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

		$this->data['title'] = 'vendors';
        $this->data['sub'] = 'vendors';
        $this->data['js'] = 'listing';
        $this->data['jsfile'] = 'js/clients_listing';
        $this->data['vendors'] = $this->vendor->get_all_vendors();
		$this->data['content'] = $this->load->view('backend/vendors/listing', $this->data,true);
		$this->load->view('backend/common/template',$this->data);
	}
    /**
     * loads the trash page
     * 
     * @return view trash
     * 
     * @since 1.0
     * @author DeDevelopers
     * @copyright Copyright (c) 2019, DeDevelopers, https://dedevelopers.com
     */
    public function trash()
    {
        $this->data['title'] = 'Trash vendors';
        $this->data['sub'] = 'trash-vendors';
        $this->data['js'] = 'listing';
        $this->data['jsfile'] = 'js/clients_listing';
        $this->data['vendors'] = $this->vendor->get_all_trashed_vendors();
        $this->data['content'] = $this->load->view('backend/vendors/trash', $this->data,true);
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
	    $this->form_validation->set_rules('email','Email','trim|required|valid_email|callback_check_email');
        $this->form_validation->set_rules('password','Password','trim|required');

        $this->form_validation->set_message('required','This field is required.');
        $this->form_validation->set_message('alpha_numeric_spaces','Only alphabet and numbers are allowed.');
	    if($this->form_validation->run() === false){
            $this->data['title'] = 'Add New vendor';
            $this->data['sub'] = 'add-vendor';
            
            $this->data['content'] = $this->load->view('backend/vendors/add',$this->data,true);
            $this->load->view('backend/common/template',$this->data);
        }else{

	        $dbData['name'] = $this->input->post('fname');
            $dbData['email'] = $this->input->post('email');
            $dbData['phone'] = $this->input->post('phone');
            $dbData['password'] = md5($this->input->post('password'));
	       
	        $dbData['created_at'] = date('Y-m-d H:i:s');
	        $dbData['created_by'] = $this->session->userdata('admin_id');
	        $dbData['updated_at'] = date('Y-m-d H:i:s');
            $dbData['updated_by'] = $this->session->userdata('admin_id');
            $this->db->insert('vendors',$dbData);
            $this->session->set_flashdata('msg','New vendor added successfully!');
            redirect('admin/vendors');

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

	    $result = $this->vendor->get_vendor_by_email($email);
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

        $result = $this->vendor->get_vendor_by_id($id);

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
        $this->db->update('vendors',$dbData);
        $this->session->set_flashdata('msg','vendor status updated successfully!');
        redirect('admin/vendors');
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

        $result = $this->vendor->get_vendor_by_id($id);

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
            $this->data['title'] = 'Edit vendor';
            $this->data['sub'] = 'edit-vendor';
            $this->data['jsfile'] = 'add_vendor';
            $this->data['countries'] = $this->location->get_all_countries('name','ASC');
            $this->data['content'] = $this->load->view('backend/vendors/edit',$this->data,true);
            $this->load->view('backend/common/template',$this->data);
        }else{

            $dbData['phone'] = $this->input->post('phone');


            $dbData['name'] = $this->input->post('fname');
            $dbData['email'] = $this->input->post('email');
            $dbData['updated_at'] = date('Y-m-d H:i:s');
            $dbData['updated_by'] = $this->session->userdata('admin_id');



            $input = "profile_pic";
            if((isset($_FILES[$input]) && $_FILES[$input]['size'] > 0))
            $image = $this->image_upload($input,'./resources/uploads/vendors/','jpg|jpeg|png|gif');
            if($image['upload'] == true || $_FILES[$input]['size']<1){
                $image = $image['data'];
                if((isset($_FILES[$input]) && $_FILES[$input]['size'] > 0)){
                    $dbData['profile_pic'] = $image['file_name'];
                }
            }



            $this->db->where('id',$id);
            $this->db->update('vendors', $dbData);
            $this->session->set_flashdata('msg', 'vendor updated successfully!');
            redirect('admin/vendors');

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

        $result = $this->vendor->get_vendor_by_email($email);
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
        $result = $this->vendor->get_vendor_by_id($id);

        if(!$result){

            $this->session->set_flashdata('err','Invalid request.');
            redirect('admin/404_page');

        }
        $dbData['deleted_by'] = $this->session->userdata('admin_id');
        $dbData['is_deleted'] = 1;
        $this->db->where('id',$id);
        $this->db->update('vendors', $dbData);
        $this->session->set_flashdata('msg', 'vendor deleted successfully!');
        redirect('admin/vendors');
    }
    /**
     * moves row from trash to back to listing page
     * 
     * @param  integer $id id of row in trash
     * @return redirect     back to trash view
     * 
     * @since 1.0
     * @author DeDevelopers
     * @copyright Copyright (c) 2019, DeDevelopers, https://dedevelopers.com
     */
    public function restore($id){
        
        $dbData['deleted_by'] = $this->session->userdata('admin_id');
        $dbData['is_deleted'] = 0;
        $this->db->where('id',$id);
        $this->db->update('vendors', $dbData);
        $this->session->set_flashdata('msg', 'vendor restored successfully!');
        redirect('admin/trash-vendors');
    }
    
	/**
     * loads detail page of vendor
     * 
     * @param  integer $id id of vendor to view the details
     * @return view     details
     * 
     * @since 1.0
     * @author DeDevelopers
     * @copyright Copyright (c) 2019, DeDevelopers, https://dedevelopers.com
     */
	public function vendor_detail($id)
	{		
		$result = $this->vendor->get_vendor_by_id($id);

        if(!$result){

            $this->session->set_flashdata('err','Invalid request.');
            redirect('admin/404_page');

        }
		$this->data['title'] = 'vendor Detail';
		$this->data['sub'] = 'add-vendor';
		$this->data['jsfile'] = 'client_detail';
		$this->data['vendor_detail'] = $this->vendor->get_vendor_by_id($id);
		
		$this->data['content'] = $this->load->view('backend/vendors/detail',$this->data,true);
		$this->load->view('backend/common/template',$this->data);
		
		
	}
   
	
}
