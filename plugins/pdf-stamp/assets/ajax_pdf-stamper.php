<?php
include "../../../wp-config.php";
$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
if (mysqli_connect_errno()) {
	exit("Couldn't connect to the database: " . mysqli_connect_error());
}

if( isset($_POST['stampAjax']) && $_POST['stampAjax'] == 'pdf_secureURLs')
{
	//Set Global Variables
	global $wpdb;
	global $lzaws;
	//Collect POST variables
	$post_id 	= $_POST['post_id'];
	$hasAnswer 	= $_POST['hasAnswer'];

	//Create Return Array For storing Secure URLS
	$secureURLs = array();
	$secureURLs['posturl'] 		= '';
	$secureURLs['answerurl'] 	= '';

	//Verify POST ID sent
	if(isset($post_id))
	{
		$secureURLs['posturl'] = $lzaws->get_secure_attachment_url(1455723, '300');

		if($hasAnswer == 'yes') {
			$answer_id 	= $_POST['answer_id'];
			$secureURLs['answerurl'] = $lzaws->get_secure_attachment_url($answer_id, '300');
		}
	}

	echo json_encode($secureURLs);
}

?>