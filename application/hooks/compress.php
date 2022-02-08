<?php
 $CI =& get_instance();
 $buffer = $CI->output->get_output();
 
 $search = array(
    '/\n/',
    '/\>[^\S ]+/s',
    '/[^\S ]+\</s',
    '/(\s)+/s'
  );

 $replace = array(
    ' ',
    '>',
    '<',
    '\\1'
  );

 $buffer = preg_replace($search, $replace, $buffer);

 $CI->output->set_output($buffer);
 $CI->output->_display();