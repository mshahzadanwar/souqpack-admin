<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * handles the Categories
 * 
 * @since 1.0
 * @author DeDevelopers
 * @copyright Copyright (c) 2019, DeDevelopers, https://dedevelopers.com
 */
class Categories extends ADMIN_Controller {
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
        $this->redirect_role(1);
        $this->data['active'] = 'category';
        $this->load->model('categories_model','category');
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

		$this->data['title'] = 'Categories';
        $this->data['sub'] = 'categories';
        $this->data['js'] = 'listing';
        $this->data['jsfile'] = 'js/categories_listing';
        // if(isset($_POST['form_filter'])){
        //     $this->data['categories'] = $this->category->get_all_categories();
        // } else {
            $this->data['categories'] = $this->category->get_all_categories();
        // }
		$this->data['content'] = $this->load->view('backend/categories/listing',$this->data,true);
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
        redirect_region();
        $this->data['title'] = 'Trash Categories';
        $this->data['sub'] = 'trash';
        $this->data['js'] = 'listing';
        $this->data['jsfile'] = 'js/categories_listing';
        $this->data['categories'] = $this->category->get_all_trash_categories();
        $this->data['content'] = $this->load->view('backend/categories/trash',$this->data,true);
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
        redirect_region();
        $dbData['deleted_by'] = $this->session->userdata('admin_id');
        $dbData['is_deleted'] = 0;
        $this->db->where('id',$id);
        $this->db->update('categories', $dbData);
        $this->session->set_flashdata('msg', 'Category restored successfully!');
        redirect('admin/trash-categories');
    }
    /**
     * loads the add view, then handles the submitted data
     * 
     * @since 1.0
     * @author DeDevelopers
     * @copyright Copyright (c) 2019, DeDevelopers, https://dedevelopers.com
     */
	public function add (){

        redirect_region();

        $dlang = dlang();
        $langs = langs();
        $input = $dlang->slug."[title]";
	    $this->form_validation->set_rules($input,'Title','trim|required');

        $input = $dlang->slug."[image]";
	    $this->form_validation->set_rules($input,'Image','callback_image_not_required['.$input.',20,20]');
	    if($this->form_validation->run() === false){
            $this->data['title'] = 'Add New Category';
            $this->data['sub'] = 'add-category';
            $this->data['categories'] = $this->db->where('is_deleted',0)
            ->where('parent',0)
            ->where('lparent',0)
            ->get('categories');
            if(isset($_GET['replicate']))
            {
                $this->data['prev'] = $this->db->where('is_deleted',0)
                ->where('id',$_GET['replicate'])
                ->get('categories')->result_object()[0];
               
            }
            $this->data['meta'] = $this->load->view('backend/common/meta_data',$this->data,true);
            $this->data['content'] = $this->load->view('backend/categories/add',$this->data,true);
            $this->load->view('backend/common/template',$this->data);
        }else{
            $def_key=0;
            $def_parent=0;
            $fault = false;
            foreach($langs as $key=>$lang){
                $input = $lang->slug."[title]";
    	        $dbData['title'] = $this->input->post($input);

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



                $dbData["lparent"] = $def_key;
                $dbData["lang_id"] = $lang->id;

                $input = $lang->slug."_image";
                if((isset($_FILES[$input]) && $_FILES[$input]['size'] > 0))
    	        $image = $this->image_upload($input,'./resources/uploads/categories/','jpg|jpeg|png|gif');
    	        if($image['upload'] == true || $_FILES[$input]['size']<1){
                    $image = $image['data'];
                    if((isset($_FILES[$input]) && $_FILES[$input]['size'] > 0)){
                        $dbData['image'] = $image['file_name'];
                        $this->image_thumb($image['full_path'],'./resources/uploads/categories/actual_size/',1400,438);
                    }
                    // $this->insert_lang_wise('categories',$dbData,$this->input->post("clang"));
                    $this->db->insert('categories',$dbData);
                    if($def_key==0)
                    $def_key = $this->db->insert_id();
                    
                }else{
                    $fault=true;
                }
            }

            if($fault){
                $this->session->set_flashdata('err','An Error occurred durring uploading image, please try again');
                redirect('admin/add-category');
                return;
            }
            $this->session->set_flashdata('msg','New Category added successfully!');
            redirect('admin/categories');
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
        redirect_region();
        $result = $this->category->get_category_by_id($id);

        if(!$result){

            $this->session->set_flashdata('err','Invalid request.');
            redirect('admin/404_page');

        }

        $category_status = 1;

        if($status == 1){

            $category_status = 0;

        }

        $dbData['status'] = $category_status;
        $dbData['updated_at'] = date('Y-m-d H:i:s');
        $dbData['updated_by'] = $this->session->userdata('admin_id');

        $this->db->where('id',$id);
        $this->db->update('categories',$dbData);
        $this->session->set_flashdata('msg','Category status updated successfully!');
        redirect('admin/categories');
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
    public function cust($id){
        redirect_region();
        $result = $this->category->get_category_by_id($id);

        if(!$result){

            $this->session->set_flashdata('err','Invalid request.');
            redirect('admin/404_page');

        }

        $category_status = 1;

        if($result->cust == 1){

            $category_status = 0;

        }

        $dbData['cust'] = $category_status;
        $dbData['updated_at'] = date('Y-m-d H:i:s');
        $dbData['updated_by'] = $this->session->userdata('admin_id');

        $this->db->where('id',$id);
        if($result->lparent!=0)
        {
            $this->db->or_where("id",$result->lparent);
        }
        else
        {
            $this->db->or_where("lparent",$result->id);

        }
        $this->db->update('categories',$dbData);
        $this->session->set_flashdata('msg','Category updated successfully!');
        redirect('admin/categories');
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
        redirect_region();
        $result = $this->category->get_category_by_id($id);
        $this->data["the_id"] = $id;

        if(!$result){

            $this->session->set_flashdata('err','Invalid request.');
            redirect('admin/404_page');

        }

        $this->data['data'] = $result;



        $dlang = dlang();
        $langs = langs();
        $input = $dlang->slug."[title]";
        $this->form_validation->set_rules($input,'Title','trim|required');

        $input = $dlang->slug."[image]";
        $this->form_validation->set_rules($input,'Image','callback_image_not_required['.$input.',20,20]');
        $this->form_validation->set_message('required','This field is required.');
        $this->form_validation->set_message('alpha_numeric_spaces','Only alphabet and numbers are allowed.');
        
        if($this->form_validation->run() === false){
            $this->data['title'] = 'Edit Category';
            $this->data['meta'] = $this->load->view('backend/common/meta_data',$this->data,true);
            $this->data['categories'] = $this->db->where('is_deleted',0)
            ->where('parent',0)
            ->where('lparent',0)
            ->get('categories');
            $this->data['content'] = $this->load->view('backend/categories/edit',$this->data,true);
             
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

                $input = $lang->slug."[slug]";
                $dbData['slug'] = $this->input->post($input);

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

                $input = $lang->slug."_image";
                if((isset($_FILES[$input]) && $_FILES[$input]['size'] > 0))
                $image = $this->image_upload($input,'./resources/uploads/categories/','jpg|jpeg|png|gif');
                if($image['upload'] == true || $_FILES[$input]['size']<1){
                    $image = $image['data'];
                    if((isset($_FILES[$input]) && $_FILES[$input]['size'] > 0)){
                        $dbData['image'] = $image['file_name'];
                        $this->image_thumb($image['full_path'],'./resources/uploads/categories/actual_size/',1400,438);
                    }
                }else{
                    if($lang->is_default==1){
                         $this->session->set_flashdata('err',$error);
                        redirect($_SERVER["HTTP_REFERER"]);
                        return;
                    }
                }
                $this->db->where("id",$row_id);
                $this->db->update('categories',$dbData);
            }

            $this->session->set_flashdata('msg','Category updated successfully!');
            redirect('admin/categories');

        }
    }

    /**
     * loads the customizable view, then handles the submitted data
     * 
     * @param integer $id id of row in database
     * 
     * @since 1.0
     * @author DeDevelopers
     * @copyright Copyright (c) 2019, DeDevelopers, https://dedevelopers.com
     */
    public function customize($id){
        //redirect_region();
        $result = $this->category->get_category_by_id($id);
        $this->data["the_id"] = $id;
        $this->data["dont_you_dare_validate"] = 55;

        if(!$result){

            $this->session->set_flashdata('err','Invalid request.');
            redirect('admin/404_page');
        }

        $this->data['data'] = $result;
        $this->form_validation->set_rules("logo_price",'Logo Price','trim|required');
        $this->form_validation->set_rules("delivery",'Delivery','trim|required');
        $this->form_validation->set_rules("delivery_type",'Delivery Type','trim|required');

        $this->form_validation->set_message('required','This field is required.');
        $this->form_validation->set_message('alpha_numeric_spaces','Only alphabet and numbers are allowed.');
        
        if($this->form_validation->run() === false){
            $this->data['title'] = 'Customize Category';
            
            $this->data['content'] = $this->load->view('backend/categories/customize',$this->data,true);
            $this->data['jsfile'] = 'js/customize_category';
            $this->load->view('backend/common/template',$this->data);
        }else{

            // $dbData["c_title"] = $this->input->post("c_title");
            // $dbData["c_title_ar"] = $this->input->post("c_title_ar");
            $dbData["logo_price"] = $this->input->post("logo_price");

            $dbData["shipping"] = $this->input->post("shipping");

            $dbData["vat"] = $this->input->post("vat");


            $dbData["min_qty"] = $this->input->post("min_qty")?$this->input->post("min_qty"):100;
            $dbData["pc_price"] = $this->input->post("pc_price")?$this->input->post("pc_price"):100;
            $dbData["stamps_price"] = $this->input->post("stamps_price")?$this->input->post("stamps_price"):100;

            $dbData["faces"] = $this->input->post("faces")==1?1:0;
            $dbData["faces_from"] = $this->input->post("faces_from")?$this->input->post("faces_from"):0;
            $dbData["faces_to"] = $this->input->post("faces_to")?$this->input->post("faces_to"):0;
            $dbData["faces_price"] = $this->input->post("faces_price")?$this->input->post("faces_price"):0;

            $dbData["colors"] = $this->input->post("colors")==1?1:0;
            $dbData["colors_from"] = $this->input->post("colors_from")?$this->input->post("colors_from"):0;
            $dbData["colors_to"] = $this->input->post("colors_to")?$this->input->post("colors_to"):0;
            $dbData["colors_price"] = $this->input->post("colors_price")?$this->input->post("colors_price"):0;

            $dbData["base"] = $this->input->post("base")==1?1:0;
            $dbData["base_price"] = $this->input->post("base_price")?$this->input->post("base_price"):0;

            $dbData["sides"] = $this->input->post("sides")==1?1:0;
            $dbData["sides_price"] = $this->input->post("sides_price")?$this->input->post("sides_price"):0;


            $dbData["width"] = $this->input->post("width")==1?1:0;
            $dbData["height"] = $this->input->post("height")==1?1:0;



            $dbData["delivery"] = $this->input->post("delivery");
            $dbData["delivery_type"] = $this->input->post("delivery_type");


            // NOTES & TC's
            $dbData["note_text_en"] = $this->input->post("note_text_en");
            $dbData["note_text_ar"] = $this->input->post("note_text_ar");
            //$terms_en = preg_replace ("/[\n\r]+/", "", $this->input->post("terms_en"));
            $dbData["terms_en"] = nl2br($this->input->post("terms_en"));
            $dbData["terms_ar"] = nl2br($this->input->post("terms_ar"));

//            $this->db->where("category",$id)->delete("cat_options");
//
//            foreach($this->input->post("title") as $k=>$v)
//            {
//                $title = $this->input->post("title")[$k];
//                $values = $this->input->post("values")[$k];
//                $disabled = $this->input->post("disabled")[$k];
//                $price = $this->input->post("price")[$k];
//                $title_ar = $this->input->post("title_ar")[$k];
//                $values_ar = $this->input->post("values_ar")[$k];
//
//                $final_option = array(
//                    "title"=>$title,
//                    "values"=>$values,
//                    "disabled"=>$disabled,
//                    "price"=>$price,
//                    "title_ar"=>$title_ar,
//                    "values_ar"=>$values_ar,
//                    "category"=>$id,
//                    "created_at"=>date("Y-m-d H:i:s")
//                );
//                $this->db->insert("cat_options",$final_option);
//            }
                // new comment start

//             $this->db->where("category",$id)->delete("cat_options");
//             foreach($this->input->post("c_var_size_en") as $key=>$val)
//             {

//                 $title_en = $val;
//                 $title_ar = $this->input->post("c_var_size_ar")[$key];

//                 $price = $this->input->post("price")[$key];
//                 $disabled = $this->input->post("disabled")[$key]==1?1:0;

// //
//                 $options = array();
//                 foreach($this->input->post("c_var_option_en")[$key] as $o_key=>$o_val)
//                 {

//                     $options[] = array(
//                         "en"=>$o_val,
//                         "ar"=>$this->input->post("c_var_option_en")[$key][$o_key],
//                         "price"=>$this->input->post("price")[$key][$o_key]
//                     );
//                 }

//                 $this->db->insert("cat_options",array(
//                     'category'=>$id,
//                     "title"=>$title_en,
//                     "title_ar"=>$title_ar,
//                     "options"=>json_encode($options),
//                     "disabled"=>$disabled,
// //                    "price"=>$price,
//                     "created_at"=>date("Y-m-d H:i:s"),
//                     "values"=>"NONE"
//                 ));
//             }

            // new comment end

            // $files = $_FILES;
            // $input = "image_more";
            // $cpt = count($_FILES[$input]['name']);
            // // echo $cpt;exit;
            // $more_images = array();
            // for($i=0; $i<$cpt; $i++)
            // {
            //     $_FILES[$input]['name']= $files[$input]['name'][$i];
            //     $_FILES[$input]['type']= $files[$input]['type'][$i];
            //     $_FILES[$input]['tmp_name']= $files[$input]['tmp_name'][$i];
            //     $_FILES[$input]['error']= $files[$input]['error'][$i];
            //     $_FILES[$input]['size']= $files[$input]['size'][$i];

            //     $image = $this->image_upload($input,'./resources/uploads/categories/','jpg|jpeg|png|gif|JPG|JPEG|PNG|GIF');

            //     if($image['upload'] == true){

            //         $image = $image['data'];

            //         $more_images[] = $image['file_name'];
            //         $this->image_thumb($image['full_path'],'./resources/uploads/categories/actual_size/',200,200);

            //     }
            // }

            // foreach($more_images as $im)
            // {
            //     $this->db->insert('category_images',array(
            //         'category_id'=>$id,
            //         'image'=>$im
            //     ));
            // }



            $input = "image";

            if((isset($_FILES[$input]) && $_FILES[$input]['size'] > 0))
                $image = $this->image_upload($input,'./resources/uploads/categories/','jpg|jpeg|png|gif');
            if($image['upload'] == true || $_FILES[$input]['size']<1){
                $image = $image['data'];
                if((isset($_FILES[$input]) && $_FILES[$input]['size'] > 0)){
                    $dbData["cust_image"] = $image['file_name'];
                    $this->image_thumb($image['full_path'],'./resources/uploads/categories/actual_size/',200,200);
                }

                 //$this->db->where("category_id",$id)->delete("category_images");
                
            }else{

                // $this->session->set_flashdata('err','An Error occurred during uploading image, please try again');
                // redirect('admin/add-product');
            }
            $this->db->where("category_id",$id)->delete("category_images");
            $this->db->where("category",$id)->delete("v_variations");
            $this->db->where("category",$id)->delete("cat_options");
            $variations = $this->input->post("c_title");

            foreach($variations as $key=>$variation)
            {

                $c_title = $this->input->post("c_title")[$key];
                $c_title_ar = $this->input->post("c_title_ar")[$key];

                $c_descps_en = $this->input->post("c_descps_en")[$key];
                $c_descps_ar = $this->input->post("c_descps_ar")[$key];


                $meta_title_en = $this->input->post("meta_title_en")[$key];
                $meta_descps_en = $this->input->post("meta_descps_en")[$key];
                $meta_keys_en = $this->input->post("meta_keys_en")[$key];
                $meta_title_ar = $this->input->post("meta_title_ar")[$key];
                $meta_descps_ar = $this->input->post("meta_descps_ar")[$key];
                $meta_keys_ar = $this->input->post("meta_keys_ar")[$key];

                $table_arr = array(
                    "category"=>$id,
                    "c_title"=>$c_title,
                    "c_title_ar"=>$c_title_ar,
                    "c_descps_ar"=>$c_descps_ar,
                    "c_descps_en"=>$c_descps_en,

                    "meta_title_en"=>$meta_title_en,
                    "meta_descps_en"=>$meta_descps_en,
                    "meta_keys_en"=>$meta_keys_en,
                    "meta_title_ar"=>$meta_title_ar,
                    "meta_descps_ar"=>$meta_descps_ar,
                    "meta_keys_ar"=>$meta_keys_ar,
                );

                $input = "c_image_".$key;
                // print_r(expression)

                if((isset($_FILES[$input]) && $_FILES[$input]['size'] > 0)){
                    $image = $this->image_upload($input,'./resources/uploads/categories/','jpg|jpeg|png|gif');
                }
                if($image['upload'] == true){
                    $image = $image['data'];
                    $table_arr["cust_image"] = $image['file_name'];
                }
                else
                {
                    $table_arr["cust_image"] = $this->input->post("pre_selected_image")[$key];
                }

               

                $this->db->insert("v_variations",$table_arr);
                $v_variation_id = $this->db->insert_id();

                // ADD NEW IMAGES MORE
                $files = $_FILES;
                $input = "image_more_".$key;
               
                $cpt = count($_FILES[$input]['name']);
                // echo $cpt;exit;
                $more_images = array();
                for($i=0; $i<$cpt; $i++)
                {
                    $_FILES[$input]['name']= $files[$input]['name'][$i];
                    $_FILES[$input]['type']= $files[$input]['type'][$i];
                    $_FILES[$input]['tmp_name']= $files[$input]['tmp_name'][$i];
                    $_FILES[$input]['error']= $files[$input]['error'][$i];
                    $_FILES[$input]['size']= $files[$input]['size'][$i];

                    $image = $this->image_upload($input,'./resources/uploads/categories/','jpg|jpeg|png|gif|JPG|JPEG|PNG|GIF');

                    if($image['upload'] == true){

                        $image = $image['data'];

                        //$more_images[] = $image['file_name'];
                        $this->image_thumb($image['full_path'],'./resources/uploads/categories/actual_size/',200,200);

                        // foreach($more_images as $im)
                        // {
                            $this->db->insert('category_images',array(
                                'category_id'=>$id,
                                'image'=>$image['file_name'],
                                'v_id'=>$v_variation_id
                            ));
                        // }

                    }
                    else{
                        $input_def = "def_more_".$key;
                        $this->db->insert('category_images',array(
                            'category_id'=>$id,
                            'image'=>$this->input->post($input_def)[$i],
                            'v_id'=>$v_variation_id
                        ));
                     
                    }
                }

                
                /////////////////////////


                $the_unique_variation = $this->input->post("variation_id")[$key];

                
                
                foreach($this->input->post("c_var_size_en")[$the_unique_variation] as $ckey=>$cval)
                {

                    $title_en = $cval;
                    $title_ar = $this->input->post("c_var_size_ar")[$key][$ckey];


                    $price = $this->input->post("price")[$key][$ckey];
                    $disabled = $this->input->post("disabled")[$key][$ckey]==1?1:0;

    //
                    $options = array();
                    foreach($this->input->post("c_var_option_en")[$ckey] as $o_key=>$o_val)
                    {

                        $options[] = array(
                            "en"=>$o_val,
                            "ar"=>$this->input->post("c_var_option_ar")[$ckey][$o_key],
                            "price"=>$this->input->post("price")[$ckey][$o_key]
                        );
                    }

                    $this->db->insert("cat_options",array(
                        'category'=>$id,
                        'variation_id'=>$v_variation_id,
                        "title"=>$title_en,
                        "title_ar"=>$title_ar,
                        "options"=>json_encode($options),
                        "disabled"=>$disabled,
    //                    "price"=>$price,
                        "created_at"=>date("Y-m-d H:i:s"),
                        "values"=>"NONE"
                    ));
                }

                ////////////////////////


                $tables = $this->db->where("variation_id",$key)->get("tmp_table")->result_object();
                // echo $this->db->last_query();exit;
                // print_r($tables);exit;
                foreach($tables as $table_id=>$nothing)
                {
                    $qty = $nothing->qty;
                    $faces = $nothing->faces;
                    $table_arr = array(
                        "created_at"=>date("Y-m-d"),
                        "faces"=>$faces,
                        "unit_name"=>$nothing->unit_name,
                        "unit_name_ar"=>$nothing->unit_name_ar,
                        "variation_id"=>$v_variation_id,
                        "c_title"=>$c_title,
                        "c_title_ar"=>$c_title_ar,
                        "table_print_name_en"=>$nothing->table_print_name_en,
                        "table_print_name_ar"=>$nothing->table_print_name_ar,
                        "size_eng"=>$nothing->size_eng,
                        "size_ar"=>$nothing->size_ar,
                        "qty"=>$qty
                    );

                    $this->db->insert("v_table",$table_arr);
                    $v_table_id = $this->db->insert_id();


                    $rows = $this->db->where("table_id",$nothing->id)->get("tmp_rows")->result_object();


                    foreach($rows as $row_id=>$nothing_row)
                    {

                        $prints = $nothing_row->prints;
                        // print_r($prints);

                        // $prints = array_values($prints);
                        // print_r($prints);exit;

                        $row_arr = array(
                            "created_at"=>date("Y-m-d"),
                            "table_id"=>$v_table_id,
                            "whg"=>$nothing_row->whg,
                            "plain_price"=>$nothing_row->plain_price,
                            "prints"=>($prints)
                        );
                        $this->db->insert("v_rows",$row_arr);
                    }
                }
            }

            $this->db->where("id",$id);
            $this->db->update('categories',$dbData);

            // $this->db->query("DELETE FROM tmp_table");
            // $this->db->query("DELETE FROM tmp_rows");
            // $this->db->query("DELETE FROM tmp_variations");
            $this->session->set_flashdata('msg','Category customized successfully!');
            redirect($_SERVER['HTTP_REFERER']);
             //redirect('admin/categories');
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
        redirect_region();
        $result = $this->category->get_category_by_id($id);

        if(!$result){

            $this->session->set_flashdata('err','Invalid request.');
            redirect('admin/404_page');

        }
        $dbData['deleted_by'] = $this->session->userdata('admin_id');
        $dbData['is_deleted'] = 1;
        $this->db->where('id',$id);
        $this->db->update('categories', $dbData);
        $this->session->set_flashdata('msg', 'Category deleted successfully!');
        redirect('admin/categories');
    }
    public function display_order()
    {
        redirect_region();
        // $result = $this->category->get_category_display_order();

        // if(!$result){

        //     $this->session->set_flashdata('err','Invalid request.');
        //     redirect('admin/404_page');

        // }

        $this->data['data'] = $result;
        $this->form_validation->set_rules('json_order','Title','trim|required');
       
        if($this->form_validation->run() === false){
            $this->data['title'] = 'Edit Categories Display Order';
            $this->data['jsfile'] = "js/categories_display_order";
            $this->data['categories'] = $this->db->where('is_deleted',0)
            ->order_by('display_priority',"ASC")
            ->where('parent',0)
            ->get('categories');

            $this->data['content'] = $this->load->view('backend/categories/display_order',$this->data,true);
             
            $this->load->view('backend/common/template',$this->data);
        }else{


            $json_order = $this->input->post('json_order');
            $json_order = json_decode($json_order);
            $i = 1;
            foreach ($json_order as $json_order_key => $json_order_value) {
                $this->db->where('id',$json_order_value->id)
                ->update('categories',array(
                    'parent'=>0,
                    'display_priority'=>$i
                ));
                $i++;

                foreach($json_order_value->children as $child)
                {
                    $this->db->where('id',$child->id)->update('categories',array('parent'=>$json_order_value->id,'display_priority'=>$i));
                    $i++;
                }
            }
            
            // $this->db->where('id',$id);
            // $this->db->update('categories', $dbData);
            $this->session->set_flashdata('msg', 'Category updated successfully!');
            redirect($_SERVER['HTTP_REFERER']);

        }
    }
    public function view_option()
    {

        $this->data['title'] = $this->input->post("title");
        $this->data['title_ar'] = $this->input->post("title_ar");

        $this->data['values'] = $this->input->post("values");
        $this->data['values_ar'] = $this->input->post("values_ar");

        $this->data['price'] = $this->input->post("price");
        $this->data['disabled'] = $this->input->post("disabled")==1?1:0;

        echo $this->load->view('backend/categories/option',$this->data,true);
        // $this->load->view('backend/common/template',$this->data);

    }
    public function view_more_image()
    {

        echo $this->load->view('backend/categories/image_section',$this->data,true);
    }
    public function view_cvariation_section($item_id)
    {
        $this->data['item_id'] = $item_id;
        echo $this->load->view('backend/categories/cvariation_section',$this->data,true);
    }
    public function more_option($t)
    {
        echo more_optionv2($t);
    }

    public function show_price_tables_dynamic($colors,$faces){

            // if($faces < 2){
            //     $table = '<div class="headings">
            //                 <div style="padding: 0; margin: 0; width: 100%">
            //                 <div>
            //                     <div colspan="'.$colors.'">with '.($colors).' color print</div>
            //                 </div>';

            //     if($faces>1){
            //             $table .= '<div>';

            //         for($y=0;$y<=($faces-1);$y++){
            //            $table .= '<div  class="heading_2" style="width:50%;">'.($y+1).'face</div>';
            //         }
            //             $table .= '</div>';
            //     }
            //     $table .='</div></div>';
            // }else {
            //     for($t=0;$t<=($colors-1);$t++) 
            //         {
            //             $table .= '<div  class="headings">
            //                         <div style="padding: 0; margin: 0; width: 100%">
            //                         <div>
            //                             <div colspan="'.$colors.'">with '.($t+1).' color print</div>
            //                         </div>';

            //             if($faces>0){
            //                 $table .= '<div>';

            //             for($y=0;$y<=($faces-1);$y++){
            //                $table .= '<div  class="heading_2" style="width:50%;">'.($y+1).'face</div>';
            //             }
            //                 $table .= '</div>';
            //             }
            //             $table .='</div></div>';
                       
            //         }
            // }
          
        
            for($t=0;$t<=($colors-1);$t++) 
                    {
                        $table .= '<div  class="headings">
                                    <div style="padding: 0; margin: 0; width: 100%">
                                    <div>
                                        <div colspan="'.$colors.'">with '.($t+1).' color print</div>
                                    </div>';

                        if($faces>1){
                            $table .= '<div>';

                        for($y=0;$y<=($faces-1);$y++){
                           $table .= '<div  class="heading_2" style="width:50%;">'.($y+1).'face</div>';
                        }
                            $table .= '</div>';
                        }
                        $table .='</div></div>';
                       
                    }

          echo $table;
       
    }
    public function get_new_row()
    {
        echo newRow($this->input->post("first"),$this->input->post("time"));
    }
    public function get_new_rowv2()
    {
        $id = $this->input->post("id")?$this->input->post("id"):0;
        $table_id = $this->input->post("table_id")?$this->input->post("table_id"):0;
        echo newRow($id,$table_id);
    }
    
    public function get_new_table()
    {
        $prints = $this->input->post("prints");
        $faces = $this->input->post("faces");
        echo newTableV2(false,$prints,$faces);
    }
    public function get_new_table_v2()
    {
        echo newTableV2(0,false,$this->input->post("data"));
    }
    public function get_new_variation_v2()
    {
        echo newVariationV2(0);
    }

    public function add_new_print()
    {
        // $row_id = $this->input->post("row_id")?$this->input->post("row_id"):0;
        $table_id = $this->input->post("table_id")?$this->input->post("table_id"):0;

        $table = $this->db->where("id",$table_id)->get("tmp_table")->result_object()[0];
        $rows = $this->db->where("table_id",$table_id)->get("tmp_rows")->result_object();

        foreach($rows as $row){

            $prints = json_decode($row->prints);

            for($qq = 0; $qq<=$table->faces-1; $qq++)
            {
                $dummy_print[] = "1.00";
            }

            $prints[] = $dummy_print;
            $this->db->where("id",$row->id);
            $this->db->update("tmp_rows",array("prints"=>json_encode($prints)));
        }
        echo newTableV2($table_id,true);
    }
    public function remove_print()
    {
        // $row_id = $this->input->post("row_id")?$this->input->post("row_id"):0;
        $table_id = $this->input->post("table_id")?$this->input->post("table_id"):0;

        $table = $this->db->where("id",$table_id)->get("tmp_table")->result_object()[0];
        $rows = $this->db->where("table_id",$table_id)->get("tmp_rows")->result_object();
        $key = $this->input->post("key");
        foreach($rows as $row){

            $prints = (array) json_decode($row->prints);
            unset($prints[$key]);
            $prints = array_values($prints);
            $this->db->where("id",$row->id);
            $this->db->update("tmp_rows",array("prints"=>json_encode($prints)));
        }
        echo newTableV2($table_id,true);
    }

    public function remove_row()
    {
        $table_id = $this->input->post("table_id")?$this->input->post("table_id"):0;
        $row_id = $this->input->post("row_id")?$this->input->post("row_id"):0;
        $this->db->where("id",$row_id)->delete("tmp_rows");
        
        echo newTableV2($table_id,true);
    }

    public function tablePrintNameChangedEn(){

        $table_id = $this->input->post("table_id")?$this->input->post("table_id"):0;
        $table = $this->db->where("id",$table_id)->get("tmp_table")->result_object()[0];

        $value = $this->input->post("value");

        $this->db->where("id",$table_id)->update("tmp_table",array("table_print_name_en"=>$value));
        echo "successfully";
    }
    public function tablePrintNameChangedAr(){

        $table_id = $this->input->post("table_id")?$this->input->post("table_id"):0;
        $table = $this->db->where("id",$table_id)->get("tmp_table")->result_object()[0];

        $value = $this->input->post("value");

        $this->db->where("id",$table_id)->update("tmp_table",array("table_print_name_ar"=>$value));
        echo "successfully";
    }
    public function SizeNameChangedEn(){

        $table_id = $this->input->post("table_id")?$this->input->post("table_id"):0;
        $table = $this->db->where("id",$table_id)->get("tmp_table")->result_object()[0];

        $value = $this->input->post("value");

        $this->db->where("id",$table_id)->update("tmp_table",array("size_eng"=>$value));
        echo "successfully";
    }
    public function SizeNameChangedAr(){

        $table_id = $this->input->post("table_id")?$this->input->post("table_id"):0;
        $table = $this->db->where("id",$table_id)->get("tmp_table")->result_object()[0];

        $value = $this->input->post("value");

        $this->db->where("id",$table_id)->update("tmp_table",array("size_ar"=>$value));
        echo "successfully";
    }
    public function face_changed()
    {
        // $row_id = $this->input->post("row_id")?$this->input->post("row_id"):0;
        $table_id = $this->input->post("table_id")?$this->input->post("table_id"):0;

        $table = $this->db->where("id",$table_id)->get("tmp_table")->result_object()[0];
        $rows = $this->db->where("table_id",$table_id)->get("tmp_rows")->result_object();
        $value = $this->input->post("value");

        $this->db->where("id",$table_id)->update("tmp_table",array("faces"=>$value));
        foreach($rows as $row){

            $prints = (array) json_decode($row->prints);
            foreach($prints as $print_key=>$print)
            {
                $brand_print = array();
                for($iq=0; $iq<=$value-1;$iq++)
                {
                    $brand_print[] = $print[$iq]?$print[$iq]:"0";
                }
                $prints[$print_key]=$brand_print;
            }
            $prints = array_values($prints);
            $this->db->where("id",$row->id);
            $this->db->update("tmp_rows",array("prints"=>json_encode($prints)));
        }
        echo newTableV2($table_id,true);
    }
    public function unit_changed()
    {
        $table_id = $this->input->post("table_id")?$this->input->post("table_id"):0;
        $table = $this->db->where("id",$table_id)->get("tmp_table")->result_object()[0];
        $rows = $this->db->where("table_id",$table_id)->get("tmp_rows")->result_object();
        $value = $this->input->post("value");
        $this->db->where("id",$table_id)->update("tmp_table",array("unit_name"=>$value));
        echo "successfully";
    }
    public function unit_changed_ar()
    {
        $table_id = $this->input->post("table_id")?$this->input->post("table_id"):0;
        $table = $this->db->where("id",$table_id)->get("tmp_table")->result_object()[0];
        $rows = $this->db->where("table_id",$table_id)->get("tmp_rows")->result_object();
        $value = $this->input->post("value");
        $this->db->where("id",$table_id)->update("tmp_table",array("unit_name_ar"=>$value));
        echo "successfully";
    }
    
    public function whg_changed()
    {
        $value = $this->input->post("value");
        $row_id = $this->input->post("row_id");
        $this->db->where("id",$row_id)->update("tmp_rows",array("whg"=>$value)); 
    }
    public function qty_changed()
    {
        $value = $this->input->post("value");
        $table_id = $this->input->post("table_id");
        $this->db->where("id",$table_id)->update("tmp_table",array("qty"=>$value)); 
        echo "successfully";
    }
    public function plain_price_changed()
    {
        $value = $this->input->post("value");
        $row_id = $this->input->post("row_id");
        $this->db->where("id",$row_id)->update("tmp_rows",array("plain_price"=>$value)); 
        echo "successfully";
    }
    public function column_changed()
    {
        $value = $this->input->post("value");
        $row_id = $this->input->post("row_id");
        $print_key = $this->input->post("print_key");
        $face_key = $this->input->post("face_key");


        $row = $this->db->where("id",$row_id)->get("tmp_rows")->result_object()[0];

        $table = $this->db->where("id",$row->table_id)->get("tmp_table")->result_object()[0];

        $prints = (array) json_decode($row->prints);
        

        $prints[$print_key][$face_key] = number_format($value,2);
        
        $this->db->where("id",$row->id);
        $this->db->update("tmp_rows",array("prints"=>json_encode($prints)));

        $this->db->where("id",$row_id)->update("tmp_rows",array("plain_price"=>$value)); 
        echo "successfully";
    }

    public function set_filter(){
        $_SESSION['filter_cat'] = $_POST['filter'];
        $this->redirect_back();
    }
    public function reset_filter(){
        unset($_SESSION['filter_cat']);
        $this->redirect_back();
    }
    public function remove_custom_variration(){
       $post = $_REQUEST['id'];
       $this->db->where("id",$post)->delete("cat_options");
    }
    public function remove_parent_name_variration(){
        $post = $_REQUEST['id'];
        $table = $this->db->query("SELECT * FROM v_table WHERE id = ".$post)->result_object();
        foreach ($table as $key => $value) {
            $this->db->where("table_id",$value->id)->delete("v_rows");
        }
        $this->db->where("variation_id",$post)->delete("v_table");
        $this->db->where("id",$post)->delete("v_variations");
    }

    public function remove_custom_table_variration(){
        $post = $_REQUEST['id'];
        $table = $this->db->query("SELECT * FROM v_table WHERE id = ".$post)->result_object();
        
        // $varition = $this->db->query("SELECT * FROM v_table WHERE id = ".$table->variation_id)->result_object()[0];

        //$this->db->where("table_id",$value->id)->delete("v_rows");

        foreach ($table as $key => $value) {
            $this->db->where("table_id",$value->id)->delete("v_rows");
        }
        $this->db->where("id",$post)->delete("v_table");
    }
    public function remove_custom_table_variration_update(){
        $post = $_REQUEST['id'];
        $vid = $_REQUEST['vid'];
        $table = $this->db->query("SELECT * FROM v_table WHERE id = ".$post)->result_object();
        foreach ($table as $key => $value) {
            $this->db->where("table_id",$value->id)->delete("v_rows");
        }
        $this->db->where("id",$post)->delete("v_table");
        $this->db->where("id",$vid)->delete("tmp_table");
    }
    public function remove_more_image(){
        $post = $_REQUEST['id'];
        $table = $this->db->query("SELECT * FROM category_images WHERE id = ".$post)->result_object();
        $this->db->where("id",$post)->delete("category_images");
    }

}
