<div class="aws-content lzaws-settings">
    <?php
    $buckets = $this->get_buckets();


    if (is_wp_error($buckets)) :
        ?>
        <div class="error">
            <p>
                <?php _e('Error retrieving a list of your S3 buckets from AWS:', 'lzaws'); ?>
                <?php echo $buckets->get_error_message(); ?>
            </p>
        </div>
        <?php
    endif;

    if (isset($_GET['updated'])) {
        ?>
        <div class="updated">
            <p>
                <?php _e('Settings saved.', 'lzaws'); ?>
            </p>
        </div>
        <?php
    }
    ?>
    <form method="post">
       
        <input type="hidden" name="action" value="save" />
        <?php wp_nonce_field('lzaws-save-settings') ?>

        <table class="form-table">
        <?php
           
        //print_r(gettype($buckets));
        //exit();
        ?>
            <tr valign="top">
               
                <td>
                    <h3><?php _e('AWS S3 Settings', 'lzaws'); ?></h3>

                    <select name="bucket" id="bucket" class="bucket">
                        <option value="">-- <?php _e('Select an S3 Bucket', 'lzaws'); ?> --</option>
                        <?php if (is_array($buckets)) foreach ($buckets as $bucket): ?>
                                <option value="<?php echo esc_attr($bucket['Name']); ?>" <?php echo $bucket['Name'] == $this->get_setting('bucket') ? 'selected="selected"' : ''; ?>><?php echo esc_html($bucket['Name']); ?></option>
                            <?php endforeach; ?>
                        <option value="new"><?php _e('Create a new bucket...', 'lzaws'); ?></option>
                    </select>
                    <button type="button" class="button" id="listBucket"><?php _e('List Bucket >>', 'lzaws'); ?></button>
                    <br />

                    <input type="checkbox" name="virtual-host" value="1" id="virtual-host" <?php echo $this->get_setting('virtual-host') ? 'checked="checked" ' : ''; ?> />
                    <label for="virtual-host"> <?php _e('Bucket is setup for virtual hosting', 'lzaws'); ?></label> (<a href="http://docs.amazonwebservices.com/AmazonS3/2006-03-01/VirtualHosting.html">more info</a>)
                    <br />

                    <input type="checkbox" name="expires" value="1" id="expires" <?php echo $this->get_setting('expires') ? 'checked="checked" ' : ''; ?> />
                    <label for="expires"> <?php printf(__('Set a <a href="%s" target="_blank">far future HTTP expiration header</a> for uploaded files <em>(recommended)</em>', 'lzaws'), 'http://developer.yahoo.com/performance/rules.html#expires'); ?></label>
                </td>
                <td rowspan='5' id='s3bucket-list'>
                    <div class='s3bucket-list'></div>
                </td>
            </tr>

            <tr valign="top">
                <td><?= 'lzaws' ?>
                    <label><?php _e('Object Path:', 'lzaws'); ?></label>&nbsp;&nbsp;
                    <input type="text" name="object-prefix" id="object-prefix" value="<?php echo esc_attr($this->get_setting('object-prefix')); ?>" size="30" />
                </td>
            </tr>

            <tr valign="top">
                <td>
                    <h3><?php _e('CloudFront Settings', 'lzaws'); ?></h3>

                    <label><?php _e('Domain Name', 'lzaws'); ?></label><br />
                    <input type="text" name="cloudfront" id="cloudfront" value="<?php echo esc_attr($this->get_setting('cloudfront')); ?>" size="50" />
                    <p class="description"><?php _e('Leave blank if you aren&#8217;t using CloudFront.', 'lzaws'); ?></p>

                </td>
            </tr>

            <tr valign="top">
                <td>
                    <h3><?php _e('Plugin Settings', 'lzaws'); ?></h3>
                       <?php //print_r($this->get_setting()); ?>
                    <input type="checkbox" name="copy-to-s3" id="copy-to-s3" value="1" id="copy-to-s3" <?php echo $this->get_setting('copy-to-s3') ? 'checked="checked" ' : ''; ?> />
                    <label for="copy-to-s3"> <?php _e('Copy files to S3 as they are uploaded to the Media Library', 'lzaws'); ?></label>
                    <br />

                    <input type="checkbox" name="serve-from-s3" id="serve-from-s3" value="1" id="serve-from-s3" <?php echo $this->get_setting('serve-from-s3') ? 'checked="checked" ' : ''; ?> />
                    <label for="serve-from-s3"> <?php _e('Point file URLs to S3/CloudFront for files that have been copied to S3', 'lzaws'); ?></label>
                    <br />

                    <input type="checkbox" name="remove-local-file" value="1" id="remove-local-file" <?php echo $this->get_setting('remove-local-file') ? 'checked="checked" ' : ''; ?> />
                    <label for="remove-local-file"> <?php _e('Remove uploaded file from local filesystem once it has been copied to S3', 'lzaws'); ?></label>
                    <br />

                    <input type="checkbox" name="force-ssl" value="1" id="force-ssl" <?php echo $this->get_setting('force-ssl') ? 'checked="checked" ' : ''; ?> />
                    <label for="force-ssl"> <?php _e('Always serve files over https (SSL)', 'lzaws'); ?></label>
                    <br />

                </td>
            </tr>
            <tr valign="top">
                <td>
                    <h3><?php _e('Attachment Regeneration', 'lzaws'); ?></h3>

                    <label><?php _e('Regenerate IDs', 'lzaws'); ?></label><br />
                    <input type="text" name="regenerate_ids" id="regenerate_ids" value="<?php echo esc_attr($this->get_setting('regenerate_ids')); ?>" size="50" />
                    <p class="description"><?php _e('Leave blank to regenerate all IDs.', 'lzaws'); ?></p>
                    <button type="button" class="button" id="regenerate"><?php _e('Regenerate now >>', 'lzaws'); ?></button>
                    <p class="description"><?php _e('Regeration will delete all attachment links and regenerate based on S3 contents.', 'lzaws'); ?></p>
                    <button type="button" class="button" id="update-featured-images"><?php _e('Upate Featured Images >>', 'lzaws'); ?></button>
                    <p class="description"><?php _e('Featured images are updated as part of regeneration, but can be done separately here.', 'lzaws'); ?></p>

                </td>
            </tr>
            <tr valign="top">
                <td>
                    <button type="submit" class="button button-primary"><?php _e('Save Changes', 'amazon-web-services'); ?></button>
                </td>
            </tr>
        </table>

    </form>

</div>

