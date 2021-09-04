<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * handles the admins
 * 
 * @since 1.0
 * @author DeDevelopers
 * @copyright Copyright (c) 2019, DeDevelopers, https://dedevelopers.com
 */
class Seo extends ADMIN_Controller {
    /**
     * constructs ADMIN_Controller as parent object
     * loads the neccassary class
     * checks if current user has the rights to access this class
     * 
     * @since 1.0
     * @author DeDevelopers
     * @copyright Copyright (c) 2019, DeDevelopers, https://dedevelopers.com
     */
	function __construct()
	{
	    
		parent::__construct();
	}
	public function index()
	{
        $categories = $this->db->query("SELECT slug,id FROM categories WHERE lparent = 0 AND status = 1")->result_object();
        $this->data['categories'] = $categories;

        $products = $this->db->query("SELECT slug,id FROM products WHERE lparent = 0 AND status = 1")->result_object();
        $this->data['products'] = $products;

        $pages = $this->db->query("SELECT slug,id FROM pages WHERE lparent = 0 AND status = 1")->result_object();
        $this->data['pages'] = $pages;

        $seo_settins = $this->db->query("SELECT *FROM seo_settins WHERE id = 1")->result_object()[0];
        $this->data['settings'] = $seo_settins;

		header("Content-Type: text/xml;charset=iso-8859-1");
        
		$this->data['content'] = $this->load->view('backend/sitemap', $this->data);
	}

    public function settings()
    {
        $seo = $this->db->query("SELECT * FROM seo_settins WHERE id = 1")->result_object()[0];
        $this->data['data'] = $seo;
        $this->data['content'] = $this->load->view('backend/sitemap_settings', $this->data,true);
        $this->load->view('backend/common/template',$this->data);
    }

    public function update_seo(){

        $dbData['base_freq'] = $this->input->post('base_freq');
        $dbData['base_pr'] = $this->input->post('base_pr');
        $dbData['cat_freq'] = $this->input->post('cat_freq');
        $dbData['cat_pr'] = $this->input->post('cat_pr');
        $dbData['pro_freq'] = $this->input->post('pro_freq');
        $dbData['pro_pr'] = $this->input->post('pro_pr');
        $dbData['page_freq'] = $this->input->post('page_freq');
        $dbData['page_pr'] = $this->input->post('page_pr');
        $dbData['cart_freq'] = $this->input->post('cart_freq');
        $dbData['cart_pr'] = $this->input->post('cart_pr');
        $dbData['wish_freq'] = $this->input->post('wish_freq');
        $dbData['wish_pr'] = $this->input->post('wish_pr');
        $dbData['log_freq'] = $this->input->post('log_freq');
        $dbData['log_pr'] = $this->input->post('log_pr');
        $dbData['sign_freq'] = $this->input->post('sign_freq');
        $dbData['sign_pr'] = $this->input->post('sign_pr');

        $this->db->where('id',1);
        $this->db->update('seo_settins', $dbData);
        $this->session->set_flashdata('msg','New Category added successfully!');
        $this->redirect_back();
    }
}
