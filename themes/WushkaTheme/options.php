<?php

function optionsframework_option_name() {
    $themename = get_option('stylesheet');
    $themename = preg_replace("/\W/", "_", strtolower($themename));

    $optionsframework_settings = get_option('optionsframework');
    $optionsframework_settings['id'] = $themename;
    update_option('optionsframework', $optionsframework_settings);
}

function optionsframework_options() {

    $options = array();

    $options[] = array(
        'name' => __('Settings', 'options_framework_theme'),
        'type' => 'heading');

    $options[] = array(
        'name' => __('Frontpage Comments Number', 'options_framework_theme'),
        'desc' => __('Enter 0 to hide comments on frontpage', 'options_framework_theme'),
        'id' => 'frontpage_comments_number',
        'std' => '3',
        'class' => 'mini',
        'type' => 'text');

    $options[] = array(
        'name' => __('Google Tag Manager', 'options_framework_theme'),
        'desc' => __('Enter the Tag Manager code here', 'options_framework_theme'),
        'id' => 'google_tag_manager',
        'std' => '',
        'class' => 'wide',
        'type' => 'text');

    $options[] = array(
        'name' => __('Bing verification', 'options_framework_theme'),
        'desc' => __('Enter the Bing verification code', 'options_framework_theme'),
        'id' => 'bing_verification',
        'std' => '',
        'class' => 'wide',
        'type' => 'text');

    $options[] = array(
        'name' => __('Pinterest verification', 'options_framework_theme'),
        'desc' => __('Enter the Pinterest verification code', 'options_framework_theme'),
        'id' => 'pinterest_verification',
        'std' => '',
        'class' => 'wide',
        'type' => 'text');

    $options[] = array(
        'name' => __('Google+', 'options_framework_theme'),
        'desc' => __('Enter the social URL', 'options_framework_theme'),
        'id' => 'google_social',
        'std' => '',
        'class' => 'wide',
        'type' => 'text');
    $options[] = array(
        'name' => __('YouTube', 'options_framework_theme'),
        'desc' => __('Enter the social URL', 'options_framework_theme'),
        'id' => 'youtube_social',
        'std' => '',
        'class' => 'wide',
        'type' => 'text');
    $options[] = array(
        'name' => __('Twitter', 'options_framework_theme'),
        'desc' => __('Enter the social URL', 'options_framework_theme'),
        'id' => 'twitter_social',
        'std' => '',
        'class' => 'wide',
        'type' => 'text');
    $options[] = array(
        'name' => __('Facebook', 'options_framework_theme'),
        'desc' => __('Enter the social URL', 'options_framework_theme'),
        'id' => 'facebook_social',
        'std' => '',
        'class' => 'wide',
        'type' => 'text');
    $options[] = array(
        'name' => __('Facebook Admin', 'options_framework_theme'),
        'desc' => __('Enter the Facebook Admin URL', 'options_framework_theme'),
        'id' => 'facebook_admin',
        'std' => '',
        'class' => 'wide',
        'type' => 'text');
    $options[] = array(
        'name' => __('Pinterest', 'options_framework_theme'),
        'desc' => __('Enter the social URL', 'options_framework_theme'),
        'id' => 'pinterest_social',
        'std' => '',
        'class' => 'wide',
        'type' => 'text');

    $options[] = array(
        'name' => __('Header Logo', 'options_framework_theme'),
        'desc' => __('Logo height should be 40px. Width is flexible. Leave blank to use site title text.', 'options_framework_theme'),
        'id' => 'logo',
        'type' => 'upload');

    return $options;
}

?>