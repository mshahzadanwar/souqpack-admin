<?php

class Custorders_model extends CI_Model{
	
	public function get_all_orders($field='id',$order = 'DESC')
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
        	//$wherehre = where_region_id();
        	//$this->db->where_in("region_id",explode(',', $wherehre));
        	$this->db->where("is_deleted",0);
        	if($_GET["s_status"]!="")
        	$this->db->where("status",$_GET["s_status"]);
        	if($_GET["customer_id"]!="")
        		$this->db->where("user_id",$_GET["customer_id"]);
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

        if(isset($_SESSION['filter_web']))
        {
        	if(isset($_SESSION['status_payment']) && $_SESSION['status_payment'] != ""){
        		$query = " AND payment_done = '".$_SESSION['status_payment']."'";
        	}
        	//if($_SESSION['status_web_change'] == ""){
        	if(isset($_SESSION['status_web_change']) && $_SESSION['status_web_change'] != ""){
        		$query .= " AND from_web_mobile = '".$_SESSION['status_web_change']."'";
        	}
        	$result = $this->db->query('
				SELECT *
				FROM c_orders
				WHERE is_deleted  = 0
				'.$query.'
				
				ORDER BY '.$field.' '.$order.'
				'
	        );
	       //echo $this->db->last_query();
        }
       
        return $result;

	}

	

    public function get_all_active_orders($field='id',$order = 'DESC')
    {
        $result = $this->db->query('
			SELECT *
			FROM c_orders
			WHERE is_deleted  = 0
			AND status  = 1
			ORDER BY '.$field.' '.$order.'
			'
        );

        return $result;

    }
	public function get_order_by_title($title)
	{
        $result = $this->db->query('
			SELECT *
			FROM c_orders
			WHERE is_deleted  = 0
			AND title = "'.$title.'"
			'
        );

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


	

	
}


?>
