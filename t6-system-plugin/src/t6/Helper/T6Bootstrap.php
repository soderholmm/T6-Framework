<?php
namespace T6\Helper;


class T6Bootstrap {
	public static function getAttrs($data = array()){
		if(empty($data)) return "";
		$attribs = " ";
		foreach ($data as $name => $value) {
			if(J3J4::isJ4() || defined('T6_BS5')){
				$attribs .= "data-bs-".$name."=".$value;
			}else{
				$attribs .= "data-".$name."=".$value;
			}
			$attribs .= " ";
		}
		return $attribs;
	}
}
