<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * handles the forms
 * 
 * @since 1.0
 * @author DeDevelopers
 * @copyright Copyright (c) 2019, DeDevelopers, https://dedevelopers.com
 */
class Forms extends ADMIN_Controller {
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
        $this->data['active'] = 'form';
        $this->load->model('forms_model','form');
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

        $this->data['title'] = 'forms';
        $this->data['sub'] = 'forms';
        $this->data['js'] = 'listing';
        $this->data['jsfile'] = 'js/forms_listing';
        $this->data['forms'] = $this->form->get_all_forms();
        $this->data['content'] = $this->load->view('backend/forms/listing',$this->data,true);
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

        $this->data['title'] = 'Trash forms';
        $this->data['sub'] = 'trash';
        $this->data['js'] = 'listing';
        $this->data['jsfile'] = 'js/forms_listing';
        $this->data['forms'] = $this->form->get_all_trash_forms();
        $this->data['content'] = $this->load->view('backend/forms/trash',$this->data,true);
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
        $this->db->update('forms', $dbData);
        $this->session->set_flashdata('msg', 'form restored successfully!');
        redirect('admin/trash-forms');
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
        
        $input = "title";
        $this->form_validation->set_rules($input,'Title','trim|required');

        $input = "image";
        $this->form_validation->set_rules($input,'Image','callback_image_not_required['.$input.',20,20]');
        $this->form_validation->set_message('required','This field is required.');
        $this->form_validation->set_message('alpha_numeric_spaces','Only alphabet and numbers are allowed.');
        if($this->form_validation->run() === false){
            $this->data['title'] = 'Add New form';
            $this->data['sub'] = 'add-form';
            $this->data['jsfile'] = 'js/add_form';
            $this->data['forms'] = $this->db->where('is_deleted',0)
            ->where('parent',0)
            ->where('lparent',0)
            ->get('forms');
            $this->data['categories'] = $this->db->where('is_deleted',0)
            ->where('parent',0)
            ->where('lparent',0)

            ->get('categories');
            if(isset($_GET['replicate']))
            {
                $this->data['prev'] = $this->db->where('is_deleted',0)
                ->where('id',$_GET['replicate'])
                ->get('forms')->result_object()[0];
            }
            $this->data['meta'] = $this->load->view('backend/common/meta_data',$this->data,true);
            $this->data['content'] = $this->load->view('backend/forms/add',$this->data,true);
            $this->load->view('backend/common/template',$this->data);
        }else{
            
            
                $input = "title";
                $dbData['title'] = $this->input->post($input);


                $input = "category";
                $dbData['category'] = $this->input->post($input);


                $input = "applies_on_date";
                $dbData['applies_on_date'] = $this->input->post($input)==1?1:0;


                 $input = "featured";
                $dbData['featured'] = $this->input->post($input)==1?1:0;

                $input = "start_date";
                $dbData['start_date'] = $this->input->post($input);

                $input = "end_date";
                $dbData['end_date'] = $this->input->post($input);

                
                $input = "description";
                $dbData['description'] = $this->input->post($input);
                
                $dbData['created_at'] = date('Y-m-d H:i:s');
                $dbData['created_by'] = $this->session->userdata('admin_id');
                $dbData['updated_at'] = date('Y-m-d H:i:s');
                $dbData['updated_by'] = $this->session->userdata('admin_id');
                $input = "parent";
               
                $dbData["lparent"] = 0;
                $dbData["lang_id"] = $dlang->id;

                

                $this->db->insert('forms',$dbData);
            $this->session->set_flashdata('msg','New form added successfully!');
            redirect('admin/forms');
        }
    }
    /**
     * validation check
     * 
     * @since 1.0
     * @author DeDevelopers
     * @copyright Copyright (c) 2019, DeDevelopers, https://dedevelopers.com
     */
    public function check_form($title){

        $result = $this->form->get_form_by_title($title);
        if(!empty($title)) {
            if ($result->num_rows() > 0) {
                $this->form_validation->set_message('check_form', 'This form already exist.');
                return false;
            } else {
                return true;
            }
        }else{
            $this->form_validation->set_message('check_form', 'This field is required.');
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

        $result = $this->form->get_form_by_id($id);

        if(!$result){

            $this->session->set_flashdata('err','Invalid request.');
            redirect('admin/404_page');

        }

        $form_status = 1;

        if($status == 1){

            $form_status = 0;

        }

        $dbData['status'] = $form_status;
        $dbData['updated_at'] = date('Y-m-d H:i:s');
        $dbData['updated_by'] = $this->session->userdata('admin_id');

        $this->db->where('id',$id);
        $this->db->update('forms',$dbData);
        $this->session->set_flashdata('msg','form status updated successfully!');
        redirect('admin/forms');
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

        $result = $this->form->get_form_by_id($id);
        $this->data["the_id"] = $id;

        if(!$result){

            $this->session->set_flashdata('err','Invalid request.');
            redirect('admin/404_page');

        }

        $this->data['data'] = $result;



        $dlang = dlang();
        
        $input = "title";
        $this->form_validation->set_rules($input,'Title','trim|required');

        $this->form_validation->set_message('required','This field is required.');
        $this->form_validation->set_message('alpha_numeric_spaces','Only alphabet and numbers are allowed.');
        
        if($this->form_validation->run() === false){
            $this->data['title'] = 'Edit form';
            $this->data['jsfile'] = 'js/add_form';
            $this->data['meta'] = $this->load->view('backend/common/meta_data',$this->data,true);
            $this->data['categories'] = $this->db->where('is_deleted',0)
            ->where('parent',0)
            ->where('lparent',0)

            ->get('categories');
            
            $this->data['content'] = $this->load->view('backend/forms/edit',$this->data,true);
             
            $this->load->view('backend/common/template',$this->data);
        }else{

            $input = "title";
            $dbData['title'] = $this->input->post($input);


            $input = "category";
            $dbData['category'] = $this->input->post($input);


            $input = "applies_on_date";
            $dbData['applies_on_date'] = $this->input->post($input)==1?1:0;


             $input = "featured";
                $dbData['featured'] = $this->input->post($input)==1?1:0;

            $input = "start_date";
            $dbData['start_date'] = $this->input->post($input);

            $input = "end_date";
            $dbData['end_date'] = $this->input->post($input);

            
            $input = "description";
            $dbData['description'] = $this->input->post($input);
            $this->db->where("id",$id);
            $this->db->update('forms',$dbData);
            

            $this->session->set_flashdata('msg','form updated successfully!');
            redirect('admin/forms');

        }
    }
    /**
     * validation check
     * 
     * @since 1.0
     * @author DeDevelopers
     * @copyright Copyright (c) 2019, DeDevelopers, https://dedevelopers.com
     */
    public function check_form_edit($title,$id){

        $result = $this->form->get_form_by_title($title);
        if(!empty($title)) {
            if ($result->num_rows() > 0) {
                $result = $result->row();
                if($result->id == $id){
                    return true;
                }else{
                    $this->form_validation->set_message('check_form_edit', 'This form already exist.');
                    return false;
                }
            } else {
                return true;
            }
        }else{
            $this->form_validation->set_message('check_form_edit', 'This field is required.');
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
        $result = $this->form->get_form_by_id($id);

        if(!$result){

            $this->session->set_flashdata('err','Invalid request.');
            redirect('admin/404_page');

        }
        $dbData['deleted_by'] = $this->session->userdata('admin_id');
        $dbData['is_deleted'] = 1;
        $this->db->where('id',$id);
        $this->db->update('forms', $dbData);
        $this->session->set_flashdata('msg', 'form deleted successfully!');
        redirect('admin/forms');
    }
    public function display_order()
    {
        // $result = $this->form->get_form_display_order();

        // if(!$result){

        //     $this->session->set_flashdata('err','Invalid request.');
        //     redirect('admin/404_page');

        // }

        $this->data['data'] = $result;
        $this->form_validation->set_rules('json_order','Title','trim|required');
       
        if($this->form_validation->run() === false){
            $this->data['title'] = 'Edit forms Display Order';
            $this->data['jsfile'] = "js/forms_display_order";
            $this->data['forms'] = $this->db->where('is_deleted',0)
            ->order_by('display_priority',"ASC")
            ->where('parent',0)
            ->get('forms');

            $this->data['content'] = $this->load->view('backend/forms/display_order',$this->data,true);
             
            $this->load->view('backend/common/template',$this->data);
        }else{


            $json_order = $this->input->post('json_order');
            $json_order = json_decode($json_order);
            $i = 1;
            foreach ($json_order as $json_order_key => $json_order_value) {
                $this->db->where('id',$json_order_value->id)
                ->update('forms',array(
                    'parent'=>0,
                    'display_priority'=>$i
                ));
                $i++;

                foreach($json_order_value->children as $child)
                {
                    $this->db->where('id',$child->id)->update('forms',array('parent'=>$json_order_value->id,'display_priority'=>$i));
                    $i++;
                }
            }
            
            // $this->db->where('id',$id);
            // $this->db->update('forms', $dbData);
            $this->session->set_flashdata('msg', 'form updated successfully!');
            redirect($_SERVER['HTTP_REFERER']);

        }
    }
}
