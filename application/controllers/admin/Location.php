<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Location extends ADMIN_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('Location_model' ,'location');
	}

	public function get_stats_by_country_id()
    {
        $country_id = $this->input->post('country_id');
        $selected = $this->input->post('selected');
    	$states = $this->location->get_stats_by_country($country_id);

    	$html = '<option value="">Choose State</option>';
    	//$html = '';
        $items = array();
    	foreach($states->result() as $state){
    	    if($state->id == $selected){
                $html .= '<option selected="selected" value="'.$state->id.'">'.$state->name.'</option>';
            }else{
                $html .= '<option value="'.$state->id.'">'.$state->name.'</option>';
            }

            $items[] = array('id'=>$state->id,'text'=>$state->name);

    	}

    	$return['status'] = 200;
    	$return['states'] = $html;
    	$return['item'] = $items;
    	echo json_encode($return);
    }

    public function get_city_by_state_id()
    {
        $state_id = $this->input->post('state_id');
        $selected = $this->input->post('selected');
        $cities = $this->location->get_cities_by_state($state_id);

        $html = '<option value="">Choose City</option>';
        //$html = '';
        $items = array();
        foreach($cities->result() as $city){
            if($city->id == $selected){
                $html .= '<option selected="selected" value="'.$city->id.'">'.$city->name.'</option>';
            }else{
                $html .= '<option value="'.$city->id.'">'.$city->name.'</option>';
            }
            $items[] = array('id'=>$city->id,'text'=>$city->name);
        }

        $return['status'] = 200;
        $return['cities'] = $html;
        $return['item'] = $items;
        echo json_encode($return);
    }

    public function page_404(){
        $this->data['title'] = '404 Page';
        $this->load->view('404',$this->data);
    }
	

}
