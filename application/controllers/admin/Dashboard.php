<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends ADMIN_Controller {

	function __construct()
	{
		parent::__construct();
		auth();
		$this->load->model('dashboard_model','dashboard');
	}
	
	public function index()
	{
		
		$this->data['title'] = 'Dashboard';
		$this->data['active'] = 'dashboard';
		$this->data['js'] = 'dashboard';
		$this->data['jsfile'] = 'js/dashboard_listing';
		if(is_designer()) {
			$this->data['content'] = $this->load->view('backend/dashboard_designer',$this->data,true);
		}
		else if(is_accountant() || is_stock())
			$this->data['content'] = $this->load->view('backend/dashboard_ac_sk',$this->data,true);

		else if(is_purchaser())
			$this->data['content'] = $this->load->view('backend/dashboard_pur',$this->data,true);
		else if(is_production())
			$this->data['content'] = $this->load->view('backend/dashboard_production',$this->data,true);
		else
			$this->data['content'] = $this->load->view('backend/dashboard',$this->data,true);
		$this->load->view('backend/common/template',$this->data);
		
	}
	//
	public function logout()
	{
		
		$this->session->sess_destroy();
			
		redirect(base_url());
		
	}
	
	public function auth_check(){
		
		
		$admin_data = get_admin_by_id($this->session->userdata('admin_id'))->row();
		
		$data['status'] = 1;
		
		if($admin_data->session_id != $this->session->userdata('sess_id')){
			
			$data['status'] = 0;
		}
		
		echo json_encode($data);
		
	}
	
	public function change_password()
	{
		
		$this->form_validation->set_rules('old','Old Password','trim|required|callback_old_password_check');
		$this->form_validation->set_rules('new_pass','New Password','trim|required|min_length[8]');
		$this->form_validation->set_rules('cpass','Confirm Password','trim|required');
		$this->form_validation->set_message('required','This field is required.');
		
		if($this->form_validation->run() === false){
			
			$this->data['title'] = 'Settings';
			$this->data['active'] = 'settings';
			$this->data['content'] = $this->load->view('change_password',$this->data,true);
			$this->load->view('common/template',$this->data);
			
		}else{
			
			$dbData['password'] = md5($this->input->post('new_pass'));
			$this->db->where('id',$this->session->userdata('admin_id'));
			$this->db->update('admin',$dbData);
			$this->session->set_flashdata('msg','Password updated successfully!');
			redirect('change-password');
			
		}
		
		
	}
	
	public function old_password_check($password)
	{
		$this->load->model('login_model','login');
		if(!empty($password)){
			
			$password = md5($password);
			
			$result = $this->login->get_admin_data_by_id_and_password($this->session->userdata('admin_id'),$password);
			
			if(!$result)
			{
				$this->form_validation->set_message('old_password_check', 'Invalid old password');
				return FALSE;
			}
			else
			{
				return TRUE ;
			}
		}else{
			$this->form_validation->set_message('old_password_check', 'This field is required.');
			return FALSE;
		}
	}
	
	public function notification(){
		
		$result = $this->dashboard->get_one_notification_of_admin();
		$count = $this->dashboard->get_notification_count();
		$notififcation = array();
		$notififcation['push'] = array();
		$notififcation['count'] = $count;
		if($result->num_rows() > 0){
			
			foreach($result->result() as $row){
				$dbData['push_in'] = 1;
				$this->db->where('id',$row->id);
				$this->db->update('notifications',$dbData);
				$arr = array();
				$this->data['data'] = $row;
				$arr['html'] = $this->load->view('notification',$this->data,true);
				$arr['id'] = 'notification-push-'.$row->id;
				$arr['top'] = $this->load->view('top_notification',$this->data,true);
				$notififcation['push'][] = $arr;
				
			}
		}
		echo json_encode($notififcation);
	}

	public function all_notifications()
	{
		$this->data['title'] = 'Notifications';
		$this->data['active'] = 'dashboard';
		$this->data['js'] = 'listing';
		$this->data['jsfile'] = 'notifications_lead';
		$this->data['notifi'] = $this->app->get_all_notification();
		$this->data['content'] = $this->load->view('backend/notifications/notifications',$this->data,true);
		$this->load->view('backend/common/template',$this->data);
	}
	public function detail($id){
		
		$this->data['title'] = 'Notifications Detail';
		$this->data['active'] = 'dashboard';
		$this->data['jsfile'] = 'notifications_lead';
		$this->data['notificationss'] = $this->app->get_quote_by_id($id)->row();
		$dbData['read_it'] = $this->data['notificationss']->read_it==1?0:1;
		$this->db->where('id', $id);

		$this->db->update('notifications', $dbData);
		$this->data['content'] = $this->load->view('backend/notifications/detail',$this->data,true);
		$this->load->view('backend/common/template',$this->data);
	}
	public function delete_notificaton($id)
	{
		$dbData['is_delete'] = 1;
		$this->db->where('id', $id);

		$this->db->update('notifications', $dbData);
		redirect($_SERVER['HTTP_REFERER']);
	}
	
	// LANGUAGE SECTION
	public function languages()
	{
		
		$this->data['title'] = 'Languages';
		$this->data['active'] = 'languages';
		$this->data['js'] = 'dashboard';
		$this->data['languages'] = $this->dashboard->get_language_lists();
		$this->data['content'] = $this->load->view('backend/languages/listing',$this->data,true);
		$this->load->view('backend/common/template',$this->data);
		
	}
	public function add_language()
	{
	
		$this->data['title'] = 'Add New Language';
		$this->data['active'] = 'dashboard';
		$this->data['js'] = 'dashboard';
		$this->data['content'] = $this->load->view('backend/languages/add',$this->data,true);
		$this->load->view('backend/common/template',$this->data);
		
	}
	public function read_all()
	{
		$notifs = notifications();
		$column = notif_read_column();

		$where_in = array(-1);
		foreach($notifs as $where){
			$where_in[] = $where->id;
		}

		$this->db->where_in("id",$where_in)->update('notifications',array($column=>1));
		$this->redirect_back();
	}
	public function open_notif($id)
	{
		$notif = $this->db->where("id",$id)->get("notifications")->result_object()[0];
		if($notif->is_normal == 0) {
			
			$task = $this->db->where("order_id",$notif->order_id)->order_by("id","DESC")->limit(1)->get("designer_tasks")->result_object()[0];
			$url = base_url()."admin/view-c_order/".$notif->order_id;
			//print_r($notif);
			if(is_stock() || is_purchaser()){
				
				$taskss = $this->db->query("SELECT * FROM `cost_items` WHERE `cost_id` = ".$notif->order_id)->result_object()[0];

				$url = base_url()."admin/view-cost/".$taskss->cost_id;
			}
			
			if(is_designer())
			{
				$url = base_url()."admin/view-task/".$task->id;
			}
			if(is_production())
			{
				$url = base_url()."admin/view-task/".$notif->order_id;
			}	
		}
		else {
			$url = base_url()."admin/view-invoice-template/0/".$notif->order_id."?type=header";
		}
		$column = notif_read_column();
		$this->db->where("id",$id)->update("notifications",array($column=>1));
		
		redirect($url);
	}

	public function redirect_new_product($id)
	{
		$notif = $this->db->where("id",$id)->get("notifications")->result_object()[0];
		$url = base_url()."admin/edit-product/".$notif->order_id;
			

		$column = notif_read_column();
		$this->db->where("id",$id)->update("notifications",array($column=>1));

		redirect($url);
	}
	public function redirect_new_purchase($id)
	{
		$notif = $this->db->where("id",$id)->get("notifications")->result_object()[0];
		$url = base_url()."admin/edit-purchase/".$notif->order_id;
			

		$column = notif_read_column();
		$this->db->where("id",$id)->update("notifications",array($column=>1));

		redirect($url);
	}
}
