<?php
/**
 * Displaying Messages
 */
function showErrorMessage( $title, $message, $return_url = null ) {
	$message = [
		'type'       => 'error',
		"title"      => $title,
		'body'       => $message,
		'return_url' => $return_url
	];
	include 'templates/other/show_message.php';

	die();
}

function showSuccessMessage( $title, $message, $return_url = null ) {
	$message = [
		'type'       => 'success',
		"title"      => $title,
		'body'       => $message,
		'return_url' => $return_url
	];
	include 'templates/other/show_message.php';

	die();
}