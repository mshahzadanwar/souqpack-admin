<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Category_model extends ADMIN_Model{

public function __construct()
	{
		parent::__construct();	  	
	}


	/*Category Datatable*/
	public function get_all_category()
		{
			if ($this->data['order_by']=='') {
				$orderBy = "ORDER BY t.category_name  ASC ";
			}else{
				$orderBy = "ORDER BY ".$this->data['order_by']; 
			}

			$query="SELECT t.*,l.title as language,l.title
					FROM blog_categories as t  
					LEFT JOIN languages as l ON l.id=t.category_language_id
					WHERE t.is_delete = 0 
					AND t.category_language_id=2
					AND 1=1".$this->data['search_text']." 
					".$orderBy."
					LIMIT ".$this->data['limit_start'].",".$this->data['limit_length']."";
			
			$query_count="SELECT COUNT(t.category_id) as total 
				FROM blog_categories as t  
				LEFT JOIN languages as l ON l.id=t.category_language_id
				WHERE t.is_delete=0 
				AND t.category_language_id=2
				AND 1=1 ".$this->data['search_text']; 
		
			$result = $this->query_result($query);
			$total  = $this->query_result($query_count);			
			if ($result>0 && $total>0) 
				{ 
					return array('total'=>$total[0]->total,"result"=>$result) ;	
				}
			return false;
		}
 
	/*Category Details*/
	public function get_category_detail()
		{
			$result  = $this->query("SELECT t.*,t.category_id as id
				FROM blog_categories as t
				WHERE t.category_id='".$this->data['id']."' ") ;

			if ( $result->result_id->num_rows > 0 )
			{ 	
				$data=[]; 
				$data['en'] = $result->row_array();		 

				$result1  = $this->query("SELECT t.*,t.category_id as id
					FROM blog_categories as t
					WHERE t.category_language_master_id='".$this->data['id']."'
					AND t.category_language_id=1 ") ;

				if ( $result1->result_id->num_rows > 0 ){
					$data['ar'] = $result1->row_array();		 
				}else{
					$data['ar'] = [];		
				}
				return $data;
			}
	    	else
		   	{
	   	 		return false;
	   		}
		}
  
	/*Category List*/
	public function get_all_category_list()
		{
			$result  = $this->query("SELECT t.*
				FROM blog_categories as t
				WHERE t.is_delete=0   
				AND t.category_language_id=2	
				ORDER BY t.category_name ASC ") ;

			if ( $result->result_id->num_rows > 0 )
			{
				return $result->result();		
			}
	    	else
		   	{
	   	 	return false;
	   		}
		}
		 
	/*Category Available*/
	public function category_available($title,$id='')
		{	 
			if (!empty($id)) { 
				$query = "SELECT category_id
					FROM blog_categories
					WHERE is_delete=0
					AND category_name='".$title."'
					AND category_id<>".$id; 
			}else{
				$query = "SELECT category_id
					FROM blog_categories
					WHERE is_delete=0
					AND category_name='".$title."'"; 
			} 
			$result =$this->query($query);
			
			if ( $result->result_id->num_rows > 0)
			{
				return $result->row();	
			}
			return false;
		}


		public function slug_available($title,$id='')
		{	 
			if (!empty($id)) { 
				$query = "SELECT category_id
					FROM blog_categories
					WHERE is_delete=0
					AND category_slug='".$title."'
					AND category_id<>".$id; 
			}else{
				$query = "SELECT category_id
					FROM blog_categories
					WHERE is_delete=0
					AND category_slug='".$title."'"; 
			} 
			$result =$this->query($query);
			
			if ( $result->result_id->num_rows > 0)
			{
				return $result->row();	
			}
			return false;
		}

	 
 

}

?>