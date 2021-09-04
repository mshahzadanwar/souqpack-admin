<?php
/**
 * 
 * [Login_model hanldes login of admin]
 *
 * @member Login_model
 * @member get_admin_by_email
 * @member get_admin_data_by_id_and_password
 *
 * @since 1.0
 * @author DeDevelopers
 * @copyright Copyright (c) 2019, DeDevelopers, https://dedevelopers.com
 */


class Login_model extends CI_Model{
	/**
	 * [get_admin_data_by_email_and_password returns false if provided credentials are 
	 * invalid, and returns object of user if credentials are correct, NOTE this
	 * function deosn't login user, it just checks the credentials]
	 * @param  [array] $params [array(email=>email,password=>password)]
	 * @return [object|boolean]         [returns false if provided credentials are 
	 * invalid, and returns object of user if credentials are correct]
	 *
	 *
	 * @since 1.0
	 * @author DeDevelopers
	 * @copyright Copyright (c) 2019, DeDevelopers, https://dedevelopers.com
	 */
	public function get_admin_data_by_email_and_password($params)
	{
		
		$result = $this->db->query('
			SELECT *
			FROM admin
			WHERE password = "'.$params['password'].'"
			AND is_deleted = 0
			AND (email = "'.$params['email'].'" OR username = "'.$params['email'].'")'
		);
		if($result->num_rows()>0){
			return $result;
		}else{
			return false;
		}
	}
	
	/**
	 * [get_admin_by_email returns object of admin of provided email, otherwise false]
	 * @param  [string] $email [description]
	 * @return [object|boolean]        [object if email matched in db, 
	 * false otherwise]
	 *
	 * @since 1.0
	 * @author DeDevelopers
	 * @copyright Copyright (c) 2019, DeDevelopers, https://dedevelopers.com
	 */
	public function get_admin_by_email($email){
		
		$result = $this->db->query('
			SELECT *
			FROM admin
			WHERE email = "'.$email.'"
		');
		
		if($result->num_rows()>0){
			return $result;
		}else{
			return false;
		}
		
		
	}
	/**
	 * [get_admin_data_by_id_and_password provides complete admin object by id
	 * and password.]
	 * @param  [int] $id       [id of admin in database]
	 * @param  [string] $password [admin hash of password in database]
	 * @return [object|boolean]           [returns object of user if ID and password
	 * match, or return false if not.]
	 *
	 * @since 1.0
	 * @author DeDevelopers
	 * @copyright Copyright (c) 2019, DeDevelopers, https://dedevelopers.com
	 */
	public function get_admin_data_by_id_and_password($id,$password)
	{
		
		$result = $this->db->query('
			SELECT *
			FROM admin
			WHERE password = "'.$password.'"
			AND id = '.$id.''
		);
		
		if($result->num_rows()>0){
			return $result;
		}else{
			return false;
		}
	}
}


?>
