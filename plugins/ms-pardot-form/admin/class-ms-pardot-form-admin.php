<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://modernstar.com
 * @since      1.0.0
 *
 * @package    Ms_Pardot_Form
 * @subpackage Ms_Pardot_Form/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Ms_Pardot_Form
 * @subpackage Ms_Pardot_Form/admin
 * @author     Modern Star <sshrestha@modernstar.com>
 */
class Ms_Pardot_Form_Admin
{

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct($plugin_name, $version)
	{

		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Ms_Pardot_Form_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Ms_Pardot_Form_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/ms-pardot-form-admin.css', array(), $this->version, 'all');
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Ms_Pardot_Form_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Ms_Pardot_Form_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/ms-pardot-form-admin.js', array('jquery'), $this->version, false);
	}

	/**
	 * Handles the processing of the "form_post_link" meta field when a post is updated or published.
	 *
	 * @param int $post_ID The ID of the post being processed.
	 * @param WP_Post $post The post object being processed.
	 * @param bool $update Whether the post is being updated.
	 */
	public function pardot_field($post_ID, $post, $update)
	{
		if($post->post_type != 'educational_resource'){
			return;
		}
		// Get the post status
		$status = $post->post_status;
		//echo "<pre>".print_r($post, true)."</pre>";die;
		error_log($update);

		// Check if the post is being updated, or if the post status is "auto-draft" or "draft"
		if ($update || ($status === 'auto-draft' && $status === 'publish') || ($status === 'draft' && $status === 'publish')) {
			// Get the value of the "form_post_link" meta field
			$pardot_field = get_post_meta($post_ID, 'form_post_link', true);

			// If the "form_post_link" meta field is not null, call the "load_html_from_pardot" method
			if (!empty(trim($pardot_field))) {
				$this->load_html_from_pardot($post_ID, $pardot_field);
			}
		}
	}


	/**
	 * Check if a string contains a specific word
	 *
	 * @param string $string The string to search
	 * @param string $check_word The word to search for
	 * @return bool Returns true if the string contains the word, false otherwise
	 */
	public function check_string($string, $check_word)
	{
		return (stripos($string, $check_word) !== false) ? true : false;
	}

	/**
	 * Compares the fields in the $fields array with the elements in the $updated array
	 * and returns an array containing the elements in $fields that are not present in $updated.
	 *
	 * @param array $updated An array containing elements to be compared with the $fields array
	 *
	 * @return array An array containing the elements in $fields that are not present in $updated
	 */
	public function compare_updated(array $updated): array
	{
		$fields = [
			'First Name',
			'Last Name',
			'Email',
			'Country',
			'Country Option',
			'State',
			'State Option',
			'Education Sector',
			'Education Sector Option',
			'Job Title',
			'Job Title Option'
		];

		return array_diff($fields, $updated);
	}



	/**
	 * Loads the HTML from a Pardot URL and updates the post meta fields with the relevant data.
	 *
	 * @param int $post_ID The ID of the post being processed.
	 * @param string $pardot_url The URL of the Pardot page.
	 */
	public function load_html_from_pardot($post_ID, $pardot_url)
	{
		// Include the simple_html_dom library
		include(plugin_dir_path(__FILE__) . 'includes/simple_html_dom.php');
		// Use file_get_html to fetch the HTML from the Pardot URL
		$html = file_get_html($pardot_url);

		// Find all label elements on the page
		$labels = $html->find('label');

		// Define an array of label texts to check
		$inputLabels = [
			'First Name'		=>	'first_name',
			'Last Name'			=>	'last_name',
			'Email'				=>	'email',
			'Country'			=>	'country',
			'State'				=>	'state',
			'Education Sector'	=>	'education_sector',
			'Job Title'			=>	'job_title'
		];

		$optionHtml = [
			'Country'			=>	'country_option',
			'State'				=>	'state_option',
			'Education Sector'	=>	'education_sector_option',
			'Job Title'			=>	'job_title_option'
		]; 

		$updated = [];

		// Loop through the label elements
		foreach ($labels as $label) {

			// Loop through the input text and get name
			foreach($inputLabels as $inputKey => $inputValue) {
				if($this->check_string($label->innertext, $inputKey)){
					// Update the post meta with the input field name
					$input = $label->next_sibling();
					update_post_meta( $post_ID, $inputValue, $input->name );
					//
					$updated[] = $inputKey;
					// Exit the inner loop
					break;					
				}
			}

			 // Loop through the options and get option HTML
			 foreach ($optionHtml as $optionHtmlKey => $optionHtmlValue) {
				// Check if the label text matches one of the option texts
				if($this->check_string($label->innertext, $optionHtmlKey)){
					// Get the options for the select field
					$input = $label->next_sibling(); 
					$options = $input->find('option');
					$optionVal = [];
					// Add the option HTML to the $optionVal array
					foreach($options as $option){						
						$optionVal[] = $option->outertext;
					}
					$optionVal = implode(' ', $optionVal);
					// Update the post meta with the option HTML
					update_post_meta( $post_ID, $optionHtmlValue, $optionVal);
					

					if($optionHtmlKey == 'Country'){
						// Use a regular expression to find the AU option element
						preg_match('/<option\s+([^>]+)>AU<\/option>/', $optionVal, $matches);
						$attributes = $matches[1];

						// Use a regular expression to find the value attribute
						preg_match('/value="([^"]+)"/', $attributes, $matches);

						$au_value = $matches[1];
						// Update the post meta with the option HTML
						update_post_meta( $post_ID, 'country_au_value', $au_value);
					}

					//
					$updated[] = $inputKey . ' Option';
					// Exit the inner loop
					break;
				}
			}
		}

		$terms = $html->find('input[type=checkbox]', 0);
		if(!empty($terms->name)){
			update_post_meta( $post_ID, "terms_and_conditions", $terms->name);
			//
			$updated[] = "Terms and Conditions";
		}

		// clean up memory
		$html->clear();
		unset($html);

		
		$diff = $this->compare_updated($updated);
		if (!empty($diff)) {
			set_transient( 'pardot_transient_'.$post_ID, $diff, 3600 );
		}
	}
 
	/**
	 * Displays a warning notice with a list of failed Pardot items and a dismiss button
	 */
	public function pardot_alert(){
		// Set the type of notice to display. Can be 'warning', 'error', 'info', or 'success'
		$notice_type = 'warning';

		// Get the current post ID
		$post_id = get_the_ID();
		
		// Set the transient name to a string with the post ID appended
		$pardot_transient_name = 'pardot_transient_'.$post_id;
		
		// Get the transient value with the given name
		$pardot_transients = get_transient($pardot_transient_name);
		
		// If the transient value is not empty
		if(!empty($pardot_transients)){
			// Echo a div with a notice and a dismiss button
			echo "<div class='notice notice-$notice_type is-dismissible'><p>";
			
			// Echo a message asking the user to contact the devops team
			echo "Attention: The following items have failed to load from pardot. Please contact the devops team with the provided field name for further assistance. <br>";
			
			// Echo each item in the transients array as a list item
			echo '<ul style="list-style: decimal;padding-left: 30px;">';
			foreach($pardot_transients as $pardot_transient){
				echo '<li>' . $pardot_transient . '</li>';
			}
			echo '</ul>';			
			// Close the div
			echo "</p></div>";

			//Delete Transient 
			delete_transient($pardot_transient_name);
		}
	}

}
