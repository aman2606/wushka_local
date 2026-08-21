<?php
  	if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
  		if ( isset( $_POST['set_class_session'], $_POST['set_archive_session'] ) ) {
  			//error_log('passed class id = '.$_POST['set_class_session']);
			$b_return = set_class_session( $_POST['set_class_session'], $_POST['set_archive_session'] );
			//error_log('Set Session Result: '.$b_return);
		} else if ( isset( $_POST['set_user_session'] ) ) {
			$b_return = set_user_session( $_POST['set_user_session'] );
  		}

  		echo json_encode($b_return);
		exit();
	}

function set_class_session($i_new_class = NULL, $s_class_archive = NULL) {
	if ( ! isset($_SESSION) ) {
		session_start();
	}
	if ( ! isset($_SESSION['class_id'], $_POST['class_archive']) ) {
		$_SESSION['class_id'] = NULL;
		$_SESSION['class_archive'] = NULL;
	}

	//error_log('Current Session = '.$_SESSION['class_id']);

	if ( isset($i_new_class, $s_class_archive) ) {
		$_SESSION['class_id'] = (int)$i_new_class;
		$_SESSION['class_archive'] = (string)$s_class_archive;
		return TRUE;
	}

	//error_log('New Session = '.$_SESSION['class_id']);
	return FALSE;
}

function set_user_session( $s_user = NULL ) {
	if ( ! isset($_SESSION) ) {
		session_start();
	}
	if ( ! isset($_SESSION['class_student']) ) {
		$_SESSION['class_student'] = NULL;
	}

	if ( isset($s_user) ) {
		$_SESSION['class_student'] = (string)trim($s_user);
		return TRUE;
	}

	return FALSE;
}

/* ----- EOF ----- */