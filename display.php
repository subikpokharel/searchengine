<?php

	//print_r($_POST);
	# Remove leading and trailing spacing.
	$URL     = trim( $_POST["searchBar"] );

	# For security, remove some Unix metacharacters.
	$meta    = array( ";", ">", ">>", ";", "*", "?", "&", "|" );
	$URL     = str_replace( $meta, "", $URL );

	header( "Content-type: text/plain" );
	$cmd = "lynx -dump -source '" . $URL . "' > result.txt";
	echo  ( $cmd . "\n\n\n" );
	system( "chmod 777 result.txt" );
	system( $cmd );
	system( "chmod 755 result.txt" );
	system( "cat result.txt" );




?>
