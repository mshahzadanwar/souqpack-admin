<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * handles the Brands
 * 
 * @since 1.0
 * @author DeDevelopers
 * @copyright Copyright (c) 2019, DeDevelopers, https://dedevelopers.com
 */
class Brands extends ADMIN_Controller {
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
        $this->redirect_role(4);
        $this->data['active'] = 'brand';
        $this->load->model('brands_model','brand');
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

        $this->data['title'] = 'Clients';
        $this->data['sub'] = 'brands';
        $this->data['js'] = 'listing';
        $this->data['jsfile'] = 'js/brands_listing';
        $this->data['brands'] = $this->brand->get_all_brands();
        $this->data['content'] = $this->load->view('backend/brands/listing',$this->data,true);
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

        $this->data['title'] = 'Trash Clients';
        $this->data['sub'] = 'trash';
        $this->data['js'] = 'listing';
        $this->data['jsfile'] = 'js/brands_listing';
        $this->data['brands'] = $this->brand->get_all_trash_brands();
        $this->data['content'] = $this->load->view('backend/brands/trash',$this->data,true);
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
        $this->db->update('brands', $dbData);
        $this->session->set_flashdata('msg', 'Client restored successfully!');
        redirect('admin/trash-brands');
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
        $this->form_validation->set_rules($input,'Title','trim|required|callback_check_brand');

        $input = $dlang->slug."[image]";
        $this->form_validation->set_rules($input,'Image','callback_image_not_required['.$input.',20,20]');
        $this->form_validation->set_message('required','This field is required.');
        $this->form_validation->set_message('alpha_numeric_spaces','Only alphabet and numbers are allowed.');
        if($this->form_validation->run() === false){
            $this->data['title'] = 'Add New Client';
            $this->data['sub'] = 'add-brand';
            $this->data['brands'] = $this->db->where('is_deleted',0)
            ->where('parent',0)
            ->where('lparent',0)
            ->get('brands');
            if(isset($_GET['replicate']))
            {
                $this->data['prev'] = $this->db->where('is_deleted',0)
                ->where('id',$_GET['replicate'])
                ->get('brands')->result_object()[0];
               
            }
            $this->data['meta'] = $this->load->view('backend/common/meta_data',$this->data,true);
            $this->data['content'] = $this->load->view('backend/brands/add',$this->data,true);
            $this->load->view('backend/common/template',$this->data);
        }else{
            $def_key=0;
            $def_parent=0;
            $fault = false;
            foreach($langs as $key=>$lang){
                $input = $lang->slug."[title]";
                $dbData['title'] = $this->input->post($input);


                // $input = $lang->slug."[sale_title]";
                // $dbData['sale_title'] = $this->input->post($input);

                // $input = $lang->slug."[sale_subtitle]";
                // $dbData['sale_subtitle'] = $this->input->post($input);

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




                // $input = $lang->slug."_sale_banner";
                // if((isset($_FILES[$input]) && $_FILES[$input]['size'] > 0))
                // $image = $this->image_upload($input,'./resources/uploads/brands/','jpg|jpeg|png|gif');
                // if($image['upload'] == true || $_FILES[$input]['size']<1){
                //     $image = $image['data'];
                //     if((isset($_FILES[$input]) && $_FILES[$input]['size'] > 0)){
                //         $dbData['sale_banner'] = $image['file_name'];
                //         $this->image_thumb($image['full_path'],'./resources/uploads/brands/actual_size/',100,100);
                //     }
                    
                    
                    
                // }else{
                //     $fault=true;
                // }



                $input = $lang->slug."_image";
                if((isset($_FILES[$input]) && $_FILES[$input]['size'] > 0))
                $image = $this->image_upload($input,'./resources/uploads/brands/','jpg|jpeg|png|gif');
                if($image['upload'] == true || $_FILES[$input]['size']<1){
                    $image = $image['data'];
                    if((isset($_FILES[$input]) && $_FILES[$input]['size'] > 0)){
                        $dbData['image'] = $image['file_name'];
                        $this->image_thumb($image['full_path'],'./resources/uploads/brands/actual_size/',100,100);
                    }
                    
                    $this->db->insert('brands',$dbData);
                    if($def_key==0)
                    $def_key = $this->db->insert_id();
                    
                }else{
                    $fault=true;
                }
            }

            if($fault){
                $this->session->set_flashdata('err','An Error occurred durring uploading image, please try again');
                redirect('admin/add-brand');
                return;
            }
            $this->session->set_flashdata('msg','New Client added successfully!');
            redirect('admin/brands');

        }
    }
    /**
     * validation check
     * 
     * @since 1.0
     * @author DeDevelopers
     * @copyright Copyright (c) 2019, DeDevelopers, https://dedevelopers.com
     */
    public function check_brand($title){

        $result = $this->brand->get_brand_by_title($title);
        if(!empty($title)) {
            if ($result->num_rows() > 0) {
                $this->form_validation->set_message('check_brand', 'This Client already exist.');
                return false;
            } else {
                return true;
            }
        }else{
            $this->form_validation->set_message('check_brand', 'This field is required.');
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

        $result = $this->brand->get_brand_by_id($id);

        if(!$result){

            $this->session->set_flashdata('err','Invalid request.');
            redirect('admin/404_page');

        }

        $brand_status = 1;

        if($status == 1){

            $brand_status = 0;

        }

        $dbData['status'] = $brand_status;
        $dbData['updated_at'] = date('Y-m-d H:i:s');
        $dbData['updated_by'] = $this->session->userdata('admin_id');

        $this->db->where('id',$id);
        $this->db->update('brands',$dbData);
        $this->session->set_flashdata('msg','Clients status updated successfully!');
        redirect('admin/brands');
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

        $result = $this->brand->get_brand_by_id($id);
        $this->data["the_id"] = $id;

        if(!$result){

            $this->session->set_flashdata('err','Invalid request.');
            redirect('admin/404_page');

        }

        $this->data['data'] = $result;



        $dlang = dlang();
        $langs = langs();
        $input = $dlang->slug."[title]";
        $this->form_validation->set_rules($input,'Title','trim|required|callback_check_brand_edit['.$id.']');

        $input = $dlang->slug."[image]";
        $this->form_validation->set_rules($input,'Image','callback_image_not_required['.$input.',20,20]');
        $this->form_validation->set_message('required','This field is required.');
        $this->form_validation->set_message('alpha_numeric_spaces','Only alphabet and numbers are allowed.');
        
        if($this->form_validation->run() === false){
            $this->data['title'] = 'Edit Clients';
            $this->data['meta'] = $this->load->view('backend/common/meta_data',$this->data,true);
            $this->data['brands'] = $this->db->where('is_deleted',0)
            ->where('parent',0)
            ->where('lparent',0)
            ->get('brands');
            $this->data['content'] = $this->load->view('backend/brands/edit',$this->data,true);
             
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


                // $input = $lang->slug."[sale_title]";
                // $dbData['sale_title'] = $this->input->post($input);

                // $input = $lang->slug."[sale_subtitle]";
                // $dbData['sale_subtitle'] = $this->input->post($input);

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


                // $input = $lang->slug."_sale_banner";
                // if((isset($_FILES[$input]) && $_FILES[$input]['size'] > 0))
                // $image = $this->image_upload($input,'./resources/uploads/brands/','jpg|jpeg|png|gif');
                // if($image['upload'] == true || $_FILES[$input]['size']<1){
                //     $image = $image['data'];
                //     if((isset($_FILES[$input]) && $_FILES[$input]['size'] > 0)){
                //         $dbData['sale_banner'] = $image['file_name'];
                //         $this->image_thumb($image['full_path'],'./resources/uploads/brands/actual_size/',100,100);
                //     }
                // }else{
                //     if($lang->is_default==1){
                //          $this->session->set_flashdata('err',$error);
                //         redirect($_SERVER["HTTP_REFERER"]);
                //         return;
                //     }
                // }





                $input = $lang->slug."_image";
                if((isset($_FILES[$input]) && $_FILES[$input]['size'] > 0))
                $image = $this->image_upload($input,'./resources/uploads/brands/','jpg|jpeg|png|gif');
                if($image['upload'] == true || $_FILES[$input]['size']<1){
                    $image = $image['data'];
                    if((isset($_FILES[$input]) && $_FILES[$input]['size'] > 0)){
                        $dbData['image'] = $image['file_name'];
                        $this->image_thumb($image['full_path'],'./resources/uploads/brands/actual_size/',100,100);
                    }
                }else{
                    if($lang->is_default==1){
                         $this->session->set_flashdata('err',$error);
                        redirect($_SERVER["HTTP_REFERER"]);
                        return;
                    }
                }
                $this->db->where("id",$row_id);
                $this->db->update('brands',$dbData);
            }

            $this->session->set_flashdata('msg','Client updated successfully!');
            redirect('admin/brands');

        }
    }
    /**
     * validation check
     * 
     * @since 1.0
     * @author DeDevelopers
     * @copyright Copyright (c) 2019, DeDevelopers, https://dedevelopers.com
     */
    public function check_brand_edit($title,$id){

        $result = $this->brand->get_brand_by_title($title);
        if(!empty($title)) {
            if ($result->num_rows() > 0) {
                $result = $result->row();
                if($result->id == $id){
                    return true;
                }else{
                    $this->form_validation->set_message('check_brand_edit', 'This Client already exist.');
                    return false;
                }
            } else {
                return true;
            }
        }else{
            $this->form_validation->set_message('check_brand_edit', 'This field is required.');
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
        $result = $this->brand->get_brand_by_id($id);

        if(!$result){

            $this->session->set_flashdata('err','Invalid request.');
            redirect('admin/404_page');

        }
        $dbData['deleted_by'] = $this->session->userdata('admin_id');
        $dbData['is_deleted'] = 1;
        $this->db->where('id',$id);
        $this->db->update('brands', $dbData);
        $this->session->set_flashdata('msg', 'Client deleted successfully!');
        redirect('admin/brands');
    }
    public function display_order()
    {
        // $result = $this->brand->get_brand_display_order();

        // if(!$result){

        //     $this->session->set_flashdata('err','Invalid request.');
        //     redirect('admin/404_page');

        // }

        $this->data['data'] = $result;
        $this->form_validation->set_rules('json_order','Title','trim|required');
       
        if($this->form_validation->run() === false){
            $this->data['title'] = 'Edit brands Display Order';
            $this->data['jsfile'] = "js/brands_display_order";
            $this->data['brands'] = $this->db->where('is_deleted',0)
            ->order_by('display_priority',"ASC")
            ->where('parent',0)
            ->get('brands');

            $this->data['content'] = $this->load->view('backend/brands/display_order',$this->data,true);
             
            $this->load->view('backend/common/template',$this->data);
        }else{


            $json_order = $this->input->post('json_order');
            $json_order = json_decode($json_order);
            $i = 1;
            foreach ($json_order as $json_order_key => $json_order_value) {
                $this->db->where('id',$json_order_value->id)
                ->update('brands',array(
                    'parent'=>0,
                    'display_priority'=>$i
                ));
                $i++;

                foreach($json_order_value->children as $child)
                {
                    $this->db->where('id',$child->id)->update('brands',array('parent'=>$json_order_value->id,'display_priority'=>$i));
                    $i++;
                }
            }
            
            // $this->db->where('id',$id);
            // $this->db->update('brands', $dbData);
            $this->session->set_flashdata('msg', 'brand updated successfully!');
            redirect($_SERVER['HTTP_REFERER']);

        }
    }
}
