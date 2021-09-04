<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ADMIN_Controller extends CI_Controller {

	/**
	 * Core Class for everthing controller of admin.
	 *
	 * this class is for running pre-codes before actual class.
	 *
	 * @$data is variable of this class and is inherited with childs
	 * this is a semi-global container of multiple objects|array|any
     * @copyright Copyright (c) 2019, DeDevelopers, https://dedevelopers.com
	*/
	// private $data = array();
	
	public $_default_template_name = "template" ; 
	function __construct()
	{
		parent::__construct();
		error_reporting(1);
		$this->load->library('form_validation');
		$this->load->library('session');
        $this->load->helper('url');
        $this->load->database();
        $this->db->reconnect();

        $this->data['url'] = base_url();
		$this->data['assets'] = base_url()."resources/backend/";
		$this->data['root'] = base_url();
		$this->data['js'] = '';
		$this->data['jsfile'] = '';
		$this->data['sub'] = '';
		$this->load->model('app_model','app');
		$this->data['notifications'] = $this->app->get_all_notification();
		$this->data['settings'] =settings();

        // refreshing roles
        $this->refresh_roles();
	}
	/**
	 * function image_required takes name as String and takes file from global 
	 * $_FILES array, and a 2nd perimeter $required as array of 3 consective
	 * elements, first input field name from HTML, 2nd is allowed width, and 3rd 
	 * is allowed height, thus this function returns true or false on behalf of
	 * given data. This function returns false if no images is submitted
	 *
	 * @param {$val|string}
	 * @param {$requird|array}
	 * @return {$fine|boolean}
	 * 
	 * @since 1.0
	 * @author DeDevelopers https://dedevelopers.com
     * @copyright Copyright (c) 2019, DeDevelopers, https://dedevelopers.com
	 */
	public function image_required($val,$requird){
        $requird = explode(',',$requird);
        $field_name = $requird[0];
        $required_width = $requird[1];
        $required_height = $requird[2];
	    if(empty($_FILES[$field_name]['name'])){

	        $this->form_validation->set_message('image_required','This field is required.');
	        return false;
        }else{
            $file_parts = pathinfo($_FILES[$field_name]['name']);
            $allowed_ext = array('jpg','jpeg','png','gif');
            if(in_array(strtolower($file_parts['extension']),$allowed_ext)){
                $image_info = getimagesize($_FILES[$field_name]["tmp_name"]);
                $width = $image_info[0];
                $height = $image_info[1];
                if(($required_height < $width) && ($required_height < $height)){
                    return true;
                }else{
                    $this->form_validation->set_message('image_required','Minimum image dimension is '.$required_width.' X '.$required_height.' required.');
                    return false;
                }
            }else{
                $this->form_validation->set_message('image_required','Only jpg,jpeg,png and gif image are allowed.');

                return false;
            }
        }

    }
    /**
	 * function image_not_required takes name as String and takes file from global 
	 * $_FILES array, and a 2nd perimeter $required as array of 3 consective
	 * elements, first input field name from HTML, 2nd is allowed width, and 3rd 
	 * is allowed height, thus this function returns true or false on behalf of
	 * given data. This function returns true if no images is submitted
	 *
	 * @param {$val|string}
	 * @param {$requird|array}
	 * @return {$fine|boolean}
	 * 
	 * @since 1.0
	 * @author DeDevelopers https://dedevelopers.com
     * @copyright Copyright (c) 2019, DeDevelopers, https://dedevelopers.com
	 */
    public function image_not_required($val,$requird){
        $requird = explode(',',$requird);
        $field_name = $requird[0];
        $required_width = $requird[1];
        $required_height = $requird[2];
        if(empty($_FILES[$field_name]['name'])){

            return true;
        }else{
            $file_parts = pathinfo($_FILES[$field_name]['name']);
            $allowed_ext = array('jpg','jpeg','png','gif');
            // print_r($_FILES[$field_name]['name']);
            if(in_array(strtolower($file_parts['extension']),$allowed_ext)){
                $image_info = getimagesize($_FILES[$field_name]["tmp_name"]);
                $width = $image_info[0];
                $height = $image_info[1];
                if(($required_height < $width) && ($required_height < $height)){
                    return true;
                }else{
                    $this->form_validation->set_message('image_not_required','Minimum image dimension is '.$required_width.' X '.$required_height.' required.');
                    return false;
                }
            }else{
                $this->form_validation->set_message('image_not_required','Only jpg,jpeg,png and gif image are allowed.');
                // echo 1;exit;
                return false;
            }
        }

    }
    /**
	 * function image_upload takes field name from HTML, path to upload to, and 
	 * array of allowed extensions, then this function uploads image to given path
	 * 
	 *
	 * @param {$field|string}
	 * @param {$path|string}
	 * @param {$extensions|array}
	 * @return {$result|array}
	 * 
	 * @since 1.0
	 * @author DeDevelopers https://dedevelopers.com
     * @copyright Copyright (c) 2019, DeDevelopers, https://dedevelopers.com
	 */
    public function image_upload($field,$path,$extensions){
        $config['upload_path']          = $path;
        $config['allowed_types']        = $extensions;
        $config['file_ext_tolower']     = true;
        $config['encrypt_name']         = true;
        $config['remove_spaces']        = true;

        $this->load->library('upload', $config);
        $this->upload->initialize($config);
        
        $return['upload'] = true;
        $return['data'] = '';

        if ( ! $this->upload->do_upload($field))
        {

            $return['upload'] = false;
            $return['data'] = $this->upload->display_errors();

        }
        else
        {
            $return['data'] = $this->upload->data();

        }

        return $return;

    }

    public function file_upload_func($field,$path,$extensions){
        $config['upload_path']          = $path;
        $config['allowed_types']        = $extensions;
        $config['file_ext_tolower']     = true;
        $config['encrypt_name']         = true;
        $config['remove_spaces']        = true;

        $this->load->library('upload', $config);
        $this->upload->initialize($config);

        $return['upload'] = true;
        $return['data'] = '';
        if ( ! $this->upload->do_upload($field))
        { 
            $return['upload']   = false;
            $return['data']     = $this->upload->display_errors(); 
        }
        else
        {
            $return['data'] = $this->upload->data(); 
        } 
        return $return;

    }
    /**
	 * function image_upload_width_height field as string and path to upload file
	 * to and width and height to strech image to.
	 *
	 * @param {$field|string}
	 * @param {$path|string}
	 * @param {$extensions|array}
	 * @param {$width|string}
	 * @param {$height|string}
	 * @return {$result|array}
	 * 
	 * @since 1.0
	 * @author DeDevelopers https://dedevelopers.com
     * @copyright Copyright (c) 2019, DeDevelopers, https://dedevelopers.com
	 */
    public function image_upload_width_height($field,$path,$extensions,$width,$height)
    {
        $config['upload_path']          = $path;
        $config['allowed_types']        = $extensions;
        $config['file_ext_tolower']     = true;
        $config['encrypt_name']         = true;
        $config['remove_spaces']        = true;
        $config['min_width']            = $width;
        $config['min_height']           = $height;

        $this->load->library('upload', $config);
        $this->upload->initialize($config);

        $return['upload'] = true;
        $return['data'] = '';

        if ( ! $this->upload->do_upload($field))
        {
            $return['upload'] = false;
            $return['data'] = $this->upload->display_errors();

        }
        else
        {
            $return['data'] = $this->upload->data();

        }
        return $return;
    }
    /**
	 * function image_thumb takes $resource as source image and makes a thumbnail
	 * of given $width and $height, also stores it on specified $path.
	 *
	 * @param {$resource|string}
	 * @param {$path|string}
	 * @param {$width|string}
	 * @param {$height|string}
	 *
	 * 
	 * @since 1.0
	 * @author DeDevelopers https://dedevelopers.com
     * @copyright Copyright (c) 2019, DeDevelopers, https://dedevelopers.com
	 */
    public function image_thumb($resource,$path,$width,$height)
    {
        $config['image_library'] = 'gd2';
        $config['source_image'] = $resource;
        $config['maintain_ratio'] = TRUE;
        $config['width']         = $width;
        $config['height']       = $height;
        $config['quality']       = '100%';
        $config['new_image']       = $path;
        $this->load->library('image_lib', $config);
        $this->image_lib->resize();
    }
    /**
	 * function reply_email takes $to to send email to, take $subject as subject 
	 * (string) and a $message (string) and $attached_file (string|path_to_file)
	 * and sends and email from dedevelopers@gmail.com 
	 *
	 * @param {$to|string}
	 * @param {$subject|string}
	 * @param {$message|string}
	 * @param {$attached_file|string}
	 *
	 * 
	 * @since 1.0
	 * @author DeDevelopers https://dedevelopers.com
     * @copyright Copyright (c) 2019, DeDevelopers, https://dedevelopers.com
	 */
    public function reply_email($to , $subject ,$message ,$attached_file)
    {
        $config['charset'] = 'iso-8859-1';
        $config['wordwrap'] = TRUE;
        $this->load->library('email',$config);
        $this->email->from('dedevelopers@gmail.com', 'Dedevelopers');
        $this->email->to($to);
        $this->email->subject($subject);
        $this->email->message($message);
        if($attached_file !='')
        {
          $this->email->attach($attached_file); 
        }
        $this->email->set_mailtype("html");
        $this->email->send();
    }

    /**
     * [redirect_role redirects the user to logout page if user is trying
     * to access the class if the premission is missing... the class is where
     * from this function is called at the moment, with given role]
     * @param  [number] $role [role required to access this class]
     * @return [redirect|voic]       [redirects if user is missing the required
     * permissions, otherwise doesn't do anything]
     * 
     * @since 1.0
     * @author DeDevelopers https://dedevelopers.com
     * @copyright Copyright (c) 2019, DeDevelopers, https://dedevelopers.com
     */
    public function redirect_role($role)
    {
        if(is_designer() && in_array($role, $this->designer_permitted())) return true;
        if(is_accountant() && in_array($role, $this->accountant_permitted())) return true;
        if(is_stock() && in_array($role, $this->stock_permitted())) return true;
        if(!check_role($role))
        {
            redirect(base_url().'admin/logout');
            exit;
        }
    }
    private function designer_permitted()
    {
        return array(3,1);
    }

    private function accountant_permitted()
    {
        return array(3,1);
    }

    private function stock_permitted()
    {
        return array(3,1);
    }
    /**
     * [refresh_roles when this function is called all the user roles sessions
     * are refreshed by using the update/current database values]
     * @return [void] []
     *
     * 
     * @since 1.0
     * @author DeDevelopers https://dedevelopers.com
     * @copyright Copyright (c) 2019, DeDevelopers, https://dedevelopers.com
     */
    public function refresh_roles()
    {
        $roles = $this->db->where('admin_id',$this->session->userdata('admin_id'))->get('admin_roles')->result_object();
            foreach($roles as $role)
                $final_roles[]=$role->role;
        $this->session->set_userdata('admin_roles',$final_roles);
    }
    
    /**
    * this method is used inside un-available functions
    *
     * @since 1.0
     * @author DeDevelopers https://dedevelopers.com
     * @copyright Copyright (c) 2019, DeDevelopers, https://dedevelopers.com
    */
    public function shutdown_function()
    {
        echo "Function is shutdown for unavailability reasons around this feature";
        exit;
    }
    /**
    * this method is used to store data according to the selected language
    *
    * @since 1.0
    * @author DeDevelopers https://dedevelopers.com
    * @copyright Copyright (c) 2019, DeDevelopers, https://dedevelopers.com
    */
    public function insert_lang_wise($table,$data,$clang)
    {
        
    }
    public function redirect_back()
    {
        redirect($_SERVER["HTTP_REFERER"]);
    }

}













 /********Derived class..********/
class Template extends ADMIN_Controller {

    protected $_title            = "";
    protected $_site_name        = "Wediina Blog";
    protected $_footer           = array();
    protected $_header           = array();
    protected $_meta_title       = 'Wediina Blog';
    protected $_meta_keywords    = 'Wediina Blog';
    protected $_meta_description = 'Wediina Blog';
    protected $_canonical_link   ='';
    protected $_footer_path      = "blocks/footer";
    protected $_header_path      ="blocks/header";
    protected $settings      ="blocks/header";
    

    public function __construct()
    {
        parent::__construct();
        
        $this->load->library('parser');
        $this->load->library('assets_load');
        $this->load->helper('message');
        $this->_title = $this->config->item("site_name");
        
        
        $this->settings =settings();
        
        // echo "<pre>";print_r($this->settings);exit;
        
        

        define('SERVER_IMG_DIR',FCPATH.'/wp-includes/upload/images/');
        define('SERVER_SUM_IMG_DIR',FCPATH.'/wp-includes/upload/images/summer');
        define('FRONT_IMG_DIR',base_url().'wp-includes/upload/images/');
        define('FRONT_sum_IMG_DIR',base_url().'wp-includes/upload/images/summer');
        
    }

    public function status_changer($table_name, $column_name, $where_to_change, $input_data)
    {
        $data = array( $column_name => $input_data );
        $where = array('id' => $where_to_change);
        $this->common_model->set_fields($data);
        $this->common_model->updateData($table_name, $where);
    }
    

    public function heading($head)
    {
        $this->session->set_flashdata("head",$head);
    }

    /* <optional>   Set browser Title*/
    public function set_title($title)   
    {
        if ( $title != '' )
        {
            $this->_title   = $title." | ".$this->config->item("site_name");
        }
    }
    public function set_site_name($site_name)   
    {
        if ( $site_name != '' )
        {
            $this->_site_name   = $site_name;
        }else{
            $this->_site_name   = $this->config->item("site_name");
        }
    }
    
    /* <optional>   Set Another Template*/
    public function set_template($template_name)    
    {
        if ( $template_name != '' )
        {
            $this->_default_template_name   = $template_name;
        }
    }

    /* <optional>   Set another Footer*/
    public function set_footer_path ( $footer_path )
    {
        $this->_footer_path = $footer_path;
    }

    /*  <optional> Set Footer */
    public function set_footer ( $footer )
    {
        $this->_footer  = $footer;
    }
    
    
    
    /* <optional>   Set Header*/
    public function set_header_path ( $header_path )
    {
        $this->_header_path = $header_path;
    }

    /*  <optional> Set Header */
    public function set_header ( $header )
    {
        $this->_header  = $header;
    }
    
    public function set_meta_title($meta_title)
    {
        if($meta_title !='' )
        {
            $this->_meta_title  = $meta_title;
        }else{
            $this->_meta_title  = $this->config->item("meta_title");
        }
    }
    public function set_meta_keywords($meta_keywords)
    {
        if($meta_keywords !='' )
        {
            $this->_meta_keywords   = $meta_keywords;
        }else{
            $this->_meta_keywords   = $this->config->item("meta_keywords");
        }
    }
    public function set_meta_description ($meta_description)
    {
        if($meta_description !='' )
        {
            $this->_meta_description    = $meta_description;
        }else{
            $this->_meta_description    = $this->config->item("meta_description");
        }
    }
    public function set_canonical_link ($canonical_link)
    {
        if($canonical_link !='' )
        {
            $this->_canonical_link  = $canonical_link;
        }else{
            $this->_canonical_link  = $this->config->item("canonical_link");
        }
    }
    
    public function send_phone_message($mobile_number,$message)
    {
        $mobile_number=$query->row_array()['phone_no'];
        $otp=$query->row_array()['order_otp'];
        $username="MGST-LITE";  
        $password="MGST-LITE@2019" ; 
        $message="OTP ".$otp." For Confirm Order no #".$query->row_array()['order_id'];  
        $sender="SHOPCL";  
        
        $url="encreta.bulksms5.com/sendmessage.php?user=".urlencode($username)."&password=".urlencode($password)."&mobile=".urlencode($mobile_number)."&message=".urlencode($message)."&sender=".urlencode($sender)."&type=".urlencode('3'); 
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $curl_scraped_page = curl_exec($ch);
        curl_close($ch); 
    }

    /* use this file for insert content on view
    */
    public function view( $view_file_name, $data = array() ) 
    {
            
        $template_data = array();
        $template_data ['title']    = $this->_title;
        $template_data ['settings']    = $this->settings;
        $template_data ['site_name']    = $this->_site_name;
        $template_data ['meta_title']   = $this->_meta_title;
        $template_data ['meta_keywords']    = $this->_meta_keywords;
        $template_data ['meta_description']     = $this->_meta_description;
        $template_data ['canonical_link']   = $this->_canonical_link;
        $template_data ["header"]   = $this->load->view( $this->_header_path, array_merge( $data, $template_data ),TRUE );
        $template_data ["content"]  = $this->load->view( $view_file_name, array_merge( $data, $template_data ),TRUE );
        $template_data ['footer']   = $this->load->view ( $this->_footer_path, $this->_footer,TRUE );
                    
        unset($template_data['settings']);
        $this->parser->parse( $this->_default_template_name, $template_data );
    }
        
    public function upload_image($field_name,$file_clone_path = '/uploads/')
    {
        return $this->file_upload( $field_name, 'gif|jpg|png', $file_clone_path );
    } 
    
    public function get_post_values($keys)
    {
        $returnArray = array();
        foreach( $keys as $k => $field )
        {
            $returnArray[$field] = xss_clean( trim($this->input->post($field)) );
        }
        return $returnArray;
    } 
    
    //check and return 
    public function is_session_active( $redirect_enable = false )
    {
        
        if ( ! $this->session->userdata('id')  )
        {
            if ( $redirect_enable )
                redirect('');
            return false;
        }   
        return $this->session->userdata('id');
    }
    
    public function encrypt( $password )
    {
        //$str = do_hash($str); // SHA1
        
        return do_hash($password, 'SHA1'); // MD5   
    } 

    /*Return  Json Encode Data With Url*/
    public function maro_jawab($status,$msg='',$url='')
        {   
            
            /*
                success=200
                failure=500
                error=500
                missing=404
            */ 

            $array=array();
            $array['status']=$status; 
            $array['message']=$msg;
            $array['rtn_url']=$url;
            echo json_encode($array);
            return;
        }

    /*-----Upload Other file----
            arguments 
                    1) Field name
                    2) Upload path
    */  
    public function upload_file($field_name,$file_clone_path = '/uploads/')
    {
        return $this->file_upload( $field_name, 'txt|pdf|doc', $file_clone_path );
    } 

    //After logout use this function to clear cache 
    public function clear_cache()
    {
        //$this->cache->clean();
        $this->db->cache_delete_all();
        
        header("Expires: Thu, 19 Nov 1981 08:52:00 GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate");   
    }
        
    /*
    * Function for upload any types of file 
    */
    private function file_upload( $field_name, $allowed_type='gif|jpg|png' ,$file_clone_path = '/uploads/' )
    {
        //Initailize
        $config['upload_path'] = $file_clone_path;
        $config['allowed_types'] = $allowed_type;
        $config['max_size'] = '100';
        $config['max_width']  = '1024';
        $config['max_height']  = '768';
        
        //Assign file name
        if ( $this->get_file_name ( $field_name ) )
        {
            $config ['file_name']  = $this->get_file_name ( $field_name ) ; 
        }
        return  array("status"=>"404"); 
        
        $this->load->library('upload', $config);
        $this->upload->initialize($config);

        //Uploading process
        if ( ! $this->upload->do_upload($field_name) ) //File upload fail
        {
            $error = array('error' => $this->upload->display_errors());

            return array_merge( array("status"=>"500"), $error );
        }
        else        //File upload success
        {
            return array_merge( array("status"=>"200"), $error );
            return $this->upload->data();
        }   
    }
    
    
    public function required_field(  $fields,$all=false )
    {
        $error = '';
        foreach( $fields as $field )
        {
                if ( trim( $this->input->post($field) )=='' )
                {
                    $error = "<div>".$field ." field is required. </div>";
                    if ( $all == false )
                        break;
                }           
        }   
        return $error;
    }
    

    public function required_field_advance_validation(  $fields, $pre_msg='', $post_msg=' is required.' )
    {
        //print_r( $fields ); 
        $error = '';
        foreach( $fields as $field )
        {
                //print_r( $field['field'] ); 
                if ( trim( $this->input->post( $field['field']  ) ) =='' )
                {
                    $error .= "<div>".$pre_msg.$field['label'] .$post_msg."</div>";
                }           
        }
    
        return $error;

    }
    
    public function createPDF($fileName,$html) {
        ob_start(); 
        
        // Include the main TCPDF library (search for installation path).
         
        $this->load->library('Pdf');
        // create new PDF document
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('MGST-LITE');
        $pdf->SetTitle('MGST-LITE');
        $pdf->SetSubject('MGST-LITE');
        $pdf->SetKeywords('MGST-LITE');
    
        // set default header data
        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);
    
        // set header and footer fonts
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        
        $pdf->SetPrintHeader(false);
        $pdf->SetPrintFooter(false);
    
        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    
        // set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, 0, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(0);
        $pdf->SetFooterMargin(0);
    
        // set auto page breaks
        //$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->SetAutoPageBreak(TRUE, 0);
    
        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
    
        // set some language-dependent strings (optional)
        if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
            require_once(dirname(__FILE__).'/lang/eng.php');
            $pdf->setLanguageArray($l);
        }       
    
        // set font
        $pdf->SetFont('dejavusans', '', 10);
    
        // add a page
        $pdf->AddPage();
    
        // output the HTML content
        $pdf->writeHTML($html, true, false, true, false, '');
    
        // reset pointer to the last page
        $pdf->lastPage();       
        ob_end_clean();
        //Close and output PDF document
        $pdf->Output($fileName, 'F');        
    }
    
    public function setemail()
    {
        $email="xyz@gmail.com";
        $subject="some text";
        $message="some text";
        $this->sendEmail($email,$subject,$message);
    }
    
    public function sendEmail($email,$subject,$message,$attachPath='')
    {
        $config = array (
              'protocol' => 'smtp',
              'smtp_host' => 'ssl://smtp.googlemail.com',
              'smtp_port' => 465,
              'smtp_user' => 'shm.hardik123@gmail.com', 
              'smtp_pass' => 'shmH@123', 
              'mailtype' => 'html',
              'charset' => 'iso-8859-1',
              'wordwrap' => TRUE
                       );
        $this->load->library('email',$config);
        $this->email->set_newline("\r\n");
    
        $this->email->from('shm.hardik123@gmail.com','Hardik');
        //$data = array( 'email'=> $data['email']);

        $this->email->to($email);  // replace it with receiver mail id
        $this->email->subject($subject); // replace it with relevant subject 
    
        $this->email->message($message); 
        if(isset($attachPath) && $attachPath!=''){
            $this->email->attach($attachPath);    
        }
        if($this->email->send())
        {

        }
        else
        { show_error($this->email->print_debugger()); }

    }

    /*Get current path*/
    
    public function get_current_url()
    {
        $protocol       = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        //$server_name  = $_SERVER['HTTP_HOST'].'/'.ltrim($_SERVER['REQUEST_URI'], '/');
        $server_name    = ltrim($_SERVER['REQUEST_URI'], '/');
        return  ($protocol.$server_name);
    }   
    
    
    /*
    * File name Generator   
    */
    private function get_file_name ( $field_name )
    {
        //$ext = end(explode(".", $_FILES [ $field_name ] ['name']));
        if( !file_exists( $_FILES[$field_name] ['tmp_name']) || !is_uploaded_file ( $_FILES[$field_name]['tmp_name']) )
        {
            return false;
        }
        return rand(0000,1111).$_FILES [ $field_name ] ['name'];    
    }
    
    public function sendTextOtp($mobile_no,$otp,$message,$type='3')
    { 
        $username="MGST-LITE";  
        $password="MGST-LITE@2019" ;  
        $sender="SHOPCL";   
        $url="encreta.bulksms5.com/sendmessage.php?user=".urlencode($username)."&password=".urlencode($password)."&mobile=".urlencode($mobile_number)."&message=".urlencode($message)."&sender=".urlencode($sender)."&type=".urlencode('3'); 
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $curl_scraped_page = curl_exec($ch);
        curl_close($ch);  
    }
    
    public function mres($value)
    {
        $search = array("\\",  "\x00", "\n",  "\r",  "'",  '"', "\x1a");
        $replace = array("\\\\","\\0","\\n", "\\r", "\'", '\"', "\\Z");
    
        return str_replace($search, $replace, $value);
    }

    public function model($model_name)
    {
        $this->load->model($model_name);
    }
    public function convert_number($number)
    {
        if (($number < 0) || ($number > 999999999)) {
            throw new Exception("Number is out of range");
        }
        $Gn = floor($number / 1000000);
        /* Millions (giga) */
        $number -= $Gn * 1000000;
        $kn = floor($number / 1000);
        /* Thousands (kilo) */
        $number -= $kn * 1000;
        $Hn = floor($number / 100);
        /* Hundreds (hecto) */
        $number -= $Hn * 100;
        $Dn = floor($number / 10);
        /* Tens (deca) */
        $n = $number % 10;
        /* Ones */
        $res = "";
        if ($Gn) {
            $res .= $this->convert_number($Gn) .  "Million";
        }
        if ($kn) {
            $res .= (empty($res) ? "" : " ") .$this->convert_number($kn) . " Thousand";
        }
        if ($Hn) {
            $res .= (empty($res) ? "" : " ") .$this->convert_number($Hn) . " Hundred";
        }
        $ones = array("", "One", "Two", "Three", "Four", "Five", "Six", "Seven", "Eight", "Nine", "Ten", "Eleven", "Twelve", "Thirteen", "Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eightteen", "Nineteen");
        $tens = array("", "", "Twenty", "Thirty", "Fourty", "Fifty", "Sixty", "Seventy", "Eigthy", "Ninety");
        if ($Dn || $n) {
            if (!empty($res)) {
                $res .= " and ";
            }
            if ($Dn < 2) {
                $res .= $ones[$Dn * 10 + $n];
            } else {
                $res .= $tens[$Dn];
                if ($n) {
                    $res .= "-" . $ones[$n];
                }
            }
        }
        if (empty($res)) {
            $res = "zero";
        }
        return $res;
    }
    public function global_upload_image($passed_file='',$create_thumb=false,$set_name='',$passed_width='',$passed_height='')
        {
          $image_arr = array();
          $path = SERVER_IMG_DIR;
            if (isset($passed_file) && !empty($passed_file)) {
                if (is_array($passed_file['name'])) {
                  $limit   = count($passed_file['name']); 
                }else{
                  $limit = 1;
                  $temp_file = $passed_file;
                  $passed_file = array();
                  $passed_file['name'][0] = $temp_file['name'];  
                  $passed_file['type'][0] = $temp_file['type'];  
                  $passed_file['tmp_name'][0] = $temp_file['tmp_name'];  
                  $passed_file['error'][0] = $temp_file['error'];  
                  $passed_file['size'][0] = $temp_file['size'];  
                }

                for ($i=0; $i < $limit; $i++) { 
                    if(isset($passed_file['name'][$i]) && $passed_file['name'][$i] !=''){  
                      $images_details = @getimagesize($passed_file["tmp_name"][$i]);
                      $this->file_uploader->set_default_upload_path($path);                  
                      if( $passed_file['type'][$i] == 'image/gif' ||  $passed_file['type'][$i] == 'image/jpg' ||  $passed_file['type'][$i] == 'image/JPG' || $passed_file['type'][$i] == 'image/jpeg' ||  $passed_file['type'][$i] == 'image/png')     
                      {
                        if(!empty($set_name)){
                            $passed_file["name"][$i] = str_replace(' ','-',$set_name);
                        }else{
                            $passed_file["name"][$i] = str_replace(' ','-',$passed_file["name"][$i]);
                        }

                        $_FILES['global_image_var'] = array(
                            'name'       =>     $passed_file["name"][$i],
                            'type'       =>     $passed_file["type"][$i],
                            'tmp_name'     =>     $passed_file["tmp_name"][$i],
                            'error'     =>     $passed_file["error"][$i],
                            'size'       =>     $passed_file["size"][$i], 
                        );
                        $post_image = $this->file_uploader->upload_image('global_image_var');
                        //print_r($post_image);exit();

                        if($post_image['status'] == 200 && (!empty($passed_width) || !empty($passed_height)) )
                        {
                            $thumb_name = explode("/", $post_image [ "data" ]);
                            if(!file_exists($path.$thumb_name[0]))
                            {
                                mkdir($path.$thumb_name[0]);
                                chmod($path.$thumb_name[0],0777);
                            }

                            $this->load->library('image_lib');
                            $resize_config = array(
                                    'image_library' =>'gd2',
                                    'library_path'  => '/usr/X11R6/bin/',
                                    'source_image'  => $path.$post_image[ "data" ],
                                    'new_image'     => $path.$thumb_name[0]."/".$thumb_name[1],
                                    'maintain_ratio'=> true ,
                                    'max_size'      => '5MB',
                                    'create_thumb'  => false ,
                            );

                            if(!empty($passed_width)){
                              $resize_config['width'] = $passed_width;
                            }elseif(!empty($passed_height)){
                              $resize_config['height'] = $passed_height;
                            }else{
                              $resize_config['width'] = $images_details[0];
                            }

                            if(empty($create_thumb)){
                              @unlink($path.$passed_file["name"][$i]);
                            }

                            $this->image_lib->initialize($resize_config);
                            $this->image_lib->resize();
                            $errors = $this->image_lib->display_errors();

                        }
                        
                        $img_size_arr = @getimagesize($path.$post_image['data']);
                        $image_arr[$i]['image']  = $post_image['data'];
                        $image_arr[$i]['width']  = $img_size_arr[0];
                        $image_arr[$i]['height'] = $img_size_arr[1];
                      }
                    }
                }
                return $image_arr;
            }
            return false;
        }
 




}

