<?php
// ---------------------------------------------------------------------------
// Guards
// ---------------------------------------------------------------------------
if ( empty( $args ) || ! is_array( $args ) ) {
    return;
}

$o_term = $args['o_term'] ?? null;
if ( ! is_object( $o_term )
    || empty( $o_term->term_taxonomy_id )
    || empty( $o_term->slug )
) {
    return;
}

$counter = $args['counter'] ?? null;
if ( $counter === null ) {
    return;
}

// ---------------------------------------------------------------------------
// Shared values
// ---------------------------------------------------------------------------
$expand_id     = $counter . '-' . $o_term->term_taxonomy_id;
$phase_class   = trim(preg_replace('/[^a-z0-9_-]+/', '-', strtolower($args['phase_label'] ?? '')), '-');
$data_term     = $counter . '-' . $o_term->slug;
$panel_base    = 'panel'
    . ' panel-' . $counter . '-' . $o_term->term_taxonomy_id
    . ' panel-' . $counter . '-' . $o_term->slug;

$daily_slideshow_slides = $args['daily_slideshow']      ?? [];
$planning_assessments   = $args['planning_assessments'] ?? [];
$sh_asset_label         = $args['sh_asset_label']       ?? '';
$plng_asset_label       = $args['plng_asset_label']     ?? '';

$phonics_color          = get_field('phonics_color', 'term_' . $o_term->term_taxonomy_id) ?: '#f7941d';
$display_new_label      = get_field('display_new_label', 'term_' . $o_term->term_taxonomy_id) ?: false;
$panel_border           = 'style="border: 2px solid ' . esc_attr($phonics_color) . ';"';
$panel_heading_back     = 'style="background-color: ' . esc_attr($phonics_color) . ';"';
$day_week_badge_fill    = 'style="background: ' . esc_attr($phonics_color) . ';"';
$day_week_border        = 'style="border: 4px solid ' . esc_attr($phonics_color) . ';"';
$label_color            = 'style="color: ' . esc_attr($phonics_color) . ';"';
?>

<?php
// ===========================================================================
// SHELF 1 — Planning & Assessments
// ===========================================================================
?>
<?php if ( ! empty( $planning_assessments ) ) : ?>
<?php
    $pa_carousel_id   = 'carousel-taxo-pa-' . $counter . '-' . $o_term->term_taxonomy_id;
    $pa_panel_classes = $panel_base . ' panel-planning-assessments';
    $pa_pages         = pa_chunk_posts( $planning_assessments );   // array of pages, 6 posts each
    $pa_total_pages   = count( $pa_pages );
    $pa_global_idx    = 0;   // running index across all pages for unique element IDs
?>
<div class="shelf-wrapper planning-and-assessment <?= $phase_class; ?>">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div id="expand-pa-<?php echo esc_attr( $expand_id ); ?>"
                     class="wk-panel-shelf expand"
                     data-id="accordion">

                    <div class="<?php echo esc_attr( $pa_panel_classes ); ?>"
                         data-term="<?php echo esc_attr( $data_term ); ?>" <?= $panel_border; ?>>

                        <div class="carousel slide" id="<?php echo esc_attr( $pa_carousel_id ); ?>">

                            <!-- Heading -->
                            <div class="panel-heading" <?= $panel_heading_back; ?>>
                                <i class="glyphicon glyphicon-inbox bookshelf-glyphicon"></i>
                                <?php echo esc_html( $plng_asset_label ); ?>
                                <span class="pull-right">
                                    <a role="button"
                                       class="btn btn-small btn-shelf-expand"
                                       style="display:none"
                                       href="#collapse-pa-<?php echo esc_attr( $expand_id ); ?>"
                                       data-parent="#accordion">
                                        <span class="glyphicon glyphicon-circle-plus bookshelf-glyphicon"></span>
                                        <span class="sr-only">Toggle</span>
                                    </a>
                                </span>
                                <span class="clearfix"></span>
                            </div><!-- /.panel-heading -->

                            <!-- Carousel pages -->
                            <div class="panel-body ebook__panel-body">
                                <?php if ($display_new_label) { ?>
                                    <div class="ebook__new-label">
                                        <h3>NEW</h3>
                                    </div>
                                <?php } ?>
                                <div class="carousel-inner">

                                    <?php foreach ( $pa_pages as $page_num => $page_posts ) : ?>
                                    <div class="item<?php echo $page_num === 0 ? ' active' : ''; ?>">
                                        <div class="row">

                                            <?php foreach ( $page_posts as $pa ) :
                                                $asset_data          = get_field( 'support_material_assets', $pa->ID );
                                                $img_src             = $asset_data['icon']                ?? '';
                                                $primary_button_text = $asset_data['primary_button_text'] ?? '';
                                                $phase_number        = $asset_data['phase_number'] ?? '';
                                                $primary_button_link = $asset_data['primary_button_link'] ?? '#';
                                                $is_flipable         = $asset_data['is_flipable_file'] ?? false;
                                                $iframe_url          = $asset_data['iframe_url'] ?? '';
                                                $final_iframe_url    = $is_flipable ? $iframe_url : $primary_button_link;
                                                $item_id             = $o_term->slug . '-pa-' . $pa_global_idx++;
                                            ?>
                                            <div data-support="<?php echo esc_attr( strtolower( str_replace( ' ', '-', $pa->post_title ) ) ); ?>"
                                                 class="thumb accordion-shelf-book col-xsp-12 col-xsl-6 col-xs-4 col-sm-2 text-center"
                                                 data-comprehension=""
                                                 data-text=""
                                                 data-title="<?php echo esc_attr( $pa->post_title ); ?>"
                                                 data-pages=""
                                                 id="<?php echo esc_attr( $item_id ); ?>">

                                                <div class="item-detail link-<?php echo esc_attr( $pa->ID ); ?>">
                                                    <span class="sr-only"><?php echo esc_html( $pa->post_title ); ?></span>
                                                    <div class="bookshelf-item-wrapper">
                                                        <div class="assessment-icon">
                                                        	<input type="hidden" class="img-source" value="<?php echo esc_url( $img_src ); ?>">

                                                            <?php if(!empty($img_src)): ?>
                                                                <img class="img-responsive img-rounded"
                                                                 alt="<?php echo esc_attr( $pa->post_title ); ?>"
                                                                 data-value="<?php echo esc_url( $img_src ); ?>"
                                                                 src="<?php echo esc_url( $img_src ); ?>"
                                                                 loading="lazy"
                                                                 style="width:200px; height:284px;">
                                                            <?php endif; ?>

                                                             <?php if( !empty($phase_number) ): ?>
                                                                 <span class="phase-number"><?= $phase_number; ?></span>
                                                             <?php endif; ?>
                                                         </div>

                                                    	<div class="action-buttons">
                                                            <?php if(!empty($final_iframe_url)): ?>
                                                                <?php if ( is_user_logged_in() ) : ?>
                                                            		<a href="<?php echo get_permalink($pa->ID).'?sm_type=assessment'; ?>" target="_blank">
        	                                                            <span <?= $panel_heading_back; ?>><?php echo esc_html( $primary_button_text ?: 'Assessment' ); ?></span>
        	                                                        </a>
                                                                <?php else : ?>
                                                                    <a href="#" class="primary-button login-required-link" data-tooltip="Login required" onclick="return false;">
                                                                        <span <?php echo $panel_heading_back ?? ''; ?>><?php echo esc_html( $primary_button_text ?: 'Assessment' ); ?></span>
                                                                     </a>
                                                                <?php endif; ?>
                                                            <?php endif; ?>
                                                        </div>
                                                        
                                                    </div>
                                                </div>

                                            </div><!-- /.thumb -->
                                            <?php endforeach; ?>

                                        </div><!-- /.row -->
                                    </div><!-- /.item -->
                                    <?php endforeach; ?>

                                </div><!-- /.carousel-inner -->
                            </div><!-- /.panel-body -->

                            <?php if ( $pa_total_pages > 1 ) : ?>
                            <!-- Prev / next arrows — OUTSIDE .panel-body, INSIDE .carousel.slide -->
                            <a class="left carousel-control bg-frontpage"
                               href="#<?php echo esc_attr( $pa_carousel_id ); ?>"
                               data-slide="prev">
                                <span class="arrow-left-wrapper">
                                    <span class="glyphicon glyphicon-chevron-left x2 library-arrow left"></span>
                                </span>
                                <span class="sr-only">Left Slide</span>
                            </a>
                            <a class="right carousel-control bg-frontpage"
                               href="#<?php echo esc_attr( $pa_carousel_id ); ?>"
                               data-slide="next">
                                <span class="arrow-right-wrapper">
                                    <span class="glyphicon glyphicon-chevron-right x2 library-arrow right"></span>
                                </span>
                                <span class="sr-only">Right Slide</span>
                            </a>
                            <?php endif; ?>

                        </div><!-- /.carousel.slide -->
                    </div><!-- /.panel -->
                </div><!-- /.wk-panel-shelf -->

                <!-- Hidden Rows -->
                <div id="collapse-pa-<?php echo esc_attr( $expand_id ); ?>" class="wk-panel-shelf collapse">
                    <div class="<?php echo esc_attr( $pa_panel_classes ); ?>" data-term="<?php echo esc_attr( $data_term ); ?>" <?= $panel_border; ?>>
                        <div class="panel-heading" <?= $panel_heading_back; ?>>
                            <i class="glyphicon glyphicon-inbox bookshelf-glyphicon"></i>
                            <?php echo esc_html( $plng_asset_label ); ?>
                            <span class="pull-right">
                                <a role="button" tabindex="0" class="btn btn-small btn-shelf-close-bottom"
                                    href="#collapse-pa-<?php echo esc_attr( $expand_id ); ?>" data-toggle="collapse"
                                    data-parent="#accordion">
                                    <span class="glyphicon glyphicon-circle-minus bookshelf-glyphicon "></span>
                                    <span class="sr-only">Toggle</span>
                                </a>
                            </span>
                            <span class="clearfix"></span>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                            </div>
                        </div>
                    </div>
                </div>

            </div><!-- /.col-sm-12 -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div><!-- /.shelf-wrapper planning-and-assessment -->
<?php endif; ?>


<?php
// ===========================================================================
// SHELF 2 — Daily Slideshow
// ===========================================================================
?>

<?php if ( ! empty( $daily_slideshow_slides ) ) : ?>
<?php
    $ds_carousel_id   = 'carousel-taxo-ds-' . $counter . '-' . $o_term->term_taxonomy_id;
    $ds_panel_classes = $panel_base . ' panel-daily-slideshow';
    $ds_pages         = pa_chunk_posts( $daily_slideshow_slides );   // pages of 6 cards each
    $ds_total_pages   = count( $ds_pages );
    $ds_global_idx    = 0;
?>
<div class="shelf-wrapper daily-slideshow <?= $phase_class; ?>">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div id="expand-ds-<?php echo esc_attr( $expand_id ); ?>" class="wk-panel-shelf expand" data-id="accordion">
                    <div class="<?php echo esc_attr( $ds_panel_classes ); ?>" data-term="<?php echo esc_attr( $data_term ); ?>" <?php echo $panel_border ?? ''; ?>>

                        <div class="carousel slide" id="<?php echo esc_attr( $ds_carousel_id ); ?>">

                            <!-- Heading -->
                            <div class="panel-heading" <?php echo $panel_heading_back ?? ''; ?>>
                                <i class="glyphicon glyphicon-inbox bookshelf-glyphicon"></i>
                                <?php echo esc_html( $sh_asset_label ); ?>
                                <span class="pull-right">
                                    <a role="button"
                                       class="btn btn-small btn-shelf-expand"
                                       style="display:none"
                                       href="#collapse-ds-<?php echo esc_attr( $expand_id ); ?>"
                                       data-parent="#accordion">
                                        <span class="glyphicon glyphicon-circle-plus bookshelf-glyphicon"></span>
                                        <span class="sr-only">Toggle</span>
                                    </a>
                                </span>
                                <span class="clearfix"></span>
                            </div><!-- /.panel-heading -->

                            <!-- Carousel pages -->
                            <div class="panel-body ebook__panel-body">
                                <?php if ($display_new_label) { ?>
                                    <div class="ebook__new-label">
                                        <h3>NEW</h3>
                                    </div>
                                <?php } ?>
                                <div class="carousel-inner">

                                    <?php foreach ( $ds_pages as $page_num => $page_cards ) : ?>
                                    <div class="item<?php echo $page_num === 0 ? ' active' : ''; ?>">
                                        <div class="row">

                                            <?php foreach ( $page_cards as $card ) :
                                                $item_id             = $o_term->slug . '-ds-' . $ds_global_idx++;
                                                $type                = $card->is_week ? 'week' : 'day';
                                                $primary_btn_text    = $card->is_week ? 'Outline' : 'Lesson Plan';

                                                $primary_button_link = $card->primary_link ?? '';
                                                $is_flipable         = $card->is_flipable ?? false;
                                                $iframe_url          = $card->iframe_url ?? '';
                                                $final_iframe_url    = $is_flipable ? $iframe_url : $primary_button_link;

                                                $secondary_link_id   = $card->is_secondary_flipable ? $card->index_id : $card->secondary_link;
                                                $slideshow_file_type = $card->is_secondary_flipable ? 'fliphtml' : 'ppt';
                                            ?>
                                            <div data-support="<?php echo esc_attr( strtolower( str_replace( ' ', '-', $card->post_title ) ) ); ?>"
                                                 class="thumb accordion-shelf-book col-xsp-12 col-xsl-6 col-xs-4 col-sm-2 text-center <?php echo $card->is_week ? 'a-week' : 'a-day'; ?>"
                                                 data-comprehension=""
                                                 data-text=""
                                                 data-title="<?php echo esc_attr( $card->post_title ); ?>"
                                                 data-pages=""
                                                 id="<?php echo esc_attr( $item_id ); ?>">

                                                <div class="item-detail link-<?php echo esc_attr( $card->post_id ); ?>">
                                                    <span class="sr-only"><?php echo esc_html( $card->post_title ); ?></span>
                                                    <div class="bookshelf-item-wrapper">
                                                        <?php if ( $card->label ) : ?>
                                                            <h6 <?php echo $label_color ?? ''; ?>><?php echo esc_html( $card->label ); ?></h6>
                                                        <?php endif; ?>

                                                        <?php if( $card->is_week ): ?>
                                                            <div class="day-icon-box week">
                                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 500 429" width="100" height="100">
                                                                    <path d="M59.39 132.23C70.67 131.13 82.41 132 93.75 132C115.42 132 137.08 132 158.75 132C224.58 132 290.42 132 356.25 132C378.08 132 399.92 132 421.75 132C435.66 132 448.64 130.93 458.85 141.89C468.87 152.67 466.57 168.69 466.57 182.25C466.57 212.75 466.57 243.25 466.57 273.75C466.57 294.75 466.57 315.75 466.57 336.75C466.57 347.55 468.19 359.66 462.66 369.41C454.16 384.39 439.54 384.49 424.25 384.49C406.25 384.49 388.25 384.49 370.25 384.49C287.42 384.49 204.58 384.49 121.75 384.49C106.58 384.49 91.42 384.49 76.25 384.49C60.75 384.49 45.98 384.68 37.27 369.49C33.08 362.19 33.42 353.91 33.42 345.75C33.42 334.58 33.42 323.42 33.42 312.25C33.42 267.58 33.42 222.92 33.42 178.25C33.42 170.61 32.49 162.29 34.13 154.79C36.68 143.14 47.54 133.39 59.39 132.23Z" fill="#ffffff" fill-rule="evenodd" stroke="#ffffff" stroke-width="0.25" stroke-linejoin="round"/>
                                                                    <path xmlns="http://www.w3.org/2000/svg" d="M115.75 9.1C124.96 7.23 135.19 14.02 137.86 22.83C139.45 28.04 138.85 33.88 138.85 39.25C138.85 47.75 138.85 56.25 138.85 64.75C138.85 70.94 139.78 78.13 137.78 84.1C135.51 90.88 129.15 97.36 121.75 98.01C111.04 98.96 102.21 92.82 99.24 82.56C98.17 78.87 98.94 74.07 98.94 70.25C98.94 62.08 98.94 53.92 98.94 45.75C98.94 38.03 97.5 28.71 100.21 21.4C102.63 14.88 109.19 10.43 115.75 9.1ZM382.29 9.12C390.76 7.2 400.85 13.42 403.8 21.39C406.12 27.67 405.14 35.13 405.14 41.75C405.14 45.75 405.14 49.75 405.14 53.75C405.14 68.05 409.26 88.88 393.37 96.18C391.75 96.92 390.06 97.85 388.25 98.02C378.53 98.93 368.86 93.43 366.15 83.67C364.94 79.33 365.54 74.2 365.54 69.75C365.54 61.42 365.54 53.08 365.54 44.75C365.54 37.13 363.96 27.53 367.25 20.46C370.07 14.38 375.96 10.56 382.29 9.12ZM499.92 86.56C499.92 182.54 499.92 278.51 499.92 374.48C498.35 376.61 498.62 380.5 497.78 383.09C496.97 385.59 495.88 388.14 494.72 390.5C487.5 405.18 474.17 414.78 458.63 418.79C447.73 421.6 431.25 419.91 419.75 419.91C394.58 419.91 369.42 419.91 344.25 419.91C271.08 419.91 197.92 419.91 124.75 419.91C106.25 419.91 87.75 419.91 69.25 419.91C54.72 419.91 40.61 420.55 27.49 413.73C17.23 408.4 8.95 399 4.25 388.54C2.71 385.12 1.46 376.54 0.08 374.52C0.08 278.04 0.08 181.55 0.08 85.06C1.61 83.1 1.38 79.38 2.24 76.95C4.32 71.1 7.28 65.32 11.19 60.43C19.41 50.16 32.14 42.52 45.23 41.07C50.98 40.44 56.97 40.83 62.75 40.83C68.67 40.83 74.87 40.32 80.75 41C81.54 44.53 81.03 48.62 81.03 52.25C81.03 65.57 78.79 81.12 84.24 93.58C97.01 122.82 138.95 122.99 152.84 94.63C158.97 82.1 156.63 65.88 156.63 52.25C156.63 48.71 156.12 44.7 156.86 41.25C220.42 41.25 283.98 41.25 347.55 41.25C350.58 59.4 343.34 79.08 352.3 96.47C366.28 123.62 407.8 120.97 419.85 93.64C425.33 81.2 423 65.58 423 52.25C423 48.65 422.51 44.62 423.25 41.1C428.21 40.24 433.7 40.83 438.75 40.83C444.03 40.83 449.51 40.49 454.76 41.08C469.35 42.73 482.3 51.7 490.84 63.38C493.91 67.58 496.4 72.89 497.85 77.85C498.6 80.41 498.35 84.53 499.92 86.56ZM59.39 132.23C47.54 133.39 36.68 143.14 34.13 154.79C32.49 162.29 33.42 170.61 33.42 178.25C33.42 222.92 33.42 267.58 33.42 312.25C33.42 323.42 33.42 334.58 33.42 345.75C33.42 353.91 33.08 362.19 37.27 369.49C45.98 384.68 60.75 384.49 76.25 384.49C91.42 384.49 106.58 384.49 121.75 384.49C204.58 384.49 287.42 384.49 370.25 384.49C388.25 384.49 406.25 384.49 424.25 384.49C439.54 384.49 454.16 384.39 462.66 369.41C468.19 359.66 466.57 347.55 466.57 336.75C466.57 315.75 466.57 294.75 466.57 273.75C466.57 243.25 466.57 212.75 466.57 182.25C466.57 168.69 468.87 152.67 458.85 141.89C448.64 130.93 435.66 132 421.75 132C399.92 132 378.08 132 356.25 132C290.42 132 224.58 132 158.75 132C137.08 132 115.42 132 93.75 132C82.41 132 70.67 131.13 59.39 132.23Z" fill="<?= $phonics_color; ?>" fill-rule="evenodd" stroke="#f7941d" stroke-width="0.25" stroke-linejoin="round"/>
                                                                    <foreignObject x="10" y="150" width="480" height="200">
                                                                        <div xmlns="http://www.w3.org/1999/xhtml"
                                                                               id="dynamic-text"
                                                                               style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;
                                                                                      text-align:center;font-family:Arial, sans-serif;font-weight:700;color:#000000;
                                                                                      font-size:65px;line-height:1.1;overflow-wrap:break-word;word-break:break-word;
                                                                                      box-sizing:border-box;padding:0 8px;">
                                                                            <?php echo wp_kses_post( $card->icon_text ); ?>
                                                                        </div>
                                                                    </foreignObject>
                                                                </svg>
                                                            </div>
                                                        <?php else: ?>
                                                            <div class="icon-wrapper">
                                                                <div class="icon-square" <?= $day_week_border; ?>>
                                                                    <span class="icon-letter"><?php echo wp_kses_post( $card->icon_text ); ?></span>
                                                                </div>
                                                                <div class="icon-badge" <?= $day_week_badge_fill; ?>><?php echo esc_html( $card->number ); ?></div>
                                                            </div>
                                                        <?php endif;?>


                                                        <div class="action-buttons">
                                                            <?php if ( !empty( $final_iframe_url ) ) : ?>
                                                                <?php if ( is_user_logged_in() ) : ?>
                                                                    <a href="<?php echo esc_url( $card->post_link ).'?sm_type=lesson_plan&type='.$type.'&id='.$card->index_id; ?>" class="primary-button" target="_blank">
                                                                        <span <?php echo $panel_heading_back ?? ''; ?>>
                                                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 95 95"><path fill="#fff" d="M17.5 13.3c-.3.6-.4 16.5-.3 35.2l.3 34 29.4.3c22.9.2 29.6-.1 30.3-1 .9-1.5 1.1-65.8.2-68.2-.5-1.4-4.1-1.6-30-1.6-22.7 0-29.6.3-29.9 1.3m48.2 18.3c.3.9.2 2.4-.4 3.3-.9 1.4-3.4 1.6-17.8 1.6s-16.9-.2-17.8-1.6c-.6-.9-.7-2.4-.4-3.3.6-1.4 2.9-1.6 18.2-1.6s17.6.2 18.2 1.6m-.2 15.9v3h-18c-16.5 0-18-.1-18.3-1.8-.9-4.6-.3-4.8 18.5-4.5l17.8.3zm-.7 11.7c1.8 1.8 1.4 4.6-.7 5.8-2.6 1.3-30.6 1.3-33.2 0-2.1-1.2-2.5-4-.7-5.8 1.7-1.7 32.9-1.7 34.6 0"/></svg>
                                                                            <?php echo esc_html( $card->primary_text ?: $primary_btn_text ); ?>
                                                                        </span>
                                                                    </a>
                                                                <?php else : ?>
                                                                    <a href="#" class="primary-button login-required-link" data-tooltip="Login required" onclick="return false;">
                                                                        <span <?php echo $panel_heading_back ?? ''; ?>>
                                                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 95 95"><path fill="#fff" d="M17.5 13.3c-.3.6-.4 16.5-.3 35.2l.3 34 29.4.3c22.9.2 29.6-.1 30.3-1 .9-1.5 1.1-65.8.2-68.2-.5-1.4-4.1-1.6-30-1.6-22.7 0-29.6.3-29.9 1.3m48.2 18.3c.3.9.2 2.4-.4 3.3-.9 1.4-3.4 1.6-17.8 1.6s-16.9-.2-17.8-1.6c-.6-.9-.7-2.4-.4-3.3.6-1.4 2.9-1.6 18.2-1.6s17.6.2 18.2 1.6m-.2 15.9v3h-18c-16.5 0-18-.1-18.3-1.8-.9-4.6-.3-4.8 18.5-4.5l17.8.3zm-.7 11.7c1.8 1.8 1.4 4.6-.7 5.8-2.6 1.3-30.6 1.3-33.2 0-2.1-1.2-2.5-4-.7-5.8 1.7-1.7 32.9-1.7 34.6 0"/></svg>
                                                                            <?php echo esc_html( $card->primary_text ?: $primary_btn_text ); ?>
                                                                        </span>
                                                                    </a>
                                                                <?php endif; ?>
                                                            <?php endif; ?>

                                                           <?php if ( isset($secondary_link_id) && $secondary_link_id !== '' && $secondary_link_id !== null ) : ?>
                                                                <?php if ( is_user_logged_in() ) : ?>
                                                                    <a href="<?php echo esc_url( $card->post_link ).'?sm_type=slideshow&type='.$type.'&viewer=' . $slideshow_file_type . '&id=' . $secondary_link_id; ?>" class="secondary-button" target="_blank">
                                                                        <span <?php echo $panel_heading_back ?? ''; ?>>
                                                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 95 95"><g fill="#fff" stroke-width="0"><path d="M29.1 14c-5.6 1.2-11 5.4-13.6 10.6-1.6 3.3-2 6.4-2.3 19.5-.5 20.4.6 25.9 6.2 31.5 5.2 5.3 10.2 6.4 28.1 6.4s22.9-1.1 28.1-6.4c5.3-5.2 6.4-10.2 6.4-28.1 0-16.2-1.2-22.5-5.1-27-5.3-6-8.5-6.9-26.9-7.1-9.1-.2-18.5.1-20.9.6m39 9.3c4.9 3.3 5.4 5.6 5.4 24.6 0 25.5-.6 26.1-26 26.1-24.4 0-25.7-1.1-26.3-21.7-.6-18.2.2-23.5 3.9-27.2s5-3.9 24.4-3.7c13.4.1 16.4.4 18.6 1.9"/><path d="M35 31c-1.2.7-1.6 3.9-1.8 15.9-.4 15.7.3 19.1 3.8 19.1 2.5 0 28-14.8 28.7-16.7.3-.8-.3-2.3-1.3-3.3-1.8-1.8-26.2-16-27.4-16-.3 0-1.2.5-2 1"/></g></svg>
                                                                            <?php echo esc_html( $card->secondary_text ?: 'Slideshow' ); ?>
                                                                        </span>
                                                                    </a>
                                                                <?php else : ?>
                                                                    <a href="#" class="secondary-button login-required-link" data-tooltip="Login required" onclick="return false;">
                                                                        <span <?php echo $panel_heading_back ?? ''; ?>>
                                                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 95 95"><g fill="#fff" stroke-width="0"><path d="M29.1 14c-5.6 1.2-11 5.4-13.6 10.6-1.6 3.3-2 6.4-2.3 19.5-.5 20.4.6 25.9 6.2 31.5 5.2 5.3 10.2 6.4 28.1 6.4s22.9-1.1 28.1-6.4c5.3-5.2 6.4-10.2 6.4-28.1 0-16.2-1.2-22.5-5.1-27-5.3-6-8.5-6.9-26.9-7.1-9.1-.2-18.5.1-20.9.6m39 9.3c4.9 3.3 5.4 5.6 5.4 24.6 0 25.5-.6 26.1-26 26.1-24.4 0-25.7-1.1-26.3-21.7-.6-18.2.2-23.5 3.9-27.2s5-3.9 24.4-3.7c13.4.1 16.4.4 18.6 1.9"/><path d="M35 31c-1.2.7-1.6 3.9-1.8 15.9-.4 15.7.3 19.1 3.8 19.1 2.5 0 28-14.8 28.7-16.7.3-.8-.3-2.3-1.3-3.3-1.8-1.8-26.2-16-27.4-16-.3 0-1.2.5-2 1"/></g></svg>
                                                                            <?php echo esc_html( $card->secondary_text ?: 'Slideshow' ); ?>
                                                                        </span>
                                                                    </a>
                                                                <?php endif; ?>
                                                            <?php endif; ?>
                                                        </div>

                                                    </div>
                                                </div>

                                            </div><!-- /.thumb -->
                                            <?php endforeach; ?>

                                        </div><!-- /.row -->
                                    </div><!-- /.item -->
                                    <?php endforeach; ?>

                                </div><!-- /.carousel-inner -->
                            </div><!-- /.panel-body -->

                            <?php if ( $ds_total_pages > 1 ) : ?>
                            <!-- Prev / next arrows — OUTSIDE .panel-body, INSIDE .carousel.slide -->
                            <a class="left carousel-control bg-frontpage"
                               href="#<?php echo esc_attr( $ds_carousel_id ); ?>"
                               data-slide="prev">
                                <span class="arrow-left-wrapper">
                                    <span class="glyphicon glyphicon-chevron-left x2 library-arrow left"></span>
                                </span>
                                <span class="sr-only">Left Slide</span>
                            </a>
                            <a class="right carousel-control bg-frontpage"
                               href="#<?php echo esc_attr( $ds_carousel_id ); ?>"
                               data-slide="next">
                                <span class="arrow-right-wrapper">
                                    <span class="glyphicon glyphicon-chevron-right x2 library-arrow right"></span>
                                </span>
                                <span class="sr-only">Right Slide</span>
                            </a>
                            <?php endif; ?>

                        </div><!-- /.carousel.slide -->
                    </div><!-- /.panel -->
                </div><!-- /.wk-panel-shelf -->

                <!-- Hidden Rows -->
                <div id="collapse-ds-<?php echo esc_attr( $expand_id ); ?>" class="wk-panel-shelf collapse">
                    <div class="<?php echo esc_attr( $ds_panel_classes ); ?>" data-term="<?php echo esc_attr( $data_term ); ?>" <?php echo $panel_border ?? ''; ?>>
                        <div class="panel-heading" <?= $panel_heading_back; ?>>
                            <i class="glyphicon glyphicon-inbox bookshelf-glyphicon"></i>
                            <?php echo esc_html( $sh_asset_label ); ?>
                            <span class="pull-right">
                                <a role="button" tabindex="0" class="btn btn-small btn-shelf-close-bottom"
                                    href="#collapse-ds-<?php echo esc_attr( $expand_id ); ?>" data-toggle="collapse"
                                    data-parent="#accordion">
                                    <span class="glyphicon glyphicon-circle-minus bookshelf-glyphicon "></span>
                                    <span class="sr-only">Toggle</span>
                                </a>
                            </span>
                            <span class="clearfix"></span>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                            </div>
                        </div>
                    </div>
                </div>

            </div><!-- /.col-sm-12 -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div><!-- /.shelf-wrapper daily-slideshow -->
<?php endif; ?>