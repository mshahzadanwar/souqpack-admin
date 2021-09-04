<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * handles the Coupons
 * 
 * @since 1.0
 * @author DeDevelopers
 * @copyright Copyright (c) 2019, DeDevelopers, https://dedevelopers.com
 */
class Coupons extends ADMIN_Controller {
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
        $this->redirect_role(20);
        $this->data['active'] = 'coupon';
        $this->load->model('coupons_model','coupon');
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

        $this->data['title'] = 'coupons';
        $this->data['sub'] = 'coupons';
        $this->data['js'] = 'listing';
        $this->data['jsfile'] = 'js/coupons_listing';
        $this->data['coupons'] = $this->coupon->get_all_coupons();
        $this->data['content'] = $this->load->view('backend/coupons/listing',$this->data,true);
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

        $this->data['title'] = 'Trash coupons';
        $this->data['sub'] = 'trash';
        $this->data['js'] = 'listing';
        $this->data['jsfile'] = 'js/coupons_listing';
        $this->data['coupons'] = $this->coupon->get_all_trash_coupons();
        $this->data['content'] = $this->load->view('backend/coupons/trash',$this->data,true);
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
        $this->db->update('coupons', $dbData);
        $this->session->set_flashdata('msg', 'coupon restored successfully!');
        redirect('admin/trash-coupons');
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
        $this->form_validation->set_rules($input,'Title','trim|required|callback_check_coupon');
        $input = "code";
        $this->form_validation->set_rules($input,'Code','trim|required');

        $input = "discount_type";
        $this->form_validation->set_rules($input,'Discount Type','trim|required');

        $input = "discount";
        $this->form_validation->set_rules($input,'Discount','trim|required');

        $input = "from_date";
        $this->form_validation->set_rules($input,'From Date','trim|required');

        $input = "to_date";
        $this->form_validation->set_rules($input,'To Date','trim|required');


       
        $this->form_validation->set_message('required','This field is required.');
        $this->form_validation->set_message('alpha_numeric_spaces','Only alphabet and numbers are allowed.');
        if($this->form_validation->run() === false){
            $this->data['title'] = 'Add New coupon';
            $this->data['sub'] = 'add-coupon';
            $this->data['coupons'] = $this->db->where('is_deleted',0)
            ->where('parent',0)
            ->where('lparent',0)
            ->get('coupons');
            if(isset($_GET['replicate']))
            {
                $this->data['prev'] = $this->db->where('is_deleted',0)
                ->where('id',$_GET['replicate'])
                ->get('coupons')->result_object()[0];
               
            }
            $this->data['meta'] = $this->load->view('backend/common/meta_data',$this->data,true);
            $this->data['content'] = $this->load->view('backend/coupons/add',$this->data,true);
            $this->load->view('backend/common/template',$this->data);
        }else{
            $def_key=0;
            $def_parent=0;
            $fault = false;
            foreach($langs as $key=>$lang){
                $dbData = array();

                $input = $lang->slug."[title]";
                $dbData['title'] = $this->input->post($input);


                $input = "code";
                $dbData['code'] = $this->input->post($input);


                $input = "discount_type";
                $dbData['discount_type'] = $this->input->post($input);


                $input = "discount";
                $dbData['discount'] = $this->input->post($input);

                $input = "from_date";
                $dbData['from_date'] = $this->input->post($input);

                $input = "to_date";
                $dbData['to_date'] = $this->input->post($input);


                

                $input = $lang->slug."[description]";
                $dbData['description'] = $this->input->post($input);

                // $input = $lang->slug."[meta_title]";
                // $dbData['meta_title'] = $this->input->post($input);
                // $input = $lang->slug."[meta_keys]";
                // $dbData['meta_keywords '] = $this->input->post($input);
                // $input = $lang->slug."[meta_desc]";
                // $dbData['meta_description'] = $this->input->post($input);
                $dbData['created_at'] = date('Y-m-d H:i:s');
                $dbData['created_by'] = $this->session->userdata('admin_id');
                $dbData['updated_at'] = date('Y-m-d H:i:s');
                $dbData['updated_by'] = $this->session->userdata('admin_id');
               



                $dbData["lparent"] = $def_key;
                $dbData["lang_id"] = $lang->id;




               $this->db->insert('coupons',$dbData);
                if($def_key==0)
                $def_key = $this->db->insert_id();
            }

          
            $this->session->set_flashdata('msg','New coupon added successfully!');
            redirect('admin/coupons');

        }
    }
    /**
     * validation check
     * 
     * @since 1.0
     * @author DeDevelopers
     * @copyright Copyright (c) 2019, DeDevelopers, https://dedevelopers.com
     */
    public function check_coupon($title){

        $result = $this->coupon->get_coupon_by_title($title);
        if(!empty($title)) {
            if ($result->num_rows() > 0) {
                $this->form_validation->set_message('check_coupon', 'This coupon already exist.');
                return false;
            } else {
                return true;
            }
        }else{
            $this->form_validation->set_message('check_coupon', 'This field is required.');
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

        $result = $this->coupon->get_coupon_by_id($id);

        if(!$result){

            $this->session->set_flashdata('err','Invalid request.');
            redirect('admin/404_page');

        }

        $coupon_status = 1;

        if($status == 1){

            $coupon_status = 0;

        }

        $dbData['status'] = $coupon_status;
        $dbData['updated_at'] = date('Y-m-d H:i:s');
        $dbData['updated_by'] = $this->session->userdata('admin_id');

        $this->db->where('id',$id);
        $this->db->update('coupons',$dbData);
        $this->session->set_flashdata('msg','coupon status updated successfully!');
        redirect('admin/coupons');
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

        $result = $this->coupon->get_coupon_by_id($id);
        $this->data["the_id"] = $id;

        if(!$result){

            $this->session->set_flashdata('err','Invalid request.');
            redirect('admin/404_page');

        }

        $this->data['data'] = $result;



        $dlang = dlang();
        $langs = langs();
        $input = $dlang->slug."[title]";
        $this->form_validation->set_rules($input,'Title','trim|required|callback_check_coupon');
         $input = "code";
        $this->form_validation->set_rules($input,'Code','trim|required');

        $input = "discount_type";
        $this->form_validation->set_rules($input,'Discount Type','trim|required');

        $input = "discount";
        $this->form_validation->set_rules($input,'Discount','trim|required');

        $input = "from_date";
        $this->form_validation->set_rules($input,'From Date','trim|required');

        $input = "to_date";
        $this->form_validation->set_rules($input,'To Date','trim|required');

        $this->form_validation->set_message('required','This field is required.');
        $this->form_validation->set_message('alpha_numeric_spaces','Only alphabet and numbers are allowed.');
        
        if($this->form_validation->run() === false){
            $this->data['title'] = 'Edit coupon';
            $this->data['meta'] = $this->load->view('backend/common/meta_data',$this->data,true);
            $this->data['coupons'] = $this->db->where('is_deleted',0)
            ->where('parent',0)
            ->where('lparent',0)
            ->get('coupons');
            $this->data['content'] = $this->load->view('backend/coupons/edit',$this->data,true);
             
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


                $input = "code";
                $dbData['code'] = $this->input->post($input);


                $input = "discount_type";
                $dbData['discount_type'] = $this->input->post($input);


                $input = "discount";
                $dbData['discount'] = $this->input->post($input);

                $input = "from_date";
                $dbData['from_date'] = $this->input->post($input);

                $input = "to_date";
                $dbData['to_date'] = $this->input->post($input);


                

                $input = $lang->slug."[description]";
                $dbData['description'] = $this->input->post($input);


                $input = $lang->slug."[description]";
                $dbData['description'] = $this->input->post($input);
                // $input = $lang->slug."[meta_title]";
                // $dbData['meta_title'] = $this->input->post($input);
                // $input = $lang->slug."[meta_keys]";
                // $dbData['meta_keywords '] = $this->input->post($input);
                // $input = $lang->slug."[meta_desc]";
                // $dbData['meta_description'] = $this->input->post($input);
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
                $this->db->update('coupons',$dbData);
            }

            $this->session->set_flashdata('msg','coupon updated successfully!');
            redirect('admin/coupons');

        }
    }
    /**
     * validation check
     * 
     * @since 1.0
     * @author DeDevelopers
     * @copyright Copyright (c) 2019, DeDevelopers, https://dedevelopers.com
     */
    public function check_coupon_edit($title,$id){

        $result = $this->coupon->get_coupon_by_title($title);
        if(!empty($title)) {
            if ($result->num_rows() > 0) {
                $result = $result->row();
                if($result->id == $id){
                    return true;
                }else{
                    $this->form_validation->set_message('check_coupon_edit', 'This coupon already exist.');
                    return false;
                }
            } else {
                return true;
            }
        }else{
            $this->form_validation->set_message('check_coupon_edit', 'This field is required.');
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
        $result = $this->coupon->get_coupon_by_id($id);

        if(!$result){

            $this->session->set_flashdata('err','Invalid request.');
            redirect('admin/404_page');

        }
        $dbData['deleted_by'] = $this->session->userdata('admin_id');
        $dbData['is_deleted'] = 1;
        $this->db->where('id',$id);
        $this->db->update('coupons', $dbData);
        $this->session->set_flashdata('msg', 'coupon deleted successfully!');
        redirect('admin/coupons');
    }
    public function display_order()
    {
        // $result = $this->coupon->get_coupon_display_order();

        // if(!$result){

        //     $this->session->set_flashdata('err','Invalid request.');
        //     redirect('admin/404_page');

        // }

        $this->data['data'] = $result;
        $this->form_validation->set_rules('json_order','Title','trim|required');
       
        if($this->form_validation->run() === false){
            $this->data['title'] = 'Edit coupons Display Order';
            $this->data['jsfile'] = "js/coupons_display_order";
            $this->data['coupons'] = $this->db->where('is_deleted',0)
            ->order_by('display_priority',"ASC")
            ->where('parent',0)
            ->get('coupons');

            $this->data['content'] = $this->load->view('backend/coupons/display_order',$this->data,true);
             
            $this->load->view('backend/common/template',$this->data);
        }else{


            $json_order = $this->input->post('json_order');
            $json_order = json_decode($json_order);
            $i = 1;
            foreach ($json_order as $json_order_key => $json_order_value) {
                $this->db->where('id',$json_order_value->id)
                ->update('coupons',array(
                    'parent'=>0,
                    'display_priority'=>$i
                ));
                $i++;

                foreach($json_order_value->children as $child)
                {
                    $this->db->where('id',$child->id)->update('coupons',array('parent'=>$json_order_value->id,'display_priority'=>$i));
                    $i++;
                }
            }
            
            // $this->db->where('id',$id);
            // $this->db->update('coupons', $dbData);
            $this->session->set_flashdata('msg', 'coupon updated successfully!');
            redirect($_SERVER['HTTP_REFERER']);

        }
    }
}
