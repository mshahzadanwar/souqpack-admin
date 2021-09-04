<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * handles the purchases
 * 
 * @since 1.0
 * @author DeDevelopers
 * @copyright Copyright (c) 2019, DeDevelopers, https://dedevelopers.com
 */
class Purchases extends ADMIN_Controller {
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
        $this->redirect_role(28);

        $this->data['active'] = 'purchase';
        $this->load->model('purchases_model','purchase');
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

        $this->data['title'] = 'purchases';
        $this->data['sub'] = 'purchases';
        $this->data['js'] = 'listing';
        $this->data['jsfile'] = 'js/purchases_listing';
        $this->data['purchases'] = $this->purchase->get_all_purchases();
        $this->data['content'] = $this->load->view('backend/purchases/listing',$this->data,true);
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

        $this->data['title'] = 'Trash purchases';
        $this->data['sub'] = 'trash';
        $this->data['js'] = 'listing';
        $this->data['jsfile'] = 'js/purchases_listing';
        $this->data['purchases'] = $this->purchase->get_all_trash_purchases();
        $this->data['content'] = $this->load->view('backend/purchases/trash',$this->data,true);
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
        $this->db->update('purchases', $dbData);
        $this->session->set_flashdata('msg', 'purchase restored successfully!');
        redirect('admin/trash-purchases');
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

      
        $this->form_validation->set_message('required','This field is required.');
        if($this->form_validation->run() === false){
            $this->data['title'] = 'Add New purchase';
            $this->data['sub'] = 'add-purchase';
            
            $this->data['content'] = $this->load->view('backend/purchases/add',$this->data,true);
            $this->load->view('backend/common/template',$this->data);
        }else{

            $def_key=0;
            $def_parent=0;
            $fault = false;
            foreach($langs as $key=>$lang){

                $input = $lang->slug."[title]";
                $dbData['title'] = $this->input->post($input);

                if($lang->slug=="english")
                    $ptile = $dbData["title"];

                $input = $lang->slug."[avl_qty]";
                $dbData['avl_qty'] = $this->input->post($input);

                $input = $lang->slug."[vendor]";
                $dbData['vendor'] = $this->input->post($input);

                 $input = $lang->slug."[size]";
                $dbData['size'] = $this->input->post($input);



                $input = $lang->slug."[price]";
                $dbData['price'] = $this->input->post($input);

                $input = $lang->slug."[unit_price]";
                $dbData['unit_price'] = $this->input->post($input);


                $input = $lang->slug."[total_price]";
                $dbData['total_price'] = $this->input->post($input);

                $input = $lang->slug."[discount]";
                $dbData['discount'] = $this->input->post($input)?$this->input->post($input):0;
                $dbData['region_id'] = $this->input->post("region_id");

                $input = $lang->slug."[sku]";
                $dbData['sku'] = $this->input->post($input);

                

 
                $dbData['created_at'] = date('Y-m-d H:i:s');
                $dbData['created_by'] = $this->session->userdata('admin_id');
                $dbData['updated_at'] = date('Y-m-d H:i:s');
                $dbData['updated_by'] = $this->session->userdata('admin_id');
                $input = $lang->slug."[parent]";
               



                $dbData["lparent"] = $def_key;
                $dbData["lang_id"] = $lang->id;



               
                $this->db->insert('purchases',$dbData);

                //$this->db->last_query();
                
                if($lang->slug=="english")
                $def_key = $this->db->insert_id();


                // $thread = array(
                //     "by"=>"Purchaser",
                //     "order_id"=>$order_id,
                //     "by_id"=>$this->session->userdata('admin_id'),
                //     "desc"=> "A new purcha",
                //     "for_admin"=>1,
                //     "created_at"=>date("Y-m-d H:i:s")
                // );

                  
                // $this->db->insert("notifications",$thread);
            }

            // $thread = array(
            //     "by"=>"Admin",
            //     "order_id"=>$def_key,
            //     "function"=>"redirect_new_purchase",
            //     "by_id"=>$this->session->userdata('admin_id'),
            //     "desc"=> get_admin_by_id($this->session->userdata('admin_id'))->result_object()[0]->name." has added a new purcahse ".$ptile,
            //     "for_admin"=>1,
            //     "created_at"=>date("Y-m-d H:i:s")
            // );

              
            // $this->db->insert("notifications",$thread);

            
            $this->session->set_flashdata('msg','New purchase added successfully!');
            redirect('admin/purchases');

        }
    }
    /**
     * validation check
     * 
     * @since 1.0
     * @author DeDevelopers
     * @copyright Copyright (c) 2019, DeDevelopers, https://dedevelopers.com
     */
    public function check_purchase($title){

        $result = $this->purchase->get_purchase_by_title($title);
        if(!empty($title)) {
            if ($result->num_rows() > 0) {
                $this->form_validation->set_message('check_purchase', 'This purchase already exist.');
                return false;
            } else {
                return true;
            }
        }else{
            $this->form_validation->set_message('check_purchase', 'This field is required.');
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

        $result = $this->purchase->get_purchase_by_id($id);

        if(!$result){

            $this->session->set_flashdata('err','Invalid request.');
            redirect('admin/404_page');

        }

        $purchase_status = 1;

        if($status == 1){

            $purchase_status = 0;

        }

        $dbData['status'] = $purchase_status;
        $dbData['updated_at'] = date('Y-m-d H:i:s');
        $dbData['updated_by'] = $this->session->userdata('admin_id');

        $this->db->where('id',$id);
        $this->db->update('purchases',$dbData);
        $this->session->set_flashdata('msg','purchase status updated successfully!');
        redirect('admin/purchases');
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

        $result = $this->purchase->get_purchase_by_id($id);
        $this->data["the_id"] = $id;

        if(!$result){

            $this->session->set_flashdata('err','Invalid request.');
            redirect('admin/404_page');

        }

        $this->data['data'] = $result;



        $dlang = dlang();
        $langs = langs();
        $input = $dlang->slug."[avl_qty]";
        $this->form_validation->set_rules($input,'Available Quantity','trim|required');

        
        $this->form_validation->set_message('required','This field is required.');
       
        if($this->form_validation->run() === false){
            $this->data['title'] = 'Edit purchase';
            $this->data['content'] = $this->load->view('backend/purchases/edit',$this->data,true);
            $this->load->view('backend/common/template',$this->data);

        }else{
           
            $def_key=0;
            $def_parent=0;
            $fault = false;
            foreach($langs as $key=>$lang){
                $dbData=array();
                $input = $lang->slug."[row_id]";
                $row_id = $this->input->post($input);



                //$input = $lang->slug."[title]";
                //$dbData['title'] = $this->input->post($input);

                
                 $input = $lang->slug."[size]";
                $dbData['size'] = $this->input->post($input);



                $input = $lang->slug."[vendor]";
                $dbData['vendor'] = $this->input->post($input);


                $input = $lang->slug."[price]";
                $dbData['price'] = $this->input->post($input);

                $input = $lang->slug."[unit_price]";
                $dbData['unit_price'] = $this->input->post($input);

                $input = $lang->slug."[total_price]";
                $dbData['total_price'] = $this->input->post($input);

                $input = $lang->slug."[discount]";
                $dbData['discount'] = $this->input->post($input)?$this->input->post($input):0;
                $dbData['region_id'] = $this->input->post("region_id");

                $input = $lang->slug."[sku]";
                $dbData['sku'] = $this->input->post($input);

                $input = $lang->slug."[avl_qty]";
                $dbData['avl_qty'] = $this->input->post($input);


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
                $this->db->update('purchases',$dbData);
               
                // UPDATE PRPODUCT MINI QTY
                $purchase = $this->db->query("SELECT * FROM purchases WHERE id = '".$row_id."'")->result_object()[0];
                $product = $this->db->query("SELECT * FROM products WHERE title = '".$purchase->title."'")->result_object()[0];
                $qty_purchase_t =  (20/100)*$dbData['avl_qty'];
                $this->db->query("UPDATE products SET min_order_qty = ".$qty_purchase_t." WHERE id = ".$product->id);
            }
           
            

            $this->session->set_flashdata('msg','purchase updated successfully!');
            redirect('admin/purchases');

        }
    }
    /**
     * validation check
     * 
     * @since 1.0
     * @author DeDevelopers
     * @copyright Copyright (c) 2019, DeDevelopers, https://dedevelopers.com
     */
    public function check_purchase_edit($title,$id){

        $result = $this->purchase->get_purchase_by_title($title);
        if(!empty($title)) {
            if ($result->num_rows() > 0) {
                $result = $result->row();
                if($result->id == $id){
                    return true;
                }else{
                    $this->form_validation->set_message('check_purchase_edit', 'This purchase already exist.');
                    return false;
                }
            } else {
                return true;
            }
        }else{
            $this->form_validation->set_message('check_purchase_edit', 'This field is required.');
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
        $result = $this->purchase->get_purchase_by_id($id);

        if(!$result){

            $this->session->set_flashdata('err','Invalid request.');
            redirect('admin/404_page');

        }
        $dbData['deleted_by'] = $this->session->userdata('admin_id');
        $dbData['is_deleted'] = 1;
        $this->db->where('id',$id);
        $this->db->update('purchases', $dbData);
        $this->session->set_flashdata('msg', 'purchase deleted successfully!');
        redirect('admin/purchases');
    }
    public function view_details($id)
    {
        $data["purchase"] = $this->db->where("id",$id)->get("purchases")->result_object()[0];
        $data["purchase_ar"] = $this->db->where("lparent",$id)->get("purchases")->result_object()[0];

        echo $this->load->view("backend/purchases/details",$data,true);
    }


    public function update_purchase_data(){
        $products = $this->db->query("SELECT * FROM products")->result_object();
        $qty_purchase = 500;
        
        foreach ($products as $key => $product) {
            
            if($product->lparent == 0){
                $price = $this->db->query("SELECT * FROM product_units WHERE product_id = ".$product->id)->result_object()[0];
                $dbData["title"]    = $product->title;
                $dbData["price"]    = $price->price;
                $dbData["sku"]      = $product->sku;
                $dbData["avl_qty"]  = $qty_purchase;
                $dbData["created_at"] = date("Y-m-d H:i:s");
                $dbData["status"]   = 1;
                $dbData["lang_id"]  = $product->lang_id;
                $dbData["lparent"]  = 0;
                $this->db->insert('purchases',$dbData);
            }else{
                $product_en = $this->db->query("SELECT * FROM products WHERE id  =".$product->lparent)->result_object()[0];
                $price = $this->db->query("SELECT * FROM product_units WHERE product_id = ".$product_en->id)->result_object()[0];

                $purchase = $this->db->query("SELECT * FROM purchases WHERE title = '".$product_en->title."'")->result_object()[0];
                $dbData["title"]    = $product->title;
                $dbData["price"]    = $price->price;
                $dbData["sku"]      = $product_en->sku;
                $dbData["avl_qty"]  = $qty_purchase;
                $dbData["created_at"] = date("Y-m-d H:i:s");
                $dbData["status"]   = 1;
                $dbData["lang_id"]  = 1;
                $dbData["lparent"]  = $purchase->id;
                $this->db->insert('purchases',$dbData);
            }

            $qty_purchase_t =  (20/100)*$qty_purchase;
            $this->db->query("UPDATE products SET min_order_qty = ".$qty_purchase_t." WHERE id = ".$product->id);
        }

    }
   
}
