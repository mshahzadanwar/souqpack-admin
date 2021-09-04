<?php

class Corders_model extends CI_Model{
	
	public function get_all_corders($field='id',$order = 'DESC')
	{
        $result = $this->db->query('
			SELECT *
			FROM c_orders
			WHERE is_deleted  = 0
			ORDER BY '.$field.' '.$order.'
			'
        );


        if($_GET["start_date"]!="")
        {
        	$wherehre = where_region_id();
        	$this->db->where("is_deleted",0);
        	if($_GET["admin_status"]!=-1)
        	$this->db->where("admin_status",$_GET["admin_status"]);
        	
        	$this->db->where("DATE(created_at) >=",$_GET["start_date"]);
        	$this->db->where("DATE(created_at) <=",$_GET["to_date"]);
        	$this->db->order_by($field,$order);
        	$result = $this->db->get("c_orders");

        }

        if($_GET["user_id"]!="")
        {
        	$result = $this->db->query('
				SELECT *
				FROM c_orders
				WHERE is_deleted  = 0
				AND user_id = '.$_GET["user_id"].'
				ORDER BY '.$field.' '.$order.'
				'
	        );
        }

        return $result;
	}

	public function get_order_by_id($id){

        $result = $this->db->query('
			SELECT *
			FROM c_orders
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
    public function get_designer_tasks($type=-1)
    {
        if($type!=-1)
        {
            if($type==0)
            {
                $this->db->where_in("status",array(0,3));
            }

            if($type==1)
            {
                $this->db->where("status",4);
            }

            if($type==2)
            {
                $today_date = date("Y-m-d H:i:s");
                $this->db->where("deadline <",$today_date)->where_in("status",array(0,3));
            }
        }
        $this->db->where("designer_id",$this->session->userdata("admin_id"));
        $this->db->order_by("id","DESC");
        $tasks = $this->db->get("designer_tasks");
        return $tasks->result_object();
    }
    public function get_production_tasks($type=-1)
    {
        if($type!=-1)
        {
            if($type==0)
            {
                $this->db->where_in("production_status",array(0,1));
            }

            if($type==1)
            {
                $this->db->where_in("production_status",array(2,3,4));
            }

            if($type==2)
            {
                $today_date = date("Y-m-d H:i:s");
                $this->db->where("production_deadline <",$today_date)->where_in("status",array(0,1));
            }
        }

        $this->db->where("production_id",$this->session->userdata("admin_id"));
        $this->db->order_by("id","DESC");
        $tasks = $this->db->get("c_orders");

        return $tasks->result_object();
    }
}



?>
