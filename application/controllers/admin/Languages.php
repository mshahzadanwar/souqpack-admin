<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * handles the languages
 * 
 * @since 1.0
 * @author DeDevelopers
 * @copyright Copyright (c) 2019, DeDevelopers, https://dedevelopers.com
 */
class Languages extends ADMIN_Controller {
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
        $this->redirect_role(-1);
        $this->data['active'] = 'languages';
        $this->load->model('languages_model','language');
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

			$this->data['title'] = 'Languages';
            $this->data['sub'] = 'languages';
            $this->data['js'] = 'listing';
            $this->data['jsfile'] = 'js/languages_listing';
            $this->data['languages'] = $this->language->get_all_languages();
			$this->data['content'] = $this->load->view('backend/languages/listing',$this->data,true);
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

        $this->data['title'] = 'Trash languages';
        $this->data['sub'] = 'trash';
        $this->data['js'] = 'listing';
        $this->data['jsfile'] = 'js/languages_listing';
        $this->data['languages'] = $this->language->get_all_trash_languages();
        $this->data['content'] = $this->load->view('backend/languages/trash',$this->data,true);
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
        $this->db->update('languages', $dbData);
        $this->session->set_flashdata('msg', 'language restored successfully!');
        redirect('admin/trash-languages');
    }
    /**
     * loads the add view, then handles the submitted data
     * 
     * @since 1.0
     * @author DeDevelopers
     * @copyright Copyright (c) 2019, DeDevelopers, https://dedevelopers.com
     */
	public function add (){
        echo 1;exit;
        $this->form_validation->set_rules('title','Title','trim|required|callback_check_language');
	    $this->form_validation->set_rules('direction','Direction','trim|required');
	    //$this->form_validation->set_rules('description','Description','trim|required');
	    $this->form_validation->set_rules('image','Image','callback_image_not_required[image,20,20]');
        $this->form_validation->set_message('required','This field is required.');
	    if($this->form_validation->run() === false){
            $this->data['title'] = 'Add New language';
            $this->data['sub'] = 'add-language';
            if($_GET['replicate']==1)
            {
                $this->data['prev'] = $this->db->where('is_deleted',0)
                ->order_by('id',"DESC")
                ->get('languages')->result_object()[0];
               
            }
            $this->data['meta'] = $this->load->view('backend/common/meta_data',$this->data,true);
            $this->data['content'] = $this->load->view('backend/languages/add',$this->data,true);
            $this->load->view('backend/common/template',$this->data);
        }else{

            $dbData['title'] = $this->input->post('title');
            $dbData['slug'] = slug($this->input->post('title'));
	        $dbData['direction'] = $this->input->post('direction');
	        $dbData['created_at'] = date('Y-m-d H:i:s');
	        $dbData['created_by'] = $this->session->userdata('admin_id');
	        $dbData['updated_at'] = date('Y-m-d H:i:s');
            $dbData['updated_by'] = $this->session->userdata('admin_id');


            if((isset($_FILES['image']) && $_FILES['image']['size'] > 0))
	        $image = $this->image_upload('image','./resources/uploads/languages/','jpg|jpeg|png|gif');
	        if($image['upload'] == true || $_FILES['image']['size']<1){
                $image = $image['data'];
                if((isset($_FILES['image']) && $_FILES['image']['size'] > 0)){
                    $dbData['image'] = $image['file_name'];
                    $this->image_thumb($image['full_path'],'./resources/uploads/languages/actual_size/',20,20);
                }
                $this->db->insert('languages',$dbData);
                $this->session->set_flashdata('msg','New language added successfully!');
                redirect('admin/languages');
            }else{
	            $this->session->set_flashdata('err','An Error occurred durring uploading image, please try again');
	            redirect('admin/add-language');
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
    public function check_language($title){

	    $result = $this->language->get_language_by_title($title);
	    if(!empty($title)) {
            if ($result->num_rows() > 0) {
                $this->form_validation->set_message('check_language', 'This language already exist.');
                return false;
            } else {
                return true;
            }
        }else{
            $this->form_validation->set_message('check_language', 'This field is required.');
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

        $result = $this->language->get_language_by_id($id);

        if(!$result){

            $this->session->set_flashdata('err','Invalid request.');
            redirect('admin/404_page');

        }

        $language_status = 1;

        if($status == 1){

            $language_status = 0;

        }

        $dbData['status'] = $language_status;
        $dbData['updated_at'] = date('Y-m-d H:i:s');
        $dbData['updated_by'] = $this->session->userdata('admin_id');

        $this->db->where('id',$id);
        $this->db->update('languages',$dbData);
        $this->session->set_flashdata('msg','language status updated successfully!');
        redirect('admin/languages');
    }

    /**
     * changes default of given id row in database
     * 
     * @param  integer $id     id of row in database
     * @param  integer $default new default to set
     * @return redirect        redirects to sucess page
     * 
     * @since 1.0
     * @author DeDevelopers
     * @copyright Copyright (c) 2019, DeDevelopers, https://dedevelopers.com
     */
    public function mdefault($id,$default){

        $result = $this->language->get_language_by_id($id);

        if(!$result){

            $this->session->set_flashdata('err','Invalid request.');
            redirect('admin/404_page');

        }

        $language_status = 1;

        if($status == 1){

            $language_status = 0;

        }
        $this->db->update('languages',array("is_default"=>0));

        $dbData['is_default'] = $language_status;
        $dbData['updated_at'] = date('Y-m-d H:i:s');
        $dbData['updated_by'] = $this->session->userdata('admin_id');

        $this->db->where('id',$id);
        $this->db->update('languages',$dbData);
        $this->session->set_flashdata('msg','language default updated successfully!');
        redirect('admin/languages');
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

        $result = $this->language->get_language_by_id($id);

        if(!$result){

            $this->session->set_flashdata('err','Invalid request.');
            redirect('admin/404_page');

        }

        $this->data['data'] = $result;
        $this->form_validation->set_rules('title','Title','trim|required|callback_check_language_edit['.$id.']');
        $this->form_validation->set_rules('direction','Direction','trim|required');
        $this->form_validation->set_rules('image','Image','callback_image_not_required[image,20,20]');
        $this->form_validation->set_message('required','This field is required.');
        $this->form_validation->set_message('alpha_numeric_spaces','Only alphabet and numbers are allowed.');
        if($this->form_validation->run() === false){
            $this->data['title'] = 'Edit language';
            $this->data['meta'] = $this->load->view('backend/common/meta_data',$this->data,true);
        
            $this->data['content'] = $this->load->view('backend/languages/edit',$this->data,true);
             
            $this->load->view('backend/common/template',$this->data);
        }else{

            $dbData['title'] = $this->input->post('title');
            $dbData['slug'] = slug($this->input->post('title'));
            $dbData['direction'] = $this->input->post('direction');
            $dbData['updated_at'] = date('Y-m-d H:i:s');
            $dbData['updated_by'] = $this->session->userdata('admin_id');

            if(!empty($_FILES['image']['name'])) {
                unlink('./resources/uploads/languages/'.$this->data['data']->image);
                unlink('./resources/uploads/languages/actual_size/'.$this->data['data']->image);
                $image = $this->image_upload('image', './resources/uploads/languages/', 'jpg|jpeg|png|gif');
                if ($image['upload'] == true) {
                    $image = $image['data'];
                    $dbData['image'] = $image['file_name'];
                    $this->image_thumb($image['full_path'], './resources/uploads/languages/actual_size/', 20, 20);
                } else {

                    $this->session->set_flashdata('err', 'An Error occurred durring uploading image, please try again');
                    redirect('admin/add-language');
                }
            }
            $this->db->where('id',$id);
            $this->db->update('languages', $dbData);
            $this->session->set_flashdata('msg', 'language updated successfully!');
            redirect('admin/languages');

        }
    }
     /**
     * validation check
     * 
     * @since 1.0
     * @author DeDevelopers
     * @copyright Copyright (c) 2019, DeDevelopers, https://dedevelopers.com
     */
    public function check_language_edit($title,$id){

        $result = $this->language->get_language_by_title($title);
        if(!empty($title)) {
            if ($result->num_rows() > 0) {
                $result = $result->row();
                if($result->id == $id){
                    return true;
                }else{
                    $this->form_validation->set_message('check_language_edit', 'This language already exist.');
                    return false;
                }
            } else {
                return true;
            }
        }else{
            $this->form_validation->set_message('check_language_edit', 'This field is required.');
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
        echo 1;exit;
        $result = $this->language->get_language_by_id($id);

        if(!$result){

            $this->session->set_flashdata('err','Invalid request.');
            redirect('admin/404_page');

        }
        $dbData['deleted_by'] = $this->session->userdata('admin_id');
        $dbData['is_deleted'] = 1;
        $this->db->where('id',$id);
        $this->db->update('languages', $dbData);
        $this->session->set_flashdata('msg', 'language deleted successfully!');
        redirect('admin/languages');
    }
}
