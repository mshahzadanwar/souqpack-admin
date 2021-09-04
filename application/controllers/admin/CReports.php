<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CReports extends ADMIN_Controller {

	function __construct()
	{
		parent::__construct();
		auth();
        $this->redirect_role(40);
        $this->data['active'] = 'creports';
        $this->load->model('custorders_model','order');
	}

	public function orders()
	{

		$this->data['title'] = 'Order Reports';
        $this->data['sub'] = 'orders_reports';
        $this->data['js'] = 'listing';
        $this->data['jsfile'] = 'js/orders_listing';
        $this->data['orders'] = $this->order->get_all_orders();
		$this->data['content'] = $this->load->view('backend/custom_reports/orders',$this->data,true);
		$this->load->view('backend/common/template',$this->data);

	}

	public function category_sales()
	{

		$this->data['title'] = 'Order Reports';
        $this->data['sub'] = 'category_sales';
        $this->data['js'] = 'listing';
        $this->data['jsfile'] = 'js/orders_listing';
		$this->data['content'] = $this->load->view('backend/custom_reports/category_sale',$this->data,true);
		$this->load->view('backend/common/template',$this->data);

	}
	
	public function item_reports()
	{

		$this->data['title'] = 'Item History report';
        $this->data['sub'] = 'item_reports';
        $this->data['js'] = 'listing';
        $this->data['jsfile'] = 'js/orders_listing';
        $this->data['orders'] = $this->order->get_all_orders();
		$this->data['content'] = $this->load->view('backend/custom_reports/item_history',$this->data,true);
		$this->load->view('backend/common/template',$this->data);

	}
	public function products_reports()
	{

		$this->data['title'] = 'Item History report';
        $this->data['sub'] = 'item_reports';
        $this->data['js'] = 'listing';
        $this->data['jsfile'] = 'js/orders_listing';
        //$this->data['orders'] = $this->order->get_all_orders();
		$this->data['content'] = $this->load->view('backend/custom_reports/products_history_smart',$this->data,true);
		$this->load->view('backend/common/template',$this->data);

	}
	public function finance_reports()
	{

		$this->data['title'] = 'Finance (Custom Order) report';
        $this->data['sub'] = 'item_reports';
        $this->data['js'] = 'listing';
        $this->data['jsfile'] = 'js/orders_listing';
        //$this->data['orders'] = $this->order->get_all_orders();
		$this->data['content'] = $this->load->view('backend/custom_reports/finance_history',$this->data,true);
		$this->load->view('backend/common/template',$this->data);

	}
    public function customers()
	{

		$this->data['title'] = 'Customer Reports';
        $this->data['sub'] = 'orders_reports';
        $this->data['js'] = 'listing';
        $this->data['jsfile'] = 'js/orders_listing';
        $this->data['orders'] = $this->order->get_all_orders();
		$this->data['content'] = $this->load->view('backend/custom_reports/users',$this->data,true);
		$this->load->view('backend/common/template',$this->data);
	}


	public function countries()
	{

		$this->data['title'] = 'Country Reports';
        $this->data['sub'] = 'orders_reports';
        $this->data['js'] = 'listing';
        $this->data['jsfile'] = 'js/orders_listing';
        $this->data['orders'] = $this->order->get_all_orders();
		$this->data['content'] = $this->load->view('backend/custom_reports/countries',$this->data,true);
		$this->load->view('backend/common/template',$this->data);
	}
	public function top_selling()
	{
		$this->data['title'] = 'Top Selling Products';
        $this->data['sub'] = 'orders_reports';
        $this->data['js'] = 'listing';
        $this->data['jsfile'] = 'js/orders_listing';
        $this->data['orders'] = $this->order->get_all_orders();
		$this->data['content'] = $this->load->view('backend/custom_reports/most_selling',$this->data,true);
		$this->load->view('backend/common/template',$this->data);
	}

	public function set_filter(){
		if($_POST['categorys'] == "-1"){
			unset($_SESSION['ffilter_cats']);
		}else{
        	$_SESSION['ffilter_cats'] = $_POST['categorys'];
    	}
        $this->redirect_back();
    }
    public function reset_filter(){
        unset($_SESSION['ffilter_cats']);
        $this->redirect_back();
    }
    
}
