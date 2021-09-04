<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('test_method'))
{
	function get_message($message_key)
	{
		switch($message_key)
		{
			case "authentication_login_success":
					return "Logged in successfully.";
			case "authentication_login_error":
					return "Invalid Username or Password.";
		    case "authentication_login_error_front_end":
					return "Invalid email or password.";
			case "authentication_new_user_added":
					return "User registered successfully.";
			case "authentication_register_email_already_exists":
					return "Email address already exists.";
			case "authentication_register_phone_already_exists":
					return "Phone number already exists.";
			case "authentication_admin_change_password_fail":
					return "Invalid current password.";
			case "authentication_admin_profile_update_success":
					return "Your account information has been updated successfully.";
			case "authentication_invalid_id":
					return "Invalid user id.";
			case "compare_password_fail":
					return "Confirm password does not match.";
			case "update_password_success":
					return "Your password has been updated successfully.";
			case "invalid_password":
					return "Invalid Current Password.";
			case "invalid_phonenumber":
					return "Invalid Phone Number.";		
			case 'forget_password_blank':
					return "Please enter email address.";
			case "operation_unsuccessful.":
					return "Operation unsuccessful.";			
			case "administrator_email_does_not_exists":
					return "Login failed, email does not exists.";	
			case "valid_email":
					return "Email address does not exists.";			
			case "authentication_login_error":
					return "Invalid Email or password.";
			case "authentication_logout_success":
					return "Logout successfully.";
			case "project_added":
					return "Project added successfully.";
			case "project_updated":
					return "Project updated successfully.";
			case "project_delete_success":
					return "Project deleted successfully.";
			case "project_success":
					return "Project deleted successfully.";
			case "no_check_box_select":
					return "Please select atleast one checkbox.";
			case "selected_project_delete_success":
					return "Selected project deleted successfully.";
			case "project_already_exists":
					return "Project already exists.";
			case "loyalty_added":
					return "Loyalty added successfully.";					
			case "invalid_reference_number":
					return "Invalid reference";
			case "profile_update_success";
					return"Profile updated successfully";
			case "user_member_added":
					return"User added successfully.";
			case "user_member_updated":
					return"User updated successfully.";		
			case "valid_profile_image":
					return "Please upload a valid format file jpg, jpeg or png in profile image.";								
			case "user_delete_success":
					return "user deleted successfully.";
			case "selected_user_delete_success":
					return "Selected user deleted successfully.";					
			case 'selected_users_status_success':
					return "Selected users status changed successfully.";
			case 'user_status_change_success':
					return "User status changed successfully.";			
					
			default :
				return "";
		}
	}
	
	function file_required($file)
	{
	    if($file['size']===0)
	    {
	        $this->CI->form_validation->set_message('file_required','Uploading a file for %s is required.');
	        return FALSE;
	    }
	    return TRUE;
	}
}
