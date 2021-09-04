<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * handles the stores
 * 
 * @since 1.0
 * @author DeDevelopers
 * @copyright Copyright (c) 2019, DeDevelopers, https://dedevelopers.com
 */
class Stores extends ADMIN_Controller {
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
        $this->redirect_role(19);
        $this->data['active'] = 'store';
        $this->load->model('stores_model','store');
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

        $this->data['title'] = 'stores';
        $this->data['sub'] = 'stores';
        $this->data['js'] = 'listing';
        $this->data['jsfile'] = 'js/stores_listing';
        $this->data['stores'] = $this->store->get_all_stores();
        $this->data['content'] = $this->load->view('backend/stores/listing',$this->data,true);
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

        $this->data['title'] = 'Trash stores';
        $this->data['sub'] = 'trash';
        $this->data['js'] = 'listing';
        $this->data['jsfile'] = 'js/stores_listing';
        $this->data['stores'] = $this->store->get_all_trash_stores();
        $this->data['content'] = $this->load->view('backend/stores/trash',$this->data,true);
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
        $this->db->update('stores', $dbData);
        $this->session->set_flashdata('msg', 'store restored successfully!');
        redirect('admin/trash-stores');
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

        $input = $dlang->slug."_image";
        $this->form_validation->set_rules($input,'Image','callback_image_not_required['.$input.',20,20]');
        $this->form_validation->set_message('required','This field is required.');
        $this->form_validation->set_message('alpha_numeric_spaces','Only alphabet and numbers are allowed.');
        if($this->form_validation->run() === false){
            $this->data['title'] = 'Add New store';
            $this->data['sub'] = 'add-store';
            $this->data['stores'] = $this->db->where('is_deleted',0)
            ->where('parent',0)
            ->where('lparent',0)
            ->get('stores');
            if(isset($_GET['replicate']))
            {
                $this->data['prev'] = $this->db->where('is_deleted',0)
                ->where('id',$_GET['replicate'])
                ->get('stores')->result_object()[0];
               
            }
            $this->data['meta'] = $this->load->view('backend/common/meta_data',$this->data,true);
            $this->data['content'] = $this->load->view('backend/stores/add',$this->data,true);
            $this->load->view('backend/common/template',$this->data);
        }else{
            $def_key=0;
            $def_parent=0;
            $fault = false;
            foreach($langs as $key=>$lang){

                $dbData = array();
                $input = $lang->slug."[title]";
                $dbData['title'] = $this->input->post($input);


                $input = $lang->slug."[email]";
                $dbData['email'] = $this->input->post($input);

                $input = $lang->slug."[region_id]";
                $dbData['region_id'] = $this->input->post($input);

                $input = $lang->slug."[phone]";
                $dbData['phone'] = $this->input->post($input);

                $input = $lang->slug."[address]";
                $dbData['address'] = $this->input->post($input);

                $input = $lang->slug."[title]";
                $dbData['slug'] = slug($this->input->post($input));
                $input = $lang->slug."[description]";
                $dbData['description'] = $this->input->post($input);
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
                $input = $lang->slug."[parent]";
               



                $dbData["lparent"] = $def_key;
                $dbData["lang_id"] = $lang->id;




                $input = $lang->slug."_banner";
                if((isset($_FILES[$input]) && $_FILES[$input]['size'] > 0))
                $image = $this->image_upload($input,'./resources/uploads/stores/','jpg|jpeg|png|gif');
                if($image['upload'] == true || $_FILES[$input]['size']<1){
                    $image = $image['data'];
                    if((isset($_FILES[$input]) && $_FILES[$input]['size'] > 0)){
                        $dbData['banner'] = $image['file_name'];
                        $this->image_thumb($image['full_path'],'./resources/uploads/stores/actual_size/',100,100);
                    }
                    
                    
                    
                }else{
                    $fault=true;
                }



                $input = $lang->slug."_image";
                if((isset($_FILES[$input]) && $_FILES[$input]['size'] > 0))
                $image = $this->image_upload($input,'./resources/uploads/stores/','jpg|jpeg|png|gif');
                if($image['upload'] == true || $_FILES[$input]['size']<1){
                    $image = $image['data'];
                    if((isset($_FILES[$input]) && $_FILES[$input]['size'] > 0)){
                        $dbData['image'] = $image['file_name'];
                        $this->image_thumb($image['full_path'],'./resources/uploads/stores/actual_size/',100,100);
                    }
                    
                    $this->db->insert('stores',$dbData);
                    if($def_key==0)
                    $def_key = $this->db->insert_id();
                    
                }else{
                    $fault=true;
                    break;
                }
            }

            if($fault){
                $this->session->set_flashdata('err','An Error occurred durring uploading image, please try again');
                redirect('admin/add-store');
                return;
            }
            $this->session->set_flashdata('msg','New store added successfully!');
            redirect('admin/stores');

        }
    }
    /**
     * validation check
     * 
     * @since 1.0
     * @author DeDevelopers
     * @copyright Copyright (c) 2019, DeDevelopers, https://dedevelopers.com
     */
    public function check_store($title){

        $result = $this->store->get_store_by_title($title);
        if(!empty($title)) {
            if ($result->num_rows() > 0) {
                $this->form_validation->set_message('check_store', 'This store already exist.');
                return false;
            } else {
                return true;
            }
        }else{
            $this->form_validation->set_message('check_store', 'This field is required.');
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

        $result = $this->store->get_store_by_id($id);

        if(!$result){

            $this->session->set_flashdata('err','Invalid request.');
            redirect('admin/404_page');

        }

        $store_status = 1;

        if($status == 1){

            $store_status = 0;

        }

        $dbData['status'] = $store_status;
        $dbData['updated_at'] = date('Y-m-d H:i:s');
        $dbData['updated_by'] = $this->session->userdata('admin_id');

        $this->db->where('id',$id);
        $this->db->update('stores',$dbData);
        $this->session->set_flashdata('msg','store status updated successfully!');
        redirect('admin/stores');
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

        $result = $this->store->get_store_by_id($id);
        $this->data["the_id"] = $id;

        if(!$result){

            $this->session->set_flashdata('err','Invalid request.');
            redirect('admin/404_page');

        }

        $this->data['data'] = $result;



        $dlang = dlang();
        $langs = langs();
        $input = $dlang->slug."[title]";
        $this->form_validation->set_rules($input,'Title','trim|required|callback_check_store_edit['.$id.']');

        $input = $dlang->slug."_image";
        $this->form_validation->set_rules($input,'Image','callback_image_not_required['.$input.',20,20]');
        $this->form_validation->set_message('required','This field is required.');
        $this->form_validation->set_message('alpha_numeric_spaces','Only alphabet and numbers are allowed.');
        
        if($this->form_validation->run() === false){
            $this->data['title'] = 'Edit store';
            $this->data['meta'] = $this->load->view('backend/common/meta_data',$this->data,true);
            $this->data['stores'] = $this->db->where('is_deleted',0)
            ->where('parent',0)
            ->where('lparent',0)
            ->get('stores');
            $this->data['content'] = $this->load->view('backend/stores/edit',$this->data,true);
             
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


                $input = $lang->slug."[email]";
                $dbData['email'] = $this->input->post($input);


                $input = $lang->slug."[region_id]";
                $dbData['region_id'] = $this->input->post($input);

                $input = $lang->slug."[phone]";
                $dbData['phone'] = $this->input->post($input);

                $input = $lang->slug."[address]";
                $dbData['address'] = $this->input->post($input);

                $input = $lang->slug."[title]";
                $dbData['slug'] = slug($this->input->post($input));
                $input = $lang->slug."[description]";
                $dbData['description'] = $this->input->post($input);
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


                $input = $lang->slug."_banner";
                if((isset($_FILES[$input]) && $_FILES[$input]['size'] > 0))
                $image = $this->image_upload($input,'./resources/uploads/stores/','jpg|jpeg|png|gif');
                if($image['upload'] == true || $_FILES[$input]['size']<1){
                    $image = $image['data'];
                    if((isset($_FILES[$input]) && $_FILES[$input]['size'] > 0)){
                        $dbData['banner'] = $image['file_name'];
                        $this->image_thumb($image['full_path'],'./resources/uploads/stores/actual_size/',100,100);
                    }
                }else{
                    if($lang->is_default==1){
                         $this->session->set_flashdata('err',$error);
                        redirect($_SERVER["HTTP_REFERER"]);
                        return;
                    }
                }



                $input = $lang->slug."_image";
                if((isset($_FILES[$input]) && $_FILES[$input]['size'] > 0))
                $image = $this->image_upload($input,'./resources/uploads/stores/','jpg|jpeg|png|gif');
                if($image['upload'] == true || $_FILES[$input]['size']<1){
                    $image = $image['data'];
                    if((isset($_FILES[$input]) && $_FILES[$input]['size'] > 0)){
                        $dbData['image'] = $image['file_name'];
                        $this->image_thumb($image['full_path'],'./resources/uploads/stores/actual_size/',100,100);
                    }
                }else{
                    if($lang->is_default==1){
                         $this->session->set_flashdata('err',$error);
                        redirect($_SERVER["HTTP_REFERER"]);
                        return;
                    }
                }
                $this->db->where("id",$row_id);
                $this->db->update('stores',$dbData);
            }

            $this->session->set_flashdata('msg','store updated successfully!');
            redirect('admin/stores');

        }
    }
    /**
     * validation check
     * 
     * @since 1.0
     * @author DeDevelopers
     * @copyright Copyright (c) 2019, DeDevelopers, https://dedevelopers.com
     */
    public function check_store_edit($title,$id){

        $result = $this->store->get_store_by_title($title);
        if(!empty($title)) {
            if ($result->num_rows() > 0) {
                $result = $result->row();
                if($result->id == $id){
                    return true;
                }else{
                    $this->form_validation->set_message('check_store_edit', 'This store already exist.');
                    return false;
                }
            } else {
                return true;
            }
        }else{
            $this->form_validation->set_message('check_store_edit', 'This field is required.');
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
        $result = $this->store->get_store_by_id($id);

        if(!$result){

            $this->session->set_flashdata('err','Invalid request.');
            redirect('admin/404_page');

        }
        $dbData['deleted_by'] = $this->session->userdata('admin_id');
        $dbData['is_deleted'] = 1;
        $this->db->where('id',$id);
        $this->db->update('stores', $dbData);
        $this->session->set_flashdata('msg', 'store deleted successfully!');
        redirect('admin/stores');
    }
    public function display_order()
    {
        // $result = $this->store->get_store_display_order();

        // if(!$result){

        //     $this->session->set_flashdata('err','Invalid request.');
        //     redirect('admin/404_page');

        // }

        $this->data['data'] = $result;
        $this->form_validation->set_rules('json_order','Title','trim|required');
       
        if($this->form_validation->run() === false){
            $this->data['title'] = 'Edit stores Display Order';
            $this->data['jsfile'] = "js/stores_display_order";
            $this->data['stores'] = $this->db->where('is_deleted',0)
            ->order_by('display_priority',"ASC")
            ->where('parent',0)
            ->get('stores');

            $this->data['content'] = $this->load->view('backend/stores/display_order',$this->data,true);
             
            $this->load->view('backend/common/template',$this->data);
        }else{


            $json_order = $this->input->post('json_order');
            $json_order = json_decode($json_order);
            $i = 1;
            foreach ($json_order as $json_order_key => $json_order_value) {
                $this->db->where('id',$json_order_value->id)
                ->update('stores',array(
                    'parent'=>0,
                    'display_priority'=>$i
                ));
                $i++;

                foreach($json_order_value->children as $child)
                {
                    $this->db->where('id',$child->id)->update('stores',array('parent'=>$json_order_value->id,'display_priority'=>$i));
                    $i++;
                }
            }
            
            // $this->db->where('id',$id);
            // $this->db->update('stores', $dbData);
            $this->session->set_flashdata('msg', 'store updated successfully!');
            redirect($_SERVER['HTTP_REFERER']);

        }
    }
}
