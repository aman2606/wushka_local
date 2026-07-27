<?php
/**
 * Support material viewer template.
 */
$sm_type = isset( $_GET['sm_type'] ) ? sanitize_text_field( wp_unslash( $_GET['sm_type'] ) ) : '';
$viewer  = isset( $_GET['viewer'] ) ? sanitize_text_field( wp_unslash( $_GET['viewer'] ) ) : '';
$id      = isset( $_GET['id'] ) ? sanitize_text_field( wp_unslash( $_GET['id'] ) ) : '';

$is_slideshow  = ( $sm_type === 'slideshow' && $id !== '' );
$is_assessment = in_array( $sm_type, [ 'assessment', 'lesson_plan' ], true );

if ( $is_slideshow && $viewer === 'ppt' ) {
    get_template_part( 'template-parts/support-material/ppt-viewer', null, [] );
}  elseif ( $is_assessment || $is_slideshow) {
    get_template_part( 'template-parts/support-material/fliphtml-viewer', null, [] );
} else {
    echo esc_html__( 'No slideshow or assessment specified.', 'your-textdomain' );
}