<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

if(!function_exists('pre'))
{
	function pre($array, $die = 'null'){
		echo "<pre>";
			print_r($array);
		echo "</pre>";
		if($die != null){
		  return $die;
		}
	}
}

if ( ! function_exists('json_response') )
{


	function json_response( $status,$message,$data,$total_page="",$page="" )
	{
		$res_data 					= array();
		$res_data [ "success" ] 	= $status;
		$res_data [ "message" ] 	= $message;
		$res_data [ "data" ]		= $data;
		if($total_page !="")
		{
			$res_data [ "total_page" ]		= "$total_page";
		}
		if($page !="")
		{
			$res_data [ "page" ]		= "$page";
		}
		header('Content-Type: application/json; charset=UTF8');
		//return json_encode( $res_data );
		return json_encode( $res_data,JSON_NUMERIC_CHECK);
		
	}
}
if ( ! function_exists('json_response_cart') )
{


	function json_response_cart( $status,$message,$data,$grandtotal=0)
	{
		$res_data 					= array();
		$res_data [ "success" ] 	= $status;
		$res_data [ "message" ] 	= $message;
		$res_data [ "data" ]		= $data;
		
		$res_data [ "grand_total" ]		= "$grandtotal";
		header('Content-Type: application/json; charset=UTF8');
		//return json_encode( $res_data );
		return json_encode( $res_data,JSON_NUMERIC_CHECK);
		
	}
}

if ( ! function_exists('json_response_friends') )
{


	function json_response_friends( $status,$message,$data,$total_page="",$page="",$totalPendindRequest )
	{
		$res_data 					= array();
		$res_data [ "success" ] 	= $status;
		$res_data [ "message" ] 	= $message;
		$res_data [ "data" ]		= $data;
		if($total_page !="")
		{
			$res_data [ "total_page" ]		= "$total_page";
		}
		if($page !="")
		{
			$res_data [ "page" ]		= "$page";
		}
		$res_data [ "pending_request_count" ] = $totalPendindRequest;
		header('Content-Type: application/json; charset=UTF8');
		return json_encode( $res_data,JSON_NUMERIC_CHECK);
		
	}
	
	
}
?>
