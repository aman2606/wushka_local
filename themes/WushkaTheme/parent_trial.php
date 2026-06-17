<?php
/**
 * Template Name: Parent - Free Trial Signup
 */
include_once 'functions/parent-trial/class_parent-trial.php';
//Check for Submitted form
$c_trial = new Trial_Parent();
$c_trial->create_trail_account();

$a_return   = $c_trial->a_return;
$a_user     = $c_trial->a_user;


?>
<?php get_header(); ?>
<div class="container-fluid">
    <div class="row mt30">
        <div class="col-xs-12">
            <h1 class="glyphicon-heading">
                <span class="x2 glyphicon glyphicon-user hidden-xs"></span>
                <span class="glyphicon-heading-text">Want to try? No problem!</span>
            </h1>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-md-4 col-md-offset-4 col-lg-6 col-lg-offset-3">
            <?php if ( ! $a_return['sent'] ) { ?>
            <div class="panel panel-default">
                <div class="panel-body">
                    <form action="#" method="POST" id="wushka-free-trial">
                        <div class="col-xs-12 form-group">
                            <!-- h3>Enter your details here</h3 -->
                        </div>
                        <div class="col-xs-12 form-group">
                            <div class="col-xs-12 col-lg-3 text-right">
                                <label class="control-label" for="first_name">First Name</label>
                            </div>
                            <div class="col-xs-12 col-lg-6">
                                <input type="text" name="first_name" id="first_name" class="form-control" minlength="2" required="" value="<?php echo $a_user['first_name']; ?>">
                            </div>
                        </div>
                        <div class="col-xs-12 form-group">
                            <div class="col-xs-12 col-lg-3 text-right">
                                <label class="control-label" for="last_name">Surname</label>
                            </div>
                            <div class="col-xs-12 col-lg-6">
                                <input type="text" name="last_name" id="last_name" class="form-control" minlength="2" required="" value="<?php echo $a_user['last_name']; ?>">
                            </div>
                        </div>
                        <div class="col-xs-12 form-group">
                            <div class="col-xs-12 col-lg-3 text-right">
                                <label class="control-label" for="user_email">Email</label>
                            </div>
                            <div class="col-xs-12 col-lg-6">
                                <input type="email" name="user_email" id="user_email" class="form-control" required="" value="<?php echo $a_user['user_email']; ?>">
                            </div>
                        </div>
                        <div class="col-xs-12 form-group">
                            <div class="col-xs-12 col-lg-6 col-lg-offset-3">
                                <p><input type="checkbox" id="user_agree" name="user_agree" required="" /><span> I agree to wushka <a href="<?php echo home_url('/home-terms-and-conditions/');?>">Terms and Conditions</a></span></p>
                            </div>
                            <div class="col-xs-12 col-lg-6 col-lg-offset-3">
                                <label class="control-label alert alert-danger" id="form-alert"><?php echo $a_return['message']; ?></label>
                            </div>
                        </div>
                        <div class="col-xs-12 text-center"><p>We will send you an activation email to start your trial.</p></div>
                        <div class="col-xs-12 form-group">
                            <div class="col-xs-12 col-lg-6 col-lg-offset-3">
                                <input type="submit" class="btn btn-primary btn-block" id="submit_trial" name="submit_trial" value="Begin 5 Day Free Trial!" />
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        <?php } else { ?>
            <div class="panel panel-success">
                <div class="panel-heading text-center" style="border-bottom:none;">
                    <h3>An email has been sent!</h3>
                </div>
                <div class="panel-body">
                    <div class="col-xs-12">
                        <p style="margin:0;">In a few moments you will receive an email with a link to activate your trial.</p>
                    </div>
                </div>
            </div>
        <?php } ?>
        </div>
    </div>
</div>

<?php get_footer(); ?>
<?php /* ----- EOF ----- */ ?>