<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * handles the Affiliates
 * 
 * @since 1.0
 * @author DeDevelopers
 * @copyright Copyright (c) 2019, DeDevelopers, https://dedevelopers.com
 */
class Affiliates extends ADMIN_Controller {
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
        $this->data['active'] = 'affiliate';
        $this->load->model('affiliates_model','affiliate');
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

			$this->data['title'] = 'affiliates';
            $this->data['sub'] = 'affiliates';
            $this->data['js'] = 'listing';
            $this->data['jsfile'] = 'js/affiliates_listing';
            $this->data['affiliates'] = $this->affiliate->get_all_affiliates();
			$this->data['content'] = $this->load->view('backend/affiliates/listing',$this->data,true);
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

            $this->data['title'] = 'Trash affiliates';
            $this->data['sub'] = 'trash';
            $this->data['js'] = 'listing';
            $this->data['jsfile'] = 'js/affiliates_listing';
            $this->data['affiliates'] = $this->affiliate->get_all_trash_affiliates();
            $this->data['content'] = $this->load->view('backend/affiliates/trash',$this->data,true);
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
        $this->db->update('affiliates', $dbData);
        $this->session->set_flashdata('msg', 'affiliate restored successfully!');
        redirect('admin/trash-affiliates');
    }
    /**
     * loads the add view, then handles the submitted data
     * 
     * @since 1.0
     * @author DeDevelopers
     * @copyright Copyright (c) 2019, DeDevelopers, https://dedevelopers.com
     */
	public function add (){

        $this->form_validation->set_rules('title','Title','trim|required|alpha_numeric_spaces|callback_check_affiliate');
        $this->form_validation->set_rules('discount_type','Discount Type','trim|required');
	    $this->form_validation->set_rules('discount','Discount','trim|required');

        $this->form_validation->set_rules('bonus_type','Bonus Type','trim|required');
        $this->form_validation->set_rules('bonus','Bonus','trim|required');
	
        $this->form_validation->set_message('required','This field is required.');
        $this->form_validation->set_message('alpha_numeric_spaces','Only alphabet and numbers are allowed.');
	    if($this->form_validation->run() === false){
            $this->data['title'] = 'Add New affiliate';
            $this->data['sub'] = 'add-affiliate';
            if($_GET['replicate']==1)
            {
                $this->data['prev'] = $this->db->where('is_deleted',0)
                ->order_by('id',"DESC")
                ->get('affiliates')->result_object()[0];
               
            }
            $this->data['meta'] = $this->load->view('backend/common/meta_data',$this->data,true);
            $this->data['content'] = $this->load->view('backend/affiliates/add',$this->data,true);
            $this->load->view('backend/common/template',$this->data);
        }else{

	        $dbData['title'] = $this->input->post('title');
	        $dbData['slug'] = slug($this->input->post('title'));


            $dbData['discount_type'] = $this->input->post('discount_type')?$this->input->post('discount_type'):0;
            
            $dbData['discount'] = $this->input->post('discount')?$this->input->post('discount'):0;


            $dbData['bonus_type'] = $this->input->post('bonus_type')?$this->input->post('bonus_type'):0;
            
            $dbData['bonus'] = $this->input->post('bonus')?$this->input->post('bonus'):0;

	        $dbData['description'] = $this->input->post('description');
	        $dbData['meta_title'] = $this->input->post('meta_title');
	        $dbData['meta_keywords '] = $this->input->post('meta_keys');
	        $dbData['meta_description'] = $this->input->post('meta_desc');
	        $dbData['created_at'] = date('Y-m-d H:i:s');
	        $dbData['created_by'] = $this->session->userdata('admin_id');
	        $dbData['updated_at'] = date('Y-m-d H:i:s');
            $dbData['updated_by'] = $this->session->userdata('admin_id');


            $this->db->insert('affiliates',$dbData);
                $this->session->set_flashdata('msg','New affiliate added successfully!');
                redirect('admin/affiliates');

        }
    }
    /**
     * validation check
     * 
     * @since 1.0
     * @author DeDevelopers
     * @copyright Copyright (c) 2019, DeDevelopers, https://dedevelopers.com
     */
    public function check_affiliate($title){

	    $result = $this->affiliate->get_affiliate_by_title($title);
	    if(!empty($title)) {
            if ($result->num_rows() > 0) {
                $this->form_validation->set_message('check_affiliate', 'This affiliate already exist.');
                return false;
            } else {
                return true;
            }
        }else{
            $this->form_validation->set_message('check_affiliate', 'This field is required.');
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

        $result = $this->affiliate->get_affiliate_by_id($id);

        if(!$result){

            $this->session->set_flashdata('err','Invalid request.');
            redirect('admin/404_page');

        }

        $affiliate_status = 1;

        if($status == 1){

            $affiliate_status = 0;

        }

        $dbData['status'] = $affiliate_status;
        $dbData['updated_at'] = date('Y-m-d H:i:s');
        $dbData['updated_by'] = $this->session->userdata('admin_id');

        $this->db->where('id',$id);
        $this->db->update('affiliates',$dbData);
        $this->session->set_flashdata('msg','affiliate status updated successfully!');
        redirect('admin/affiliates');
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

        $result = $this->affiliate->get_affiliate_by_id($id);

        if(!$result){

            $this->session->set_flashdata('err','Invalid request.');
            redirect('admin/404_page');

        }

        $this->data['data'] = $result;
        $this->form_validation->set_rules('title','Title','trim|required|alpha_numeric_spaces|callback_check_affiliate_edit['.$id.']');
        $this->form_validation->set_rules('discount_type','Discount Type','trim|required');
        $this->form_validation->set_rules('discount','Discount','trim|required');

        $this->form_validation->set_rules('bonus_type','Bonus Type','trim|required');
        $this->form_validation->set_rules('bonus','Bonus','trim|required');
        $this->form_validation->set_message('required','This field is required.');
        $this->form_validation->set_message('alpha_numeric_spaces','Only alphabet and numbers are allowed.');
        if($this->form_validation->run() === false){
            $this->data['title'] = 'Edit affiliate';
            $this->data['meta'] = $this->load->view('backend/common/meta_data',$this->data,true);
        
            $this->data['content'] = $this->load->view('backend/affiliates/edit',$this->data,true);
             
            $this->load->view('backend/common/template',$this->data);
        }else{

            $dbData['title'] = $this->input->post('title');


            $dbData['discount_type'] = $this->input->post('discount_type')?$this->input->post('discount_type'):0;
            
            $dbData['discount'] = $this->input->post('discount')?$this->input->post('discount'):0;


            $dbData['bonus_type'] = $this->input->post('bonus_type')?$this->input->post('bonus_type'):0;
            
            $dbData['bonus'] = $this->input->post('bonus')?$this->input->post('bonus'):0;

            $dbData['description'] = $this->input->post('description');
            $dbData['meta_title'] = $this->input->post('meta_title');
            $dbData['meta_keywords '] = $this->input->post('meta_keys');
            $dbData['meta_description'] = $this->input->post('meta_desc');
            $dbData['updated_at'] = date('Y-m-d H:i:s');
            $dbData['updated_by'] = $this->session->userdata('admin_id');

          
            $this->db->where('id',$id);
            $this->db->update('affiliates', $dbData);
            $this->session->set_flashdata('msg', 'affiliate updated successfully!');
            redirect('admin/affiliates');

        }
    }
    /**
     * validation check
     * 
     * @since 1.0
     * @author DeDevelopers
     * @copyright Copyright (c) 2019, DeDevelopers, https://dedevelopers.com
     */
    public function check_affiliate_edit($title,$id){

        $result = $this->affiliate->get_affiliate_by_title($title);
        if(!empty($title)) {
            if ($result->num_rows() > 0) {
                $result = $result->row();
                if($result->id == $id){
                    return true;
                }else{
                    $this->form_validation->set_message('check_affiliate_edit', 'This affiliate already exist.');
                    return false;
                }
            } else {
                return true;
            }
        }else{
            $this->form_validation->set_message('check_affiliate_edit', 'This field is required.');
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
        $result = $this->affiliate->get_affiliate_by_id($id);

        if(!$result){

            $this->session->set_flashdata('err','Invalid request.');
            redirect('admin/404_page');

        }
        $dbData['deleted_by'] = $this->session->userdata('admin_id');
        $dbData['is_deleted'] = 1;
        $this->db->where('id',$id);
        $this->db->update('affiliates', $dbData);
        $this->session->set_flashdata('msg', 'affiliate deleted successfully!');
        redirect('admin/affiliates');
    }
}
