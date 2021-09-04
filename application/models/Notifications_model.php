<?php

class Notifications_model extends CI_Model{
	
	public function get_all_notifications($field='id',$order = 'DESC')
	{
        $result = $this->db->query('
			SELECT *
			FROM notifications
			WHERE is_deleted  = 0
			AND lparent  = 0
			ORDER BY '.$field.' '.$order.'
			'
        );

        return $result;

	}

	public function get_all_trash_notifications($field='id',$order = 'DESC')
	{
        $result = $this->db->query('
			SELECT *
			FROM notifications
			WHERE is_deleted  = 1
			AND lparent  = 0
			ORDER BY '.$field.' '.$order.'
			'
        );

        return $result;

	}

    public function get_all_active_notifications($field='id',$order = 'DESC')
    {
        $result = $this->db->query('
			SELECT *
			FROM notifications
			WHERE is_deleted  = 0
			AND status  = 1
			AND lparent  = 0
			ORDER BY '.$field.' '.$order.'
			'
        );

        return $result;

    }
	public function get_notification_by_title($title)
	{
        $result = $this->db->query('
			SELECT *
			FROM notifications
			WHERE is_deleted  = 0
			AND lparent  = 0
			AND title = "'.$title.'"
			'
        );

        return $result;

	}

	public function get_notification_by_id($id){

        $result = $this->db->query('
			SELECT *
			FROM notifications
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
    public function get_notification_by_lang($lang_id,$id){
    	$this->db->group_start();
    	$this->db->where("lang_id",$lang_id);
    	$this->db->group_end();
    	$this->db->group_start();
    	$this->db->where("id",$id);
    	$this->db->or_where("lparent",$id);
    	$this->db->group_end();
    	return $this->db->get('notifications')->result_object()[0];
    }
}


?>
