<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * handles the costs
 * 
 * @since 1.0
 * @author DeDevelopers
 * @copyright Copyright (c) 2019, DeDevelopers, https://dedevelopers.com
 */
class Costs extends ADMIN_Controller {
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
        // $this->redirect_role(28);

        $this->data['active'] = 'cost';
        $this->load->model('costs_model','cost');
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

        $this->data['title'] = 'costs';
        $this->data['sub'] = 'costs';
        $this->data['js'] = 'listing';
        $this->data['jsfile'] = 'js/costs_listing';
        $this->data['costs'] = $this->cost->get_all_costs();
        $this->data['content'] = $this->load->view('backend/costs/listing',$this->data,true);
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

        $this->data['title'] = 'Trash costs';
        $this->data['sub'] = 'trash';
        $this->data['js'] = 'listing';
        $this->data['jsfile'] = 'js/costs_listing';
        $this->data['costs'] = $this->cost->get_all_trash_costs();
        $this->data['content'] = $this->load->view('backend/costs/trash',$this->data,true);
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
        $this->db->update('costs', $dbData);
        $this->session->set_flashdata('msg', 'cost restored successfully!');
        redirect('admin/trash-costs');
    }
    /**
     * loads the add view, then handles the submitted data
     * 
     * @since 1.0
     * @author DeDevelopers
     * @copyright Copyright (c) 2019, DeDevelopers, https://dedevelopers.com
     */
    public function add (){

      
        $this->form_validation->set_rules("supplier_name",'supplier_name','trim|required');

      
        $this->form_validation->set_message('required','This field is required.');
        if($this->form_validation->run() === false){
            $this->data['title'] = 'Add New cost';
            $this->data['sub'] = 'add-cost';
            $this->data['jsfile'] = 'js/add_cost';
            $this->data['content'] = $this->load->view('backend/costs/add',$this->data,true);
            $this->load->view('backend/common/template',$this->data);
        }else{
            
                $dbData['user_id'] = $this->session->userdata('admin_id');
                $dbData['supplier_name'] = $this->input->post("supplier_name");
                $dbData['supplier_phone'] = $this->input->post("supplier_phone");
                $dbData['supplier_address'] = $this->input->post("supplier_address");

                $dbData['invoice_no'] = $this->input->post("invoice_no");
                $dbData['date'] = $this->input->post("date");

                $dbData['created_at'] = date('Y-m-d H:i:s');
                $dbData['created_by'] = $this->session->userdata('admin_id');
                $dbData['updated_at'] = date('Y-m-d H:i:s');
                $dbData['updated_by'] = $this->session->userdata('admin_id');
           
               



                $dbData["lparent"] = 0;

                 $input = "invoice";
                if(!empty($_FILES[$input]['name'])) {
                    
                    $image = $this->file_upload_func($input, './resources/uploads/costs/', 'jpg|jpeg|png|gif|pdf|txt|doc|docx|docs');
                    if ($image['upload'] == true) {
                        $dbData['invoice_file'] = $image['data']['file_name'];
                        $dbData["filename"]=$file_name;
                        $dbData["is_image"] = $image["data"]["is_image"];
                        $dbData["file_type"] = $image["data"]["file_ext"];
                        $dbData["file_size"] = $image["data"]["file_size"];
                    } else {

                       
                    }
                }





               
                $this->db->insert('costs',$dbData);

                $the_id = $this->db->insert_id();
                foreach($this->input->post("item_name") as $key=>$item)
                {
                    $item = array(
                        "cost_id"=>$the_id,
                        "item_name"=>$this->input->post("item_name")[$key],
                        "item_details"=>$this->input->post("item_details")[$key],
                        "item_qty"=>$this->input->post("item_qty")[$key],
                        "item_cost"=>$this->input->post("item_cost")[$key],
                        "item_total"=>number_format($this->input->post("item_cost")[$key]*$this->input->post("item_qty")[$key],2),
                    );

                    $this->db->insert("cost_items",$item);
                }

            $this->session->set_flashdata('msg','New cost added successfully!');
            redirect('admin/costs');

        }
    }
    /**
     * validation check
     * 
     * @since 1.0
     * @author DeDevelopers
     * @copyright Copyright (c) 2019, DeDevelopers, https://dedevelopers.com
     */
    public function check_cost($title){

        $result = $this->cost->get_cost_by_title($title);
        if(!empty($title)) {
            if ($result->num_rows() > 0) {
                $this->form_validation->set_message('check_cost', 'This cost already exist.');
                return false;
            } else {
                return true;
            }
        }else{
            $this->form_validation->set_message('check_cost', 'This field is required.');
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

        $result = $this->cost->get_cost_by_id($id);

        if(!$result){

            $this->session->set_flashdata('err','Invalid request.');
            redirect('admin/404_page');

        }

        $cost_status = 1;

        if($status == 1){

            $cost_status = 0;

        }

        $dbData['status'] = $cost_status;
        $dbData['updated_at'] = date('Y-m-d H:i:s');
        $dbData['updated_by'] = $this->session->userdata('admin_id');

        $this->db->where('id',$id);
        $this->db->update('costs',$dbData);
        $this->session->set_flashdata('msg','cost status updated successfully!');
        redirect('admin/costs');
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

        $result = $this->cost->get_cost_by_id($id);
        $this->data["the_id"] = $id;

        if(!$result){

            $this->session->set_flashdata('err','Invalid request.');
            redirect('admin/404_page');

        }

        $this->data['data'] = $result;



         $this->form_validation->set_rules("supplier_name",'supplier_name','trim|required');

        
        $this->form_validation->set_message('required','This field is required.');
       
        if($this->form_validation->run() === false){
            $this->data['title'] = 'Edit cost';
            $this->data['jsfile'] = 'js/add_cost';
            $this->data['content'] = $this->load->view('backend/costs/edit',$this->data,true);
             
            $this->load->view('backend/common/template',$this->data);
        }else{

                $dbData['user_id'] = $this->session->userdata('admin_id');
                $dbData['supplier_name'] = $this->input->post("supplier_name");
                $dbData['supplier_phone'] = $this->input->post("supplier_phone");
                $dbData['supplier_address'] = $this->input->post("supplier_address");

                $dbData['invoice_no'] = $this->input->post("invoice_no");
                $dbData['date'] = $this->input->post("date");

               
                $dbData['updated_at'] = date('Y-m-d H:i:s');
                $dbData['updated_by'] = $this->session->userdata('admin_id');
           
               



                $dbData["lparent"] = 0;

                if($this->input->post("do_i_remove_file")==1)
                    $dbData["invoice_file"] ="";

                 $input = "invoice";
                if(!empty($_FILES[$input]['name'])) {
                    $file_name = $_FILES[$input]['name'];
                    $image = $this->file_upload_func($input, './resources/uploads/costs/', 'jpg|jpeg|png|gif|pdf|txt|doc|docx|docs');
                    if ($image['upload'] == true) {
                        // $image = $image['data'];
                        $dbData['invoice_file'] = $image['data']['file_name'];


                        $dbData["filename"]=$file_name;
                        $dbData["is_image"] = $image["data"]["is_image"];
                        $dbData["file_type"] = $image["data"]["file_ext"];
                        $dbData["file_size"] = $image["data"]["file_size"];
                    } else {

                       
                    }
                }




                 $this->db->where("id",$id);
               
                $this->db->update('costs',$dbData);

               
                // UPDATE COST ITEMS
                //$this->db->where("cost_id",$id)->delete("cost_items");
               
                foreach($this->input->post("item_name") as $key=>$item)
                {
                        $id_update = $this->input->post("item_idddd")[$key];
                        $price_cost = $this->input->post("item_cost")[$key]*$this->input->post("item_qty")[$key];
                        
                        $item = array(
                            "cost_id"=>$id,
                            "item_name"=>$this->input->post("item_name")[$key],
                            "item_details"=>$this->input->post("item_details")[$key],
                            "item_qty"=>$this->input->post("item_qty")[$key],
                            "item_cost"=>$this->input->post("item_cost")[$key],
                            "item_total"=>$price_cost,
                        );
                    if($id_update != "") {
                        unset($item["cost_id"]);
                        $this->db->where("id",$id_update);
                        $this->db->update("cost_items",$item);

                        $row_shw = $this->db->query("SELECT * FROM cost_items WHERE id = ".$id_update)->result_object()[0];
                        $cost_detailss = $this->db->where("id",$row_shw->cost_id)->get("costs")->result_object()[0];
                        if($row_shw->sk_status != 0){
                            $desc = "Purchase Person has updated the item information you rejected againts Invoice # ".$cost_detailss->invoice_no;
                            $for_stock = 1;
                            $for_purchaser = 0;
                        
                            $thread = array(
                                "by"=>"Admin",
                                "order_id"=>$cost_detailss->id,
                                "by_id"=>$this->session->userdata("admin_id"),
                                "desc"=> $desc,
                                "for_purchaser"=>$for_purchaser,
                                "for_stock"=>$for_stock,
                                "puchase_stcok" => 1,
                                "created_at"=>date("Y-m-d H:i:s")
                            );

                            $this->db->insert("notifications",$thread);

                            $item = array(
                                "sk_status"=>3,
                            );
                        $this->db->where("id",$id_update);
                        $this->db->update("cost_items",$item);

                        }
                    } else {
                        $this->db->insert("cost_items",$item);
                    }
                }
           
            $this->session->set_flashdata('msg','cost updated successfully!');
            redirect('admin/costs');

        }
    }
    /**
     * validation check
     * 
     * @since 1.0
     * @author DeDevelopers
     * @copyright Copyright (c) 2019, DeDevelopers, https://dedevelopers.com
     */
    public function check_cost_edit($title,$id){

        $result = $this->cost->get_cost_by_title($title);
        if(!empty($title)) {
            if ($result->num_rows() > 0) {
                $result = $result->row();
                if($result->id == $id){
                    return true;
                }else{
                    $this->form_validation->set_message('check_cost_edit', 'This cost already exist.');
                    return false;
                }
            } else {
                return true;
            }
        }else{
            $this->form_validation->set_message('check_cost_edit', 'This field is required.');
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
        $result = $this->cost->get_cost_by_id($id);

        if(!$result){

            $this->session->set_flashdata('err','Invalid request.');
            redirect('admin/404_page');

        }
        $dbData['deleted_by'] = $this->session->userdata('admin_id');
        $dbData['is_deleted'] = 1;
        $this->db->where('id',$id);
        $this->db->update('costs', $dbData);
        $this->session->set_flashdata('msg', 'cost deleted successfully!');
        redirect('admin/costs');
    }
    public function view_details($id)
    {
        $data["cost"] = $this->db->where("id",$id)->get("costs")->result_object()[0];
        $data["cost_ar"] = $this->db->where("lparent",$id)->get("costs")->result_object()[0];

        echo $this->load->view("backend/costs/details",$data,true);
    }
    public function print_row()
    {
        $row["item_no"] = $this->input->post("key");
        echo new_row_cost($row);
    }
    public function download($id)
    {
        $thread = $this->db->where("id",$id)->get("costs")->result_object()[0];

        $this->load->helper('download');
        $data = file_get_contents(base_url()."resources/uploads/costs/".$thread->invoice_file);
        force_download($thread->filename, $data);
    }
    public function details($id)
    {

        $this->data['title'] = 'Cost Details';
        $this->data['sub'] = 'costs';
        $this->data['jsfile'] = 'js/cost_details';
        $this->data['data'] = $this->db->where("id",$id)->get("costs")->result_object()[0];
        $this->data['content'] = $this->load->view('backend/costs/details',$this->data,true);
        $this->load->view('backend/common/template',$this->data);
    }

    public function approveItem($id){

        $result = $this->db->where("id",$id)->get("cost_items")->result_object()[0];

         if(!$result || (!is_accountant() && !is_stock() && check_role(-1))){

            $this->session->set_flashdata('err','Invalid request.');
            redirect('admin/404_page');

        }


        if(is_accountant())
        {
            $dbData["ac_status"]=1;
            $dbData["ac_id"]=$this->session->userdata('admin_id');
            $dbData["ac_at"]=date('Y-m-d H:i:s');
        }
        else
        {
            $dbData["sk_status"]=1;
            $dbData["sk_id"]=$this->session->userdata('admin_id');
            $dbData["sk_at"]=date('Y-m-d H:i:s');
        }

       
      
        $this->db->where('id',$id);
        $this->db->update('cost_items',$dbData);
        $this->session->set_flashdata('msg','cost item status updated successfully!');
        $this->redirect_back();
    }
    public function rejectItem(){

        $id = $this->input->post("item_id");
        $result = $this->db->where("id",$id)->get("cost_items")->result_object()[0];
        if(!$result || (!is_accountant() && !is_stock() && check_role(-1))){

            $this->session->set_flashdata('err','Invalid request.');
            redirect('admin/404_page');

        }
        
        $cost_detailss = $this->db->where("id",$result->cost_id)->get("costs")->result_object()[0];
        
        if(is_accountant())
        {
            $dbData["ac_status"]=2;
            $dbData["ac_comments"]=$this->input->post('reason');
            $dbData["ac_id"]=$this->session->userdata('admin_id');
            $dbData["ac_at"]=date('Y-m-d H:i:s');
        }
        else
        {
            $dbData["sk_status"]=2;
            $dbData["sk_comments"]=$this->input->post('reason');
            $dbData["sk_id"]=$this->session->userdata('admin_id');
            $dbData["sk_at"]=date('Y-m-d H:i:s');
        }

        if(is_stock())
        {
            $desc = "Stock Person has rejected an item in Purchase/Cost Againts Invoice # ".$cost_detailss->invoice_no;
            $for_stock = 0;
            $for_purchaser = 1;
        
            $thread = array(
                "by"=>"Admin",
                "order_id"=>$cost_detailss->id,
                "by_id"=>$this->session->userdata("admin_id"),
                "desc"=> $desc,
                "for_purchaser"=>$for_purchaser,
                "for_stock"=>$for_stock,
                "puchase_stcok" => 1,
                "created_at"=>date("Y-m-d H:i:s")
            );

            $this->db->insert("notifications",$thread);
        }
        
        $this->db->where('id',$id);
        $this->db->update('cost_items',$dbData);
        $this->session->set_flashdata('msg','cost item status updated successfully!');
        $this->redirect_back();
    }
}
