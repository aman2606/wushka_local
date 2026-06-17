<?php
if (! defined('ABSPATH')) {
    exit;
} // Exit if accessed directly
//exits when file is load directly
if (! function_exists('add_action')) {
    echo "This page cannot be called directly.";
    exit;
}
require_once plugin_dir_path(__FILE__) . '../stamp/library/SetaPDF/Autoload.php';

class Stamp_Support_Materials
{
    private $o_user;
    private $a_options;
    private $s_type;
    private $b_display;
    private $s_file_name;
    private $s_file_url;
    private $o_post;

    private $b_test;

    public function __construct()
    {
        $this->a_options = $this->load_options();
        $this->s_type      = NULL;
        $this->b_display   = TRUE;
        $this->s_file_name = 'example.pdf';
        $this->s_file_url  = NULL;
        $this->o_post      = NULL;

        #TODO: Remove After Testing is Completed
        $this->b_test = FALSE;
        $this->log('----- PDF Stamp -----');
    }

    /* ----- Store Current Stamp Options ----- */
    private function load_options()
    {
        $x_options = get_option('pdf_stamp');

        $a_options = NULL;
        if ($x_options !== FALSE) {
            $a_options = $x_options;
        }

        return $a_options;
    }


    public function validate()
    {
        if (! is_user_logged_in() || (! current_user_can('teacher') && ! current_user_can('school'))) {
            $this->log('Error: Current User is Invalid');

            return FALSE;
        }

        //Store Type
        $s_type    = stripcslashes(filter_input(INPUT_POST, 'type'));
        $s_display = stripcslashes(filter_input(INPUT_POST, 'display'));
        $i_post    = stripcslashes(filter_input(INPUT_POST, 'post'));

        //Store Stamp Type
        if (! isset($s_type) || empty($s_type)) {
            $this->log('Error: stamp type failed validation');

            return FALSE;
        }

        //Store Display Setting
        if (! isset($s_display) || empty($s_display)) {
            $this->log('Error: display setting failed validation');

            return FALSE;
        }

        //By Default, New PDFs will be displayed in browser.
        //Otherwise, download pdf file.
        $b_display = TRUE;
        if ($s_display == 'download') {
            $b_display = FALSE;
        }

        //Validate Post
        if (! isset($i_post) || empty($i_post)) {
            $this->log('Error: Post ID failed validation');

            return FALSE;
        }

        //Get Post
        $o_post = get_post($i_post);
        if (! isset($o_post)) {
            $this->log('Error: Post of passed ID does not exist');

            return FALSE;
        }

        //Validation Past, Store Parameters
        global $current_user;
        $this->o_user    = $current_user;
        $this->s_type    = $s_type;
        $this->b_display = $b_display;
        $this->o_post    = $o_post;

        return TRUE;
    }

    public function get_data()
    {
        #TODO: What Other Data do i need for Support Materials

        return TRUE;
    }

    public function stamp()
    {
        $this->log('----- Begin Support Material PDF -----');
        /* ----- STEP 2 - FILE PATH ----- */
        $s_file_url = $this->generate_file_url($this->o_post->ID);
        if ($this->parse_url($s_file_url) === FALSE) {
            $this->log('Failed to Load PDF URL. Abort Function.');

            return FALSE;
        }

        $this->s_file_name = trim(ucwords($this->o_post->post_title)) . '.pdf';

        $s_content = 'attachment';
        if ($this->b_display) {
            $s_content = 'inline';
        }

        $filename = trim(str_replace(' ', '', $s_file_url));
        $imgInfo  = get_headers($filename, 1);
        header('Content-Type: ' . $imgInfo['Content-Type']);
        header('Content-Disposition: ' . $s_content . '; filename="' . $this->s_file_name . '"');
        header('Content-Transfer-Encoding: binary');
        header('Content-Length: ' . $imgInfo['Content-Length']);
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        ob_clean();
        flush();
        readfile($filename);
        $this->log('----- END Support Material PDF -----');
    }

    private function generate_file_url($i_post = NULL)
    {
        if (! isset($i_post)) {
            return NULL;
        }

        $s3Info = get_post_meta($i_post, 'amazonS3_info', true);

        if ($s3Info) {
            return CDN_URL . '/' . $s3Info['key'];
        } else {
            $aPost = get_post($i_post);

            $pdfFilePath = get_attached_file($aPost->ID);
            $filename = basename($pdfFilePath);  // e.g. "report.pdf"

            // Make sure file exists
            if (!file_exists($pdfFilePath)) {
                http_response_code(404);
                die("File not found");
            }

            $s_content = 'attachment';
            if ($this->b_display) {
                $s_content = 'inline';
            }

            // Important: no output before headers (no echo, no whitespace, no BOM)
            header('Content-Type: application/pdf');
            header('Content-Disposition: ' . $s_content . '; filename="' . $filename . '"');
            header('Content-Length: ' . filesize($pdfFilePath));
            header('Cache-Control: private, max-age=0, must-revalidate');
            header('Pragma: public');

            // Send the file content
            readfile($pdfFilePath);
            exit;
        }
    }

    private function parse_url($s_url = NULL)
    {
        if (! isset($s_url)) {
            return FALSE;
        }
        $a_new_url   = [];
        $s_parse_url = parse_url($s_url);
        foreach ($s_parse_url as $key => $value) {
            if ($key == 'scheme') {
                $value = $value . '://';
            } else if ($key == 'port') {
                $value = ':' . $value;
            } else if ($key == 'query') {
                $value = '?' . $value;
            } else if ($key == 'path') {
                $explode_path = explode("/", $value);
                foreach ($explode_path as $part => $val) {
                    $explode_path[$part] = urlencode($val);
                }
                $value = implode('/', $explode_path);
            }
            $a_new_url[] = $value;
        }

        $x_file_url = implode('', $a_new_url);
        if (isset($x_file_url)) {
            $this->s_file_url = file_get_contents($x_file_url);

            return TRUE;
        }

        return FALSE;
    }

    private function log($s_text = NULL)
    {
        if ($this->b_test && isset($s_text)) {
            error_log($s_text);
        }

        return TRUE;
    }
}

/* ----- EOF ------ */
