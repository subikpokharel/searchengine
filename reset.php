<?php

	require_once "class/Database.class.php";

	$obj = new Database();
	$obj->resetSystem();

	header("Location: data.php");


?>