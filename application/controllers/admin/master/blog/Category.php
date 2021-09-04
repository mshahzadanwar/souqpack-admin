<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
     * handles the Brands
     * 
     * @since 1.0
     * @author Inlancer
     * @copyright Copyright (c) 2021, Inlancer, https://inlancer.in
*/

class Category extends Template {
    /**
        * constructs Category as parent object
        * loads the neccassary class
        * checks if current user has the rights to access this class
        * 
        * @since 1.1
        * @author Inlancer
        * @copyright Copyright (c) 2021, inlancer, https://inlancer.in
    */
    
    public function __construct()
        {
            parent::__construct();
            $this->db->cache_delete_all();
            header('Last-Modified: '.gmdate("D, d M Y H:i:s") . ' GMT');
            header("Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0");
            header("Pragma: no-cache");
            header("Expires: Thu, 19 Nov 1981 08:52:00 GMT");
         
            $this->load->model('master/blog/category_model');  
            $this->load->model('common_model');
             
            $this->heading('Souqpack Admin'); 

            $this->data['active'] = 'blog';

            /*Template settings*/
                $this->set_template("master/template/template");
                $this->set_header_path('master/template/header');
                $this->set_footer_path('master/template/footer'); 
                
                $this->set_title("Souqpack Admin");
                $this->session->set_userdata("ADMIN_CURRENT_PAGE","Souqpack Admin");  
        } 

 

    public function index()
        {  
            $this->assets_load->add_css(array(base_url('wp-includes/vendors/dataTable/datatables.min.css')),"header");             
            $this->assets_load->add_js(array(base_url('wp-includes/vendors/dataTable/datatables.min.js')),"footer");  
            $this->assets_load->add_css(array(base_url('wp-includes/vendors/select2/css/select2.min.css')),"header");
            $this->assets_load->add_js(array(base_url('wp-includes/vendors/select2/js/select2.min.js')),"header");   
            $this->assets_load->add_js(array(base_url('resources/backend/js/jquery.form.js')),"header"); 

         
            $this->set_title("Category Master");
            $this->session->set_userdata("ADMIN_CURRENT_PAGE","Category Master");
            $data = array();
            $data['active']="category";
            $data['sub']="category";

 

            /*Here we will use grid's data for making it dynamic*/ 
            $grid_columns=array(
                array(
                    'column_name'=>'No',
                    'column_style'=>'style=""',
                    'column_width'=>'width="10%"',
                    'column_class'=>'', 
                    'column_sortable'=>'false', 
                    ),
                array(
                    'column_name'=>'Category',
                    'column_style'=>'style=""',
                    'column_width'=>'width="35%"',
                    'column_class'=>'', 
                    'column_sortable'=>'true',
                    ),
                array(
                    'column_name'=>'Language',
                    'column_style'=>'style=""',
                    'column_width'=>'width="30%"',
                    'column_class'=>'', 
                    'column_sortable'=>'false',
                    ), 
                array(
                    'column_name'=>'Action',
                    'column_style'=>'style=""',
                    'column_width'=>'width="25%"',
                    'column_class'=>' ', 
                    'column_sortable'=>'false',
                    ),

                );

            $table_style='border-collapse: collapse; border-spacing: 0; width: -webkit-fill-available;';
            $table_class='table table-striped nowrap table-bordered dt-responsive nowrap'; 
            $grid_data=array(
                'grid_name'=>'Category ',
                'grid_dt_url'=>base_url('category-list'),
                'grid_delete_url'=>base_url('category-delete/'),
                'grid_data_url'=>base_url('category-form/'),
                'grid_total_columns'=>'4',  
                'grid_columns'=>$grid_columns,
                'grid_order_by'=>'2',
                'grid_order_by_type'=>'ASC',
                'grid_tbl_name'=>'category', 
                'grid_tbl_display_name'=>'Category', 
                'grid_tbl_length'=>'10', 
                'grid_tbl_style'=>$table_style,
                'grid_tbl_class'=>$table_class,

                );

            $data['grid']=$grid_data; 

            $data ["page_title"]        = "Category ";

            $extra_pages=array( 
                'master/blog/category/add_modal'
            );
            $data ["extra_pages"]=$extra_pages; 
            // echo "<pre>";print_r($data);exit;
            $this->view('master/dt_master',$data); 
            
        }

    public function dt_list( $id = -1 )
        { 
            $start_index    = $this->input->get('iDisplayStart')!=null?xss_clean(trim($this->input->get('iDisplayStart'))):0;
            $end_index      = $this->input->get('iDisplayLength')?xss_clean(trim($this->input->get('iDisplayLength'))):10;      
            $search_text    = $this->input->get('sSearch')?xss_clean(trim($this->input->get('sSearch'))):''; 
            $sOrder             = "";
            $aColumns       = array("t.category_id","t.category_name","l.title");
            $aColumns_where = array("t.category_id","t.category_name","l.title");
        
            if (  $this->input->get('iSortCol_0') !== FALSE )
            {       
                for ( $i=0 ; $i<intval( $this->input->get('iSortingCols') ) ; $i++ )
                {
                    if ( $this->input->get( 'bSortable_'.intval($this->input->get('iSortCol_'.$i)) ) == "true" )
                    {
                        $sOrder .= $aColumns[ intval( ( $_GET['iSortCol_'.$i] ) ) ]."
                            ".$this->mres( $_GET['sSortDir_'.$i] ) .", ";
                    }
                }
                
                $sOrder = substr_replace( $sOrder, "", -2 );
            } 
            $sWhere     = " ";
            for ( $i=0 ; $i<count($aColumns_where) ; $i++ )
            {
                if ( isset($_GET['bSearchable_'.$i])  && $_GET['bSearchable_'.$i] == "true" && $_GET['sSearch_'.$i] != '' )
                {
                    if( $sWhere != '' )
                    {
                        $sWhere .= " AND ";
                    }
                    $sWhere .= $aColumns_where[$i]." = '".$this->mres($_GET['sSearch_'.$i])."' ";
                }
            }
            
            if( isset($_GET['sSearch'])  )
            {                   
                $sWhere .= ' AND ( ';   
                $or     = '';
                foreach( $aColumns_where as $row )
                {                   
                    $sWhere .= $or.$row." LIKE '%".str_replace("'","\\\\\''",$this->mres($_GET['sSearch']))."%'";
                    if( $or == ''  )
                    {
                        $or         = ' OR ';
                    }
                    
                }   
                $sWhere .= ')';
            }
                
                
            $this->category_model->set_fields(array('limit_start'=>$start_index,'limit_length'=>$end_index,'search_text'=>$sWhere,"order_by"=>$sOrder));        
    
            $category_info  = $this->category_model->get_all_category();
            
            $category_results       = $category_info['result'];
            $data                   = array();
            $row_dt             = array();
            $i=0;
            foreach( $category_results as $row )
            {
                ++$i;   
                $row_dt   = array();
                $row_dt[] = $i;
                $row_dt[] = $row->category_name;  
                $row_dt[] = $row->language;  
                 
                $statusEdit='<a href="javascript:;" title="Edit Category"  onclick="edit('.$row->category_id.')"  style="overflow: hidden; position: relative;" class="btn btn-outline-primary waves-effect waves-light"><i class="fa fa-edit"></i></a>';

                $row_dt[] =$statusEdit.' &nbsp;<a href="javascript:;" onclick="myDelete('.$row->category_id.')"  title="Delete Category" style="overflow: hidden; position: relative;" class="btn btn-outline-danger waves-effect waves-light"><i class="fa fa-trash fa-x"></i></a>';
                $data[] = $row_dt;
                    
            }
        
            $response['iTotalRecords']          = $category_info['total'];
            $response['iTotalDisplayRecords']   = $category_info['total'];
            $response['aaData']                     = $data;
            
            echo json_encode($response);
        } 
 
    public function loadForm($id)
        {
            $this->category_model->set_fields(array('id'=>$id)); 
            $data = $this->category_model->get_category_detail();  
            // echo "<pre>";print_r($data);exit;
            return $this->load->view('master/blog/category/edit_modal',$data); 
        } 
 
    public function save()
        {
            $id1=xss_clean(trim($this->input->post("id1")));
            $id2=xss_clean(trim($this->input->post("id2")));
            $mode=xss_clean(trim($this->input->post("mode")));
            $fields=array( 
                'category_name_1',
                'category_name_2',
                'category_slug',
            );
            $data=array();
            $error="";
            
            foreach ($fields as $field) 
            {
                $data[$field]=$this->input->post($field);
            } 

            $config=array(
                array(
                    'field'=>'category_name_2',
                    'label'=>'English Category',
                    'rules'=>'trim|required'
                ),array(
                    'field'=>'category_name_1',
                    'label'=>'Arabic Category',
                    'rules'=>'trim|required'
                ),
                array(
                    'field'=>'category_slug',
                    'label'=>'Slug',
                    'rules'=>'trim|required'
                ) 
            );      
            $this->form_validation->set_rules($config);
            if ($this->form_validation->run()==FALSE) 
            {    
                $array=array();
                $array['status']=500; 
                $array['message']= validation_errors();
                echo json_encode($array);
                exit; 
            }
            else 
            {   
                // echo "<pre>";print_r($this->input->post());exit;

                $categoryAvailable = $this->category_model->category_available($data['category_name_1'],$id1); 
                if ($categoryAvailable) { 
                    $array=array();
                    $array['status']    =   500; 
                    $array['message']   =   'This category is not available, Please fill another one';
                    echo json_encode($array);
                    exit; 
                }


                $slugAvailable = $this->category_model->slug_available($data['category_slug'],$id2); 
                if ($slugAvailable) { 
                    $array=array();
                    $array['status']    =   500; 
                    $array['message']   =   'This category slug  is not available, Please fill another one';
                    echo json_encode($array);
                    exit; 
                }

                $categoryAvailable = $this->category_model->category_available($data['category_name_2'],$id2); 
                if ($categoryAvailable) { 
                    $array=array();
                    $array['status']    =   500; 
                    $array['message']   =   'This category is not available, Please fill another one';
                    echo json_encode($array);
                    exit; 
                }
 

                if($mode == 'add')
                {  
                    $data2 = [
                        'category_name'         =>$data['category_name_2'],
                        'category_slug'         =>$data['category_slug'],
                        'category_language_id'  =>2
                    ];   
                    $this->common_model->set_fields($data2);
                    $category_id = $this->common_model->save('blog_categories');
                    if ($category_id) 
                    {
                        $data1 = [
                            'category_name'                 =>$data['category_name_1'],
                            'category_language_id'          =>1,
                            'category_language_master_id'   =>$category_id
                        ]; 
                        $this->common_model->set_fields($data1);
                        $this->common_model->save('blog_categories');

                        $array=array();
                        $array['status']=200;
                        $array['message']="Category Added Successfully....";
                        echo json_encode($array);
                        exit;
                    }
                    else
                    {
                        $array=array();
                        $array['status']=500;
                        $array['message']="Category Adding Failed....";
                        echo json_encode($array);
                        exit;
                    }
                }
                else
                { 
                    $data2 = [
                        'category_name'             =>$data['category_name_2'], 
                        'category_slug'             =>$data['category_slug']
                    ]; 
                    $data1 = [
                        'category_name'                 =>$data['category_name_1'], 
                    ]; 

                    $where1 = array('category_id'=>$id1);
                    $this->common_model->set_fields($data1);                 
                    $this->common_model->updateData('blog_categories',$where1);

                    $where2 = array('category_id'=>$id2);
                    $this->common_model->set_fields($data2);                 
                    $this->common_model->updateData('blog_categories',$where2);


                    $array=array();
                    $array['status']=200;
                    $array['message']="Category Updated Successfully...."; 
                    echo json_encode($array);
                    exit;
                }
            }
        } 
 
    public function delete_category($id)
        {
            $this->common_model->set_fields(array('is_delete'=>1));
            $this->common_model->updateData('blog_categories',array('category_id'=>$id));
            $this->common_model->updateData('blog_categories',array('category_language_master_id'=>$id));
            
            if( $this->input->is_ajax_request() )
            {
                $arr                    = array();
                $arr['status']          = 200;          
                $arr['message']         = "Category Deleted Successfully"; 
                echo json_encode($arr);
                return;
            }
            else
            {
                $this->session->set_flashdata('success',"Category Deleted Successfully.");
                redirect($_SERVER["HTTP_REFERER"]);
            }           
        }

     


}
