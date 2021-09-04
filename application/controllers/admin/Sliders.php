<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * handles the sliders
 * 
 * @since 1.0
 * @author DeDevelopers
 * @copyright Copyright (c) 2019, DeDevelopers, https://dedevelopers.com
 */
class Sliders extends ADMIN_Controller {
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
        $this->redirect_role(27);
        $this->data['active'] = 'slider';
        $this->load->model('sliders_model','slider');
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

        $this->data['title'] = 'Sliders';
        $this->data['sub'] = 'sliders';
        $this->data['js'] = 'listing';
        $this->data['jsfile'] = 'js/sliders_listing';
        $this->data['sliders'] = $this->slider->get_all_sliders();
        $this->data['content'] = $this->load->view('backend/sliders/listing',$this->data,true);
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
        redirect_region();
        $this->data['title'] = 'Trash Sliders';
        $this->data['sub'] = 'trash';
        $this->data['js'] = 'listing';
        $this->data['jsfile'] = 'js/sliders_listing';
        $this->data['sliders'] = $this->slider->get_all_trash_sliders();
        $this->data['content'] = $this->load->view('backend/sliders/trash',$this->data,true);
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
        
        redirect_region();
        $dbData['deleted_by'] = $this->session->userdata('admin_id');
        $dbData['is_deleted'] = 0;
        $this->db->where('id',$id);
        $this->db->update('sliders', $dbData);
        $this->session->set_flashdata('msg', 'Slider restored successfully!');
        redirect('admin/trash-sliders');
    }
    /**
     * loads the add view, then handles the submitted data
     * 
     * @since 1.0
     * @author DeDevelopers
     * @copyright Copyright (c) 2019, DeDevelopers, https://dedevelopers.com
     */
    public function add (){

        redirect_region();
        $dlang = dlang();
        $langs = langs();
        $input = $dlang->slug."[title]";
        $this->form_validation->set_rules($input,'Title','trim|required|callback_check_slider');

        $this->form_validation->set_message('required','This field is required.');
        if($this->form_validation->run() === false){
            $this->data['title'] = 'Add New Slider';
            $this->data['sub'] = 'add-slider';
            
            $this->data['content'] = $this->load->view('backend/sliders/add',$this->data,true);
            $this->load->view('backend/common/template',$this->data);
        }else{
            $def_key=0;
            $def_parent=0;
            $fault = false;
            foreach($langs as $key=>$lang){
                $input = $lang->slug."[title]";
                $dbData['title'] = $this->input->post($input);
                $input_2 = $lang->slug."[category]";
                $dbData['cat_slug'] = $this->input->post($input_2);
                
                $dbData['created_at'] = date('Y-m-d H:i:s');
                $dbData['created_by'] = $this->session->userdata('admin_id');
                $dbData['updated_at'] = date('Y-m-d H:i:s');
                $dbData['updated_by'] = $this->session->userdata('admin_id');
                $input = $lang->slug."[parent]";
               



                $dbData["lparent"] = $def_key;
                $dbData["lang_id"] = $lang->id;




                


                $input = $lang->slug."_image";
                if((isset($_FILES[$input]) && $_FILES[$input]['size'] > 0))
                $image = $this->image_upload($input,'./resources/uploads/sliders/','jpg|jpeg|png|gif');
                if($image['upload'] == true || $_FILES[$input]['size']<1){
                    $image = $image['data'];
                    if((isset($_FILES[$input]) && $_FILES[$input]['size'] > 0)){
                        $dbData['image'] = $image['file_name'];
                        $this->image_thumb($image['full_path'],'./resources/uploads/sliders/actual_size/',100,100);
                    }
                    
                    $this->db->insert('sliders',$dbData);
                    if($def_key==0)
                    $def_key = $this->db->insert_id();
                    
                }else{
                    $fault=true;
                }
            }

            if($fault){
                $this->session->set_flashdata('err','An Error occurred durring uploading image, please try again');
                redirect('admin/add-slider');
                return;
            }
            $this->session->set_flashdata('msg','New Slider added successfully!');
            redirect('admin/sliders');

        }
    }
    /**
     * validation check
     * 
     * @since 1.0
     * @author DeDevelopers
     * @copyright Copyright (c) 2019, DeDevelopers, https://dedevelopers.com
     */
    public function check_slider($title){

        redirect_region();
        $result = $this->slider->get_slider_by_title($title);
        if(!empty($title)) {
            if ($result->num_rows() > 0) {
                $this->form_validation->set_message('check_slider', 'This Slider already exist.');
                return false;
            } else {
                return true;
            }
        }else{
            $this->form_validation->set_message('check_slider', 'This field is required.');
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

        redirect_region();
        $result = $this->slider->get_slider_by_id($id);

        if(!$result){

            $this->session->set_flashdata('err','Invalid request.');
            redirect('admin/404_page');

        }

        $slider_status = 1;

        if($status == 1){

            $slider_status = 0;

        }

        $dbData['status'] = $slider_status;
        $dbData['updated_at'] = date('Y-m-d H:i:s');
        $dbData['updated_by'] = $this->session->userdata('admin_id');

        $this->db->where('id',$id);
        $this->db->update('sliders',$dbData);
        $this->session->set_flashdata('msg','Slider status updated successfully!');
        redirect('admin/sliders');
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

        redirect_region();
        $result = $this->slider->get_slider_by_id($id);
        $this->data["the_id"] = $id;

        if(!$result){

            $this->session->set_flashdata('err','Invalid request.');
            redirect('admin/404_page');

        }

        $this->data['data'] = $result;



        $dlang = dlang();
        $langs = langs();
        $input = $dlang->slug."[title]";
        $this->form_validation->set_rules($input,'Title','trim|required|callback_check_slider_edit['.$id.']');

        
        $this->form_validation->set_message('required','This field is required.');
        
        if($this->form_validation->run() === false){
            $this->data['title'] = 'Edit Slider';
            $this->data['meta'] = $this->load->view('backend/common/meta_data',$this->data,true);
            $this->data['sliders'] = $this->db->where('is_deleted',0)
            ->where('parent',0)
            ->where('lparent',0)
            ->get('sliders');
            $this->data['content'] = $this->load->view('backend/sliders/edit',$this->data,true);
             
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

                $input_2 = $lang->slug."[category]";
                $dbData['cat_slug'] = $this->input->post($input_2);

                $dbData['created_at'] = date('Y-m-d H:i:s');
                $dbData['created_by'] = $this->session->userdata('admin_id');
                $dbData['updated_at'] = date('Y-m-d H:i:s');
                $dbData['updated_by'] = $this->session->userdata('admin_id');
                $input = $lang->slug."[parent]";
                if($key==0){
                    $dbData['parent'] = $this->input->post($input)?$this->input->post($input):0;
                    $def_parent = $dbData['parent'];
                }
                else{
                    $dbData['parent'] = $def_parent;
                }
                // $dbData["lparent"] = $def_key;
                // $dbData["lang_id"] = $lang->id;




                $input = $lang->slug."_image";
                if((isset($_FILES[$input]) && $_FILES[$input]['size'] > 0))
                $image = $this->image_upload($input,'./resources/uploads/sliders/','jpg|jpeg|png|gif');
                if($image['upload'] == true || $_FILES[$input]['size']<1){
                    $image = $image['data'];
                    if((isset($_FILES[$input]) && $_FILES[$input]['size'] > 0)){
                        $dbData['image'] = $image['file_name'];
                        $this->image_thumb($image['full_path'],'./resources/uploads/sliders/actual_size/',100,100);
                    }
                }else{
                    if($lang->is_default==1){
                         $this->session->set_flashdata('err',$error);
                        redirect($_SERVER["HTTP_REFERER"]);
                        return;
                    }
                }
                $this->db->where("id",$row_id);
                $this->db->update('sliders',$dbData);
            }

            $this->session->set_flashdata('msg','Slider updated successfully!');
            redirect('admin/sliders');

        }
    }
    /**
     * validation check
     * 
     * @since 1.0
     * @author DeDevelopers
     * @copyright Copyright (c) 2019, DeDevelopers, https://dedevelopers.com
     */
    public function check_slider_edit($title,$id){

        redirect_region();
        $result = $this->slider->get_slider_by_title($title);
        if(!empty($title)) {
            if ($result->num_rows() > 0) {
                $result = $result->row();
                if($result->id == $id){
                    return true;
                }else{
                    $this->form_validation->set_message('check_slider_edit', 'This Slider already exist.');
                    return false;
                }
            } else {
                return true;
            }
        }else{
            $this->form_validation->set_message('check_slider_edit', 'This field is required.');
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
        redirect_region();
        $result = $this->slider->get_slider_by_id($id);

        if(!$result){
            $this->session->set_flashdata('err','Invalid request.');
            redirect('admin/404_page');
        }
        $dbData['deleted_by'] = $this->session->userdata('admin_id');
        $dbData['is_deleted'] = 1;
        $this->db->where('id',$id);
        $this->db->update('sliders', $dbData);
        $this->session->set_flashdata('msg', 'Slider deleted successfully!');
        redirect('admin/sliders');
    }
    
}
