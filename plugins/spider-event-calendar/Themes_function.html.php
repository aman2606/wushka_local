<?php

if (!current_user_can('manage_options')) {
  die('Access Denied');
}

function html_show_theme_calendar($rows, $pageNav, $sort) {
  
  ?><script language="javascript">
    function ordering(name, as_or_desc) {
      document.getElementById('asc_or_desc').value = as_or_desc;
      document.getElementById('order_by').value = name;
      document.getElementById('admin_form').submit();
    }
    function doNothing() {
      var keyCode = event.keyCode ? event.keyCode : event.which ? event.which : event.charCode;
      if (keyCode == 13) {
        if (!e) {
          var e = window.event;
        }
        e.cancelBubble = true;
        e.returnValue = false;
        if (e.stopPropagation) {
          e.stopPropagation();
          e.preventDefault();
        }
      }
    }
  </script>
  <form method="post" onkeypress="doNothing()" action="admin.php?page=spider_calendar_themes" id="admin_form" name="admin_form">
    <?php $sp_cal_nonce = wp_create_nonce('nonce_sp_cal'); ?>
    <table cellspacing="10" width="100%">
      <tr>
        <td width="100%" style="font-size:14px; font-weight:bold">
          <a href="https://web-dorado.com/spider-calendar-wordpress-guide-step-5/5-1.html" target="_blank" style="color:blue; text-decoration:none;">User Manual</a>
          <br />
          This section allows you to create/edit themes for the calendars.
          <a href="https://web-dorado.com/spider-calendar-wordpress-guide-step-5/5-1.html" target="_blank" style="color:blue; text-decoration:none;">More...</a>
        </td>
      </tr>
      <tr>
        <td style="width:80px"><h2>Themes</h2></td>
        <td style="width:90px; text-align:right;">
          <p class="submit" style="padding:0px; text-align:left">
            <input type="button" value="Add a Theme" name="custom_parametrs" onclick="window.location.href='admin.php?page=spider_calendar_themes&task=add_theme'"/>
          </p>
        </td>
        <td style="text-align:right;font-size:16px;padding:20px; padding-right:50px"></td>
      </tr>
    </table>
    <?php
    $serch_value = "";
    if (isset($_POST['serch_or_not']) && ($_POST['serch_or_not'] == "search")) {
      $serch_value = esc_js(esc_html(stripslashes($_POST['search_events_by_title'])));
    }
    $serch_fields = '
      <div class="alignleft actions" >
        <label for="search_events_by_title" style="font-size:14px">Title: </label>
        <input type="text" name="search_events_by_title" value="' . $serch_value . '" id="search_events_by_title" onchange="clear_serch_texts()">
      </div>
      <div class="alignleft actions">
        <input type="button" value="Search" onclick="document.getElementById(\'page_number\').value=\'1\'; document.getElementById(\'serch_or_not\').value=\'search\';
          document.getElementById(\'admin_form\').submit();" class="button-secondary action">
        <input type="button" value="Reset" onclick="window.location.href=\'admin.php?page=spider_calendar_themes\'" class="button-secondary action">
      </div>';
    print_html_nav($pageNav['total'], $pageNav['limit'], $serch_fields);
	
    ?>
    <table class="wp-list-table widefat fixed pages" style="width:95%">
      <thead>
        <TR>
          <th scope="col" id="id" class="<?php echo (($sort["sortid_by"] == "id") ? $sort["custom_style"] : $sort["default_style"]); ?>" style="width:120px">
            <a href="javascript:ordering('id',<?php echo (($sort["sortid_by"] == "id") ? $sort["1_or_2"] : "1"); ?>)">
              <span>ID</span>
              <span class="sorting-indicator"></span>
            </a>
          </th>
          <th scope="col" id="title" class="<?php echo (($sort["sortid_by"] == "title") ? $sort["custom_style"] : $sort["default_style"]); ?>" >
            <a href="javascript:ordering('title',<?php echo (($sort["sortid_by"] == "title") ? $sort["1_or_2"] : "1"); ?>)">
              <span>Title</span>
              <span class="sorting-indicator"></span>
            </a>
          </th>
          <th style="width:80px">Edit</th>
          <th style="width:80px">Delete</th>
        </TR>
      </thead>
      <tbody>
        <?php for ($i = 0; $i < count($rows); $i++) { ?>
      <tr>
        <td><?php echo $rows[$i]->id; ?></td>
        <td><a href="admin.php?page=spider_calendar_themes&task=edit_theme&id=<?php echo $rows[$i]->id?>"><?php echo $rows[$i]->title; ?></a></td>
        <td><a href="admin.php?page=spider_calendar_themes&task=edit_theme&id=<?php echo $rows[$i]->id?>">Edit</a></td>
        <td><a href="admin.php?page=spider_calendar_themes&task=remove_theme_calendar&id=<?php echo $rows[$i]->id?>&_wpnonce=<?php echo $sp_cal_nonce; ?>">Delete</a></td>
      </tr>
        <?php } ?>
      </tbody>
    </table>
    <input type="hidden" name="asc_or_desc" id="asc_or_desc" value="<?php if (isset($_POST['asc_or_desc'])) echo esc_js(esc_html(stripslashes($_POST['asc_or_desc'])));?>"/>
    <input type="hidden" name="order_by" id="order_by" value="<?php if (isset($_POST['order_by'])) echo esc_js(esc_html(stripslashes($_POST['order_by']))); ?>"/>
    <?php
    ?>
  </form>
  <?php
}

function html_edit_theme_calendar($row, $id) {
  ?>
  <script language="javascript" type="text/javascript">
    function submitbutton(pressbutton) {
      var form = document.adminForm;
      if (pressbutton == 'cancel_theme') {
        submitform(pressbutton);
        return;
      }
      if (document.getElementById('title').value == '') {
        alert('The theme must have a title')
        return;
      }
      submitform(pressbutton);
    }
    function submitform(pressbutton) {
      document.getElementById('adminForm').action = document.getElementById('adminForm').action + "&task=" + pressbutton;
      document.getElementById('adminForm').submit();
    }
    function change_width() {
      width = parseInt(document.getElementById('width').value) + 45 + parseInt(document.getElementById('border_width').value);
      height = 550;
      document.getElementById('spider_calendar_preview').href = "<?php echo plugins_url("preview.php", __FILE__) ?>?TB_iframe=1&tbWidth=" + width + "&tbHeight=" + height;	  
	  var count = document.getElementsByClassName("color_for_this").length;
	  for(var i=0; i<count; i++){
	  document.getElementsByClassName("color_for_this")[i].childNodes[1].childNodes[0].style.backgroundColor = "transparent";
	  }  
    }
    var thickDims, tbWidth = 200, tbHeight = 200;
    jQuery(document).ready(function ($) {
      thickDims = function () {
        var tbWindow = $('#TB_window'), H = $(window).height(), W = $(window).width(), w, h;
        w = (tbWidth && tbWidth < W - 90) ? tbWidth : W - 200;
        h = (tbHeight && tbHeight < H - 60) ? tbHeight : H - 60;
        if (tbWindow.size()) {
          tbWindow.width(w).height(h);
          $('#TB_iframeContent').width(w).height(h - 27);
          tbWindow.css({'margin-left':'-' + parseInt((w / 2), 10) + 'px'});
          if (typeof document.body.style.maxWidth != 'undefined') {
            tbWindow.css({'top':(H - h) / 2, 'margin-top':'0'});
          }
        }
      };
      thickDims();
      $(window).resize(function () {
        thickDims()
      });
      $('a.thickbox-preview').click(function () {
        tb_click.call(this);
        var alink = $(this).parents('.available-theme').find('.activatelink'), link = '', href = $(this).attr('href'), url, text;
        if (tbWidth = href.match(/&tbWidth=[0-9]+/)) {
          tbWidth = parseInt(tbWidth[0].replace(/[^0-9]+/g, ''), 10);
        }
        else {
          tbWidth = $(window).width() - 90;
        }
        if (tbHeight = href.match(/&tbHeight=[0-9]+/)) {
          tbHeight = parseInt(tbHeight[0].replace(/[^0-9]+/g, ''), 10);
        }
        else {
          tbHeight = $(window).height() - 60;
        }
        if (alink.length) {
          url = alink.attr('href') || '';
          text = alink.attr('title') || '';
          link = '&nbsp; <a href="' + url + '" target="_top" class="tb-theme-preview-link">' + text + '</a>';
        }
        else {
          text = $(this).attr('title') || '';
          link = '&nbsp; <span class="tb-theme-preview-link">' + text + '</span>';
        }
        $('#TB_title').css({'background-color':'#222', 'color':'#dfdfdf'});
        $('#TB_closeAjaxWindow').css({'float':'left'});
        $('#TB_ajaxWindowTitle').css({'float':'right'}).html(link);
        $('#TB_iframeContent').width('100%');
        thickDims();
        return false;
      });
      // Theme details.
      $('.theme-detail').click(function () {
        $(this).siblings('.themedetaildiv').toggle();
        return false;
      });
    });
    jQuery(document).ready(function() {
		jQuery('.color_input').wpColorPicker();
	 });
	</script>    
	<style>
	.wp-picker-holder{
		position: absolute;
		z-index: 2;
		top: 20px;
	}
	.wp-color-result {
	  background-color: transparent;
	  width: 85px;
	  border-radius: 0px;
	}
	.wp-color-result:focus{
			outline: none;
		}
	.color_for_this {
	  height: 24px;
	  top: 0px;
	  position: relative;
	  width: 35px;
	  left: 2px;
	}
	 .wp-color-result:hover{
	  background-color: transparent;
	}
	</style>
  <table width="90%">
    <tr>
      <td width="100%" style="font-size:14px; font-weight:bold">
        <a href="https://web-dorado.com/step-4-adding-themes/spider-calendar-wordpress-guide-step-4-1.html" target="_blank" style="color:blue; text-decoration:none;">User Manual</a>
        <br />
        This section allows you to create/edit themes for the calendars.
        <a href="https://web-dorado.com/step-4-adding-themes/spider-calendar-wordpress-guide-step-4-1.html" target="_blank" style="color:blue; text-decoration:none;">More...</a>
      </td>
    </tr>
    <tr>
      <td width="100%"><h2><?php echo (($id == 0) ? 'Adding New' : 'Edit'); ?> Theme</h2></td>
      <td id="priview_td">
        <a href="<?php echo plugins_url("preview.php", __FILE__); ?>?TB_iframe=1&tbWidth=<?php echo $row->width + 45; ?>&tbHeight=550"
          class="thickbox-preview" id="spider_calendar_preview" title="Spider Calendar" onclick="return false;">
          <input type="button" value="preview" class="button-primary">
        </a>
      </td>
      <td align="right"><input type="button" onclick="submitbutton('Save')" value="Save" class="button-secondary action"></td>
      <td align="right"><input type="button" onclick="submitbutton('Apply')" value="Apply" class="button-secondary action"></td>
      <td align="right">
        <input type="button" onclick="window.location.href='admin.php?page=spider_calendar_themes'" value="Cancel" class="button-secondary action">
      </td>
    </tr>
  </table>
  <form action="admin.php?page=spider_calendar_themes&id=<?php echo $id; ?>" method="post" id="adminForm" name="adminForm">
  <div class="col width-25">
    <fieldset class="adminform">
      <legend>General parameters</legend>
      <table class="admintable">
        <tr>
          <td class="key"><label for="title">Title: </label></td>
          <td>
            <input type="text" <?php echo ((($row->id < 18) && ($id > 0)) ? 'readonly' : ''); ?> name="title" id="title" size="20" value="<?php echo htmlspecialchars($row->title); ?>" />
          </td>
        </tr>
        <?php if (($row->id > 17) || ($id == 0)) { ?>
        <tr>
          <td class="key"><label for="slect_theme">Default themes: </label></td>
          <td>
            <select id="slect_theme" onchange="set_theme()">
              <option value="0"> Custom</option>
              <option value="1"> Wasabi</option>
              <option value="2"> Bluejay and Orange</option>
              <option value="3"> White and Blue</option>
              <option value="4"> Dark</option>
              <option value="5"> Red and Olive</option>
              <option value="6"> Blue and Bisque</option>
              <option value="7"> White and OliveDrab</option>
              <option value="8"> DarkCyan and Violet</option>
              <option value="9"> SteelBlue</option>
              <option value="10"> PaleGreen</option>
              <option value="11"> Gold and Brown</option>
              <option value="12"> Shiny Red</option>
              <option value="13"> Shiny Blue</option>
              <option value="14"> Shiny Green</option>
              <option value="15"> Shiny Orange</option>
              <option value="16"> Shiny Pink</option>
              <option value="17"> Shiny Purple</option>
            </select>
          </td>
        </tr>
        <?php } ?>
        <tr>
          <td class="key"><label for="width">Width: </label></td>
          <td>
            <input onchange="change_width()" type="text" name="width" id="width" size="10" value="<?php echo $row->width; ?>"/>px
          </td>
        </tr>
        <tr>
          <td class="key"><label for="week_start_day">The first day of the week: </label></td>
          <td>
            <select name="week_start_day" id="week_start_day" class="inputbox">
              <option <?php selectted($row->week_start_day, 'mo'); ?> value="mo">Monday</option>
              <option <?php selectted($row->week_start_day, 'su'); ?>  value="su">Sunday</option>
            </select>
          </td>
        </tr>
        <tr>
          <td class="key"><label for="border_color">Main border color: </td>
          <td>
			<div class="color_for_this" style="background-color: #<?php echo $row->border_color; ?>">
				<input type="text" name="border_color" id="border_color" size="20" value="<?php echo $row->border_color ?>" class="color_input wp-color-picker"/>
			</div>
          </td>
        </tr>
        <tr>
          <td class="key"><label for="border_radius">Main border radius: </label></td>
          <td>
            <input type="text" name="border_radius" id="border_radius" size="10" value="<?php echo $row->border_radius; ?>"/>px
          </td>
        </tr>
        <tr>
          <td class="key"><label for="border_width">Main border width: </label></td>
          <td>
            <input type="text" onchange="change_width()" name="border_width" id="border_width" size="10" value="<?php echo $row->border_width; ?>"/>px
          </td>
        </tr>
		<tr>
          <td class="key"><label for="show_cat1">Display Category Legend: </label></td>
          <td>
            <input type="radio" name="show_cat" id="show_cat0" value="0" class="inputbox" <?php cheched($row->show_cat, '0'); ?>>
            <label for="show_cat0">No</label>
            <input type="radio" name="show_cat" id="show_cat1" value="1" <?php cheched($row->show_cat, '1'); ?> class="inputbox">
            <label for="show_cat1">Yes</label>
          </td>
        </tr>
        <tfoot>
          <tr style="text-align:center">
            <td colspan="11">
              <?php if (($row->id < 18) && ($id > 0)) { ?>
              <img onclick="reset_theme_<?php echo $row->id; ?>();" src="<?php echo plugins_url("elements/reset_theme.png", __FILE__) ?>"/>
              <?php }?>
            </td>
          </tr>
        </tfoot>
      </table>
    </fieldset>

    <fieldset class="adminform">
      <legend>Header parameters</legend>
      <table class="admintable">
      <tr>
        <td class="key"><label for="top_height">Header height: </label></td>
        <td>
          <input onchange="change_width()" type="text" name="top_height" id="top_height" size="10" value="<?php echo $row->top_height; ?>"/>px
        </td>
      </tr>
      <tr>
        <td class="key"><label for="bg_top">Header background color: </label></td>
        <td>
			<div class="color_for_this" style="background-color: #<?php echo $row->bg_top; ?>">
				<input type="text" name="bg_top" id="bg_top" size="20" value="<?php echo $row->bg_top; ?>" class="color_input wp-color-picker"/>
			</div>
        </td>
      </tr>
      <tr>
        <td class="key"><label for="month_font_size">Current month font size: </label></td>
        <td>
          <input type="text" name="month_font_size" id="month_font_size" size="10" value="<?php echo $row->month_font_size; ?>"/>px
        </td>
      </tr>
      <tr>
        <td class="key"><label for="text_color_month">Current month color: </label></td>
        <td>
			<div class="color_for_this" style="background-color: #<?php echo $row->text_color_month; ?>">
				<input type="text" name="text_color_month" id="text_color_month" size="20" value="<?php echo $row->text_color_month; ?>" class="color_input wp-color-picker"/>
		  </div>
        </td>
      </tr>
      <tr>
        <td class="key"><label for="arrow_color_month">Current month arrow color: </label></td>
        <td>
			<div class="color_for_this" style="background-color: #<?php echo $row->arrow_color_month; ?>">
				<input type="text" name="arrow_color_month" id="arrow_color_month" size="20" value="<?php echo $row->arrow_color_month; ?>" class="color_input wp-color-picker"/>
			</div>
        </td>
      </tr>
      <tr>
        <td class="key"><label for="arrow_size">Arrow size: </label></td>
        <td>
          <input type="text" name="arrow_size" id="arrow_size" size="10" value="<?php echo $row->arrow_size; ?>"/>px
        </td>
      </tr>
      <tr>
        <td class="key"><label for="text_color_week_days">Color of weekday names: </label></td>
        <td>
			<div class="color_for_this" style="background-color: #<?php echo $row->text_color_week_days; ?>">
				<input type="text" name="text_color_week_days" id="text_color_week_days" size="20" value="<?php echo $row->text_color_week_days; ?>" class="color_input wp-color-picker"/>
			</div>
        </td>
      </tr>
      <tr>
        <td class="key"><label for="week_days_cell_height">Cell height of weekday names: </label></td>
        <td>
          <input type="text" name="week_days_cell_height" id="week_days_cell_height" size="10" value="<?php echo $row->week_days_cell_height; ?>"/>px
        </td>
      </tr>
      <tr>
        <td class="key"><label for="weekdays_bg_color">Background color of weekday names: </label></td>
        <td>	
			<div class="color_for_this" style="background-color: #<?php echo $row->weekdays_bg_color; ?>">
				<input type="text" name="weekdays_bg_color" id="weekdays_bg_color" size="20" value="<?php echo $row->weekdays_bg_color; ?>" class="color_input wp-color-picker"/>
			</div>
        </td>
      </tr>
      <tr>
        <td class="key"><label for="weekday_sunday_bg_color">Sunday background color: </label></td>
        <td>
			<div class="color_for_this" style="background-color: #<?php echo $row->weekday_sunday_bg_color; ?>">
				<input type="text" name="weekday_sunday_bg_color" id="weekday_sunday_bg_color" size="20" value="<?php echo $row->weekday_sunday_bg_color; ?>" class="color_input wp-color-picker"/>
			</div>
        </td>
      </tr>
      <tr>
        <td class="key"><label for="weekdays_font_size">Font size of Weekday Names: </label></td>
        <td>
          <input type="text" name="weekdays_font_size" id="weekdays_font_size" size="10" value="<?php echo $row->weekdays_font_size; ?>"/>px
        </td>
      </tr>
      </table>
    </fieldset>
  </div>

  <div class="col width-25">
    <fieldset class="adminform">
      <legend>Body parameters</legend>
      <table class="admintable">
        <tr>
          <td class="key"><label for="bg_bottom">Background color: </label></td>
          <td>
			<div class="color_for_this" style="background-color: #<?php echo $row->bg_bottom; ?>">
				<input type="text" name="bg_bottom" id="bg_bottom" size="20" value="<?php echo $row->bg_bottom; ?>" class="color_input wp-color-picker"/>
			</div>
          </td>
        </tr>
        <tr>
          <td class="key"><label for="cell_height">Cell height: </label></td>
          <td>
            <input onchange="change_width()" type="text" name="cell_height" id="cell_height" size="10" value="<?php echo $row->cell_height; ?>"/>px
          </td>
        </tr>
        <tr>
          <td class="key"><label for="text_color_other_months">Text color of other month's days: </label></td>
          <td>
			<div class="color_for_this" style="background-color: #<?php echo $row->text_color_other_months; ?>">
				<input type="text" name="text_color_other_months" id="text_color_other_months" size="20" value="<?php echo $row->text_color_other_months; ?>" class="color_input wp-color-picker"/>
			</div>
          </td>
        </tr>
        <tr>
          <td class="key"><label for="bg_color_other_months">Background color of other month's days: </label></td>
          <td>
			<div class="color_for_this" style="background-color: #<?php echo $row->bg_color_other_months; ?>">
				<input type="text" name="bg_color_other_months" id="bg_color_other_months" size="20" value="<?php echo $row->bg_color_other_months; ?>" class="color_input wp-color-picker"/>
			</div>
          </td>
        </tr>
        <tr>
          <td class="key"><label for="text_color_this_month_unevented">Cell text color without events: </label></td>
          <td>
			<div class="color_for_this" style="background-color: #<?php echo $row->text_color_this_month_unevented; ?>">
				<input type="text" name="text_color_this_month_unevented" id="text_color_this_month_unevented" size="20" value="<?php echo $row->text_color_this_month_unevented; ?>" class="color_input wp-color-picker"/>
			</div>
          </td>
        </tr>
        <tr>
          <td class="key"><label for="text_color_this_month_evented">Cell text color with events: </label></td>
          <td>
			<div class="color_for_this" style="background-color: #<?php echo $row->text_color_this_month_evented; ?>">
				<input type="text" name="text_color_this_month_evented" id="text_color_this_month_evented" size="20" value="<?php echo $row->text_color_this_month_evented; ?>" class="color_input wp-color-picker"/>
			</div>
          </td>
        </tr>
        <tr>
          <td class="key"><label for="bg_color_this_month_evented">Cell background color with events: </label></td>
          <td>
			<div class="color_for_this" style="background-color: #<?php echo $row->bg_color_this_month_evented; ?>">
				<input type="text" name="bg_color_this_month_evented" id="bg_color_this_month_evented" size="20" value="<?php echo $row->bg_color_this_month_evented; ?>" class="color_input wp-color-picker"/>
			</div>
          </td>
        </tr>
        <tr>
          <td class="key"><label for="event_title_color">Event title color: </label></td>
          <td>
			<div class="color_for_this" style="background-color: #<?php echo $row->event_title_color; ?>">
				<input type="text" name="event_title_color" id="event_title_color" size="20" value="<?php echo $row->event_title_color; ?>" class="color_input wp-color-picker"/>
			</div>
          </td>
        </tr>
        <tr>
          <td class="key"><label for="current_day_border_color">Current day cell border color: </label></td>
          <td>
			<div class="color_for_this" style="background-color: #<?php echo $row->current_day_border_color; ?>">
				<input type="text" name="current_day_border_color" id="current_day_border_color" size="20" value="<?php echo $row->current_day_border_color; ?>" class="color_input wp-color-picker"/>
			</div>
          </td>
        </tr>
        <tr>
          <td class="key"><label for="cell_border_color">Cell border color: </label></td>
          <td>
			<div class="color_for_this" style="background-color: #<?php echo $row->cell_border_color; ?>">
				<input type="text" name="cell_border_color" id="cell_border_color" size="20" value="<?php echo $row->cell_border_color; ?>" class="color_input wp-color-picker"/>
			</div>
          </td>
        </tr>
        <tr>
          <td class="key"><label for="text_color_sun_days">Sundays text color: </label></td>
          <td>	
			<div class="color_for_this" style="background-color: #<?php echo $row->text_color_sun_days; ?>">
				<input type="text" name="text_color_sun_days" id="text_color_sun_days" size="20" value="<?php echo $row->text_color_sun_days; ?>" class="color_input wp-color-picker"/>
			</div>
          </td>
        </tr>
        <tr>
          <td class="key"><label for="sundays_bg_color">Sundays cell background color: </label></td>
          <td>
			<div class="color_for_this" style="background-color: #<?php echo $row->sundays_bg_color; ?>">
				<input type="text" name="sundays_bg_color" id="sundays_bg_color" size="20" value="<?php echo $row->sundays_bg_color; ?>" class="color_input wp-color-picker"/>
			</div>
          </td>
        </tr>
        <tr>
          <td class="key"><label for="sundays_font_size">Sundays font size: </label></td>
          <td>
            <input type="text" name="sundays_font_size" id="sundays_font_size" size="10" value="<?php echo $row->sundays_font_size; ?>"/>px
          </td>
        </tr>
        <tr>
          <td class="key"><label for="other_days_font_size">Days font size: </label></td>
          <td>
            <input type="text" name="other_days_font_size" id="other_days_font_size" size="10" value="<?php echo $row->other_days_font_size; ?>"/>px
          </td>
        </tr>
        <tr>
          <td class="key"><label for="show_time1">Show time in cell: </label></td>
          <td>
            <input type="radio" name="show_time" id="show_time0" value="0" class="inputbox" <?php cheched($row->show_time, '0'); ?>>
            <label for="show_time0">No</label>
            <input type="radio" name="show_time" id="show_time1" value="1" <?php cheched($row->show_time, '1'); ?> class="inputbox">
            <label for="show_time1">Yes</label>
          </td>
        </tr>
        <tr>
          <td class="key"><label for="published">Show numbers for event: </label></td>
          <td>
            <input type="radio" name="show_event" id="show_event0" value="0" <?php cheched($row->day_start, '0'); ?> class="inputbox">
            <label for="show_event0">No</label>
            <input type="radio" name="show_event" id="show_event1" value="1" <?php cheched($row->day_start,'1'); ?> class="inputbox">
            <label for="show_event1">Yes</label>
          </td>
        </tr>
        <tr>
          <td class="key"><label for="number_of_shown_evetns">Number of displayed events: </label></td>
          <td>
            <input type="text" name="number_of_shown_evetns" id="number_of_shown_evetns" size="10" value="<?php echo $row->number_of_shown_evetns; ?>"/>
          </td>
        </tr>
        <tr>
          <td class="key"><label for="ev_title_bg_color">Event titles background color: </label></td>
          <td>
			<div class="color_for_this" style="background-color: #<?php echo $row->ev_title_bg_color; ?>">
				<input type="text" name="ev_title_bg_color" id="ev_title_bg_color" size="20" value="<?php echo $row->ev_title_bg_color; ?>" class="color_input wp-color-picker"/>
			</div>
          </td>
        </tr>
        <tr>
          <td class="key"><label for="views_tabs_bg_color">Views tabs background color: </label></td>
          <td>
			<div class="color_for_this" style="background-color: #<?php echo $row->views_tabs_bg_color; ?>">
				<input type="text" name="views_tabs_bg_color" id="views_tabs_bg_color" size="20" value="<?php echo $row->views_tabs_bg_color; ?>" class="color_input wp-color-picker"/>
			</div>
          </td>
        </tr>
        <tr>
          <td class="key"><label for="views_tabs_text_color">Views tabs text color: </label></td>
          <td>
			<div class="color_for_this" style="background-color: #<?php echo $row->views_tabs_text_color; ?>">
				<input type="text" name="views_tabs_text_color" id="views_tabs_text_color" size="20" value="<?php echo $row->views_tabs_text_color; ?>" class="color_input wp-color-picker"/>
			</div>
          </td>
        </tr>
        <tr>
          <td class="key"><label for="views_tabs_font_size">Views tabs font size: </label></td>
          <td>
            <input type="text" name="views_tabs_font_size" id="views_tabs_font_size" size="10" value="<?php echo $row->views_tabs_font_size; ?>"/>px
          </td>
        </tr>
      </table>
    </fieldset>
  </div>

  <div class="col width-25">
    <fieldset class="adminform">
      <legend>Popup window parameters</legend>
      <table class="admintable">
        <tr>
          <td class="key"><label for="date_format">Date format in popup (w/d/m/y): </label></td>
          <td>
            <input type="text" name="date_format" id="date_format" size="10" value="<?php echo (($row->date_format) ? $row->date_format : 'w/d/m/y'); ?>"/>
          </td>
        </tr>
        <tr>
          <td class="key"><label for="title_color">Event title color in popup: </label></td>
          <td>
			<div class="color_for_this" style="background-color: #<?php echo $row->title_color; ?>">
				<input type="text" name="title_color" id="title_color" size="20" value="<?php echo $row->title_color; ?>" class="color_input wp-color-picker"/>
			</div>
          </td>
        </tr>
        <tr>
          <td class="key"><label for="title_font_size">Event title font size in popup: </label></td>
          <td>
            <input type="text" name="title_font_size" id="title_font_size" size="10" value="<?php echo $row->title_font_size; ?>"/>px
          </td>
        </tr>
        <tr>
          <td class="key"><label for="title_font">Event title font family in popup: </label></td>
          <td>
            <select name="title_font" id="title_font" class="inputbox">
              <option value="">- Select Font -</option>
              <option <?php selectted($row->title_font, 'Arial') ?> value="Arial">Arial</option>
              <option <?php selectted($row->title_font, 'Courier New') ?> value="Courier New">Courier New</option>
              <option <?php selectted($row->title_font, 'Georgia') ?> value="Georgia">Georgia</option>
              <option <?php selectted($row->title_font, 'Tahoma') ?> value="Tahoma">Tahoma</option>
              <option <?php selectted($row->title_font, 'Verdana') ?> value="Verdana">Verdana</option>
              <option <?php selectted($row->title_font, 'Impact') ?> value="Impact">Impact</option>
            </select>
          </td>
        </tr>
        <tr>
          <td class="key"><label for="title_style">Event title font style in popup: </label></td>
          <td>
            <select name="title_style" id="title_style" class="inputbox">
              <option <?php selectted($row->title_style, 'normal') ?> value="normal">Normal</option>
              <option <?php selectted($row->title_style, 'bold') ?> value="bold">Bold</option>
              <option <?php selectted($row->title_style, 'italic') ?> value="italic">Italic</option>
              <option <?php selectted($row->title_style, 'bold/italic') ?> value="bold/italic">Bold and Italic</option>
            </select>
          </td>
        </tr>
        <tr>
          <td class="key"><label for="date_color">Date color in popup: </label></td>
          <td>
			<div class="color_for_this" style="background-color: #<?php echo $row->date_color; ?>">
				<input type="text" name="date_color" id="date_color" size="20" value="<?php echo $row->date_color; ?>" class="color_input wp-color-picker"/>
			</div>
          </td>
        </tr>
        <tr>
          <td class="key"><label for="date_size">Date font size in popup: </label></td>
          <td>
            <input type="text" name="date_size" id="date_size" size="10" value="<?php echo $row->date_size; ?>"/>px
          </td>
        </tr>
        <tr>
          <td class="key"><label for="date_font">Date font family in popup: </label></td>
          <td>
            <select name="date_font" id="date_font" class="inputbox">
              <option value="">- Select Font -</option>
              <option <?php selectted($row->date_font, 'Arial') ?> value="Arial">Arial</option>
              <option <?php selectted($row->date_font, 'Courier New') ?> value="Courier New">Courier New</option>
              <option <?php selectted($row->date_font, 'Georgia') ?> value="Georgia">Georgia</option>
              <option <?php selectted($row->date_font, 'Tahoma') ?> value="Tahoma">Tahoma</option>
              <option <?php selectted($row->date_font, 'Verdana') ?> value="Verdana">Verdana</option>
              <option <?php selectted($row->date_font, 'Impact') ?> value="Impact">Impact</option>
            </select>
          </td>
        </tr>
        <tr>
          <td class="key"><label for="date_style">Date style in popup: </label></td>
          <td>
            <select name="date_style" id="date_style" class="inputbox">
              <option value="normal" <?php selectted($row->date_style, 'normal') ?> >Normal</option>
              <option value="bold" <?php selectted($row->date_style, 'bold') ?> >Bold</option>
              <option value="italic" <?php selectted($row->date_style, 'italic') ?> >Italic</option>
              <option value="bold/italic" <?php selectted($row->date_style, 'bold/italic') ?> >Bold and Italic</option>
            </select>
          </td>
        </tr>
        <tr>
          <td class="key"><label for="next_prev_event_bgcolor">Arrow background color in popup: </label></td>
          <td>
			<div class="color_for_this" style="background-color: #<?php echo $row->next_prev_event_bgcolor; ?>">
				<input type="text" name="next_prev_event_bgcolor" id="next_prev_event_bgcolor" size="20" value="<?php echo $row->next_prev_event_bgcolor; ?>" class="color_input wp-color-picker"/>
			</div>
          </td>
        </tr>
        <tr>
          <td class="key"><label for="next_prev_event_arrowcolor">Arrow color in popup: </label></td>
          <td>
			<div class="color_for_this" style="background-color: #<?php echo $row->next_prev_event_arrowcolor; ?>">
				<input type="text" name="next_prev_event_arrowcolor" id="next_prev_event_arrowcolor" size="20" value="<?php echo $row->next_prev_event_arrowcolor; ?>" class="color_input wp-color-picker"/>
			</div>
          </td>
        </tr>
        <tr>
          <td class="key"><label for="show_event_bgcolor">Popup background color: </label></td>
          <td>
			<div class="color_for_this" style="background-color: #<?php echo $row->show_event_bgcolor; ?>">
				<input type="text" name="show_event_bgcolor" id="show_event_bgcolor" size="20" value="<?php echo $row->show_event_bgcolor; ?>" class="color_input wp-color-picker"/>
			</div>
          </td>
        </tr>
        <tr>
          <td class="key"><label for="popup_width">Popup width: </label></td>
          <td>
            <input type="text" name="popup_width" id="popup_width" size="10" value="<?php echo $row->popup_width; ?>"/>px
          </td>
        </tr>
        <tr>
          <td class="key"><label for="popup_height">Popup height: </label></td>
          <td>
            <input type="text" name="popup_height" id="popup_height" size="10" value="<?php echo $row->popup_height; ?>"/>px
          </td>
        </tr>
        <tr>
          <td class="key"><label for="show_repeat1">Show the repeat rate: </label></td>
          <td>
            <input type="radio" name="show_repeat" id="show_repeat0" value="0" <?php cheched($row->show_repeat, '0'); ?> class="inputbox">
            <label for="show_repeat0">No</label>
            <input type="radio" name="show_repeat" id="show_repeat1" value="1" <?php cheched($row->show_repeat, '1'); ?> class="inputbox">
            <label for="show_repeat1">Yes</label>
          </td>
        </tr>
      </table>
    </fieldset>
  </div>
  <div class="col width-25">
    <fieldset class="adminform">
      <legend>Other views parameters</legend>
      <table class="admintable">
        <tr>
          <td class="key"><label for="date_bg_color">Date background color: </label></td>
          <td>
			<div class="color_for_this" style="background-color: #<?php echo $row->date_bg_color; ?>">
				<input type="text" name="date_bg_color" id="date_bg_color" size="20" value="<?php echo $row->date_bg_color; ?>" class="color_input wp-color-picker"/>
			</div>
          </td>
        </tr>
        <tr>
          <td class="key"><label for="event_bg_color1">Event background color1: </label></td>
          <td>
			<div class="color_for_this" style="background-color: #<?php echo $row->event_bg_color1; ?>">
				<input type="text" name="event_bg_color1" id="event_bg_color1" size="20" value="<?php echo $row->event_bg_color1; ?>" class="color_input wp-color-picker"/>
			</div>
          </td>
        </tr>
        <tr>
          <td class="key"><label for="event_bg_color2">Event background color2: </label></td>
          <td>
			<div class="color_for_this" style="background-color: #<?php echo $row->event_bg_color2; ?>">
				<input type="text" name="event_bg_color2" id="event_bg_color2" size="20" value="<?php echo $row->event_bg_color2; ?>" class="color_input wp-color-picker"/>
			</div>
          </td>
        </tr>
        <tr>
          <td class="key"><label for="event_num_bg_color1">Event number background color 1: </label></td>
          <td>
			<div class="color_for_this" style="background-color: #<?php echo $row->event_num_bg_color1; ?>">
				<input type="text" name="event_num_bg_color1" id="event_num_bg_color1" size="20" value="<?php echo $row->event_num_bg_color1; ?>" class="color_input wp-color-picker"/>
			</div>
          </td>
        </tr>
        <tr>
          <td class="key"><label for="event_num_bg_color2">Event number background color 2: </label></td>
          <td>
			<div class="color_for_this" style="background-color: #<?php echo $row->event_num_bg_color2; ?>">
				<input type="text" name="event_num_bg_color2" id="event_num_bg_color2" size="20" value="<?php echo $row->event_num_bg_color2; ?>" class="color_input wp-color-picker"/>
			</div>
          </td>
        </tr>
        <tr>
          <td class="key"><label for="event_num_color">Event number color: </label></td>
          <td>
			<div class="color_for_this" style="background-color: #<?php echo $row->event_num_color; ?>">
				<input type="text" name="event_num_color" id="event_num_color" size="20" value="<?php echo $row->event_num_color; ?>" class="color_input wp-color-picker"/>
			</div>
          </td>
        </tr>
        <tr>
          <td class="key"><label for="day_month_font_color">Day and month font color: </label></td>
          <td>
			<div class="color_for_this" style="background-color: #<?php echo $row->day_month_font_color; ?>">
				<input type="text" name="day_month_font_color" id="day_month_font_color" size="20" value="<?php echo $row->day_month_font_color; ?>" class="color_input wp-color-picker"/>
			</div>
          </td>
        </tr>
        <tr>
          <td class="key"><label for="week_font_color">Day of the week font color: </label></td>
          <td>
			<div class="color_for_this" style="background-color: #<?php echo $row->week_font_color; ?>">
				<input type="text" name="week_font_color" id="week_font_color" size="20" value="<?php echo $row->week_font_color; ?>" class="color_input wp-color-picker"/>
			</div>
          </td>
        </tr>
        <tr>
          <td class="key"><label for="date_font_size">Date font size: </label></td>
          <td>
            <input type="text" name="date_font_size" id="date_font_size" size="10" value="<?php echo $row->date_font_size; ?>"/>px
          </td>
        </tr>
        <tr>
          <td class="key"><label for="event_num_font_size">Event number font size: </label></td>
          <td>
            <input type="text" name="event_num_font_size" id="event_num_font_size" size="10" value="<?php echo $row->event_num_font_size; ?>"/>px
          </td>
        </tr>
        <tr>
          <td class="key"><label for="event_table_height">Event cell height: </label></td>
          <td>
            <input type="text" name="event_table_height" id="event_table_height" size="10" value="<?php echo $row->event_table_height; ?>"/>px
          </td>
        </tr>
        <tr>
          <td class="key"><label for="date_height">Date cell height: </label></td>
          <td>
            <input type="text" name="date_height" id="date_height" size="10" value="<?php echo $row->date_height; ?>"/>px
          </td>
        </tr>
        <tr>
          <td class="key"><label for="day_month_font_size">Day and month font size: </label></td>
          <td>
            <input type="text" name="day_month_font_size" id="day_month_font_size" size="10" value="<?php echo $row->day_month_font_size; ?>"/>px
          </td>
        </tr>
        <tr>
          <td class="key"><label for="week_font_size">Day of the week font size: </label></td>
          <td>
            <input type="text" name="week_font_size" id="week_font_size" size="10" value="<?php echo $row->week_font_size; ?>"/>px
          </td>
        </tr>
      </table>
    </fieldset>
  </div>
  <?php wp_nonce_field('nonce_sp_cal', 'nonce_sp_cal'); ?>
  <input type="hidden" name="option" value="com_spidercalendar"/>
  <input type="hidden" name="id" value="<?php echo $row->id; ?>"/>
  <input type="hidden" name="cid[]" value="<?php echo $row->id; ?>"/>
  <input type="hidden" name="task" value=""/>
  </form>
  <?php
}

function cheched($row, $y) {
  if ($row == $y) {
    echo 'checked="checked"';
  }
}

function selectted($row, $y) {
  if ($row == $y) {
    echo 'selected="selected"';
  }
}

?>