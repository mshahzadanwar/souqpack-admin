<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * [Login controller of login areas of admin]
 *
 * @member [index]
 * @member [check_user]
 * @member [reset_password]
 * @member [verify_email]
 */
class Login extends ADMIN_Controller {
	/**
	 * [__construct checks if logged in or not, loads login model]
	 */
	function __construct()
	{
		parent::__construct();
		if($this->uri->segment(2)!="logout")
		is_session();
		$this->load->model('Login_model','login');
	}
	/**
	 * [index loads login page, handels submit action from login page, provides
	 * validation on email and password of type required and trim, also checks if
	 * provided email are perfect math in database]
	 * @return [view|redirect] [returns view of login page if not logged in
	 * and redirects user to dashboard if logged in.]
	 */
	public function index()
	{
		$this->form_validation->set_rules('email', 'Email', 'trim|required');
		$this->form_validation->set_rules('password', 'Password', 'trim|required|callback_check_user');
		$this->form_validation->set_message('required', 'This field is required.');
		if ($this->form_validation->run() == FALSE)
		{
			$this->data['title'] = 'Login';
			$this->load->view('backend/login',$this->data);
		}
		else
		{
			$data = array(
				'email' => $this->input->post('email'),
				'password' => md5($this->input->post('password'))
			);
			$this->load->helper('string');
			$result = $this->login->get_admin_data_by_email_and_password($data)->row();
			$session_id = random_string('alnum', 20);
			$sess_array = array(
				'admin_id' => $result->id,
				'admin_name'=> $result->name,
				'admin_username'=> $result->username,
				'admin_profile_pic'=> $result->admin_profile_pic,
				'admin_email'=> $result->email,
				'is_Login'=>true,
				'sess_id'=>$session_id
			);
			$sessData['session_id'] = $session_id;
			$this->db->where('id',$result->id);
			$this->db->update('admin',$sessData);
			$this->session->set_userdata($sess_array);
			include('./application/third_party/Browser.php');
			$browser = new Browser();
			$dbData['ip'] = $_SERVER['REMOTE_ADDR'];
			$dbData['admin_id'] = $result->id;
			$dbData['date_time'] = date('Y-m-d H:i:s');
			$dbData['browser'] = $browser->getBrowser().' Version = '.$browser->getVersion();
			$dbData['platfrom'] = $browser->getPlatform();
			$dbData['user_agent'] = $browser->getUserAgent();
			$this->db->insert('admin_login_history',$dbData);
			redirect('admin/dashboard');
				
		}		
	}
	/**
	 * [check_user checks user in database, takes $password as param, and email 
	 * as $_POST]
	 * @param  [string] $password [password string of user submitted value]
	 * @return [boolean]           [true if email and password are perfect match
	 * false otherwise]
	 */
	public function check_user($password)
	{
		if(!empty($password)){
			$data = array(
				'email' => $this->input->post('email'),
				'password' => md5($this->input->post('password'))
			);
			//print_r($loginData); die;
			$result = $this->login->get_admin_data_by_email_and_password($data);
			if(!$result)
			{
				$this->form_validation->set_message('check_user', 'Invalid email/username or password');
				return FALSE;
			}
			else
			{
				return TRUE ;
			}
		}else{
			$this->form_validation->set_message('check_user', 'This field is required.');
			return FALSE;
		}
	}
	/**
	 * [reset_password displays reset password page, handles submit action
	 * sends email to user to reset password, stores token in databasae and then
	 * redirects user to login page]
	 * @return [view|redirect] [if submitted email is matched in db, returns redirect
	 * otherwise reset page view]
	 */
	public function reset_password()
	{
		$this->form_validation->set_rules('rest_email', 'Email', 'trim|required|valid_email|callback_verify_email');
		$this->form_validation->set_message('required', 'This field is required.');
		$this->form_validation->set_message('valid_email', 'Please enter valid email address.');
		if ($this->form_validation->run() == FALSE)
		{
			$this->data['title'] = 'Reset Password';
			$this->load->view('login',$this->data);
		}
		else
		{
			$email = $this->input->post('rest_email');
			$this->load->helper('string');
			$result = $this->login->get_admin_by_email($email)->row();
			$password = random_string('alnum', 8);
			$dbData['password'] = md5($password);
			$this->db->where('id',$result->id);
			$this->db->update('admin',$dbData);
			$this->load->library('email');

			$this->email->from('noreply@dedevelopers', 'DeDevelopers');
			$this->email->to($result->email);
			$this->email->subject('Recover Password');
			$this->email->message('Hi your DeDevelopers admin panel password is: <strong>'.$password.'</strong>');

			$this->email->send();
			redirect('admin/login');
				
		}		
	}
	/**
	 * [verify_email takes email as param and checks if its valid, then checks it 
	 * in our db, then returns message accordingly]
	 * @param  [string] $email [email submitted by user]
	 * @return [boolean]        [true of email is valid and matched in our db
	 * false if not valid, empty or not found in our database]
	 */
	public function verify_email($email)
	{
		if(!empty($email)){
			if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
				$result = $this->login->get_admin_by_email($email);
			
				if(!$result)
				{
					$this->form_validation->set_message('verify_email', 'This email does not exist in our system');
					return FALSE;
				}
				else
				{
					return TRUE ;
				}
			}else{
				$this->form_validation->set_message('verify_email', 'Please enter valid email address.');
				return FALSE;
			}
		}else{
			$this->form_validation->set_message('verify_email', 'This field is required.');
			return FALSE;
		}
	}
	/**
	 * [logout logs out the admin]
	 * @return [redirect] [redirects user to login page]
	 */
	public function logout()
	{	
		$this->session->sess_destroy();

		// echo 1;exit;
		redirect(base_url()."admin/login");
	}
}
