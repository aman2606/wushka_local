<?php get_template_part('template-parts/content', 'notice');    ?>

<script>
    var jobTitles = {
        "Primary School": [
            "Head of Year",
            "Head of Subject/Curriculum",
            "Teacher/Lecturer",
            "Principal/Assistant Principal",
            "Art Technician",
            "Laboratory Tech",
            "Librarian",
            "Other School Staff",
            "School Support Staff i.e. Teaching Aid, Facilities Manager, Technician",
            "Owner/Director",
            "Business Manager/Admin/Procurement",
            "Home School Educator",
            "Tutor",
            "ICT & Technology",
            "Other"
        ],
        "Secondary School": [
            "Head of Year",
            "Head of Subject/Curriculum",
            "Teacher/Lecturer",
            "Principal/Assistant Principal",
            "Art Technician",
            "Laboratory Tech",
            "Librarian",
            "Other School Staff",
            "School Support Staff i.e. Teaching Aid, Facilities Manager, Technician",
            "Owner/Director",
            "Home School Educator",
            "Tutor",
            "ICT & Technology",
            "Other"
        ],
        "Combined (Primary/Secondary)": [
            "Head of Year",
            "Head of Subject/Curriculum",
            "Teacher/Lecturer",
            "Principal/Assistant Principal",
            "Art Technician",
            "Laboratory Tech",
            "Librarian",
            "Other School Staff",
            "School Support Staff i.e. Teaching Aid, Facilities Manager, Technician",
            "Owner/Director",
            "Home School Educator",
            "Tutor",
            "ICT & Technology",
            "Other"
        ],
        "University/College (Tertiary)": [
            "Head of Year",
            "Head of Subject/Curriculum",
            "Teacher/Lecturer",
            "Principal/Assistant Principal",
            "Art Technician",
            "Laboratory Tech",
            "Librarian",
            "Other School Staff",
            "School Support Staff i.e. Teaching Aid, Facilities Manager, Technician",
            "Owner/Director",
            "Home School Educator",
            "Tutor",
            "ICT & Technology",
            "Other"
        ],
        "Early Childhood": [
            "Owner/Director",
            "Business Manager/Admin/Procurement",
            "Head Office Operations / Staff",
            "Admin Support Staff",
            "Out of School Hours Care",
            "Early Childhood Educator/Teacher",
            "ICT & Technology",
            "Other"
        ],
        "Afterschool Care": [
            "Owner/Director",
            "Business Manager/Admin/Procurement",
            "Head Office Operations / Staff",
            "Admin Support Staff",
            "Out of School Hours Care",
            "Early Childhood Educator/Teacher",
            "ICT & Technology",
            "Other"
        ],
        "Special Education": [
            "Head of Year",
            "Head of Subject/Curriculum",
            "Teacher/Lecturer",
            "Principal/Assistant Principal",
            "Art Technician",
            "Laboratory Tech",
            "Librarian",
            "Other School Staff",
            "School Support Staff i.e. Teaching Aid, Facilities Manager, Technician",
            "Owner/Director",
            "Business Manager/Admin/Procurement",
            "Head Office Operations / Staff",
            "Admin Support Staff",
            "Out of School Hours Care",
            "Early Childhood Educator/Teacher",
            "Home School Educator",
            "Tutor",
            "ICT & Technology",
            "Other"
        ],
        "Parent": [
            "Parent"
        ],
        "Other": [
            "Owner/Director",
            "Business Manager/Admin/Procurement",
            "Head Office Operations / Staff",
            "Admin Support Staff",
            "Out of School Hours Care",
            "Early Childhood Educator/Teacher",
            "Home School Educator",
            "Tutor",
            "ICT & Technology",
            "Other"
        ]
    };
    // Create a modal button
    jQuery(document).ready(function() {
	jQuery(".click-free-trial a").attr("data-toggle", "modal").attr("data-target", "#wk-form-modal");
        jQuery(document).on('change', '#education_sector', function() {

            var jobTitleSelect = jQuery("#Cat_Title__c");

            jobTitleSelect.html('');

            var jt = jQuery(this).val();

            if (jQuery(this).val() != 'Parent') {

                var jobTitleHtml = '<option value="" selected> -- Select an option -- </option>';

            }


            if (jQuery(this).val() != "") {


                if (jobTitles[jt]) {

                    for (var jobTitle of jobTitles[jt]) {

                        jobTitleHtml += `<option value="${jobTitle}">${jobTitle}</option>`;

                    }
                }

            }

            jobTitleSelect.html(jobTitleHtml);

            if (jobTitleSelect.val() == 'Parent') {

                jobTitleSelect.focus();

            }
        });

        if (location.href.includes('/#trial-request')) {

            jQuery("#wk-form-modal").modal('show');

        }
        if (location.href.includes('/trial')) {

           // jQuery('.notice-container').hide();

            jQuery("#wk-form-modal").modal('show');
            jQuery('body').addClass('home');

        }

    });
</script>
<?php
$extension = pathinfo($_SERVER['SERVER_NAME'], PATHINFO_EXTENSION);
if ($extension == 'nz') {
?>
    <div id="trial-modal-parent">
        <div class="modal fade gform-modal" id="wk-form-modal" role="dialog" aria-labelledby="wk-form-modal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <?php echo do_shortcode('[gravityform id="1" title="true" description="false" ajax=true]'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
} else {
?>
    <div id="trial-modal-parent">
        <div class="modal fade" tabindex="-1" id="wk-form-modal" role="dialog" aria-labelledby="wk-form-modal" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true" style="font-size: 3.5rem;">&times;</span>
                        </button>
                        <p class="modal-copy__title" style="margin-bottom: 0 !important;">Start your 30 day free Wushka trial today!</p>
                    </div>
                    <div class="modal-body">
                        <?php /* echo do_shortcode('[gravityform id="1" title="true" description="false"]'); */ ?>

                        <p class="text-left contact-form-note">
                            You’re just a few steps away from 30 days of free Wushka in your school! <br />
                            Simply complete the form below, then check your inbox for next steps. <br />
                            Already a Wushka customer? <a href="<?= site_url(); ?>/login/" target="_blank">Login here</a>.
                        </p>

                    <form action="https://webto.salesforce.com/servlet/servlet.WebToCase?encoding=UTF-8" class="sf-form" method="POST">
                        <input type="hidden" name="orgid" value="00D90000000Ixox">
                        <input type="hidden" name="debug" value="0" disabled>
                        <input type="hidden" name="debugEmail" value="jlim@modernstar.com" disabled>
                        <input type="hidden" name="recordType" id="recordType" value="01290000000nZ26">

                        <p class="tip">*Required Fields</p>

                        <div class="row">
                            <div class="col-md-6 p-l-0">
                                <label for="00N90000008kFT1">First Name<span class="mandatory">*</span></label>
                                <input id="00N90000008kFT1" maxlength="30" name="00N90000008kFT1" size="20" type="text" required><br>
                            </div>

                            <div class="col-md-6 p-r-0">
                                <label for="00N90000008kFTB">Last Name<span class="mandatory">*</span></label>
                                <input id="00N90000008kFTB" maxlength="50" name="00N90000008kFTB" size="20" type="text" required><br>
                            </div>
                        </div>

                        <div class="row">
                           <div class="col-md-6 p-l-0">
                                <label for="education_sector">Education Sector *</label>
                                <select name="00N9000000EKNtD" id="00N9000000EKNtD" class="form-control" required>
                                    <option value="" selected> -- None -- </option>
                                    <option value="Primary School Teacher">Primary School Teacher</option>
                                    <option value="Secondary School Teacher">Secondary School Teacher</option>
                                    <option value="Principal / Assistant Principal">Principal / Assistant Principal</option>
                                    <option value="Other School Staff">Other School Staff</option>
                                    <option value="Tutor">Tutor</option>
                                    <option value="Parent">Parent</option>
                                    <option value="Student">Student</option>
                                </select>
                                <br>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 p-l-0">
                                <label for="00N90000008kFXT">Email Address<span class="mandatory">*</span></label>
                                <input id="00N90000008kFXT" maxlength="80" name="00N90000008kFXT" size="20" type="text" required><br>
                            </div>
                            <div class="col-md-6 p-r-0">
                                <label for="00N90000008kFXO">Mobile Number<span class="mandatory">*</span></label>
                                <input id="00N90000008kFXO" maxlength="40" name="00N90000008kFXO" size="20" type="text" required><br>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 p-l-0">
                                <label for="00N90000008kFY7">School or centre name<span class="mandatory">*</span></label>
                                <input id="00N90000008kFY7" maxlength="100" name="00N90000008kFY7" size="20" type="text" required=""><br>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 p-l-0">
                                <label for="00N90000008kFXi">Suburb<span class="mandatory">*</span></label>
                                <input id="00N90000008kFXi" maxlength="100" name="00N90000008kFXi" size="20" type="text" required=""><br>
                            </div>
                            <div class="col-md-6 p-l-0">
                                <label for="00N90000008kFXn">State<span class="mandatory">*</span></label>
                                <select id="00N90000008kFXn" name="00N90000008kFXn" class="form-control" required="">
                                    <option value="">--None--</option>
                                    <option value="NSW">NSW</option>
                                    <option value="VIC">VIC</option>
                                    <option value="QLD">QLD</option>
                                    <option value="ACT">ACT</option>
                                    <option value="WA">WA</option>
                                    <option value="SA">SA</option>
                                    <option value="NT">NT</option>
                                    <option value="TAS">TAS</option>
                                </select>
                            </div>
                            <div class="col-md-6 p-l-0">
                                <label for="00N90000008kzAU">Postcode<span class="mandatory">*</span></label>
                                <input id="00N90000008kzAU" maxlength="20" name="00N90000008kzAU" size="20" type="text" required=""><br>
                            </div>
                        </div>

                        <div class="row">
                           <div class="col-md-6 p-l-0">
                                <label for="00NRF000000uIiD">So we can tailor your trial, what aspect of literacy does your school need support with?</label>
                                <select id="00NRF000000uIiD" name="00NRF000000uIiD" title="Apects of Literacy" multiple required>
                                    <option value="K -2 Reading">K -2 Reading</option>
                                    <option value="3 - 6 Reading">3 - 6 Reading</option>
                                    <option value="Phonics">Phonics</option>
                                    <option value="Decodable Books">Decodable Books</option>
                                    <option value="Home Reading">Home Reading</option>
                                    <option value="Comprehension">Comprehension</option>
                                    <option value="Assessment">Assessment</option>
                                    <option value="Intervention">Intervention</option>
                                    <option value="Engagement in Reading">Engagement in Reading</option>
                                    <option value="Variety of Reading Material">Variety of Reading Material</option>
                                    <option value="Fluent Readers">Fluent Readers</option>
                                    <option value="Physical Books">Physical Books</option>
                                    <option value="Digital Books">Digital Books</option>
                                </select><br>
                           </div>
                        </div>

                        <select id="00N90000008kFXs"  name="00N90000008kFXs" title="Country" hidden>
                            <?php
                            $country = substr(site_url(), -2);
                            $country = strtoupper($country);
                            ?>
                            <option value="<?php echo $country; ?>" id="<?php echo $country; ?>"><?php echo $country; ?>
                            </option>
                        </select>

                        <input type="hidden" id="subject" name="subject" value="Wushka Trial Request from Wushka Site" class="no-clear">
                        <input type="hidden" id="00N9000000FAizC" name="00N9000000FAizC" title="Modern Star Division" class="no-clear" value="Education">
                        <!-- From website -->
                        <input id="00N90000006jGwn" name="00N90000006jGwn" type="hidden" class="no-clear" value="<?php echo site_url(); ?>">

                        <!-- Account -->
                        <input id="account" name="account" type="hidden" class="no-clear" value="unknown">
                        <input type="hidden" id="reason" name="reason" value="Digital Learning - Wushka" class="no-clear">
                        <input type="hidden" id="external" name="external" value="1">

                        <div class="form-check trial_term_container col-md-12">
                            <input type="checkbox" class="form-check-input" name='trial_terms' id='trial_terms' style="min-height: 23px;width: 23px;order:0;" required>
                            <label class="form-check-label" for="trial_terms" style="margin-left: 13px;">I agree to Modern Teaching Aids <a href="<?php echo get_site_url() ?>/school-terms-and-conditions/" target="_blank">Terms and
                                    Conditions</a> and acknowledge I have read the <a href="<?php echo get_site_url() ?>/privacy" target="_blank">Privacy
                                    Policy</a>.</label>
                        </div>

                        <div class="row submit-container">
                            <button type="submit" id="form-submit" class="contact-us-btn btn btn-primary spacious m-t-md">Submit</button>

                            <div id="ajax-loader-placeholder" style="display: none;text-align:center;">
                                <i class="fa fa-spinner fa-spin fa-2x fa-fw"></i>
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>
                    </form>

                    </div>
                    <div class="modal-footer text-left">
                        <small>
                            <strong>Please note:</strong> Wushka is currently only available for schools, early childhood and OSHC centres. If you’re a parent or tutor, please watch this space!
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>

<div class="modal fade" id="form-submission-modal" tabindex="-1" role="dialog" aria-labelledby="form-submission-modal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body text-center">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>

                <div class="row">
                    <div class="col-sm-8 col-sm-offset-2">
                        <img class="benefit-block__icon m20" src="//d4iqe7beda780.cloudfront.net/resources/site/mtaau/campaigns/empowered/icon-check.jpg" alt="">

                        <p class="modal-copy__title">Thanks for confirming your details.</p>
                        <p>Our team will be in touch very soon.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php /*----- Ereader Modal Window-----*/ ?>
<div class="modal ereader-modal fade" id="ereader-modal" tabindex="-1" role="dialog" aria-labelledby="ereader-modal" aria-hidden="true">
    <div class="container-fluid">
        <div class="row" data-dismiss="modal">
            <div class="col-xs-12">
                <div class="ereader-wrapper">
                    <div class="ereader-wrapper-inner">
                        <button type="button" class="ereader-close" data-dismiss="modal" aria-label="Close">
                            <span class="ereader-close-icon" aria-hidden="true">&times;</span>
                        </button>
                        <div id="iframe-wrapper"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php /*----- END READER MODAL-----*/ ?>

</div> <!-- End of .wrapper-main -->
<?php $s_uri = $_SERVER['REQUEST_URI']; ?>
<?php if (($s_uri == '/stories/' && !is_user_logged_in()) || ((is_home() || !is_user_logged_in()) && $s_uri != '/stories/')) { ?>
    <aside class="footer-nav padding-y">
        <header>
            <h2 class="sr-only">Sitemap</h2>
        </header>
        <div class="container">
            <div class="row">
                <div class="col-xs-9 col-xs-offset-0 col-sm-3 col-sm-offset-0">
                    <?php wp_nav_menu(array('theme_location' => 'footer-nav-1')); ?>
                </div>
                <div class="col-xs-9 col-xs-offset-0 col-sm-3 col-sm-offset-0">
                    <?php wp_nav_menu(array('theme_location' => 'footer-nav-2')); ?>
                </div>
                <div class="col-xs-9 col-xs-offset-0 col-sm-3 col-sm-offset-0">
                    <?php wp_nav_menu(array('theme_location' => 'footer-nav-3')); ?>
                </div>
                <div class="col-xs-9 col-xs-offset-0 col-sm-3 col-sm-offset-0">
                    <?php wp_nav_menu(array('theme_location' => 'footer-nav-4')); ?>
                </div>
                <?php /* ?>
            <div class="col-xs-12 col-xs-offset-0 col-sm-12 col-sm-offset-0">
                <div class="footer-disclaimer">
                    <?php if ( is_page_template( array( 'pricing.php', 'tpl-how-it-works.php' ) ) ) { ?>
                    <i>*60 further decodable ebooks to come in 2022</i>
                    <?php } ?>
                    <?php if ( is_front_page() ) { ?>
                    <i>*60 more decodable readers to follow in 2022</i>
                    <?php } ?>
                </div>
            </div>
            <?php   */  ?>
            </div>
        </div>
    </aside>
    <script>
        if ($("#menu-footer-nav-social a:eq(0)").attr('title') == "Get Social") {
            $("#menu-footer-nav-social a:eq(0)").removeAttr('title');
        }

        $("#menu-footer-nav-social a").children().each(function(index) {
            var social_links = ['instagram', 'facebook', 'youtube'];
            for (var i = 0; i < social_links.length; i++) {
                if ($(this).hasClass('social-' + social_links[i])) {
                    $(this).closest('a').attr('title', social_links[i] + ' (Opens in new window)');
                }
            }
        });
        var clickHereRemoved = jQuery('#hidden-select-school .js-no-school').text().replace(' Click here.', '');
        $('#hidden-select-school .js-no-school').text(clickHereRemoved);
        //$('#recordType').attr('aria-label', 'MTA Website Request');
    </script>
    <footer class="footer-minimal padding-y-lg">
        <div class="container">
            <div class="footer-flex">
                <div class="brands">
                   <a href="<?php echo home_url() ?>" title="Wushka" class="flex">
			<img alt="wushka logo" src="https://cdn1.wushka.com.au/public/2024/11/15165117/wushka_mta_header_transparent.webp" style="position: relative;max-width: 700px;" />
		   </a>
                </div>
            </div>
            <div class="footer-flex">
                <div class="footer-copyright">© <?php echo date('Y'); ?> Wushka. All rights reserved.</div>
            </div>
        </div>
    </footer>
<?php } ?>

<?php
/* =====================
*
* Stray tag as per W3C 
*
*======================= 
</div>
 */
?>
<!-- <div id="scrolltotop"><a href="#"><i class="icon-chevron-up"></i><br /><?php _e('Top', 'lessonzone'); ?></a></div> -->
<?php wp_footer(); ?>

<script>
    window.addEventListener("DOMContentLoaded", function() {
        document.querySelector(".hamburger.hamburger--squeeze").onclick = function squeezeHamburger() {
            this.classList.toggle("is-active");
            if (document.body.classList.contains("scroll-lock")) {
                setTimeout(function() {
                    document.body.classList.remove("scroll-lock")
                }, 450);
            } else {
                document.body.classList.add("scroll-lock")

            }

        };
    });


    /*! enquire.js v2.1.2 - Awesome Media Queries in JavaScript Copyright (c) 2014 Nick Williams - http://wicky.nillia.ms/enquire.js License: MIT (http://www.opensource.org/licenses/mit-license.php) */
    ! function(a, b, c) {
        var d = window.matchMedia;
        "undefined" != typeof module && module.exports ? module.exports = c(d) : "function" == typeof define && define.amd ?
            define(function() {
                return b[a] = c(d)
            }) : b[a] = c(d)
    }("enquire", this, function(a) {
        "use strict";

        function b(a, b) {
            var c, d = 0,
                e = a.length;
            for (d; e > d && (c = b(a[d], d), c !== !1); d++);
        }

        function c(a) {
            return "[object Array]" === Object.prototype.toString.apply(a)
        }

        function d(a) {
            return "function" == typeof a
        }

        function e(a) {
            this.options = a, !a.deferSetup && this.setup()
        }

        function f(b, c) {
            this.query = b, this.isUnconditional = c, this.handlers = [], this.mql = a(b);
            var d = this;
            this.listener = function(a) {
                d.mql = a, d.assess()
            }, this.mql.addListener(this.listener)
        }

        function g() {
            if (!a) throw new Error("matchMedia not present, legacy browsers require a polyfill");
            this.queries = {}, this.browserIsIncapable = !a("only all").matches
        }
        return e.prototype = {
            setup: function() {
                this.options.setup && this.options.setup(), this.initialised = !0
            },
            on: function() {
                !this.initialised && this.setup(), this.options.match && this.options.match()
            },
            off: function() {
                this.options.unmatch && this.options.unmatch()
            },
            destroy: function() {
                this.options.destroy ? this.options.destroy() : this.off()
            },
            equals: function(a) {
                return this.options === a || this.options.match === a
            }
        }, f.prototype = {
            addHandler: function(a) {
                var b = new e(a);
                this.handlers.push(b), this.matches() && b.on()
            },
            removeHandler: function(a) {
                var c = this.handlers;
                b(c, function(b, d) {
                    return b.equals(a) ? (b.destroy(), !c.splice(d, 1)) : void 0
                })
            },
            matches: function() {
                return this.mql.matches || this.isUnconditional
            },
            clear: function() {
                b(this.handlers, function(a) {
                    a.destroy()
                }), this.mql.removeListener(this.listener), this.handlers.length = 0
            },
            assess: function() {
                var a = this.matches() ? "on" : "off";
                b(this.handlers, function(b) {
                    b[a]()
                })
            }
        }, g.prototype = {
            register: function(a, e, g) {
                var h = this.queries,
                    i = g && this.browserIsIncapable;
                return h[a] || (h[a] = new f(a, i)), d(e) && (e = {
                    match: e
                }), c(e) || (e = [e]), b(e, function(b) {
                    d(b) && (b = {
                        match: b
                    }), h[a].addHandler(b)
                }), this
            },
            unregister: function(a, b) {
                var c = this.queries[a];
                return c && (b ? c.removeHandler(b) : (c.clear(), delete this.queries[a])), this
            }
        }, new g
    });
    /* Simple jQuery Equal Heights - Copyright (c) 2013 Matt Banks - Dual licensed under the MIT and GPL licenses. - Uses the same license as jQuery, see: - http://docs.jquery.com/License - @version 1.5.1 */
    ! function(a) {
        a.fn.equalHeights = function() {
            var b = 0,
                c = a(this);
            return c.each(function() {
                var c = a(this).innerHeight();
                c > b && (b = c)
            }), c.css("height", b)
        }, a("[data-equal]").each(function() {
            var b = a(this),
                c = b.data("equal");
            b.find(c).equalHeights()
        })
    }(jQuery);
    jQuery(document).ready(function($) {
        // $('#dayevent').css('font-family','ProximaNovaSoft-Regular, Arial, Helvetica, sans-serif');
        /* Calendar Events */
        $('.cala_day #cal_event').removeAttr('style');
        $('.cala_day #cal_event').css("background-color", "#FFFFFF");
        jQuery('#form-alert').each(function() {
            if (jQuery(this).text() == "") {
                jQuery(this).removeClass('alert alert-danger');
            }
        });
        //Fix margin bottom when bottom dashboard visible
        // Script was here
        /* Responsive Header Menu: Move User Profile block depending on screen size - */
        $.fn.userProfileResponsive = function() {
            if (this.css('display') == 'none') {
                //console.log ('navbar-toogle hidden')
                $('.user-welcome-wrapper').insertAfter('.logo-wushka');
                $('.user-welcome-wrapper').css({
                    'float': 'left',
                    'margin-left': '15px',
                    'margin-top': '0',
                    'border-left': '1px solid rgb(210, 210, 210)',
                    'box-shadow': 'inset 1px 0 0 0 rgb(255, 255, 255)',
                    'padding-left': '15px',
                    'text-align': 'left',
                    'border-top': '0 solid transparent',
                    'padding-top': '0',
                });
                $('.tracks-wrapper').removeClass('mt0')
            } else {
                $('.user-welcome-wrapper').prependTo('.navbar-wushka .navbar-collapse');
                $('.user-welcome-wrapper').css({
                    'float': 'none',
                    'margin-left': '0',
                    'margin-top': '30px',
                    'border-left': 'none',
                    'box-shadow': 'none',
                    'padding-left': '0',
                    'text-align': 'center',
                    'border-top': '1px solid #D2D2D2',
                    'padding-top': '10px',
                });
                $('.tracks-wrapper').addClass('mt0')
            };
            // Finally, the return statement makes chaining (link many actions onto one selector) possible.
            return this;
        }
        // Call userProfileResponsive() on load:
        $('.navbar-toggle').userProfileResponsive();
        /* Video Play/Pause Button --------------------------------------------------
         // Add Play/Pause functionality:
         jQuery('video').click(function () {
         if (jQuery(this).get(0).paused) {
         jQuery(this).get(0).play();
         } else {
         jQuery(this).get(0).pause();
         }
         });
         // Align button to middle of video player:
         var videoWidth = jQuery('video').width(); //console.log(videoWidth);
         var videoHeight = jQuery('video').height(); //console.log(videoHeight);
         var positionx = videoWidth / 2; //console.log(positionx);
         var positiony = videoHeight / 2 + 20; //console.log(positiony);
         jQuery('.btn-play-pause').css({
         'left':positionx,
         'top':positiony
         });
         // Fade In/Out button on hover:
         jQuery('video').mouseenter(function () {
         jQuery('.btn-play-pause').fadeIn();
         });
         jQuery('video').mouseleave(function () {
         jQuery('.btn-play-pause.glyphicon-pause').fadeOut();
         });
         // Toggle Play/Pause icon on click
         jQuery('video').click(function(){
         jQuery('.btn-play-pause').toggleClass('glyphicon-play glyphicon-pause');
         });
         */
        <?php if (!is_page('manage-class-list')) { ?>
            $('.me-video, .me-audio').mediaelementplayer();
            /* Homepage ----------------------------------------------------------------- */
            $('.track-body-description').equalHeights();
            /* Smooth Scroll */
            $('a[href^="#"]').on('click', function(event) {
                var target = $("#tracks-heading");
                if (target.length) {
                    event.preventDefault();
                    $('html, body').animate({
                        scrollTop: target.offset().top
                    }, 1000);
                }
            });
            /* TRANSITIONS */
            /* Home Prelogin */
            $('.logo-home').velocity("fadeIn", {
                delay: 100,
                duration: 1000
            });
            /* Teacher-Parent-Student-School Dashboards */
            $('.teacher-functions-box').velocity("transition.flipXIn", {
                stagger: 100
            });
            /* Parent Manage Child List Page */
            $('.parent-functions-box').velocity("transition.flipXIn", {
                stagger: 100
            });
            $('.page-manage-child-list .child-profile').velocity("transition.flipXIn", {
                stagger: 100
            });
            /* Footer BTN Request More Information Lightbox */
            $('.btn-request-more-info').children('a').remove();
            $('.btn-request-more-info').append(
                '<button type="button" class="text-link" data-toggle="modal" data-target="#wk-form-modal">Free School Access</button>'
            );
        <?php } ?>
        $(".footer-nav, .footer-minimal").wrapAll("<div id='sticky-footer-wrapper'></div>");
        /* Contact Form Modal */
        $('#field_1_9').appendTo('.gform_footer.top_label');
        $('.gform_wrapper .gsection').css({
            'border-bottom': 'none',
            'border-top': '1px solid #CCC',
            'padding-top': '15px'
        });
        /* Carousel Settings -------------------------------------------------------- */
        $('.carousel-home').carousel({
            pause: true,
            interval: false
        });
        /* Woocommerce -------------------------------------------------------------- */
        $('.button.save-address').prev().removeAttr("style");
        $('.sticky-footer').insertAfter('.wrapper-main');
        // Remove My Account Links
        var myAccountLinks = $('.my-account table a:not(.button)');
        myAccountLinks.removeAttr("href");
        myAccountLinks.css({
            'font-size': '16px',
            'font-size': '1.6rem',
            'color': '#444'
        });
        var orderDetailsLinks = $('.product-name a:not(.button)');
        orderDetailsLinks.removeAttr("href");
        /* School Dashboard --------------------------------------------------------- */
        // Equal Teacher Dashboard's boxes height
        var teacherFunctionBoxes = $('.teacher-functions-box');
        var teacherFunctionContent = $('.teacher-function.content');
        var teacherFunctionHeading = $('.teacher-function.heading-wrapper');
        var childProfileOption = $('.child-profile-options');
        teacherFunctionBoxes.equalHeights();
        teacherFunctionContent.equalHeights();
        teacherFunctionHeading.equalHeights();
        childProfileOption.equalHeights();
        // Add disabled layer
        $.fn.grayed = function() {
            this.each(function() {
                $(this).prepend('<div class="grayed"></div>');
                $(this).css('opacity', '.7');
                $(this).removeClass('grow');
            });
        };
        $('#free-licenses .teacher-function.wrapper').grayed();
        $('#rewards .teacher-function.wrapper').grayed();
        /* General ------------------------------------------------------------------ */
        // Remove empty P tags */
        $('p:empty').remove();
        // Remove empty P tags */
        $('p').each(function() {
            var $this = $(this);
            if ($this.html().replace(/\s|&nbsp;/g, '').length == 0)
                $this.remove();
        });
        /* LIGHTBOX PLUGIN - Features Pages (for Teachers / Parents ) --------------- */
        $(document).delegate('*[data-toggle="lightbox"]', 'click', function(event) {
            event.preventDefault();
            $(this).ekkoLightbox();
        });
        /* FAQ - Remove margin-bottom to dropdown P tags ---------------------------- */
        $('.feature-set .panel-link').parent('p').css('margin', '0')
        /* FAQ & Useful Information | Accordion Arrows ------------------------------ */
        $('.faq-panel-heading').click(function() {
            // Apply closed chevron to ALL panels
            $('.glyphicon').removeClass('chevron-opened');
            $('.glyphicon').addClass('chevron-closed');
            $('.glyphicon.glyphicon-circle-question-mark.x05').removeClass('chevron-closed');
            // Toggle chevron ONLY of clicked panel
            $(this).find('.glyphicon').toggleClass('chevron-opened chevron-closed');
            // but if the panel was open when clicked, apply closed chevron
            $('.in').siblings('.panel-link').find('.glyphicon').removeClass('chevron-opened');
            $('.in').siblings('.panel-link').find('.glyphicon').addClass('chevron-closed');
            $('.glyphicon.glyphicon-circle-question-mark.x05').removeClass('chevron-closed');
        });
        $('.glyphicon.glyphicon-circle-question-mark.x05').removeClass('chevron-closed');
        /* QUIZ */
        $('.slickQuizWrapper .button').mouseleave(function() {
            $(this).attr('style', 'background-color:#D64242 !important;');
            console.log('Quiz btn out')
        });
        $('.slickQuizWrapper .button').mouseenter(function() {
            $(this).attr('style', 'background-color:#D32F2F !important;');
            console.log('Quiz btn on')
        });
        $('#quizbackbtn').appendTo('.quizResultsCopy');
        $('.button.startQuiz').click(function() {
            $(this).parent().hide();
            $('.button:contains("Skip Quiz")').parent().hide();
        });
        /* Teacher Dashboard / Student Dashboard / Parent Dashboard ----------------- */
        /* $( ".navbar-toggle" ).click(function() {
         $(".navbar-toggle .glyphicon").toggleClass("glyphicon-collapse-top glyphicon-expand");
         }); */
        $('.nav-item').on('click', function() {
            $('.nav-item.active').removeClass('active');
            $(this).addClass('active');
        });
        /* Bookshelves -------------------------------------------------------------- */
        /* Wrap bookshelves in rows of 3 for mobile */
        var bookShelfItem = jQuery(".thumb.accordion-shelf-book");
        /*
        if ($('.navbar-toggle').css('display') == 'block') {
            for (var i = 0; i < bookShelfItem.length; i += 3) {
                bookShelfItem.slice(i, i + 3).wrapAll("<div class='accordion-shelf-book-item-wrapper'></div>");
            }
        }
        */
        /* Times Read Functionality - Change colors depending on times read */
        jQuery(".times-read").each(function() {
            var timesReadText = jQuery(this).text();
            var timesReadTextInt = parseInt(timesReadText);
            if (timesReadTextInt <= 1) {
                jQuery(this).css('background-color', '#CDDC39');
            } else if (timesReadTextInt <= 2) {
                jQuery(this).css('background-color', '#8BC34A');
            } else if (timesReadTextInt <= 3) {
                jQuery(this).css('background-color', '#4CAF50');
            } else {
                jQuery(this).css('background-color', '#2E7D32');
            }
        });
        jQuery(window).resize(function() {
            // Call userProfileResponsive() on resize:
            $('.navbar-toggle').userProfileResponsive();
        });
        jQuery(window).bind("load", function() {
            /* Woocommerce Checkout Page */
            $('.payment_methods.methods label').css('cursor', 'initial');
            $('#account_password-2_field label').append('<abbr class="required" title="required">*</abbr>');
            /* Add label fields */
            $('form #billing_address_2').before('<label></label>');
            $('form #account_password-2').before('<label></label>');
            /* Manage Class Filter */
            $('#class-view_filter').appendTo('.class-view-heading');
            /* Equal height of Item Detail Page Boxes */
            $('.equalheight-subscription').equalHeights();
            $('.glyphicon-heading-text a').removeAttr("href");
            $(".hgroup h3 a").each(function() {
                $(this).removeAttr("href");
            });
            /* Manage Reading Groups */
            //jQuery('.panel-equalHeights').equalHeights();
            //jQuery('.panel-level-books .panel-body, .reading-group-menu .panel-body, .reading-group-menu .ui-droppable').equalHeights();
            /* jQuery Sticky Footer */
            //       var footerHeight = 0,
            //           footerTop = 0,
            //           $footer = $("#sticky-footer-wrapper");
            //       positionFooter();
            //
            //       function positionFooter() {
            //                footerHeight = $footer.height();
            //                footerTop = ($(window).scrollTop()+$(window).height()-footerHeight)+"px";
            //
            //               if ( ($(document.body).height()+footerHeight) < $(window).height()) {
            //                   $footer.css({
            //                        position: "absolute"
            //                   }).animate({
            //                        top: footerTop
            //                   })
            //               } else {
            //                   $footer.css({
            //                        position: "static"
            //                   })
            //               }
            //       }
            //
            //       $(window)
            //               .scroll(positionFooter)
            //               .resize(positionFooter)
            /* End of jQuery Sticky Footer */
        });
        //jQuery(window).load(function () {
        jQuery(window).on('load', function() {

            // executes when complete page is fully loaded, including all frames, objects and images
            jQuery('.page-section .col-xs-12.col-sm-6.col-md-6 > .panel').equalHeights();
            jQuery('.page-school-teacher-selection .dashboard-header h3').equalHeights();
            //Fix margin bottom when bottom dashboard visible
            if (jQuery('.navbar.navbar-fixed-bottom').length > 0) {
                jQuery('body').css('margin-bottom', '80px');
            }
            <?php if (!is_page_template('manage-class-list.php')) :  ?>
                jQuery("#sticky-filter").sticky({
                    topSpacing: 0
                });

                jQuery(".btn-sticky-filter").on("click", function() {
                    jQuery("#sticky-filter").css("z-index", "1029");
                    jQuery("#sticky-filter-sticky-wrapper").css("height", "auto");

                })
            <?php endif;  ?>

        });
        jQuery(window).on("orientationchange", function() {
            jQuery('.page-section .col-xs-12.col-sm-6.col-md-6 > .panel').equalHeights();
            console.log("Orientation changed");
        });
        jQuery(document).ajaxComplete(function() {
            /* Manage Reading Groups */
            /* jQuery('.panel-equalHeights').equalHeights(); */
            /* jQuery('.panel-level-books .panel-body, .reading-group-menu .panel-body, .reading-group-menu .ui-droppable').equalHeights(); */
            /* Woocommerce */
            $('.checkbox').insertAfter('.input-checkbox');
            /* Scroll Up to Validation Message once 'Join Today' button clicked */
            $("#place_order").click(function() {
                $("body").velocity("scroll", {
                    duration: 800,
                    delay: 500
                });
            });
            // Fix Credit Card Payment Options Layout
            $('#pin_card_0, #new').addClass("wrapMe");
            $('#pin_card_0, #new').next().addClass("wrapMe");
            $('.wrapMe').wrapAll('<div style="background-color:orange;"></div>');
            /* Equalise heights */
            //        $('.pie-chart-1').equalHeights();
            //        $('.pie-chart-2').equalHeights();
        });
        $('.carousel').carousel({
            wrap: false,
            interval: false
        })
        $(".carousel.slide").swipe({
            //Generic swipe handler for all directions
            swipeLeft: function(event, direction, distance, duration, fingerCount) {
                $(this).carousel('next');
            },
            swipeRight: function() {
                $(this).carousel('prev');
            },
            excludedElements: []
        });






    });
</script>

<script>
    /**/
    function debounce(func, wait, immediate) {
        var timeout;
        return function() {
            var context = this,
                args = arguments;
            var later = function() {
                timeout = null;
                if (!immediate) func.apply(context, args);
            };
            var callNow = immediate && !timeout;
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
            if (callNow) func.apply(context, args);
        };
    };

    var searchSchool = debounce(function(e) {

        e.preventDefault();
        e.stopPropagation();

        /*Clear account number in case they re-search*/
        jQuery("#account").val("unknown");

        var schoolName = jQuery("#school-name-input").val();
        var postCode = jQuery("#post-code-input").val();

        jQuery("#js-select-school").html("");

        if (jQuery("#school-name-input").valid() && jQuery("#post-code-input").valid()) {
            /*clear  previous if any*/
            jQuery("#js-select-school div").remove();

            jQuery(".school-select-title").removeClass("display-none");
            jQuery.ajax({
                url: "https://api.modernstar.com/schools/?name=" + schoolName + "&postal_code=" + postCode,
                contentType: "application/json",
                dataType: "json",
                success: function(result) {
                    jQuery.each(result, function(i, item) {
                        console.log(result);


                        /*Extract Contents*/
                        var accountNumber = item.id;
                        var schoolName = item.address.name;
                        var address1 = item.address.address_line_1;
                        var address2 = item.address.address_line_2;
                        var address3 = item.address.address_line_3;


                        jQuery("#js-select-school").append(
                            '<div class="school-select-container"><input id="' +
                            accountNumber +
                            '" class="school-input no-send" type="radio" value="' + item
                            .id +
                            '" name="optradio"><label class="school-input-label" data-schoolname="' +
                            item.address.name + '" data-address1="' + address1 + '" data-address2="' + address2 + '"  data-address3="' + address3 + '">' + schoolName + ' ' + address1 + ' ' +
                            address2 + ' ' + address3 +
                            '</label><span class="replacement-radio"></div>');

                    });

                    if (result.length == 0) {
                        jQuery("#js-select-school").append('<label class="error">Sorry, we couldn\'t find a school with your details.</label>');
                    }

                }
            })
        }

    }, 500);


    /*Checking form submission type. Manual vs School selected*/
    function checkInputType() {
        var isSchoolSelected = jQuery(".school-select-container input:checked").val();
        var isHiddenFieldsActive = jQuery("#hidden-details:visible").length;


        /*If no school selected or hidden details(manual entry) are not showing
         * Return false*/
        if (isSchoolSelected !== undefined || isHiddenFieldsActive > 0) {
            return true;
        }

        //alert("Returning false for school input");


        jQuery(
                "<label id='school-name-input-error' class='error' for='' style='padding-left:15px'>You need to search and select  a school or manually enter one. </label>"
            )
            .insertAfter("#hidden-select-school");
        return false;
        /*If hidden details are showing, validation should take care of it for us. */



    }

    /* Blacklist email */
    jQuery.validator.addMethod("emailRule", function(value, element) {
        let blackListDomains = ["gmail", "hotmail", "outlook", "yahoo", "aol"];
        let valid = true;
        blackListDomains.forEach((blacklistDomain) => {
            if (value.includes(blacklistDomain)) {
                valid = false;
            }
        });
        return this.optional(element) || valid;
    }, "Please use your registered school or early learning centre email address.");

    jQuery.validator.addMethod('jobTypeRule', function(value, element) {
        let invalidTypes = ["Parent", "student", "Tutor"];
        let valid = true;
        invalidTypes.forEach((invalidType) => {
            if (value.includes(invalidType)) {
                valid = false;
            }
        });
        return this.optional(element) || valid;
    }, (params, element) => {
        let value = jQuery(element).val();
        let message = `This digital learning product is only available to schools or early childhood centres. To access the program, ask your schools or centre to contact us to create an account.`;

        if (value == 'student') {
            message = `This digital learning product is only available to schools or early childhood centres. Please talk to your teacher or educator about getting an account or helping you to sign in.`;
        }

        return message;
    });


    function forminit() {

        /*Validate For School Selection*/
        jQuery("#contact-us-form").validate({
            rules: {

                "Cat_First_Name__c": {
                    required: true,
                },
                "Cat_Last_Name__c": {
                    required: true,
                },
                "Cat_Email__c": {
                    required: true,
                    email: true,
                    emailRule: true,
                },
                "Cat_Title__c": {
                    required: true,
                    jobTypeRule: true,
                },
                "Cat_Num_of_Classes__c": {
                    required: true,
                },
                "education_sector": {
                    required: true,
                },
                "Cat_Phone__c": {
                    required: true,

                },
                "school-name-input": {
                    required: true,
                    minlength: 5
                },
                "post-code-input": {
                    required: true,
                    minlength: 4,
                    maxlength: 4,
                    number: true
                },
                /*Name of School*/
                "Cat_Account_Name__c": {
                    required: true,
                },
                /*PostCode - manual*/
                "Cat_Postcode__c": {
                    required: true,
                    minlength: 4,
                    maxlength: 4,
                    number: true
                },
                /*Address 1 - manual*/
                "Cat_Address_1__c": {
                    required: true,
                },
                /*Address 2 - manual*/
                "Cat_Address_2__c": {
                    required: true,
                },
                /*Cat_Suburb__c - manual*/
                "Cat_Suburb__c": {
                    required: true,
                },
                /*Cat_State__c - manual*/
                "Cat_State__c": {
                    required: true,
                },
                'trial_terms':{
                    required:true
                }
            },
            messages: {
                "Cat_State__c": {
                    required: "Please select a state."
                },
                "trial_terms":{
                    required: "Please accept the terms and condition in order to proceed."
                }
            },
            onfocusout: function(element) {
                this.element(element);
            },
            submitHandler: function(form, event) {
                event.preventDefault();
                jQuery(form).ajaxSubmit();
                /*      jQuery('#contact-modal').modal('hide');
                jQuery('#form-submission-modal').modal('show');*/
                // jQuery(form).clearForm();
            }
        });


        /*Enter submits search*/
        jQuery("#school-name-input, #post-code-input").on("keydown", function(e) {

            if (e.which == 13) {
                e.preventDefault();
                e.stopPropagation();
                document.querySelector("#js-school-search").click();
            }
        });

        /*Form Submission*/
        jQuery("#form-submit").on("click", function(e) {
            var isFormValid = jQuery("#contact-us-form").valid();

            if (!isFormValid || !checkInputType()) {
                return false;
            }


            /*Prevent default options, we are going to use ajax and don't want page reload*/
            e.preventDefault();
            e.stopPropagation();

            /*Account needs to be unknown if the user can't select their school*/
            // if (jQuery("#00N90000008kFXY").val().length > 0) {
            //     jQuery("#account").val("unknown")
            // }
            /*lock submit on finish*/
            jQuery("#form-submit").prop("disabled", true);

            /*Disable the attributes we do not want to send into the API*/
            //jQuery(".no-send").prop("disabled", true);

            jQuery("#ajax-loader-placeholder").show();

            jQuery('#wk-form-modal').data('bs.modal').options.backdrop = 'static';


            // console.log("Serialize Data");
            // console.log(jQuery("#contact-us-form").serialize());
            // return;

            var formData = new FormData(document.getElementById("contact-us-form"));
            formData.set('action', 'salesforce_wushka_trail');

            jQuery.ajax({
                    method: "POST",
                    url: "<?php echo admin_url('admin-ajax.php') ?>",
                    data: formData,
                    processData: false,
                    contentType: false
                })
                .done(function(response) {

                    response = JSON.parse(response);



                    if (response.success) {
                        jQuery("#wk-form-modal").modal("hide");
                        jQuery('#form-submission-modal').modal('show');

                        /*Reset form*/
                        jQuery('#wk-form-modal input:not(.no-clear)').val("");
                        jQuery("#js-select-school div").remove();
                        jQuery(".school-select-title").addClass("display-none");
                        // jQuery("#contact-us-form").clearForm();

                    } else {

                        console.log(response.api_response);

                        jQuery(
                                "<label id='form-submit-error' class='error' for='form-submit'>There is something wrong with your submission. Please contact <a href='mailto:onlinelearning@teaching.com.au'>onlinelearning@teaching.com.au</a></label>"
                            )
                            .insertAfter(".submit-container");

                    }


                })
                .fail(function(data) {
                    /*Output error*/
                    jQuery(
                            "<label id='form-submit-error' class='error' for='form-submit'>There is something wrong with your submission. Please contact <a href='mailto:onlinelearning@teaching.com.au'>onlinelearning@teaching.com.au</a></label>"
                        )
                        .insertAfter(".submit-container");
                })
                .always(function() {
                    jQuery('#wk-form-modal').data('bs.modal').options.backdrop = true;
                    jQuery("#ajax-loader-placeholder").hide();
                    jQuery("#form-submit").prop("disabled", false);

                });
            /*Unlock form in case. */
            jQuery(".no-send").prop("disabled", false);

        });


        /*Show Fields for manual school detail input*/
        jQuery("#contact-us-form").on("click", ".js-no-school", function(e) {

            jQuery(".school-search").hide();

            jQuery("#js-select-school div").remove();
            jQuery(".school-select-title").addClass("display-none");

            /*Prevent # going into URL*/
            e.preventDefault();

            /*Show the hidden details*/
            jQuery("#hidden-details").removeClass("display-none");

            /*Remove disabled*/
            jQuery("#hidden-details input, #hidden-details select").prop("disabled", false);

            jQuery("[name='Cat_Account_Name__c']").val(jQuery('#school-name-input').val());
            jQuery("[name='Cat_Postcode__c']").val(jQuery('#post-code-input').val());


        });

        /*School selection behaviour*/
        jQuery("#hidden-select-school").on("click", ".school-select-container", function(e) {
            /*Get the account number that we stored in the real input*/
            var accountNumber = jQuery(this).children("input").val();
            // var schoolLabel = jQuery(this).children("label").html();
            var schoolLabel = jQuery(this).children("label").data('schoolname');

            var addr1 = jQuery(this).children("label").data('address1');
            var addr2 = jQuery(this).children("label").data('address2');
            var addr3 = jQuery(this).children("label").data('address3');

            /*Programmatically select the input*/
            jQuery(this).children("input").prop("checked", true);

            /*Add selected to container so we can remove the others*/
            jQuery(this).addClass("selected");

            /*Remove non selected school*/
            jQuery(".school-select-container").not(".selected").remove();

            /*Set hidden account number field to the account number*/
            jQuery("#account").val(schoolLabel);

            jQuery("[name='Cat_Address_1__c']").val(addr1);
            jQuery("[name='Cat_Address_2__c']").val(addr2 + " " + addr3);



        });
        jQuery("#js-school-search-clear").on("click", function(e) {
            e.preventDefault();
            e.stopPropagation();
            jQuery("#js-select-school div").remove();
            jQuery(".school-select-title").addClass("display-none");
        });

        /* Show school search option to only ceratin job title */
        jQuery('#Cat_Title__c').on('change', function() {
            let value = this.value;
            if (value == "Tutor" || value == "student" || value == "Parent" || value == "") {
                jQuery("#contact-us-form .school-search").addClass('hidden');
            } else {
                jQuery("#contact-us-form .school-search").removeClass('hidden');
            }
        });


        jQuery("#hide-manual").on("click", function(e) {
            e.preventDefault();
            e.stopPropagation();
            jQuery("#hidden-details").addClass("display-none");
            jQuery("#hidden-details input").prop("disabled", true);
            jQuery("[name='Cat_Address_1__c'],[name='Cat_Address_2__c']").prop("disabled", false);
            jQuery(".school-search").show();
        });
        jQuery("#js-school-search").on("click", searchSchool);
        jQuery(document).on("click", '#js-school-search', function(e) {
            e.preventDefault();
        });
    };
    forminit();
</script>

<?php
/* Contact us form script */
if (is_page_template('contactus.php')) {
?>
    <script>
        <?php if ($extension != 'nz') {   ?>
            var contactSearchSchool = debounce(function(e) {
                e.preventDefault();
                e.stopPropagation();

                /*Clear account number in case they re-search*/
                jQuery("#account2").val("unknown");

                var schoolName = jQuery("#school-name-input2").val();
                var postCode = jQuery("#post-code-input2").val();
                if (jQuery("#school-name-input2").valid() && jQuery("#post-code-input2").valid()) {
                    /*clear  previous if any*/
                    jQuery("#js-select-school2 div").remove();

                    jQuery(".contact-school-select-title").removeClass("hidden");
                    jQuery.ajax({
                        url: "https://api.modernstar.com/schools/?name=" + schoolName + "&postal_code=" + postCode,
                        contentType: "application/json",
                        dataType: "json",
                        success: function(result) {
                            jQuery("#js-select-school2").empty();
                            if (result.length) {
                                jQuery.each(result, function(i, item) {

                                    console.log("Search Results");
                                    console.log(result);



                                    /*Extract Contents*/
                                    var accountNumber = item.id;
                                    var schoolName = item.address.name;
                                    var address1 = item.address.address_line_1;
                                    var address2 = item.address.address_line_2;
                                    var address3 = item.address.address_line_3;


                                    jQuery("#js-select-school2").append(
                                        '<div class="contact-school-select-container"><input id="' +
                                        accountNumber +
                                        '2" class="contact-school-input contact-no-send" type="radio" value="' + item
                                        .id +
                                        '" name="optradio"><label for="' +
                                        accountNumber +
                                        '2" class="contact-school-input-label" data-schoolname="' +
                                        item.address.name + '">' + schoolName + ' ' + address1 + ' ' +
                                        address2 + ' ' + address3 +
                                        '</label><span class="contact-replacement-radio"></div>');
                                });
                            } else {
                                jQuery("#js-select-school2").append('<p>Sorry, we couldn\'t find a school with your details.</p>');
                            }



                            jQuery("#js-select-school2").removeClass('hidden');

                        }
                    })
                }

            }, 500);

        <?php   }   ?>

        /*Checking form submission type. Manual vs School selected*/
        function contact_checkInputType() {
            var isSchoolSelected = jQuery(".contact-school-select-container input:checked").val();
            var isHiddenFieldsActive = jQuery("#hidden-details2:visible").length;


            /*If no school selected or hidden details(manual entry) are not showing
             * Return false*/
            if (isSchoolSelected !== undefined || isHiddenFieldsActive > 0) {
                return true;
            }

            if (!$("#school-name-input-error2").length) {
                jQuery(
                        "<p id='school-name-input-error2' class='error' for=''>You need to search and select  a school or manually enter one. </p>"
                    )
                    .insertAfter("#hidden-select-school2");
            }


            return false;
            /*If hidden details are showing, validation should take care of it for us. */



        }

        function contact_forminit() {

            /*Validate For School Selection*/
            jQuery("#contact-us-form2").validate({
                rules: {

                    "Cat_First_Name__c": {
                        required: true,
                    },
                    "Cat_Last_Name__c": {
                        required: true,
                    },
                    "Cat_Email__c": {
                        required: true,
                        email: true,
                    },
                    "Cat_Title__c": {
                        required: true,
                    },
                    "Cat_Phone__c": {
                        required: true,

                    },
                    "school-name-input": {
                        required: true,
                        minlength: 5
                    },
                    "post-code-input": {
                        required: true,
                        minlength: 4,
                        maxlength: 4,
                        number: true
                    },
                    /*Name of School*/
                    "Cat_Account_Name__c": {
                        required: true,
                    },
                    /*PostCode - manual*/
                    "Cat_Postcode__c": {
                        required: true,
                        minlength: 4,
                        maxlength: 4,
                        number: true
                    },
                    /*Address 1 - manual*/
                    "Cat_Address_1__c": {
                        required: true,
                    },
                    /*Address 2 - manual*/
                    "Cat_Address_2__c": {
                        required: true,
                    },
                    /*Cat_Suburb__c - manual*/
                    "Cat_Suburb__c": {
                        required: true,
                    },
                    /*Cat_State__c - manual*/
                    "Cat_State__c": {
                        required: true,
                    },
                    "Description": {
                        required: true,
                    },
                },
                messages: {
                    "Cat_State__c": {
                        required: "Please select a state."
                    }
                },
                onfocusout: function(element) {
                    this.element(element);
                },
                submitHandler: function(form, event) {
                    event.preventDefault();
                    jQuery(form).ajaxSubmit();
                    /*      jQuery('#contact-modal').modal('hide');
                    jQuery('#form-submission-modal').modal('show');*/
                    // jQuery(form).clearForm();
                }
            });


            /*Enter submits search*/
            jQuery("#school-name-input2, #post-code-input2").on("keydown", function(e) {

                if (e.which == 13) {
                    e.preventDefault();
                    e.stopPropagation();
                    document.querySelector("#js-school-search2").click();
                }
            });

            /*Form Submission*/
            jQuery("#form-submit2").on("click", function(e) {
                var isFormValid = jQuery("#contact-us-form2").valid();

                if (!isFormValid || !contact_checkInputType()) {
                    return false;
                }


                /*Prevent default options, we are going to use ajax and don't want page reload*/
                e.preventDefault();
                e.stopPropagation();

                /*Account needs to be unknown if the user can't select their school*/
                if (jQuery("#00N90000008kFXY2").val().length > 0) {
                    jQuery("#account2").val("unknown")
                }
                /*lock submit on finish*/
                jQuery("#form-submit2").prop("disabled", true);

                /*Disable the attributes we do not want to send into the API*/
                jQuery(".contact-no-send").prop("disabled", true);

                jQuery("#ajax-loader-placeholder2").show();

                jQuery.ajax({
                        method: "POST",
                        url: "https://api.modernstar.com/forms",
                        data: jQuery("#contact-us-form2").serialize(),
                        processData: false,
                        contentType: "application/x-www-form-urlencoded"
                    })
                    .done(function() {
                        //Show success message
                        jQuery('#form-submission-modal').modal('show');
                        /*Reset form*/
                        jQuery('input:not(.contact-no-clear)').val("");
                        jQuery('textarea').val("");
                        jQuery('select').prop('selectedIndex', 0);
                        jQuery("#js-select-school2 div").remove();
                        jQuery("#js-select-school2").addClass("hidden");
                        jQuery(".contact-school-select-title").addClass("hidden");
                        // jQuery("#contact-us-form").clearForm();
                    })
                    .fail(function(data) {
                        /*Output error*/
                        jQuery(
                                "<p id='form-submit-error2' class='error'>There is something wrong with your submission. Please contact <a href='mailto:onlinelearning@teaching.com.au'>onlinelearning@teaching.com.au</a></p>"
                            )
                            .insertAfter(".contact-submit-container");
                    })
                    .always(function() {
                        jQuery("#ajax-loader-placeholder2").hide();
                        jQuery("#form-submit2").prop("disabled", false);

                    });
                /*Unlock form in case. */
                jQuery(".contact-no-send").prop("disabled", false);

            });

            <?php if ($extension != 'nz') {   ?>
                /*Show Fields for manual school detail input*/
                jQuery("#contact-us-form2").on("click", ".contact-js-no-school", function(e) {

                    jQuery(".contact-school-search").hide();

                    jQuery("#js-select-school2 div").remove();
                    jQuery(".contact-school-select-title").addClass("hidden");

                    /*Prevent # going into URL*/
                    e.preventDefault();

                    /*Show the hidden details*/
                    jQuery("#hidden-details2").removeClass("hidden");

                    /*Remove disabled*/
                    jQuery("#hidden-details2 input, #hidden-details2 select").prop("disabled", false);
                });

                /*School selection behaviour*/
                jQuery("#hidden-select-school2").on("click", ".contact-school-select-container", function(e) {
                    /*Get the account number that we stored in the real input*/
                    var accountNumber = jQuery(this).children("input").val();

                    /*Programmatically select the input*/
                    jQuery(this).children("input").prop("checked", true);

                    /*Add selected to container so we can remove the others*/
                    jQuery(this).addClass("contact-selected");

                    /*Remove non selected school*/
                    jQuery(".contact-school-select-container").not(".contact-selected").remove();

                    /*Set hidden account number field to the account number*/
                    jQuery("#account2").val(accountNumber);

                    if ($("#school-name-input-error2").length) {
                        $("#school-name-input-error2").remove();
                    }

                });
                jQuery("#js-school-search-clear2").on("click", function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    jQuery("#js-select-school2 div").remove();
                    jQuery("#contact-us-form2 .contact-school-select-title").addClass("hidden");
                    jQuery("#js-select-school2").addClass("hidden");
                });

                jQuery("#hide-manual2").on("click", function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    jQuery("#hidden-details2").addClass("hidden");
                    jQuery("#hidden-details2 input, #hidden-details2 select").prop("disabled", true);
                    jQuery("#contact-us-form2 .contact-school-search").show();
                    jQuery("#js-select-school2").addClass("hidden");
                });
                jQuery("#js-school-search2").on("click", contactSearchSchool);

                jQuery(document).on("click", "#js-school-search2", function(e) {
                    e.preventDefault();
                });
            <?php   }   ?>
        };
        contact_forminit();
    </script>
<?php
}
/* Contact us form script ends */
?>

<script>
    var formModal = $("#trial-modal-parent").html();
    $('button[data-target="#wk-form-modal"], a[data-target="#wk-form-modal"] ').on('focus', function(e) {
        e.preventDefault();
        $("#trial-modal-parent").remove();
        if ($("#wk-form-modal")) {
            $("#wk-form-modal").remove();
        }
        $(this).after(formModal);
        forminit();
    });

    $(document).on('keydown', function(e) {
        var keyCode = e.keyCode || e.which;
        if (keyCode == 9) {
            if ($('.modal-backdrop').length != $('.modal.fade.in').length) {
                $('.modal-backdrop.fade.in').remove();
            }
        }

    });
</script>

<?php
$educational_resource_helper = helper_custom_post_type_educational_resource();
$educational_resource_post_type = $educational_resource_helper['post_type'];
if (is_singular($educational_resource_post_type)) {
    $resource_permalink = get_permalink(get_the_ID());
?>
    <script>
        function resource_forminit() {
        $("#<?php the_field('country', get_the_ID()); ?>").on('change', function(){        
            if(this.value == <?php the_field('country_au_value', get_the_ID()); ?>){
                $('#pardot-form .resource_state').removeClass('hidden');
            }else{
                $('#pardot-form .resource_state').addClass('hidden');
            }
        })
        jQuery("#pardot-form").validate({
            errorElement: 'p',
            rules: {
                '<?php the_field('email', get_the_ID()); ?>': { //Email
                    required: true,
                    email: true,
                },
                '<?php the_field('state', get_the_ID()); ?>': {
                    required: function(element) { //State
                        return $('#<?php the_field('country', get_the_ID()); ?>').val() == '<?php the_field('country_au_value', get_the_ID()); ?>';
                    }
                },
                '<?php the_field('phone', get_the_ID()); ?>': { //Phone
                    customPhone: true
                }
            },
            onfocusout: function(element) {
                this.element(element);
            },
            errorPlacement: function(error, element) {
                if(element.attr("name") == '<?php the_field('terms_and_conditions', get_the_ID()); ?>'){
                    error.insertAfter(".checkbox label");
                }else {
                    error.insertAfter(element);
                }
            }, 
            unhighlight: function (element, errorClass) {
                $(element).parents('.checkbox').find('p').remove();
                //console.log(element);
                $(element).removeClass(errorClass);
            },
            submitHandler: function(form) {
                form.submit();
                setTimeout(function () {                
                    window.location.replace('<?= $resource_permalink; ?>success/');
                }, 2000);
            }
        });

        // Custom validation rule for Australian phone numbers
        $.validator.addMethod("customPhone", function(phoneNumber, element) {
            // Australian and New Zealand phone numbers can have the following formats:
            // Australian:
            // 02/03/04/07/08: (0X) XXXX XXXX or (0X) XXXX-XXXX
            // 02/03/04/07/08: 04XX XXX XXX or 04XX-XXX-XXX
            // 13/18: XXXX XXXX
            // International: +61 XX XXXX XXXX or +61-XX-XXX-XXX
            // New Zealand:
            // +64 XX XXXX XXXX or +64-XX-XXX-XXX or 0X XXXX XXXX or 0X-XXXX-XXXX
            return this.optional(element) || phoneNumber.match(/^(\+61|0)[2-478](\-\d{4}|\s?\d{4})\s?\d{4}$/) || phoneNumber.match(/^(\+61|0)4\d{2}(\-\d{3}|\s\d{3})\s?\d{3}$/) || phoneNumber.match(/^(\+61)?1?[38]\d{2}\s?\d{4}$/) || phoneNumber.match(/^(\+61|0)4\d{2}(\s|\-)?\d{3}(\s|\-)?\d{3}$/) || phoneNumber.match(/^(\+64|0)[34679]\d{7}$|^(\+64|0)[28]\d{3}(\-)?\s?\d{4}$/);
        }, "Please enter a valid phone number.");
        
    }

    resource_forminit(); 
    </script>
<?php
}
?>

<script>
function togglePasswordVisibility(passwordInputId = "password", toggleButtonId = "toggle-password") {
  const passwordInput = document.getElementById(passwordInputId);
  const toggleButton = document.getElementById(toggleButtonId);
  
  passwordInput.type = (passwordInput.type === "password") ? "text" : "password";
  toggleButton.classList.toggle("fa-eye-slash");
  toggleButton.classList.toggle("fa-eye");
}

jQuery(document).ready(function($) {
	/* Video - Modaal Settings */
	jQuery('.video').modaal({
		type: 'video',
		overlay_close: true
	});
	/* Video - Custom button */
	jQuery(".btn-play-video")
		.on('mouseenter', function() {
			jQuery(this).css("backgroundColor", "#ff001d");
		})
		.on('mouseleave', function() {
			jQuery(this).css("backgroundColor", "#4a4947");
		});
	jQuery(".btn-play-video-layer")
		.on('mouseenter', function() {
			jQuery(this).parent().find(".btn-play-video").css("backgroundColor", "#ff001d");
		})
		.on('mouseleave', function() {
			jQuery(this).parent().find(".btn-play-video").css("backgroundColor", "#4a4947");
		});
});
</script>

</body>

</html>
