<?php
session_start();
//whenever call a class this method is invoked
spl_autoload_register(function ($class_name){
	require_once("model/" . $class_name . ".class.php");
});
?>