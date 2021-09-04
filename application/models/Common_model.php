<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Common_model extends ADMIN_Model{
 
	public function __construct()
	{
		  	parent::__construct();
		  	
	} 
	public function save($tableName)
	{			

		 $user_id = $this->insert( $this->tables[$tableName], $this->data );
		 //echo $this->db->last_query();exit;
		 return $user_id;	
	}
    public function save_batch($tableName)
    {

        $user_id = $this->insert_batch( $this->tables[$tableName], $this->data );
        //echo $this->db->last_query();exit;
        return $user_id;
    }
	 
	public function updateData($tableName, $where )
	{
		 
		$return = $this->update( $this->tables[$tableName],$this->data, $where ); 
		return $return;
	}
	 
	public function remove($tableName, $where )
	{
		return $this->delete( $this->tables[$tableName],$where );
	} 
	public function deleteMultiple($tableName, $where )
	{
		
		return $this->delete_multiple( $this->tables[$tableName],$where );
	}
	
	
}