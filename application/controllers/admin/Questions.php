<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Questions extends ADMIN_Controller {

	function __construct()
	{
		parent::__construct();
		auth();
        $this->data['active'] = 'question';
        $this->load->model('questions_model','question');


	}

	public function index()
	{

			$this->data['title'] = 'questions';
            $this->data['sub'] = 'questions';
            $this->data['js'] = 'listing';
            $this->data['jsfile'] = 'js/questions_listing';
            $this->data['questions'] = $this->question->get_all_questions();
			$this->data['content'] = $this->load->view('backend/questions/listing',$this->data,true);
			$this->load->view('backend/common/template',$this->data);

	}
    public function trash()
    {

            $this->data['title'] = 'Trash questions';
            $this->data['sub'] = 'trash';
            $this->data['js'] = 'listing';
            $this->data['jsfile'] = 'js/questions_listing';
            $this->data['questions'] = $this->question->get_all_trash_questions();
            $this->data['content'] = $this->load->view('backend/questions/trash',$this->data,true);
            $this->load->view('backend/common/template',$this->data);

    }
    public function restore($id){
        
        $dbData['deleted_by'] = $this->session->userdata('admin_id');
        $dbData['is_deleted'] = 0;
        $this->db->where('id',$id);
        $this->db->update('questions', $dbData);
        $this->session->set_flashdata('msg', 'question restored successfully!');
        redirect('admin/trash-questions');
    }
    public function view_more_choice()
    {
        echo $this->load->view('backend/questions/choice',$this->data,true);
       
    }

	public function add (){

	    $this->form_validation->set_rules('title','Title','trim|required|callback_check_question');
	    //$this->form_validation->set_rules('description','Description','trim|required');
	    $this->form_validation->set_rules('image','Image','callback_image_not_required[image,200,200]');
        $this->form_validation->set_message('required','This field is required.');
       
	    if($this->form_validation->run() === false || empty($this->input->post('choices')) ||  empty($this->input->post('correct'))){


            if(empty($this->input->post('choices')) ||  empty($this->input->post('correct')))
            {
               $this->session->set_flashdata('msg','Please add choices carefully!'); 
            }

            $this->data['title'] = 'Add New question';
            $this->data['sub'] = 'add-question';
            $this->data['jsfile'] = 'js/add_question';
            if(isset($_GET['replicate']))
            {
                $this->data['prev'] = $this->db->where('is_deleted',0)
                ->where('id',$_GET['replicate'])
                ->get('questions')->result_object()[0];
               
            }
            $this->data['meta'] = $this->load->view('backend/common/meta_data',$this->data,true);
            $this->data['content'] = $this->load->view('backend/questions/add',$this->data,true);
            $this->load->view('backend/common/template',$this->data);
        }else{

            $dbData['title'] = $this->input->post('title');
            $dbData['remember_able'] = $this->input->post('remember_able')==1?1:0;
	        $dbData['weight'] = $this->input->post('weight');


            foreach($this->input->post('choices') as $k=>$v)
                $choices[] = array(
                    "choice"=>$v,
                    "correct"=>$this->input->post('correct')[$k]==1?1:0
                );

            $choices = json_encode($choices);

            $dbData['choices'] = $choices;

	        $dbData['slug'] = slug($this->input->post('title'));
	        $dbData['description'] = $this->input->post('description');
	        $dbData['meta_title'] = $this->input->post('meta_title');
	        $dbData['meta_keywords '] = $this->input->post('meta_keys');
	        $dbData['meta_description'] = $this->input->post('meta_desc');
	        $dbData['created_at'] = date('Y-m-d H:i:s');
	        $dbData['created_by'] = $this->session->userdata('admin_id');
	        $dbData['updated_at'] = date('Y-m-d H:i:s');
            $dbData['updated_by'] = $this->session->userdata('admin_id');


            $this->db->insert('questions',$dbData);
            $this->session->set_flashdata('msg','New question added successfully!');
            redirect('admin/questions');

        }
    }

    public function check_question($title){

	    $result = $this->question->get_question_by_title($title);
	    if(!empty($title)) {
            if ($result->num_rows() > 0) {
                $this->form_validation->set_message('check_question', 'This question already exist.');
                return false;
            } else {
                return true;
            }
        }else{
            $this->form_validation->set_message('check_question', 'This field is required.');
            return false;
        }
    }


    public function status($id,$status){

        $result = $this->question->get_question_by_id($id);

        if(!$result){

            $this->session->set_flashdata('err','Invalid request.');
            redirect('admin/404_page');

        }

        $question_status = 1;

        if($status == 1){

            $question_status = 0;

        }

        $dbData['status'] = $question_status;
        $dbData['updated_at'] = date('Y-m-d H:i:s');
        $dbData['updated_by'] = $this->session->userdata('admin_id');

        $this->db->where('id',$id);
        $this->db->update('questions',$dbData);
        $this->session->set_flashdata('msg','question status updated successfully!');
        redirect('admin/questions');
    }

    public function edit($id){

        $result = $this->question->get_question_by_id($id);

        if(!$result){

            $this->session->set_flashdata('err','Invalid request.');
            redirect('admin/404_page');

        }

        $this->data['data'] = $result;
        $this->form_validation->set_rules('title','Title','trim|required|callback_check_question_edit['.$id.']');
        //$this->form_validation->set_rules('description','Description','trim|required');
        $this->form_validation->set_rules('image','Image','callback_image_not_required[image,200,200]');
        $this->form_validation->set_message('required','This field is required.');
     
        if($this->form_validation->run() === false){
            $this->data['title'] = 'Edit question';
            $this->data['meta'] = $this->load->view('backend/common/meta_data',$this->data,true);
        
            $this->data['content'] = $this->load->view('backend/questions/edit',$this->data,true);
             
            $this->load->view('backend/common/template',$this->data);
        }else{

            $dbData['title'] = $this->input->post('title');
            $dbData['description'] = $this->input->post('description');
            $dbData['meta_title'] = $this->input->post('meta_title');
            $dbData['meta_keywords '] = $this->input->post('meta_keys');
            $dbData['meta_description'] = $this->input->post('meta_desc');
            $dbData['updated_at'] = date('Y-m-d H:i:s');
            $dbData['updated_by'] = $this->session->userdata('admin_id');

            if(!empty($_FILES['image']['name'])) {
                unlink('./resources/uploads/questions/'.$this->data['data']->image);
                unlink('./resources/uploads/questions/actual_size/'.$this->data['data']->image);
                $image = $this->image_upload('image', './resources/uploads/questions/', 'jpg|jpeg|png|gif');
                if ($image['upload'] == true) {
                    $image = $image['data'];
                    $dbData['image'] = $image['file_name'];
                    $this->image_thumb($image['full_path'], './resources/uploads/questions/actual_size/', 1400, 438);
                } else {

                    $this->session->set_flashdata('err', 'An Error occurred durring uploading image, please try again');
                    redirect('admin/add-question');
                }
            }
            $this->db->where('id',$id);
            $this->db->update('questions', $dbData);
            $this->session->set_flashdata('msg', 'question updated successfully!');
            redirect('admin/questions');

        }
    }

    public function check_question_edit($title,$id){

        $result = $this->question->get_question_by_title($title);
        if(!empty($title)) {
            if ($result->num_rows() > 0) {
                $result = $result->row();
                if($result->id == $id){
                    return true;
                }else{
                    $this->form_validation->set_message('check_question_edit', 'This question already exist.');
                    return false;
                }
            } else {
                return true;
            }
        }else{
            $this->form_validation->set_message('check_question_edit', 'This field is required.');
            return false;
        }
    }

    public function delete($id){
        $result = $this->question->get_question_by_id($id);

        if(!$result){

            $this->session->set_flashdata('err','Invalid request.');
            redirect('admin/404_page');

        }
        $dbData['deleted_by'] = $this->session->userdata('admin_id');
        $dbData['is_deleted'] = 1;
        $this->db->where('id',$id);
        $this->db->update('questions', $dbData);
        $this->session->set_flashdata('msg', 'question deleted successfully!');
        redirect('admin/questions');
    }
}
