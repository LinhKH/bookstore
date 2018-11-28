<?php
	require_once 'define.php';
	function __autoload($clasName){
		require_once LIBRARY_PATH . "{$clasName}.php";
	}

	Session::init();
	
	$bootstrap = new Bootstrap();
	$bootstrap->init();
?>