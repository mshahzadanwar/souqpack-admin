<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Settings extends ADMIN_Controller {
	/**
	 * [__construct constructs parent object and authorized user]
	 */
	function __construct()
	{
		parent::__construct();
		auth();
        $this->redirect_role(-1);
	}
	/**
	 * [index performs settings view handling and action submission]
	 * @return [view|redriect] [redirects to settings page after saving settings
	 * , and presents settings view before saving it]
	 */
	public function index()
	{
		
		$this->form_validation->set_rules('title','title','trim|required|alpha_numeric_spaces');
		$this->form_validation->set_rules('email','Email','trim|valid_email');
		$this->form_validation->set_rules('mobile','Mobile Number','trim');
		$this->form_validation->set_rules('address','Address','trim');
		$this->form_validation->set_rules('rights','Copy Rights','trim');
		$this->form_validation->set_rules('facebook','Facebook','trim|valid_url');
		$this->form_validation->set_rules('twitter','Twitter','trim|valid_url');
		$this->form_validation->set_rules('linkedin','LinkedIn','trim|valid_url');
		$this->form_validation->set_rules('gplus','Google Plus','trim|valid_url');
		$this->form_validation->set_rules('skype','Skype','trim');
		$this->form_validation->set_message('required','This field is required.');
		$this->form_validation->set_message('valid_email','Please enter the valid email addres.');
		$this->form_validation->set_message('valid_url','Please enter the valid URL.');
		$this->data['data'] = settings();
		
		if($this->form_validation->run() === false){

			$this->data['meta'] = $this->load->view('backend/common/meta_data',$this->data,true);
			
			$this->data['title'] = 'Settings';
			$this->data['active'] = 'settings';
			$this->data['content'] = $this->load->view('backend/settings',$this->data,true);
			$this->load->view('backend/common/template',$this->data);
			
		}else{
			
			$dbData['footer_about'] = $this->input->post('footer_about');
			$dbData['footer_about_ar'] = $this->input->post('footer_about_ar');
			$dbData['site_title'] = $this->input->post('title');
			$dbData['site_title_ar'] = $this->input->post('title_ar');
			$dbData['mobile'] = $this->input->post('mobile');
			$dbData['email'] = $this->input->post('email');
			$dbData['copy_right'] = $this->input->post('rights');
			$dbData['snapchat'] = $this->input->post('snapchat');
			$dbData['instagram'] = $this->input->post('instagram');
			$dbData['twitter'] = $this->input->post('twitter');
			$dbData['youtube'] = $this->input->post('youtube');
			$dbData['facebook'] = $this->input->post('facebook');
			$dbData['linkedin'] = $this->input->post('linkedin');
			$dbData['google_plus'] = $this->input->post('gplus');
			$dbData['skype'] = $this->input->post('skype');
			$dbData['address'] = $this->input->post('address');

			// META TITLES
			$dbData['meta_title_en'] = $this->input->post('meta_title_en');
			$dbData['meta_title_ar'] = $this->input->post('meta_title_ar');
			$dbData['meta_desc_en'] = $this->input->post('meta_desc_en');
			$dbData['meta_desc_ar'] = $this->input->post('meta_desc_ar');
			$dbData['meta_keys_en'] = $this->input->post('meta_keys_en');
			$dbData['meta_keys_ar'] = $this->input->post('meta_keys_ar');
			


			$dbData['show_email'] = $this->input->post('show_email')?$this->input->post('show_email'):0;
			$dbData['show_mobile'] = $this->input->post('show_mobile')?$this->input->post('show_mobile'):0;
			$dbData['show_fb'] = $this->input->post('show_fb')?$this->input->post('show_fb'):0;
			$dbData['show_tw'] = $this->input->post('show_tw')?$this->input->post('show_tw'):0;
			$dbData['show_li'] = $this->input->post('show_li')?$this->input->post('show_li'):0;
			$dbData['show_gp'] = $this->input->post('show_gp')?$this->input->post('show_gp'):0;
			$dbData['show_skype'] = $this->input->post('show_skype')?$this->input->post('show_skype'):0;
			$dbData['show_address'] = $this->input->post('show_address')?$this->input->post('show_address'):0;


			$dbData['currency_ar'] = $this->input->post('currency_ar');
			$dbData['currency'] = $this->input->post('currency');
			$dbData['currency_position'] = $this->input->post('currency_position');
			$dbData['currency_space'] = $this->input->post('currency_space');
			$dbData['shipping_fee'] = $this->input->post('shipping_fee');
			$dbData['tax'] = $this->input->post('tax');
			
			if(!empty($_FILES['logo']['name'])){
				$config['upload_path']          = './resources/uploads/logo/';
				$config['allowed_types']        = 'gif|jpg|png|jpeg|GIF|JPG|JPEG|PNG';
				$config['file_ext_tolower']        = TRUE;
				$config['encrypt_name']        = TRUE;
				$config['remove_spaces']        = TRUE;
				$this->load->library('upload', $config);
				if ( $this->upload->do_upload('logo'))
				{
					$logo_data = $this->upload->data();
					unlink('./resources/uploads/logo/'.$this->data['data']->site_logo);
					$dbData['site_logo'] = $logo_data['file_name'];
				}
			}
			if(!empty($_FILES['logo_small']['name'])){
				$config['upload_path']          = './resources/uploads/logo/';
				$config['allowed_types']        = 'gif|jpg|png|jpeg|GIF|JPG|JPEG|PNG';
				$config['file_ext_tolower']        = TRUE;
				$config['encrypt_name']        = TRUE;
				$config['remove_spaces']        = TRUE;
				$this->load->library('upload', $config);
				if ( $this->upload->do_upload('logo_small'))
				{
					$logo_data = $this->upload->data();
					unlink('./resources/uploads/logo/'.$this->data['data']->site_logo_small);
					$dbData['site_logo_small'] = $logo_data['file_name'];
				}
			}


			if(!empty($_FILES['favicon']['name'])){
				$config['upload_path']          = './resources/uploads/favicon/';
				$config['allowed_types']        = 'gif|jpg|png|jpeg|GIF|JPG|JPEG|PNG';
				$config['file_ext_tolower']        = TRUE;
				$config['encrypt_name']        = TRUE;
				$config['remove_spaces']        = TRUE;
				$this->load->library('upload', $config);
				if ( $this->upload->do_upload('favicon'))
				{
					$logo_data = $this->upload->data();
					unlink('./resources/uploads/favicon/'.$this->data['data']->site_favicon);
					$dbData['site_favicon'] = $logo_data['file_name'];
				}
			}

			$dbData['meta_title'] = $this->input->post('meta_title');
	        $dbData['meta_keywords '] = $this->input->post('meta_keys');
	        $dbData['meta_description'] = $this->input->post('meta_desc');


			$this->db->where('id',1);
			$this->db->update('settings',$dbData);
			$this->session->set_flashdata('msg','Site settings updated successfully!');
			redirect('admin/settings');
		}
	}
	public function footer()
	{
		
		$this->form_validation->set_rules('text_1_en','Text 1 English','trim|required');
		$this->form_validation->set_rules('text_1_ar','Text 1 Arabic','trim|required');

		$this->form_validation->set_rules('text_2_en','Text 2 English','trim|required');
		$this->form_validation->set_rules('text_2_ar','Text 2 Arabic','trim|required');

		$this->form_validation->set_rules('text_3_en','Text 3 English','trim|required');
		$this->form_validation->set_rules('text_3_ar','Text 3 Arabic','trim|required');

		$this->data['data'] = settings();
		
		if($this->form_validation->run() === false){

			$this->data['meta'] = $this->load->view('backend/common/meta_data',$this->data,true);
			
			$this->data['title'] = 'Settings';
			$this->data['active'] = 'settings';
			$this->data['content'] = $this->load->view('backend/footer_text',$this->data,true);
			$this->load->view('backend/common/template',$this->data);
			
		}else{
			
			$dbData['text_1_en'] = $this->input->post('text_1_en');
			$dbData['text_1_ar'] = $this->input->post('text_1_ar');

			$dbData['text_2_en'] = $this->input->post('text_2_en');
			$dbData['text_2_ar'] = $this->input->post('text_2_ar');

			$dbData['text_3_en'] = $this->input->post('text_3_en');
			$dbData['text_3_ar'] = $this->input->post('text_3_ar');
			
			$this->db->where('id',1);
			$this->db->update('settings',$dbData);
			$this->session->set_flashdata('msg','Footer Text updated successfully!');
			redirect('admin/settings/footer');
		}
	}
}
