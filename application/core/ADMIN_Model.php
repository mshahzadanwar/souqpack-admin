<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ADMIN_Model extends CI_Model {
	/**
	 *
	 * This class helps inherited classes contain some pre-defined functions
	 * 
	 */
	protected 	$tables	;
	protected 	$data  ;	
	
	//Initialize
	public function __construct()
	{
        	parent::__construct();
			
			//initialize 
			$this->data		= array();
			$this->tables	= array(  
									"admin"    =>    "admin",
									"admin_login_history"    =>    "admin_login_history",
									"admin_roles"    =>    "admin_roles",
									"affiliates"    =>    "affiliates",
									"attachment_type"    =>    "attachment_type",
									"attachments"    =>    "attachments",
									"bank_payment_recipts"    =>    "bank_payment_reci",
									"blog"    =>    "blog",
									"blog_categories"    =>    "blog_categories",
									"blog_images"    =>    "blog_images",
									"blog_tags"    =>    "blog_tags",
									"brands"    =>    "brands",
									"c_orders"    =>    "c_orders",
									"cat_images"    =>    "cat_images",
									"cat_options"    =>    "cat_options",
									"categories"    =>    "categories",
									"category_images"    =>    "category_images",
									"chat_record"    =>    "chat_record",
									"chat_users"    =>    "chat_users",
									"ci_sessions"    =>    "ci_sessions",
									"cities"    =>    "cities",
									"company_details"    =>    "company_details",
									"corder_threads"    =>    "corder_threads",
									"cost_items"    =>    "cost_items",
									"costs"    =>    "costs",
									"countries"    =>    "countries",
									"coupons"    =>    "coupons",
									"designer_tasks"    =>    "designer_tasks",
									"email_template"    =>    "email_template",
									"faqs"    =>    "faqs",
									"footers"    =>    "footers",
									"invoice_templates"    =>    "invoice_templates",
									"languages"    =>    "languages",
									"newsletters"    =>    "newsletters",
									"notification_type"    =>    "notification_type",
									"notifications"    =>    "notifications",
									"notificationss"    =>    "notificationss",
									"offers"    =>    "offers",
									"order_products"    =>    "order_products",
									"order_reviews"    =>    "order_reviews",
									"orders"    =>    "orders",
									"pages"    =>    "pages",
									"payment_methods"    =>    "payment_methods",
									"product_c_vars"    =>    "product_c_vars",
									"product_images"    =>    "product_images",
									"product_units"    =>    "product_units",
									"products"    =>    "products",
									"purchases"    =>    "purchases",
									"quantity_units"    =>    "quantity_units",
									"questionnaires"    =>    "questionnaires",
									"questions"    =>    "questions",
									"ready_data"    =>    "ready_data",
									"redeemed_coupons"    =>    "redeemed_coupons",
									"refund"    =>    "refund",
									"regions"    =>    "regions",
									"search_history"    =>    "search_history",
									"seo_settins"    =>    "seo_settins",
									"settings"    =>    "settings",
									"shipments"    =>    "shipments",
									"shipping_addresses"    =>    "shipping_addresses",
									"site_languages"    =>    "site_languages",
									"sliders"    =>    "sliders",
									"states"    =>    "states",
									"store_followers"    =>    "store_followers",
									"stores"    =>    "stores",
									"task_threads"    =>    "task_threads",
									"temp_phones"    =>    "temp_phones",
									"tmp_rows"    =>    "tmp_rows",
									"tmp_table"    =>    "tmp_table",
									"tmp_variations"    =>    "tmp_variations",
									"users"    =>    "users",
									"v_rows"    =>    "v_rows",
									"v_table"    =>    "v_table",
									"v_variations"    =>    "v_variations",
									"variations"    =>    "variations",
									"vendors"    =>    "vendors",
									"wishlist"    =>    "wishlist",

									
			);
	}
	
	/*Fields setter*/
	public function set_fields($data)
	{
		$this->data = $data;
	} 
	 
	
	public function set_field($key,$value)
	{
		$this->data [$key] = $value ; 
	}
	
	public function update_fields($data)
	{
		foreach( $data as $key=>$value )
		{
			$this->data [$key] = $value ;
		}	
	}
	
	/*Field getter*/
	public function get_field( $field_name )
	{
		if ( array_key_exists( $field_name, $this->data ) )
			return $this->data [ $field_name  ];
		return false;	
	}

	
	//insert query
	public function insert($table_name,$data)
	{
		$this->db->insert($table_name,$data);
		return $this->db->insert_id();
	}
	
	//update query
	public function update($table_name,$data,$where_arr)
	{
		$this->db->where($where_arr);
		//print_r($data);exit();
		$this->db->update($table_name,$data);
	}
	
	//Delete query
	public function delete ($table_name,$where_arr)
	{
		$this->db->where($where_arr);
		$this->db->delete($table_name);
	}
	
	//Select query
	public function query($query)
	{
		return $this->db->query($query);	
	}
	
	public function select_query( $fields,$table_name,$where = array() )
	{
		
		$this->db->select($fields);		
		$this->db->from($table_name);
		
		if ( !empty($where) )
		{
				$this->db->where($where);
		}
		
		return $this->db->get();
	}
	
	//Select query and return result
	public function query_result($query)
	{ 
		$query =  $this->db->query($query);
		if ( $query->result_id->num_rows > 0 )
			return $query->result();
		
		return array();	
	}
	public function query_array($query)
	{ 
		$query =  $this->db->query($query);
		if ( $query->result_id->num_rows > 0 )
			return $query->result_array();
		
		return array();	
	}

	public function string_result($query)
	{ 
		$query =  $this->db->query($query);
		if ( $query->result_id->num_rows > 0 )
			return $query->row();	
	}

	public function array_result($query)
	{ 
		$query =  $this->db->query($query);
		if ( $query->result_id->num_rows > 0 )
			return $query->row_array();	
	}
	
	//public function 	
	
	//Insert batch records
	public function insert_batch($table_name,$data)
	{
		$this->db->insert_batch($table_name, $data);		 	
		return $this->db->insert_id();		 	
	}
	
	//Generate an array for drop down
	public function get_drop_down( $query, $key, $value, $name )
	{
		$query 		= $this->db->query($query);
		$records = $query->result_array(); //array of arrays
		
		$data=array(""=>"Select ".$name." ");
		
		
		foreach ($records as $row)
    	{
      	$data[ $row[$key] ] = $row[ $value ];
    	}
    	
		return $data;
	}
	
	/* generate random password*/
	public function createRandomPassword() {
		$chars = "abcdefghijkmnopqrstuvwxyz023456789";
		
		srand((double)microtime()*1000000);
		
		$i = 0;
		
		$pass = '' ;
		
		while ($i <= 7) {
		
			$num = rand() % 33;
		
			$tmp = substr($chars, $num, 1);
		
			$pass = $pass . $tmp;
		
			$i++;
		
		}
		return $pass;
	}
	
	/* send mail*/
	public function sendmail($to,$subject,$emailBody) {
		$headers = "MIME-Version: 1.0\r\n"; 
		$headers .= "Content-type: text/html; charset=ISO-8859-1\r\n";
		$headers .= "From: Emall <noreply@emall.com> \r\n";
		$headers .= 'X-Mailer: PHP/' . phpversion();
		mail($to, $subject, $emailBody, $headers);
	}
	
}