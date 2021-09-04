<?php

class Emails_model extends CI_Model{
	
	public function get_all_emails($field='id',$order = 'DESC')
	{
        $result = $this->db->query('
			SELECT *
			FROM email_template
			WHERE is_deleted  = 0
			AND lparent  = 0
			ORDER BY '.$field.' '.$order.'
			'
        );

        return $result;

	}

	public function get_all_trash_emails($field='id',$order = 'DESC')
	{
        $result = $this->db->query('
			SELECT *
			FROM email_template
			WHERE is_deleted  = 1
			AND lparent  = 0
			ORDER BY '.$field.' '.$order.'
			'
        );

        return $result;

	}

    public function get_all_active_emails($field='id',$order = 'DESC')
    {
        $result = $this->db->query('
			SELECT *
			FROM email_template
			WHERE is_deleted  = 0
			AND status  = 1
			AND lparent  = 0
			ORDER BY '.$field.' '.$order.'
			'
        );

        return $result;

    }
	public function get_email_by_title($title)
	{
        $result = $this->db->query('
			SELECT *
			FROM email_template
			WHERE is_deleted  = 0
			AND lparent  = 0
			AND name = "'.$title.'"
			'
        );

        return $result;

	}

	public function get_email_by_id($id){

        $result = $this->db->query('
			SELECT *
			FROM email_template
			WHERE is_deleted  = 0
			AND id = '.$id.'
			AND lparent  = 0
			ORDER BY id DESC
			'
        );

        if($result->num_rows()>0){

            return $result->row();
        }else{

            return false;

        }
    }
    public function get_email_by_lang($lang_id,$id){
    	$this->db->group_start();
    	$this->db->where("lang_id",$lang_id);
    	$this->db->group_end();
    	$this->db->group_start();
    	$this->db->where("id",$id);
    	$this->db->or_where("lparent",$id);
    	$this->db->group_end();
    	return $this->db->get('email_template')->result_object()[0];
    }

	

	
}


?>
