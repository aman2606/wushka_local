<?php
function spider_calendar_chech_update() {
  global $wpdb;
  $category = $wpdb->get_results("SHOW COLUMNS FROM ".$wpdb->prefix."spidercalendar_event");
  if( $wpdb->get_var("SHOW TABLES LIKE '" . $wpdb->prefix . "spidercalendar_theme'") === $wpdb->prefix . 'spidercalendar_theme' ) {
    $category_hide = $wpdb->get_results("SHOW COLUMNS FROM ".$wpdb->prefix."spidercalendar_theme");
    $cat_hide='not_exist';
  } else {
    $category_hide = array();
    $cat_hide='';
  } 
  if( $wpdb->get_var("SHOW TABLES LIKE '" . $wpdb->prefix . "spidercalendar_widget_theme'") === $wpdb->prefix . 'spidercalendar_widget_theme' ) {
    $category_hide_widget = $wpdb->get_results("SHOW COLUMNS FROM ".$wpdb->prefix."spidercalendar_widget_theme");	
    $cat_hide_widget='not_exist';
	$cat_hide_widget_ev_title='not_exist';
  } else {
    $category_hide_widget = array();
    $cat_hide_widget='';
	$cat_hide_widget_ev_title='';
  }	
  $calendar1 = $wpdb->get_results("SHOW COLUMNS FROM ".$wpdb->prefix."spidercalendar_calendar");
  $calexist1=0;
  $defmonth = 0;
  for($i=0;$i<count($calendar1);$i++){
    if($calendar1[$i]->Field=="def_month"){
	  $calexist1= 1;
	  $defmonth = 1;
	  break;
	}
  }
  //return;
  if($cat_hide == 'not_exist') {
    $max_id = $wpdb->get_var("SELECT MAX(id) FROM ".$wpdb->prefix."spidercalendar_theme");
  }
  else {
    $max_id = 0;
  }
  if ($calexist1==0 || $max_id = 0 || $max_id = 13) {
	  	for($i=0;$i<count($category_hide);$i++){
		  if($category_hide[$i]->Field=="show_cat"){
			$cat_hide=1;
			break;
		  }
		}
		if($cat_hide=='not_exist') {
		  $wpdb->query("ALTER TABLE " . $wpdb->prefix . "spidercalendar_theme ADD `show_cat` int(11) NOT NULL DEFAULT '1' AFTER `show_time`");		
		}
		
		for($i=0;$i<count($category_hide_widget);$i++){
		  if($category_hide_widget[$i]->Field=="show_cat"){
			$cat_hide_widget=1;
			break;
		  }
		}

		if($cat_hide_widget=='not_exist') {
		  $wpdb->query("ALTER TABLE " . $wpdb->prefix . "spidercalendar_widget_theme ADD `show_cat` int(11) NOT NULL DEFAULT '1' AFTER `week_start_day`");
		}
		
		for($i=0;$i<count($category_hide_widget);$i++){
		  if($category_hide_widget[$i]->Field=="ev_title_color"){
			$cat_hide_widget_ev_title=1;
			break;
		  }
		}
		
		if($cat_hide_widget_ev_title=='not_exist') {
		  $wpdb->query("ALTER TABLE ".$wpdb->prefix."spidercalendar_widget_theme  ADD `ev_title_color` varchar(255) AFTER title;");	
		}
		
	  $wpdb->query("DROP TABLE IF EXISTS `" . $wpdb->prefix . "spidercalendar_theme`");
	  $spider_theme_table = "CREATE TABLE IF NOT EXISTS `" . $wpdb->prefix . "spidercalendar_theme` (
	  `id` int(11) NOT NULL AUTO_INCREMENT,
	  `title` varchar(255) NOT NULL,
	  `width` varchar(255) NOT NULL,
	  `cell_height` varchar(255) NOT NULL,
	  `bg_top` varchar(255) NOT NULL,
	  `bg_bottom` varchar(255) NOT NULL,
	  `border_color` varchar(255) NOT NULL,
	  `text_color_year` varchar(255) NOT NULL,
	  `text_color_month` varchar(255) NOT NULL,
	  `text_color_week_days` varchar(255) NOT NULL,
	  `text_color_other_months` varchar(255) NOT NULL,
	  `text_color_this_month_unevented` varchar(255) NOT NULL,
	  `text_color_this_month_evented` varchar(255) NOT NULL,
	  `event_title_color` varchar(255) NOT NULL,
	  `current_day_border_color` varchar(255) NOT NULL,
	  `bg_color_this_month_evented` varchar(255) NOT NULL,
	  `next_prev_event_arrowcolor` varchar(255) NOT NULL,
	  `show_event_bgcolor` varchar(255) NOT NULL,
	  `cell_border_color` varchar(255) NOT NULL,
	  `arrow_color_year` varchar(255) NOT NULL,
	  `week_days_cell_height` varchar(255) NOT NULL,
	  `arrow_color_month` varchar(255) NOT NULL,
	  `text_color_sun_days` varchar(255) NOT NULL,
	  `title_color` varchar(255) NOT NULL,
	  `next_prev_event_bgcolor` varchar(255) NOT NULL,
	  `title_font_size` varchar(255) NOT NULL,
	  `title_font` varchar(255) NOT NULL,
	  `title_style` varchar(255) NOT NULL,
	  `date_color` varchar(255) NOT NULL,
	  `date_size` varchar(255) NOT NULL,
	  `date_font` varchar(255) NOT NULL,
	  `date_style` varchar(255) NOT NULL,
	  `popup_width` varchar(255) NOT NULL,
	  `popup_height` varchar(255) NOT NULL,
	  `number_of_shown_evetns` varchar(255) NOT NULL,
	  `sundays_font_size` varchar(255) NOT NULL,
	  `other_days_font_size` varchar(255) NOT NULL,
	  `weekdays_font_size` varchar(255) NOT NULL,
	  `border_width` varchar(255) NOT NULL,
	  `top_height` varchar(255) NOT NULL,
	  `bg_color_other_months` varchar(255) NOT NULL,
	  `sundays_bg_color` varchar(255) NOT NULL,
	  `weekdays_bg_color` varchar(255) NOT NULL,
	  `week_start_day` varchar(255) NOT NULL,
	  `weekday_sunday_bg_color` varchar(255) NOT NULL,
	  `border_radius` varchar(255) NOT NULL,
	  `year_font_size` varchar(255) NOT NULL,
	  `month_font_size` varchar(255) NOT NULL,
	  `arrow_size` varchar(255) NOT NULL,
	  `next_month_text_color` varchar(255) NOT NULL,
	  `prev_month_text_color` varchar(255) NOT NULL,
	  `next_month_arrow_color` varchar(255) NOT NULL,
	  `prev_month_arrow_color` varchar(255) NOT NULL,
	  `next_month_font_size` varchar(255) NOT NULL,
	  `prev_month_font_size` varchar(255) NOT NULL,
	  `month_type` varchar(255) NOT NULL,
	  `date_format` varchar(255) NOT NULL,
	  `show_time` int(11) NOT NULL,
	  `show_cat` int(11) NOT NULL,
	  `show_repeat` int(11) NOT NULL,
	  `date_bg_color` varchar(255) NOT NULL,
	  `event_bg_color1` varchar(255) NOT NULL,
	  `event_bg_color2` varchar(255) NOT NULL,
	  `event_num_bg_color1` varchar(255) NOT NULL,
	  `event_num_bg_color2` varchar(255) NOT NULL,
	  `event_num_color` varchar(255) NOT NULL,
	  `date_font_size` varchar(255) NOT NULL,
	  `event_num_font_size` varchar(255) NOT NULL,
	  `event_table_height` varchar(255) NOT NULL,
	  `date_height` varchar(255) NOT NULL,
	  `ev_title_bg_color` varchar(255) NOT NULL,
	  `week_font_size` varchar(255) NOT NULL,
	  `day_month_font_size` varchar(255) NOT NULL,
	  `week_font_color` varchar(255) NOT NULL,
	  `day_month_font_color` varchar(255) NOT NULL,
	  `views_tabs_bg_color` varchar(255) NOT NULL,
	  `views_tabs_text_color` varchar(255) NOT NULL,
	  `views_tabs_font_size` varchar(255) NOT NULL,
	  `day_start` int(11) NOT NULL,
	  PRIMARY KEY (`id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8;";
	$wpdb->query($spider_theme_table);
	$spider_theme_rows = "INSERT INTO `" . $wpdb->prefix . "spidercalendar_theme` (`id`,`title`, `width`, `cell_height`, `bg_top`, `bg_bottom`, `border_color`, `text_color_year`, `text_color_month`, `text_color_week_days`, `text_color_other_months`, `text_color_this_month_unevented`, `text_color_this_month_evented`, `event_title_color`, `current_day_border_color`, `bg_color_this_month_evented`, `next_prev_event_arrowcolor`, `show_event_bgcolor`, `cell_border_color`, `arrow_color_year`, `week_days_cell_height`, `arrow_color_month`, `text_color_sun_days`, `title_color`, `next_prev_event_bgcolor`, `title_font_size`, `title_font`, `title_style`, `date_color`, `date_size`, `date_font`, `date_style`, `popup_width`, `popup_height`, `number_of_shown_evetns`, `sundays_font_size`, `other_days_font_size`, `weekdays_font_size`, `border_width`, `top_height`, `bg_color_other_months`, `sundays_bg_color`, `weekdays_bg_color`, `week_start_day`, `weekday_sunday_bg_color`, `border_radius`, `year_font_size`, `month_font_size`, `arrow_size`, `next_month_text_color`, `prev_month_text_color`, `next_month_arrow_color`, `prev_month_arrow_color`, `next_month_font_size`, `prev_month_font_size`, `month_type`, `date_format`, `show_time`, `show_cat`, `show_repeat`, `date_bg_color`, `event_bg_color1`, `event_bg_color2`, `event_num_bg_color1`, `event_num_bg_color2`, `event_num_color`, `date_font_size`, `event_num_font_size`, `event_table_height`, `date_height`, `ev_title_bg_color`, `week_font_size`, `day_month_font_size`, `week_font_color`, `day_month_font_color`, `views_tabs_bg_color`, `views_tabs_text_color`, `views_tabs_font_size`, `day_start`) VALUES
	  ('1','Wasabi', '700', '70', 'A6BA7D', 'FDFCDE', '000000', '', '080808', '000000', '6E5959', '060D12', '000000', '000000', '4AFF9E', 'FF6933', 'E0E0C5', 'FDFCDE', '000000', '', '50', '000000', 'FF0000', '000000', 'CCCCCC', '18', 'Courier New', 'normal', '000000', '16', 'Courier New', 'bold', '800', '600', '1', '18', '12', '14', '2', '90', 'FFFFFF', 'FDFCDE', 'E6E6DE', 'su', 'BD848A', '0', '', '35', '45', '', '', '', '', '', '', '', 'w/d/m/y', 1, 1, 1, 'A6BA7C', 'FDFCDE', 'FDFCDE', 'FDFCDE', 'FDFCDE', '000000', '15', '15', '25', '25', 'FF6933', '15', '13', 'FFFFFF', '474747', 'E8E7CC', '000000', '13', 1),
	  ('2','Bluejay and Orange', '700', '80', '36A7E9', 'FFFFFF', '000000', '', '000000', '000000', '525252', '000000', 'FFFFFF', '000000', '36A7E9', 'FFA142', 'FFFFFF', '36A7E9', '000000', '', '40', '000000', '36A7E9', 'FFFFFF', 'FFA142', '', '', 'normal', 'FFFFFF', '16', '', 'bold', '800', '600', '1', '20', '20', '14', '4', '80', 'FFFFFF', 'FFFFFF', 'FFFFFF', 'su', 'FFFFFF', '0', '', '25', '40', '', '', '', '', '', '', '', 'w/d/m/y', 1, 1, 1, 'FFA041', 'FFFFFF', 'FFFFFF', 'FFFFFF', 'FFFFFF', '000000', '15', '15', '25', '25', 'FFA142', '15', '13', 'FFFFFF', '6E6E6E', 'FFA142', 'FFFFFF', '13', 1),
	  ('3','White and Blue', '700', '70', '00004F', '5BCAFF', '000000', '', 'D1D4F5', 'FFFFFF', 'E6E6E6', '000000', 'FFFFFF', '050505', 'FFFFFF', 'DEDEDE', 'FFFFFF', '009EEB', '000000', '', '30', 'FFFFFF', '000000', 'FFFFFF', '00004F', '', '', 'normal', 'FFFFFF', '', '', 'normal', '600', '500', '1', '18', '14', '14', '2', '120', 'C0C0C0', '8ADAFF', '000000', 'su', '000000', '', '', '40', '50', '', '', '', '', '', '', '', 'w/d/m/y', 1, 1, 1, '5BCAFF', 'FFFFFF', 'FFFFFF', 'FFFFFF', 'FFFFFF', '000000', '15', '15', '25', '25', 'DEDEDE', '15', '12', 'FFFFFF', 'FFFFFF', '5BCAFF', 'FFFFFF', '13', 1),
	  ('4','Dark', '700', '70', '2A2829', '323232', '000000', '', 'FFFFFF', 'FFFFFF', 'FFFFFF', 'FFFFFF', '000000', '000000', 'FFFFFF', 'F0F0F0', 'C7C7C7', '969696', '000000', '', '35', 'FFFFFF', 'FFFFFF', 'FFFFFF', '323232', '', '', 'normal', 'FFFFFF', '', '', 'normal', '600', '500', '1', '16', '12', '14', '2', '90', '282828', '323232', '969696', 'su', '969696', '8', '', '35', '45', '', '', '', '', '', '', '', 'w/d/m/y', 1, 1, 1, '969696', '323232', '323232', '323232', '323232', 'FFFFFF', '15', '15', '25', '25', 'F0F0F0', '15', '12', 'FFFFFF', 'FFFFFF', '969696', 'FFFFFF', '13', 1),
	  ('5','Red and Olive', '700', '70', '9A0000', 'CDCC96', 'E6E6E4', '', 'FFFFFF', '000000', '525252', '000000', 'FFFFFF', 'FFFFFF', '9A0000', '9A0000', 'DEDDB5', 'FFFED0', 'FFFFFF', '', '60', 'FFFFFF', '000000', '000000', '9A0000', '', '', 'normal', '000000', '', '', 'normal', '600', '500', '1', '18', '', '14', '18', '100', 'E4E7CC', 'CDCC96', 'FFFED0', 'mo', 'FFFED0', '6', '', '25', '50', '', '', '', '', '', '', '', 'w/d/m/y', 1, 1, 1, 'E4E7CC', 'CECD97', 'CECD97', 'CECD97', 'CECD97', '000000', '15', '15', '25', '25', '9A0000', '15', '12', '000000', '8F8F8F', 'CDCC96', 'FFFFFF', '13', 1),
	  ('6','Blue and Bisque', '700', '70', 'FCF7D9', 'FFFFFF', '3DBCEB', '', '9A0000', 'FFFFFF', 'C7C7C7', '1374C3', '000000', '000000', '9A0000', 'FCF7D9', 'E0E0E0', 'FCF7D9', '1374C3', '', '20', '9A0000', '013A7D', '000000', '21B5FF', '', '', 'normal', '000000', '', '', 'bold', '600', '500', '1', '16', '12', '14', '12', '93', 'FFFFFF', 'FFFFFF', '013A7D', 'su', '1374C3', '6', '', '35', '40', '', '', '', '', '', '', '', 'w/d/m/y', 1, 1, 1, '3CBBEB', 'FFFFFF', 'FFFFFF', 'FCF7D9', 'FFFFFF', '970000', '15', '15', '25', '25', 'CFCBB2', '15', '12', 'FFFFFF', 'FBE6E6', '3DBCEB', '000000', '',1),
	  ('7','White and OliveDrab', '700', '70', '598923', 'F0F0E6', 'D78B29', '', 'FFFFFF', '000000', 'A6A6A6', '5C5C5C', 'FFFFFF', '000000', '000000', 'D78B29', 'D78B29', 'FFB061', '363636', '', '30', 'FFFFFF', '000000', 'FFFFFF', 'DDDCC8', '', 'Courier New', 'bold', '000000', '', '', 'normal', '600', '500', '1', '16', '12', '14', '12', '100', 'DDDCC8', 'F0F0E6', 'D78B29', 'su', 'D78B29', '6', '', '35', '50', '', '', '', '', '', '', '', 'w/d/m/y', 1, 1, 1, '588922', 'F0F0E6', 'F0F0E6', 'F0F0E6', 'F0F0E6', '000000', '15', '15', '25', '25', 'D78B29', '15', '12', 'FFFFFF', 'FFFFFF', 'D78B29', 'FFFFFF', '13', 1),
	  ('8','DarkCyan and Violet', '700', '70', '009898', 'FDFDCC', 'FDFDCC', '', 'FFFFFF', '000000', '8C8C8C', '383838', '383838', '000000', '000000', 'FE7C00', 'FEAC30', 'FE7C00', '4D4D4D', '', '30', 'FFFFFF', '000000', 'FFFFFF', 'FDFDE8', '', '', 'normal', 'FFFFFF', '', '', 'normal', '600', '500', '1', '16', '12', '14', '14', '90', 'FDFDE8', 'BACBDC', '9865FE', 'su', '9865FE', '2', '', '25', '45', '', '', '', '', '', '', '', 'w/d/m/y', 1, 1, 1, '9765FD', 'FDFCCC', 'FDFCCC', 'FDFCCC', 'FDFCCC', '000000', '15', '15', '25', '25', 'FE7C00', '15', '12', 'FFFFFF', 'FFFFFF', 'FDFDCC', '000000', '13', 1),
	  ('9','SteelBlue', '700', '70', '346699', 'E3F9F9', '346699', '', 'FFFFFF', 'FFFFFF', 'FFFFFF', '2410EE', '000000', '000000', '346699', 'FFCC33', 'E3B62D', 'FFCC33', '6B6B6B', '', '25', 'FFFFFF', '2410EE', 'FFFFFF', '346699', '', '', 'normal', '000000', '', '', 'normal', '600', '500', '1', '18', '14', '14', '10', '100', 'CCCCCC', 'CDDDFF', '68676D', 'su', '68676D', '4', '', '35', '50', '', '', '', '', '', '', '', 'w/d/m/y', 1, 1, 1, 'E3F8FA', 'CCCCCC', 'CCCCCC', 'CCCCCC', 'CCCCCC', '000000', '15', '15', '25', '25', 'FFCC33', '15', '12', '726ED6', '726ED6', 'CDDDFF', 'FFFFFF', '13', 1),
	  ('10','PaleGreen', '700', '70', 'C0EFC0', 'E3F9F9', 'ABCEA8', '', '58A42B', '000000', 'B0B0B0', '383838', '383838', '383838', '58A42B', 'C0EFC0', 'AED9AE', 'C0EFC0', 'B1B1B0', '', '25', '58A42B', 'FF7C5C', 'FFFFFF', '58A42B', '', '', 'normal', '262626', '', '', 'normal', '600', '500', '1', '16', '12', '12', '8', '40', 'E1DDE9', 'FFFFFF', 'FFFFFF', 'su', 'FFFFFF', '2', '', '20', '20', '', '', '', '', '', '', '', 'w/d/m/y', 1, 1, 1, 'DFDDE7', 'E4F9FA', 'E4F9FA', 'FFFFFF', 'FFFFFF', '000000', '15', '15', '25', '25', 'C0EFC0', '15', '12', '7DAC84', '7DAC84', 'E3F9F9', '000000', '13', 1),
	  ('11','Gold and Brown', '700', '70', 'E7C892', '7E5F43', 'FFC219', '', '404040', '404040', 'FFFFFF', 'FFFFFF', '404040', '404040', 'FFFFFF', 'FFC219', 'B3875F', '7E5F43', '000000', '', '30', '404040', 'FFFFFF', 'FFFFFF', 'FFC219', '', '', 'normal', 'FFFFFF', '', '', 'normal', '800', '500', '2', '18', '12', '14', '10', '100', '523F30', '7E5F43', 'FFC219', 'su', 'FFC219', '6', '', '35', '50', '', '', '', '', '', '', '', 'w/d/m/y', 1, 1, 1, 'FFC11A', '7E5F43', '7E5F43', '7E5F43', '523F31', 'FFFFFF', '15', '15', '25', '25', 'FFC219', '15', '12', '4F3A11', '4F3A11', 'FFC219', 'FFFFFF', '13', 1),
	  ('13','Shiny Blue', '700', '70', '005478', 'E1E1E1', '005478', 'F9F2F4', 'F9F2F4', '005D78', 'B0B0B0', '6A6A6A', '6A6A6A', '236283', '005478', 'B4C5CC', '97A0A6', 'B4C5CC', 'A9A9A9', 'CCD1D2', '50', 'CCD1D2', '6A6A6A', 'FFFFFF', '00608A', '', '', 'normal', '262626', '', '', 'normal', '600', '500', '1', '25', '25', '25', '0', '100', 'E1E1E1', 'E1E1E1', 'D6D6D6', 'su', 'B5B5B5', '0', '25', '25', '50', 'CCD1D2', 'CCD1D2', 'CCD1D2', '1010A4', '16', '16', '2', 'w/d/m/y', 1, 1, 1, 'D6D4D5', 'E1E1E1', 'DEDCDD', '005478', '006E91', 'FFFFFF', '15', '13', '30', '25', 'C3D0D6', '15', '12', '005476', '737373', '01799C', 'FFFFFF', '13', 1),
	  ('12','Shiny Red', '700', '65', '520017', 'E1E1E1', 'ABCEA8', 'FEFCFC', 'FEFCFC', '2A674D', '817F7F', '817F7F', '817F7F', '292929', '520017', 'B69DA4', 'B69DA4', 'C5B1B6', 'B1B1B0', '58A42B', '50', 'D0D0D0', '817F7F', 'FFFFFF', '997783', '', '', 'normal', '262626', '', '', 'normal', '600', '500', '1', '23', '23', '20', '0', '100', 'E1E1E1', 'E1E1E1', 'E1E1E1', 'su', 'BBBBBB', '2', '35', '35', '50', '58A42B', '58A42B', 'FEFCFC', 'FEFCFC', '25', '25', '2', 'w/d/m/y', 1, 1, 1, 'D6D5D5', 'E1E1E1', 'E1E1E1', '450013', '5A011A', 'FFFFFF', '15', '13', '30', '25', 'C5B1B6', '15', '12', '400012', '747474', '860126', 'FFFFFF', '13', 1),
	  ('14','Shiny Green', '700', '70', '00512F', 'E1E1E1', '005478', '58A42B', 'FFFFFF', '175E41', 'B0B0B0', '9A9898', '9A9898', '383838', '00502F', '9DB5AB', '9DB5AB', 'B1C4BC', 'B1B1B0', '58A42B', '50', 'CFD2CF', '9A9898', 'FFFFFF', '175E41', '', '', 'normal', 'FFFFFF', '', '', 'normal', '600', '500', '2', '25', '25', '20', '0', '100', 'E1E1E1', 'E1E1E1', 'E0E0E0', 'su', 'BBBBBB', '0', '18', '35', '45', '58A42B', '58A42B', '58A42B', '58A42B', '16', '16', '2', 'w/d/m/y', 1, 1, 1, 'D6D5D5', 'E1E1E1', 'DEDDDD', '003C23', '00502F', 'FFFFFF', '15', '13', '30', '25', 'B1C4BC', '15', '12', '003D24', '747474', '00882A', 'FFFFFF', '13', 1),
	  ('17','Shiny Orange', '700', '70', 'D57E01', 'E1E1E1', '005478', '58A42B', 'FFFFFF', '015130', 'B0B0B0', '7C7A7A', '7C7A7A', '383838', 'D57E01', 'DDC39D', 'E4CFB1', 'DDC39D', 'B1B1B0', '58A42B', '50', 'E1E2D9', '7C7A7A', 'FFFFFF', 'D37D00', '', '', 'normal', 'FFFFFF', '', '', 'normal', '800', '500', '2', '25', '25', '20', '0', '100', 'E1DDE9', 'E1E1E1', 'E1E1E1', 'su', 'BBBBBB', '0', '18', '35', '45', '58A42B', '58A42B', '58A42B', '58A42B', '16', '16', '2', 'w/d/m/y', 1, 1, 1, 'D6D5D5', 'E1E1E1', 'DEDDDD', 'AB6501', 'D57E01', 'FFFFFF', '15', '13', '30', '25', 'E4CFB1', '15', '12', 'A26001', '838383', 'E0AD01', 'FFFFFF', '13', 1),
	  ('18','Shiny Pink', '700', '70', 'FEA2EC', 'E1E1E1', '005478', '58A42B', 'FFFFFF', '00502F', 'B0B0B0', '817F7F', '817F7F', '383838', 'FEA2EC', 'EACEE4', 'EED8E9', 'EACEE4', 'B1B1B0', '58A42B', '50', 'D1D1D1', '817F7F', 'FFFFFF', 'FA9FE8', '', '', 'normal', 'FFFFFF', '', '', 'normal', '800', '500', '2', '25', '25', '20', '0', '100', 'E1E1E1', 'E1E1E1', 'D6D6D6', 'su', 'B5B5B5', '0', '18', '35', '45', '58A42B', '58A42B', '58A42B', '58A42B', '16', '16', '2', 'w/d/m/y', 1, 1, 1, 'D6D5D5', 'E1E1E1', 'DEDDDD', 'C17BB4', 'FCA0EA', 'FFFFFF', '15', '13', '30', '25', 'EED8E9', '15', '12', 'BD78B0', '999898', 'FDC5F2', 'FFFFFF', '13', 1),
	  ('19','Shiny Purple', '700', '70', '52004F', 'E1E1E1', '005478', '58A42B', 'FFFFFF', '00502F', 'B0B0B0', '817F7F', '817F7F', '383838', '52004F', 'B69DB5', 'C5B1C4', 'B69DB5', 'B1B1B0', '58A42B', '50', 'D1D1D1', '817F7F', 'FFFFFF', '51004E', '', '', 'normal', 'FFFFFF', '', '', 'normal', '800', '500', '2', '25', '25', '20', '0', '100', 'E1DDE9', 'E1E1E1', 'E1E1E1', 'su', 'BBBBBB', '0', '18000', '35', '45', '58A42B', '58A42B', '58A42B', '58A42B', '16', '16', '2', 'w/d/m/y', 1, 1, 1, 'D6D5D5', 'E1E1E1', 'DEDDDD', '420040', '52004F', 'FFFFFF', '15', '13', '30', '25', 'C5B1C4', '15', '12', '480045', 'D6D5D5', '850088', 'FFFFFF', '13', 1)";
	$wpdb->query($spider_theme_rows);

	$wpdb->query("DROP TABLE IF EXISTS `" . $wpdb->prefix . "spidercalendar_widget_theme`");
	$spider_widget_theme_table = "CREATE TABLE IF NOT EXISTS `" . $wpdb->prefix . "spidercalendar_widget_theme` (
	  `id` int(11) NOT NULL AUTO_INCREMENT,
	  `title` varchar(255) NOT NULL,
	  `ev_title_color` varchar(255),
	  `width` varchar(255) NOT NULL,
	  `week_start_day` varchar(255) NOT NULL,
	  `show_cat` int(11) NOT NULL,
	  `font_year` varchar(255) NOT NULL,
	  `font_month` varchar(255) NOT NULL,
	  `font_day` varchar(255) NOT NULL,
	  `font_weekday` varchar(255) NOT NULL,
	  `header_bgcolor` varchar(255) NOT NULL,
	  `footer_bgcolor` varchar(255) NOT NULL,
	  `text_color_month` varchar(255) NOT NULL,
	  `text_color_week_days` varchar(255) NOT NULL,
	  `text_color_other_months` varchar(255) NOT NULL,
	  `text_color_this_month_unevented` varchar(255) NOT NULL,
	  `text_color_this_month_evented` varchar(255) NOT NULL,
	  `bg_color_this_month_evented` varchar(255) NOT NULL,
	  `bg_color_selected` varchar(255) NOT NULL,
	  `arrow_color` varchar(255) NOT NULL,
	  `text_color_selected` varchar(255) NOT NULL,
	  `border_day` varchar(255) NOT NULL,
	  `text_color_sun_days` varchar(255) NOT NULL,
	  `weekdays_bg_color` varchar(255) NOT NULL,
	  `su_bg_color` varchar(255) NOT NULL,
	  `cell_border_color` varchar(255) NOT NULL,
	  `year_font_size` varchar(255) NOT NULL,
	  `year_font_color` varchar(255) NOT NULL,
	  `year_tabs_bg_color` varchar(255) NOT NULL,
	  `date_format` varchar(255) NOT NULL,
	  `title_color` varchar(255) NOT NULL,
	  `title_font_size` varchar(255) NOT NULL,
	  `title_font` varchar(255) NOT NULL,
	  `title_style` varchar(255) NOT NULL,
	  `date_color` varchar(255) NOT NULL,
	  `date_size` varchar(255) NOT NULL,
	  `date_font` varchar(255) NOT NULL,
	  `date_style` varchar(255) NOT NULL,
	  `next_prev_event_bgcolor` varchar(255) NOT NULL,
	  `next_prev_event_arrowcolor` varchar(255) NOT NULL,
	  `show_event_bgcolor` varchar(255) NOT NULL,
	  `popup_width` varchar(255) NOT NULL,
	  `popup_height` varchar(255) NOT NULL,
	  `show_repeat` varchar(255) NOT NULL,
	  PRIMARY KEY (`id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8;";
	$wpdb->query($spider_widget_theme_table);
	$spider_widget_theme_rows = "INSERT INTO `" . $wpdb->prefix . "spidercalendar_widget_theme` (`id`,`title`,`ev_title_color`,`width`,`week_start_day`, `show_cat`, `font_year`,`font_month`,`font_day`,`font_weekday`,`header_bgcolor`,`footer_bgcolor`,`text_color_month`,`text_color_week_days`,`text_color_other_months`,`text_color_this_month_unevented`,`text_color_this_month_evented`,`bg_color_this_month_evented`,`bg_color_selected`,`arrow_color`,`text_color_selected`,`border_day`,`text_color_sun_days`,`weekdays_bg_color`,`su_bg_color`,`cell_border_color`,`year_font_size`,`year_font_color`,`year_tabs_bg_color`,`date_format`,`title_color`,`title_font_size`,`title_font`,`title_style`,`date_color`,`date_size`,`date_font`,`date_style`,`next_prev_event_bgcolor`,`next_prev_event_arrowcolor`,`show_event_bgcolor`,`popup_width`,`popup_height`,`show_repeat`) VALUES
	  (1,'Shiny Blue','005478','200','mo','1','','','','','005478','E1E1E1','FFFFFF','2F647D','939699','989898','FBFFFE','005478','005478','CED1D0','FFFFFF','005478','989898','D6D6D6','B5B5B5','D2D2D2','13','ACACAC','ECECEC','w/d/m/y','FFFFFF','','','normal','262626','','','normal','00608A','97A0A6','B4C5CC','600','500','1'),
	  (2,'Shiny Green','00512F','200','mo','1','','','','','00512F','E1E1E1','FFFFFF','37745A','939699','989898','FBFFFE','00502F','00502F','CED1D0','FFFFFF','00502F','989898','D6D6D6','B5B5B5','D2D2D2','13','ACACAC','ECECEC','w/d/m/y','FFFFFF','','','normal','FFFFFF','','','normal','175E41','9DB5AB','B1C4BC','600','500','1'),
	  (3,'Shiny Yellow','D57F01','200','mo','1','','','','','D57F01','E1E1E1','FFFFFF','E29F3D','939699','989898','FBFFFE','D57E01','D57E01','CED1D0','FFFFFF','D57E01','989898','D6D6D6','B5B5B5','D2D2D2','13','ACACAC','ECECEC','w/d/m/y','FFFFFF','','','normal','FFFFFF','','','normal','D37D00','E4CFB1','DDC39D','800','500','1'),
	  (4,'Shiny Red','520017','200','mo','1','','','','','520017','E1E1E1','FFFFFF','520017','939699','989898','FBFFFE','520017','520017','CED1D0','FFFFFF','520017','989898','D6D6D6','B5B5B5','D2D2D2','13','ACACAC','ECECEC','w/d/m/y','FFFFFF','','','normal','262626','','','normal','997783','B69DA4','C5B1B6','600','500','1'),
	  (5,'Shiny Pink','FCA1EA','200','mo','1','','','','','FCA1EA','E1E1E1','FFFFFF','FCA1EA','939699','989898','FBFFFE','FCA1EA','FCA1EA','CED1D0','FFFFFF','FCA1EA','989898','D6D6D6','B5B5B5','D2D2D2','13','ACACAC','ECECEC','w/d/m/y','FFFFFF','','','normal','FFFFFF','','','normal','FA9FE8','EED8E9','EACEE4','800','500','1'),
	  (6,'Shiny Purple','540052','200','mo','1','','','','','540052','E1E1E1','FFFFFF','540052','939699','989898','FBFFFE','540052','540052','CED1D0','FFFFFF','540052','989898','D6D6D6','B5B5B5','D2D2D2','13','ACACAC','ECECEC','w/d/m/y','FFFFFF','','','normal','FFFFFF','','','normal','51004E','C5B1C4','B69DB5','800','500','1')";
	$wpdb->query($spider_widget_theme_rows);

	
	$catexist=0;
	for($i=0;$i<count($category);$i++){
	  if($category[$i]->Field=="category"){
		$catexist=1;
		break;
	  }
	}
	if($catexist==0) {
	  $wpdb->query("ALTER TABLE ".$wpdb->prefix."spidercalendar_event  ADD category int(11) AFTER title;");
	}
	
	$calendar = $wpdb->get_results("SHOW COLUMNS FROM ".$wpdb->prefix."spidercalendar_calendar");
	$calexist=0;
	for($i=0;$i<count($calendar);$i++){
	  if($calendar[$i]->Field=="start_month"){
		$calexist=1;
		break;
	  }
	}
	if ($calexist==0) {
	  $sql = "ALTER TABLE " . $wpdb->prefix . "spidercalendar_calendar ADD start_month varchar(255);";
	  $wpdb->query($sql);
	}
	if($defmonth == 0) {
	  $wpdb->query("ALTER TABLE " . $wpdb->prefix . "spidercalendar_calendar ADD `def_month` varchar(255) NOT NULL AFTER `start_month`");
	  $wpdb->query("ALTER TABLE " . $wpdb->prefix . "spidercalendar_calendar CHANGE `start_month` `def_year` VARCHAR(512) NOT NULL");
	}
  }
}
?>