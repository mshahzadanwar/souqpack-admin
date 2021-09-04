<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * handles the Push
 * 
 * @since 1.0
 * @author DeDevelopers
 * @copyright Copyright (c) 2019, DeDevelopers, https://dedevelopers.com
 */
class Push extends ADMIN_Controller {
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
        check_role(12);
        $this->data['active'] = 'push';
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
		$this->data['title'] = 'Push Notifications';
        $this->data['sub'] = 'push';
        $this->data['jsfile'] = 'js/push';

		$this->data['content'] = $this->load->view('backend/push/send',$this->data,true);
		$this->load->view('backend/common/template',$this->data);
	}
	
	public function send()
	{
	    $title = $this->input->post("title");
        $body = $this->input->post("short_description");


        $user_ids = $this->input->post("users");
       
        if(!empty($user_ids))
            $users = $this->db->where("status",1)->where("is_deleted",0)->where_in("id",$user_ids)->get("users")->result_object();


        if($this->input->post("all_users")==1)
        {
            $users = $this->db->where("status",1)->where("is_deleted",0)->get("users")->result_object();
        }
        foreach($users as $user)
        {
            if($user->push_id)
                $final_notifs[] = $user->push_id;
        }
        

        if(empty($final_notifs))
        {
            redirect($_SERVER["HTTP_REFERER"]);
            return;
        }

        $notif["data"] = (Object) array();
        $notif["tag"] = "Updates";
        $notif["title"] =$title;
        $notif["msg"] = $body;

        foreach($final_notifs as $final_notif){
            try{
                push_notif($final_notif,$notif);
            }
            catch(Exception $e)
            {
                
            }
        }

        $this->session->set_flashdata('msg', 'Notification sent successfully!');
        redirect($_SERVER["HTTP_REFERER"]);
	}
}
