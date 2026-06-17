<style>
    .educational-resource li:before {
        padding: 6px 7px;
        content: url('<?php echo get_template_directory_uri() ?>/img/icon_tick.svg');
        font-family: FontAwesome;
        color: white;
        background: <?= $args['bullet_point_colour'] ?>;
        border-radius: 50%;
        margin-right: 11px;
        font-size: 14px;

    }
</style>
<div class="educational-resource resource-single template3 lp">
    <div class="breadcrumb-container" style="background-color:<?= $args['background_color'] ?>;">
        <div class="container">
            <div class="breadcrumb-content" style="background-color:<?= $args['background_color'] ?>;">
                <div class="row">
                    <div class="col-md-6">
                        <img src="<?php echo get_the_post_thumbnail_url(); ?>" class="img-responsive" alt="">
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <h1 class="white title_heading">
                            <?php the_title(); ?>
                        </h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="resource-container">
            <div class="row resource-bg">
                <div class="col-md-6 content">
                    <div class="content_backend">
                        <?php the_content(); ?>
                    </div>
                </div>
                <div class="col-md-6 form">
                    <h3>Enter your details</h3>
                    <p class="text mb30">*All Fields Required</p>
                    <form accept-charset="UTF-8" method="post" action="<?= $args['form_post_link']; ?>" class="form" id="pardot-form" target="pardot-hidden-form">
                        <div class="form-group first_name">
                            <label for="<?= $args['first_name']; ?>">First Name*</label>
                            <input type="text" name="<?= $args['first_name']; ?>" id="<?= $args['first_name']; ?>" class="form-control" size="30" maxlength="40" required />
                        </div>
                        <div class="form-group last_name">
                            <label for="<?= $args['last_name']; ?>">Last Name*</label>
                            <input type="text" name="<?= $args['last_name']; ?>" id="<?= $args['last_name']; ?>" class="form-control" size="30" maxlength="80" required />
                        </div>
                        <div class="form-group email">
                            <label for="<?= $args['email']; ?>">Email*</label>
                            <input type="email" name="<?= $args['email']; ?>" id="<?= $args['email']; ?>" class="form-control" size="30" maxlength="255" required />
                        </div>

                        <?php if (!empty($args['phone'])){ ?>
                        <div class="form-group phone">
                            <label for="<?= $args['phone']; ?>">Phone</label>
                            <input type="text" name="<?= $args['phone']; ?>" id="<?= $args['phone']; ?>" class="form-control" size="20" maxlength="80" />
                        </div>
                        <?php } ?>

                        <div class="form-group country">
                            <label for="<?= $args['country']; ?>">Country*</label>
                            <select name="<?= $args['country']; ?>" id="<?= $args['country']; ?>" class="form-control notselected-select" required>
                                <?= $args['country_option']; ?>
                            </select>
                        </div>
                        <div class="form-group resource_state hidden">
                            <label for="<?= $args['state']; ?>">State*</label>
                            <select name="<?= $args['state']; ?>" id="<?= $args['state']; ?>" class="form-control notselected-select" required>
                                <?= $args['state_option']; ?>
                            </select>
                        </div>
                        <?php if (!empty($args['school'])){ ?>
                        <div class="form-group school">
                            <label for="<?= $args['school']; ?>">School*</label>
                            <input type="text" name="<?= $args['school']; ?>" id="<?= $args['school']; ?>" class="form-control" required />
                        </div>
                        <?php } ?>
                        <div class="form-group Education_Sector">
                            <label for="<?= $args['education_sector']; ?>">Education Sector*</label>
                            <select name="<?= $args['education_sector']; ?>" id="<?= $args['education_sector']; ?>" class="form-control notselected-select" required>
                                <?= $args['education_sector_option']; ?>
                            </select>
                        </div>
                        <div class="form-group Role_Title">
                            <label for="<?= $args['job_title']; ?>">Job Title*</label>
                            <select name="<?= $args['job_title']; ?>" id="<?= $args['job_title']; ?>" class="form-control notselected-select" required>
                                <?= $args['job_title_option']; ?>
                            </select>
                        </div>
                        <div class="form-group tcs Terms_and_Conditions">
                            <div class="checkbox checkbox-info">
                                <input type="checkbox" name="<?= $args['terms_and_conditions']; ?>" id="<?= $args['terms_and_conditions']; ?>" value="<?php $arr = explode("_", $args['terms_and_conditions']);
                                                                                                                                                        echo end($arr); ?>" required />
                                <label for="<?= $args['terms_and_conditions']; ?>" style="padding-left:20px;">
                                    <span class="text" style="padding-left:20px;"><b>Terms and Conditions</b></span><br /><span class="text" style="padding-left:20px;font-family:LatoRegular;display:block;">I agree to the Terms and Conditions and to receive information from MTA about Wushka.</span>
                                </label>
                            </div>
                        </div>
                        <p style="position: absolute; width: 190px; left: -9999px; top: -9999px; visibility: hidden">
                            <label for="pi_extra_field">Comments</label>
                            <input type="text" name="pi_extra_field" id="pi_extra_field" />
                        </p>
                        <div class="clearfix form-group download-btn-group">
                            <div class="pull-left">
                                <!-- forces IE5-8 to correctly submit UTF8 content  -->
                                <input name="_utf8" type="hidden" value="&#9731;" />
                                <?php if(!empty($args['lp_submit_button_text'])){ ?>
                                <button type="submit" class="btn btn-primary" accesskey="s" value="Submit" style="background-color:<?= $args['button_colour'] ?>;border-color:<?= $args['button_colour'] ?>;"><?php echo !empty($args['lp_submit_button_text']) ? $args['lp_submit_button_text'] : 'Download Now'; ?></button>
                                <?php } ?>
                                <input type="hidden" name="hiddenDependentFields" id="hiddenDependentFields" value="" />
                            </div>

                        </div>
                    </form>
                    <iframe id="pardot-iframe" src="" style="display:none" name="pardot-hidden-form"></iframe>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    jQuery(document).ready(function() {
        jQuery('select').focus(function() {
            jQuery(this).removeClass('notselected-select');
        });
        jQuery('select').focusout(function() {
            if (jQuery(this).val() == '') {
                jQuery(this).addClass('notselected-select');
            } else {
                jQuery(this).removeClass('notselected-select');
            }
        });
    });
</script>
