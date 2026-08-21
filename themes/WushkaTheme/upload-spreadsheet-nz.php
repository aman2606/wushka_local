<?php
/*
 * Teachers uploading students' data
 */
include $_SERVER['DOCUMENT_ROOT'] . '/wp-config.php';

if( $_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES) ) {
    $return = array(
        'available' => 'Load Uploaded School Speadsheet',
    );

    $load_type     = NULL;
    $inputFileName = $_FILES['filename']['tmp_name'];  // File to read
    $extension     = pathinfo($_FILES['filename']['name'], PATHINFO_EXTENSION);
    error_log('Uploading School Data Spreadseet');
    error_log('File Extension: ' . $extension);
    /* for Excel/.xlsx file extension */
    if( $extension == 'xlsx' ) {
        error_log('ready to import:' . $inputFileName);
        // bit of a crap way to do this, but might as well hijack the class uploading funtionality for School taxonomy load

        if( ($x_data = wushka_load_sheet_data($inputFileName)) !== FALSE ) {

            error_log('First Line: ' . print_r($x_data[1], TRUE));
            if( trim($x_data[1]['A']) == 'Customer' &&
                trim($x_data[1]['B']) == 'Account Name'
            ) {
                error_log('File is School Spreadsheet');
                $load_type = 'SCHOOL';

                foreach( $x_data as $idx => $data ) {
                    if( $idx == 1 ) {
                        continue;
                    }

                    if( ! isset($data['A']) && empty($data['A']) ) {
                        error_log('Found Empty Customer Number, Assume end of data');
                        break;
                    }

                    $students[ $idx ]['A'] = $data['A']; //Customer ID
                    $students[ $idx ]['B'] = $data['B']; //School Name
                    //students[ $idx ]['C'] = $data['C']; //School Name Extended
                    $students[ $idx ]['J'] = $data['J']; //Customer Group
                    //$students[ $idx ]['L'] = $data['L']; //CAPS School Name
                    $students[ $idx ]['M'] = $data['C']; //School Address 1
                    $students[ $idx ]['N'] = $data['D']; //School Address 2
                    $students[ $idx ]['O'] = $data['E']; //School Address 3
                    $students[ $idx ]['P'] = $data['F'] . ' ' . $data['G']; //School Address 4 + Postcode
                    $students[ $idx ]['Q'] = $data['K']; //Telephone #
                    $students[ $idx ]['R'] = $data['L']; //Web Address
//                    $students[ $idx ]['S'] = $data['S']; //Latitude
//                    $students[ $idx ]['T'] = $data['T']; //Longitude
//                    $students[ $idx ]['U'] = $data['U']; //Student Count (Pupils)
                    $students[ $idx ]['V'] = 'NZ';//$data['V']; //Country
                }

                $return['available'] = 'School import success';
                error_log('Loaded: ' . count($students) . ' From School SpreadSheet');
            }

            /* Error checking, validation and writing to database */
            if( $extension == 'xlsx' ) {
                if( ! empty($students) && $load_type == 'SCHOOL' ) {
                    error_log('School SpreadSheet Found, Prepare to Store School Terms');
                    $b_finished = generate_school_taxonomy_terms($students);
                    if( $b_finished === TRUE ) {
                        $return['available'] = 'School Terms Added';
                    } else {
                        $return['available'] = 'Error Occured Adding School Terms';
                    }
                } else {
                    $return['available'] = 'Empty';
                }
            }
        }
    } else {
        error_log('School Upload failed, incorrect spreadsheet format (must be xlsx');
        $return['available'] = 'Incorrect Spreadsheet Format';
    }

    echo json_encode($return);
    exit();
}


function wushka_load_sheet_data( $s_filename ) {
    if( empty($s_filename) ) {
        return FALSE;
    }

    /** PHPExcel_IOFactory */
    require_once 'Classes/PHPExcel/IOFactory.php';

    try {
        $objPHPExcel = PHPExcel_IOFactory::load($s_filename);
        $sheetData   = $objPHPExcel->getActiveSheet()->toArray(NULL, TRUE, TRUE, TRUE);
        error_log('School SheetData Successfully Loaded');

        return $sheetData;

    } catch( Exception $e ) {
        $return['available'] = 'School import failed';
        error_log('School Uploader FAILED: Could not load into IOFactory');
        error_log('Error loading file "' . pathinfo($s_filename, PATHINFO_BASENAME) . '": ' . $e->getMessage());
    }

    return FALSE;
}

function generate_school_taxonomy_terms( $a_schools = NULL ) {
    if( ! isset($a_schools) || empty($a_schools) ) {
        return FALSE;
    }

    foreach( $a_schools as $idx => $a_school ) {
        error_log('Gathering Options for School: ' . $a_school['A']);
        $a_term = array(
            'slug'        => NULL,
            'name'        => NULL,
            'description' => NULL,
            'state'       => NULL,
            'country'     => NULL,
            'cust_group'  => NULL,
            'timezone'    => NULL,
            'web'         => NULL,
            'phone'       => NULL,
            'pupils'      => NULL,
            'lat'         => NULL,
            'lng'         => NULL
        );

        //1. Support ID -> School ID
        $a_term['slug'] = (int)$a_school['A'];

        //2. Name -> School Name
        $s_name = NULL;
        if( isset($a_school['B']) && ! empty($a_school['B']) ) {
            $s_name = trim($a_school['B']);
        }
        //3. Extended School Name
        if( isset($a_school['C']) && ! empty($a_school['C']) ) {
            $s_name .= ' ' . trim($a_school['C']);
        }

        if( isset($s_name) ) {
            $a_term['name'] = strtolower(ucwords(trim($s_name)));
        }

        //4. Customer Group
        if( isset($a_school['J']) && ! empty($a_school['J']) ) {
            $a_term['cust_group'] = trim($a_school['J']);
        }

        //5. Address -> Term Description
        $a_address = array(
            'M' => trim($a_school['M']),
            'N' => trim($a_school['N']),
            'O' => trim($a_school['O']),
            'P' => trim($a_school['P']),
        );

        $s_address = '';
        $s_state   = '';
        foreach( $a_address as $s_col => $s_cell ) {
            if( isset($s_cell) && ! empty($s_cell) ) {
                $s_address .= ' ' . $s_cell;

                if( $a_school['V'] == 'AU' && ($s_col == 'O' || $s_col == 'P') ) {
                    $s_state .= ' ' . $s_cell;
                }
            }
        }

        unset($a_address);

        if( isset($s_address) ) {
            $a_term['description'] = strtolower(ucwords(trim($s_address)));
        }

        //6. School Country Code
        if( isset($a_school['V']) && ! empty($a_school['V']) ) {
            $a_term['country'] = trim($a_school['V']);
        }

        //Based on Country Code, get appropriate TimeZone
        if( $a_term['country'] == 'AU' ) {
            //6. School State
            if( isset($s_state) ) {
                $s_state = strtolower($s_state);
                $s_state = trim($s_state);
            }

            upload_school_get_au_timezone($s_state, $a_school);

        } else if( $a_term['country'] == 'NZ' ) {
            $a_term['timezone'] = upload_school_get_nz_timezone();
        }

        //7. Telephone
        if( isset($a_school['Q']) && ! empty($a_school['Q']) ) {
            $a_term['phone'] = trim($a_school['Q']);
        }

        //8. School Website
        if( isset($a_school['R']) && ! empty($a_school['R']) ) {
            $a_term['web'] = trim($a_school['R']);
        }

        //9. School Lat Lng Coords
        if( isset($a_school['S']) && ! empty($a_school['S']) ) {
            $a_term['lat'] = trim($a_school['S']);
        }
        if( isset($a_school['T']) && ! empty($a_school['T']) ) {
            $a_term['lng'] = trim($a_school['T']);
        }

        //10. Student Count AKA Pupils
        if( isset($a_school['U']) && ! empty($a_school['U']) ) {
            $a_term['pupils'] = trim($a_school['U']);
        }

        //error_log('---------- School TERM ----------');
        //error_log('Slug: ' .        $a_term['slug']);
        //error_log('Name: ' .        $a_term['name']);
        //error_log('---------------------------------');

        $a_term_args = array(
            'description' => $a_term['description'],
            'slug'        => $a_term['slug']
        );

        $o_term    = get_term_by('slug', $a_term['slug'], 'school');
        $i_term_id = NULL;
        if( $o_term === FALSE ) {
            error_log('School Not Found: Inserting new school term');
            $x_new_term = wp_insert_term($a_term['name'], 'school', $a_term_args);
            if( is_wp_error($x_new_term) ) {
                error_log('loop idx(' . $idx . ') --- Failed to Add Term for: ' . $a_term['name'] . '(slug=' . $a_term['slug'] . ')');
            } else {
                $i_term_id = $x_new_term['term_id'];
            }
        } else {
            error_log('Updating School Term');
            $i_term_id = $o_term->term_id;
        }

        if( isset($i_term_id) ) {
            $school_options = array();
            if( isset($a_term['timezone']) ) {
                $school_options['school_tz'] = $a_term['timezone'];
            }
            if( isset($a_term['lat']) ) {
                $school_options['school_latitude'] = $a_term['lat'];
            }
            if( isset($a_term['lng']) ) {
                $school_options['school_longitude'] = $a_term['lng'];
            }
            if( isset($a_term['state']) ) {
                $school_options['school_state'] = $a_term['state'];
            }
            if( isset($a_term['cust_group']) ) {
                $school_options['school_cust_group'] = $a_term['cust_group'];
            }
            if( isset($a_term['phone']) ) {
                $school_options['school_phone'] = $a_term['phone'];
            }
            if( isset($a_term['web']) ) {
                $school_options['school_web'] = $a_term['web'];
            }
            if( isset($a_term['pupils']) ) {
                $school_options['school_pupils'] = $a_term['pupils'];
            }
            if( isset($a_term['country']) ) {
                $school_options['school_country'] = $a_term['country'];
            }

            update_option('taxonomy_' . $i_term_id, $school_options);
        }

        /* --- END ForEach --- */
    }
}

function upload_school_get_au_timezone( $s_state ) {
    $a_return = array(
        'state'    => NULL,
        'timezone' => NULL
    );

    //7. PostCode/State -> TimeZone
    $a_states = array(
        'VIC' => 'Australia/Melbourne',
        'NSW' => 'Australia/Sydney',
        'QLD' => 'Australia/Queensland',
        'ACT' => 'Australia/Canberra',
        'SA'  => 'Australia/Adelaide',
        'NT'  => 'Australia/Darwin',
        'WA'  => 'Australia/Perth',
        'TAS' => 'Australia/Hobart',
    );

    $a_add_state = explode(' ', $s_state);
    $s_school_tz = NULL;

    foreach( $a_add_state as $ix => $word ) {
        if( $s_school_tz !== NULL ) {
            break;
        }
        $test_word = strtoupper($word);
        foreach( $a_states as $s_state_name => $s_state_timezone ) {
            if( $test_word == $s_state_name ) {
                $s_school_tz       = $s_state_timezone;
                $a_return['state'] = $s_state_name;
                break;
            }
        }
        unset($test_word);
    }

    if( isset($s_school_tz) && ! empty($s_school_tz) ) {
        $tzlist = DateTimeZone::listIdentifiers(DateTimeZone::ALL);
        foreach( $tzlist as $tz_id => $tz ) {
            if( $s_school_tz == $tz ) {
                $a_return['timezone'] = trim($tz_id);
            }
        }
    }

    return $a_return;
}

function upload_school_get_nz_timezone() {
    $tzlist = DateTimeZone::listIdentifiers(DateTimeZone::ALL);
    foreach( $tzlist as $tz_id => $tz ) {
        if( 'Pacific/Auckland' == $tz ) {
            return trim($tz_id);
        }
    }

    return NULL;
}

/* ----- End Of File ----- */
