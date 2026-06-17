<?php

if ( !function_exists( 'register_activation_hook' ) ) {
	echo "This page cannot be called directly.";
	exit;
}

	/*
	 * LANGUAGE PRIORITY TABLE QUERIES FOR LessonZone Post Attachment
	 * Reminder: When Adding Additional Languages/Countries, Ensure That The
	 * Appropriate Country is also added to country Array in 'lzPA-countryList.php' File!!!
	 */

	global $wpdb;
	
	$table = $wpdb->prefix ."lessonzone_languagecode";
	$structure = "drop table if exists $table";
	$wpdb->query($structure);
	
	
	//On Install Create Language Table in WPDB
	$lzPA_tableName = $wpdb->prefix ."lessonzone_languagecode";
	$lzPA_qry = "CREATE TABLE IF NOT EXISTS $lzPA_tableName (	ID INT(13) NOT NULL AUTO_INCREMENT,
												LANG_BASE VARCHAR(80) NOT NULL,
												LANG_TYPE VARCHAR(80) NOT NULL,
												LANG_NAME VARCHAR(80) NOT NULL,
												NAME_CODE VARCHAR(2),
												LANG_CODE VARCHAR(3) NOT NULL,
												LANG_VAR VARCHAR (3) NULL,
												UNIQUE KEY ID (ID),
												UNIQUE (LANG_CODE) );";
	$wpdb->query($lzPA_qry);
	$wpdb->query("TRUNCATE $lzPA_tableName");
	error_log('new table: ' . $lzPA_tableName);
	
	$checkDB = $wpdb->get_var("SELECT * FROM ".$wpdb->prefix."lessonzone_languagecode");
	if(!isset($checkDB))
	{
	error_log('Table Empty, run insert queries');
	//------------------------------------- Populate table -------------------------------------\\
	//------------ Universal Language -----------\\
	$lzPA_insertData = "INSERT  INTO $lzPA_tableName (LANG_BASE, LANG_TYPE, LANG_NAME, LANG_CODE) VALUES ('English', 'Universal','Universal - No Language','Z01');";
						$wpdb->query($lzPA_insertData);
	
	//-----------------English-------------------\\
	//Global English
	$lzPA_insertData = "INSERT  INTO $lzPA_tableName (LANG_BASE, LANG_TYPE, LANG_NAME, LANG_CODE) VALUES ('English', 'Base','Global English','E01');";
						$wpdb->query($lzPA_insertData);
	
	//English Language Variants
	$lzPA_insertData = "INSERT  INTO $lzPA_tableName (LANG_BASE, LANG_TYPE, LANG_NAME, LANG_CODE) VALUES ('English', 'Language','Std (UK) English','E02');";
						$wpdb->query($lzPA_insertData);
	$lzPA_insertData = "INSERT  INTO $lzPA_tableName (LANG_BASE, LANG_TYPE, LANG_NAME, LANG_CODE) VALUES ('English', 'Language','U.S English','E03');";
						$wpdb->query($lzPA_insertData);
						
	//English Country Variants
	$lzPA_insertData = "INSERT  INTO $lzPA_tableName (LANG_BASE, LANG_TYPE, LANG_NAME, NAME_CODE, LANG_CODE, LANG_VAR) VALUES ('English', 'Country', 'Australia',		'AU', 'E12', 'E02');";
						$wpdb->query($lzPA_insertData);
	$lzPA_insertData = "INSERT  INTO $lzPA_tableName (LANG_BASE, LANG_TYPE, LANG_NAME, NAME_CODE, LANG_CODE, LANG_VAR) VALUES ('English', 'Country', 'Canada',			'CA', 'E15', 'E03');";
						$wpdb->query($lzPA_insertData);
	$lzPA_insertData = "INSERT  INTO $lzPA_tableName (LANG_BASE, LANG_TYPE, LANG_NAME, NAME_CODE, LANG_CODE, LANG_VAR) VALUES ('English', 'Country', 'Ireland',			'IE', 'E11', 'E02');";
						$wpdb->query($lzPA_insertData);
	$lzPA_insertData = "INSERT  INTO $lzPA_tableName (LANG_BASE, LANG_TYPE, LANG_NAME, NAME_CODE, LANG_CODE, LANG_VAR) VALUES ('English', 'Country', 'New Zealand',		'NZ', 'E13', 'E02');";
						$wpdb->query($lzPA_insertData);
	$lzPA_insertData = "INSERT  INTO $lzPA_tableName (LANG_BASE, LANG_TYPE, LANG_NAME, NAME_CODE, LANG_CODE, LANG_VAR) VALUES ('English', 'Country', 'South Africa',	'ZA', 'E16', 'E03');";
						$wpdb->query($lzPA_insertData);
	$lzPA_insertData = "INSERT  INTO $lzPA_tableName (LANG_BASE, LANG_TYPE, LANG_NAME, NAME_CODE, LANG_CODE, LANG_VAR) VALUES ('English', 'Country', 'United Kingdom',	'GB', 'E10', 'E02');";
						$wpdb->query($lzPA_insertData);
	$lzPA_insertData = "INSERT  INTO $lzPA_tableName (LANG_BASE, LANG_TYPE, LANG_NAME, NAME_CODE, LANG_CODE, LANG_VAR) VALUES ('English', 'Country', 'Unites States',	'US', 'E14', 'E03');";
						$wpdb->query($lzPA_insertData);
	
	//---------------- Spanish ------------------\\
	//Global Spanish
	$lzPA_insertData = "INSERT  INTO $lzPA_tableName (LANG_BASE, LANG_TYPE, LANG_NAME, LANG_CODE) VALUES ('Spanish', 'Base','Global Spanish','S03');";
						$wpdb->query($lzPA_insertData);
						
	//Spanish Language Variants
	$lzPA_insertData = "INSERT  INTO $lzPA_tableName (LANG_BASE, LANG_TYPE, LANG_NAME, LANG_CODE) VALUES ('Spanish', 'Language','European Spanish','S02');";
						$wpdb->query($lzPA_insertData);
	/* $lzPA_insertData = "INSERT  INTO $lzPA_tableName (LANG_BASE, LANG_TYPE, LANG_NAME, LANG_CODE) VALUES ('Spanish', 'Language','Latin American Spanish','S03');";
						$wpdb->query($lzPA_insertData); */
	
	//Spanish Country Variants
	$lzPA_insertData = "INSERT  INTO $lzPA_tableName (LANG_BASE, LANG_TYPE, LANG_NAME, NAME_CODE, LANG_CODE) VALUES ('Spanish', 'Country','U.S.A.','US','S10');";
						$wpdb->query($lzPA_insertData);
	$lzPA_insertData = "INSERT  INTO $lzPA_tableName (LANG_BASE, LANG_TYPE, LANG_NAME, NAME_CODE, LANG_CODE) VALUES ('Spanish', 'Country','Mexico','MX','S11');";
						$wpdb->query($lzPA_insertData);
	$lzPA_insertData = "INSERT  INTO $lzPA_tableName (LANG_BASE, LANG_TYPE, LANG_NAME, NAME_CODE, LANG_CODE) VALUES ('Spanish', 'Country','Chile','CL','S12');";
						$wpdb->query($lzPA_insertData);
	$lzPA_insertData = "INSERT  INTO $lzPA_tableName (LANG_BASE, LANG_TYPE, LANG_NAME, NAME_CODE, LANG_CODE) VALUES ('Spanish', 'Country','Argentina','AR','S13');";
						$wpdb->query($lzPA_insertData);
	$lzPA_insertData = "INSERT  INTO $lzPA_tableName (LANG_BASE, LANG_TYPE, LANG_NAME, NAME_CODE, LANG_CODE) VALUES ('Spanish', 'Country','Spain','ES','S14');";
						$wpdb->query($lzPA_insertData);
	
	//---------------- Chinese ------------------\\
	//Global Chinese
	$lzPA_insertData = "INSERT  INTO $lzPA_tableName (LANG_BASE, LANG_TYPE, LANG_NAME, LANG_CODE) VALUES ('Chinese', 'Base','Global Chinese','C02');";
						$wpdb->query($lzPA_insertData);
						
	//Chinese Language Variants
/* 	$lzPA_insertData = "INSERT  INTO $lzPA_tableName (LANG_BASE, LANG_TYPE, LANG_NAME, LANG_CODE) VALUES ('Chinese', 'Language','Simplified Chinese','C02');";
						$wpdb->query($lzPA_insertData);
	$lzPA_insertData = "INSERT  INTO $lzPA_tableName (LANG_BASE, LANG_TYPE, LANG_NAME, LANG_CODE) VALUES ('Chinese', 'Language','Traditional Chinese','C03');";
						$wpdb->query($lzPA_insertData); */
						
	//Chinese Country Variants
	$lzPA_insertData = "INSERT  INTO $lzPA_tableName (LANG_BASE, LANG_TYPE, LANG_NAME, NAME_CODE, LANG_CODE) VALUES ('Chinese', 'Country','China','CN','C10');";
						$wpdb->query($lzPA_insertData);
	$lzPA_insertData = "INSERT  INTO $lzPA_tableName (LANG_BASE, LANG_TYPE, LANG_NAME, NAME_CODE, LANG_CODE) VALUES ('Chinese', 'Country','Taiwan','TW','C11');";
						$wpdb->query($lzPA_insertData);
	$lzPA_insertData = "INSERT  INTO $lzPA_tableName (LANG_BASE, LANG_TYPE, LANG_NAME, NAME_CODE, LANG_CODE) VALUES ('Chinese', 'Country','Hong Kong','HK','C12');";
						$wpdb->query($lzPA_insertData);
	$lzPA_insertData = "INSERT  INTO $lzPA_tableName (LANG_BASE, LANG_TYPE, LANG_NAME, NAME_CODE, LANG_CODE) VALUES ('Chinese', 'Country','Singapore','SG','C13');";
						$wpdb->query($lzPA_insertData);
	//---------------- Hindi ------------------\\	
	//Global Hindi
	$lzPA_insertData = "INSERT  INTO $lzPA_tableName (LANG_BASE, LANG_TYPE, LANG_NAME, LANG_CODE) VALUES ('Hindi', 'Base', 'Global Hindi','H01');";
	$wpdb->query($lzPA_insertData);
	//---------------- French ------------------\\
	//Global French
	$lzPA_insertData = "INSERT  INTO $lzPA_tableName (LANG_BASE, LANG_TYPE, LANG_NAME, LANG_CODE) VALUES ('French', 'Base' ,'Global Chinese','F01');";
	$wpdb->query($lzPA_insertData);
	}
	if(isset($checkDB))
	{
		error_log('Table Populated, Do not run insert queries');
	}
?>