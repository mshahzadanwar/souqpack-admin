<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * handles the footers
 * 
 * @since 1.0
 * @author DeDevelopers
 * @copyright Copyright (c) 2019, DeDevelopers, https://dedevelopers.com
 */
class footers extends ADMIN_Controller {
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
        $this->redirect_role(-1);

        $this->data['active'] = 'brand';
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

        $this->data['title'] = 'Clients';
        $this->data['sub'] = 'footers';
        $this->data['js'] = 'listing';
        $this->data['jsfile'] = 'js/footers_listing';
        $this->data['content'] = $this->load->view('backend/footers/listing',$this->data,true);
        $this->load->view('backend/common/template',$this->data);

    }
   
    public function edit($id){

        $result = $this->db->where("id",$id)->get("footers")->result_object()[0];
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
            $this->data['title'] = 'Edit Clients';
            
            $this->data['content'] = $this->load->view('backend/footers/edit',$this->data,true);
             
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


                $input = $lang->slug."[body]";
                $dbData['body'] = $this->input->post($input);

                $this->db->where("id",$row_id);
                $this->db->update('footers',$dbData);
            }

            $this->session->set_flashdata('msg','Client updated successfully!');
            redirect('admin/footers');

        }
    }
}
