<?php

use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;

class LessonZone_AWS extends AWS_Plugin_Base {

    private $aws, $s3client;
    
    const SETTINGS_KEY = 'lessonzone_aws';

    function __construct($plugin_file_path,$aws) {

        parent::__construct($plugin_file_path);
        
        $this->aws = $aws;
       
        add_action('aws_admin_menu', array($this, 'admin_menu'));

        $this->plugin_title = __('LessonZone AWS', 'lzaws');
       
        $this->plugin_menu_title = __('LessonZone AWS', 'lzaws');
        
        add_action('wp_ajax_lzaws-create-bucket', array($this, 'ajax_create_bucket'));
        add_action('wp_ajax_lzaws-list-bucket', array($this, 'ajax_list_bucket'));
        add_action('wp_ajax_lzaws-regenerate', array($this, 'ajax_regenerate'));
        add_action('wp_ajax_lzaws-update-featured-images', array($this, 'ajax_update_featured_images'));

        add_filter('wp_get_attachment_url', array($this, 'wp_get_attachment_url'), 9, 2);
        add_filter('wp_generate_attachment_metadata', array($this, 'wp_generate_attachment_metadata'), 20, 2);
    }


  


    function get_setting($key,$default = '') {
       
       
        $settings = $this->get_settings();
        
        // Default object prefix
        if ('object-prefix' == $key && !isset($settings['object-prefix'])) {
            $uploads = wp_upload_dir();
            $parts = parse_url($uploads['baseurl']);
           
            return substr($parts['path'], 1) . '/';
        }
       //print_r($settings);
       //exit();
        return parent::get_setting($key);
    }

    function wp_generate_attachment_metadata($data, $post_id) {
        
        if (!$this->get_setting('copy-to-s3') || !$this->is_plugin_setup()) {
            return $data;
        }

        $prefix = ltrim(trailingslashit($this->get_setting('object-prefix')), '/');

        $acl = apply_filters('lzaws_upload_acl', 'public-read', $data, $post_id);

        $file_path = get_attached_file($post_id, true);

        if (!file_exists($file_path)) {
            return $data;
        }

        $file_name = basename($file_path);
        $files_to_remove = array($file_path);
        //1- change to get_client()
        $s3client = $this->aws->get_client();

        $bucket = $this->get_setting('bucket');

        //If it is uploaded to wp-content then update the $args_key
        $args_key = $prefix . $file_name;
        if(strpos($file_path, 'wp-content/uploads')){
            $args_key = 'public/' . $file_name;
        }

        $args = array(
            'Bucket' => $bucket,
            'Key' => $args_key,
            'SourceFile' => $file_path,
            //'ACL' => $acl
        );
        // If far future expiration checked (10 years)
        if ($this->get_setting('expires')) {
            $args['Expires'] = date('D, d M Y H:i:s O', time() + 315360000);
        }

        try {
            $s3client->putObject($args);
        } catch (Exception $e) {
            error_log('Error uploading ' . $file_path . ' to S3: ' . $e->getMessage());
            return $data;
        }

        update_post_meta($post_id, 'amazonS3_info', array(
            'bucket' => $bucket,
            'key' => $args_key
        ));

        $additional_images = array();

        if (isset($data['thumb']) && $data['thumb']) {
            $path = str_replace($file_name, $data['thumb'], $file_path);
            $additional_images[] = array(
                'Key' => $prefix . $data['thumb'],
                'SourceFile' => $path
            );
            $files_to_remove[] = $path;
        } elseif (!empty($data['sizes'])) {
            foreach ($data['sizes'] as $size) {
                $path = str_replace($file_name, $size['file'], $file_path);
                $additional_images[] = array(
                    'Key' => $prefix . $size['file'],
                    'SourceFile' => $path
                );
                $files_to_remove[] = $path;
            }
        }

        if ($this->get_setting('remove-local-file')) {
            $this->remove_local_files($files_to_remove);
        }

        return $data;
    }

    function remove_local_files($file_paths) {
        foreach ($file_paths as $path) {
            if (!@unlink($path)) {
                error_log('Error removing local file ' . $path);
            }
        }
    }

    function wp_get_attachment_url($url, $post_id) {
        
        $new_url = $this->get_attachment_url($post_id);
        if (false === $new_url) {
            return $url;
        }

        $new_url = apply_filters('lzaws_wp_get_attachment_url', $new_url, $post_id);

        return $new_url;
    }

    function get_attachment_s3_info($post_id) {
        return get_post_meta($post_id, 'amazonS3_info', true);
    }

    function is_plugin_setup() {
        return (bool) $this->get_setting('bucket') && !is_wp_error($this->aws->get_client());
    }

    /**
     * Generate a link to download a file from Amazon S3 using query string
     * authentication. This link is only valid for a limited amount of time.
     *
     * @param mixed $post_id Post ID of the attachment or null to use the loop
     * @param int $expires Seconds for the link to live
     */
    function get_secure_attachment_url($post_id, $expires = 900) {
        return $this->get_attachment_url($post_id, $expires);
    }

    function get_attachment_url($post_id, $expires = null) {
        if (!$this->get_setting('serve-from-s3') || !( $s3object = $this->get_attachment_s3_info($post_id) )) {
            return false;
        }

        if (is_ssl() || $this->get_setting('force-ssl')) {
            $scheme = 'https';
        } else {
            $scheme = 'http';
        }

        if (is_null($expires) && $this->get_setting('cloudfront')) {
            $domain_bucket = $this->get_setting('cloudfront');
        } elseif ($this->get_setting('virtual-host')) {
            $domain_bucket = $s3object['bucket'];
        // } elseif (is_ssl() || $this->get_setting('force-ssl')) {
        //     $domain_bucket = 's3.amazonaws.com/' . $s3object['bucket'];
        } else {
            $domain_bucket = $s3object['bucket'] . '.s3.amazonaws.com';
        }

        $url = $scheme . '://' . $domain_bucket . '/' . $s3object['key'];
	/** =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=- **/
	/** #TODO: REMOVE THIS VALUE ONCE SERVER TIME IS FIXED **/
	    $expires = 3900;
	/** =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=- **/	

      /*  if (isset($expires)) {
            try {
                $expires = time() + $expires;
                //2 change to get_client()
                $secure_url = $this->aws->get_client()->getObjectUrl($s3object['bucket'], $s3object['key'], $expires);
                $url .= substr($secure_url, strpos($secure_url, '?'));
            } catch (Exception $e) {
                return new WP_Error('exception', $e->getMessage());
            }
	    }*/
        //$url = esc_url(remove_query_arg(['AWSAccessKeyId', 'Expires', 'Signature'],$url));
	
        error_log('s3 url ' . $url);
        return apply_filters('lzaws_get_attachment_url', $url, $s3object, $post_id, $expires);
    }

    function verify_ajax_request() {
       
        if (!is_admin() || !wp_verify_nonce($_POST['_nonce'], $_POST['action'])) {
            wp_die(__('Cheatin&#8217; eh?', 'lzaws'));
        }

        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions to access this page.', 'lzaws'));
        }
    }

    function ajax_create_bucket() {
        
        $this->verify_ajax_request();

        if (!isset($_POST['bucket_name']) || !$_POST['bucket_name']) {
            wp_die(__('No bucket name provided.', 'lzaws'));
        }

        $result = $this->create_bucket($_POST['bucket_name']);
        if (is_wp_error($result)) {
            $out = array('error' => $result->get_error_message());
        } else {
            $out = array('success' => '1', '_nonce' => wp_create_nonce('lzaws-create-bucket'));
        }

        echo json_encode($out);
        exit;
    }

    function ajax_list_bucket() {
       
        $this->verify_ajax_request();

        if (!isset($_POST['bucket_name']) || !$_POST['bucket_name']) {
            wp_die(__('No bucket name provided.', 'lzaws'));
        }

        global $wpdb;
        $error = false;
        $listBucket = array();
        $bucket = $_POST['bucket_name'];
        $prefix = $_POST['prefix'];
        if (substr($prefix, -1) != '/') {
            $prefix.= '/';
        }
        try {
            //3 change to get_client()
            $bucketlist = $this->aws->get_client()->getIterator('ListObjects', array(
                'Bucket' => $bucket,
                'Prefix' => $prefix
            ));
            foreach ($bucketlist as $object) {
                $key = $object['Key'];
                $size = $object['Size'];
                // exclude folders
                if ($key != $prefix && $size > 0) {
                    $bucketObject = array();

                    $key = str_ireplace($prefix, '', $key);
                    $id = stristr($key, '/', true);
                    $postID = $wpdb->get_var("SELECT post_title FROM " . $wpdb->prefix . "postmeta join " . $wpdb->prefix . "posts on ".$wpdb->prefix."posts.ID = ".$wpdb->prefix."postmeta.post_id WHERE meta_key ='esiss_resource_id' AND meta_value='" . $id . "'");
                    if (isset($postID)) {
                        $title = $postID;
                    } else {
                        $title = 'Resource not found in database';
                    }
                    $bucketObject['file'] = $key;
                    $bucketObject['title'] = $title;
                    $listBucket[] = $bucketObject;
                }
            }
            $out = array('success' => '1', '_nonce' => wp_create_nonce('lzaws-list-bucket'));
            $out['data'] = $listBucket;
        } catch (Exception $e) {
            $error = array('error' => $e->getMessage());
        }

        if ($error) {
            echo json_encode($error);
        } else {
            echo json_encode($out);
        }
        exit;
    }

    function ajax_regenerate() {
        $this->verify_ajax_request();
       
        if (!isset($_POST['bucket_name']) || !$_POST['bucket_name']) {
            wp_die(__('No bucket name provided.', 'lzaws'));
        }

        global $wpdb;
        $error = false;
        $bucket = $_POST['bucket_name'];
        
        $prefix = $_POST['prefix'];
        $cloudfront = $_POST['cloudfront'];
       
        $out = array('success' => '1', '_nonce' => wp_create_nonce('lzaws-regenerate'), 'data' => array());
        if (substr($prefix, -1) != '/') {
            $prefix.= '/';
        }

        $query = "SELECT post_id, meta_value FROM " . $wpdb->prefix . "postmeta WHERE meta_key ='esiss_resource_id'";
        $resource_ids = $_POST['regenerate_ids'];
        if (isset($resource_ids) && !empty($resource_ids)) {
            if (!strpos($resource_ids, '%')) {
                $query .= ' AND meta_value IN (' . $resource_ids . ')';
            } else {
                $query .= ' AND meta_value LIKE "' . $resource_ids . '"';
            }
        }

        $resources = $wpdb->get_results($query);
       
        foreach ($resources as $resource) {
            // step 1 - iterate through each post, scan S3 for files for that particular resource and store them in an array
            try {
                // add resource id to prefix
                $resourceFolder = $prefix . $resource->meta_value;
                $resourceFolder.= '/';
                //4 change to get_client()
                $bucketlist = $this->aws->get_client()->getIterator('ListObjects', array(
                    'Bucket' => $bucket,
                    'Prefix' => $resourceFolder,
                    'Delimiter' => '/'
                ), array(
                    'names_only' => true
                ));
                
                // create array of all files
                $bucketObjects = array();
                foreach ($bucketlist as $object) {
                    $key = $object['Key'];
                    //$key = $object;
                    //$size = $object['Size'];
//                    error_log("file:$key");
//                  error_log(print_r($object, true));
                     // exclude main resource folders
                    
//                    if ($key != $resourceFolder && $size > 0) {
                    if ($key != $resourceFolder ) {
                        // remove the prefix and leave just the filename
                        
                        $filename = str_ireplace($resourceFolder, '', $key);
                      
                        //var_dump($filename);
                        //exit();
                        //error_log('filename: ' . $filename);
                        // exclude sub folders
                        if (!strpos($filename, '/')) {
                            
                            $bucketObjects[] = $filename;
                            // ensure any image have public-read access
                            $info = pathinfo($filename);
                           /* if (strtolower($info['extension']) === "jpg") {
                              $args = array(
                                    'Bucket' => $bucket,
                                    'Key' => $key,
                                    //'ACL' => 'public-read'
                                );
                                /* try {
                                    //5 change 2
                                    $this->aws->get_client()->putObjectAcl([
                                        'Bucket' => $bucket,
                                        'Key' => $key,
                                        'ACL' => 'public-read'
                                    ]);
                                } catch (Exception $e) {
                                    error_log('Error updating ACL for ' . $key . $e->getMessage());
                                }
                            }*/
                        }
                    }
                }
                // step 2 - get the post attachment details and store them in an array
//                $args = array(
//                    'post_type' => 'attachment',
//                    'post_parent' => $resource->post_id,
//                    'posts_per_page' => -1
//                );
                $postObjects = array();
                $postDetails = array();
//                $attachments = get_posts($args);
                $query = "SELECT ID, guid FROM " . $wpdb->prefix . "posts WHERE post_type = 'attachment' AND post_parent = " . $resource->post_id;
                $attachments = $wpdb->get_results($query);
                
                if ($attachments) {
                    foreach ($attachments as $attachment) {
                        $guid = basename($attachment->guid);
                        $postObjects[] = $guid;
                        // also store as key in associative
                        $postDetails[$guid] = $attachment->ID;
                    }
                }
                $attachments = null;
                //error_log("S3 objects: " . print_r($bucketObjects, true));
                //error_log("attachment objects: " . print_r($postObjects, true));
                //error_log("post details: " . print_r($postDetails, true));
                // step 3 - compare postObjects with bucketObjects to find attachments which need to be deleted
                $toDelete = array_udiff($postObjects, $bucketObjects, 'strcasecmp');
                
                error_log("to delete: " . print_r($toDelete, true));
                foreach ($toDelete as $delete) {
                    $id = $postDetails[$delete];
                    // force deletion of attachment, do not keep in trash
                    wp_delete_attachment($id, true);
                    array_push($out['data'], array('file' => $id, 'status' => 'deleted'));
                }
                $toDelete = null;
                $postDetails = null;
                // step 4 - compare bucketObjects with postObjects to find new attachments which need to be created
                $toCreate = array_udiff($bucketObjects, $postObjects, 'strcasecmp');
                error_log("to create: " . print_r($toCreate, true));
                foreach ($toCreate as $create) {
                    $wp_filetype = wp_check_filetype($create);
                    $info = pathinfo($create);
                    if (is_ssl() || $this->get_setting('force-ssl')) {
                        $scheme = 'https';
                    } else {
                        $scheme = 'http';
                    }
                    $url = $scheme . '://' . $cloudfront . '/' . $resourceFolder . $create;
                   
                    $attachment = array(
                        'guid' => $url,
                        'post_mime_type' => $wp_filetype['type'],
                        'post_title' => $info['filename'],
                        'post_content' => '',
                        'post_status' => 'inherit'
                    );
                    //print_r($attachment);
                    //exit();
                    $attach_id = wp_insert_attachment($attachment, false, $resource->post_id);
                    
                    if (isset($attach_id)) {
                        array_push($out['data'], array('file' => $attach_id, 'status' => 'created'));
                        // add S3 specific meta data on attachment
                        update_post_meta($attach_id, 'amazonS3_info', array(
                            'bucket' => $bucket,
                            'key' => $resourceFolder . $create
                        ));
                        // add post thumbnail (featured image) meta data on post
                        if ($wp_filetype['type'] == 'image/jpeg') {
                            // encode URL
                            $parsed_url = parse_url($url);
                            //print_r($parsed_url);
                            //exit();
                            $newURL = '';
                            foreach ($parsed_url as $key => $value) {
                                if ($key == 'scheme') {
                                    $value = $value . '://';
                                }
                                if ($key == 'port') {
                                    $value = ':' . $value;
                                }
                                if ($key == 'query') {
                                    $value = '?' . $value;
                                }
                                if ($key == 'path') {
                                    $explodePath = explode("/", $value);
                                    foreach ($explodePath as $part => $val) {
                                        $encodedname = urlencode($val);
                                        $val = $encodedname;
                                        $explodePath[$part] = $val;
                                    }
                                    $implodedPath = implode('/', $explodePath);
                                    $value = $implodedPath;
                                }
                                $newURL .= $value;
                            }
                            // get image dimensions
                            list($width, $height) = getimagesize($newURL);
                            update_post_meta($attach_id, 'size_info', array(
                                'width' => $width,
                                'height' => $height
                            ));
                            add_post_meta($resource->post_id, 'post_image', $url, true);
                            add_post_meta($resource->post_id, '_thumbnail_id', $attach_id, true);
                        }
                    } else {
                        array_push($out['data'], array('file' => $create, 'status' => 'creation failed'));
                       
                    }
                }
            } catch (Exception $e) {
                $error = array('error' => $e->getMessage());
            }
        }
       
        // step 5 update post featured image
        $this->update_featured_images($resource_ids);
        //error_log(print_r($out, true));

        if ($error) {
            echo json_encode($error);
        } else {
            
            echo json_encode($out);
        }
        exit;
    }

    function ajax_update_featured_images() {
        $this->verify_ajax_request();
        $out = array('success' => '1', '_nonce' => wp_create_nonce('lzaws-update-featured-images'), 'data' => array());
        
        $this->update_featured_images();
        
        array_push($out['data'], array('file' => 'featured images', 'status' => 'updated'));
        echo json_encode($out);
        
        exit;
    }
    
    function update_featured_images ($resource_ids = null) {
    
        error_log('resource_ids: ' . $resource_ids);
        global $wpdb;
        // catch all mutiple page resources
        $additional_query = '';
        if ($resource_ids != null) {
            if (!strpos($resource_ids, '%')) {
                $additional_query = ' AND post_title LIKE "' . $resource_ids . '%"';
            } else {
                $additional_query = ' AND post_title LIKE "' . $resource_ids . '"';
            }
        }
        $query = "SELECT ID, post_title, guid, post_parent FROM " . $wpdb->prefix . "posts WHERE post_type = 'attachment' AND post_parent > 0 AND SUBSTRING(post_title, -2) = '01' AND post_mime_type='image/jpeg'" . $additional_query . " AND post_title not like '%_CA01%' AND post_title not like '%_BM01%' AND post_title not like '%_LP01%' and post_title not like '%_Booklet01%' and post_title not like '%_WBooklet01%' and post_title not like '%_DC01%' and post_title not like '%_WC01%' and post_title not like '%_RR01%' and post_title not like '%_SS01%' and post_title not like '%_VR01%' order by post_title DESC";
        $attachments = $wpdb->get_results($query);
        $resource = '';
        if ($attachments) {
            foreach ($attachments as $attachment) {
                $resource_check = substr($attachment->post_title, 0, 6);
                //error_log('updating featured image for: ' . $attachment->post_title . ", resource_check: " . $resource_check);
                //if ($resource !== $resource_check) {
                //    error_log('new resource found: ' . $resource_check);
                //}
                if ($resource !== $resource_check && isLessonZone_postGallery($attachment->post_title)) {
                    delete_post_meta($attachment->post_parent, 'post_image');
                    delete_post_meta($attachment->post_parent, '_thumbnail_id');
                    update_post_meta($attachment->post_parent, 'post_image', $attachment->guid, true);
                    update_post_meta($attachment->post_parent, '_thumbnail_id', $attachment->ID, true);
                    error_log('featured image updated for: ' . $attachment->post_parent . " - " . $attachment->guid);
                    // update featured image size
                    $wp_filetype = wp_check_filetype($attachment->guid);
                    if ($wp_filetype['type'] == 'image/jpeg') {
                        // encode URL
                        $parsed_url = parse_url($attachment->guid);
                        $newURL = '';
                        foreach ($parsed_url as $key => $value) {
                            if ($key == 'scheme') {
                                $value = $value . '://';
                            }
                            if ($key == 'port') {
                                $value = ':' . $value;
                            }
                            if ($key == 'query') {
                                $value = '?' . $value;
                            }
                            if ($key == 'path') {
                                $explodePath = explode("/", $value);
                                foreach ($explodePath as $part => $val) {
                                    $encodedname = urlencode($val);
                                    $val = $encodedname;
                                    $explodePath[$part] = $val;
                                }
                                $implodedPath = implode('/', $explodePath);
                                $value = $implodedPath;
                            }
                            $newURL .= $value;
                        }
                        // get image dimensions
                        list($width, $height) = getimagesize($newURL);
                        update_post_meta($attachment->ID, 'size_info', array(
                            'width' => $width,
                            'height' => $height
                        ));
                        error_log('image size updated for: ' . $attachment->guid . ' to: ' . $width . 'x' . $height);
                    }
                    
                    $resource = $resource_check;
                }
            }
        }
        
        return true;
        
    }
    
    function create_bucket($bucket_name) {
        
        try {
            //6 change to get_client()
            $this->aws->get_client()->createBucket(array('Bucket' => $bucket_name));
        } catch (Exception $e) {
            return new WP_Error('exception', $e->getMessage());
        }

        return true;
    }

    function admin_menu($aws) {
        $hook_suffix = $aws->add_page($this->plugin_title, $this->plugin_menu_title, 'manage_options', $this->plugin_slug, array($this, 'render_page'));

        

        add_action('load-' . $hook_suffix, array($this, 'plugin_load'));
    }

    function get_s3client() {
       
        if (is_null($this->s3client)) {
            $this->s3client = $this->aws->get_client();
        }
        
        return $this->s3client;
    }

    function get_buckets() {
       
       
        $result = $this->aws->get_client()->listBuckets();
       
        return $result['Buckets'];
    }

    function plugin_load() {
        $src = plugins_url('assets/css/styles.css', $this->plugin_file_path);
        wp_enqueue_style('lzaws-styles', $src, array(), $this->get_installed_version());
        //$src = plugins_url( 'assets/css/global.css', $this->plugin_file_path );
        //wp_enqueue_style( 'aws-global-styles', $src, array(), $this->get_installed_version() );

        $suffix = (defined('SCRIPT_DEBUG') && SCRIPT_DEBUG) ? '' : '.min';

        $src = plugins_url('assets/js/script' . $suffix . '.js', $this->plugin_file_path);
        wp_enqueue_script('lzaws-script', $src, array('jquery'), $this->get_installed_version(), true);

        wp_localize_script('lzaws-script', 'lzaws_i18n', array(
            'create_bucket_prompt' => __('Bucket Name:', 'lzaws'),
            'create_bucket_error' => __('Error creating bucket: ', 'lzaws'),
            'create_bucket_nonce' => wp_create_nonce('lzaws-create-bucket'),
            'list_bucket_error' => __('Error listing bucket: ', 'lzaws'),
            'list_bucket_nonce' => wp_create_nonce('lzaws-list-bucket'),
            'regenerate_error' => __('Error regenerating attachment links: ', 'lzaws'),
            'regenerate_nonce' => wp_create_nonce('lzaws-regenerate'),
            'update_featured_images_error' => __('Error updating featured images: ', 'lzaws'),
            'update_featured_images_nonce' => wp_create_nonce('lzaws-update-featured-images')
        ));

        $this->handle_post_request();
    }

    function handle_post_request() {
        if (empty($_POST['action']) || 'save' != $_POST['action']) {
            return;
        }

        if (empty($_POST['_wpnonce']) || !wp_verify_nonce($_POST['_wpnonce'], 'lzaws-save-settings')) {
            die(__("Cheatin' eh?", 'amazon-web-services'));
        }

        $this->set_settings(array());

        $post_vars = array('bucket', 'expires', 'virtual-host', 'permissions', 'cloudfront', 'object-prefix', 'copy-to-s3', 'serve-from-s3', 'remove-local-file', 'force-ssl');
        foreach ($post_vars as $var) {
            if (!isset($_POST[$var])) {
                continue;
            }

            $this->set_setting($var, $_POST[$var]);
        }

        $this->save_settings();
     

        wp_redirect('admin.php?page=' . $this->plugin_slug . '&updated=1');
        exit;
    }

    function render_page() {
        $this->aws->render_view('header', array('page_title' => $this->plugin_title));

        $aws_client = $this->aws->get_client();
    
        if (is_wp_error($aws_client)) {
            $this->render_view('error', array('error' => $aws_client));
        } else {
            $this->render_view('settings');
        }

        $this->aws->render_view('footer');
    }

}
