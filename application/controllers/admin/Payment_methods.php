<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * handles the Payment_methods
 * 
 * @since 1.0
 * @author DeDevelopers
 * @copyright Copyright (c) 2019, DeDevelopers, https://dedevelopers.com
 */
class Payment_methods extends ADMIN_Controller {
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
        $this->redirect_role(5);

        $this->data['active'] = 'payment_methods';
        $this->load->model('payment_methods_model','payment_method');
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

			$this->data['title'] = 'Payment Methods';
            $this->data['sub'] = 'payment_methods';
            $this->data['js'] = 'listing';
            $this->data['jsfile'] = 'js/payment_methods_listing';
            $this->data['payment_methods'] = $this->payment_method->get_all_payment_methods();
			$this->data['content'] = $this->load->view('backend/payment_methods/listing',$this->data,true);
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

            $this->data['title'] = 'Trash payment_methods';
            $this->data['sub'] = 'trash';
            $this->data['js'] = 'listing';
            $this->data['jsfile'] = 'js/payment_methods_listing';
            $this->data['payment_methods'] = $this->payment_method->get_all_trash_payment_methods();
            $this->data['content'] = $this->load->view('backend/payment_methods/trash',$this->data,true);
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
        $this->shutdown_function();
        
        $dbData['deleted_by'] = $this->session->userdata('admin_id');
        $dbData['is_deleted'] = 0;
        $this->db->where('id',$id);
        $this->db->update('payment_methods', $dbData);
        $this->session->set_flashdata('msg', 'payment_method restored successfully!');
        redirect('admin/trash-payment_methods');
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

	    $this->form_validation->set_rules('title','Title','trim|required|alpha_numeric_spaces|callback_check_payment_method');
	    //$this->form_validation->set_rules('description','Description','trim|required');
	    $this->form_validation->set_rules('image','Image','callback_image_not_required[image,200,200]');
        $this->form_validation->set_message('required','This field is required.');
        $this->form_validation->set_message('alpha_numeric_spaces','Only alphabet and numbers are allowed.');
	    if($this->form_validation->run() === false){
            $this->data['title'] = 'Add New payment_method';
            $this->data['sub'] = 'add-payment_method';
            if($_GET['replicate']==1)
            {
                $this->data['prev'] = $this->db->where('is_deleted',0)
                ->order_by('id',"DESC")
                ->get('payment_methods')->result_object()[0];
               
            }
            $this->data['meta'] = $this->load->view('backend/common/meta_data',$this->data,true);
            $this->data['content'] = $this->load->view('backend/payment_methods/add',$this->data,true);
            $this->load->view('backend/common/template',$this->data);
        }else{

	        $dbData['title'] = $this->input->post('title');
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
	        $image = $this->image_upload('image','./resources/uploads/payment_methods/','jpg|jpeg|png|gif');
	        if($image['upload'] == true || $_FILES['image']['size']<1){
                $image = $image['data'];
                if((isset($_FILES['image']) && $_FILES['image']['size'] > 0)){
                    $dbData['image'] = $image['file_name'];
                    $this->image_thumb($image['full_path'],'./resources/uploads/payment_methods/actual_size/',200,200);
                }
                $this->db->insert('payment_methods',$dbData);
                $this->session->set_flashdata('msg','New payment_method added successfully!');
                redirect('admin/payment_methods');
            }else{
                print_r($image);exit;

	            $this->session->set_flashdata('err','An Error occurred durring uploading image, please try again');
	            redirect('admin/add-payment_method');
            }

        }
    }
     /**
     * validation check
     * 
     * @since 1.0
     * @author DeDevelopers
     * @copyright Copyright (c) 2019, DeDevelopers, https://dedevelopers.com
     */
    public function check_payment_method($title){

	    $result = $this->payment_method->get_payment_method_by_title($title);
	    if(!empty($title)) {
            if ($result->num_rows() > 0) {
                $this->form_validation->set_message('check_payment_method', 'This payment_method already exist.');
                return false;
            } else {
                return true;
            }
        }else{
            $this->form_validation->set_message('check_payment_method', 'This field is required.');
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
    public function status($id=0,$status=0){

        $redirect = true;

        if($id==0)
        {
            $id=$_REQUEST['id'];
            $redirect=false;
        }

        if($status==0)
        {
            $status=$_REQUEST['status'];
        }
        $result = $this->payment_method->get_payment_method_by_id($id);

        if(!$result){

            $this->session->set_flashdata('err','Invalid request.');
            redirect('admin/404_page');

        }

        $payment_method_status = 1;

        if($status == 1){

            $payment_method_status = 0;

        }

        $dbData['status'] = $payment_method_status;
        $dbData['updated_at'] = date('Y-m-d H:i:s');
        $dbData['updated_by'] = $this->session->userdata('admin_id');

        $this->db->where('id',$id);
        $this->db->update('payment_methods',$dbData);
        $this->session->set_flashdata('msg','Payment Method status updated successfully!');

        if($redirect)
        redirect('admin/payment_methods');
        else echo "1";
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

        $result = $this->payment_method->get_payment_method_by_id($id);

        if(!$result){

            $this->session->set_flashdata('err','Invalid request.');
            redirect('admin/404_page');

        }

        $this->data['data'] = $result;
        $this->form_validation->set_rules('title','Title','trim|required|alpha_numeric_spaces|callback_check_payment_method_edit['.$id.']');
       
        
        $this->form_validation->set_message('required','This field is required.');
        $this->form_validation->set_message('alpha_numeric_spaces','Only alphabet and numbers are allowed.');
        if($this->form_validation->run() === false){
            $this->data['title'] = 'Edit Payment Method';
         
            $this->data['content'] = $this->load->view('backend/payment_methods/edit',$this->data,true);
             
            $this->load->view('backend/common/template',$this->data);
        }else{

            $dbData['title'] = $this->input->post('title');
            $dbData['paypal_email'] = $this->input->post('paypal_email');
            $dbData['paypal_api'] = $this->input->post('paypal_api');
            $dbData['paypal_secret'] = $this->input->post('paypal_secret');
            $dbData['stripe_api'] = $this->input->post('stripe_api');
            $dbData['stripe_secret'] = $this->input->post('stripe_secret');
            $dbData['bank_name'] = $this->input->post('bank_name');
            $dbData['iban'] = $this->input->post('iban');
            $dbData['company_name'] = $this->input->post('company_name');
            $dbData['description'] = $this->input->post('description');

            $dbData['bank_name_2'] = $this->input->post('bank_name_2');
            $dbData['iban_2'] = $this->input->post('iban_2');

            $dbData['bank_name_3'] = $this->input->post('bank_name_3');
            $dbData['iban_3'] = $this->input->post('iban_3');
         
            $dbData['updated_at'] = date('Y-m-d H:i:s');
            $dbData['updated_by'] = $this->session->userdata('admin_id');


            $this->db->where('id',$id);
            $this->db->update('payment_methods', $dbData);
            $this->session->set_flashdata('msg', 'Payment Method updated successfully!');
            redirect('admin/payment_methods');

        }
    }
     /**
     * validation check
     * 
     * @since 1.0
     * @author DeDevelopers
     * @copyright Copyright (c) 2019, DeDevelopers, https://dedevelopers.com
     */
    public function check_payment_method_edit($title,$id){

        $result = $this->payment_method->get_payment_method_by_title($title);
        if(!empty($title)) {
            if ($result->num_rows() > 0) {
                $result = $result->row();
                if($result->id == $id){
                    return true;
                }else{
                    $this->form_validation->set_message('check_payment_method_edit', 'This payment_method already exist.');
                    return false;
                }
            } else {
                return true;
            }
        }else{
            $this->form_validation->set_message('check_payment_method_edit', 'This field is required.');
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
        $result = $this->payment_method->get_payment_method_by_id($id);

        if(!$result){

            $this->session->set_flashdata('err','Invalid request.');
            redirect('admin/404_page');

        }
        $dbData['deleted_by'] = $this->session->userdata('admin_id');
        $dbData['is_deleted'] = 1;
        $this->db->where('id',$id);
        $this->db->update('payment_methods', $dbData);
        $this->session->set_flashdata('msg', 'payment_method deleted successfully!');
        redirect('admin/payment_methods');
    }
}
