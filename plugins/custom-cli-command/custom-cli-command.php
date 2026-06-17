<?php

/**
 * Plugin Name: Custom WP-CLI Command
 * Description: A simple plugin to demonstrate custom WP-CLI commands.
 * Version: 1.0
 * Author: Your Name
 */

if (! defined('WP_CLI') || ! WP_CLI) {
    return;
}

class My_Sync_Command
{
    /**
     * This runs when you type: wp sync
     */

    public static $HAVING_ORDER = [300533]; // B Sheet
    //public static $HAVING_ORDER = [300625, 300626, 300637, 300638, 300649, 300650, 300661, 300662, 300673, 300674]; // R Sheet

    public static $ONLY_PHASES = ['2c2-letter-sounds', '3c2-phonics', '4c2-blends', '5c2-vowel-sounds', '6c2-spelling']; // B Sheet
    //public static $ONLY_PHASES = ['2c3-letter-sounds', '3c3-phonics', '4c3-blends', '5c3-vowel-sounds', '6c3-spelling']; // R Sheet

    //public static $FOLDER_BASE_URL = 'C:/Projects/ebook_assets'; // Local
    public static $FOLDER_BASE_URL = '/home/ubuntu/ebook_assets'; // Live
   // public static $FOLDER_BASE_URL = '/home/aman-sharma/ebook_assets_1'; // Dev Instance

    public static $CSV_FILE = 'Wushka-Batch-2-B.csv';
    //public static $CSV_FILE = 'Wushka-Batch-2-R.csv'; // R Sheet
    // public static $CSV_FILE = 'Fix_Its_and_Phase_55_Metadata_con.csv';


    public static array $mapping = [
        'phases' => [
            '2c2-letter-sounds' => [
                'Fiction' => [
                    'phase_folder' => 'Beanies Set 2/Hi Lo Set 2 Phase 2 BB153 12BOOKS',
                    'cover_folder' => 'Coloured Front Cover BB153',
                    'support_materials' => [
                        'BM' => [
                            'folder' => 'Support Materials BB153/Blackline Masters + icons',
                            'pdf_code' => 'E02_BM',
                            'image_code' => 'E02_BM01'
                        ],
                        'LP' => [
                            'folder' => 'Support Materials BB153/Lesson Plans + icons',
                            'pdf_code' => 'E02_LP',
                            'image_code' => 'E02_LP01'
                        ],
                        'BWR' => [ // for pdf
                            'folder' => 'Support Materials BB153/Black White reader + icons',
                            'pdf_code' => 'E02_Booklet',
                        ],
                        'BWRI' => [ // for icons
                            'folder' => 'Support Materials BB153/Black White reader + icons',
                            'image_code' => 'E02_Booklet01',
                        ],
                        'BWWR' => [ // for pdf
                            'folder' => 'Support Materials BB153/BW wordless readers',
                            'pdf_code' => 'E02_WBooklet',
                        ],
                        'BWWRI' => [ // for icons
                            'folder' => 'Support Materials BB153/BW wordless readers',
                            'image_code' => 'E02_WBooklet01',
                        ],
                        // 'SS' => [
                        //     'folder' => 'Support Materials/Sequence Strips BB142',
                        //     'pdf_code' => 'E02_SS',
                        //     'image_code' => 'E02_SS01'
                        // ],
                        'WC' => [

                            'folder' => 'Support Materials BB153/Word Cards +icons',
                            'pdf_code' => 'E02_WC',
                            'image_code' => 'E02_WC01'

                        ]
                    ],
                    'code' => 'E02_0101',

                ],
                'Non-Fiction' => []

            ],
            '3c2-phonics' => [
                'Fiction' => [
                    'phase_folder' => 'Beanies Set 2/Hi Lo Set 2 Phase 3 BB154 12BOOKS',
                    'cover_folder' => 'Coloured Front Cover BB154',
                    'support_materials' => [
                        'BM' => [
                            'folder' => 'Support Materials BB154/Blackline Masters BB154',
                            'pdf_code' => 'E02_BM',
                            'image_code' => 'E02_BM01'
                        ],
                        'LP' => [
                            'folder' => 'Support Materials BB154/Lesson Plans + iconsBB154',
                            'pdf_code' => 'E02_LP',
                            'image_code' => 'E02_LP01'
                        ],
                        'BWR' => [ // for pdf
                            'folder' => 'Support Materials BB154/Black White reader BB154',
                            'pdf_code' => 'E02_Booklet',
                        ],
                        'BWRI' => [ // for icons
                            'folder' => 'Support Materials BB154/Black White reader BB154',
                            'image_code' => 'E02_Booklet01',
                        ],
                        'BWWR' => [ // for pdf
                            'folder' => 'Support Materials BB154/BW wordless BB154',
                            'pdf_code' => 'E02_WBooklet',
                        ],
                        'BWWRI' => [ // for icons
                            'folder' => 'Support Materials BB154/BW wordless BB154',
                            'image_code' => 'E02_WBooklet01',
                        ],
                        'SS' => [
                            'folder' => 'Support Materials BB154/Sequence Strips+iconsBB154',
                            'pdf_code' => 'E02_SS',
                            'image_code' => 'E02_SS01'
                        ],
                        // 'WC' => [

                        //     'folder' => 'Support Materials BB154/Word Cards +icons',
                        //     'pdf_code' => 'E02_WC',
                        //     'image_code' => 'E02_WC01'

                        // ]
                    ],
                    'code' => 'E02_0101',

                ],
                'Non-Fiction' => []

            ],
            '4c2-blends' => [
                'Fiction' => [
                    'phase_folder' => 'Beanies Set 2/Hi Lo Set 2 Phase 4 BB155 12BOOKS',
                    'cover_folder' => 'Coloured Front Cover BB155',
                    'support_materials' => [
                        'BM' => [
                            'folder' => 'Support Materials BB155/BlacklinMasters+iconsBB155',
                            'pdf_code' => 'E02_BM',
                            'image_code' => 'E02_BM01'
                        ],
                        'LP' => [
                            'folder' => 'Support Materials BB155/Lesson Plans + iconsBB155',
                            'pdf_code' => 'E02_LP',
                            'image_code' => 'E02_LP01'
                        ],
                        'BWR' => [ // for pdf
                            'folder' => 'Support Materials BB155/BlackWhiteread+iconsBB155',
                            'pdf_code' => 'E02_Booklet',
                        ],
                        'BWRI' => [ // for icons
                            'folder' => 'Support Materials BB155/BlackWhiteread+iconsBB155',
                            'image_code' => 'E02_Booklet01',
                        ],
                        'BWWR' => [ // for pdf
                            'folder' => 'Support Materials BB155/BW wordless readerBB155',
                            'pdf_code' => 'E02_WBooklet',
                        ],
                        'BWWRI' => [ // for icons
                            'folder' => 'Support Materials BB155/BW wordless readerBB155',
                            'image_code' => 'E02_WBooklet01',
                        ],
                        'SS' => [
                            'folder' => 'Support Materials BB155/Sequence Strips+iconsBB155',
                            'pdf_code' => 'E02_SS',
                            'image_code' => 'E02_SS01'
                        ],
                        // 'WC' => [

                        //     'folder' => 'Support Materials BB155/Word Cards +icons',
                        //     'pdf_code' => 'E02_WC',
                        //     'image_code' => 'E02_WC01'

                        // ]
                    ],
                    'code' => 'E02_0101',

                ],
                'Non-Fiction' => []

            ],
            '5c2-vowel-sounds' => [
                'Fiction' => [
                    'phase_folder' => 'Beanies Set 2/Hi Lo Set 2 Phase 5 BB156 12BOOKS',
                    'cover_folder' => 'Coloured Front Cover BB156',
                    'support_materials' => [
                        'BM' => [
                            'folder' => 'Support Materials BB156/BlackMasters+iconsBB156',
                            'pdf_code' => 'E02_BM',
                            'image_code' => 'E02_BM01'
                        ],
                        'LP' => [
                            'folder' => 'Support Materials BB156/Lesson Plans + iconsBB156',
                            'pdf_code' => 'E02_LP',
                            'image_code' => 'E02_LP01'
                        ],
                        'BWR' => [ // for pdf
                            'folder' => 'Support Materials BB156/BWhite reader +icons BB156',
                            'pdf_code' => 'E02_Booklet',
                        ],
                        'BWRI' => [ // for icons
                            'folder' => 'Support Materials BB156/BWhite reader +icons BB156',
                            'image_code' => 'E02_Booklet01',
                        ],
                        'BWWR' => [ // for pdf
                            'folder' => 'Support Materials BB156/BW wordless readersBB156',
                            'pdf_code' => 'E02_WBooklet',
                        ],
                        'BWWRI' => [ // for icons
                            'folder' => 'Support Materials BB156/BW wordless readersBB156',
                            'image_code' => 'E02_WBooklet01',
                        ],
                        'SS' => [
                            'folder' => 'Support Materials BB156/Sequence Strips+iconsBB156',
                            'pdf_code' => 'E02_SS',
                            'image_code' => 'E02_SS01'
                        ],
                        // 'WC' => [

                        //     'folder' => 'Support Materials BB156/Word Cards +icons',
                        //     'pdf_code' => 'E02_WC',
                        //     'image_code' => 'E02_WC01'

                        // ]
                    ],
                    'code' => 'E02_0101',

                ],
                'Non-Fiction' => []

            ],
            '6c2-spelling' => [
                'Fiction' => [
                    'phase_folder' => 'Beanies Set 2/Hi Lo Set 2 Phase 6 BB157 12BOOKS',
                    'cover_folder' => 'Coloured Front Cover BB157',
                    'support_materials' => [
                        'BM' => [
                            'folder' => 'Support Materials BB157/BlackMasters + iconsBB157',
                            'pdf_code' => 'E02_BM',
                            'image_code' => 'E02_BM01'
                        ],
                        'LP' => [
                            'folder' => 'Support Materials BB157/LessonPlans+iconsBB157',
                            'pdf_code' => 'E02_LP',
                            'image_code' => 'E02_LP01'
                        ],
                        'BWR' => [ // for pdf
                            'folder' => 'Support Materials BB157/BlackW reader +icons BB157',
                            'pdf_code' => 'E02_Booklet',
                        ],
                        'BWRI' => [ // for icons
                            'folder' => 'Support Materials BB157/BlackW reader +icons BB157',
                            'image_code' => 'E02_Booklet01',
                        ],
                        'BWWR' => [ // for pdf
                            'folder' => 'Support Materials BB157/BW wordless readersBB157',
                            'pdf_code' => 'E02_WBooklet',
                        ],
                        'BWWRI' => [ // for icons
                            'folder' => 'Support Materials BB157/BW wordless readersBB157',
                            'image_code' => 'E02_WBooklet01',
                        ],
                        'SS' => [
                            'folder' => 'Support Materials BB157/Sequence Strips+iconsBB157',
                            'pdf_code' => 'E02_SS',
                            'image_code' => 'E02_SS01'
                        ],
                        // 'WC' => [

                        //     'folder' => 'Support Materials BB156/Word Cards +icons',
                        //     'pdf_code' => 'E02_WC',
                        //     'image_code' => 'E02_WC01'

                        // ]
                    ],
                    'code' => 'E02_0101',

                ],
                'Non-Fiction' => []

            ], // Real World Starting
            '2c3-letter-sounds' => [
                'Non-Fiction' => [
                    'phase_folder' => 'Real World/Phase 2 Real World BB170 12 BOOKS',
                    'cover_folder' => 'Coloured Front Covers BB170',
                    'support_materials' => [
                        'BM' => [
                            'folder' => 'Support Materials BB170/Blackline Masters +icons',
                            'pdf_code' => 'E02_BM',
                            'image_code' => 'E02_BM01'
                        ],
                        'LP' => [
                            'folder' => 'Support Materials BB170/Lessons Plans + icons',
                            'pdf_code' => 'E02_LP',
                            'image_code' => 'E02_LP01'
                        ],
                        'BWR' => [ // for pdf
                            'folder' => 'Support Materials BB170/Black White Reader + icons',
                            'pdf_code' => 'E02_Booklet',
                        ],
                        'BWRI' => [ // for icons
                            'folder' => 'Support Materials BB170/Black White Reader + icons',
                            'image_code' => 'E02_Booklet01',
                        ],
                        'BWWR' => [ // for pdf
                            'folder' => 'Support Materials BB170/BW wordless readers',
                            'pdf_code' => 'E02_WBooklet',
                        ],
                        'BWWRI' => [ // for icons
                            'folder' => 'Support Materials BB170/BW wordless readers',
                            'image_code' => 'E02_WBooklet01',
                        ],
                        // 'SS' => [
                        //     'folder' => 'Support Materials/Sequence Strips BB143',
                        //     'pdf_code' => 'E02_SS',
                        //     'image_code' => 'E02_SS01'
                        // ],
                        'WC' => [

                            'folder' => 'Support Materials BB170/Word Cards + icons',
                            'pdf_code' => 'E02_WC',
                            'image_code' => 'E02_WC01'

                        ]
                    ],
                    'code' => 'E02_0101'

                ],
                'Fiction' => []

            ],
            '3c3-phonics' => [
                'Non-Fiction' => [
                    'phase_folder' => 'Real World/Phase 3 Real World BB171 12 BOOKS',
                    'cover_folder' => 'Coloured Front Covers BB171',
                    'support_materials' => [
                        'BM' => [
                            'folder' => 'Support Materials BB171/Blackline Masters +icons',
                            'pdf_code' => 'E02_BM',
                            'image_code' => 'E02_BM01'
                        ],
                        'LP' => [
                            'folder' => 'Support Materials BB171/Lessons Plans + icons',
                            'pdf_code' => 'E02_LP',
                            'image_code' => 'E02_LP01'
                        ],
                        'BWR' => [ // for pdf
                            'folder' => 'Support Materials BB171/Black White Reader + icons',
                            'pdf_code' => 'E02_Booklet',
                        ],
                        'BWRI' => [ // for icons
                            'folder' => 'Support Materials BB171/Black White Reader + icons',
                            'image_code' => 'E02_Booklet01',
                        ],
                        'BWWR' => [ // for pdf
                            'folder' => 'Support Materials BB171/BW wordless readers',
                            'pdf_code' => 'E02_WBooklet',
                        ],
                        'BWWRI' => [ // for icons
                            'folder' => 'Support Materials BB171/BW wordless readers',
                            'image_code' => 'E02_WBooklet01',
                        ],
                        // 'SS' => [
                        //     'folder' => 'Support Materials/Sequence Strips BB143',
                        //     'pdf_code' => 'E02_SS',
                        //     'image_code' => 'E02_SS01'
                        // ],
                        'WC' => [

                            'folder' => 'Support Materials BB171/Word Cards SS + icons',
                            'pdf_code' => 'E02_WC',
                            'image_code' => 'E02_WC01'

                        ]
                    ],
                    'code' => 'E02_0101'

                ],
                'Fiction' => []

            ],
            '4c3-blends' => [
                'Non-Fiction' => [
                    'phase_folder' => 'Real World/Phase 4 Real World BB172 12 BOOKS',
                    'cover_folder' => 'Coloured Front Covers BB172',
                    'support_materials' => [
                        'BM' => [
                            'folder' => 'Support Materials BB172/Blackline Masters +icons',
                            'pdf_code' => 'E02_BM',
                            'image_code' => 'E02_BM01'
                        ],
                        'LP' => [
                            'folder' => 'Support Materials BB172/Lessons Plans + icons',
                            'pdf_code' => 'E02_LP',
                            'image_code' => 'E02_LP01'
                        ],
                        'BWR' => [ // for pdf
                            'folder' => 'Support Materials BB172/Black White Reader + icons',
                            'pdf_code' => 'E02_Booklet',
                        ],
                        'BWRI' => [ // for icons
                            'folder' => 'Support Materials BB172/Black White Reader + icons',
                            'image_code' => 'E02_Booklet01',
                        ],
                        'BWWR' => [ // for pdf
                            'folder' => 'Support Materials BB172/BW wordless readers',
                            'pdf_code' => 'E02_WBooklet',
                        ],
                        'BWWRI' => [ // for icons
                            'folder' => 'Support Materials BB172/BW wordless readers',
                            'image_code' => 'E02_WBooklet01',
                        ],
                        'SS' => [
                            'folder' => 'Support Materials BB172/Sequence Strips + icons',
                            'pdf_code' => 'E02_SS',
                            'image_code' => 'E02_SS01'
                        ],
                        // 'WC' => [

                        //     'folder' => 'Support Materials BB170/Word Cards + icons',
                        //     'pdf_code' => 'E02_WC',
                        //     'image_code' => 'E02_WC01'

                        // ]
                    ],
                    'code' => 'E02_0101'

                ],
                'Fiction' => []

            ],
            '5c3-vowel-sounds' => [
                'Non-Fiction' => [
                    'phase_folder' => 'Real World/Phase 5 Real World BB173 12 BOOKS',
                    'cover_folder' => 'Coloured Front Covers BB173',
                    'support_materials' => [
                        'BM' => [
                            'folder' => 'Support Materials BB173/Blackline Masters +icons',
                            'pdf_code' => 'E02_BM',
                            'image_code' => 'E02_BM01'
                        ],
                        'LP' => [
                            'folder' => 'Support Materials BB173/Lessons Plans + icons',
                            'pdf_code' => 'E02_LP',
                            'image_code' => 'E02_LP01'
                        ],
                        'BWR' => [ // for pdf
                            'folder' => 'Support Materials BB173/Black White Reader + icons',
                            'pdf_code' => 'E02_Booklet',
                        ],
                        'BWRI' => [ // for icons
                            'folder' => 'Support Materials BB173/Black White Reader + icons',
                            'image_code' => 'E02_Booklet01',
                        ],
                        'BWWR' => [ // for pdf
                            'folder' => 'Support Materials BB173/BW wordless readers',
                            'pdf_code' => 'E02_WBooklet',
                        ],
                        'BWWRI' => [ // for icons
                            'folder' => 'Support Materials BB173/BW wordless readers',
                            'image_code' => 'E02_WBooklet01',
                        ],
                        'SS' => [
                            'folder' => 'Support Materials BB173/Sequence Strips + icons',
                            'pdf_code' => 'E02_SS',
                            'image_code' => 'E02_SS01'
                        ],
                        // 'WC' => [

                        //     'folder' => 'Support Materials BB170/Word Cards + icons',
                        //     'pdf_code' => 'E02_WC',
                        //     'image_code' => 'E02_WC01'

                        // ]
                    ],
                    'code' => 'E02_0101'

                ],
                'Fiction' => []

            ],
            '6c3-spelling' => [
                'Non-Fiction' => [
                    'phase_folder' => 'Real World/Phase 6 Real World BB174 12 BOOKS',
                    'cover_folder' => 'Coloured Front Covers BB174',
                    'support_materials' => [
                        'BM' => [
                            'folder' => 'Support Materials BB174/Blackline Masters +icons',
                            'pdf_code' => 'E02_BM',
                            'image_code' => 'E02_BM01'
                        ],
                        'LP' => [
                            'folder' => 'Support Materials BB174/Lessons Plans + icons',
                            'pdf_code' => 'E02_LP',
                            'image_code' => 'E02_LP01'
                        ],
                        'BWR' => [ // for pdf
                            'folder' => 'Support Materials BB174/Black White Reader + icons',
                            'pdf_code' => 'E02_Booklet',
                        ],
                        'BWRI' => [ // for icons
                            'folder' => 'Support Materials BB174/Black White Reader + icons',
                            'image_code' => 'E02_Booklet01',
                        ],
                        'BWWR' => [ // for pdf
                            'folder' => 'Support Materials BB174/BW wordless readers',
                            'pdf_code' => 'E02_WBooklet',
                        ],
                        'BWWRI' => [ // for icons
                            'folder' => 'Support Materials BB174/BW wordless readers',
                            'image_code' => 'E02_WBooklet01',
                        ],
                        'SS' => [
                            'folder' => 'Support Materials BB174/Sequence Strips + icons',
                            'pdf_code' => 'E02_SS',
                            'image_code' => 'E02_SS01'
                        ],
                        // 'WC' => [

                        //     'folder' => 'Support Materials BB174/Word Cards + icons',
                        //     'pdf_code' => 'E02_WC',
                        //     'image_code' => 'E02_WC01'

                        // ]
                    ],
                    'code' => 'E02_0101'

                ],
                'Fiction' => []

            ]
        ]
    ];



    public function __invoke()
    {

        $csvFile = self::$CSV_FILE;

        $file_path = self::$FOLDER_BASE_URL . "/{$csvFile}";
        if (! file_exists($file_path)) {
            WP_CLI::error("File not found: $file_path");
        }

        if (($handle = fopen($file_path, "r")) !== FALSE) {
            WP_CLI::log("Reading file...");

            $count = 0;
            // 3. Loop through rows
            while (($data = fgetcsv($handle, 0, ",", '"')) !== FALSE) {

                // echo "<pre>";
                // print_r($data);
                // exit;

                $phaseSlug = $data[19];


                if (!empty(self::$HAVING_ORDER) && !in_array($data[0], self::$HAVING_ORDER)) {
                    continue;
                }

                if (!empty(self::$ONLY_PHASES) && !in_array($phaseSlug, self::$ONLY_PHASES)) {
                    continue;
                }



                echo "IMPORTING RESOURCE - " . $data[0] . "\n";

                if (!isset(self::$mapping['phases'][$data[19]])) {
                    echo "PHASE NOT FOUND FOR BOOK - " . $data[0] . '\n';
                    continue;
                }

                $post_id = $this->createEbook($data);
                if ($post_id) {
                    $this->setTaxonomies($post_id, $data);
                } else {
                    echo "NOT ABLE TO CREATE/UPDATE EBOOK - " . $data[0];
                }

                // $data is an array of columns


                $count++;

                echo "\n\n\n";
            }

            fclose($handle);
            WP_CLI::success("Finished reading $count rows.");
        } else {
            WP_CLI::error("Could not open the file.");
        }
        WP_CLI::log("Starting synchronization...");
        // Your logic here
        WP_CLI::success("Sync complete!");
    }

    public function createEbook($data)
    {


        $title = $data[2];
        $slug = $data[4];
        $content = $data[3];


        $existing_posts = get_posts([
            'name'           => $slug,
            'post_type'      => 'ebook',
            'post_status'    => 'any', // Checks published, drafts, etc.
            'posts_per_page' => 1,
            'fields'         => 'ids', // Return only the ID for speed
        ]);

        $post_id = ! empty($existing_posts) ? $existing_posts[0] : 0;



        $post_data = [
            'ID'           => $post_id,
            'post_title'   => $title,   // Matches CSV Header "Title"
            'post_slug' => $slug,
            'post_content' => $content, // Matches CSV Header "Content"
            'post_status'  => 'publish',
            'post_type'    => 'ebook', // Change this to your CPT slug
        ];

        // 2. Insert the Post
        $post_id = wp_insert_post($post_data);

        if ($post_id) {
            return $post_id;
        }

        return false;
    }

    public function setTaxonomies($post_id, $data)
    {

        $readingLevel = $data[6];
        $yearLevel = $data[7];
        $fiction = $data[9];
        $age = $data[10];
        $phonicPhase = $data[19];
        $themes = $data[8];

        $themeTerms = explode(',', $themes);
        $themeTerms = array_map('trim', $themeTerms);

        wp_set_object_terms($post_id, [$readingLevel], 'reading-level');
        wp_set_object_terms($post_id, [$yearLevel], 'year-level');
        wp_set_object_terms($post_id, [$fiction], 'fiction');
        wp_set_object_terms($post_id, [$age], 'age');
        wp_set_object_terms($post_id, [$phonicPhase], 'phonics-phase');
        wp_set_object_terms($post_id, ['ebook'], 'category');
        wp_set_object_terms($post_id, $themeTerms, 'ebook-theme');

        $resourceId = $data[0];

        update_post_meta($post_id, 'esiss_resource_id', $resourceId);
        update_post_meta($post_id, 'esiss_page_count', $data[11]);
        update_post_meta($post_id, 'esiss_word_count', $data[12]);
        update_post_meta($post_id, 'esiss_sounds', $data[18]);
        update_post_meta($post_id, 'esiss_tricky', $data[20]);
        update_post_meta($post_id, 'wushka_quiz_id', $data[31]);
        update_post_meta($post_id, 'is_script_imported', 1);



        update_field('esiss_blurb', $data[13], $post_id);
        update_field('esiss_activities', $data[15], $post_id);

        $group_value = array(
            'has_iframe_url' => 1, // Use 1 for true, 0 for false
            'iframe_url'     => "https://online.fliphtml5.com/qdppx/{$resourceId}E02_Singlepages"
        );

        update_field('ebook_iframe_info', $group_value, $post_id);

        $phaseMap = self::$mapping['phases'][$phonicPhase];

        $frontCoverFolders = self::$FOLDER_BASE_URL . '/' . $phaseMap[$data[9]]['phase_folder'] . '/' . $phaseMap[$data[9]]['cover_folder'];
        $coverImage = $resourceId . $phaseMap[$data[9]]['code']; //300409E02_0101

        $coverImagePath = $frontCoverFolders . '/' . $coverImage . ".jpg";

        // echo $coverImagePath.'\n';
        // exit;

        $url = $this->upload_image_to_media($coverImagePath);

        if ($url) {
            update_field('post_image', $url, $post_id);
        } else {
            echo "UNABLE TO UPLOAD IMAGE FOR -" . $data[0] . '\n';
        }

       //$this->uploadSupportMaterials($post_id, $data);
    }

    private function uploadSupportMaterials($post_id, $data)
    {
        $phonicPhase = $data[19];
        $phaseMap = self::$mapping['phases'][$phonicPhase];
        $resourceId = $data[0];

        if (isset($phaseMap) && isset($phaseMap[$data[9]]['support_materials'])) {

            $supportMaterials = $phaseMap[$data[9]]['support_materials'];

            foreach ($supportMaterials as $k => $m) {
                $imageCode = false;
                $pdfCode = false;

                if (isset($supportMaterials[$k]['image_code'])) {
                    $imageCode = $supportMaterials[$k]['image_code'];
                }

                if (isset($supportMaterials[$k]['pdf_code'])) {
                    $pdfCode = $supportMaterials[$k]['pdf_code'];
                }

                if ($pdfCode) {
                    $pdfMat = $resourceId . $pdfCode;
                    $pdfFolder = self::$FOLDER_BASE_URL . '/' . $phaseMap[$data[9]]['phase_folder'] . '/' . $phaseMap[$data[9]]['support_materials'][$k]['folder'];
                    $pdfPath = $pdfFolder . '/' . $pdfMat . ".pdf";

                    $url = $this->upload_image_to_media($pdfPath, $post_id, true);

                    if (!$url) {
                        echo "UNABLE TO UPLOAD PDF " . $k . " FOR " . $data[0] . '\n';
                    }
                }



                if ($imageCode) {
                    $imageMat = $resourceId . $imageCode;
                    $pdfFolder = self::$FOLDER_BASE_URL . '/' . $phaseMap[$data[9]]['phase_folder'] . '/' . $phaseMap[$data[9]]['support_materials'][$k]['folder'];
                    $imagePath = $pdfFolder . '/' . $imageMat . ".jpg";
                    $url = $this->upload_image_to_media($imagePath, $post_id, true);

                    if (!$url) {
                        echo "UNABLE TO UPLOAD IMAGE " . $k . " FOR " . $data[0] . '\n';
                    }
                }
            }
        }
    }


    private function upload_image_to_media($file_path, $parent = 0, $support = false)
    {
        if (! file_exists($file_path)) {
            return false;
        }

        global $wpdb;

        $attachment_id = $wpdb->get_var($wpdb->prepare(
            "SELECT p.ID 
        FROM $wpdb->posts p
        INNER JOIN $wpdb->postmeta pm ON p.ID = pm.post_id
        WHERE p.post_type = 'attachment'
        AND pm.meta_key = '_wp_attached_file'
        AND pm.meta_value LIKE %s
        LIMIT 1",
            '%' . $wpdb->esc_like(basename($file_path)) . '%'
        ));


        if ($attachment_id) {
            $attachmentPost = get_post($attachment_id);

            if (!empty($attachmentPost) && $parent > 0 && $support) {
                // echo $attachment_id.'\n';
                // echo $parent;exit;
                wp_update_post([
                    'ID'          => $attachment_id,
                    'post_parent' => $parent,
                ]);
            }

            return wp_get_attachment_url($attachment_id); // Skip upload, return existing URL
        }

        // 1. Create a temporary copy of the file
        // We add 'tmp_' to the name so WordPress deletes this one, not your original.
        $temp_file = dirname($file_path) . '/tmp_' . basename($file_path);
        copy($file_path, $temp_file);

        // 2. Point WordPress to the TEMPORARY file
        $file_array = [
            'name'     => basename($file_path),
            'tmp_name' => $temp_file, // WordPress will delete THIS file
        ];

        require_once(ABSPATH . 'wp-admin/includes/image.php');
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/media.php');


        $force_name_callback = function ($filename, $ext, $dir) use ($file_array) {
            return $file_array['name'];
        };

        add_filter('wp_unique_filename', $force_name_callback, 20, 3);


        // 3. Perform the sideload
        $attachment_id = media_handle_sideload($file_array, $parent);

        // CLEAN UP FILTERS
        remove_filter('wp_unique_filename', $force_name_callback, 20);

        if (is_wp_error($attachment_id)) {
            // If it fails, make sure we still clean up the temp file if it exists
            if (file_exists($temp_file)) {
                unlink($temp_file);
            }
            wp_cache_delete($attachment_id, 'post_meta');

            WP_CLI::warning("Upload failed: " . $attachment_id->get_error_message());
            return false;
        }

        // Your original $file_path is now safe and untouched!
        return wp_get_attachment_url($attachment_id);
    }
}

if (defined('WP_CLI') && WP_CLI) {
    WP_CLI::add_command('sync', 'My_Sync_Command');
}
