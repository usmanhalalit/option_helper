<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*  
Options Helper for CodeIgniter

Doc: http://usman.it/wordpress-like-option-feature-for-your-codeigniter-application/

Version: 1.0

Author: Muhammad Usman

Author URI: http://usman.it

License:
Copyright (c) 2011, Muhammad Usman
All rights reserved.

Redistribution and use in source and binary forms, with or without modification, are permitted provided that the following conditions are met:

Redistributions of source code must retain the above copyright notice, this list of conditions and the following disclaimer.
Redistributions in binary form must reproduce the above copyright notice, this list of conditions and the following disclaimer in the documentation and/or other materials provided with the distribution.
THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.

Database Table for this Helper:

CREATE TABLE IF NOT EXISTS `tbl_option` (
 
`option_id` bigint(20) NOT NULL AUTO_INCREMENT,
 
`option_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
 
`option_value` longtext COLLATE utf8_unicode_ci NOT NULL,
 
`option_type` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
 
PRIMARY KEY (`option_id`),
 
UNIQUE KEY `option_name` (`option_name`)
 
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=59 ;

*/

function add_option($name,$value)
{
	$CI =& get_instance();
	$CI->load->database();
	$query = $CI->db->select('*')->from('tbl_option')->where('option_name',$name)->get();
	
	//option already exists
	if($query->num_rows() > 0)
		return false;
	
	$data_type = 'text';
	if(is_array($value))
	{
		$data_type = 'array';
		$value = serialize($value);
	}
	elseif(is_object($value))
	{
		$data_type = 'object';
		$value = serialize($value);
	}
	
	$data=array(
		'option_name'  => $name,
		'option_value' => $value,
		'option_type'  => $data_type,
	);
	$CI->db->insert('tbl_option',$data);
}

function update_option($name,$value)
{
	$CI =& get_instance();
	$CI->load->database();
	
	$data_type='text';
	if(is_array($value)) 
	{
		$data_type = 'array';
		$value = serialize($value);
	}
	elseif(is_object($value))
	{
		$data_type = 'object';
		$value = serialize($value);
	}
	
	$data=array(
		'option_name'  => $name,
		'option_value' => $value,
		'option_type'  => $data_type,
	);
	$query=$CI->db->select('*')->from('tbl_option')->where('option_name',$name)->get();
	
	//if option already exists then update else insert new
	if($query->num_rows() < 1) return $CI->db->insert('tbl_option',$data);
	else		  		  	   return $CI->db->update('tbl_option',$data,array('option_name'=>$name));
}

function get_option($name)
{
	$CI =& get_instance();
	$CI->load->database();
	$query = $CI->db->select('*')->from('tbl_option')->where('option_name',$name)->get();
	//option not found
	if($query->num_rows() < 1) return false;
	
	$option = $query->row();
	
	if('text' == $option->option_type)
		return $option->option_value;
	elseif('array' == $option->option_type || 'object' == $option->option_type)
		return unserialize($option->option_value);
}

function delete_option($name)
{
	$CI =& get_instance();
	$CI->load->database();
	return $CI->db->delete('tbl_option',array('option_name'=>$name));
}
