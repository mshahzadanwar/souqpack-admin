<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Faqs extends ADMIN_Controller {

	function __construct()
	{
		parent::__construct();
		auth();
        $this->redirect_role(7);
        $this->data['active'] = 'faq';
        $this->load->model('faqs_model','faq');
	}

	public function index()
	{

		$this->data['title'] = 'FAQs';
        $this->data['sub'] = 'faqs';
        $this->data['js'] = 'listing';
        $this->data['jsfile'] = 'js/faqs_listing';
        $this->data['faqs'] = $this->faq->get_all_faqs();
		$this->data['content'] = $this->load->view('backend/faqs/listing',$this->data,true);
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

        $this->data['title'] = 'Trash FAQs';
        $this->data['sub'] = 'trash';
        $this->data['js'] = 'listing';
        $this->data['jsfile'] = 'js/faqs_listing';
        $this->data['faqs'] = $this->faq->get_all_trash_faqs();
        $this->data['content'] = $this->load->view('backend/faqs/trash',$this->data,true);
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
        $this->db->update('faqs', $dbData);
        $this->session->set_flashdata('msg', 'faq restored successfully!');
        redirect('admin/trash-faqs');
    }
    /**
     * loads the add view, then handles the submitted data
     * 
     * @since 1.0
     * @author DeDevelopers
     * @copyright Copyright (c) 2019, DeDevelopers, https://dedevelopers.com
     */
	public function add (){

        $dlang = dlang();
        $langs = langs();
        $input = $dlang->slug."[title]";
        $this->form_validation->set_rules($input,'Title','trim|required');

	   
        $this->form_validation->set_message('required','This field is required.');
       
	    if($this->form_validation->run() === false){
            $this->data['title'] = 'Add New FAQ';
            $this->data['sub'] = 'add-faq';
            $this->data['meta'] = $this->load->view('backend/common/meta_data',$this->data,true);
            if($_GET['replicate']==1)
            {
                $this->data['prev'] = $this->db->where('is_deleted',0)
                ->order_by('id',"DESC")
                ->get('faqs')->result_object()[0];
               
            }
            $this->data['content'] = $this->load->view('backend/faqs/add',$this->data,true);
            $this->load->view('backend/common/template',$this->data);
        }else{
            $def_key=0;
            $def_parent=0;
            $fault = false;
            foreach($langs as $key=>$lang){

                $dbData = array();
	            $input = $lang->slug."[title]";
                $dbData['title'] = $this->input->post($input);
	            $dbData['slug'] = slug($this->input->post($input));
                $input = $lang->slug."[description]";

	            $dbData['description'] = $this->input->post($input);
	        // $dbData['meta_title'] = $this->input->post('meta_title');
	        // $dbData['meta_keywords '] = $this->input->post('meta_keys');
	        // $dbData['meta_description'] = $this->input->post('meta_desc');
    	        $dbData['created_at'] = date('Y-m-d H:i:s');
    	        $dbData['created_by'] = $this->session->userdata('admin_id');
    	        $dbData['updated_at'] = date('Y-m-d H:i:s');
                $dbData['updated_by'] = $this->session->userdata('admin_id');
                $dbData["lparent"] = $def_key;
                $dbData["lang_id"] = $lang->id;

                $this->db->insert('faqs',$dbData);
                if($def_key==0)
                    $def_key = $this->db->insert_id();
            }
            $this->session->set_flashdata('msg','New faq added successfully!');
            redirect('admin/faqs');
        }
    }
    /**
     * validation check
     * 
     * @since 1.0
     * @author DeDevelopers
     * @copyright Copyright (c) 2019, DeDevelopers, https://dedevelopers.com
     */
    public function check_faq($title){

	    $result = $this->faq->get_faq_by_title($title);
	    if(!empty($title)) {
            if ($result->num_rows() > 0) {
                $this->form_validation->set_message('check_faq', 'This faq already exist.');
                return false;
            } else {
                return true;
            }
        }else{
            $this->form_validation->set_message('check_faq', 'This field is required.');
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

        $result = $this->faq->get_faq_by_id($id);

        if(!$result){

            $this->session->set_flashdata('err','Invalid request.');
            redirect('admin/404_page');

        }

        $faq_status = 1;

        if($status == 1){

            $faq_status = 0;

        }

        $dbData['status'] = $faq_status;
        $dbData['updated_at'] = date('Y-m-d H:i:s');
        $dbData['updated_by'] = $this->session->userdata('admin_id');

        $this->db->where('id',$id);
        $this->db->update('faqs',$dbData);
        $this->session->set_flashdata('msg','FAQ status updated successfully!');
        redirect('admin/faqs');
    }

    public function edit($id){

        $result = $this->faq->get_faq_by_id($id);
        $this->data["the_id"] = $id;
        if(!$result){

            $this->session->set_flashdata('err','Invalid request.');
            redirect('admin/404_page');

        }




        $this->data['data'] = $result;


        $dlang = dlang();
        $langs = langs();
        $input = $dlang->slug."[title]";
        $this->form_validation->set_rules($input,'Title','trim|required|callback_check_faq_edit['.$id.']');
       
        $this->form_validation->set_message('required','This field is required.');
     
        if($this->form_validation->run() === false){
            $this->data['title'] = 'Edit faq';
            $this->data['meta'] = $this->load->view('backend/common/meta_data',$this->data,true);
        
            $this->data['content'] = $this->load->view('backend/faqs/edit',$this->data,true);
             
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

                $input = $lang->slug."[description]";

                $dbData['description'] = $this->input->post($input);
                // $dbData['meta_title'] = $this->input->post('meta_title');
                // $dbData['meta_keywords '] = $this->input->post('meta_keys');
                // $dbData['meta_description'] = $this->input->post('meta_desc');
                $dbData['updated_at'] = date('Y-m-d H:i:s');
                $dbData['updated_by'] = $this->session->userdata('admin_id');

                
                $this->db->where('id',$row_id);
                $this->db->update('faqs', $dbData);
                
            }
            $this->session->set_flashdata('msg', 'faq updated successfully!');
            redirect('admin/faqs');

        }
    }
    /**
     * validation check
     * 
     * @since 1.0
     * @author DeDevelopers
     * @copyright Copyright (c) 2019, DeDevelopers, https://dedevelopers.com
     */
    public function check_faq_edit($title,$id){

        $result = $this->faq->get_faq_by_title($title);
        if(!empty($title)) {
            if ($result->num_rows() > 0) {
                $result = $result->row();
                if($result->id == $id){
                    return true;
                }else{
                    $this->form_validation->set_message('check_faq_edit', 'This faq already exist.');
                    return false;
                }
            } else {
                return true;
            }
        }else{
            $this->form_validation->set_message('check_faq_edit', 'This field is required.');
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
        $result = $this->faq->get_faq_by_id($id);

        if(!$result){

            $this->session->set_flashdata('err','Invalid request.');
            redirect('admin/404_page');

        }
        $dbData['deleted_by'] = $this->session->userdata('admin_id');
        $dbData['is_deleted'] = 1;
        $this->db->where('id',$id);
        $this->db->update('faqs', $dbData);
        $this->session->set_flashdata('msg', 'faq deleted successfully!');
        redirect('admin/faqs');
    }
}
