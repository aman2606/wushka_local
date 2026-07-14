<?php
/**
 * Support material viewer template.
 *
 */

//if ( isset( $_GET['sm_type'] ) && isset( $_GET['id'] ) && ! empty( $_GET['id'] ) ) {
if ( in_array( $_GET['sm_type'] ?? '', ['slideshow'], true ) && !empty( $_GET['id'] ) ) {
    get_template_part('template-parts/support-material/slideshow-and-lesson-plans', null, []); 
} elseif ( isset( $_GET['sm_type'] ) && in_array( $_GET['sm_type'] ?? '', ['assessment', 'lesson_plan'], true ) ) {
    get_template_part('template-parts/support-material/planning-and-assessments', null, []);
} else {
    echo esc_html__( 'No slideshow or assessment specified.', 'your-textdomain' );
}

/* ----- END OF FILE ----- */