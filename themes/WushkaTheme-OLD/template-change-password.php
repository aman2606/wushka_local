<?php
/*
Template Name: Change Password
*/

if (!is_user_logged_in()) {
    wp_redirect(wp_login_url());
    exit;
}

$user = wp_get_current_user();
$allowed_roles = passwordPolicyAllowedRoles();

if (!array_intersect($allowed_roles, $user->roles)) {
    wp_redirect(home_url());
    exit;
}

$errors = [];


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $old_pass = $_POST['old_pass'] ?? '';
    $new_pass = $_POST['new_pass'] ?? '';
    $confirm_pass = $_POST['confirm_pass'] ?? '';

    // if (!wp_check_password($old_pass, $user->user_pass, $user->ID)) {
    //     $errors[] = 'Incorrect current password.';
    // }

    if (!is_password_policy_valid($new_pass)) {
        $errors[] = 'New password must be at least 15 characters long and include letters, numbers, and symbols.';
    }

    if ($new_pass !== $confirm_pass) {
        $errors[] = 'New password and confirm password do not match.';
    }

    if (empty($errors)) {
        wp_set_password($new_pass, $user->ID);
        delete_user_meta($user->ID, 'password_needs_reset');

        // Log the user out
        wp_logout();

        // Redirect to your custom login page with success flag
        wp_redirect(site_url('/login?password=changed'));
        exit;
    }
}

get_header();
?>

<style>
    body {
        background-color: #f8f9fa;
    }

    .change-password-container {
        display: flex;
        align-items: center;
        justify-content: center;
        margin-top: 30px;
    }

    .change-password-form {
        background: #fff;
        padding: 30px;
        border-radius: 6px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        max-width: 400px;
        width: 100%;
        color: #000;
    }

    .note {
        font-size: 13px;
        color: #777;
        padding-top: 20px;
        text-align: left;
    }
    .notice-container{
        display: none;
    }
</style>

<div class="container change-password-container">
    <div class="change-password-form">
        <h3 class="text-center" style="font-weight: bold;">Change Your Password</h3>

        <?php if (isset($_GET['success']) && $_GET['success'] == '1') : ?>
            <div class="alert alert-success" style="margin-top:12px;">
                Password changed successfully! You can continue using the site.
            </div>
        <?php endif; ?>

        <?php if (!empty($errors)) : ?>
            <div class="alert alert-danger" style="margin-top:12px;">
                <ul class="mb-0">
                    <?php foreach ($errors as $e) echo "<li>$e</li>"; ?>
                </ul>
            </div>
        <?php endif; ?>

        <p class="note text-center">
            <strong>Note:</strong> As a non-student user, your password must meet the password policy criteria (At least 15 characters long and include letters, numbers, and symbols).
        </p>

        <form method="post" class="mt-3">
            <!-- <div class="form-group">
                <label for="old_pass">Current Password</label>
                <input type="password" name="old_pass" id="old_pass" class="form-control" required>
            </div> -->

            <div class="form-group">
                <label for="new_pass">New Password</label>
                <input type="password" name="new_pass" id="new_pass" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="confirm_pass">Confirm New Password</label>
                <input type="password" name="confirm_pass" id="confirm_pass" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary btn-block">Update Password</button>

        </form>
    </div>
</div>

<?php get_footer(); ?>