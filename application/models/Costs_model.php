<?php

class Costs_model extends CI_Model{
			// AND region_id IN('.where_region_id().')
	
	public function get_all_costs($field='id',$order = 'DESC')
	{
        $result = $this->db->query('
			SELECT *
			FROM costs
			WHERE is_deleted  = 0
			AND lparent  = 0
			ORDER BY '.$field.' '.$order.'
			'
        );

        // echo $this->db->last_query();exit;

        return $result;

	}

	public function get_all_trash_costs($field='id',$order = 'DESC')
	{
        $result = $this->db->query('
			SELECT *
			FROM costs
			WHERE is_deleted  = 1
			AND lparent  = 0
			ORDER BY '.$field.' '.$order.'
			'
        );

        return $result;

	}

    public function get_all_active_costs($field='id',$order = 'DESC')
    {
        $result = $this->db->query('
			SELECT *
			FROM costs
			WHERE is_deleted  = 0
			AND lparent  = 0
			AND status  = 1
			ORDER BY '.$field.' '.$order.'
			'
        );

        return $result;

    }
	public function get_cost_by_title($title)
	{
        $result = $this->db->query('
			SELECT *
			FROM costs
			WHERE is_deleted  = 0
			AND lparent  = 0
			AND title = "'.$title.'"
			'
        );

        return $result;

	}

	public function get_cost_by_id($id){

        $result = $this->db->query('
			SELECT *
			FROM costs
			WHERE is_deleted  = 0
			AND id = '.$id.'
			ORDER BY id DESC
			'
        );

        if($result->num_rows()>0){

            return $result->row();
        }else{

            return false;

        }
    }
    public function get_cost_by_lang($lang_id,$id){
    	$this->db->group_start();
    	$this->db->where("lang_id",$lang_id);
    	$this->db->group_end();
    	$this->db->group_start();
    	$this->db->where("id",$id);
    	$this->db->or_where("lparent",$id);
    	$this->db->group_end();
    	return $this->db->get('costs')->result_object()[0];
    }
    
}


?>
