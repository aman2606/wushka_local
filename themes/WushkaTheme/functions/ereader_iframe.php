<?php
if (!defined('ABSPATH')) {
    exit(); // Exit if accessed directly
}

class Ebook_Reader {

	private $_s_url;
	private $_a_return;
	private $_a_book;

	public function __construct() {
		$this->_a_book = array(
			'id' 	=> NULL,
			'res' 	=> NULL,
			'code'	=> NULL
		);

		$this->_a_return = array(
			'html' 		=> NULL,
			'status' 	=> FALSE,
			'message'	=> 'Loading iframe'
		);

		$this->_s_url = NULL;

		$this->validate_parameters();
	}

	private function validate_parameters() {
		//Ajax Validation
		$s_validate = json_decode(stripcslashes(filter_input(INPUT_POST, 's_validate')), true);
		if ( ! isset( $s_validate ) || ! wp_verify_nonce($s_validate, 'ereader_iframe_validation') ) {
			$this->_a_response['status']  = 'failed';
			$this->_a_response['message'] = 'Validation Failed';
			return FALSE;
		}

		//Book ID
		$s_var_1 = json_decode( stripcslashes( filter_input( INPUT_POST, 's_var_1') ), true);
		if ( isset( $s_var_1 ) || ! empty($s_var_1) ) {
			$this->_a_book['id'] = $s_var_1;
		} else {
			$this->_a_return['status'] = 'failed';
			$this->_a_return['message'] = 'Missing Field';
			return FALSE;
		}

		//Book Resource ID
		$s_var_2 = json_decode( stripcslashes( filter_input( INPUT_POST, 's_var_2') ), true);
		if ( isset( $s_var_2 ) || ! empty($s_var_2) ) {
			$this->_a_book['res'] = $s_var_2;
		} else {
			$this->_a_return['status'] = 'failed';
			$this->_a_return['message'] = 'Missing Field';
			return FALSE;
		}
		//Book Code
		$s_var_3 = json_decode( stripcslashes( filter_input( INPUT_POST, 's_var_3') ), true);
		if ( isset( $s_var_3 ) || ! empty($s_var_3) ) {
			$this->_a_book['code'] = $s_var_3;
		} else {
			$this->_a_return['status'] = 'failed';
			$this->_a_return['message'] = 'Missing Field';
			return FALSE;
		}

		$this->_a_return['status'] = 'success';
		$this->_a_return['message'] = 'Validation Passed';
		return TRUE;
	}

	private function build_url(){

		$s_url = home_url('/ereader/?reader=samples&epub=/Resources/'.$this->_a_book['res'].'/'.$this->_a_book['code'].'/');


		$a_html[] = '<iframe class="sample-reader" title="'.$this->_a_book['res'].'" src="'.$s_url.'"></iframe>';

		$this->_a_return['html'] = implode('', $a_html);

		$this->_a_return['message'] = print_r($this->_a_book, true);
		return TRUE;
	}

	public function ajax_return() {
		$this->build_url();

		return $this->_a_return;
	}

}
/* ----- END OF FILE ----- */