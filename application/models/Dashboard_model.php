<?php

class Dashboard_model extends ADMIN_Model{
	
	public function get_one_notification_of_admin(){
		
		$result = $this->db->query('
			SELECT *
			FROM notifications
			WHERE push_in = 0
			AND read_it = 0
			AND admin_id = '.$this->session->userdata('admin_id').'
			LIMIT 0,1
			'
		);
		
		return $result;
		
	}

    public function get_notification_count(){

        $result = $this->db->query('
			SELECT *
			FROM notifications
			WHERE read_it = 0
			AND admin_id = '.$this->session->userdata('admin_id').'
			'
        );

        return $result->num_rows();

    }
	
	 public function get_language_lists(){

        $result = $this->db->query('
			SELECT *
			FROM `site_languages`'
        );
		// echo $this->db->last_query();
        return $result;

    }
	
}


?>
