<?php
/*
Plugin Name: LessonZone - Post Attachment
Plugin URI:
Description: This Plugin Displays all the PDF files Attached to a Post Type Resource page and displays them in order according to Language Priority.
Author: ESISS Pty Ltd.
Author URI:
Version: 0.1
License: GPLv2
*/


/*================================================================================
						Start Post Attchment Plugin
=================================================================================*/
//Prepare Install / Unistall Functions
register_activation_hook(__FILE__,'lessonZonePA_install');
register_deactivation_hook(__FILE__ , 'lessonZonePA_uninstall' );

//Install Function
function lessonZonePA_install()
{
	require_once(WP_PLUGIN_DIR.'/lessonZone-postAttachment/lzPA-tableData.php');
}
//Uninstall Function
function lessonZonePA_uninstall()
{
	//On Uninstall, remove Language Table from WPDB
	global $wpdb;

	$lzPA_tableName = $wpdb->prefix ."lessonzone_languagecode";
	$lzPA_deleteQry = "drop table if exists $lzPA_tableName";
	$wpdb->query($lzPA_deleteQry);
}



// ------------------------------------------------------------------
// Add all your sections, fields and settings during admin_init
// ------------------------------------------------------------------
//

function lzPA_settings_init() {

	// Add the field with the names and function to use for our new
	// settings, put it in our new section
	add_settings_field('lzPA_setting_country',
	'Set Default Country',
	'lzPA_setting_callback',
	'general',
	'default');

	// Register our setting so that $_POST handling is done for us and
	// our callback function just has to echo the <input>
	register_setting('general','lzPA_setting_country');
}// eg_settings_api_init()

add_action('admin_init', 'lzPA_settings_init');

//Setting Callback Function
function lzPA_setting_callback()
{
 	require_once(WP_PLUGIN_DIR.'/lessonZone-postAttachment/lzPA-countryList.php');


	echo '<select name="lzPA_setting_country" style="width: 300px;">';
	foreach ($lzPA_tableCountry as $language => $countries)
	{
		foreach($countries as $country)
		{
			$thisCountry = $language.'/'.$country;
			?><option value="<?php echo $thisCountry; ?>" <?php selected ( get_option("lzPA_setting_country") == $thisCountry ); ?> ><?php echo $country; ?></option><?php
		}
	}
	echo '</select>';

}
/*
 * ---------------------------------- Collect Resource PDF files From AWS ----------------------------------
 */
function lessonZone_postAttachment_display( $content )
{
	$checkReturn = $content;
	if ( is_singular(array('post', 'ebook')) || $checkReturn == 'collection' )
	{
			global $wpdb;
			global $wp_user;
			$wp_user = wp_get_current_user();
			$thisUser = $wp_user->ID;
			global $woocommerce;

			//Store Other Resource Variables
			$resource_ID = get_the_ID();
			$resource_title = get_the_title();
			//get esiss_resource_id meta data for this Resource
			$resource_meta_id = get_post_meta($resource_ID, 'esiss_resource_id', true);


			if(has_term('clip-art', 'resource-type', $resource_ID))
			{
				$isClipArt = 'yes';
			}
			else
			{
				$isClipArt = 'no';
			}

			if(get_post_meta($resource_ID, 'esiss_has_answer_sheet', true))
			{
				$hasAnswer = 'yes';
			}
			else
			{
				$hasAnswer = 'no';
			}

			$currentRes_pdfCode = '';

			$resourceUploadDIR = WP_UPLOAD_DIR();
			$resourceDIR = $resourceUploadDIR['path'] .'/'.$resource_meta_id.'/';
			$resourceDIR = str_replace( '\\', '/', $resourceDIR );

			//Get Country From WP general setting 'lzPA_setting_country' Option
			$getCountry = get_option('lzPA_setting_country');
			if(isset($getCountry))
			{
				$splitCountry = explode("/", $getCountry);

				$getCountryLang = $splitCountry[0];
				$getCountry = $splitCountry[1];
			}
			if(!isset($getCountry))
			{
				$getCountry = 'Australia';
				$getCountryLang = 'English';
			}


		/*
		 * // ----------------------------- LANGUAGE CODE VERIFICATION ------------------------------ \\
		 * Verify Current Country Code Exists in Language DB
		 * Determine What Level of Code to Filter
		 * Filter follows the following tier:
		 * Country Variant -> Language Variant -> Base Language -> Universal Language
		 */

			//Get DB entry for Current Country
			$chklanguagedb 		= "SELECT * FROM ".$wpdb->prefix."lessonzone_languagecode WHERE LANG_NAME = '".$getCountry."'";
			$userCountry   		= $wpdb->get_row($chklanguagedb);
			//If Country Exists Store Code
			if (isset( $userCountry ))
			{
				$countryCode = $userCountry->LANG_CODE;
				//If selected Country has secondary Country Code, Store for later use
				if ( isset($userCountry->LANG_VAR) )
				$langVarCode	 = $userCountry->LANG_VAR;
				//Grab Base Language
				$chklanguagedb 		= "SELECT LANG_CODE FROM ".$wpdb->prefix."lessonzone_languagecode WHERE LANG_BASE = '".$getCountryLang."' AND LANG_TYPE = 'Base'";
				$baseLangCode  		= $wpdb->get_var($chklanguagedb);
				//Define Universal Language
				$universeCode = 'Z01';

				//Get Resource + Code For PDF Name
				$countryTierCode 	= $resource_meta_id . $countryCode;
				$languageTierCode 	= $resource_meta_id . $langVarCode;
				$baseTierCode 		= $resource_meta_id . $baseLangCode;
				$universeTierCode 	= $resource_meta_id . $universeCode;

				$codeCheck  = 'Country Variant: 	'. $countryCode;
				$codeCheck .= ' Language Variant: 	'. $langVarCode;
				$codeCheck .= ' Base Language: 		'. $baseLangCode;
				$codeCheck .= ' Universal Language: '. $universeCode;
				//echo('codeCheck: '.$codeCheck);

				/*
				 * ------------------ Attachment Grab / Loop / Filter ------------------------
				 * Default Filname structure : resourceCode (6) + CountryCode (3) _ filename + filetype
				 * Example Sheet filename:			110325E11_examplesheetname.jpeg
				 * Example Answer Sheet filename:	110325E11A_examplesheetname.jpg
				 * PageNumbers After Titlename: 	110325E11_examplesheetname2.jpeg
				 */
				if( has_term('clip-art', 'resource-type', $resource_ID) ):
					//Get Country Code PDF File Attachment and Store Filelocation
					$args = array (	'post_type'			=> 'attachment',
									'post_parent'		=> $resource_ID,
									'post_mime_type'   	=> 'image/png',
									'posts_per_page' 	=> -1);
					//error_log("searching for clipart: $args");
				else :
					//Get Country Code PDF File Attachment and Store Filelocation
					$args = array (	'post_type'			=> 'attachment',
									'post_parent'		=> $resource_ID,
									'post_mime_type'   	=> 'application/pdf',
									'posts_per_page' 	=> -1);
					//error_log("searching for PDF's: $args");
				endif;

				$resource_pdfs = get_posts($args);
				/*
				 * -------------------------- ATTACHMENT LOOP --------------------------
				 * Store All PDF Attachments in Arrays Depending on Language Code found in Title
				 */
				if(count($resource_pdfs) !== 0)
				{
					$countryCount 	= 0;
					$languageCount 	= 0;
					$baseCount 		= 0;
					$universeCount 	= 0;

					foreach ($resource_pdfs as $pdf)
					{
						$attachName = $pdf->post_title;
						$nameArray = explode("_", $attachName);
						$attachCode = $nameArray[0];

						//If Attachment has a Country Code, Store in resCountryArray
						if($attachCode == $countryTierCode)
						{
							$countryCount++;
						}
						//If Attachment has a Language Code, Store in resLanguageArray
						if($attachCode == $languageTierCode)
						{
							$languageCount++;
						}
						//If Attachment has a Base Code, Store in resBaseArray
						if($attachCode == $baseTierCode)
						{
							$baseCount++;
						}
						//If Attachment has a Universal Code, Store in resUniArray
						if($attachCode == $universeTierCode)
						{
							$universeCount++;
						}

					}		//End For Loop


					//echo $countryCount.' / '.$languageCount.' / '.$baseCount.' / '.$universeCount;
					//Find Highest Tier Code
					//Heirachy as follows: Country Code -> Langauge Code -> Base Code -> Unviersal
					if($countryCount !== 0)
					{
						$currentRes_pdfCode = $countryTierCode;
					}
					else if($languageCount !== 0)
					{
						$currentRes_pdfCode = $languageTierCode;
					}
					else if($baseCount !== 0)
					{
						$currentRes_pdfCode = $baseTierCode;
					}
					else if($universeCount !== 0)
					{
						$currentRes_pdfCode = $universeTierCode;
					}
					else {}
					//$hasAnswer = 'yes';
					//echo $currentRes_pdfCode;
					$pdfData = '<div id="lzpa-postAttachData">';
					$pdfData .= "<input type='hidden' id='lzPA_resourceID' value='$resource_meta_id' />";
					$pdfData .= '<input type="hidden" id="lzPA_hasAnswer" value="'.$hasAnswer.'"/>';
					$pdfData .= '<input type="hidden" id="lzPA_hasClipArt" value="'.$isClipArt.'" />';

					foreach ($resource_pdfs as $pdf)
					{
						$pdfName = $pdf->post_title;

						$nameArray = explode("_", $pdfName);
						$pdfCode = $nameArray[0];

						if($pdfCode == $currentRes_pdfCode)
						{
 							$pdfFileName = $pdf->post_title;

							//Add post Attach Data to Post display
							$pdfData .= '<input type="hidden" id="lzPA_code" value="'.$currentRes_pdfCode.'" />';
							$pdfData .= '<input type="hidden" id="lzPA_pdfFileID" value="'.$pdf->ID.'" />';
							$pdfData .= '<input type="hidden" id="lzPA_pdfFileName" value="'.$pdfFileName.'"/>';
						}
						if($pdfCode == $currentRes_pdfCode.'A' && $hasAnswer == "yes")
						{
							$pdfFileName = $pdf->post_title;

							//Add post Attach Data to Post display
							$pdfData .= '<input type="hidden" id="lzPA_ansFileID" value="'.$pdf->ID.'" />';
							$pdfData .= '<input type="hidden" id="lzPA_ansFileName" value="'.$pdfFileName.'"/>';
						}
					}

					$pdfData .= '</div><!-- end lzpa-postAttachData -->';

					if($checkReturn == 'collection')
					{
						$content = $pdfData;
					}
					else if(is_singular(array('post','ebook')))
					{
						$content .= $pdfData;
					}


				}//End if resource Count
				else
				{
					$content = '';
				}

			} //End if( isset(userCountry) )


			//If Country Doesnt Exist Display Error
			if (!isset( $userCountry ))
			{
				$content = "<script type='text/javascript'>alert('NO COUNTRY FOUND');</script>";
			}

	}

	if($checkReturn == 'collection')
	{
		return $content;
	}
	else if(is_singular(array('post','ebook')) )
	{
		echo $content;
	}
	wp_reset_postdata();

}
add_action('lzPA_singlePost_hook', 'lessonZone_postAttachment_display');

/*
 * ---------------------------------- ALTERNATIVE RESOURCE LANGUAGES  ----------------------------------
*/

function lessonZone_postAttachment_altLanguages( $content )
{
	if ( is_singular(array('post', 'ebook')) )
	{
			global $wpdb;
			global $wp_user;
			$wp_user = wp_get_current_user();
			$thisUser = $wp_user->ID;
			global $woocommerce;
			global $lzaws;

			//get esiss_resource_id meta data for this Resource
			$resource_meta_id = get_post_meta ( get_the_ID(), 'esiss_resource_id', true);
			//Store Other Resource Variables
			$resource_ID = get_the_ID();
			$resource_title = get_the_title();

			$checkForAnswer = get_post_meta(get_the_ID(), 'esiss_has_answer_sheet', true);

			if($checkForAnswer)
			{
				$hasAnswer = 'yes';
			}
			else
			{
				$hasAnswer = 'no';
			}

			//Get Country From WP general setting 'lzPA_setting_country' Option
			$getCountry = get_option('lzPA_setting_country');
			if(isset($getCountry))
			{
				$splitCountry = explode("/", $getCountry);
				$getCountry = $splitCountry[1];
				$getCountryLang = $splitCountry[0];
			}
			if(!isset($getCountry))
			{
				$getCountry = 'Australia';
				$getCountryLang = 'English';
			}

			//Determine if Resource Has Attachments with Base Languages Other Than the Set Default

			//Get All Codes for Base Languages That arent the Default Language Being Used
			$getBaseLanguages = "SELECT * FROM ".$wpdb->prefix."lessonzone_languagecode WHERE LANG_BASE != '".$getCountryLang."' AND LANG_TYPE = 'Base'";
			$baseLanguages = $wpdb->get_results($getBaseLanguages);
			if(isset($baseLanguages))
			{
				$AltCountries = array();
				//If There are Resource Attachments that have non-default Base Code, store in Array for Display
				foreach($baseLanguages as $baseLanguage)
				{
					$baseCode = $baseLanguage->LANG_CODE;
					//$baseCode = strtolower($baseCode);
					$countryName = $baseLanguage->LANG_BASE;
					$ImgDisplay = null;
					$pdfDisplay = null;
					$countryDisplay = null;
					/*
					 * -------------------------- ATTACHMENT LOOP --------------------------
					* Store All Attachments in Arrays Depending on Language Code found in Title
					*/
					//Grab Resource's Attachements ( jpeg only )
					$args = array (	'post_type'			=> 'attachment',
									'post_parent'		=> $resource_ID,
									'post_mime_type'	=> 'application/pdf',
									'posts_per_page' 	=> -1);
					//Store Attachment Array
					$langVarPdfs = get_posts($args);
					if (count($langVarPdfs) > 0)
					{


						$pdfDisplay = '<div class="lzpa-languageVariantData">';
						$pdfDisplay .= "<input type='hidden' id='lzPA_resourceID' value='$resource_meta_id' />";
						$pdfDisplay .= '<input type="hidden" id="lzPA_langHasAnswer" value="'.$hasAnswer.'"/>';
						$pdfDetails = '';

						foreach ($langVarPdfs as $pdf)
						{
							$pdfName = $pdf->post_title;
							$nameArray = explode("_", $pdfName);
							$pdfCode = $nameArray[0];
							$getTitle = $nameArray[1];
							$spacedTitle = preg_replace('/([A-Z])/', ' $1', $getTitle);
							$pdfTitle = trim($spacedTitle);

							if($pdfCode == $resource_meta_id . $baseCode)
							{
								$resCount = 0;
								//Grab Resource's Attachements ( jpeg only )
								$args = array (	'post_type'			=> 'attachment',
												'post_parent'		=> $resource_ID,
												'post_mime_type'	=> 'image/jpeg',
												'orderby'			=> 'title',
												'order'				=> 'ASC',
												'posts_per_page'	=> -1);
								$image_attachments = get_posts($args);
								foreach ($image_attachments as $image)
								{
									if($resCount == 0)
									{
										$attachName = $image->post_title;
										$nameArray = explode("_", $attachName);
										$attachCode = $nameArray[0];


										if($attachCode == $resource_meta_id . $baseCode)
										{
											$imgsrc = get_post_meta($image->ID, 'post_image', true);
											$imgwidth = 200;
											$imgheight = 267;
											if(!$imgsrc) {
												$attached 	= wp_get_attachment_image_src(get_post_thumbnail_id($image->ID), 'full');
												$imgsrc 	= $attached[0];
												$imgwidth 	= $attached[1];
												$imgheight 	= $attached[2];
											}

											$size = ( get_post_meta(get_post_thumbnail_id($image->ID), 'size_info', true) != '')  ? get_post_meta(get_post_thumbnail_id($image->ID), 'size_info', true) : null;

											if($size && is_array($size)) {
												$imgwidth = ( (int)$size['width'] == 0 ) ? $imgwidth : (int)$size['width'];
												$imgheight = ( (int)$size['height'] == 0 ) ? $imgheight : (int)$size['height'];
											}
											//Resource Image
											$ImgDisplay = '<div class="lzPA-foreignResImg" id="foreignResImg-'.$image->ID.'">';
											$ImgDisplay .= '<img src="'.$image->guid .'" alt="'.$image->post_title.'" style="width:'.$imgwidth.';height:'.$imgheight.';" />';
											$ImgDisplay .= '</div>';

											//Resource Title
											$ImgDisplay .= '<div class="lzPA-foreignCountryTitle">'.$pdfTitle.'</div>';
											//Resource Buttons
											$ImgDisplay .= '<input type="button" class="lz-res btn-attach variant print foreignResBtns" id="lzPA-resPrint" value="Print" />';
											$ImgDisplay .= '<input type="button" class="lz-res btn-attach variant download foreignResBtns" id="lzPA-resDownload" value="Download" />';
											$resCount++;
										}//End Attachment if
									}
								}

								$pdfFileName = $pdf->post_title;
								//Add post Attach Data to Post display

								$pdfDisplay .= '<input type="hidden" id="lzPA_langVar" value="'.$countryName.'" />';
								$pdfDisplay .= '<input type="hidden" id="lzPA_pdfFileID" value="'.$pdf->ID.'"/>';
								$pdfDisplay .= '<input type="hidden" id="lzPA_pdfFileName" value="'.$pdfFileName.'" />';

						}//End PDF if
						if($pdfCode == $resource_meta_id.$baseCode.'A' && $hasAnswer == "yes")
						{
							$pdfFileName = $pdf->post_title;
							//Add post Attach Data to Post display


							$pdfDisplay .= '<input type="hidden" id="lzPA_langAnsFileID" value="'.$pdf->ID.'" />';
							$pdfDisplay .= '<input type="hidden" id="lzPA_langAnsFileName" value="'.$pdfFileName.'"/>';
						}

					}//End PDF foreach

					$pdfDisplay .= '</div>';

					if($ImgDisplay)
					{
						$countryDisplay  = '<div class="lzPA-ForeignCountry" id="fcid-'.$baseCode.'">';
						//Language Name
						if($countryName == 'Chinese')
							$countryName = 'Simplified Chinese';
						$countryDisplay .= '<label style="font-size: 12pt; display:block; margin: 10px 0px 10px 0px; height: auto; color:black;">'.$countryName.'</label>';

						$countryDisplay .= $ImgDisplay;
						$countryDisplay .= $pdfDisplay;
						$countryDisplay .= '</div><!--End altLanguage Wrap -->'; 	//END DIV - altLanguageWrap
						$AltCountries[] = $countryDisplay;
					}
					}//End Count PDF If
				}//End Base Language for each

				if(count($AltCountries) !==0 )
				{
					//Creating Wrap For This Display
					$content .= '<div id="lzPA-altCountriesWrap">';
					$content .= '<label style="font-size: 12pt;height: auto; display:block;margin-bottom: 10px;color:black;">';
					$content .= 'This Printable Teaching Resource is also available in these Languages</label>';
					//Display Gathered Foreign Resources

					foreach ($AltCountries as $country)
					{
						$content .= $country;
					}

					$content .= '</div>';
				}
			}//End isset base languages
			if(!isset($baseLanguages))
			{
				echo "<script type='text/javascript'>console.log('Database Error: No Database Languages were found.');</script>";
			}
	}//end if singular post

	echo $content;
}
add_action('lzPA_langPost_hook', 'lessonZone_postAttachment_altLanguages');

/*
 * --------------------JAKES CONFIRM FUNCTION------------------------------
 */
function isLessonZone_postGallery($attachment) {

	global $wpdb;
	$resource_meta_id = substr($attachment, 0, 6);
	$currentRes_pdfCode = '';

	//Get Country From WP general setting 'lzPA_setting_country' Option
	$getCountry = get_option('lzPA_setting_country');

	if (isset($getCountry)) {
		$splitCountry = explode("/", $getCountry);
		$getCountry = $splitCountry[1];
		$getCountryLang = $splitCountry[0];
	}

	if (!isset($getCountry)) {
		$getCountry = 'Australia';
		$getCountryLang = 'English';
	}
	/*

	* // ----------------------------- LANGUAGE CODE VERIFICATION ------------------------------ \\
	* Verify Current Country Code Exists in Language DB
	* Determine What Level of Code to Filter
	* Filter follows the following tier:
	* Country Variant -> Language Variant -> Base Language -> Universal Language
	*/

	//Get DB entry for Current Country
	$chklanguagedb = "SELECT * FROM " . $wpdb->prefix . "lessonzone_languagecode WHERE LANG_NAME = '" . $getCountry . "'";
	$userCountry = $wpdb->get_row($chklanguagedb);

	//If Country Exists Store Code
	if (isset($userCountry)) {
		$countryCode = $userCountry->LANG_CODE;
		//If selected Country has secondary Country Code, Store for later use
		if (isset($userCountry->LANG_VAR))
			$langVarCode = $userCountry->LANG_VAR;
		//Grab Base Language
		$chklanguagedb = "SELECT LANG_CODE FROM " . $wpdb->prefix . "lessonzone_languagecode WHERE LANG_BASE = '" . $getCountryLang . "' AND LANG_TYPE = 'Base'";
		$baseLangCode = $wpdb->get_var($chklanguagedb);
		//Define Universal Language
		$universeCode = 'Z01';
		//Get Resource + Code For PDF Name
		$countryTierCode = $resource_meta_id . $countryCode;
		$languageTierCode = $resource_meta_id . $langVarCode;
		$baseTierCode = $resource_meta_id . $baseLangCode;
		$universeTierCode = $resource_meta_id . $universeCode;
		/*
		* ------------------ Attachment Grab / Loop / Filter ------------------------
		* Default Filname structure : resourceCode (6) + CountryCode (3) _ filename + filetype
		* Example Sheet filename:                                 110325E11_examplesheetname.jpeg
		* Example Answer Sheet filename:                110325E11A_examplesheetname.jpg
		* PageNumbers After Titlename:    110325E11_examplesheetname2.jpeg
		*/
		/*
		* -------------------------- ATTACHMENT LOOP --------------------------
		* Store All Attachments in Arrays Depending on Language Code found in Title
		*/
		$nameArray = explode("_", $attachment);
		$attachCode = $nameArray[0];
		//If Attachment has a Country Code, Store in resCountryArray
		if ($attachCode == $countryTierCode) {
			return true;
		}
		//If Attachment has a Language Code, Store in resLanguageArray
		if ($attachCode == $languageTierCode) {
			return true;
		}
		//If Attachment has a Base Code, Store in resBaseArray
		if ($attachCode == $baseTierCode) {
			return true;
		}
		//If Attachment has a Universal Code, Store in resUniArray
		if ($attachCode == $universeTierCode) {
			return true;
		}
	}
	return false;
}



//Hook my area javascript in admin
add_action('admin_print_scripts', 'lessonzone_postAttachment_css');
//Hook my area javascript in template
add_action('wp_enqueue_scripts', 'lessonzone_postAttachment_css');

function lessonzone_postAttachment_css()
{
	//Post Attachment CSS enqueue
	wp_register_style( 'lzPA-css',  plugin_dir_url(__FILE__).'/lzPA-style.css');
	wp_enqueue_style( 'lzPA-css' );

}


function lzpa_return_current_country() {
	$s_option = get_option('lzPA_setting_country');
	$a_option = array('language' => 'English', 'country' => 'Australia');

	if ( isset( $s_option ) && ! empty( $s_option ) ) {
		$a_country = explode('/', $s_option);
		$a_option['language'] = $a_country[0];
		$a_option['country'] = $a_country[1];
	}

	return $a_option;
}

function lzpa_get_current_country() {
	global $wpdb;
	$a_current_country = lzpa_return_current_country();
	//Get DB entry for Current Country
	$s_table = $wpdb->prefix.'lessonzone_languagecode';
	$s_query = 'SELECT * FROM '.$s_table.' WHERE LANG_NAME = %s';
	$o_country = $wpdb->get_row(
		$wpdb->prepare( $s_query, $a_current_country['country'])
	);

	//If Country Exists Store Code
	if ( isset( $o_country ) && ! empty($o_country) ) {
		return $o_country;
	}

	return NULL;
}

function lzpa_resource_codes($i_resid = NULL, $o_country = NULL ) {
	$a_codes = array(
		'country' 	=> NULL,
		'language' 	=> NULL,
		'base' 		=> NULL,
		'universal' => NULL
	);

	if ( ! isset( $i_resid, $o_country ) && empty($i_resid) && empty($o_country) ) {
		return $a_codes;
	}

	$a_current_country = lzpa_return_current_country();

	global $wpdb;
	$s_table = $wpdb->prefix.'lessonzone_languagecode';
	$s_query = 'SELECT LANG_CODE FROM '.$s_table.' WHERE LANG_BASE = %s AND LANG_TYPE = %s';

	$s_basecode = $wpdb->get_var(
		$wpdb->prepare($s_query, $a_current_country['language'], 'Base')
	);

	$a_codes = array(
		'country' 	=> isset( $o_country->LANG_CODE ) ? $i_resid . $o_country->LANG_CODE : NULL,
		'language' 	=> isset( $o_country->LANG_VAR )  ? $i_resid . $o_country->LANG_VAR : NULL,
		'base' 		=> ( isset( $s_basecode ) && ! empty($s_basecode) ) ? $i_resid . $s_basecode : NULL,
		'universal' => $i_resid . 'Z01'
	);

	//error_log('LZPA Resource Codes: '.print_r($a_codes, true));

	return $a_codes;
}

function lzpa_get_highest_tier_attachment($a_codes = array(), $a_post_resources = array()) {
	if ( ! isset($a_codes, $a_post_resources) || empty($a_codes) || empty($a_post_resources) ) {
		return $a_codes['universal'];
	}

	$a_counter = array(
		'country' 	=> 0,
		'language' 	=> 0,
		'base' 		=> 0,
		'universal' => 0
	);

	foreach ( $a_post_resources as $o_attachment ) {
		$s_title = $pdf->post_title;
		$a_title = explode("_", $s_title);
		$s_postcode = $a_title[0];

		//Find all Available Attachments with These Codes
		//Determine Highest available teired attachment
		switch ( $s_postcode ) {
			case $a_code['country'] :
				$a_counter['country']++;
				break;
			case $a_code['language'] :
				$a_counter['language']++;
				break;
			case $a_code['base'] :
				$a_counter['base']++;
				break;
			case $a_code['universal'] :
				$a_counter['universal']++;
				break;
		}
	}		//End For Loop

	//Find Highest Tier Code
	//Heirachy as follows: Country Code -> Langauge Code -> Base Code -> Unviersal
	$s_high_tier = max( array_keys( $a_counter ) );
	error_log('Highest Tier Code For this Resource = '.$a_codes[$s_high_tier]);
	return $a_codes[$s_high_tier];
}


/*
 * Wushka Free Sample URL Generation
 */
function lzpa_wushka_sample_books( $i_id = null ) {

	if ( ! isset($i_id) || empty($i_id) ) {
		return NULL;
	}

	global $wpdb;
    $o_book = get_post($i_id);

    if ( ! isset($o_book) || empty($o_book) || $o_book->esiss_free_sample !== 'Y' ) {
		return NULL;
	}

	//Store Other Resource Variables

	$s_title = $o_book->post_title;
	$i_resid = $o_book->esiss_resource_id;


	$o_country = lzpa_get_current_country();
	if ( ! isset( $o_country ) || empty($o_country) ) {
		return NULL;
	}

	$a_codes = lzpa_resource_codes($i_resid, $o_country);

	/*
	 * ------------------ Attachment Grab / Loop / Filter ------------------------
	 * Default Filname structure : resourceCode (6) + CountryCode (3) _ filename + filetype
	 * Example Sheet filename:			110325E11_examplesheetname.jpeg
	 * Example Answer Sheet filename:	110325E11A_examplesheetname.jpg
	 * PageNumbers After Titlename: 	110325E11_examplesheetname2.jpeg
	 */
	//Get Country Code PDF File Attachment and Store Filelocation
	$args = array(
		'post_type'			=> 'attachment',
		'post_parent'		=> $i_id,
		'post_mime_type'   	=> '',
		'posts_per_page' 	=> -1
	);

	$a_post_resources = get_posts($args);
	if ( ! isset($o_book) || empty($o_book) || count($a_post_resources) <= 0 ) {
		return NULL;
	}

	//Determine Highest Tiered Attachment Available
	//$s_high_tier = lzpa_get_highest_tier_attachment($a_codes, $a_post_resources);
	//$s_ereader_url = esc_url( home_url('/').'ereader/?book=/Resources/'.$i_resid.'/'.$a_codes['language'].'/');
	$s_ereader_url = $a_codes['language'];

	return $s_ereader_url;
}
add_action('wushka_lzpa_sample_books', 'lzpa_wushka_sample_books');

/* ----- EOF ----- */
?>