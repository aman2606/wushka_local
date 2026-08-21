<?php

if (!defined('ABSPATH')) {
    exit(); // Exit if accessed directly
}

/*
 * Managing Teacher Reading Group CLASS
 *
 */

class Reading_Groups {

	/* ------------ CONSTANTS ------------*/
	private $_s_table;
	private $_s_table_1;
	private $_s_table_2;


	public function __construct() {
		$this->initialise_tables();
	}

	private function initialise_tables() {
		global $wpdb;

		$s_table_1 = $wpdb->prefix.'wushka_reading_groups';
		$s_table_2 = $wpdb->prefix.'wushka_reading_groups_books';

		if ( $s_table_1 != $wpdb->get_var("SHOW TABLES LIKE '".$s_table_1."'") ) {
		    //table is not created. you may create the table here.
			$s_query = 'CREATE TABLE IF NOT EXISTS '.$s_table_1.' ( '.
				'`ID` INT(13) NOT NULL AUTO_INCREMENT, '.
				'`group_name` VARCHAR(45) NOT NULL, '.
				'`class_id` INT(13) NOT NULL, '.
				'PRIMARY KEY (`ID`) );';
			$wpdb->query($s_query);
			unset($s_query);
		}

		if ( $s_table_2 != $wpdb->get_var("SHOW TABLES LIKE '".$s_table_2."'") ) {
		    //table is not created. you may create the table here.
			$s_query = 'CREATE TABLE IF NOT EXISTS '.$s_table_2.' ( '.
				'`ID` INT(13) NOT NULL AUTO_INCREMENT, '.
				'`group_id` INT(13) NOT NULL, '.
				'`post_id` INT(13) NOT NULL, '.
				'`active` TINYINT(1) NOT NULL DEFAULT 1, '.
				'PRIMARY KEY (`ID`) );';
			$wpdb->query($s_query);
			unset($s_query);
		}

		$this->_s_table_1 = $s_table_1;
		$this->_s_table_2 = $s_table_2;
	}

	/* ------------ QUERY FUNCTIONS ------------*/


	/**
	 * Get Groups
	 * Runs a query against the reading groups table to return
	 * reading group data. use class or group id fields to filter query
	 * @param $s_field (string) - 'group' || 'class - Determines
	 * desired column meta to query, group = group_id, class = class_id
	 * @param $x_values (string|array) - array of ids, either class or group ids
	 * @return array - array of group data.
	 */
	public function get_groups ( $s_field = NULL, $x_values = NULL ) {
		error_log('get_groups, field: ' . $s_field . ', value: ' . $x_values);
		if ( $this->validate(array($s_field,$x_values)) === TRUE ) {
			if ( ! is_array($x_values) ) {
				$a_values = array( (int) $x_values );
			} else {
				$a_values = $x_values;
			}

			if ( ($a_groups = $this->query_groups($s_field, $a_values)) !== FALSE ) {
				return $a_groups;
			} else {
				error_log('Get Groups - No Results');
			}
		} else {
			error_log('Get Groups - Missing Parameters');
		}

		return FALSE;
	}

	public function get_books ( $i_group = NULL ) {
		if ( ($this->validate($i_group)) === TRUE ) {
			if ( ($a_books = $this->query_books($i_group)) !== FALSE ) {
				return $a_books;
			} else {
				error_log('Get Books - No Results');
			}
		} else {
			error_log('Get Books - Missing Parameters');
		}

		return FALSE;
	}

	private function query_groups ($s_type, $a_ids) {
		global $wpdb;

		$s_field = NULL;
		switch($s_type) {
			case 'group' :
				$s_field = 'ID';
				break;
			case 'class' :
				$s_field = 'class_id';
				break;
		}

		if ( ! isset($s_field) ) {
			return FALSE;
		}

		$a_format = array();
		foreach( $a_ids as $i_key => $i_group ) {
			$a_format[] = '%d';
		}

		$s_query = 'SELECT * FROM '.$this->_s_table_1.' WHERE '.
			$s_field.' IN ( '.implode(',', $a_format).' ) '.
		'ORDER BY ID ASC';

		$a_groups = $wpdb->get_results(
			$wpdb->prepare( $s_query, $a_ids )
		);

		if ( isset($a_groups) && ! empty($a_groups) ) {
			return $a_groups;
		}

		return FALSE;
	}

	private function query_books ($i_group) {
		global $wpdb;

		$s_query = 'SELECT * FROM '.$this->_s_table_2.' WHERE '.
			'group_id = %d '.
		'ORDER BY ID DESC';

		$a_books = $wpdb->get_results(
			$wpdb->prepare( $s_query, $i_group )
		);

		if ( isset($a_books) && ! empty($a_books) ) {
			return $a_books;
		}

		return FALSE;
	}

	/* ------------ CREATE FUNCTIONS ------------*/
	public function create_group( $s_name = NULL, $i_class = NULL ) {
		if ( ($this->validate(array($s_name, $i_class))) === TRUE ) {
			$a_fields = array(
				'group_name'	=> $s_name,
				'class_id'	 	=> $i_class
			);
			$a_format = $this->format_fields($a_fields);

			return $this->create($this->_s_table_1, $a_fields, $a_format);
		}

		error_log('Create Group Error: Missing Parameters');
		return FALSE;
	}

	public function create_book( $i_group = NULL, $i_book = NULL ) {
		if ( ($this->validate(array($i_group, $i_book))) === TRUE ) {

			$a_fields = array( 'group_id' => $i_group, 'post_id'  => $i_book );
			$a_format = $this->format_fields($a_fields);

			return $this->create($this->_s_table_2, $a_fields, $a_format);
		}

		error_log('Create Book Error: Missing Parameters');
		return FALSE;
	}

	private function create( $s_table, $a_data, $a_format ) {
		global $wpdb;

		$b_insert = $wpdb->insert(
			$s_table,
			$a_data,
			$a_format
		);

		if ( $b_insert == 1 ) {
			error_log('New Row in table '.$s_table.'('.$wpdb->insert_id.')');
			return $wpdb->insert_id;
		}

		return FALSE;
	}

	/* ------------ EDIT FUNCTIONS ------------*/
	public function edit_group( $i_id = NULL, $s_field = NULL, $x_value = NULL ) {
		if ( ($this->validate(array($i_id, $s_field, $x_value))) === TRUE ) {
			$a_fields 	= array( $s_field => $x_value );
			$a_format 	= $this->format_fields($a_fields);

			return $this->edit( $this->_s_table_1, $i_id,  $a_fields, $a_format );
		}

		error_log('Edit Group Error: Missing Parameters');
		return FALSE;
	}

	public function edit_book( $i_id = NULL, $s_field = NULL, $x_value = NULL ) {
		if ( ($this->validate(array($i_id, $s_field ))) === TRUE ) {

			$a_data 	= array( $s_field => $x_value );
			$a_format 	= $this->format_fields($a_data);

			return $this->edit( $this->_s_table_2, $i_id,  $a_data, $a_format );
		}

		error_log('Edit Book Error: Missing Parameters');
		return FALSE;
	}

	private function edit( $s_table, $i_id,  $a_data, $a_params ) {
		global $wpdb;

		$x_return = $wpdb->update(
			$s_table,
			$a_data,
			array( 'ID' => $i_id ),
			$a_params,
			array( '%d' )
		);

		if ( $x_return !==  FALSE ) {
			if ( $x_return > 0 ) {
				error_log('Updated Row '.$i_id.' in table '.$s_table);
				return TRUE;
			} else if ( $x_return == 0 ) {
				error_log('ERROR: Row was not updated');
			}
		}

		return FALSE;
	}

	/* ------------ DELETE FUNCTIONS ------------*/
	public function delete_group( $i_id = NULL ) {
		if ( ($this->validate($i_id)) === TRUE ) {
			if ( $this->delete($this->_s_table_1, $i_id) !== FALSE ) {
				if ( $this->delete_group_books($i_id) !== FALSE ) {
					return TRUE;
				}
			}
		}

		error_log('Delete Group Error: Missing Parameters');
		return FALSE;
	}

	public function delete_book( $i_id = NULL ) {
		if ( ($this->validate($i_id)) === TRUE ) {
			return $this->delete($this->_s_table_2, $i_id);
		}

		error_log('Delete Book Error: Missing Parameters');
		return FALSE;
	}

	private function delete_group_books($i_id ) {
		global $wpdb;

		$x_return = $wpdb->delete(
			$this->_s_table_2,
			array( 'group_id' => $i_id ),
			array( '%d' )
		);

		if ( $x_return !== FALSE ) {
			error_log('Removed Group ('.$i_id.') Books from Table '.$this->_s_table_2);
			return TRUE;
		}

		error_log('Error: Could Not Delete Group');
		return FALSE;
	}

	private function delete( $s_table, $i_id ) {
		global $wpdb;

		$x_return = $wpdb->delete(
			$s_table,
			array( 'ID' => $i_id ),
			array( '%d' )
		);

		if ( $x_return !== FALSE ) {
			error_log('Row ('.$i_id.') from Table '.$s_table);
			return TRUE;
		}

		error_log('Error: Could Not Delete Group');
		return FALSE;
	}

	/* ------------ STUDENT META ------------ */
	public function user_meta( $i_user = NULL, $i_group = NULL ) {
		if ( ($this->validate($i_user)) === TRUE ) {
			return $this->update_meta($i_user, $i_group);
		}

		error_log('Updating Student Meta Error: Missing Parameters');
		return FALSE;
	}

	private function update_meta( $i_user, $i_group ) {
		$i_meta = get_user_meta($i_user, 'my_reading_group', TRUE);

		if ( ! $i_group ) {
			error_log('Student Reading Group Deleted');
			delete_user_meta($i_user, 'my_reading_group', $i_meta);
			return TRUE;
		} else {
			if( $i_meta ) {
				error_log('Student Reading Group Updated');
			} else {
				error_log('Student Reading Group Created');
			}
			update_user_meta($i_user, 'my_reading_group', $i_group);
			return TRUE;
		}

		return FALSE;
	}


	/* ------------ UTILITY FUNCTIONS ------------*/
	private function validate( $x_vars = array() ) {
		if ( ! $x_vars ) {
			return FALSE;
		}

		if ( ! isset( $x_vars ) || empty( $x_vars ) ) {
				error_log('Invalid Variable');
				return FALSE;
		}
		if ( is_array($x_vars) ) {
			foreach ( $x_vars as $i_key => $x_var ) {
				if ( ! isset( $x_var ) || empty( $x_var ) ) {
					error_log('Invalid Variable: '.$i_key );
					return FALSE;
				}
			}
		}

		return TRUE;
	}

	private function format_fields( $a_fields = array() ) {
		$a_format[] = '%s';
		if ( $a_fields ) {
			foreach ( $a_fields as $key => $val ) {
				$a_format[] = $this->format_field($key);
			}
		}

		return $a_format;
	}

	private function format_field( $s_field ) {
		$s_param = '%s';
		switch ($s_field) {
			case 'group_name' :
				$s_param = '%s';
				break;
			case 'class_id' :
				$s_param = '%d';
				break;
			case 'group_id' :
				$s_param = '%d';
				break;
			case 'book_id' :
				$s_param = '%d';
				break;
			case 'active' :
				$s_param = '%d';
				break;
		}

		return $s_param;
	}

	/*----- END CLASS -----*/
}





/* ----- EOF ----- */