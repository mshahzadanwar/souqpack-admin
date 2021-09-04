<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 

/* Category Master*/
    $route['category-master'] 			= 'admin/master/blog/Category';
    $route['save-category'] 			= 'admin/master/blog/Category/save';
    $route['category-list'] 			= 'admin/master/blog/Category/dt_list';
    $route['category-delete/(:num)'] 	= 'admin/master/blog/Category/delete_category/$1';
    $route['category-form/(:num)'] 		= 'admin/master/blog/Category/loadForm/$1';
    $route['set-category'] 				= 'admin/master/blog/Category/set_category';


/* Tag Master*/
    $route['tag-master'] 				= 'admin/master/blog/Tag';
    $route['save-tag'] 					= 'admin/master/blog/Tag/save';
    $route['tag-list'] 					= 'admin/master/blog/Tag/dt_list';
    $route['tag-delete/(:num)'] 		= 'admin/master/blog/Tag/delete_tag/$1';
    $route['tag-form/(:num)'] 			= 'admin/master/blog/Tag/loadForm/$1';
    $route['set-tag'] 					= 'admin/master/blog/Tag/set_tag';
 

/* Blog*/
    $route['blog-master']               = 'admin/master/blog/Blog';
    $route['blog-list'] 				= 'admin/master/blog/Blog/dt_list';
    $route['add-blog']                  = 'admin/master/blog/Blog/add';
    $route['edit-blog/(:num)']          = 'admin/master/blog/Blog/edit/$1';
    $route['save-blog'] 				= 'admin/master/blog/Blog/save';
    $route['blog-delete/(:num)'] 		= 'admin/master/blog/Blog/delete_blog/$1'; 
    $route['blog-status/(:num)/(:num)'] 		= 'admin/master/blog/Blog/update_blog/$1/$2';