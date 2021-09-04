<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class blogs extends ADMIN_Controller {

	function __construct()
	{
		parent::__construct();
		auth();
        $this->redirect_role(12);

        $this->data['active'] = 'blog';
        $this->load->model('blogs_model','page');
	}

	public function index()
	{

		$this->data['title'] = 'Blogs';
        $this->data['sub'] = 'blogs';
        $this->data['js'] = 'listing';
        $this->data['jsfile'] = 'js/blogs_listing';
        $this->data['blogs'] = $this->page->get_all_blogs();
		$this->data['content'] = $this->load->view('backend/blogs/listing',$this->data,true);
		$this->load->view('backend/common/template',$this->data);

	}
    public function trash()
    {

        $this->data['title'] = 'Trash blogs';
        $this->data['sub'] = 'trash';
        $this->data['js'] = 'listing';
        $this->data['jsfile'] = 'js/blogs_listing';
        $this->data['blogs'] = $this->page->get_all_trash_blogs();
        $this->data['content'] = $this->load->view('backend/blogs/trash',$this->data,true);
        $this->load->view('backend/common/template',$this->data);

    }
    public function restore($id){
        
        $dbData['deleted_by'] = $this->session->userdata('admin_id');
        $dbData['is_deleted'] = 0;
        $this->db->where('id',$id);
        $this->db->update('blogs', $dbData);
        $this->session->set_flashdata('msg', 'page restored successfully!');
        redirect('admin/trash-blogs');
    }

	public function add (){


        $dlang = dlang();
        $langs = langs();
        $input = $dlang->slug."[title]";
        $this->form_validation->set_rules($input,'Title','trim|required');

        $input = $dlang->slug."_image";
	    
	    $this->form_validation->set_rules($input,'Image','callback_image_not_required['.$input.',10,10]');


        $this->form_validation->set_message('required','This field is required.');
        $this->form_validation->set_message('alpha_numeric_spaces','Only alphabet and numbers are allowed.');
	    if($this->form_validation->run() === false){
            $this->data['title'] = 'Add New Blog';
            $this->data['sub'] = 'add-blog';
            if($_GET['replicate']==1)
            {
                $this->data['prev'] = $this->db->where('is_deleted',0)
                ->order_by('id',"DESC")
                ->get('blogs')->result_object()[0];
               
            }
            $this->data['meta'] = $this->load->view('backend/common/meta_data',$this->data,true);
            $this->data['content'] = $this->load->view('backend/blogs/add',$this->data,true);
            $this->load->view('backend/common/template',$this->data);
        }else{



	        $def_key=0;
            $def_parent=0;
            $fault = false;
            foreach($langs as $key=>$lang){

                $dbData = array();
                $input = $lang->slug."[title]";
                $dbData['title'] = $this->input->post($input);
    	        $dbData['slug'] = slug($this->input->post('title'));

                $input = $lang->slug."[descriptions]";

    	        $dbData['descriptions'] = $this->input->post($input);
    	        // $dbData['meta_title'] = $this->input->post('meta_title');
    	        // $dbData['meta_keywords '] = $this->input->post('meta_keys');
    	        // $dbData['meta_description'] = $this->input->post('meta_desc');


                $input = $lang->slug."[meta_title]";
                $dbData['meta_title'] = $this->input->post($input);
                $input = $lang->slug."[meta_keys]";
                $dbData['meta_keywords '] = $this->input->post($input);
                $input = $lang->slug."[meta_desc]";
                $dbData['meta_description'] = $this->input->post($input);



    	        $dbData['created_at'] = date('Y-m-d H:i:s');
    	        $dbData['created_by'] = $this->session->userdata('admin_id');
    	        $dbData['updated_at'] = date('Y-m-d H:i:s');
                $dbData['updated_by'] = $this->session->userdata('admin_id');


                $dbData["lparent"] = $def_key;
                $dbData["lang_id"] = $lang->id;

                $input = $lang->slug."_image";
                if((isset($_FILES[$input]) && $_FILES[$input]['size'] > 0))
    	        $image = $this->image_upload($input,'./resources/uploads/blogs/','jpg|jpeg|png|gif');
    	        if($image['upload'] == true || $_FILES[$input]['size']<1){
                    $image = $image['data'];
                    if((isset($_FILES[$input]) && $_FILES[$input]['size'] > 0)){
                        $dbData['image'] = $image['file_name'];
                        $this->image_thumb($image['full_path'],'./resources/uploads/blogs/actual_size/',10,10);
                    }
                    $this->db->insert('blogs',$dbData);

                    if($def_key==0)
                    $def_key = $this->db->insert_id();
                    
                }else{

    	            $fault = true;
                    break;
                }
            }

            if($fault)
            {
                $this->session->set_flashdata('err','An Error occurred durring uploading image, please try again');
                    redirect('admin/add-blog');
            }
            else{
                $this->session->set_flashdata('msg','New blog added successfully!');
                    redirect('admin/blogs');
            }

        }
    }

    public function check_blog($title){

	    $result = $this->page->get_blog_by_title($title);
	    if(!empty($title)) {
            if ($result->num_rows() > 0) {
                $this->form_validation->set_message('check_blog', 'This blog already exist.');
                return false;
            } else {
                return true;
            }
        }else{
            $this->form_validation->set_message('check_blog', 'This field is required.');
            return false;
        }
    }


    public function status($id,$status){

        $result = $this->page->get_blog_by_id($id);

        if(!$result){

            $this->session->set_flashdata('err','Invalid request.');
            redirect('admin/404_page');

        }

        $page_status = 1;

        if($status == 1){

            $page_status = 0;

        }

        $dbData['status'] = $page_status;
        $dbData['updated_at'] = date('Y-m-d H:i:s');
        $dbData['updated_by'] = $this->session->userdata('admin_id');

        $this->db->where('id',$id);
        $this->db->update('blogs',$dbData);
        $this->session->set_flashdata('msg','blog status updated successfully!');
        redirect('admin/blogs');
    }

    public function edit($id){

        $result = $this->page->get_blog_by_id($id);
        $this->data["the_id"] = $id;

        if(!$result){

            $this->session->set_flashdata('err','Invalid request.');
            redirect('admin/404_page');

        }

        $this->data['data'] = $result;


        $dlang = dlang();
        $langs = langs();
        $input = $dlang->slug."[title]";
        $this->form_validation->set_rules($input,'Title','trim|required|callback_check_blog_edit['.$id.']');

        
        $this->form_validation->set_message('required','This field is required.');
        $this->form_validation->set_message('alpha_numeric_spaces','Only alphabet and numbers are allowed.');
        if($this->form_validation->run() === false){
            $this->data['title'] = 'Edit blog';
            $this->data['meta'] = $this->load->view('backend/common/meta_data',$this->data,true);
           
            $this->data['content'] = $this->load->view('backend/blogs/edit',$this->data,true);
             
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

                $input = $lang->slug."[slug]";
                $dbData['slug'] = $this->input->post($input);

                // $dbData['slug'] = $this->input->post('slug');

                $input = $lang->slug."[descriptions]";
                $dbData['descriptions'] = $this->input->post($input);
                // $dbData['meta_title'] = $this->input->post('meta_title');
                // $dbData['meta_keywords '] = $this->input->post('meta_keys');
                // $dbData['meta_description'] = $this->input->post('meta_desc');



                $input = $lang->slug."[meta_title]";
                $dbData['meta_title'] = $this->input->post($input);
                $input = $lang->slug."[meta_keys]";
                $dbData['meta_keywords '] = $this->input->post($input);
                $input = $lang->slug."[meta_desc]";
                $dbData['meta_description'] = $this->input->post($input);

                
                $dbData['updated_at'] = date('Y-m-d H:i:s');
                $dbData['updated_by'] = $this->session->userdata('admin_id');

                $input = $lang->slug."_image";
                if(!empty($_FILES[$input]['name'])) {
                    unlink('./resources/uploads/blogs/'.$this->data['data']->image);
                    unlink('./resources/uploads/blogs/actual_size/'.$this->data['data']->image);
                    $image = $this->image_upload($input, './resources/uploads/blogs/', 'jpg|jpeg|png|gif');
                    if ($image['upload'] == true) {
                        $image = $image['data'];
                        $dbData["image"] = $image['file_name'];
                        $this->image_thumb($image['full_path'], './resources/uploads/blogs/actual_size/', 10, 10);
                    } 
                }
                $this->db->where('id',$row_id);
                $this->db->update('blogs', $dbData);
            }

            $this->session->set_flashdata('msg', 'page updated successfully!');
            redirect('admin/blogs');
        }
    }

    public function check_blog_edit($title,$id){

        $result = $this->page->get_blog_by_title($title);
        if(!empty($title)) {
            if ($result->num_rows() > 0) {
                $result = $result->row();
                if($result->id == $id){
                    return true;
                }else{
                    $this->form_validation->set_message('check_blog_edit', 'This page already exist.');
                    return false;
                }
            } else {
                return true;
            }
        }else{
            $this->form_validation->set_message('check_blog_edit', 'This field is required.');
            return false;
        }
    }

    public function delete($id){
        $result = $this->page->get_blog_by_id($id);

        if(!$result){

            $this->session->set_flashdata('err','Invalid request.');
            redirect('admin/404_page');

        }
        $dbData['deleted_by'] = $this->session->userdata('admin_id');
        $dbData['is_deleted'] = 1;
        $this->db->where('id',$id);
        $this->db->update('blogs', $dbData);
        $this->session->set_flashdata('msg', 'blog deleted successfully!');
        redirect('admin/blogs');
    }
}
