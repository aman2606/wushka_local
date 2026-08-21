<?php

use App\Controllers\AzureAuth;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

if (is_user_logged_in()) {
    wp_redirect(get_site_url());
}

$extension = pathinfo($_SERVER['SERVER_NAME'], PATHINFO_EXTENSION);

$studentUrl = get_site_url() . '/my-page';

if (isset($_GET['login'])) {
    if ($_GET['login'] == 'failed') {
        $alert = [
            'type'        => 'danger',
            'message'    => 'Invalid username or password.'
        ];
    }
    if ($_GET['login'] == 'invalidkey') {
        $alert = [
            'type'        => 'danger',
            'message'    => 'Invalid Key.'
        ];
    }
    if ($_GET['login'] == 'empty') {
        $alert = [
            'type'        => 'danger',
            'message'    => 'All fields are required.'
        ];
    }

    if ($_GET['login'] == 'nsw_login_disabled') {
        $alert = [
            'type'        => 'danger',
            'message'    => 'Sorry, Username and Password login are not allowed for your school\'s login system. Please use Login with NSW DOE for authentication.'
        ];
    }

    if ($_GET['login'] == 'login_disabled') {
        $alert = [
            'type'        => 'danger',
            'message'    => 'Sorry, Username and Password login are not allowed for your school\'s login system. Please use Login with NSW DOE for authentication.'
        ];
    }
}

if (isset($_GET['sso']) && $_GET['sso'] == 'nsw_doe') {
    $provider = new AzureAuth();
    $authUrl = $provider->getAuthorizationUrl();
    wp_redirect($authUrl);
    exit();
}

// Check if the user just requested a new password 
if (isset($_GET['checkemail']) && $_GET['checkemail'] == 'confirm') {
    $alert = [
        'type'        => 'success',
        'message'    => 'Check your email for a link to reset your password.'
    ];
}

if (isset($_GET['password']) && $_GET['password'] == 'changed' && !$_GET['login']) {
    $alert = [
        'type'        => 'success',
        'message'    => 'Your password has been changed. You can sign in now.'
    ];
}

if ($extension != 'nz') {
    if (isset($_GET['code']) && isset($_GET['state'])) {
        if (isset($_SESSION['oauth_state'])) {
            $code     =     sanitize_text_field($_GET['code']);
            $state     =     sanitize_text_field($_GET['state']);

            $oauthClient = new AzureAuth();

            $validate = $oauthClient->validateAuthState($code, $state);
            if ($validate['type'] == 'success') {
                //Authenticate user with token
                $authenticate = $oauthClient->authenticateUser();
                $alert = $authenticate;
            } else {
                $alert = $validate;
            }
        } else {
            $alert = [
                'type'        => 'danger',
                'message'    => 'Unexpected state.'
            ];
        }
    }
}



get_header();
?>
<style>
    .show-password {
        position: relative;
    }

    #toggle-password {
        position: absolute;
        top: -3px;
        right: 0;
        padding-right: 10px;
        cursor: pointer;
    }
</style>
<section class="sso-login-wrapper container mt70 pb70">
    <div class="clearfix">

        <div class="col-md-6 col-sm-6 col-xs-12">
            <h1 class="mb20 text-left login_heading">Login to your account</h1>

            <?php
            if (!empty($alert)) {
                $alert_type = isset($alert['type']) ? ($alert['type'] == 'error' ? 'danger' : $alert['type']) : '';
                $alert_message = (isset($alert['message'])) ? $alert['message'] : '';
            ?>
                <div class="alert alert-<?= $alert_type; ?> alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">
                            &times;
                        </span>
                    </button>
                    <?= $alert_message; ?>
                </div>
            <?php } ?>

            <div class="well p30">
                <form method="post" action="<?php echo get_site_url(); ?>/wp-login.php" autocomplete="off">
                    <div class="form-group">
                        <label for="username">Username <span class="error">*</span></label>
                        <input type="text" class="form-control" id="username" name="log" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password <span class="error">*</span></label>
                        <span class="show-password"><input type="password" class="form-control" id="password" name="pwd" required>
                            <i class="fa fa-eye" onclick="togglePasswordVisibility()" id="toggle-password"></i>
                        </span>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="hidden" name="redirect" value="<?php echo esc_url(home_url()); ?>" />
                                <button type="submit" class="btn btn-primary btn-block" name="login">Login</button>
                            </div>
                        </div>
                        <div class="col-md-6 text-right">
                            <a href="<?php echo esc_url(home_url('/lost-password')); ?>" class="forgot">Forgot Password?</a>
                        </div>
                    </div>

                    <?php
                    if ($extension != 'nz') {
                    ?>
                        <div class="form-divider">
                            <span class="line"></span>
                            <p>or continue with</p>
                        </div>

                        <div class="form-group">
                            <a href="<?php echo esc_url(add_query_arg('sso', 'nsw_doe')); ?>" class="btn btn-primary btn-block other-login-btn" title="Login with NSW Department of Education">
                                <img src="<?= get_template_directory_uri(); ?>/img/nsw-government-logo.svg" alt="">
                                Login with NSW DOE
                            </a>
                        </div>
                    <?php    }    ?>
                </form>
            </div>
        </div>

        <!-- QR CODE LOGIN -->
        <!-- QR CODE LOGIN -->
        <div class="col-md-6 col-sm-6 col-xs-12">
            <h1 class="mb20 text-left login_qr_heading">Login with your QR Code</h1>
            <div class="QR_login">
                <div class="QR_login_sample"></div>
                <div class="camera_activation_section">
                    <div class="activate_camera btn btn-primary btn-block open_scanner">
                        <span class="cas_camera_icon"><img src="<?php echo get_template_directory_uri() ?>/images/QR/icon_camera.png" /></span><span class='ca_text'>Click here to Activate Camera</span>
                        <div class="cas_cursor">
                            <img src="<?php echo get_template_directory_uri() ?>/images/QR/cursor.png" />
                        </div>
                    </div>
                    <a class="btn btn-primary btn-block activate_camera" style="margin-top:5px;" href="<?= CDN_URL  ?>/public/Wushka-QR-Code-Sign-In.pdf" target="_blank">
                        <span>Learn How</span>
                    </a>
                    <div style="padding-top: 10px;text-align: center;color: #73777B;"><small><i>Not available for NSW Department of Education Schools.</i></small></div>
                    <?php
                    /*<div class="cas_watch_video btn btn-block">
                        <span class="cas_video_icon"><img src="<?php echo get_template_directory_uri() ?>/images/QR/icon_play.png" /></span class='ca_text'><span>Watch the Video here</span>
                    </div>*/
                    ?>

                </div>
            </div>



            <!-- <div class="QR_section">
                <div id="reader" style="height:100%;width:100%"></div>
            </div>
            <h1 class="mb20 text-center"><a href="javascript:void(0)" class='stop_scanner btn btn-primary' style="display: none;width:100%">Stop Scanning</a></h1> -->
        </div>
    </div>
</section>
<section class="container mt70 pb70 login_with_QR" style="display:none;">
    <div class="clearfix">
        <div class="stop_scanner btn btn-primary"><span class="back_icon"><img src="<?php echo get_template_directory_uri() ?>/images/QR/icon_back.png" /></span><span>Back</span></div>
        <a href="javascript:void(0)" style="display:none;" class="flip_camera btn" title="Flip Camera"><span class="glyphicon glyphicon-refresh"></span></a>
        <div class="row">
            <div class="col-md-4 col-sm-12 col-xs-12 qr_postion_text">
                <div class="lqr_arrow"></div>
                <div>Position your QR Code within the frame</div>
            </div>
            <div class="col-md-4 col-sm-6 col-xs-12 reader-col">
                <img class="qr_place" src="<?php echo get_template_directory_uri() ?>/images/QR/qrcode_frame.png" />
                <div class="reader" id="reader"></div>
                <!-- <div class="testing_cam" style="height:300px;width:300px;background:yellow;position: absolute;top: -55px;transform:scaleX(-1)">Testing Camera</div> -->
            </div>
            <div class="col-md-4 col-sm-6 col-xs-12 qr_code_image">
                <img src="<?php echo get_template_directory_uri() ?>/images/QR/qrcode_image_2.png" />
            </div>

        </div>
    </div>
</section>

<script src="https://unpkg.com/@ungap/global-this@0.4.4/min.js"></script>
<script src="https://unpkg.com/html5-qrcode@2.2.1/html5-qrcode.min.js"></script>
<script>
    function goToScanner() {

        jQuery('.login_with_QR').show();
        jQuery('.sso-login-wrapper').hide();
        jQuery('html,body').scrollTop(0);

    }

    function goBackToLogin() {

        jQuery('.login_with_QR').hide();
        jQuery('.sso-login-wrapper').show();
        jQuery('html,body').scrollTop(0);


    }

    let openScannerLink = document.querySelector('.open_scanner');
    //let decodeText = document.querySelector('.decode_text');
    let stopScanner = document.querySelector('.stop_scanner');

    function onScanSuccess(decodedText, decodedResult) {


        // stopScanner.innerHTML = "Finding Student ....";



        html5QrCode.stop().then(_ => {


            jQuery.ajax({
                type: "POST",
                url: "<?php echo admin_url('admin-ajax.php') ?>",
                data: {
                    code_value: decodedText,
                    action: 'student_login_by_QR_code'
                },
                success: function(response) {

                    //console.log(response);

                    //stopScanner.innerHTML = "Stop Scanning";

                    var response = JSON.parse(response);

                    if (response.success) {

                        location.href = "<?php echo $studentUrl ?>";

                    } else {

                        // stopScanner.style.display = "none";
                        // openScannerLink.style.display = 'block';

                        // goBackToLogin();

                        ask.alert.render({
                            message: response.message,
                            afterElement: ".stop_scanner",
                            infoClass: 'danger'
                        });

                        jQuery('.open_scanner').trigger('click');

                        //alert("Student Not found !");

                    }

                }
            });


            // decodeText.innerHTML = `<strong>${decodedResult}</strong>`;
            // openScannerLink.style.display = 'inline';


        }).catch(error => {
            // Could not stop scanning for reasons specified in `error`.
            // This conditions should ideally not happen.
        });




    }

    function onFailure(error) {
        // handle scan failure, usually better to ignore and keep scanning.
        // for example:
        console.log(`Code scan error = ${error}`);
    }

    const html5QrCode = new Html5Qrcode("reader");

    var deviceCameras = [];

    var currentCamera = false;

    function flipCamera(deviceCameras) {

        var nextCamera = deviceCameras.filter(function(value) {

            return value.id != currentCamera.id;

        });

        currentCamera = nextCamera[0];


        return currentCamera;

    }

    const config = {

        fps: 10,
        qrbox: {
            width: 350,
            height: 350
        },
        rememberLastUsedCamera: false
    };

    function startCamera(camera = false) {

        let cameraMode = camera.id;

        if (camera === false) {

            cameraMode = {
                facingMode: "user"
            }

        }

        html5QrCode.start(cameraMode, config, onScanSuccess, onFailure).then(function(res) {

            jQuery('.qr_place').css('height', jQuery('video').height() + 35);

            // console.log('START RES IS:');
            // console.log(res);

            if (deviceCameras.length > 1) {
                jQuery('.flip_camera').show();
            } else {
                jQuery('.flip_camera').hide();

            }

            var cameraLabel = false;

            if (typeof camera.label != 'undefined') {

                cameraLabel = camera.label.toLowerCase();
            }

            // console.log("Camera Lable Is========");
            // console.log(cameraLabel);


            if (cameraLabel && (cameraLabel.includes('back') || cameraLabel.includes('rear'))) {

                jQuery("#reader video").css('transform', 'scaleX(1)');
                //jQuery(".testing_cam").css('transform','scaleX(1)');
            } else {

                jQuery("#reader video").css('transform', 'scaleX(-1)');
                // jQuery(".testing_cam").css('transform','scaleX(-1)');

            }


        }).
        catch((err) => {

            console.log("Start Camera Errr");
            console.log(err);
        });



    }

    jQuery(function() {

        jQuery('.QR_login_sample,.open_scanner').on('click', () => {

            if (jQuery(".sso-login-wrapper #confirm-alert-box").length > 0) {
                jQuery(".sso-login-wrapper #confirm-alert-box").remove();
            }

            goToScanner();

            Html5Qrcode.getCameras().then(devices => {

                //alert(JSON.stringify(devices));

                deviceCameras = [];

                if (devices && devices.length) {

                    // var cameraId = devices[0].id;

                    for (let cam of devices) {

                        deviceCameras.push({
                            id: cam.id,
                            label: cam.label
                        });

                    }

                }

                //deviceCameras.push('testCameraId');

                currentCamera = deviceCameras[0];

                startCamera(currentCamera);



            }).catch((err) => {

                console.log(err);

                if (err.includes('Permission denied')) {
                    err = "Permission denied: Please give your camera permission to scan.";
                }

                if (err.includes('NotReadableError')) {
                    err = "Please check your camera. Please enable it or close if its already open for other sources";
                }

                if (err.includes('device not found')) {
                    err = "Looks like your device does not have Camera. Please enable camera to access this feature.";

                }

                goBackToLogin();

                ask.alert.render({
                    message: err,
                    infoClass: 'danger',
                    afterElement: ".login_qr_heading"
                });

            });

        });

        $('.flip_camera').click(function() {

            if (html5QrCode.getState() == 2) {

                html5QrCode.stop().then(function() {

                    currentCamera = flipCamera(deviceCameras);

                    startCamera(currentCamera);


                });

            }

        });

        stopScanner.addEventListener('click', () => {

            html5QrCode.stop().then(function() {

                // stopScanner.style.display = "none";
                // openScannerLink.style.display = 'block';

                goBackToLogin();

            });

        });

        window.onorientationchange = (event) => {

            console.log("fixing Height");

            setTimeout(function() {

                jQuery('.qr_place').css('height', jQuery('video').height() + 35);


            }, 500);


        };



    });
</script>

<?php get_footer();
