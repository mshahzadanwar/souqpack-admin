<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
     * handles the Brands
     * 
     * @since 1.0
     * @author Inlancer
     * @copyright Copyright (c) 2021, Inlancer, https://inlancer.in
*/

class Tag extends Template {
    /**
        * constructs Tag as parent object
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
         
            $this->load->model('master/blog/tag_model');  
            $this->load->model('common_model');
             
            $this->heading('Souqpack Admin'); 

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

         
            $this->set_title("Tag Master");
            $this->session->set_userdata("ADMIN_CURRENT_PAGE","Tag Master");
            $data = array();
            $data['active']="tag";
            $data['sub']="tag";

 

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
                    'column_name'=>'Tag',
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
                'grid_name'=>'Tag ',
                'grid_dt_url'=>base_url('tag-list'),
                'grid_delete_url'=>base_url('tag-delete/'),
                'grid_data_url'=>base_url('tag-form/'),
                'grid_total_columns'=>'4',  
                'grid_columns'=>$grid_columns,
                'grid_order_by'=>'2',
                'grid_order_by_type'=>'ASC',
                'grid_tbl_name'=>'tag', 
                'grid_tbl_display_name'=>'Tag', 
                'grid_tbl_length'=>'10', 
                'grid_tbl_style'=>$table_style,
                'grid_tbl_class'=>$table_class,

                );

            $data['grid']=$grid_data; 

            $data ["page_title"]        = "Tag ";

            $extra_pages=array( 
                'master/blog/tag/add_modal'
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
            $aColumns       = array("t.tag_id","t.tag_title","l.title");
            $aColumns_where = array("t.tag_id","t.tag_title","l.title");
        
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
                
                
            $this->tag_model->set_fields(array('limit_start'=>$start_index,'limit_length'=>$end_index,'search_text'=>$sWhere,"order_by"=>$sOrder));        
    
            $tag_info  = $this->tag_model->get_all_tag();
            
            $tag_results       = $tag_info['result'];
            $data                   = array();
            $row_dt             = array();
            $i=0;
            foreach( $tag_results as $row )
            {
                ++$i;   
                $row_dt   = array();
                $row_dt[] = $i;
                $row_dt[] = $row->tag_title;  
                $row_dt[] = $row->language;  
                 
                $statusEdit='<a href="javascript:;" title="Edit Tag"  onclick="edit('.$row->tag_id.')"  style="overflow: hidden; position: relative;" class="btn btn-outline-primary waves-effect waves-light"><i class="fa fa-edit"></i></a>';

                $row_dt[] =$statusEdit.' &nbsp;<a href="#" onclick="myDelete('.$row->tag_id.')"  title="Delete Tag" style="overflow: hidden; position: relative;" class="btn btn-outline-danger waves-effect waves-light"><i class="fa fa-trash fa-x"></i></a>';
                $data[] = $row_dt;
                    
            }
        
            $response['iTotalRecords']          = $tag_info['total'];
            $response['iTotalDisplayRecords']   = $tag_info['total'];
            $response['aaData']                     = $data;
            
            echo json_encode($response);
        } 
 
    public function loadForm($id)
        {
            $this->tag_model->set_fields(array('id'=>$id)); 
            $data = $this->tag_model->get_tag_detail();  
            // echo "<pre>";print_r($data);exit;
            return $this->load->view('master/blog/tag/edit_modal',$data); 
        } 
 
    public function save()
        {
            $id1=xss_clean(trim($this->input->post("id1")));
            $id2=xss_clean(trim($this->input->post("id2")));
            $mode=xss_clean(trim($this->input->post("mode")));
            $fields=array(
                "tag_title_2",
                "tag_language_id_2",
                "tag_title_1",
                "tag_language_id_1",
            );
            $data=array();
            $error="";
            
            foreach ($fields as $field) 
            {
                $data[$field]=$this->input->post($field);
            } 

            $config=array(
                array(
                    'field'=>'tag_title_2',
                    'label'=>'English Tag',
                    'rules'=>'trim|required'
                ),
                array(
                    'field'=>'tag_language_id_2',
                    'label'=>'English',
                    'rules'=>'trim|required'
                ),array(
                    'field'=>'tag_title_1',
                    'label'=>'Arabic Tag',
                    'rules'=>'trim|required'
                ),
                array(
                    'field'=>'tag_language_id_1',
                    'label'=>'Arabic',
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

                $tagAvailable = $this->tag_model->tag_available($data['tag_title_1'],$id1); 
                if ($tagAvailable) { 
                    $array=array();
                    $array['status']    =   500; 
                    $array['message']   =   'This tag is available, Please fill another one';
                    echo json_encode($array);
                    exit; 
                }

                $tagAvailable = $this->tag_model->tag_available($data['tag_title_2'],$id2); 
                if ($tagAvailable) { 
                    $array=array();
                    $array['status']    =   500; 
                    $array['message']   =   'This tag is available, Please fill another one';
                    echo json_encode($array);
                    exit; 
                }
 

                if($mode == 'add')
                {  
                    $data2 = [
                        'tag_title'         =>$data['tag_title_2'],
                        'tag_language_id'   =>$data['tag_language_id_2']
                    ];   
                    $this->common_model->set_fields($data2);
                    $tag_id = $this->common_model->save('blog_tags');
                    if ($tag_id) 
                    {
                        $data1 = [
                            'tag_title'                 =>$data['tag_title_1'],
                            'tag_language_id'           =>$data['tag_language_id_1'],
                            'tag_language_master_id'    =>$tag_id
                        ]; 
                        $this->common_model->set_fields($data1);
                        $this->common_model->save('blog_tags');

                        $array=array();
                        $array['status']=200;
                        $array['message']="Tag Added Successfully....";
                        echo json_encode($array);
                        exit;
                    }
                    else
                    {
                        $array=array();
                        $array['status']=500;
                        $array['message']="Tag Adding Failed....";
                        echo json_encode($array);
                        exit;
                    }
                }
                else
                { 
                    $data2 = [
                        'tag_title'         =>$data['tag_title_2'],
                        'tag_language_id'   =>$data['tag_language_id_2']
                    ]; 
                    $data1 = [
                        'tag_title'                 =>$data['tag_title_1'],
                        'tag_language_id'           =>$data['tag_language_id_1'], 
                    ]; 

                    $where1 = array('tag_id'=>$id1);
                    $this->common_model->set_fields($data1);                 
                    $this->common_model->updateData('blog_tags',$where1);
                    $where2 = array('tag_id'=>$id2);
                    $this->common_model->set_fields($data2);                 
                    $this->common_model->updateData('blog_tags',$where2);
                    $array=array();
                    $array['status']=200;
                    $array['message']="Tag Updated Successfully...."; 
                    echo json_encode($array);
                    exit;
                }
            }
        } 
 
    public function delete_tag($id)
        {
            $this->common_model->set_fields(array('is_delete'=>1));
            $this->common_model->updateData('blog_tags',array('tag_id'=>$id));
            $this->common_model->updateData('blog_tags',array('tag_language_master_id'=>$id));
            
            if( $this->input->is_ajax_request() )
            {
                $arr                    = array();
                $arr['status']          = 200;          
                $arr['message']         = "Tag Deleted Successfully"; 
                echo json_encode($arr);
                return;
            }
            else
            {
                $this->session->set_flashdata('success',"Tag Deleted Successfully.");
                redirect($_SERVER["HTTP_REFERER"]);
            }           
        }

     


}
