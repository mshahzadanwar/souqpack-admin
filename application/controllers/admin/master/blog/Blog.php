<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
     * handles the Brands
     * 
     * @since 1.0
     * @author Inlancer
     * @copyright Copyright (c) 2021, Inlancer, https://inlancer.in
*/

class Blog extends Template {
    /**
        * constructs Blog as parent object
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
            $this->load->model('master/blog/category_model');
            $this->load->model('master/blog/blog_model');  
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

         
            $this->set_title("Blog Master");
            $this->session->set_userdata("ADMIN_CURRENT_PAGE","Blog Master");
            $data = array();
            $data['active']="blog";
            $data['sub']="blog";

 

            /*Here we will use grid's data for making it dynamic*/ 
            $grid_columns=array(
                array(
                    'column_name'=>'No',
                    'column_style'=>'style=""',
                    'column_width'=>'width="5%"',
                    'column_class'=>'', 
                    'column_sortable'=>'false', 
                    ),
                array(
                    'column_name'=>'Title',
                    'column_style'=>'style=""',
                    'column_width'=>'width="25%"',
                    'column_class'=>'', 
                    'column_sortable'=>'true',
                    ),
                array(
                    'column_name'=>'Slug',
                    'column_style'=>'style=""',
                    'column_width'=>'width="20%"',
                    'column_class'=>'', 
                    'column_sortable'=>'false',
                    ),
                array(
                    'column_name'=>'Date',
                    'column_style'=>'style=""',
                    'column_width'=>'width="15%"',
                    'column_class'=>'', 
                    'column_sortable'=>'false',
                    ), 
                array(
                    'column_name'=>'Status',
                    'column_style'=>'style=""',
                    'column_width'=>'width="10%"',
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
                'grid_name'         =>'Blog ',
                'grid_add_url'      =>base_url('add-blog'),
                'grid_dt_url'       =>base_url('blog-list'),
                'grid_delete_url'   =>base_url('blog-delete/'),
                'grid_data_url'     =>base_url('blog-form/'),
                'grid_total_columns'=>'4',  
                'grid_columns'      =>$grid_columns,
                'grid_order_by'     =>'2',
                'grid_order_by_type'=>'ASC',
                'grid_tbl_name'     =>'blog', 
                'grid_tbl_display_name'=>'Blog', 
                'grid_tbl_length'   =>'10', 
                'grid_tbl_style'    =>$table_style,
                'grid_tbl_class'    =>$table_class,

            );

            $data['grid']=$grid_data; 

            $data ["page_title"]        = "Blog ";

            $extra_pages=array( 
                'master/blog/blog/scripts'
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
            $aColumns       = array("b.blog_id","b.blog_title","b.blog_slug","b.blog_date");
            $aColumns_where = array("b.blog_id","b.blog_title","b.blog_slug","b.blog_date");
        
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
                
                
            $this->blog_model->set_fields(array('limit_start'=>$start_index,'limit_length'=>$end_index,'search_text'=>$sWhere,"order_by"=>$sOrder));        
    
            $blog_info  = $this->blog_model->get_all_blog();
            
            $blog_results       = $blog_info['result'];
            $data                   = array();
            $row_dt             = array();
            $i=0;
            foreach( $blog_results as $row )
            {
                ++$i;   
                $row_dt   = array();
                $row_dt[] = $i;
                $row_dt[] = $row->blog_title;  
                $row_dt[] = $row->blog_slug;  
                $row_dt[] = $row->created_at;  
                
                if ($row->blog_status==0) { 
                    $status= '<button onclick="updateStatus('.$row->blog_id.',1)" type="button" class="btn btn-warning" style="color:black">Publish</button>';
                    $statusText ='Pending' ;
                }
                if ($row->blog_status==1) { 
                    $status= '<button onclick="updateStatus('.$row->blog_id.',0)"  type="button" class="btn btn-success" style="color:#fff">Pending</button>';
                     $statusText ='Public' ;
                }




                $row_dt[] = $statusText;  
                 
                $statusEdit='<a href="'.base_url("edit-blog/").$row->blog_id.'" title="Edit Blog"    style="overflow: hidden; position: relative;" class="btn btn-outline-primary waves-effect waves-light"><i class="fa fa-edit"></i></a>';

                $row_dt[] =$status.' '.$statusEdit.' &nbsp;<a href="javascript:;" onclick="myDelete('.$row->blog_id.')"  title="Delete Blog" style="overflow: hidden; position: relative;" class="btn btn-outline-danger waves-effect waves-light"><i class="fa fa-trash fa-x"></i></a>';
                $data[] = $row_dt;
                    
            }
        
            $response['iTotalRecords']          = $blog_info['total'];
            $response['iTotalDisplayRecords']   = $blog_info['total'];
            $response['aaData']                     = $data;
            
            echo json_encode($response);
        } 
 
    public function add()
        { 
            $this->assets_load->add_css(array(base_url('wp-includes/vendors/select2/css/select2.min.css')),"header");
            $this->assets_load->add_js(array(base_url('wp-includes/vendors/select2/js/select2.min.js')),"header");   

            $this->set_template("master/template/template");
            $this->set_header_path('master/template/header');
            $this->set_footer_path('master/template/footer'); 
           
            $this->set_title("Blog Master");
            $this->session->set_userdata("ADMIN_CURRENT_PAGE","Blog Master");
            $data = array();
            $data['active']="blog";
            $data['sub']="blog";

            $data['tags']       =$this->tag_model->get_all_tag_list(); 
            $data['categories'] =$this->category_model->get_all_category_list();  
            // echo "<pre>";print_r($data);exit;
            $this->view('master/blog/blog/add',$data); 
        }

    public function edit($id)
        { 
            $this->assets_load->add_css(array(base_url('wp-includes/vendors/select2/css/select2.min.css')),"header");
            $this->assets_load->add_js(array(base_url('wp-includes/vendors/select2/js/select2.min.js')),"header");   
           
            $this->set_title("Blog Master");
            $this->session->set_userdata("ADMIN_CURRENT_PAGE","Blog Master");
            $data = array();
            $this->blog_model->set_fields(array('id'=>$id)); 
            $data = $this->blog_model->get_blog_detail();  
            $data['active']="blog";
            $data['sub']="blog";


            $data['tags']           =   $this->tag_model->get_all_tag_list(); 
            $data['categories']     =   $this->category_model->get_all_category_list();   
            // echo "<pre>";print_r($data);exit;
            $this->view('master/blog/blog/edit',$data); 
        }

   
 
    public function save()
        {
            $id1=xss_clean(trim($this->input->post("id1")));
            $id2=xss_clean(trim($this->input->post("id2")));
            $mode=xss_clean(trim($this->input->post("mode")));
            $fields=array(  
                'blog_category_id',
                'blog_tag_ids',
                'blog_slug',

                'blog_title1',
                'blog_title2',

                'blog_content1',
                'blog_content2',

                'blog_meta_title1',
                'blog_meta_title2',

                'blog_meta_keywords1',
                'blog_meta_keywords2',

                'blog_meta_description1',
                'blog_meta_description2',
                'blog_short_description1',
                'blog_short_description2',
            );
            $data=array();
            $error="";
            
            foreach ($fields as $field) 
            {
                $data[$field]=$this->input->post($field);
            } 

            $config=array(
                array(
                    'field'=>'blog_category_id',
                    'label'=>'Category',
                    'rules'=>'trim|required'
                ),array(
                    'field'=>'blog_slug',
                    'label'=>'Slug',
                    'rules'=>'trim|required'
                ),array(
                    'field'=>'blog_title1',
                    'label'=>'Arabic Title',
                    'rules'=>'trim|required'
                ),array(
                    'field'=>'blog_title2',
                    'label'=>'English Title',
                    'rules'=>'trim|required'
                ),array(
                    'field'=>'blog_content2',
                    'label'=>'English Content',
                    'rules'=>'trim|required'
                ),array(
                    'field'=>'blog_content1',
                    'label'=>'Arabic Title',
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
                 // echo "<pre>";print_r($_POST);exit;
 
                $slugAvailable = $this->blog_model->slug_available($data['blog_slug'],$id2); 
                if ($slugAvailable) { 
                    $array=array();
                    $array['status']    =   500; 
                    $array['message']   =   'This blog slug  is not available, Please fill another one';
                    echo json_encode($array);
                    exit; 
                } 

                if(!empty($_FILES) && array_key_exists("blog_image2", $_FILES) && $_FILES['blog_image2']["name"] !='')
                {
                    $path = FCPATH.'/resources/uploads/blog/original/';
                    $this->file_uploader->set_default_upload_path($path);                  
                    if( $_FILES['blog_image2']['type'] == 'image/gif' ||  $_FILES['blog_image2']['type'] == 'image/jpg' || $_FILES['blog_image2']['type'] == 'image/jpeg' ||  $_FILES['blog_image2']['type'] == 'image/png')        
                    {
                        $_FILES['blog_image2']["name"] = str_replace(' ','_',$_FILES['blog_image2']["name"]);
                        $post_image = $this->file_uploader->upload_image('blog_image2');
                            
                        if($post_image['status'] == 200)
                        {
                            $thumb_name = explode("/", $post_image [ "data" ]);
                            if(!file_exists(FCPATH.'/resources/uploads/blog/thumb/'.$thumb_name[0]))
                            {
                                mkdir(FCPATH.'/resources/uploads/blog/thumb/'.$thumb_name[0]);
                                chmod(FCPATH.'/resources/uploads/blog/thumb/'.$thumb_name[0],0777);
                            }

                            $this->load->library('image_lib');
                            $config_manip = array(
                                    'image_library' =>'gd2',
                                    'library_path'  => '/usr/X11R6/bin/',
                                    'source_image'  => FCPATH.'/resources/uploads/blog/original/'.$post_image [ "data" ],
                                    'new_image'     => FCPATH.'/resources/uploads/blog/thumb/'.$thumb_name[0]."/".$thumb_name[1],
                                    'maintain_ratio'=> true ,
                                    'create_thumb'  => false ,
                                    'width'         => 370,
                                    'height'        => 245          
                            ); 

                            if(isset($_POST['current_picture2']) && $_POST['current_picture2'] != "")
                            {
                                @unlink(FCPATH.'/resources/uploads/blog/original/'.$_POST['current_picture2']);
                                @unlink(FCPATH.'/resources/uploads/blog/thumb/'.$_POST['current_picture2']);
                            }  

                            $this->image_lib->initialize($config_manip);
                            $this->image_lib->resize();
                            $errors = $this->image_lib->display_errors();
                            $data['blog_image2'] = $post_image['data'];   
                        }
                    }           
                    else
                    {  
                        $error=get_message("valid_profile_image");
                        $array=array();
                        $array['status']    =500;
                        $array['message']   =$error;
                        echo json_encode($array);
                        exit;
                    }
                }



                if(!empty($_FILES) && array_key_exists("blog_image1", $_FILES) && $_FILES['blog_image1']["name"] !='')
                {
                    $path = FCPATH.'/resources/uploads/blog/original/';
                    $this->file_uploader->set_default_upload_path($path);                  
                    if( $_FILES['blog_image1']['type'] == 'image/gif' ||  $_FILES['blog_image1']['type'] == 'image/jpg' || $_FILES['blog_image1']['type'] == 'image/jpeg' ||  $_FILES['blog_image1']['type'] == 'image/png')        
                    {
                        $_FILES['blog_image1']["name"] = str_replace(' ','_',$_FILES['blog_image1']["name"]);
                        $post_image = $this->file_uploader->upload_image('blog_image1');
                            
                        if($post_image['status'] == 200)
                        {
                            $thumb_name = explode("/", $post_image [ "data" ]);
                            if(!file_exists(FCPATH.'/resources/uploads/blog/thumb/'.$thumb_name[0]))
                            {
                                mkdir(FCPATH.'/resources/uploads/blog/thumb/'.$thumb_name[0]);
                                chmod(FCPATH.'/resources/uploads/blog/thumb/'.$thumb_name[0],0777);
                            }

                            $this->load->library('image_lib');
                            $config_manip = array(
                                    'image_library' =>'gd2',
                                    'library_path'  => '/usr/X11R6/bin/',
                                    'source_image'  => FCPATH.'/resources/uploads/blog/original/'.$post_image [ "data" ],
                                    'new_image'     => FCPATH.'/resources/uploads/blog/thumb/'.$thumb_name[0]."/".$thumb_name[1],
                                    'maintain_ratio'=> true ,
                                    'create_thumb'  => false ,
                                    'width'         => 370,
                                    'height'        => 245          
                            ); 

                            if(isset($_POST['current_picture2']) && $_POST['current_picture2'] != "")
                            {
                                @unlink(FCPATH.'/resources/uploads/blog/original/'.$_POST['current_picture2']);
                                @unlink(FCPATH.'/resources/uploads/blog/thumb/'.$_POST['current_picture2']);
                            }  

                            $this->image_lib->initialize($config_manip);
                            $this->image_lib->resize();
                            $errors = $this->image_lib->display_errors();
                            $data['blog_image1'] = $post_image['data'];   
                        }
                    }           
                    else
                    {  
                        $error=get_message("valid_profile_image");
                        $array=array();
                        $array['status']    =500;
                        $array['message']   =$error;
                        echo json_encode($array);
                        exit;
                    }
                }
                


                // echo "<pre>";print_r($data);exit;
                if($mode == 'add')
                {  
                    $data2 = [
                        'blog_category_id'      =>$data['blog_category_id'],
                        'blog_tag_ids'          =>$data['blog_tag_ids'],
                        'blog_title'            =>$data['blog_title2'],
                        'blog_slug'             =>$data['blog_slug'],
                        'blog_content'          =>$data['blog_content2'],
                        'blog_meta_title'       =>$data['blog_meta_title2'],
                        'blog_meta_description' =>$data['blog_meta_description2'],
                        'blog_short_description' =>$data['blog_short_description2'],
                        'blog_meta_keywords'    =>$data['blog_meta_keywords2'],
                        'blog_language_id'      =>2
                    ];   

                    if (!empty($data['blog_image2'])) { 
                        $data2['blog_main_image']=$data['blog_image2'];
                    }
                    $this->common_model->set_fields($data2);
                    $blog_id = $this->common_model->save('blog');
                    if ($blog_id) 
                    {
                        $data1 = [
                            'blog_title'                =>$data['blog_title1'], 
                            'blog_category_id'          =>$data['blog_category_id'], 
                            'blog_tag_ids'              =>$data['blog_tag_ids'],
                            'blog_content'              =>$data['blog_content1'],
                            'blog_meta_title'           =>$data['blog_meta_title1'],
                            'blog_meta_description'     =>$data['blog_meta_description1'], 
                            'blog_short_description'    =>$data['blog_short_description1'],
                            'blog_meta_keywords'        =>$data['blog_meta_keywords1'],
                            'blog_language_id'          =>1,
                            'blog_language_master_id'   =>$blog_id
                        ]; 
                        if (!empty($data['blog_image1'])) { 
                            $data1['blog_main_image']=$data['blog_image1'];
                        }
                        $this->common_model->set_fields($data1);
                        $this->common_model->save('blog');

                        $array=array();
                        $array['status']=200;
                        $array['message']="Blog Added Successfully....";
                        echo json_encode($array);
                        exit;
                    }
                    else
                    {
                        $array=array();
                        $array['status']=500;
                        $array['message']="Blog Adding Failed....";
                        echo json_encode($array);
                        exit;
                    }
                }
                else
                { 
                    $data2 = [
                        'blog_category_id'      =>$data['blog_category_id'],
                        'blog_tag_ids'          =>$data['blog_tag_ids'],
                        'blog_title'            =>$data['blog_title2'],
                        'blog_slug'             =>$data['blog_slug'],
                        'blog_content'          =>$data['blog_content2'],
                        'blog_meta_title'       =>$data['blog_meta_title2'],
                        'blog_meta_description' =>$data['blog_meta_description2'],
                        'blog_short_description' =>$data['blog_short_description2'],
                        'blog_meta_keywords'    =>$data['blog_meta_keywords2'], 
                    ];   
                    $data1 = [
                        'blog_title'                =>$data['blog_title1'], 
                        'blog_category_id'          =>$data['blog_category_id'], 
                        'blog_tag_ids'              =>$data['blog_tag_ids'],
                        'blog_content'              =>$data['blog_content1'],
                        'blog_meta_title'           =>$data['blog_meta_title1'],
                        'blog_meta_description'     =>$data['blog_meta_description1'],
                        'blog_short_description'    =>$data['blog_short_description1'],
                        'blog_meta_keywords'        =>$data['blog_meta_keywords1'],
                    ]; 

                    if (!empty($data['blog_image1'])) { 
                        $data1['blog_main_image']=$data['blog_image1'];
                    }
                    if (!empty($data['blog_image2'])) { 
                        $data2['blog_main_image']=$data['blog_image2'];
                    }
                    $where1 = array('blog_id'   =>  $id1);
                    $this->common_model->set_fields($data1);                 
                    $this->common_model->updateData('blog',$where1);

                    $where2 = array('blog_id'   =>     $id2);
                    $this->common_model->set_fields($data2);                 
                    $this->common_model->updateData('blog',$where2);


                    $array=array();
                    $array['status']=200;
                    $array['message']="Blog Updated Successfully...."; 
                    echo json_encode($array);
                    exit;
                }
            }
        } 
 
    public function delete_blog($id)
        {
            $this->common_model->set_fields(array('is_delete'=>1));
            $this->common_model->updateData('blog',array('blog_id'=>$id));
            $this->common_model->updateData('blog',array('blog_language_master_id'=>$id));
            
            if( $this->input->is_ajax_request() )
            {
                $arr                    = array();
                $arr['status']          = 200;          
                $arr['message']         = "Blog Deleted Successfully"; 
                echo json_encode($arr);
                return;
            }
            else
            {
                $this->session->set_flashdata('success',"Blog Deleted Successfully.");
                redirect($_SERVER["HTTP_REFERER"]);
            }           
        }

    public function update_blog($id,$status)
        {
            $this->common_model->set_fields(array('blog_status'=>$status));
            $this->common_model->updateData('blog',array('blog_id'=>$id));
            $this->common_model->updateData('blog',array('blog_language_master_id'=>$id));
            
            if( $this->input->is_ajax_request() )
            {
                $arr                    = array();
                $arr['status']          = 200;          
                $arr['message']         = "Blog Updated Successfully"; 
                echo json_encode($arr);
                return;
            }
            else
            {
                $this->session->set_flashdata('success',"Blog Deleted Successfully.");
                redirect($_SERVER["HTTP_REFERER"]);
            }     
        }

     


}
