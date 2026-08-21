<?php
/*
  Template Name: Contact Us
 */
?>
<?php
if ($_POST['sent']) {
    $error = "";
    if (!trim($_POST['your_name'])) {
        $error .= "<p>Please enter your name</p>";
    }
    if (!filter_var(trim($_POST['your_email']), FILTER_VALIDATE_EMAIL)) {
        $error .= "<p>Please enter a valid email address</p>";
    }
    if (!$error) {
        $email = wp_mail(get_option("admin_email"), trim($_POST['your_name']) . " sent you a message from " . get_option("blogname"), "Keep me informed of when Lesson Zone is open for subscriptions", "From: " . trim($_POST['your_name']) . " <" . trim($_POST['your_email']) . ">");
    }
}
?>
<?php get_header(); ?>

<div class="container">
    <div class="row">
        <div class="col-xs-12">
            <div class="row">
                <div class="contact-us">
                    <?php if (have_posts()) while (have_posts()) : the_post(); ?>
                            <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                                <h1><?php the_title(); ?></h1>
                                <div>
                                    <?php if ($email) { ?>
                                        <p><strong>Message successfully sent. We will reply as soon as we can</strong></p>
                                    <?php } else {
                                        if ($error) { ?>
                                            <p><strong>Your message hasn't been sent</strong><p>
                                                <?php echo $error; ?>
                                    <?php } else {
                                        the_content();
                                    } ?>
                                        <form action="<?php the_permalink(); ?>" id="contact_me" method="post">
                                            <input type="hidden" name="sent" id="sent" value="1" />
                                            <div id="form">
                                                <div class="label">Your Name*</div>
                                                <div class="input-field"><input type="text" name="your_name" id="your_name" value="<?php echo $_POST['your_name']; ?>" /></div>
                                                <div class="label">Your Email*</div>
                                                <div class="input-field"><input type="email" name="your_email" id="your_email" value="<?php echo $_POST['your_email']; ?>" /></div>
                                                <div class="input-field"><input type="submit" name = "send" value = "Send email" /></div>
                                            </div>

                                        </form>
                            <?php } ?>
                                </div><!-- .entry-content -->
                            </div><!-- #post-## -->
    <?php endwhile; ?>
                </div>
            </div>
        </div>

    </div>

</div>

<?php 
include 'dashboard_options.php';
get_footer(); 
?>