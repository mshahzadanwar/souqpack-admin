<?php

class Staff_model extends ADMIN_Model{
	
	public function get_all_staff($role=-2)
	{

		$this->db->where("admin.is_deleted",0);
		$this->db->select("admin.*");
		$this->db->from("admin");

		$this->db->join("admin_roles","admin.id=admin_roles.admin_id","LEFT");
		$this->db->where("admin_roles.role",s_role_to_int($role));
		$this->db->select("admin_roles.role as admin_role");
		$this->db->order_by("admin.id","DESC");
		$result = $this->db->get();
        return $result;

	}
	
    
	public function get_staff_by_email($email)
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

	public function get_staff_by_id($id){

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
