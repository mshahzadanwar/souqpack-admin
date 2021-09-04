<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * handles the shipments
 * 
 * @since 1.0
 * @author DeDevelopers
 * @copyright Copyright (c) 2019, DeDevelopers, https://dedevelopers.com
 */
class Shipments extends ADMIN_Controller {
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
        $this->redirect_role(24);
        $this->data['active'] = 'shipment';
        $this->load->model('shipments_model','shipment');
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

        $this->data['title'] = 'Shipment Methods';
        $this->data['sub'] = 'shipments';
        $this->data['js'] = 'listing';
        $this->data['jsfile'] = 'js/shipments_listing';
        $this->data['shipments'] = $this->shipment->get_all_shipments();
        $this->data['content'] = $this->load->view('backend/shipments/listing',$this->data,true);
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

        $this->data['title'] = 'Trash Shipment Methods';
        $this->data['sub'] = 'trash';
        $this->data['js'] = 'listing';
        $this->data['jsfile'] = 'js/shipments_listing';
        $this->data['shipments'] = $this->shipment->get_all_trash_shipments();
        $this->data['content'] = $this->load->view('backend/shipments/trash',$this->data,true);
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
        $this->db->update('shipments', $dbData);
        $this->session->set_flashdata('msg', 'shipment restored successfully!');
        redirect('admin/trash-shipments');
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
        $this->form_validation->set_rules($input,'Title','trim|required|callback_check_shipment');

       
        $this->form_validation->set_message('required','This field is required.');
        if($this->form_validation->run() === false){
            $this->data['title'] = 'Add New Shipment Method';
            $this->data['sub'] = 'add-shipment';
            $this->data['categories'] = $this->db->where('is_deleted',0)
            ->where('parent',0)
            ->where('lparent',0)

            ->get('categories');
            if(isset($_GET['replicate']))
            {
                $this->data['prev'] = $this->db->where('is_deleted',0)
                ->where('id',$_GET['replicate'])
                ->get('shipments')->result_object()[0];
               
            }
            $this->data['meta'] = $this->load->view('backend/common/meta_data',$this->data,true);
            $this->data['content'] = $this->load->view('backend/shipments/add',$this->data,true);
            $this->load->view('backend/common/template',$this->data);
        }else{
            $def_key=0;
            $def_parent=0;
            $fault = false;
            foreach($langs as $key=>$lang){
                $input = $lang->slug."[title]";
                $dbData['title'] = $this->input->post($input);


                $input = $lang->slug."[category]";
                $dbData['category'] = $this->input->post($input);

               
                $dbData['created_at'] = date('Y-m-d H:i:s');
                $dbData['created_by'] = $this->session->userdata('admin_id');
                $dbData['updated_at'] = date('Y-m-d H:i:s');
                $dbData['updated_by'] = $this->session->userdata('admin_id');
                $input = $lang->slug."[parent]";
               



                $dbData["lparent"] = $def_key;
                $dbData["lang_id"] = $lang->id;




               

                $this->db->insert('shipments',$dbData);
                if($def_key==0)
                $def_key = $this->db->insert_id();
            }

            if($fault){
                $this->session->set_flashdata('err','An Error occurred durring uploading image, please try again');
                redirect('admin/add-shipment');
                return;
            }
            $this->session->set_flashdata('msg','New Shipment Method added successfully!');
            redirect('admin/shipments');

        }
    }
    /**
     * validation check
     * 
     * @since 1.0
     * @author DeDevelopers
     * @copyright Copyright (c) 2019, DeDevelopers, https://dedevelopers.com
     */
    public function check_shipment($title){

        $result = $this->shipment->get_shipment_by_title($title);
        if(!empty($title)) {
            if ($result->num_rows() > 0) {
                $this->form_validation->set_message('check_shipment', 'This Shipment Method already exist.');
                return false;
            } else {
                return true;
            }
        }else{
            $this->form_validation->set_message('check_shipment', 'This field is required.');
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

        $result = $this->shipment->get_shipment_by_id($id);

        if(!$result){

            $this->session->set_flashdata('err','Invalid request.');
            redirect('admin/404_page');

        }

        $shipment_status = 1;

        if($status == 1){

            $shipment_status = 0;

        }

        $dbData['status'] = $shipment_status;
        $dbData['updated_at'] = date('Y-m-d H:i:s');
        $dbData['updated_by'] = $this->session->userdata('admin_id');

        $this->db->where('id',$id);
        $this->db->update('shipments',$dbData);
        $this->session->set_flashdata('msg','Shipment Method status updated successfully!');
        redirect('admin/shipments');
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

        $result = $this->shipment->get_shipment_by_id($id);
        $this->data["the_id"] = $id;

        if(!$result){

            $this->session->set_flashdata('err','Invalid request.');
            redirect('admin/404_page');

        }

        $this->data['data'] = $result;



        $dlang = dlang();
        $langs = langs();
        $input = $dlang->slug."[title]";
        $this->form_validation->set_rules($input,'Title','trim|required|callback_check_shipment_edit['.$id.']');

       
        $this->form_validation->set_message('required','This field is required.');
       
        
        if($this->form_validation->run() === false){
            $this->data['title'] = 'Edit Shipment Method';
            $this->data['meta'] = $this->load->view('backend/common/meta_data',$this->data,true);
            $this->data['shipments'] = $this->db->where('is_deleted',0)
            ->where('parent',0)
            ->where('lparent',0)
            ->get('shipments');
            $this->data['content'] = $this->load->view('backend/shipments/edit',$this->data,true);
             
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


                $input = $lang->slug."[category]";
                $dbData['category'] = $this->input->post($input);

                
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
                $this->db->update('shipments',$dbData);
            }

            $this->session->set_flashdata('msg','Shipment Method updated successfully!');
            redirect('admin/shipments');

        }
    }
    /**
     * validation check
     * 
     * @since 1.0
     * @author DeDevelopers
     * @copyright Copyright (c) 2019, DeDevelopers, https://dedevelopers.com
     */
    public function check_shipment_edit($title,$id){

        $result = $this->shipment->get_shipment_by_title($title);
        if(!empty($title)) {
            if ($result->num_rows() > 0) {
                $result = $result->row();
                if($result->id == $id){
                    return true;
                }else{
                    $this->form_validation->set_message('check_shipment_edit', 'This shipment already exist.');
                    return false;
                }
            } else {
                return true;
            }
        }else{
            $this->form_validation->set_message('check_shipment_edit', 'This field is required.');
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
        $result = $this->shipment->get_shipment_by_id($id);

        if(!$result){

            $this->session->set_flashdata('err','Invalid request.');
            redirect('admin/404_page');

        }
        $dbData['deleted_by'] = $this->session->userdata('admin_id');
        $dbData['is_deleted'] = 1;
        $this->db->where('id',$id);
        $this->db->update('shipments', $dbData);
        $this->session->set_flashdata('msg', 'Shipment Method deleted successfully!');
        redirect('admin/shipments');
    }
}
