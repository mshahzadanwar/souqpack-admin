<?php

class Admins_model extends ADMIN_Model{
	
	public function get_all_admins($field='id',$order = 'DESC')
	{
        $result = $this->db->query('
			SELECT *
			FROM admin
			WHERE is_deleted  = 0
			AND satff = 0
			AND show_admin = 0
			ORDER BY '.$field.' '.$order.'
			'
        );

        return $result;

	}
	public function get_all_trashed_admins($field='id',$order = 'DESC')
	{
        $result = $this->db->query('
			SELECT *
			FROM admin
			WHERE is_deleted  = 1
			
			ORDER BY '.$field.' '.$order.'
			'
        );

        return $result;

	}

    public function get_all_active_admins($field='id',$order = 'DESC')
    {
        $result = $this->db->query('
			SELECT *
			FROM admin
			WHERE is_deleted  = 0
			AND status  = 1
			
			ORDER BY '.$field.' '.$order.'
			'
        );

        return $result;

    }
	public function get_admin_by_email($email)
	{
        $result = $this->db->query('
			SELECT *
			FROM admin
			WHERE is_deleted  = 0
			
			AND email = "'.$email.'"
			'
        );

        return $result;

	}

	public function get_admin_by_id($id){

        $result = $this->db->query('
			SELECT *
			FROM admin
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
	
	
	
}


?>
