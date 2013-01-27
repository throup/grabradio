<?php
namespace uk\org\throup\grabradio {
	error_reporting(E_ALL);
	class Autoloader {
    	public static function load($classname) {
    		$path = '';
    		$matches = array();
    		if (preg_match('/^uk\\\\org\\\\throup\\\\grabradio\\\\([^\\\\]+)$/', $classname, $matches)) {
    			$class = $matches[1];
    			$path = __DIR__."/src/$class.inc";
    		} elseif (preg_match('/^uk\\\\org\\\\throup\\\\grabradio\\\\test\\\\([^\\\\]+)$/', $classname, $matches)) {
    			$class = $matches[1];
    			$path = __DIR__."/test/$class.inc";
	   		}
        	if ($path) {
        		require_once($path);
        		return true;
        	}
    		exit(); 
    	}
	}
	spl_autoload_register(__NAMESPACE__ . "\\Autoloader::load");
	Config::load('/home/throup/.grabradiorc');
}