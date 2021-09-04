<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class App_model extends ADMIn_Model{
	/**
	 *
	 * this function returns all notification in db. sorted in Ascending order 
	 * of read_it in push_in
	 * 
	 * @since 1.0
	 * @memberOf Model Class
	 * @return object of db containing all notifications sorted `read_id` Ascending
	 * & `push_in` Ascending
	 */
	
	public function get_all_notification()
	{
	    $admin_id = 0;
	    if($this->session->userdata('admin_id') != ''){
	        $admin_id = $this->session->userdata('admin_id');
        }
        $result = $this->db->query('
			SELECT *
			FROM notificationss
			WHERE admin_id = '.$admin_id.'
			ORDER BY read_it ASC,push_in ASC
			'
        );
        return $result;
	}
}