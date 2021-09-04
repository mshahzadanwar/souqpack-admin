<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Quantity_units extends ADMIN_Controller {

	function __construct()
	{
		parent::__construct();
		auth();
        $this->redirect_role(6);

        $this->data['active'] = 'quantity-units';
        $this->load->model('quantity_units_model','quantity_unit');
	}

	public function index()
	{

			$this->data['title'] = 'Quantity Units';
            $this->data['sub'] = 'quantity-unit';
            $this->data['js'] = 'listing';
            $this->data['jsfile'] = 'js/quantity_units';
            $this->data['quantity_units'] = $this->quantity_unit->get_all_quantity_units();
			$this->data['content'] = $this->load->view('backend/quantity_units/listing',$this->data,true);
			$this->load->view('backend/common/template',$this->data);

	}
    public function trash()
    {

            $this->data['title'] = 'Trash Quantity Units';
            $this->data['sub'] = 'trash';
            $this->data['js'] = 'listing';
            $this->data['jsfile'] = 'js/quantity_units';
            $this->data['quantity_units'] = $this->quantity_unit->get_all_trash_quantity_units();
            $this->data['content'] = $this->load->view('backend/quantity_units/trash',$this->data,true);
            $this->load->view('backend/common/template',$this->data);

    }
    public function restore($id){
        
        $dbData['deleted_by'] = $this->session->userdata('admin_id');
        $dbData['is_deleted'] = 0;
        $this->db->where('id',$id);
        $this->db->update('quantity_units', $dbData);
        $this->session->set_flashdata('msg', 'Quantity Unit restored successfully!');
        redirect('admin/trash-quantity-units');
    }

	public function add (){


        $dlang = dlang();
        $langs = langs();
        $input = $dlang->slug."[title]";
        $this->form_validation->set_rules($input,'Title','trim|required');

        $this->form_validation->set_message('required','This field is required.');
        $this->form_validation->set_message('alpha_numeric_spaces','Only alphabet and numbers are allowed.');
	    if($this->form_validation->run() === false){
            $this->data['title'] = 'Add New Quantity Unit';
            $this->data['sub'] = 'add-quantity-unit';
            $this->data['brands'] = $this->db->where('is_deleted',0)
            ->where('parent',0)
            ->get('brands');
            if($_GET['replicate']==1)
            {
                $this->data['prev'] = $this->db->where('is_deleted',0)
                ->order_by('id',"DESC")
                ->get('quantity_units')->result_object()[0];
               
            }
            $this->data['meta'] = $this->load->view('backend/common/meta_data',$this->data,true);
            $this->data['content'] = $this->load->view('backend/quantity_units/add',$this->data,true);
            $this->load->view('backend/common/template',$this->data);
        }else{

	        $def_key=0;
            $def_parent=0;
            $fault = false;
            foreach($langs as $key=>$lang){
                $input = $lang->slug."[title]";
                $dbData['title'] = $this->input->post($input);

                $input = $lang->slug."[description]";
                $dbData['description'] = $this->input->post($input);
                // if($lang->is_default==1)
                $input = $lang->slug."[title]";
    	        $dbData['slug'] = slug($this->input->post($input));
    	        // $dbData['meta_title'] = $this->input->post('meta_title');
    	        // $dbData['meta_keywords '] = $this->input->post('meta_keys');
    	        // $dbData['meta_description'] = $this->input->post('meta_desc');
    	        $dbData['created_at'] = date('Y-m-d H:i:s');
    	        $dbData['created_by'] = $this->session->userdata('admin_id');
    	        $dbData['updated_at'] = date('Y-m-d H:i:s');
                $dbData['updated_by'] = $this->session->userdata('admin_id');


                $dbData["lparent"] = $def_key;
                $dbData["lang_id"] = $lang->id;

                

                $this->db->insert('quantity_units',$dbData);
                if($def_key==0)
                    $def_key = $this->db->insert_id();

            }
                $this->session->set_flashdata('msg','New brand added successfully!');
                redirect('admin/quantity-units');

        }
    }

    public function check_quantity_unit($title){

	    $result = $this->quantity_unit->get_quantity_unit_by_title($title);
	    if(!empty($title)) {
            if ($result->num_rows() > 0) {
                $this->form_validation->set_message('check_quantity_unit', 'This Quantity Unit already exist.');
                return false;
            } else {
                return true;
            }
        }else{
            $this->form_validation->set_message('check_quantity_unit', 'This field is required.');
            return false;
        }
    }


    public function status($id,$status){

        $result = $this->quantity_unit->get_quantity_unit_by_id($id);

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
        $this->session->set_flashdata('msg','Quantity Unit status updated successfully!');
        redirect('admin/brands');
    }

    public function edit($id){

        $result = $this->quantity_unit->get_quantity_unit_by_id($id);

        $this->data["the_id"] = $id;

        if(!$result){

            $this->session->set_flashdata('err','Invalid request.');
            redirect('admin/404_page');

        }

        $this->data['data'] = $result;
        $dlang = dlang();
        $langs = langs();
        $input = $dlang->slug."[title]";
        $this->form_validation->set_rules($input,'Title','trim|required|callback_check_quantity_unit_edit['.$id.']');
        //$this->form_validation->set_rules('description','Description','trim|required');
        //$this->form_validation->set_rules('image','Image','callback_image_not_required[image,200,200]');
        $this->form_validation->set_message('required','This field is required.');
        $this->form_validation->set_message('alpha_numeric_spaces','Only alphabet and numbers are allowed.');
        if($this->form_validation->run() === false){
            $this->data['title'] = 'Edit Quantity Unit';
            $this->data['meta'] = $this->load->view('backend/common/meta_data',$this->data,true);

            $this->data['content'] = $this->load->view('backend/quantity_units/edit',$this->data,true);
             
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
                $this->db->update('quantity_units', $dbData);
            }
            $this->session->set_flashdata('msg', 'Quantity Unit updated successfully!');
            redirect('admin/quantity-units');

        }
    }

    public function check_quantity_unit_edit($title,$id){

        $result = $this->quantity_unit->get_quantity_unit_by_title($title);
        if(!empty($title)) {
            if ($result->num_rows() > 0) {
                $result = $result->row();
                if($result->id == $id){
                    return true;
                }else{
                    $this->form_validation->set_message('check_quantity_unit_edit', 'This Quantity Unit already exist.');
                    return false;
                }
            } else {
                return true;
            }
        }else{
            $this->form_validation->set_message('check_quantity_unit_edit', 'This field is required.');
            return false;
        }
    }

    public function delete($id){
        $result = $this->quantity_unit->get_quantity_unit_by_id($id);

        if(!$result){

            $this->session->set_flashdata('err','Invalid request.');
            redirect('admin/404_page');

        }
        $dbData['deleted_by'] = $this->session->userdata('admin_id');
        $dbData['is_deleted'] = 1;
        $this->db->where('id',$id);
        $this->db->update('quantity_units', $dbData);
        $this->session->set_flashdata('msg', 'Quantity Unit deleted successfully!');
        redirect('admin/quantity-units');
    }
}
