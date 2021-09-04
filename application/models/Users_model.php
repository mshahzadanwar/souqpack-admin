<?php

class users_model extends CI_Model{
	
	public function get_all_users($field='id',$order = 'DESC')
	{
        $result = $this->db->query('
			SELECT *
			FROM users
			WHERE is_deleted  = 0
			AND (email != "" OR phone != "")
			ORDER BY '.$field.' '.$order.'
			'
        );
        
        return $result;

	}
	public function get_all_trashed_users($field='id',$order = 'DESC')
	{
        $result = $this->db->query('
			SELECT *
			FROM users
			WHERE is_deleted  = 1
			ORDER BY '.$field.' '.$order.'
			'
        );

        return $result;

	}

    public function get_all_active_users($field='id',$order = 'DESC')
    {
        $result = $this->db->query('
			SELECT *
			FROM users
			WHERE is_deleted  = 0
			AND status  = 1
			ORDER BY '.$field.' '.$order.'
			'
        );

        return $result;

    }
	public function get_user_by_email($email)
	{
        $result = $this->db->query('
			SELECT *
			FROM users
			WHERE is_deleted  = 0
			AND email = "'.$email.'"
			'
        );

        return $result;

	}

	public function get_user_by_id($id){

        $result = $this->db->query('
			SELECT *
			FROM users
			WHERE is_deleted  = 0
			AND id = '.$id.'
			'
        );

        if($result->num_rows()>0){

            return $result->row();
        }else{

            return false;

        }
    }
	public function get_project_by_id($id)
	{
		$query = $this->db->query('
		
			SELECT * 
			FROM projects 
			WHERE user_id= '.$id.'
			AND is_archive = 0
		');
		if($query->num_rows()>0){

           return $query->result();
		   
        }else{

            return false;

        }	
	}
	public function get_attachments_by_id($project_id)
	{
		$query = $this->db->query('
		
			SELECT * 
			FROM  attachments 
			WHERE project_id= '.$project_id.'
			AND is_deleted = 0
		');
		if($query->num_rows()>0){

           return $query->result();
		   
        }else{

            return false;
        }	
	}

	
}


?>
