<?php
get_header();

global $wp_query;
$id = get_the_ID();
$template = get_field('template', $id);
$background_color = get_field('background_color',$id);
$button_colour = get_field('button_colour',$id);
$bullet_point_colour = get_field('bullet_point_colour',$id);

if(isset($wp_query->query_vars['status'])){
    if($wp_query->query_vars['status'] == 'success'){
        $args = [];
        $args['background_color'] = $background_color;
        $args['button_colour'] = $button_colour;
        get_template_part('template-parts/educational-resources/success/template', $template,$args);    
    }else{
        $wp_query->set_404();
        status_header( 404 );
        get_template_part( 404 ); exit();
    }
}else{
    //Pardot Form
    $args = array(
        "form_post_link"            =>  get_field('form_post_link', $id),
        "first_name"                =>  get_field('first_name', $id),
        "last_name"                 =>  get_field('last_name', $id),
        "email"                     =>  get_field('email', $id),
        "phone"                     =>  get_field('phone', $id),
        "country"                   =>  get_field('country', $id),
        "country_option"            =>  get_field('country_option', $id),
        "country_au_value"          =>  get_field('country_au_value', $id),
        "state"                     =>  get_field('state', $id),
        "school"                     =>  get_field('school', $id),
        "education_sector"          =>  get_field('education_sector', $id),
        "job_title"                 =>  get_field('job_title', $id),
        "state_option"              =>  get_field('state_option', $id),
        "education_sector_option"   =>  get_field('education_sector_option', $id),
        "job_title_option"          =>  get_field('job_title_option', $id),
        "terms_and_conditions"      =>  get_field('terms_and_conditions', $id)
    );

    // theme settings

    $args['background_color'] = $background_color;
    $args['button_colour'] = $button_colour;
    $args['bullet_point_colour'] = $bullet_point_colour;
    $args['lp_submit_button_text'] = get_field('lp_submit_button_text',$id);

    get_template_part('template-parts/educational-resources/template', $template, $args);
}

get_footer();
