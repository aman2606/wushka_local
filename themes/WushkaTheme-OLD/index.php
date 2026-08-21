<?php get_header(); ?>
    <style type="text/css">
        .separator {
            width: auto;
            height: 1px;
            background: #CCC;
            margin-top: 10px;
            margin-bottom: 10px;
        }

        article:last-child .separator {
            display: none;
        }

        h2 a:hover {
            text-decoration: underline;
        }

    </style>
    <div class="container mt30">
    <div class="row">
        <div class="col-xs-12 col-md-10 col-md-offset-1">
            <div class="row">
                <div class="col-xs-12">
                    <h2 class="site-heading strong underline mb15">Stories</h2>
                </div>
                <div class="col-xs-12">
                    <?php if( is_user_logged_in() ) { ?>
                        <div class="panel mb45">
                            <div class="panel-header"><h3 class="h3 m15 mb0">Tell us your Wushka story</h3></div>
                            <div class="panel-body">
                                <?php gravity_form(5, FALSE, FALSE, FALSE, '', TRUE, 12); ?>
                            </div>
                        </div>
                    <?php } ?>
                </div>
                <div class="col-xs-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Our Latest Wushka Stories
                        </div>
                        <div class="panel-body">
                            <?php if( have_posts() ) : while( have_posts() ) : the_post(); ?>
                                <article class="story-wrapper">
                                    <div class="col-xs-12 no-padding">
                                        <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                                    </div>

                                    <div class="story-excerpt">
                                        <?php the_excerpt(); ?>
                                    </div>

                                    <div class="text-right mb15 mt5">
                                        <a href="<?php the_permalink(); ?>" rel="bookmark"
                                           title="<?php the_title_attribute(); ?>" class="text-link"> &rarr; Read
                                            More</a>
                                    </div>

                                    <div class="separator"></div>
                                </article>
                            <?php endwhile;
                            else: ?>
                                <?php _e('Sorry, no posts matched your criteria.', 'textdomain'); ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-md-3 hidden">
                <?php include("share-story-box.php") ?>
            </div>
        </div>
    </div>

<?php include 'dashboard_options.php' ?>
<?php get_footer(); ?>