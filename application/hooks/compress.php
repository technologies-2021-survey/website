<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
function compress()
{
	$CI =& get_instance();
	$buffer = $CI->output->get_output();
	$search = array(
		'/\t/',      // replace end of line by a space
		'/\>[^\S ]+/s',    // strip whitespaces after tags, except space
		'/[^\S ]+\</s'    // strip whitespaces before tags, except space
	);
	$replace = array(
		' ',
		'>',
		'<'
	);
	$buffer = preg_replace($search, $replace, $buffer);
	$CI->output->set_output($buffer);
	$CI->output->_display();
}
?>