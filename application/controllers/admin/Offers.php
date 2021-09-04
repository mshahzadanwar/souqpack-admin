<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Offers extends ADMIN_Controller {

	function __construct()
	{
		parent::__construct();
		auth();
        $this->redirect_role(10);

        $this->data['active'] = 'offers';
        $this->load->model('offers_model','offer');
	}

	public function index()
	{

		$this->data['title'] = 'offers';
        $this->data['sub'] = 'offers';
        $this->data['js'] = 'listing';
        $this->data['jsfile'] = 'js/offers_listing';
        $this->data['offers'] = $this->offer->get_all_offers();
		$this->data['content'] = $this->load->view('backend/offers/listing',$this->data,true);
		$this->load->view('backend/common/template',$this->data);

	}
    public function trash()
    {

        $this->data['title'] = 'Trash offers';
        $this->data['sub'] = 'trash';
        $this->data['js'] = 'listing';
        $this->data['jsfile'] = 'js/offers_listing';
        $this->data['offers'] = $this->offer->get_all_trash_offers();
        $this->data['content'] = $this->load->view('backend/offers/trash',$this->data,true);
        $this->load->view('backend/common/template',$this->data);

    }
    public function send_as_newsletter($id)
    {
        $this->session->set_flashdata('msg', 'Offer has been sent as newsletter successfully!');
        redirect('admin/offers');
    }
    public function restore($id){
        
        $dbData['deleted_by'] = $this->session->userdata('admin_id');
        $dbData['is_deleted'] = 0;
        $this->db->where('id',$id);
        $this->db->update('offers', $dbData);
        $this->session->set_flashdata('msg', 'offer restored successfully!');
        redirect('admin/trash-offers');
    }

	public function add (){

	    $dlang = dlang();
        $langs = langs();
        $input = $dlang->slug."[title]";
        $this->form_validation->set_rules($input,'Title','trim|required');
        $input = $dlang->slug."_image";
	    $this->form_validation->set_rules('image','Image','callback_image_not_required['.$input.',20,20]');
        $this->form_validation->set_message('required','This field is required.');
        $this->form_validation->set_message('alpha_numeric_spaces','Only alphabet and numbers are allowed.');
	    if($this->form_validation->run() === false){
            $this->data['title'] = 'Add New offer';
            $this->data['jsfile'] = 'js/add_offer';
            $this->data['sub'] = 'add-offer';
            $this->data['categories'] = $this->db->where('is_deleted',0)
            ->where('parent',0)
            ->where('lparent',0)
            ->get('categories');
            if(isset($_GET['replicate']))
            {
                $this->data['prev'] = $this->db->where('is_deleted',0)
                ->where('id',$_GET['replicate'])
                ->get('offers')->result_object()[0];
               
            }

            $this->data['meta'] = $this->load->view('backend/common/meta_data',$this->data,true);
            $this->data['content'] = $this->load->view('backend/offers/add',$this->data,true);
            $this->load->view('backend/common/template',$this->data);
        }else{

            $def_key=0;
            $def_parent=0;
            $fault = false;
            foreach($langs as $key=>$lang){

                $dbData = array();

    	        $input = $lang->slug."[title]";
                $dbData['title'] = $this->input->post($input);

                $input = $lang->slug."[title]";
    	        $dbData['slug'] = slug($this->input->post($input));

                $dbData['discount_type'] = $this->input->post('discount_type')?$this->input->post('discount_type'):0;
                
                $dbData['discount'] = $this->input->post('discount')?$this->input->post('discount'):0;

                $dbData['applies_on_date'] = $this->input->post('applies_on_date')==1?1:0;
                $dbData['start_date'] = $this->input->post('start_date');
                $dbData['end_date'] = $this->input->post('end_date');
                $dbData['min_qty'] = $this->input->post('min_qty');
                // $dbData['category'] = $this->input->post('category');
                $dbData['products'] = implode(',', $this->input->post('products'));



                $input = $lang->slug."[description]";
    	        $dbData['description'] = $this->input->post($input);
    	        // $dbData['meta_title'] = $this->input->post('meta_title');
    	        // $dbData['meta_keywords '] = $this->input->post('meta_keys');
    	        // $dbData['meta_description'] = $this->input->post('meta_desc');
    	        $dbData['created_at'] = date('Y-m-d H:i:s');
    	        $dbData['created_by'] = $this->session->userdata('admin_id');
    	        $dbData['updated_at'] = date('Y-m-d H:i:s');
                $dbData['updated_by'] = $this->session->userdata('admin_id');

                $dbData['parent'] = $this->input->post('parent')?$this->input->post('parent'):0;

                $dbData["lparent"] = $def_key;
                $dbData["lang_id"] = $lang->id;



                $input = $lang->slug."_image";
                if((isset($_FILES[$input]) && $_FILES[$input]['size'] > 0))
    	        $image = $this->image_upload($input,'./resources/uploads/offers/','jpg|jpeg|png|gif');
    	        if($image['upload'] == true || $_FILES[$input]['size']<1){
                    $image = $image['data'];
                    if((isset($_FILES[$input]) && $_FILES[$input]['size'] > 0)){
                        $dbData['image'] = $image['file_name'];
                        $this->image_thumb($image['full_path'],'./resources/uploads/offers/actual_size/',20,20);


                        
                    }
                    else{
                    }


                    $this->db->insert('offers',$dbData);

                    if($def_key==0)
                        $def_key = $this->db->insert_id();
                    
                 }else{
                    $fault=true;
                }


                if($fault){
                    $this->session->set_flashdata('err','An Error occurred durring uploading image, please try again');
                    redirect('admin/add-offer');
                    return;
                } 
            }


            $this->session->set_flashdata('msg','New offer added successfully!');
            redirect('admin/offers');

        }
    }
    public function view_products_section($id,$product=0)
    {
        view_products_section_helper($id,$product);
    }

    public function check_offer($title){

	    $result = $this->offer->get_offer_by_title($title);
	    if(!empty($title)) {
            if ($result->num_rows() > 0) {
                $this->form_validation->set_message('check_offer', 'This offer already exist.');
                return false;
            } else {
                return true;
            }
        }else{
            $this->form_validation->set_message('check_offer', 'This field is required.');
            return false;
        }
    }


    public function status($id,$status){

        $result = $this->offer->get_offer_by_id($id);

        if(!$result){

            $this->session->set_flashdata('err','Invalid request.');
            redirect('admin/404_page');

        }

        $offer_status = 1;

        if($status == 1){

            $offer_status = 0;

        }

        $dbData['status'] = $offer_status;
        $dbData['updated_at'] = date('Y-m-d H:i:s');
        $dbData['updated_by'] = $this->session->userdata('admin_id');

        $this->db->where('id',$id);
        $this->db->update('offers',$dbData);
        $this->session->set_flashdata('msg','offer status updated successfully!');
        redirect('admin/offers');
    }

    public function edit($id){

        $result = $this->offer->get_offer_by_id($id);
        $this->data["the_id"] = $id;

        if(!$result){

            $this->session->set_flashdata('err','Invalid request.');
            redirect('admin/404_page');

        }

        $this->data['data'] = $result;
        $dlang = dlang();
        $langs = langs();
        $input = $dlang->slug."[title]";
        $this->form_validation->set_rules($input,'Title','trim|required|callback_check_offer_edit['.$id.']');

        $input = $dlang->slug."_image";
        $this->form_validation->set_rules('image','Image','callback_image_not_required['.$input.',20,20]');
        $this->form_validation->set_message('required','This field is required.');
        $this->form_validation->set_message('alpha_numeric_spaces','Only alphabet and numbers are allowed.');
        if($this->form_validation->run() === false){
            $this->data['title'] = 'Edit offer';
            $this->data['meta'] = $this->load->view('backend/common/meta_data',$this->data,true);
            $this->data['categories'] = $this->db->where('is_deleted',0)
            ->where('lparent',0)
            ->where('parent',0)
            ->get('categories');
            $this->data['content'] = $this->load->view('backend/offers/edit',$this->data,true);
             
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
                // $dbData['description'] = $this->input->post($input);
                // $dbData['meta_title'] = $this->input->post('meta_title');
                // $dbData['meta_keywords '] = $this->input->post('meta_keys');
                // $dbData['meta_description'] = $this->input->post('meta_desc');
                $dbData['updated_at'] = date('Y-m-d H:i:s');
                $dbData['updated_by'] = $this->session->userdata('admin_id');




                $dbData['discount_type'] = $this->input->post('discount_type')?$this->input->post('discount_type'):0;
                
                $dbData['discount'] = $this->input->post('discount')?$this->input->post('discount'):0;

                $dbData['applies_on_date'] = $this->input->post('applies_on_date')==1?1:0;
                $dbData['start_date'] = $this->input->post('start_date');
                $dbData['end_date'] = $this->input->post('end_date');
                $dbData['min_qty'] = $this->input->post('min_qty');
                // $dbData['category'] = $this->input->post('category');
                // $dbData['product'] = $this->input->post('product');

                $dbData['products'] = implode(',', $this->input->post('products'));

                $dbData['parent'] = $this->input->post('parent')?$this->input->post('parent'):0;


                $input = $lang->slug."_image";
                if(!empty($_FILES[$input]['name'])) {
                    unlink('./resources/uploads/offers/'.$this->data['data']->image);
                    unlink('./resources/uploads/offers/actual_size/'.$this->data['data']->image);
                    $image = $this->image_upload($input, './resources/uploads/offers/', 'jpg|jpeg|png|gif');
                    if ($image['upload'] == true) {
                        $image = $image['data'];
                        $dbData['image'] = $image['file_name'];
                        $this->image_thumb($image['full_path'], './resources/uploads/offers/actual_size/', 20, 20);
                    } else {

                        if($lang->is_default==1){
                             $this->session->set_flashdata('err',$error);
                            redirect($_SERVER["HTTP_REFERER"]);
                            return;
                        }
                    }
                }

                $this->db->where("id",$row_id);
                $this->db->update('offers', $dbData);
            }
            $this->session->set_flashdata('msg', 'offer updated successfully!');
            redirect('admin/offers');

        }
    }

    public function check_offer_edit($title,$id){

        $result = $this->offer->get_offer_by_title($title);
        if(!empty($title)) {
            if ($result->num_rows() > 0) {
                $result = $result->row();
                if($result->id == $id){
                    return true;
                }else{
                    $this->form_validation->set_message('check_offer_edit', 'This offer already exist.');
                    return false;
                }
            } else {
                return true;
            }
        }else{
            $this->form_validation->set_message('check_offer_edit', 'This field is required.');
            return false;
        }
    }

    public function delete($id){
        $result = $this->offer->get_offer_by_id($id);

        if(!$result){

            $this->session->set_flashdata('err','Invalid request.');
            redirect('admin/404_page');

        }
        $dbData['deleted_by'] = $this->session->userdata('admin_id');
        $dbData['is_deleted'] = 1;
        $this->db->where('id',$id);
        $this->db->update('offers', $dbData);
        $this->session->set_flashdata('msg', 'offer deleted successfully!');
        redirect('admin/offers');
    }
}
