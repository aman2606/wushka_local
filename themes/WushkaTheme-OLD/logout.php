<?php
/*
  Template Name: logout page
 */
wp_logout();
wp_redirect(home_url());
exit;
