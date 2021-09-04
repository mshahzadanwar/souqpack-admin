<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * handles the Reviews
 * 
 * @since 1.0
 * @author DeDevelopers
 * @copyright Copyright (c) 2019, DeDevelopers, https://dedevelopers.com
 */
class Reviews extends ADMIN_Controller {
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
        $this->redirect_role(25);
        $this->data['active'] = 'review';
        
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

        $this->data['title'] = 'reviews';
        $this->data['sub'] = 'reviews';
        $this->data['js'] = 'listing';
        $this->data['jsfile'] = 'js/reviews_listing';
        $this->data['reviews'] = $this->db->order_by("id","DESC")->get('order_reviews');
        $this->data['content'] = $this->load->view('backend/reviews/listing',$this->data,true);
        $this->load->view('backend/common/template',$this->data);

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
        $this->db->where('id',$id);
        $this->db->delete('order_reviews');
        $this->session->set_flashdata('msg', 'review deleted successfully!');
        redirect('admin/reviews');
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

        $result = $this->db->where("id",$id)->get("order_reviews")->result_object()[0];

        if(!$result){

            $this->session->set_flashdata('err','Invalid request.');
            redirect('admin/404_page');

        }

        
        $dbData['status'] = $status;

        $this->db->where('id',$id);
        $this->db->update('order_reviews',$dbData);
        $this->session->set_flashdata('msg','video status updated successfully!');
        
        redirect('admin/reviews');
    }
    
}
