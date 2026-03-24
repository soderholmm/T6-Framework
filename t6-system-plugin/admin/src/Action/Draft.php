<?php
namespace T6Admin\Action;


class Draft {
	public static function doSave () {
		$key = \T6Admin\Draft::store();
		return ["ok" => 1, "key" => $key];
	}
}