<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Blog_model extends ADMIN_Model{

public function __construct()
	{
		parent::__construct();	  	
	}


	/*Blog Datatable*/
	public function get_all_blog()
		{
			if ($this->data['order_by']=='') {
				$orderBy = "ORDER BY b.blog_date  ASC ";
			}else{
				$orderBy = "ORDER BY ".$this->data['order_by']; 
			}

			$query="SELECT b.*
					FROM blog as b  
					WHERE b.is_delete = 0 
					AND b.blog_language_id=2
					AND 1=1".$this->data['search_text']." 
					".$orderBy."
					LIMIT ".$this->data['limit_start'].",".$this->data['limit_length']."";
			
			$query_count="SELECT COUNT(b.blog_id) as total 
				FROM blog as b  
				WHERE b.is_delete=0 
				AND b.blog_language_id=2
				AND 1=1 ".$this->data['search_text']; 
		
			$result = $this->query_result($query);
			$total  = $this->query_result($query_count);			
			if ($result>0 && $total>0) 
				{ 
					return array('total'=>$total[0]->total,"result"=>$result) ;	
				}
			return false;
		}
 
	/*Blog Details*/
	public function get_blog_detail()
		{
			$result  = $this->query("SELECT b.*,b.blog_id as id
				FROM blog as b
				WHERE b.blog_id='".$this->data['id']."' ") ;

			if ( $result->result_id->num_rows > 0 )
			{ 	
				$data=[]; 
				$data['en'] = $result->row_array();		 

				$result1  = $this->query("SELECT b.*,b.blog_id as id
					FROM blog as b
					WHERE b.blog_language_master_id='".$this->data['id']."'
					AND b.blog_language_id=1 ") ;

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
  
	/*Blog List*/
	public function get_all_blog_list()
		{
			$result  = $this->query("SELECT b.*
				FROM blog as b
				WHERE b.is_delete=0 
				ORDER BY b.blog_name ASC ") ;

			if ( $result->result_id->num_rows > 0 )
			{
				return $result->result();		
			}
	    	else
		   	{
	   	 	return false;
	   		}
		}
		 
	public function slug_available($title,$id='')
		{	 
			if (!empty($id)) { 
				$query = "SELECT blog_id
					FROM blog
					WHERE blog_slug='".$title."'
					AND blog_id<>".$id; 
			}else{
				$query = "SELECT blog_id
					FROM blog
					WHERE blog_slug='".$title."'"; 
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