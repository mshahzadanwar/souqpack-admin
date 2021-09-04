<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

if ( ! function_exists('current_url'))
{

	function current_url()
	{	
    	$CI =& get_instance();
    	$url = $CI->config->site_url($CI->uri->uri_string());
    	return $_SERVER['QUERY_STRING'] ? $url.'?'.$_SERVER['QUERY_STRING'] : $url;
    	
 	}
}

if ( ! function_exists('current_url_with_parameter'))
{
	function current_url_with_parameter()
	{
		return parse_str($_SERVER['QUERY_STRING'], $_GET);
	}
}