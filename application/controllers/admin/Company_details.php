<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * handles the Company Details
 * 
 * @since 1.0
 * @author DeDevelopers
 * @copyright Copyright (c) 2019, DeDevelopers, https://dedevelopers.com
 */
class Company_details extends ADMIN_Controller {
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
        $this->data['active'] = 'company-details';
        $this->load->model('company_details_model','company_detail');
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

			$this->data['title'] = 'Company Detail';
            $this->data['sub'] = 'company-details';
            $this->data['js'] = 'listing';
            $this->data['jsfile'] = 'js/company_details_listing';
            $this->data['company_details'] = $this->company_detail->get_all_company_details();
			$this->data['content'] = $this->load->view('backend/company_details/listing',$this->data,true);
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

            $this->data['title'] = 'Trash Company Detail';
            $this->data['sub'] = 'trash';
            $this->data['js'] = 'listing';
            $this->data['jsfile'] = 'js/company_details_listing';
            $this->data['company_details'] = $this->company_detail->get_all_trash_company_details();
            $this->data['content'] = $this->load->view('backend/company_details/trash',$this->data,true);
            $this->load->view('backend/common/template',$this->data);

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
        $this->db->update('company_details', $dbData);
        $this->session->set_flashdata('msg', 'Company Detail restored successfully!');
        redirect('admin/trash-company_details');
    }
    /**
     * loads the add view, then handles the submitted data
     * 
     * @since 1.0
     * @author DeDevelopers
     * @copyright Copyright (c) 2019, DeDevelopers, https://dedevelopers.com
     */
	public function add (){

	    $this->form_validation->set_rules('company_name','Company Name','trim|required|alpha_numeric_spaces|callback_check_company_detail');
	    $this->form_validation->set_rules('image','Image','callback_image_not_required[image,200,200]');
        $this->form_validation->set_message('required','This field is required.');
        $this->form_validation->set_message('alpha_numeric_spaces','Only alphabet and numbers are allowed.');
	    if($this->form_validation->run() === false){
            $this->data['title'] = 'Add New Company Detail';
            $this->data['sub'] = 'add-company-detail';
            if($_GET['replicate']==1)
            {
                $this->data['prev'] = $this->db->where('is_deleted',0)
                ->order_by('id',"DESC")
                ->get('company_details')->result_object()[0];
               
            }
           
            $this->data['content'] = $this->load->view('backend/company_details/add',$this->data,true);
            $this->load->view('backend/common/template',$this->data);
        }else{

            $dbData['title'] = $this->input->post('company_name');
            $dbData['email'] = $this->input->post('email');
            $dbData['phone'] = $this->input->post('phone');
            $dbData['mobile'] = $this->input->post('mobile');
	        $dbData['address'] = $this->input->post('address');
	        $dbData['description'] = $this->input->post('description');
	       
	        $dbData['created_at'] = date('Y-m-d H:i:s');
	        $dbData['created_by'] = $this->session->userdata('admin_id');
	        $dbData['updated_at'] = date('Y-m-d H:i:s');
            $dbData['updated_by'] = $this->session->userdata('admin_id');


            if((isset($_FILES['image']) && $_FILES['image']['size'] > 0))
	        $image = $this->image_upload('image','./resources/uploads/company_details/','jpg|jpeg|png|gif');
	        if($image['upload'] == true || $_FILES['image']['size']<1){
                $image = $image['data'];
                if((isset($_FILES['image']) && $_FILES['image']['size'] > 0)){
                    $dbData['image'] = $image['file_name'];
                    $this->image_thumb($image['full_path'],'./resources/uploads/company_details/actual_size/',200,200);
                }
                $this->db->insert('company_details',$dbData);
                $this->session->set_flashdata('msg','New Company Detail added successfully!');
                redirect('admin/company-details');
            }else{
                print_r($image);exit;

	            $this->session->set_flashdata('err','An Error occurred durring uploading image, please try again');
	            redirect('admin/add-company-detail');
            }

        }
    }
     /**
     * validation check
     * 
     * @since 1.0
     * @author DeDevelopers
     * @copyright Copyright (c) 2019, DeDevelopers, https://dedevelopers.com
     */
    public function check_company_detail($title){

	    $result = $this->company_detail->get_company_detail_by_title($title);
	    if(!empty($title)) {
            if ($result->num_rows() > 0) {
                $this->form_validation->set_message('check_company_detail', 'This Company Detail already exist.');
                return false;
            } else {
                return true;
            }
        }else{
            $this->form_validation->set_message('check_company_detail', 'This field is required.');
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

        $result = $this->company_detail->get_company_detail_by_id($id);

        if(!$result){

            $this->session->set_flashdata('err','Invalid request.');
            redirect('admin/404_page');

        }

        $company_detail_status = 1;

        if($status == 1){

            $company_detail_status = 0;

        }

        $dbData['status'] = $company_detail_status;
        $dbData['updated_at'] = date('Y-m-d H:i:s');
        $dbData['updated_by'] = $this->session->userdata('admin_id');

        $this->db->where('id',$id);
        $this->db->update('company_details',$dbData);
        $this->session->set_flashdata('msg','Company Detail status updated successfully!');
        redirect('admin/company-details');
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

        $result = $this->company_detail->get_company_detail_by_id($id);

        if(!$result){

            $this->session->set_flashdata('err','Invalid request.');
            redirect('admin/404_page');

        }

        $this->data['data'] = $result;
        $this->form_validation->set_rules('company_name','Company Name','trim|required|alpha_numeric_spaces|callback_check_company_detail_edit['.$id.']');
       
        $this->form_validation->set_rules('image','Image','callback_image_not_required[image,200,200]');
        $this->form_validation->set_message('required','This field is required.');
        $this->form_validation->set_message('alpha_numeric_spaces','Only alphabet and numbers are allowed.');
        if($this->form_validation->run() === false){
            $this->data['title'] = 'Edit Company Detail';
            
            $this->data['content'] = $this->load->view('backend/company_details/edit',$this->data,true);
             
            $this->load->view('backend/common/template',$this->data);
        }else{

            $dbData['title'] = $this->input->post('company_name');
            $dbData['email'] = $this->input->post('email');
            $dbData['phone'] = $this->input->post('phone');
            $dbData['mobile'] = $this->input->post('mobile');
            $dbData['address'] = $this->input->post('address');
            $dbData['description'] = $this->input->post('description');
            $dbData['updated_at'] = date('Y-m-d H:i:s');
            $dbData['updated_by'] = $this->session->userdata('admin_id');

            if(!empty($_FILES['image']['name'])) {
                unlink('./resources/uploads/company_details/'.$this->data['data']->image);
                unlink('./resources/uploads/company_details/actual_size/'.$this->data['data']->image);
                $image = $this->image_upload('image', './resources/uploads/company_details/', 'jpg|jpeg|png|gif');
                if ($image['upload'] == true) {
                    $image = $image['data'];
                    $dbData['image'] = $image['file_name'];
                    $this->image_thumb($image['full_path'], './resources/uploads/company_details/actual_size/', 1400, 438);
                } else {

                    $this->session->set_flashdata('err', 'An Error occurred durring uploading image, please try again');
                    redirect($_SERVER['HTTP_REFERER']);
                }
            }
            $this->db->where('id',$id);
            $this->db->update('company_details', $dbData);
            $this->session->set_flashdata('msg', 'Company Detail updated successfully!');
            redirect('admin/company-details');

        }
    }
     /**
     * validation check
     * 
     * @since 1.0
     * @author DeDevelopers
     * @copyright Copyright (c) 2019, DeDevelopers, https://dedevelopers.com
     */
    public function check_company_detail_edit($title,$id){

        $result = $this->company_detail->get_company_detail_by_title($title);
        if(!empty($title)) {
            if ($result->num_rows() > 0) {
                $result = $result->row();
                if($result->id == $id){
                    return true;
                }else{
                    $this->form_validation->set_message('check_company_detail_edit', 'This Company Detail already exist.');
                    return false;
                }
            } else {
                return true;
            }
        }else{
            $this->form_validation->set_message('check_company_detail_edit', 'This field is required.');
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
        $result = $this->company_detail->get_company_detail_by_id($id);

        if(!$result){

            $this->session->set_flashdata('err','Invalid request.');
            redirect('admin/404_page');

        }
        $dbData['deleted_by'] = $this->session->userdata('admin_id');
        $dbData['is_deleted'] = 1;
        $this->db->where('id',$id);
        $this->db->update('company_details', $dbData);
        $this->session->set_flashdata('msg', 'Company Detail deleted successfully!');
        redirect('admin/company-details');
    }
}
