<?php
include_once '../../../../wp-config.php';

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
//exits when file is load directly
if ( ! function_exists( 'add_action' ) ) {
	echo "This page cannot be called directly.";
	exit;
}
/** ============================================================================
 *
 * 							PDF Stamp Admin Page
 *
 * ============================================================================ **/
//Get the current logged in username to be printed on the server
global $current_user;
get_currentuserinfo();

if ( ! is_user_logged_in() || ! current_user_can( 'administrator' ) ) {
	wp_redirect( home_url() );
	exit();
}

error_log('---------- PDF STAMP OPTIONS PAGE ----------');

echo build_stamp_page_html($current_user);


function build_stamp_page_html($o_user = null) {
	//Get Options
	$a_options = get_option('pdf_stamp');
	//Check for POST data
	error_log('Found Post Data, Saving New Option Values');
	$b_options = save_new_stamp_options($a_options);
	$s_username = get_stamp_username($o_user);

	//Stamp Preview button
	$a_preview = build_stamp_preview_btn($s_username);
	//Page Wrap
	$a_page[] = '<div class="page-wrap stamp-wrap">';
		//Title
		$a_page[] = '<h2>PDF Stamp Settings</h2>';
		$a_page[] = implode('', $a_preview);
		$a_page[] = '<p>Here is a list of options for the PDF STAMP plugin.</p>';
		//Begin Form
		$a_page[] = '<form name="pdf_stamp" action="" method="post" enctype="multipart/form-data">';
			//Add validation nonce
			$a_page[] = '<input type="hidden" name="option_page" value="pdf_stamp" />';
			$a_page[] = wp_nonce_field('pdf_stamp_nonce-'.$current_user->ID, 'pdfstmp_nce', false);
			//Begin Table
			$a_page[] = '<table class="form-table"><tbody>';
				//Logo Image Section
				$a_page[] = '<tr>';
					//$a_page[] = '<th scope="row">Choose a LOGO Image to Stamp:</th>';
				$a_page[] = '</tr>';
			$a_page[] = '';
			$a_page[] = '';
			$a_page[] = '';
			$a_page[] = '';
				$a_page[] = '</table>';
		$a_page[] = '</form>';
	$a_page[] = '</div>';
}

function build_stamp_preview_btn( $s_username = NULL ) {
	if ( ! isset( $s_username ) ) {
		return FALSE;
	}

	$a_btn[] = '<div class="btn-wrap preview-wrap">';
		$a_btn[] = '<input type="button" class="btn-stamp preview" id="preview-stamp" value="Preview">';
		$a_btn[] = '<input type="hidden" id="preview-username" value="'.$s_username.'">';
	$a_btn[] = '</div>';

	return $a_btn;
}


function old_menu() {
?>


<div><form>


		<h3>Stamp Content Options</h3>
		<p>Please Choose the Content of the Stamper you want to stamp</p>
		<table class="form-table">
			<tbody>
				<tr>
					<th scope="row">Choose a LOGO Image to Stamp:</th>
					<td><input type="hidden" id="pdfStamp_setLogoImage"
						name="pdfStamp_options[pdfStamp_logo]"
						value="http://cdn5.lessonzone.com.au/Resources/lessonzone-logo.png">
						<div id="pdfStamp_logoImageWrap">
							<input type="text" id="pdfStamp_logoImageSearchBar"
								value="http://cdn5.lessonzone.com.au/Resources/lessonzone-logo.png">
							<div id="pdfStamp_logoImgLoader"></div>
							<div id="pdfStamp_logoImageList">
								<!--Content Will be Rendered Here -->
							</div>
						</div> <br> <input type="radio" id="logoSize-auto"
						name="pdfStamp_options[pdfStamp_logoSize]"
						style="margin-right: 5px;" value="auto" checked="checked">auto <em>&nbsp;&nbsp;-
							Display Logo Default Size/Ratio</em><br> <input type="radio"
						id="logoSize-newSet" name="pdfStamp_options[pdfStamp_logoSize]"
						style="margin-right: 5px;" value="newSet">newSet <em>&nbsp;&nbsp;-
							Choose Logo Width and Height </em><br> <input type="text"
						id="logoWidth" name="pdfStamp_options[pdfStamp_logoRes][width]"
						value="24"
						style="width: 40px; margin-left: 25px; margin-bottom: 5px;"> <em>Width
							(px)</em> <input type="text" id="logoHeight"
						name="pdfStamp_options[pdfStamp_logoRes][height]" value="24"
						style="width: 40px; margin-left: 25px; margin-bottom: 5px;"> <em>Height
							(px)</em></td>
				</tr>
				<tr>
					<th scope="row">Choose Text Colour:</th>
					<td>
						<!-- Run Javascript for Colour Picker --> <script
							type="text/javascript">
		jQuery(document).ready(function() {
    	jQuery('#textColourPicker').hide();
    	jQuery('#textColourPicker').farbtastic('#stamp-txtColour');
   	 	jQuery('#stamp-txtColour').click(function(){jQuery('#textColourPicker').slideDown()});
    	jQuery('#stamp-txtColour').blur(function(){jQuery('#textColourPicker').slideUp()});
 		});
	</script> <!-- Add HTML Code for Colour Picker --> <label
						for="txtColor"> <input type="text" id="stamp-txtColour"
							name="pdfStamp_options[pdfStamp_textColour]" value="#000000"
							style="color: rgb(255, 255, 255); background-color: rgb(0, 0, 0);">
					</label>
						<div id="textColourPicker"
							style="margin-left: 25px; display: none;">
							<div class="farbtastic">
								<div class="color" style="background-color: rgb(255, 0, 0);"></div>
								<div class="wheel"></div>
								<div class="overlay"></div>
								<div class="h-marker marker" style="left: 97px; top: 13px;"></div>
								<div class="sl-marker marker" style="left: 147px; top: 147px;"></div>
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<th scope="row">Set the Stamp Background Type:</th>
					<td>
						<!-- Add radio button for No Background Setting --> <input
						type="radio" id="stampBackground-none"
						name="pdfStamp_options[pdfStamp_background]"
						style="margin-right: 10px;" value="noBackground" checked="checked">
						No Background <br> <!-- Add radio button for Image Background Setting -->
						<input type="radio" id="stampBackground-image"
						name="pdfStamp_options[pdfStamp_background]"
						style="margin-right: 10px;" value="image"> Image Background - <em>Chose
							an Image to upload as the Stamps Background</em><br> <input
						type="hidden" id="pdfStamp_setBgImage"
						name="pdfStamp_options[pdfStamp_backgroundImage]" value="none">
						<div id="pdfStamp_bgImageWrap">
							<input type="text" id="pdfStamp_bgImageSearchBar" value="none">
							<div id="pdfStamp_bgImgLoader"></div>
							<div id="pdfStamp_bgImageList">
								<!-- Content will be Rendered Here -->
							</div>
						</div> <br> <!-- Run Javascript for Colour Picker --> <script
							type="text/javascript">
		jQuery(document).ready(function() {
    	jQuery('#backgroundColourpicker').hide();
    	jQuery('#backgroundColourpicker').farbtastic('#stamp-bgColour');
   	 	jQuery('#stamp-bgColour').click(function(){jQuery('#backgroundColourpicker').slideDown()});
    	jQuery('#stamp-bgColour').blur(function(){jQuery('#backgroundColourpicker').slideUp()});
 		});


	</script> <!-- Add HTML Code for Colour Picker --> <input type="radio"
						id="stampBackground-colour"
						name="pdfStamp_options[pdfStamp_background]"
						style="margin-right: 10px;" value="colour"> Colour Background - <em>Pick
							a Colour from the Wheel below</em><br> <label for="bgColour"> <input
							type="text" id="stamp-bgColour"
							name="pdfStamp_options[pdfStamp_backgroundColour]"
							style="margin-left: 25px; color: rgb(0, 0, 0); background-color: rgb(149, 246, 45);"
							value="#95f62d">
					</label>
						<div id="backgroundColourpicker"
							style="margin-left: 25px; display: none;">
							<div class="farbtastic">
								<div class="color" style="background-color: rgb(132, 255, 0);"></div>
								<div class="wheel"></div>
								<div class="overlay"></div>
								<div class="h-marker marker" style="left: 181px; top: 95px;"></div>
								<div class="sl-marker marker" style="left: 55px; top: 90px;"></div>
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<th scope="row">Adjust Content height From page edge (in pixels):</th>
					<td>
						<!-- Add HTML for Terms and Conditions Text field --> <input
						type="text" id="stamp-heightOffset"
						name="pdfStamp_options[pdfStamp_heightOffset]"
						style="width: 100px;" value="0"><em> pixels</em>
					</td>
				</tr>
				<tr>
					<th scope="row">Edit the Copyright Declaration: <br> <em>type:
							\xC2\xA9 to print Copyright Symbol</em></th>
					<td>
						<!-- Add HTML for Copyright Declaration Text field --> <input
						type="text" id="copyDeclaration"
						name="pdfStamp_options[pdfStamp_declaration]"
						style="width: 400px;"
						value="Copyright © Lesson Zone All Rights Reserved">
					</td>
				</tr>
				<tr>
					<th scope="row">Edit the Terms and Conditions WebLink:</th>
					<td>
						<!-- Add HTML for Terms and Conditions Text field --> <input
						type="text" id="tsandcs"
						name="pdfStamp_options[pdfStamp_termsAndConditions]"
						style="width: 400px;"
						value="http://lessonzone.com.au/terms-conditions/">
					</td>
				</tr>
				<tr>
					<th scope="row">Select a Custom Field to Stamp:</th>
					<td><select name="pdfStamp_options[pdfStamp_customField]">
							<option value="none" selected="selected">--- None ---</option>
					</select></td>
				</tr>
			</tbody>
		</table>
		<h3>
			<br> <br>Stamp Layout Options
		</h3>
		<p>Please Choose the Layout of the Page you wish to stamp:</p>
		<table class="form-table">
			<tbody>
				<tr>
					<th scope="row">Set Stamp Height (in Pixels):</th>
					<td>
						<!-- Add HTML for Stamp Height Setting Options --> <input
						type="text" id="stamp-stampHeight"
						name="pdfStamp_options[pdfStamp_stampHeight]"
						style="width: 100px;" value="40"> <em>(px) - Pick a Number between
							( 40 - 800 ). Default is 40.</em>
					</td>
				</tr>
				<tr>
					<th scope="row">Set Page Format:</th>
					<td>
						<!-- Add HTML for Page Format Drop Down List --> <select
						name="pdfStamp_options[pdfStamp_pageLayout]">
							<option value="default" selected="selected">default</option>
							<option value="portrait">portrait</option>
							<option value="landscape">landscape</option>
					</select>
					</td>
				</tr>
				<tr>
					<th scope="row">Choose the Stamp position on the page :</th>
					<td><input type="radio" id="stampPlacement-header"
						name="pdfStamp_options[pdfStamp_placement]"
						style="margin-right: 10px;" value="header">header<br> <input
						type="radio" id="stampPlacement-footer"
						name="pdfStamp_options[pdfStamp_placement]"
						style="margin-right: 10px;" value="footer" checked="checked">footer<br>
						<input type="radio" id="stampPlacement-watermark"
						name="pdfStamp_options[pdfStamp_placement]"
						style="margin-right: 10px;" value="watermark">watermark<br> <!-- Add HTML for Watermark Rotation value List -->
						<select name="pdfStamp_options[pdfStamp_wmRotation]"
						style="margin-left: 25px; margin-bottom: 5px;">
							<option value="90">Left 90 °</option>
							<option value="45">Left 45°</option>
							<option value="0" selected="selected">0° Rotation</option>
							<option value="-45">Right 45°</option>
							<option value="-90">Right 90°</option>
					</select></td>
				</tr>
			</tbody>
		</table>
		<br>
		<p>Click Here to see a Preview of The Stamp!</p>
		<div class="previewStamp">
			<input type="button" id="previewStamp-display" value="Preview Stamp!">
			<input type="hidden" id="previewStamp-userName" value="Jake Arnold">
		</div>
		<p>Click here to Save your chosen settings!</p>
		<p class="submit">
			<input name="submit" type="submit" id="pdfStamp_saveSettings"
				value="Save Settings">
		</p>
	</form>
</div>
<?php
}


function save_new_stamp_options( $a_options = NULL ) {
	if ( ! isset( $a_options ) ) {
		error_log('No Options Passed, Abort Saving.');
		return FALSE;
	}
	if ( $_SERVER['REQUEST_METHOD'] !== 'POST' || $_POST['option_page'] !== 'pdf_stamp' ) {
		error_log('No Options posted, Skip Saving.');
		return TRUE;
	}



	/* foreach ( $a_options as $i_key => $value ) {
		if ( isset( $_POST[$i_key] ) ) {
			$a_options[$i_key] = $_POST[$i_key];
		}
	} */

}


function get_stamp_username($o_user = null) {
	if ( ! isset($o_user) ) {
		return null;
	}
	// Name generation If first condition not met, go to next
	// 1. If exists, User Firstname and Lastname
	// 2. If Exists and is not the same as user login name, User Displayname
	// 3. User Nickname
	if ( ! empty($o_user->user_firstname) && ! empty($o_user->user_firstname) ) {
		$s_username = $o_user->user_firstname.' '.$o_user->user_lastname;
	} else if ( isset($o_user->display_name) && $o_user->display_name !== $o_user->user_login ) {
		$s_username = $user_info->display_name;
	} else {
		$s_username = $o_user->nickname;
	}

	return $s_username;
}

/* ---- EOF ---- */
