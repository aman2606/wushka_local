<?php
if( ! defined('ABSPATH') ) {
    exit(); // Exit if accessed directly
}

/**
 * Created by PhpStorm.
 * User: jrdnt
 * Date: 4/11/2016
 * Time: 12:06 PM
 */
class Ereader_Analytics {
    //Private Class Variables
    private $sTable;
    private $bTest;

    //Public Variables
    public $aResults;

    public function __construct() {
        $this->sTable = NULL;
        $this->bTest  = TRUE;

        $this->aResults = array(
            'status'  => 0,
            'message' => '',
            'log'     => array(),
            'data'    => array()
        );

        $this->initialiseTable();

        return TRUE;
    }

    /** Initialise Table
     * --------------------------------------------------
     * Confirms Ereader Analaytics DB Table is online.
     * If none is found a new one is created.
     * ---------------------------------------------------
     *
     * @return true          - Return true on function completion
     **/
    function initialiseTable() {
        global $wpdb;
        $this->log('Initialise Ereader Analaytics Table');
        $this->sTable = $wpdb->prefix . 'lessonzone_reading_analytics_reading_instance';

        if( $this->sTable != $wpdb->get_var("SHOW TABLES LIKE '" . $this->sTable . "'") ) {
            //table is not created. Create the table here.
            $this->log('No ereader analytics table exists, create now');

            $sCreate = 'CREATE TABLE IF NOT EXISTS ' . $this->sTable . '( ' .
                '`read_id` int(11) NOT NULL AUTO_INCREMENT, ' .
                '`essis_resource_id` VARCHAR(255), ' .
                '`user_id` int(11), ' .
                '`created` datetime, ' .
                '`duration` int(11), ' .
                '`completed` boolean NOT NULL DEFAULT 0, ' .
                '`narrated` boolean NOT NULL DEFAULT 0, ' .
                '`form_factor` VARCHAR(255), ' .
                '`fiction` boolean NOT NULL DEFAULT 0, ' .
                '`level` int(11), ' .
                'PRIMARY KEY `id` (read_id) ' .
                ');';

            $wpdb->query($sCreate);

            $this->log('Ereader Analytics Table Created');
        }

        $this->log('Table Initialised');

        return TRUE;
    }

    /** Update Record Postmeta Data
     * ------------------------------------------------------------------------
     * Checks to see if wp_postmeta data in Records table
     * still match those in original wp_postmeta field values
     * ------------------------------------------------------------------------
     *
     * @return true - Return true on function completion
     **/
    public function updateRecordPostmeta() {
        global $wpdb;

        $this->log('----- EA Update Post Meta Data in Records -----');
        $this->log('Get Analaytics Records...');
        $aResults = $wpdb->get_results('SELECT * FROM ' . $this->sTable);
        if( isset($aResults) && ! empty($aResults) ) {
            $this->log('Found ' . count($aResults) . ' Analytics Records');
            foreach( $aResults as $oEntry ) {
                $id     = $oEntry->read_id;
                $iResId = $oEntry->essis_resource_id;

                //Get Resource Post Meta
                $oRow = $this->getResourcePostmeta($iResId);
                if( ! isset($oRow) || empty($oRow) ) {
                    $this->log('Notice: (Record #' . $id . ') Found 0 postmeta matching ResId #' . $iResId);
                    continue;
                }

                $bFiction = $this->getFictionTerm($oRow->post_id);
                $iLevel   = $this->getLevelTerm($oRow->post_id);

                $this->log('Post meta retrieved; Updating Record #' . $id);
                $wpdb->update(
                    $this->sTable,
                    array(
                        'fiction' => $bFiction,
                        'level'   => $iLevel
                    ),
                    array('read_id' => $id)
                );
            }
        } else {
            $this->log('Warning: Found 0 Analytics Records, Abort Update');
        }

        return TRUE;
    }

    /** Add New Record
     * ------------------------------------------------------------------------
     * Creates a new ereader analytics record int the DB table.
     * ------------------------------------------------------------------------
     *
     * @param int    $iUser       - ID of user reading the resource
     * @param int    $iResId      - ID of resource being read
     * @param string $sFormFactor - Device resource is being read on (PC/Tablet/Mobile)
     *
     * @return int|false    - Return ID of new record if insert was successful, false on failure
     **/
    public function addRecord( $iUser = NULL, $iResId = NULL, $sFormFactor = NULL ) {
        if( ! isset($iUser, $iResId) ) {
            $this->log('Error: Cannot Add new record with NULL parameters');

            return FALSE;
        }

        //Confirm User ID is valid
        $oUser = get_user_by('id', $iUser);
        if( $oUser === FALSE ) {
            $this->log('Error: Cannot Add new Records with invalid User');
        }

        //Get narration
        /* $narration = get_user_meta( $oUser->ID, 'narration' , true );
        $narrated = 0;
        if($narration == 'Yes'){
            $narrated = 1;
            error_log('--- Ereader Analytics - Narration Yes ---');
        } */

        //Get Postmeta For this Resource
        $oRow = $this->getResourcePostmeta($iResId);
        if( ! isset($oRow) || empty($oRow) ) {
            $this->log('Notice: Found 0 postmeta matching ResId #' . $iResId);
            $this->log('Error: Cannot Add new record with NULL postmeta');

            return FALSE;
        }

        $bFiction = $this->getFictionTerm($oRow->post_id);
        $iLevel   = $this->getLevelTerm($oRow->post_id);

        $this->log('Post meta retrieved; Insert new Analytics Record');
        global $wpdb;
        $wpdb->insert(
            $this->sTable,
            array(
                'essis_resource_id' => $iResId,
                'duration'          => 0,
                'user_id'           => $iUser,
                'created'           => current_time('mysql'),
                'form_factor'       => $sFormFactor,
                'fiction'           => $bFiction,
                //'narrated'           => $narrated,
                'level'             => $iLevel
            )
        );

        $iNew = $wpdb->insert_id;
        if( isset($iNew) && ! empty($iNew) ) {
            $this->log('Success: New Ereader Analaytics Records Added! - #' . $iNew);
            $this->aResults['data']['new'] = $iNew;
            return TRUE;
        }

        $this->log('Error: New Records failed to insert.');

        return FALSE;
    }

    /** Get Resource Postmeta
     * ------------------------------------------------------------------------
     * Returns the wp_postmeta DB table row that matches
     * the specified resource id
     * ------------------------------------------------------------------------
     *
     * @param int $iResId - Resource ID to be queries
     *
     * @return object     - WPDB object containing postmeta row of Resource ID
     **/
    private function getResourcePostmeta( $iResId = NULL ) {
        if( ! isset($iResId) || empty($iResId) ) {
            $this->log('Error: Cannot get post meta of null resource');

            return NULL;
        }

        global $wpdb;

        $sQuery = 'SELECT * FROM '.$wpdb->prefix.'postmeta WHERE meta_key = %s AND meta_value = %s';

        $oRow = $wpdb->get_row(
            $wpdb->prepare($sQuery, 'esiss_resource_id', $iResId)
        );

        if( isset($oRow) && ! empty($oRow) ) {
            return $oRow;
        }

        return NULL;
    }


    /** Get Fiction Term
     * --------------------------------------------------
     * Retrieves the Fiction Taxonomy term attached to
     * a specific WordPress Post Object
     * ---------------------------------------------------
     *
     * @param int $iPost - ID of Post to query
     *
     * @return boolean   - Return True/False if attached term is fiction
     **/
    private function getFictionTerm( $iPost ) {
        $aTerms = wp_get_post_terms($iPost, 'fiction');
        $aTerm  = wp_list_pluck($aTerms, 'name');
        error_log('Post #' . $iPost . ' Fiction: ' . print_r($aTerm, TRUE));

        return ! empty($aTerm) && $aTerm[0] == 'Fiction';
    }

    /** Get Reading Level Term
     * --------------------------------------------------
     * Retrieves the Reading Level Taxonomy term attached to
     * a specific WordPress Post Object
     * ---------------------------------------------------
     *
     * @param int $iPost - ID of Post to query
     *
     * @return string    - Return ID of level attached to Post
     **/
    private function getLevelTerm( $iPost ) {
        $aTerms = wp_get_post_terms($iPost, 'reading-level');
        $aTerm  = wp_list_pluck($aTerms, 'term_taxonomy_id');
        error_log('Post #' . $iPost . ' Level: ' . print_r($aTerm, TRUE));

        return ! empty($aTerm) ? $aTerm[0] : NULL;
    }


    /** Update Duration
     * --------------------------------------------------
     * Once User has finished reading, the duration is
     * added to the initial record.
     * ---------------------------------------------------
     *
     * @param int $iRecord   - ID of record being updated
     * @param int $iDuration - Time in seconds to add to duration
     *
     * @return boolean       - Return true on successful update, false on failure
     **/
    public function updateDuration( $iRecord = NULL, $iDuration = 0 ) {
        if( ! isset($iRecord, $iDuration) ) {
            $this->log('Error: Cannot Update duration of null parameters');

            return FALSE;
        }

        return $this->updateRecord($iRecord, 'duration', intval($iDuration), '%d');
    }

    /** Update Narrated
     * --------------------------------------------------
     * Record that this resource was read with narration
     * ---------------------------------------------------
     *
     * @param int $iRecord   - ID of record being updated
     *
     * @return boolean       - Return true on successful update, false on failure
     **/
    public function updateNarrated( $iRecord = NULL ) {
        if( ! isset($iRecord) ) {
            $this->log('Error: Cannot Update Narration of null parameters');

            return FALSE;
        }

        return $this->updateRecord($iRecord, 'narrated', 1, '%d');
    }

    /** Update Completed
     * -----------------------------------------------------
     * Record that user has completed reading this resource
     * ------------------------------------------------------
     *
     * @param int $iRecord   - ID of record being updated
     *
     * @return boolean       - Return true on successful update, false on failure
     **/
    public function updateCompleted( $iRecord = NULL ) {
        if( ! isset($iRecord) ) {
            $this->log('Error: Cannot Update Completion of null parameters');

            return FALSE;
        }

        return $this->updateRecord($iRecord, 'completed', 1, '%d');
    }

    /** Update Record
     * -------------------------------------------------------
     * Update a chosen field on a specific Record in DB Table
     * --------------------------------------------------------
     *
     * @param int $iRecord   - ID of record being updated
     * @param string $sField - Name of field to update
     * @param $xValue - new value of field being updated
     * @param string $sType - The Format of value being updated.
     *
     * @return boolean       - Return true on successful update, false on failure
     **/
    private function updateRecord( $iRecord = NULL, $sField = NULL, $xValue = NULL, $sType = '%s' ) {
        if( ! isset($iRecord, $sField, $xValue) ) {
            $this->log('Error: Cannot Update Record of null parameters');

            return FALSE;
        }

        //Type can only be %s, %d, %b
        $aValid = array(
            '%d', //Int values
            '%s', //String values
            '%b'  //Boolean values
        );
        if( ! in_array($sType, $aValid) ) {
            $this->log('Error: Cannot update record with invalid parameter type');

            return FALSE;
        }

        global $wpdb;

        $wpdb->update(
            $this->sTable,
            array(
                $sField => $xValue,
                'completed' => 1
            ),
            array(
                'read_id' => intval($iRecord)
            ),
            array($sType),
            array('%d')
        );

        $this->log('Success: Record #'.$iRecord.' - Field '.$sField.' - Has been updated');

        return TRUE;
    }


    /** Log
     * --------------------------------------------------
     * Prints a string message to debug.log
     * adds same string to results array message index
     * ---------------------------------------------------
     *
     * @param string $sEntry - String Containing a code status message
     *
     * @return true          - Return true on function completion
     **/
    private function log( $sEntry = NULL ) {
        if( $this->bTest ) {
            if( isset($sEntry) && ! empty($sEntry) ) {
                error_log($sEntry);
                $this->aResults['message'] = $sEntry;
                $this->aResults['log'][]   = '<br/>-' . $sEntry;
            }
        }

        return TRUE;
    }

    /** Get Results
     * --------------------------------------
     * Returns the storage array of the class
     * --------------------------------------
     * @return array - Contains Four index:
     *               - int      'status':  1 on success, 0 on error
     *               - string   'message': String Response from last log entry
     *               - string   'log':     String of all log entries
     *               - array    'data':    Any data to be returned on function completion (HTML, JSON etc)
     **/
    public function getResults() {
        return $this->aResults;
    }


}

/* ----- END OF FILE ----- */
