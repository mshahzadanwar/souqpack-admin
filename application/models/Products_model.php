<?php

class Products_model extends CI_Model{
	
	public function get_all_products($field='id',$order = 'DESC')
	{
		if(is_vendor()){
	        $result = $this->db->query('
				SELECT *
				FROM products
				WHERE is_deleted  = 0
				AND vendor_id = '.vendor_id().'
				AND lparent  = 0
				ORDER BY '.$field.' '.$order.'
				'
	        );
	    }else{

	    $wehere = where_region_id();

	    if(isset($_SESSION['filter'])){
	    	if(isset($_SESSION['filter_cats'])){
	    		if($_SESSION['filter_cats'] == ""){}
	    		else 
	    		{

	    			$cat_status = $this->db->query("SELECT title,id,parent,lparent FROM categories where  lparent = 0 AND id =".$_SESSION['filter_cats'])->result_object();
	    			if($cat_status[0]->parent == 0){
	    				$arr_sub[] = -1;
	    				$sub_cat = $this->db->query("SELECT title,id,parent,lparent FROM categories where  lparent = 0 AND parent =".$_SESSION['filter_cats'])->result_object();
	    					foreach ($sub_cat as $key => $sub) {
	    						$arr_sub [] = $sub->id;
	    					}
	    					$this->db->where_in("products.category",$arr_sub);
	    				//echo $this->db->last_query();die;
	    			}
	    			else {
	    				
	    				$this->db->where("products.category",$_SESSION['filter_cats']);
	    			}

	    		}
	    	}
	    	if(isset($_SESSION['status_change'])){
	    		if($_SESSION['status_change'] == ""){

	    		}else {
	    			$this->db->where("products.status",$_SESSION['status_change']);
	    		}
	    	}
	    } else {

	    }
	   
		
		$this->db->where("products.lparent",0);
		$this->db->where("products.is_deleted",0);
		$this->db->select("products.*");
		$this->db->order_by("products.id","DESC");

		$this->db->from("products");

		$this->db->join("product_units","products.id=product_units.product_id","LEFT");
		// $this->db->where_in("product_units.region_id",explode(",",$wehere));
		$this->db->select("product_units.id as no_use");


		$result = $this->db->get();
		//echo $this->db->last_query();
	   //  	$result = $this->db->query('
				// SELECT *
				// FROM products
				// WHERE is_deleted  = 0
				// AND lparent  = 0
				// ORDER BY '.$field.' '.$order.'
				// '
	   //      );
	    }

        return $result;
	}

	public function get_all_trash_products($field='id',$order = 'DESC')
	{
		if(is_vendor()){
	        $result = $this->db->query('
				SELECT *
				FROM products
				WHERE is_deleted  = 1
				AND lparent  = 0
				AND vendor_id = '.vendor_id().'
				ORDER BY '.$field.' '.$order.'
				'
	        );
    	}
        else{
        	$result = $this->db->query('
				SELECT *
				FROM products
				WHERE is_deleted  = 1
				AND lparent  = 0
				ORDER BY '.$field.' '.$order.'
				'
	        );
        }

        return $result;
	}

    public function get_all_active_products($field='id',$order = 'DESC')
    {
    	if(is_vendor()){
	        $result = $this->db->query('
				SELECT *
				FROM products
				WHERE is_deleted  = 0
				AND status  = 1
				AND lparent  = 0
				AND vendor_id = '.vendor_id().'
				ORDER BY '.$field.' '.$order.'
				'
	        );
	    }else{
	    	$result = $this->db->query('
				SELECT *
				FROM products
				WHERE is_deleted  = 0
				AND status  = 1
				AND lparent  = 0
				ORDER BY '.$field.' '.$order.'
				'
	        );
	    }

        return $result;
    }
	public function get_product_by_title($title)
	{
		if(is_vendor()){
	        $result = $this->db->query('
				SELECT *
				FROM products
				WHERE is_deleted  = 0
				AND lparent  = 0
				AND vendor_id = '.vendor_id().'
				AND title = "'.$title.'"
				'
	        );
	    }else{
	    	$result = $this->db->query('
				SELECT *
				FROM products
				WHERE is_deleted  = 0
				AND lparent  = 0
				AND title = "'.$title.'"
				'
	        );
	    }

        return $result;
	}

	public function get_product_by_id($id){
		if(is_vendor()){

	        $result = $this->db->query('
				SELECT *
				FROM products
				WHERE is_deleted  = 0
				AND id = '.$id.'
				AND vendor_id = '.vendor_id().'
				AND lparent  = 0
				ORDER BY id DESC
				'
	        );
	    }else{
	    	 $result = $this->db->query('
				SELECT *
				FROM products
				WHERE is_deleted  = 0
				AND id = '.$id.'
				AND lparent  = 0
				ORDER BY id DESC
				'
	        );
	    }

        if($result->num_rows()>0){

            return $result->row();
        }else{

            return false;
        }
    }
    public function get_product_by_lang($lang_id,$id){
    	
	    	$this->db->group_start();
	    	$this->db->where("lang_id",$lang_id);
	    	$this->db->group_end();
	    	$this->db->group_start();
	    	$this->db->where("id",$id);
	    	$this->db->or_where("lparent",$id);
	    	$this->db->group_end();
	    	return $this->db->get('products')->result_object()[0];
	    
    }
}


?>
