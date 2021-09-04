<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Questionnaires extends ADMIN_Controller {

	function __construct()
	{
		parent::__construct();
		auth();
        $this->data['active'] = 'questionnaire';
        $this->load->model('questionnaires_model','questionnaire');
	}

	public function index()
	{

			$this->data['title'] = 'questionnaires';
            $this->data['sub'] = 'questionnaires';
            $this->data['js'] = 'listing';
            $this->data['jsfile'] = 'js/questionnaires_listing';
            $this->data['questionnaires'] = $this->questionnaire->get_all_questionnaires();
			$this->data['content'] = $this->load->view('backend/questionnaires/listing',$this->data,true);
			$this->load->view('backend/common/template',$this->data);

	}
    public function trash()
    {

            $this->data['title'] = 'Trash questionnaires';
            $this->data['sub'] = 'trash';
            $this->data['js'] = 'listing';
            $this->data['jsfile'] = 'js/questionnaires_listing';
            $this->data['questionnaires'] = $this->questionnaire->get_all_trash_questionnaires();
            $this->data['content'] = $this->load->view('backend/questionnaires/trash',$this->data,true);
            $this->load->view('backend/common/template',$this->data);

    }
    public function restore($id){
        
        $dbData['deleted_by'] = $this->session->userdata('admin_id');
        $dbData['is_deleted'] = 0;
        $this->db->where('id',$id);
        $this->db->update('questionnaires', $dbData);
        $this->session->set_flashdata('msg', 'questionnaire restored successfully!');
        redirect('admin/trash-questionnaires');
    }

	public function add (){

	    $this->form_validation->set_rules('title','Title','trim|required|alpha_numeric_spaces|callback_check_questionnaire');
	    //$this->form_validation->set_rules('description','Description','trim|required');
	    $this->form_validation->set_rules('image','Image','callback_image_not_required[image,200,200]');
        $this->form_validation->set_message('required','This field is required.');
        $this->form_validation->set_message('alpha_numeric_spaces','Only alphabet and numbers are allowed.');
	    if($this->form_validation->run() === false){
            $this->data['title'] = 'Add New questionnaire';
            $this->data['sub'] = 'add-questionnaire';
            if(isset($_GET['replicate']))
            {
                $this->data['prev'] = $this->db->where('is_deleted',0)
                ->where('id',$_GET["replicate"])
                ->get('questionnaires')->result_object()[0];
               
            }
            $this->data['meta'] = $this->load->view('backend/common/meta_data',$this->data,true);
            $this->data['questions'] = $this->db->where('is_deleted',0)
            ->get('questions')
            ->result_object();
            $this->data['content'] = $this->load->view('backend/questionnaires/add',$this->data,true);
            $this->load->view('backend/common/template',$this->data);
        }else{

            $dbData['title'] = $this->input->post('title');
	        $dbData['qs'] = implode(',',$this->input->post('qs'));
	        $dbData['slug'] = slug($this->input->post('title'));
	        $dbData['description'] = $this->input->post('description');
	        $dbData['meta_title'] = $this->input->post('meta_title');
	        $dbData['meta_keywords '] = $this->input->post('meta_keys');
	        $dbData['meta_description'] = $this->input->post('meta_desc');
	        $dbData['created_at'] = date('Y-m-d H:i:s');
	        $dbData['created_by'] = $this->session->userdata('admin_id');
	        $dbData['updated_at'] = date('Y-m-d H:i:s');
            $dbData['updated_by'] = $this->session->userdata('admin_id');


            if((isset($_FILES['image']) && $_FILES['image']['size'] > 0))
	        $image = $this->image_upload('image','./resources/uploads/questionnaires/','jpg|jpeg|png|gif');
	        if($image['upload'] == true || $_FILES['image']['size']<1){
                $image = $image['data'];
                if((isset($_FILES['image']) && $_FILES['image']['size'] > 0)){
                    $dbData['image'] = $image['file_name'];
                    $this->image_thumb($image['full_path'],'./resources/uploads/questionnaires/actual_size/',200,200);
                }
                $this->db->insert('questionnaires',$dbData);
                $this->session->set_flashdata('msg','New questionnaire added successfully!');
                redirect('admin/questionnaires');
            }else{
                print_r($image);exit;

	            $this->session->set_flashdata('err','An Error occurred durring uploading image, please try again');
	            redirect('admin/add-questionnaire');
            }

        }
    }

    public function check_questionnaire($title){

	    $result = $this->questionnaire->get_questionnaire_by_title($title);
	    if(!empty($title)) {
            if ($result->num_rows() > 0) {
                $this->form_validation->set_message('check_questionnaire', 'This questionnaire already exist.');
                return false;
            } else {
                return true;
            }
        }else{
            $this->form_validation->set_message('check_questionnaire', 'This field is required.');
            return false;
        }
    }


    public function status($id,$status){

        $result = $this->questionnaire->get_questionnaire_by_id($id);

        if(!$result){

            $this->session->set_flashdata('err','Invalid request.');
            redirect('admin/404_page');

        }

        $questionnaire_status = 1;

        if($status == 1){

            $questionnaire_status = 0;

        }

        $dbData['status'] = $questionnaire_status;
        $dbData['updated_at'] = date('Y-m-d H:i:s');
        $dbData['updated_by'] = $this->session->userdata('admin_id');

        $this->db->where('id',$id);
        $this->db->update('questionnaires',$dbData);
        $this->session->set_flashdata('msg','questionnaire status updated successfully!');
        redirect('admin/questionnaires');
    }

    public function edit($id){

        $result = $this->questionnaire->get_questionnaire_by_id($id);

        if(!$result){

            $this->session->set_flashdata('err','Invalid request.');
            redirect('admin/404_page');

        }

        $this->data['data'] = $result;
        $this->form_validation->set_rules('title','Title','trim|required|alpha_numeric_spaces|callback_check_questionnaire_edit['.$id.']');
        //$this->form_validation->set_rules('description','Description','trim|required');
        $this->form_validation->set_rules('image','Image','callback_image_not_required[image,200,200]');
        $this->form_validation->set_message('required','This field is required.');
        $this->form_validation->set_message('alpha_numeric_spaces','Only alphabet and numbers are allowed.');
        if($this->form_validation->run() === false){
            $this->data['title'] = 'Edit questionnaire';
            $this->data['meta'] = $this->load->view('backend/common/meta_data',$this->data,true);

            $this->data['questions'] = $this->db->where('is_deleted',0)
            ->get('questions')
            ->result_object();
        
            $this->data['content'] = $this->load->view('backend/questionnaires/edit',$this->data,true);
             
            $this->load->view('backend/common/template',$this->data);
        }else{

            $dbData['title'] = $this->input->post('title');
            $dbData['qs'] = implode(',',$this->input->post('qs'));

            $dbData['description'] = $this->input->post('description');
            $dbData['meta_title'] = $this->input->post('meta_title');
            $dbData['meta_keywords '] = $this->input->post('meta_keys');
            $dbData['meta_description'] = $this->input->post('meta_desc');
            $dbData['updated_at'] = date('Y-m-d H:i:s');
            $dbData['updated_by'] = $this->session->userdata('admin_id');

            if(!empty($_FILES['image']['name'])) {
                unlink('./resources/uploads/questionnaires/'.$this->data['data']->image);
                unlink('./resources/uploads/questionnaires/actual_size/'.$this->data['data']->image);
                $image = $this->image_upload('image', './resources/uploads/questionnaires/', 'jpg|jpeg|png|gif');
                if ($image['upload'] == true) {
                    $image = $image['data'];
                    $dbData['image'] = $image['file_name'];
                    $this->image_thumb($image['full_path'], './resources/uploads/questionnaires/actual_size/', 1400, 438);
                } else {

                    $this->session->set_flashdata('err', 'An Error occurred durring uploading image, please try again');
                    redirect('admin/add-questionnaire');
                }
            }
            $this->db->where('id',$id);
            $this->db->update('questionnaires', $dbData);
            $this->session->set_flashdata('msg', 'questionnaire updated successfully!');
            redirect('admin/questionnaires');

        }
    }

    public function check_questionnaire_edit($title,$id){

        $result = $this->questionnaire->get_questionnaire_by_title($title);
        if(!empty($title)) {
            if ($result->num_rows() > 0) {
                $result = $result->row();
                if($result->id == $id){
                    return true;
                }else{
                    $this->form_validation->set_message('check_questionnaire_edit', 'This questionnaire already exist.');
                    return false;
                }
            } else {
                return true;
            }
        }else{
            $this->form_validation->set_message('check_questionnaire_edit', 'This field is required.');
            return false;
        }
    }

    public function delete($id){
        $result = $this->questionnaire->get_questionnaire_by_id($id);

        if(!$result){

            $this->session->set_flashdata('err','Invalid request.');
            redirect('admin/404_page');

        }
        $dbData['deleted_by'] = $this->session->userdata('admin_id');
        $dbData['is_deleted'] = 1;
        $this->db->where('id',$id);
        $this->db->update('questionnaires', $dbData);
        $this->session->set_flashdata('msg', 'questionnaire deleted successfully!');
        redirect('admin/questionnaires');
    }
}
