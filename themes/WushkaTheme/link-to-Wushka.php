<?php
  /* Template Name: Link to Wushka*/
  get_header();
  $extension = pathinfo($_SERVER['SERVER_NAME'], PATHINFO_EXTENSION);
?>
<div class="linkWushka-wrap">
    <div class="bubbles">
        <div class="b1">
            <picture>
                <source
                    srcset="<?php echo get_template_directory_uri(); ?>/img/helpful-resources/webp/b-green-orange.webp"
                    type="image/webp">
                <source srcset="<?php echo get_template_directory_uri(); ?>/img/helpful-resources/b-green-orange.png"
                    type="image/jpeg">
                <img src="<?php echo get_template_directory_uri(); ?>/img/helpful-resources/b-green-orange.png" alt="">
            </picture>
        </div>
        <div class="b2">
            <picture>
                <source
                    srcset="<?php echo get_template_directory_uri(); ?>/img/curriculum-assessment/webp/orange-oval.webp"
                    type="image/webp">
                <source srcset="<?php echo get_template_directory_uri(); ?>/img/curriculum-assessment/orange-oval.png"
                    type="image/jpeg">
                <img src="<?php echo get_template_directory_uri(); ?>/img/curriculum-assessment/orange-oval.png" alt="">
            </picture>
        </div>
    </div>
    <div id="hero">
        <div class="container">
            <div class="row">
                <div class="col-md-10 col-md-offset-1">
                    <h2 class="hero-title">Link to Wushka</h2>
                </div>
            </div>
        </div>
    </div>

    <?php 
        $supportLink = 'support@wushka.com.au';
        $homeLink = 'https://wushka.com.au';
        if($extension == 'nz')
        {
          $supportLink = 'support@wushka.co.nz';
          $homeLink = 'https://wushka.co.nz';
        } 
    ?>


    <section class="container-wrapper">
        <div class="container">
            <div class="link-wushka">
                <p class="para">Adding a link to Wushka on your school’s website makes it easier for teachers, students
                    and parents to find Wushka and start using the program.</p>
                <p class="para margin-bt">Linking to Wushka is easy; just copy the code below and add it to the code on
                    your website. If you do have any issues linking to Wushka do not hesitate to contact us for help at

                    <a
                        href="mailto:<?= $supportLink; ?>?subject=<?= rawurlencode('Enquiry about linking to Wushka'); ?>"><?= $supportLink; ?></a>
                </p>

                <div class="wushka-info">
                    <h2 class="sub-heading">Link to Wushka Using Just Text</h2>

                    <pre><code>&lt;a href=&quot;<?= $homeLink; ?>&quot;&gt;We are using the Wushka Digital Reading Program. Click here to find out more about the program and log in&lt;/a&gt;</code></pre>

                    <a href="<?= $homeLink; ?>" class="wushka-site margin-bt"
                        aria-label="We are using the Wushka Digital Reading Program."
                        title="Text link to Wushka">We are using the Wushka Digital Reading Program. Click here to find
                        out more about the program and log in</a>
                </div>
                <div class="wushka-info">
                    <h2 class="sub-heading">Link to Wushka Using Just the Logo</h2>
                    <pre><code>&lt;a href=&quot;<?= $homeLink; ?>&quot;&gt;&lt;img src=&quot;<?= getCdnLink(); ?>/Resources/logo-wushka-white-tagline4.svg&quot; style=&quot;width:300px;&quot; alt=&quot;Wushka Digital Reading program&quot;&gt;&lt;/a&gt;</code></pre>
                    <a href="<?= $homeLink; ?>/" class="wushka-logo margin-bt"><img
                            src="<?php echo get_template_directory_uri(); ?>/img/logo-wushka.svg" alt="Wushka Logo"
                            class="svg-logo" /></a>
                </div>
                <div class="wushka-info third-block">
                    <h2 class="sub-heading">Link to Wushka Using the Logo & Text</h2>
                    <pre><code>&lt;a href=&quot;<?= $homeLink; ?>&quot;&gt;&lt;img src=&quot;<?= getCdnLink(); ?>/Resources/logo-wushka-white-tagline4.svg&quot; style=&quot;width:300px;&quot; alt=&quot;Wushka Literacy&quot;&gt;We are using the Wushka Digital Reading Program. Click here to find out more about the program and log in&lt;/a&gt;</code></pre>
                    <div class="wushka-both"><a href="#" onclick="event.preventDefault(); javascript:window.location='<?= $homeLink; ?>/'" class="wushka-logo"><img
                                src="<?php echo get_template_directory_uri(); ?>/img/logo-wushka.svg" alt="Wushka Text"
                                class="svg-logo" /></a><a href="#" onclick="event.preventDefault(); javascript:window.location='<?= $homeLink; ?>/'" tabindex="-1" class="wushka-site" aria-label="Find about the program and login">We are using the
                            Wushka Digital Reading Program. Click here to find out more about the program and log in</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php get_footer(); ?>