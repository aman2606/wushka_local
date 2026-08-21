<?php

/*****
 * 
 * Template Name: Educational Resources Listing
 */

?>


<?php
get_header();

$taxs = get_object_taxonomies('educational_resource');

$filterLabels = [
    'theme_and_event' => 'Themes and Events',
    'subjects' => 'Subjects',
    'grade' => 'Grades'
];

$allCats = get_terms($taxs, array(
    'hide_empty' => false,
));

$filters = [];

foreach ($taxs as $t) {

    $filters[$t] =  array_filter($allCats, function ($v) use ($t) {
        return $t == $v->taxonomy;
    });
}




$query = new WP_Query(array(
    'post_type' => 'educational_resource',
    'post_status' => 'publish',
    'meta_query' => [
        'relation' => 'OR',
        [
            'key' => 'show_in_listing_page',
            'compare' => 'NOT EXISTS',
        ],[
            'key' => 'show_in_listing_page',
            'compare' => '!=',
            'value' => 0
        ]
        
        //[]

    ]
));

?>

<div class="educational-resources-liting">
    <div id="hero">
        <div class="container" style="padding-top:127px;padding-bottom: 20px;">
            <div class="row">
                <div class="col-md-6 col-sm-6">
                    <h1 style="padding: 0 0 10px;"><?php the_title(); ?></h1>
                    <p><?php the_content(); ?></p>

                </div>
                <div class="col-md-6 col-sm-6">
                    <img src="<?php echo get_the_post_thumbnail_url(); ?>" class="img-responsive" alt="">
                </div>
            </div>
        </div>
    </div>

    <div class="wrap" style="background:#fff;padding-top:40px;">
        <div class="container">

            <div class="row">

                <div class="col-md-3 col-sm-4">

                    <form class="controls" id="Filters">
                        <!-- We can add an unlimited number of "filter groups" using the following format: -->

                        <div class="filter-items">
                            <div class="filter-heading clearfix">
                                <h2 class="pull-left">Filter By:</h2>
                                <input type="text" id="title-filter" class="form-control" placeholder="Search Educational Resources" />
                                <a class="pull-left filter-reset" href="javascript:void(0)">Clear Filters</a>
                            </div>
                        </div>

                        <?php if (!empty($filters)) {

                            foreach ($filters as $key => $filter) { ?>

                                <div class="filter-items">
                                    <div class="filter-heading clearfix">
                                        <h2 class="pull-left"><?php echo isset($filterLabels[$key]) ? $filterLabels[$key] : $key; ?></h2>
                                        <a class="pull-right clear" href="">Clear</a>
                                    </div>
                                    <div class="filter-input">
                                        <?php foreach ($filter as $f) { ?>
                                            <div class="filter-checkbox">
                                                <input value=".<?php echo $f->slug ?>" type="checkbox" id="<?php echo $f->slug ?>" style="margin-right:4px;">
                                                <label for="<?php echo $f->slug ?>"><?php echo $f->name ?> (<?php echo $f->count; ?>)</label>
                                            </div>
                                        <?php } ?>

                                    </div>
                                </div>
                        <?php }
                        } ?>

                        <!-- <button id="Reset">Clear Filters</button> -->
                    </form>

                </div>

                <div class="col-md-9 col-sm-8">
                    <div id="Container" class="cont row">
                        <div class="fail-message"><span>No items were found matching the selected filters</span></div>

                        <?php

                        while ($query->have_posts()) :
                            $query->the_post();
                            $postId = get_the_ID();
                            $terms = wp_get_post_terms($postId, $taxs, ['fields' => 'slugs']);

                            $filterCats = implode(" ", $terms);

                            $excerpt =   get_the_excerpt();
                        ?>

                            <div class="col-lg-4 col-md-6 col-sm-6 col-12 mix <?php echo $filterCats; ?>">
                                <div class="panel panel-default">
                                    <div class="panel-heading card_head"> <a href="<?php echo get_permalink(); ?>">
                                            <img src="<?php echo get_the_post_thumbnail_url() ?>">
                                        </a></div>
                                    <div class="panel-body card_body">

                                        <h3 class="card_title"><a href="<?php echo get_permalink(); ?>"><?php the_title(); ?></a></h3>

                                        <p class="card_text">

                                            <?php if (!empty($excerpt)) {
                                                echo substr($excerpt, 0, 80) . ' [...]';
                                            } else {
                                                echo substr(strip_tags(get_the_content()), 0, 80) . ' [...]';
                                            } ?>

                                        </p>
                                        <a href="<?php echo get_permalink(); ?>" class="ed_download">Download Now</a>
                                    </div>
                                </div>
                            </div>

                        <?php
                        endwhile;
                        wp_reset_postdata();

                        ?>
                    </div>

                </div>

            </div>
        </div>
    </div><!-- end of wrap -->
</div>
<script src='https://cdn.jsdelivr.net/jquery.mixitup/latest/jquery.mixitup.min.js'></script>
<script>
    // To keep our code clean and modular, all custom functionality will be contained inside a single object literal called "checkboxFilter".

    var checkboxFilter = {

        // Declare any variables we will need as properties of the object

        $filters: null,
        $reset: null,
        groups: [],
        outputArray: [],
        outputString: '',

        // The "init" method will run on document ready and cache any jQuery objects we will need.

        init: function() {
            var self = this; // As a best practice, in each method we will asign "this" to the variable "self" so that it remains scope-agnostic. We will use it to refer to the parent "checkboxFilter" object so that we can share methods and properties between all parts of the object.

            self.$filters = $('#Filters');
            self.$reset = $('.filter-reset');
            self.$container = $('#Container');

            self.$filters.find('.filter-items').each(function() {
                self.groups.push({
                    $inputs: $(this).find('input'),
                    active: [],
                    tracker: false
                });
            });

            self.bindHandlers();
        },

        // The "bindHandlers" method will listen for whenever a form value changes. 

        bindHandlers: function() {
            var self = this;

            self.$filters.on('change', function() {
                self.parseFilters();
            });

            self.$reset.on('click', function(e) {
                e.preventDefault();
                self.$filters[0].reset();
                self.parseFilters();
            });
        },

        // The parseFilters method checks which filters are active in each group:

        parseFilters: function() {
            var self = this;

            // loop through each filter group and add active filters to arrays

            for (var i = 0, group; group = self.groups[i]; i++) {
                group.active = []; // reset arrays
                group.$inputs.each(function() {
                    $(this).is(':checked') && group.active.push(this.value);
                });
                group.active.length && (group.tracker = 0);
            }

            self.concatenate();
        },

        // The "concatenate" method will crawl through each group, concatenating filters as desired:

        concatenate: function() {
            var self = this,
                cache = '',
                crawled = false,
                checkTrackers = function() {
                    var done = 0;

                    for (var i = 0, group; group = self.groups[i]; i++) {
                        (group.tracker === false) && done++;
                    }

                    return (done < self.groups.length);
                },
                crawl = function() {
                    for (var i = 0, group; group = self.groups[i]; i++) {
                        group.active[group.tracker] && (cache += group.active[group.tracker]);

                        if (i === self.groups.length - 1) {
                            self.outputArray.push(cache);
                            cache = '';
                            updateTrackers();
                        }
                    }
                },
                updateTrackers = function() {
                    for (var i = self.groups.length - 1; i > -1; i--) {
                        var group = self.groups[i];

                        if (group.active[group.tracker + 1]) {
                            group.tracker++;
                            break;
                        } else if (i > 0) {
                            group.tracker && (group.tracker = 0);
                        } else {
                            crawled = true;
                        }
                    }
                };

            self.outputArray = []; // reset output array

            do {
                crawl();
            }
            while (!crawled && checkTrackers());

            self.outputString = self.outputArray.join();

            // If the output string is empty, show all rather than none:

            !self.outputString.length && (self.outputString = 'all');

            //console.log(self.outputString); 

            // ^ we can check the console here to take a look at the filter string that is produced

            // Send the output string to MixItUp via the 'filter' method:

            if (self.$container.mixItUp('isLoaded')) {
                self.$container.mixItUp('filter', self.outputString);
            }
        }
    };

    // On document ready, initialise our code.

    jQuery(function($) {

        // Initialize checkboxFilter code

        checkboxFilter.init();

        // Instantiate MixItUp

        $('#Container').mixItUp({
            controls: {
                enable: false // we won't be needing these
            },
            animation: {
                easing: 'cubic-bezier(0.86, 0, 0.07, 1)',
                duration: 600
            }
        });

        filterTitle();

        $('.filter-items .clear').click(function(e) {
            e.preventDefault();
            $(this).closest('.filter-items').find('input[type=checkbox]:checked').trigger('click');
        });
    });

    function filterTitle() {
        var inputText;
        var $matching = $();

        // Delay function
        var delay = (function() {
            var timer = 0;
            return function(callback, ms) {
                clearTimeout(timer);
                timer = setTimeout(callback, ms);
            };
        })();

        $("#title-filter").keyup(function() {
            // Delay function invoked to make sure user stopped typing
            delay(function() {
                inputText = $("#title-filter").val().toLowerCase();

                // Check to see if input field is empty
                if (inputText.length >= 3) {
                    $(".mix").each(function() {
                        $this = $("this");
                        //console.log(inputText);
                        // add item to be filtered out if input text matches items inside the title
                        if ($(this).find(".card_title").text().toLowerCase().match(inputText)) {
                            $matching = $matching.add(this);
                        } else {
                            // removes any previously matched item
                            $matching = $matching.not(this);
                        }
                    });
                    $("#Container").mixItUp("filter", $matching);
                } else {
                    // resets the filter to show all item if input is empty
                    $("#Container").mixItUp("filter", "all");
                }
            }, 200);
        });
    }
</script>
<?php get_footer(); ?>