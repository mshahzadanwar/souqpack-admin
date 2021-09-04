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
class Forgotcompany extends ADMIN_Controller {
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
		
		$this->form_validation->set_message('required', 'This field is required.');
		if ($this->form_validation->run() == FALSE)
		{
			$this->data['title'] = 'Forgot Password';
			$this->load->view('backend/forgot',$this->data);
		}
		else
		{
			$data = array(
				'email' => $this->input->post('email'),
			);
			$this->load->helper('string');
			$email = $this->input->post("email");
			$result = $this->db->where("email",$email)->get('vendors')->result_object()[0];
			if($result)
			{
				$email = $this->input->post('email');
				$this->load->helper('string');
				$password = random_string('alnum', 8);
				$dbData['password'] = md5($password);
				$this->db->where('id',$result->id);
				$this->db->update('vendors',$dbData);
				$this->load->library('email');

				$this->email->from(settings()->email, settings()->site_title);
				$this->email->to($result->email);
				$this->email->subject('Recover Password');
				$this->email->message('Hi your password is: <strong>'.$password.'</strong>');

				$this->email->send();
			}
			$this->session->set_userdata("email_sent","yes");
			redirect('admin/forgotcompany');
				
		}		
	}
}
