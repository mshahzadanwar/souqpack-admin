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
class Vendorlogin extends ADMIN_Controller {
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

	public function index()
	{
		$this->form_validation->set_rules('email', 'Email', 'trim|required');
		$this->form_validation->set_rules('password', 'Password', 'trim|required|callback_check_user');
		$this->form_validation->set_message('required', 'This field is required.');
		if ($this->form_validation->run() == FALSE)
		{
			$this->data['title'] = 'Login';
			$this->load->view('backend/vendorlogin',$this->data);
		}
		else
		{
			$data = array(
				'email' => $this->input->post('email'),
				'password' => md5($this->input->post('password'))
			);
			$this->load->helper('string');
			$result = $this->db->where("email",$data["email"])->where("password",$data["password"])->get("vendors")->result_object()[0];

			if($result->status!=1)
			{
				$_SESSION["invalid"] = "You're account is not active yet";
				redirect(base_url()."admin/vendorlogin");
				exit();
			}
			$session_id = random_string('alnum', 20);
			$sess_array = array(
				'admin_id' => $result->id,
				'admin_name'=> $result->name,
				'admin_profile_pic'=> $result->profile_pic,
				'admin_email'=> $result->email,
				'is_Login'=>true,
				'sess_id'=>$session_id,
				"type_vendor"=>1,
			);
			$sessData['session_id'] = $session_id;
			$this->session->set_userdata($sess_array);



			redirect('admin/dashboard');
		}		
	}
	public function check_user($password)
	{
		if(!empty($password)){
			$data = array(
				'email' => $this->input->post('email'),
				'password' => md5($this->input->post('password'))
			);
			//print_r($loginData); die;
			$result = $this->db->where("email",$data["email"])->where("password",$data["password"])->get("vendors")->result_object()[0];
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

	public function reset_user(){


		$email = $this->input->post('email');
		
		$check_login = $this->db->query("SELECT * FROM `vendors` where `email` = '".$email."' AND `status` = '1'");
		
		$ceck_count = $check_login->num_rows();
		if($ceck_count > 0){
			$row = $check_login->result_object();
			
				$reset_token = gen_rand(100);

				$this->db->where("id",$row[0]->id)->update("vendors",array(
					"reset_token"=>$reset_token,
					"reset_request_time"=>(time()+600)
				));


				$this->load->library('email');
				$this->email->from('info@dedevelopers.com', settings()->site_title);
				$this->email->to($email); 
				$message = '
								<span style="font-family: arial;font-size:12px;line-height:18px;">DEAR <strong>'.$row[0]->first_name.' '.$row[0]->last_name.'</strong>,<br /><br/>
								Your have to request to reset your password, if that was not you, please ignore this email.<br><br>
								please click on below button to reset your password
									<br /><br />
									<a href="'.base_url().'bank/reset-password/'.$reset_token.'" target="_blank">
									<button style="background:#2a2a2a; border:1px solid #2a2a2a; color:#fff; border-radius:5px; cursor:pointer; float:left; padding:10px 40px;">VERIFY EMAIL</button>
									</a>
		<br />	<br />	<br />	
									Best Regards,
									<br>
									'.settings()->site_title.'	</span>						
							';

							

				$this->email->subject('FORGOT PASSWORD - : : - '.settings()->site_title);
				$this->email->message($message);
				$this->email->set_mailtype("html");
				$send = $this->email->send();
					
				$_SESSION["valid"] = "An email has been sent to your registered email address, please follow the instructions in email to reset your password";
				redirect(base_url()."admin/vendorlogin");
			
		}
		else {
			$_SESSION["invalid"] = "Email address not found";
			redirect(base_url()."admin/vendorlogin/reset");
		}
	}

	public function reset_password_real($token)
	{


		$exists = $this->db->where("reset_token",$token)->where("reset_request_time >=",time())->get("bank_users")->result_object()[0];


		if(!$exists)
		{
			$_SESSION["invalid"] = "Invalid Request";
			redirect(base_url()."bank/login");
			exit();
		}

		if(isset($_POST["password"])){











			$password = $this->input->post('password');
			$cpassword = $this->input->post('cpassword');

			if(strlen($password)<8)
			{
				$_SESSION["invalid"] = "Please enter at least 8 characters";
				redirect($_SERVER["HTTP_REFERER"]);
				exit();
			}

			if($password!=$cpassword)
			{
				$_SESSION["invalid"] = "Confirm password doesn't match";
				redirect($_SERVER["HTTP_REFERER"]);
				exit();
			}

			$this->db->where("id",$exists->id)->update("bank_users",array("password"=>md5($password),
				"reset_token"=>gen_rand(100),
				"reset_request_time"=>(time()-5000)
			));


			$_SESSION["valid"] = "Password has been updated, please login to continue";
			redirect(base_url()."bank/login");
		}
		else{


			if(!isset($_SESSION["loan_session"])){
				$this->load->view('backend/reset_password_real',$this->data);
			}
			else{
				redirect(base_url()."bank/dashboard");
			}
		}
	}
	public function reset()
	{
		$this->load->view('backend/reset_password',$this->data);
	}
	public function signup()
	{
		$this->load->view('backend/signup',$this->data);
	}
	public function signup_user(){
		
		$this->form_validation->set_rules('name', 'Name', 'trim|required');
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|is_unique[vendors.email]');
		$this->form_validation->set_rules('phone', 'Phone', 'trim|required');
		$this->form_validation->set_rules('password', 'Password', 'trim|required');
		
		if ($this->form_validation->run() == FALSE)
		{
			$this->data['title'] = 'Signup';
			$this->load->view('backend/signup',$this->data);
		}
		else
		{
			$arr = array(
				'name' => $this->input->post('name'),
				'email'=> $this->input->post('email'),
				'phone'=> $this->input->post('phone'),
				'created_at'=> date("Y-m-d H:i:s"),
				"profile_pic"=>"dummy_image.png",
				'password'=> md5($this->input->post('password')),
				'status'=> 0,
			);
			$this->db->insert('vendors',$arr);
			$_SESSION["valid"] = "You account has been created successfully, please login to access your account!";
			redirect(base_url()."admin/vendorlogin");
		}
	}
}