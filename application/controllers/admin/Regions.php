<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * handles the regions
 * 
 * @since 1.0
 * @author DeDevelopers
 * @copyright Copyright (c) 2019, DeDevelopers, https://dedevelopers.com
 */
class Regions extends ADMIN_Controller {
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

        $this->data['active'] = 'Region';
        $this->load->model('regions_model','region');
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

        $this->data['title'] = 'regions';
        $this->data['sub'] = 'regions';
        $this->data['js'] = 'listing';
        $this->data['jsfile'] = 'js/regions_listing';
        $this->data['regions'] = $this->region->get_all_regions();
        $this->data['content'] = $this->load->view('backend/regions/listing',$this->data,true);
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
        $this->shutdown_function();
        $this->data['title'] = 'Trash Regions';
        $this->data['sub'] = 'trash';
        $this->data['js'] = 'listing';
        $this->data['jsfile'] = 'js/regions_listing';
        $this->data['regions'] = $this->region->get_all_trash_regions();
        $this->data['content'] = $this->load->view('backend/regions/trash',$this->data,true);
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
        $this->db->update('regions', $dbData);
        $this->session->set_flashdata('msg', 'Region restored successfully!');
        redirect('admin/trash-regions');
    }
    /**
     * loads the add view, then handles the submitted data
     * 
     * @since 1.0
     * @author DeDevelopers
     * @copyright Copyright (c) 2019, DeDevelopers, https://dedevelopers.com
     */
    public function add (){
        $this->shutdown_function();
        $dlang = dlang();
        $langs = langs();
        $input = $dlang->slug."[title]";
        $this->form_validation->set_rules($input,'Title','trim|required|callback_check_region');

      
        $this->form_validation->set_message('required','This field is required.');
        if($this->form_validation->run() === false){
            $this->data['title'] = 'Add New Region';
            $this->data['sub'] = 'add-region';
            
            $this->data['content'] = $this->load->view('backend/regions/add',$this->data,true);
            $this->load->view('backend/common/template',$this->data);
        }else{
            $def_key=0;
            $def_parent=0;
            $fault = false;
            foreach($langs as $key=>$lang){
                $input = $lang->slug."[title]";
                $dbData['title'] = $this->input->post($input);


                $input = $lang->slug."[vat]";
                $dbData['vat'] = $this->input->post($input);


                $input = $lang->slug."[currency]";
                $dbData['currency'] = $this->input->post($input);


             
                $dbData['created_at'] = date('Y-m-d H:i:s');
                $dbData['created_by'] = $this->session->userdata('admin_id');
                $dbData['updated_at'] = date('Y-m-d H:i:s');
                $dbData['updated_by'] = $this->session->userdata('admin_id');
                $input = $lang->slug."[parent]";
               



                $dbData["lparent"] = $def_key;
                $dbData["lang_id"] = $lang->id;




                $this->db->insert('regions',$dbData);
                    if($def_key==0)
                    $def_key = $this->db->insert_id();
            }

            
            $this->session->set_flashdata('msg','New Region added successfully!');
            redirect('admin/regions');

        }
    }
    /**
     * validation check
     * 
     * @since 1.0
     * @author DeDevelopers
     * @copyright Copyright (c) 2019, DeDevelopers, https://dedevelopers.com
     */
    public function check_region($title){
        $this->shutdown_function();
        $result = $this->region->get_region_by_title($title);
        if(!empty($title)) {
            if ($result->num_rows() > 0) {
                $this->form_validation->set_message('check_region', 'This region already exist.');
                return false;
            } else {
                return true;
            }
        }else{
            $this->form_validation->set_message('check_region', 'This field is required.');
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
        $this->shutdown_function();
        $result = $this->region->get_region_by_id($id);

        if(!$result){

            $this->session->set_flashdata('err','Invalid request.');
            redirect('admin/404_page');

        }

        $region_status = 1;

        if($status == 1){

            $region_status = 0;

        }

        $dbData['status'] = $region_status;
        $dbData['updated_at'] = date('Y-m-d H:i:s');
        $dbData['updated_by'] = $this->session->userdata('admin_id');

        $this->db->where('id',$id);
        $this->db->update('regions',$dbData);
        $this->session->set_flashdata('msg','Region status updated successfully!');
        redirect('admin/regions');
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
        //$this->shutdown_function();
        $result = $this->region->get_region_by_id($id);
        $this->data["the_id"] = $id;

        if(!$result){

            $this->session->set_flashdata('err','Invalid request.');
            redirect('admin/404_page');

        }

        $this->data['data'] = $result;



        $dlang = dlang();
        $langs = langs();
        $input = $dlang->slug."[title]";
        $this->form_validation->set_rules($input,'Title','trim|required|callback_check_region_edit['.$id.']');

        
        $this->form_validation->set_message('required','This field is required.');
       
        if($this->form_validation->run() === false){
            $this->data['title'] = 'Edit Region';
            
            $this->data['content'] = $this->load->view('backend/regions/edit',$this->data,true);
             
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


                $input = $lang->slug."[vat]";
                $dbData['vat'] = $this->input->post($input);


                $input = $lang->slug."[currency]";
                $dbData['currency'] = $this->input->post($input);


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
              
                $this->db->where("id",$row_id);
                $this->db->update('regions',$dbData);
            }

            $this->session->set_flashdata('msg','Region updated successfully!');
            redirect('admin/regions');

        }
    }
    /**
     * validation check
     * 
     * @since 1.0
     * @author DeDevelopers
     * @copyright Copyright (c) 2019, DeDevelopers, https://dedevelopers.com
     */
    public function check_region_edit($title,$id){
       // $this->shutdown_function();
        $result = $this->region->get_region_by_title($title);
        if(!empty($title)) {
            if ($result->num_rows() > 0) {
                $result = $result->row();
                if($result->id == $id){
                    return true;
                }else{
                    $this->form_validation->set_message('check_region_edit', 'This Region already exist.');
                    return false;
                }
            } else {
                return true;
            }
        }else{
            $this->form_validation->set_message('check_region_edit', 'This field is required.');
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
        $this->shutdown_function();
        $result = $this->region->get_region_by_id($id);

        if(!$result){

            $this->session->set_flashdata('err','Invalid request.');
            redirect('admin/404_page');

        }
        $dbData['deleted_by'] = $this->session->userdata('admin_id');
        $dbData['is_deleted'] = 1;
        $this->db->where('id',$id);
        $this->db->update('regions', $dbData);
        $this->session->set_flashdata('msg', 'Region deleted successfully!');
        redirect('admin/regions');
    }
   
}
