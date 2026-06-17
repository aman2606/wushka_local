<link rel="stylesheet" href="<?php echo wsm_dashboard_assets ?>/css/bootstrap-custom.css" />
<link rel="stylesheet" href="<?php echo wsm_dashboard_assets ?>/css/styles.css" />
<div class="boot_wsm">
    <div class="wrap">
        <h2>Clear Old Student Data ( Based on Last Activity On Year <?php echo date('Y', strtotime('-3 year')); ?> and below )</h2>
        <div id="poststuff">
            <div class="metabox-holder">
                <div class="postbox-container" id='postbox-container-1'>
                    <div class="postbox">
                        <div class="postbox-header">
                            <h2 class="hndle">Clear Old Student Data</h2>
                        </div>
                        <div class="inside">
                            <div class="row input-text-wrap">
                                <div class="col-md-6">
                                    <a class="button clear_old_data">Clear Old Data</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>