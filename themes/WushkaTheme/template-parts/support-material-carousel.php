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
$expand_id  = $counter . '-' . $o_term->term_taxonomy_id;
$data_term  = $counter . '-' . $o_term->slug;
$panel_base = 'panel'
    . ' panel-' . $counter . '-' . $o_term->term_taxonomy_id
    . ' panel-' . $counter . '-' . $o_term->slug;

$daily_slideshow_slides = $args['daily_slideshow']      ?? [];
$planning_assessments   = $args['planning_assessments'] ?? [];
$sh_asset_label         = $args['sh_asset_label']       ?? '';
$plng_asset_label       = $args['plng_asset_label']     ?? '';

$phonics_color          = get_field('phonics_color', 'term_' . $o_term->term_taxonomy_id) ?: '#f7941d';
$panel_border           = 'style="border: 2px solid ' . esc_attr($phonics_color) . ';"';
$panel_heading_back     = 'style="background-color: ' . esc_attr($phonics_color) . ';"';
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
<div class="shelf-wrapper planning-and-assessment">
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
                                <div class="carousel-inner">

                                    <?php foreach ( $pa_pages as $page_num => $page_posts ) : ?>
                                    <div class="item<?php echo $page_num === 0 ? ' active' : ''; ?>">
                                        <div class="row">

                                            <?php foreach ( $page_posts as $pa ) :
                                                $asset_data          = get_field( 'support_material_assets', $pa->ID );
                                                $img_src             = $asset_data['icon']                ?? '';
                                                $primary_button_text = $asset_data['primary_button_text'] ?? '';
                                                $primary_button_link = $asset_data['primary_button_link'] ?? '#';
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
                                                    	<input type="hidden" class="img-source" value="<?php echo esc_url( $img_src ); ?>">
                                                        <img class="img-responsive img-rounded"
                                                         alt="<?php echo esc_attr( $pa->post_title ); ?>"
                                                         data-value="<?php echo esc_url( $img_src ); ?>"
                                                         src="<?php echo esc_url( $img_src ); ?>"
                                                         loading="lazy"
                                                         style="width:200px; height:284px;">

                                                    	<div class="action-buttons">
                                                        	<?php if ( $primary_button_link ) : ?>
                                                        		<a href="<?php echo esc_url( $primary_button_link ); ?>" target="_blank">
		                                                            <span <?= $panel_heading_back; ?>><?php echo esc_html( $primary_button_text ?: 'Assessment' ); ?></span>
		                                                        </a>
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
<?php
// ---------------------------------------------------------------------------
// Build a flat list of "card" objects: one for the Week itself, then one
// for every day in its repeater. This flat list is what gets chunked into
// pages of 6 for the carousel — exactly like before, just sourced
// differently (week + its repeater days, instead of separate posts).
// ---------------------------------------------------------------------------
$ds_cards = [];

if ( ! empty( $daily_slideshow_slides ) ) {
    foreach ( $daily_slideshow_slides as $week_post ) {
        if ( ! is_object( $week_post ) || ! isset( $week_post->ID ) ) {
            continue;
        }

        $week_assets = get_field( 'support_material_assets', $week_post->ID );
        if ( empty( $week_assets ) ) {
            continue;
        }

        // ---- The Week card itself --------------------------------------
        $ds_cards[] = (object) [
            'post_id'        => $week_post->ID,
            'post_title'     => $week_post->post_title,
            'is_week'        => true,
            'label'          => $week_assets['week_or_day_label']    ?? $week_post->post_title,
            'icon_text'      => $week_assets['icon_text']             ?? '',
            'number'         => null,
            'primary_text'   => $week_assets['primary_button_text']  ?? '',
            'primary_link'   => $week_assets['primary_button_link']  ?? '#',
            'secondary_text' => $week_assets['seconday_button_text'] ?? '',
            'secondary_link' => $week_assets['seconday_button_link'] ?? '#',
        ];

        // ---- Each Day, pulled from the repeater -------------------------
        $days = $week_assets['week_days'] ?? [];
        if ( ! empty( $days ) && is_array( $days ) ) {
            foreach ( $days as $day_idx => $day ) {
                $ds_cards[] = (object) [
                    'post_id'        => $week_post->ID . '-day-' . $day_idx, // unique synthetic id
                    'post_title'     => $day['day_label'] ?? '',
                    'is_week'        => false,
                    'label'          => $day['day_label']                  ?? '',
                    'icon_text'      => $day['day_icon_text']              ?? '',
                    'number'         => $day['day_number']                 ?? null,
                    'primary_text'   => $day['day_primary_button_text']    ?? '',
                    'primary_link'   => $day['day_primary_button_link']    ?? '#',
                    'secondary_text' => $day['day_secondary_button_text']  ?? '',
                    'secondary_link' => $day['day_secodary_button_link']  ?? '#',
                ];
            }
        }
    }
}
?>

<?php if ( ! empty( $ds_cards ) ) : ?>
<?php
    $ds_carousel_id   = 'carousel-taxo-ds-' . $counter . '-' . $o_term->term_taxonomy_id;
    $ds_panel_classes = $panel_base . ' panel-daily-slideshow';
    $ds_pages         = pa_chunk_posts( $ds_cards );   // pages of 6 cards each
    $ds_total_pages   = count( $ds_pages );
    $ds_global_idx    = 0;

    $week_icon_url = get_stylesheet_directory_uri() . '/img/support-material-images/lessonplans-icon-week.png';
    $day_icon_url  = get_stylesheet_directory_uri() . '/img/support-material-images/lessonplans-icon-day.png';
?>
<div class="shelf-wrapper daily-slideshow">
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
                                <div class="carousel-inner">

                                    <?php foreach ( $ds_pages as $page_num => $page_cards ) : ?>
                                    <div class="item<?php echo $page_num === 0 ? ' active' : ''; ?>">
                                        <div class="row">

                                            <?php foreach ( $page_cards as $card ) :
                                                $item_id  = $o_term->slug . '-ds-' . $ds_global_idx++;
                                                $img_src  = $card->is_week ? $week_icon_url : $day_icon_url;
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

                                                        <input type="hidden" class="img-source" value="<?php echo esc_url( $img_src ); ?>">
                                                        <div class="day-icon-box">
                                                            <img class="img-responsive img-rounded"
                                                                 alt="<?php echo esc_attr( $card->post_title ); ?>"
                                                                 data-value="<?php echo esc_url( $img_src ); ?>"
                                                                 src="<?php echo esc_url( $img_src ); ?>"
                                                                 loading="lazy"
                                                                 style="width:200px; height:284px;">
                                                            <span class="day-week-icon-txt"><?php echo esc_html( $card->icon_text ); ?></span>
                                                            <?php if ( ! $card->is_week && $card->number !== null ) : ?>
                                                                <span class="number"><?php echo esc_html( $card->number ); ?></span>
                                                            <?php endif; ?>
                                                        </div>

                                                        <div class="action-buttons">
                                                            <?php if ( $card->primary_link && $card->primary_link !== '#' ) : ?>
                                                                <a href="<?php echo esc_url( $card->primary_link ); ?>" target="_blank" class="primary-button">
                                                                    <span <?php echo $panel_heading_back ?? ''; ?>>
                                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 95 95"><path fill="#fff" d="M17.5 13.3c-.3.6-.4 16.5-.3 35.2l.3 34 29.4.3c22.9.2 29.6-.1 30.3-1 .9-1.5 1.1-65.8.2-68.2-.5-1.4-4.1-1.6-30-1.6-22.7 0-29.6.3-29.9 1.3m48.2 18.3c.3.9.2 2.4-.4 3.3-.9 1.4-3.4 1.6-17.8 1.6s-16.9-.2-17.8-1.6c-.6-.9-.7-2.4-.4-3.3.6-1.4 2.9-1.6 18.2-1.6s17.6.2 18.2 1.6m-.2 15.9v3h-18c-16.5 0-18-.1-18.3-1.8-.9-4.6-.3-4.8 18.5-4.5l17.8.3zm-.7 11.7c1.8 1.8 1.4 4.6-.7 5.8-2.6 1.3-30.6 1.3-33.2 0-2.1-1.2-2.5-4-.7-5.8 1.7-1.7 32.9-1.7 34.6 0"/></svg>
                                                                        <?php echo esc_html( $card->primary_text ?: 'Lesson Plan' ); ?>
                                                                    </span>
                                                                </a>
                                                            <?php endif; ?>

                                                            <?php if ( $card->secondary_link && $card->secondary_link !== '#' ) : ?>
                                                                <a href="<?php echo esc_url( $card->secondary_link ); ?>" target="_blank" class="secondary-button">
                                                                    <span <?php echo $panel_heading_back ?? ''; ?>>
                                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 95 95"><g fill="#fff" stroke-width="0"><path d="M29.1 14c-5.6 1.2-11 5.4-13.6 10.6-1.6 3.3-2 6.4-2.3 19.5-.5 20.4.6 25.9 6.2 31.5 5.2 5.3 10.2 6.4 28.1 6.4s22.9-1.1 28.1-6.4c5.3-5.2 6.4-10.2 6.4-28.1 0-16.2-1.2-22.5-5.1-27-5.3-6-8.5-6.9-26.9-7.1-9.1-.2-18.5.1-20.9.6m39 9.3c4.9 3.3 5.4 5.6 5.4 24.6 0 25.5-.6 26.1-26 26.1-24.4 0-25.7-1.1-26.3-21.7-.6-18.2.2-23.5 3.9-27.2s5-3.9 24.4-3.7c13.4.1 16.4.4 18.6 1.9"/><path d="M35 31c-1.2.7-1.6 3.9-1.8 15.9-.4 15.7.3 19.1 3.8 19.1 2.5 0 28-14.8 28.7-16.7.3-.8-.3-2.3-1.3-3.3-1.8-1.8-26.2-16-27.4-16-.3 0-1.2.5-2 1"/></g></svg>
                                                                        <?php echo esc_html( $card->secondary_text ?: 'Slideshow' ); ?>
                                                                    </span>
                                                                </a>
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