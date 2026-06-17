<?php  
//Log User activity
  
//Init
new UserActivityLog();

class UserActivityLog{ 
    private $browserName;
    private $browserVersion;
    private $platform;
    private $ipAddress;
    private $tableName = "wushka_activity_logs";
    private $allowedRoles = array('administrator', 'subadmin');
    private $logActivityToDB = TRUE;

    public function __construct(){
        if(is_user_logged_in()){
            $current_user = wp_get_current_user();

            if($this->getActivityCookie('wordpress_swi_tch')){
                $current_user = get_user_by('id', $this->getActivityCookie('wordpress_swi_tch') );
            }

            if(!$this->validRoles($current_user)){ 
                return false;
            } 
        }

        //Create db
        add_action( 'init', [$this, 'activityLogDB'], 10 );

        //Log Login In Details
        add_action('wp_login', [$this, 'userLoginActivity'], 10, 2); 

        //Log logout Details 
        add_action('wp_logout', [$this, 'userLogoutActivity'], 10, 1);   
        add_action('wushka_custom_logout', [$this, 'userLogoutActivity'], 10, 1);   

        //switch user
        add_action('wordpress_swi_tch', [$this, 'userSwitchActivity'], 10, 4);  

        //swtich back user
        add_action( 'switch_back_user', [$this, 'userSwitchBackActivity'], 10, 4);
        

        /**
        *---------------------------------------------------------------------------------
        * Add activity
        *----------------------------------------------------------------------------------
        */ 

        //Add User
        add_action('user_register', [$this, 'addUserActivity']);
        //Add post type like post, page, ebook
        add_action( 'wp_insert_post', [$this, 'addPostTypeActivity'], 10, 2 );
        //Add term 
        add_action( 'created_term', [$this, 'addTermActivity'], 10, 3 );
        


        /**
        *---------------------------------------------------------------------------------
        * Update activity
        *----------------------------------------------------------------------------------
        */ 
        //update profile 
        add_action( 'show_user_profile', [$this, 'getUserSchoolId'], 11 );
        add_action( 'edit_user_profile', [$this, 'getUserSchoolId'], 11 );
        add_action( 'personal_options_update', [$this, 'updateProfileActivity']);
        add_action( 'edit_user_profile_update', [$this, 'updateProfileActivity']);
        add_action( 'wushka_move_student_action', [$this, 'studentClassMoveActivity'], 10, 3);

        //Update Post type
        add_action('post_updated', [$this, 'updatePostTypeActivity'], 10, 3);
        //update term
        add_action('wp_update_term_data', [$this, 'editTermActivity'], 10, 4);
        



        /**
        *---------------------------------------------------------------------------------
        * Delete activity
        *----------------------------------------------------------------------------------
        */ 

        //Delete user
        add_action( 'delete_user', [$this, 'removeUserActivity']);

        //Delete post type
        add_action( 'after_delete_post', [$this, 'removePostTypeActivity'], 10, 2 );
        add_action( 'trashed_post', [$this, 'trashPostTypeActivity'] );

        //Delete Term
        add_action( 'delete_term', [$this, 'deleteTermActivity'], 10, 5 );

        
        /**
        *---------------------------------------------------------------------------------
        * Track only if the allowed user swtiched to different user
        *----------------------------------------------------------------------------------
        */        
        if( $this->getActivityCookie('wordpress_swi_tch') ){
            //Add class
            add_action( 'wushka_insert_class_action', [$this, 'addClassActivity'], 10, 4 );

            //Update class
            add_action( 'wushka_update_class_action', [$this, 'editClassActivity'], 10, 3 );

            //Archive class
            add_action( 'wushka_delete_class_action', [$this, 'archiveClassActivity'], 10, 1 );

            //Edit User information from teacher/school panel
            add_action( 'wushka_edit_user_action', [$this, 'editUserActivity'], 10, 3 );
        }


    }

    public function activityLogDB(){
        global $wpdb; 
 
        $table_name = $wpdb->prefix . $this->tableName;  
        $row = $wpdb->get_results('SHOW TABLES LIKE "'. $table_name .'"'); 
        if(empty($row)){
            $sql = "CREATE TABLE $table_name (
                `ID` INT NOT NULL AUTO_INCREMENT,
                `activity` VARCHAR(255) NOT NULL,
                `user_id` VARCHAR(255) NOT NULL,
                `user_details` TEXT NOT NULL,
                `activity_details` TEXT NOT NULL,
                `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`ID`))";

            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
            error_log('----- wushka log db created -----');
        }
    }

    public function activityLog(array $log){
        if($this->logActivityToDB){
            global $wpdb;
            if(empty($log['activity'])){
                error_log('Activity Log Error: Activity not found.'); 
            }

            $user_id = $log['user_id'];
 
            if( $this->getActivityCookie('wordpress_swi_tch') ){
                $user = array(
                    'Real User ID'      =>  (int) $this->getActivityCookie('wordpress_swi_tch'),
                    'Active User ID'    =>  $log['user_id']
                );
                $user_id = wp_json_encode( $user );
            }

            $table_name = $wpdb->prefix . $this->tableName;
            $data = array(
                'activity'          =>      $log['activity'],
                'user_id'           =>      $user_id,
                'user_details'      =>      wp_json_encode($log['user_details']),
                'activity_details'  =>      wp_json_encode($log['activity_details']),
                'created_at'        =>      current_time( 'mysql' )
            );
            $data_type = array('%s','%s','%s','%s');
            $wpdb->insert($table_name,$data,$data_type);
            //$insert_id = $wpdb->insert_id;
        } 
    }
    
    private $basicBrowser = [
        'Trident\/7.0'      =>      'Internet Explorer 11',
        'Beamrise'          =>      'Beamrise',
        'Opera'             =>      'Opera',
        'OPR'               =>      'Opera',
        'Shiira'            =>      'Shiira',
        'Chimera'           =>      'Chimera',
        'Phoenix'           =>      'Phoenix',
        'Firebird'          =>      'Firebird',
        'Camino'            =>      'Camino',
        'Netscape'          =>      'Netscape',
        'OmniWeb'           =>      'OmniWeb',
        'Konqueror'         =>      'Konqueror',
        'icab'              =>      'iCab',
        'Lynx'              =>      'Lynx',
        'Links'             =>      'Links',
        'hotjava'           =>      'HotJava',
        'amaya'             =>      'Amaya',
        'IBrowse'           =>      'IBrowse',
        'iTunes'            =>      'iTunes',
        'Silk'              =>      'Silk',
        'Dillo'             =>      'Dillo', 
        'Maxthon'           =>      'Maxthon',
        'Arora'             =>      'Arora',
        'Galeon'            =>      'Galeon',
        'Iceape'            =>      'Iceape',
        'Iceweasel'         =>      'Iceweasel',
        'Midori'            =>      'Midori',
        'QupZilla'          =>      'QupZilla',
        'Namoroka'          =>      'Namoroka',
        'NetSurf'           =>      'NetSurf',
        'BOLT'              =>      'BOLT',
        'EudoraWeb'         =>      'EudoraWeb',
        'shadowfox'         =>      'ShadowFox',
        'Swiftfox'          =>      'Swiftfox',
        'Uzbl'              =>      'Uzbl',
        'UCBrowser'         =>      'UCBrowser',
        'Kindle'            =>      'Kindle',
        'wOSBrowser'        =>      'wOSBrowser',
        'Epiphany'          =>      'Epiphany', 
        'SeaMonkey'         =>      'SeaMonkey',
        'Avant Browser'     =>      'Avant Browser',
        'Edg'               =>      'Microsoft Edge',
        'Firefox'           =>      'Firefox',
        'Chrome'            =>      'Google Chrome',
        'MSIE'              =>      'Internet Explorer',
        'Internet Explorer' =>      'Internet Explorer',
        'Safari'            =>      'Safari',
        'Mozilla'           =>      'Mozilla'  
    ];

    private $basicplatform = [
        'windows'       =>      'Windows', 
        'iPad'          =>      'iPad', 
        'iPod'          =>      'iPod', 
        'iPhone'        =>      'iPhone', 
        'mac'           =>      'Apple', 
        'android'       =>      'Android', 
        'linux'         =>      'Linux',
        'Nokia'         =>      'Nokia',
        'BlackBerry'    =>      'BlackBerry',
        'FreeBSD'       =>      'FreeBSD',
        'OpenBSD'       =>      'OpenBSD',
        'NetBSD'        =>      'NetBSD',
        'UNIX'          =>      'UNIX',
        'DragonFly'     =>      'DragonFlyBSD',
        'OpenSolaris'   =>      'OpenSolaris',
        'SunOS'         =>      'SunOS', 
        'OS\/2'         =>      'OS/2',
        'BeOS'          =>      'BeOS',
        'win'           =>      'Windows',
        'Dillo'         =>      'Linux',
        'PalmOS'        =>      'PalmOS',
        'RebelMouse'    =>      'RebelMouse' 
    ];

    private function userAgent(){
        $http_user_agent = $_SERVER['HTTP_USER_AGENT'];
        if(empty($http_user_agent)){
            $http_user_agent = getenv('HTTP_USER_AGENT');
        }
       return $http_user_agent;
    } 

    private function detectBrowser() {
        foreach($this->basicBrowser as $pattern => $name) {
            if( preg_match("/".$pattern."/i", $this->userAgent(), $match)) {
                $this->browserName = $name;
                
                // finally get the correct version number
                $known = array('Version', $pattern, 'other');

                $patternbrowserVersion = '#(?<browser>' . join('|', $known).')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
                //dd($patternbrowserVersion);
                if (!preg_match_all($patternbrowserVersion, $this->userAgent(), $matches)) {
                    // we have no matching number just continue
                }

                // see how many we have
                $i = count($matches['browser']);
                if ($i != 1) {
                    //we will have two since we are not using 'other' argument yet
                    //see if version is before or after the name
                    if (strripos($this->userAgent(),"Version") < strripos($this->userAgent(),$pattern)){
                        @$this->browserVersion = $matches['version'][0];
                    }
                    else {
                        @$this->browserVersion = $matches['version'][1];
                    }
                }
                else {
                    $this->browserVersion = $matches['version'][0];
                }
                break;
            }
        }
    }   

    private function detectPlatform() {
        foreach($this->basicplatform as $key => $platform) {
            if (stripos($this->userAgent(), $key) !== false) {
                $this->platform = $platform;
                break;
            } 
        }
    }

    public function detect() {
        $this->detectBrowser();
        $this->detectPlatform();
        return $this;
    }

    public function getBrowser() {
        if(!empty($this->browserName)) {
            return $this->browserName;
        }
    }        

    public function getVersion() {
       return $this->browserVersion;
    }

    public function getPlatform() {
       if(!empty($this->platform)) {
          return $this->platform;
       }
    }

    public function getUserAgent() {
        return $this->userAgent();
    }

    public function getIpAddress(){
        if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
            //check ip from share internet 
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) { 
            //to check ip is pass from proxy
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

    public function getInfo() {
        $info = [
            "Ip Address"    =>  $this->getIpAddress(),
            "Browser"       =>  trim($this->getBrowser().' '.$this->getVersion(), ' '), 
            "Platform"      =>  $this->getPlatform(),
            "User Agent"    =>  $this->getUserAgent(),
        ];
        
        return $info; 
    }

    public function validRoles($user){
        if(count(array_intersect($user->roles, $this->allowedRoles)) === 0){
            return false; 
        } 
        return true;
    }

    public function getUserDetails($user){
        $info = $this->detect()->getInfo();
        $user_details = array(
            'User Login'    =>      $user->user_login,
            'User Roles'    =>      rtrim(implode(', ', $user->roles), ', '),
            'Ip Address'    =>      $info['Ip Address'],
            'Browser'       =>      $info['Browser'],
            'Platform'      =>      $info['Platform'],
            'User Agent'    =>      $info['User Agent']
        );
        return $user_details;
    }
  
    public function userLoginActivity($user_login, $user){
        if(!$this->validRoles($user)){ 
            return false;
        }        
       
        $log = array(
            "activity"          =>     'Login',
            "user_id"           =>      $user->ID,
            "user_details"      =>      $this->getUserDetails($user),
            "activity_details"  =>      array(
                'Status'    =>      'Successfully logged in.'
            )
        );

        $this->activityLog($log);
    }

    public function userLogoutActivity($user_id){
        $user = get_user_by( 'id', $user_id );  

        if( $this->getActivityCookie('wordpress_swi_tch') ){            
            $real_user = get_user_by( 'id', $this->getActivityCookie('wordpress_swi_tch') ); 

            if(!$this->validRoles($real_user)){  
                return false;
            }            
        }else{
            if(!$this->validRoles($user)){ 
                return false;
            }
        }


        $log = array(
            "activity"          =>     'Logout',
            "user_id"           =>     $user->ID,
            "user_details"      =>     $this->getUserDetails($user),
            "activity_details"  =>     array(
                'Status'    =>      'Successfully logged out.'
            )
        );

        $this->activityLog($log);   
        
        if( $this->getActivityCookie('wordpress_swi_tch') ){
            //Clear cookie
            $this->unsetActivityCookie('wordpress_swi_tch');
        }
    }

    public function userSwitchActivity($user_id, $old_user_id, $new_token, $old_token){
        $from = get_user_by( 'ID', $old_user_id );
        $to = get_user_by( 'ID', $user_id );        

        if($this->validRoles($from)){ 
            $switched_to = array(
                "Status"     =>      "Successfully Switched User",
                "From"      =>      [
                    "User Login"    =>  $from->data->user_login,
                    "User ID"       =>  $from->ID,
                    "User Role"     =>  rtrim(implode(', ', $from->roles), ', ')
                ],
                "To"        =>      [
                    "User Login"    =>  $to->data->user_login,
                    "User ID"       =>  $to->ID,
                    "User Role"     =>  rtrim(implode(', ', $to->roles), ', ')
                ]
            );
    
            $log = array(
                "activity"          =>     'Switched User',
                "user_id"           =>      $to->ID,
                "user_details"      =>      $this->getUserDetails($from),
                "activity_details"  =>      $switched_to
            );

            //Set cookie for previous user
            if(! $this->getActivityCookie('wordpress_swi_tch') ){
                $this->setActivityCookie('wordpress_swi_tch', $from->ID);
            }
            

            $this->activityLog($log); 
        }        
    }

    public function userSwitchBackActivity($user_id, $old_user_id, $new_token, $old_token){
        $from = get_user_by( 'ID', $old_user_id );
        $to = get_user_by( 'ID', $user_id );
 
        if($this->validRoles($to)){
            $switched_to = array(
                "Status"    =>      "Successfully Switched Back",
                "From"      =>      [
                    "User Login"    =>  $from->data->user_login,
                    "User ID"       =>  $from->ID,
                    "User Role"     =>  rtrim(implode(', ', $from->roles), ', ')
                ],
                "To"        =>      [
                    "User Login"    =>  $to->data->user_login,
                    "User ID"       =>  $to->ID,
                    "User Role"     =>  rtrim(implode(', ', $to->roles), ', ')
                ]
            );
    
            $log = array(
                "activity"          =>      'Switched Back',
                "user_id"           =>      $old_user_id,
                "user_details"      =>      $this->getUserDetails($from),
                "activity_details"  =>      $switched_to
            ); 

            $this->activityLog($log); 

            //Remove cookie for previous user
            $this->unsetActivityCookie('wordpress_swi_tch');
        }
    }
 
    public function getUserSchoolId($user){
        $school = wp_get_object_terms($user->ID, 'school');
        $school_id = -1;
        if(isset($school) && !empty($school)){
            $school_id = $school[0]->term_id;
        }  
        
        $this->setActivityCookie('school_id', $school_id); 
    } 

    private function profileRequestFilter($user, $request){
        //Remove unwanted fields from $request
        $filters = array( '_wpnonce', 'wp_http_referer', 'admin_bar_front', 'locale', '_wp_http_referer', 'from', 'checkuser_id', 'color-nonce', 'wpseo_nonce', 'user_id', 'action', 'submit', 'wpseo_author_title', 'wpseo_author_metadesc' );
        foreach($filters as $filter){
            if(isset($request[$filter])){
                unset($request[$filter]);
            }
        }

        //Show *** if password is set
        if(isset($request['pass1']) && !empty($request['pass1']) && isset($request['pass2']) && !empty($request['pass2'])){
            $request['pass1'] = $request['pass2'] = '******';
        } else{
            unset($request['pass1']);
            unset($request['pass2']);
        }
        

        //User meta
        $user_meta =  get_user_meta( $user->ID );
        $user_meta['display_name'][0] = $user->display_name;
        $user_meta['email'][0] = $user->data->user_email;
        $user_meta['url'][0] = $user->data->user_url; 
  
        $school_id = ( $this->getActivityCookie('school_id') )? $this->getActivityCookie('school_id') : -1; 
  
        //Get only changed keys
        foreach($request as $req_key => $req_value){
            if($req_key == 'role'){
                if(in_array($req_value, $user->roles)){
                    unset($request[$req_key]);
                }           
            }elseif($req_key == 'school'){ 
                if($req_value == $school_id){
                    unset($request[$req_key]);
                } 
            }else{
                $meta_key = $req_key;
            } 
 
            if($req_value == $user_meta[$meta_key][0]){ 
                unset($request[$req_key]);
            } 
        } 
  
        $from = array();
        //get previous data
        foreach($request as $req_key => $req_value){
            if($req_key == 'school'){
                $from[$req_key] = (string) $school_id;
            }elseif($req_key == 'role'){
                $role = unserialize($user_meta['wp_capabilities'][0]);
                $role = implode(array_keys($role),', '); 
                $from[$req_key] = $role;
            }else{
                $from[$req_key] = $user_meta[$req_key][0];
            }
            
        }

        $updated = array(
            'From'  => $from,
            'To'    => $request
        );
        
        
        //destroy school id cookie
        if( $this->getActivityCookie('school_id') ){
            $this->unsetActivityCookie('school_id'); 
        } 

        return $updated;
    } 

    public function updateProfileActivity($user_id){
        $user = wp_get_current_user();
        $update_user = get_user_by( 'id', $user_id );
               

        $request = $this->profileRequestFilter($update_user, $_REQUEST);
   
        if(empty($request['From']) && empty($request['To']) ){
            return false;
        }

        $activity_details = array(
            "Status"            =>      "Updated other user profile",
            "Updated User ID"   =>      $user_id
        );

        //Check if user is updating self or different user
        if($user->ID == $user_id){ 
            $activity_details = array(
                "Status"     =>      "Updated personal profile"
            ); 
        }

        $activity_details['updated_fields'] = $request; 

        $log = array(
            "activity"          =>      'User Updated',
            "user_id"           =>      $user->ID,
            "user_details"      =>      $this->getUserDetails($user),
            "activity_details"  =>      $activity_details
        );

        $this->activityLog($log); 
    }

    public function addUserActivity($user_id){
        $user = wp_get_current_user();
        $created_user = get_user_by( 'id', $user_id );
        
        $activity_details = array(
            'Status'            =>      'Successfully user created.',
            'Registered User' =>  array(
                    'ID'            =>      $created_user->ID,
                    'Login'         =>      $created_user->data->user_login,
                    'Email'         =>      $created_user->data->user_email,
                    'Role'          =>      $created_user->roles
            )
        );

        $log = array(
            "activity"          =>      'User Created',
            "user_id"           =>      $user->ID,
            "user_details"      =>      $this->getUserDetails($user),
            "activity_details"  =>      $activity_details
        );

        $this->activityLog($log); 
    }

    private function editUserFilter($id, $meta_key, $meta_value){
        if($meta_key == 'user_pass'){
            $meta_key = 'show_user_pwd';
        }

        $o_user = get_user_by_hash($id);
        if ( $o_user ) {
            $user_id = $o_user->ID;
        }
        
        
        if ( metadata_exists( 'user', $user_id, $meta_key ) ) {
            $prev = get_user_meta($user_id, $meta_key);
            switch($meta_key){
                case 'reading_level':
                    $prev = $prev['slug'];
                    break;
                case 'allowed_shelves':
                    $prev = $prev['name'];
                    break;
                case 'active':
                    $action = 'Archived';
                    if($meta_value){
                        $action = 'Unarchived';
                    } 
                    return array(
                        'Action'    =>  $action.' user.',
                        'ID'        =>  $user_id, 
                    );
                default:
                    $prev = $prev[0];
                    break;
            }
        }else{
            switch($meta_key){
                case 'classPass':
                    return array(
                        'Action'        => 'Whole class password changed.',
                        'Class Id'      => $id,
                        'To'            => $meta_value
                    );
                    break;
                case 'all_level':
                    return array(
                        'Action'        => 'Whole class reading level changed.',
                        'Class Id'      => $id,
                        'To'            => $meta_value
                    );
                    break;
                case 'all_shelves':
                    return array(
                        'Action'        => 'Whole class level access changed.',
                        'Class Id'      => $id,
                        'To'            => $meta_value
                    );
                    break;
                case 'allnarration':
                    return array(
                        'Action'        => 'Whole class narration changed.',
                        'Class Id'      => $id,
                        'To'            => $meta_value
                    );
                    break;
                case 'allquiz':
                    return array(
                        'Action'        => 'Whole class quizzes changed.',
                        'Class Id'      => $id,
                        'To'            => $meta_value
                    );
                    break;
                case 'new_rg':
                    return array(
                        'Action'        => 'New Reading Group Created',
                        'Class Id'      => $id,
                        'To'            => $meta_value
                    );
                    break;
                case 'multiple_rg':

                    $user_ids = [];
                    foreach($id as $userID){
                        $temp_user = get_user_by_hash($userID);
                        if($temp_user){
                            array_push($user_ids, $temp_user->ID );
                        }
                    }

                    return array(
                        'Action'                    =>  'Multiple student reading group changed',
                        'Students Id'               =>  implode($user_ids, ', '),
                        'To (Reading Group ID)'     =>  $meta_value
                    );
                    break;
                case 'all_setting':
                    return array(
                        'Action'        => 'Whole class reading group permission changed',
                        'Class Id'      => $id,
                        'To'            => $meta_value
                    );
                    break;
                case 'all_quiz_narration':
                    return array(
                        'Action'        => 'Whole class quiz narration changed',
                        'Class Id'      => $id,
                        'To'            => $meta_value
                    );
                    break;
                case 'all_quiz_results':
                    return array(
                        'Action'        => 'Allow Whole class quiz results changed',
                        'Class Id'      => $id,
                        'To'            => $meta_value
                    );
                    break;
                case 'archiveAll':
                    return array(
                        'Action'        => 'Students from the whole class have been archived.',
                        'Class Id'      => $id
                    );
                    break;
                case 'user_email':
                    $user = get_userdata($user_id);
                    $prev = $user->user_email;
                    break;               
                default:
                    return array(
                        'Key'   => $meta_key,
                        'From'  => "",
                        'To'    => $meta_value
                    );
                    break;
            } 
            
        } 

        if($prev == $meta_value){
            return false;
        }

        $updated = array(
            'Key'   => $meta_key,
            'From'  => $prev,
            'To'    => $meta_value
        ); 

        return $updated;
    }

    public function editUserActivity($id, $meta_key, $meta_value){
        $user = wp_get_current_user(); 

        $updated = $this->editUserFilter($id, $meta_key, $meta_value);
        
        if(!$updated){
            return false;
        }

        $activity_details = array(
            'Status'            =>      'Successfully user details updated.'
        ) + $updated;

        $log = array(
            "activity"              =>      'User Updated',
            "user_id"               =>      $user->ID,
            "user_details"          =>      $this->getUserDetails($user),
            "activity_details"      =>      $activity_details
        );

        $this->activityLog($log); 
    }

    public function studentClassMoveActivity($a_students, $i_table, $i_class){
        $user = wp_get_current_user(); 
        
        $students = [];
        foreach($a_students as $a_student){
            $a_student = get_user_by_hash($a_student);
            if($a_student){
                array_push($students, $a_student->ID);
            }
        }

        $activity_details = array(
            'Status'            =>      'Student successfully moved to another class.',
            'Student moved to class' =>  array(
                    'ID'                =>      implode($students, ', '),
                    'From class id'     =>      $i_table,
                    'To class id'       =>      $i_class
            )
        );

        $log = array(
            "activity"              =>      'Student moved',
            "user_id"               =>      $user->ID,
            "user_details"          =>      $this->getUserDetails($user),
            "activity_details"      =>      $activity_details
        );

        $this->activityLog($log);
    }

    public function removeUserActivity($user_id){
        $user = wp_get_current_user();
        $created_user = get_user_by( 'id', $user_id );
        
        $activity_details = array(
            'Status'            =>      'Successfully user deleted.',
            'Deleted User' =>  array(
                    'ID'            =>      $created_user->ID,
                    'Login'         =>      $created_user->data->user_login,
                    'Email'         =>      $created_user->data->user_email,
                    'Role'          =>      $created_user->roles
            )
        );

        $log = array(
            "activity"              =>      'User Deleted',
            "user_id"               =>      $user->ID,
            "user_details"          =>      $this->getUserDetails($user),
            "activity_details"      =>      $activity_details
        );

        $this->activityLog($log); 

    }
 
    public function addPostTypeActivity($post_id, $post){
        $post_type = $post->post_type;
        $post_meta_key = '_create_'.$post_type;

        $valid = false;
        if ($post->post_status == 'publish' && empty(get_post_meta( $post_id, $post_meta_key, true ))) {
            $valid = true;
            // And update the meta so it won't run again
            update_post_meta( $post_id, $post_meta_key, true );
        }

        if($valid){
            $user = wp_get_current_user();
            $activity_details = array(
                'Status'            =>      'Successfully '. $post_type .' created.',
                'Created '.$post_type =>  array(
                        'ID'            =>      $post_id,
                        'Title'         =>      $post->post_title,
                        'Slug'          =>      $post->post_name, 
                )
            );
            
            $log = array(
                "activity"              =>      ucfirst($post_type).' Created',
                "user_id"               =>      $user->ID,
                "user_details"          =>      $this->getUserDetails($user),
                "activity_details"      =>      $activity_details
            );
            
    
            $this->activityLog($log);
        }

    }
 
    private function updatePostTypeFilter($post_after, $post_before){
        $filters = array( 'post_modified', 'post_modified_gmt' ); 

        $from = [];
        $to = [];

        foreach($post_after as $key => $value){ 
            if($post_after->$key != $post_before->$key && !in_array($key, $filters) ){
                $from[$key] = $post_before->$key;
                $to[$key] = $post_after->$key; 
            }
        } 
        $updated = array(
            'From'  => $from,
            'To'    => $to
        );

        if(empty((array) $from) && empty((array) $to)){
            return false;
        } 
        return $updated;
    }

    public function updatePostTypeActivity($post_ID, $post_after, $post_before){
        $post_type = $post_after->post_type;
        $post_status = $post_after->post_status; 
        
        $filter = array('revision', 'inherit', 'auto-draft');

        if( !in_array($post_status, $filter) && !in_array($post_before->post_type, $filter) && !in_array($post_before->post_status, $filter) && $post_status != 'trash'){
            $user = wp_get_current_user();
            $updated_values = $this->updatePostTypeFilter($post_after, $post_before); 
 
            if(!$updated_values){
                return false;
            }

            $status = 'updated';
            //check if it is restored
            if($post_before->post_status == 'trash' && ($post_after->post_status == 'draft' || $post_after->post_status == 'publish')){
                $status = 'restored';
            }

            $activity_details = array(
                'Status'            =>      'Successfully '. $post_type .' '. $status.'.',
                ucfirst($status).' '.$post_type =>  array(
                        'ID'                =>      $post_ID,
                        'Update Details'    =>      $updated_values
                )
            );
            
            $log = array(
                "activity"              =>      ucfirst($post_type).' '.ucfirst($status),
                "user_id"               =>      $user->ID,
                "user_details"          =>      $this->getUserDetails($user),
                "activity_details"      =>      $activity_details
            );           

            $this->activityLog($log); 
            
        } 
    }

    public function removePostTypeActivity($post_id, $post){
        $post_type = $post->post_type;
        if($post_type != 'revision' && $post->post_status != 'inherit'){
            $user = wp_get_current_user();
            $activity_details = array(
                'Status'            =>      'Successfully '. $post_type .' deleted.',
                'Deleted '.$post_type =>  array(
                        'ID'            =>      $post_id,
                        'Title'         =>      $post->post_title
                )
            );
            
            $log = array(
                "activity"              =>      ucfirst($post_type).' Deleted',
                "user_id"               =>      $user->ID,
                "user_details"          =>      $this->getUserDetails($user),
                "activity_details"      =>      $activity_details
            );           

            $this->activityLog($log);            
        }
    }

    public function trashPostTypeActivity($post_id){
        $user = wp_get_current_user();
        $post = get_post( $post_id );
        $post_type = $post->post_type;
        $activity_details = array(
            'Status'            =>     ucfirst($post_type).' trashed successfully.',
            'Trashed '.$post_type =>  array(
                    'ID'            =>      $post_id,
                    'Title'         =>      $post->post_title
            )
        );
        
        $log = array(
            "activity"              =>      ucfirst($post_type).' Trashed',
            "user_id"               =>      $user->ID,
            "user_details"          =>      $this->getUserDetails($user),
            "activity_details"      =>      $activity_details
        );           

        $this->activityLog($log);  
    }

    public function addTermActivity(int $term_id, int $tt_id, string $taxonomy){
        $user = wp_get_current_user(); 
        
        $term = get_term( $term_id, $taxonomy );

        $activity_details = array(
            'Status'            =>      'Successfully new '.$taxonomy.' created.',
            'Added '.ucfirst($taxonomy) =>  array(
                    'Term ID'                       =>      $term_id,
                    'Taxonomy ID'                   =>      $tt_id,
                    ucfirst($taxonomy).' Name'      =>      $term->name,
                    ucfirst($taxonomy).' Slug'      =>      $term->slug
            )
        );

        $log = array(
            "activity"          =>      ucfirst($taxonomy).' Created',
            "user_id"           =>      $user->ID,
            "user_details"      =>      $this->getUserDetails($user),
            "activity_details"  =>      $activity_details
        );

        $this->activityLog($log); 
    }

    private function editTermFilter($term_before, $term_after){
        $term_after = (object) $term_after;
        $filters = ['term_id', 'term_taxonomy_id', 'filter'];
 
        $from = [];
        $to = [];
        foreach($term_before as $key => $value){
            if( $term_after->$key != $term_before->$key && !in_array($key, $filters)){ 
                $from[$key] = $term_before->$key;
                $to[$key] = $term_after->$key;  
            } 
        }

        $updated = array(
            'From'  => $from,
            'To'    => $to
        ); 
        
        if(empty((array) $from) && empty((array) $to)){
            return false;
        } 
 
        return $updated;
    }

    public function editTermActivity($data, $term_id, $taxonomy, $args){
        
        $term_before = get_term_by( 'id', $term_id, $taxonomy); 
        $term_before->term_meta = get_option( "taxonomy_".$term_id ); 

        $updated_values = $this->editTermFilter($term_before, $args); 
        if(!$updated_values){
            return false;  
        }


        $user = wp_get_current_user(); 

        $activity_details = array(
            'Status'            =>      'Successfully '.$taxonomy.' data updated.',
            'Updated '.ucfirst($taxonomy) =>  array(
                    'Term ID'               =>      $term_id,
                    'Update Details'        =>      $updated_values 
            )
        );

        $log = array(
            "activity"          =>      ucfirst($taxonomy).' Updated',
            "user_id"           =>      $user->ID,
            "user_details"      =>      $this->getUserDetails($user),
            "activity_details"  =>      $activity_details
        );
        
        $this->activityLog($log);

        return $data;
    }

    public function deleteTermActivity($term, $tt_id, $taxonomy, $deleted_term, $object_ids){
        $user = wp_get_current_user(); 

        $activity_details = array(
            'Status'            =>      'Successfully '.$taxonomy.' deleted.',
            'Deleted Term'      =>      $deleted_term 
        );

        $log = array(
            "activity"              =>      ucfirst($taxonomy). ' Deleted',
            "user_id"               =>      $user->ID,
            "user_details"          =>      $this->getUserDetails($user),
            "activity_details"      =>      $activity_details
        );

        $this->activityLog($log); 
    }

    public function addClassActivity($class_id, $className, $school_id, $class_size ){
        $user = wp_get_current_user(); 
         

        $activity_details = array(
            'Status'            =>      'Successfully new class created.',
            'Added new class'   =>  array(
                    'Class ID'      =>      $class_id,
                    'Class Name'    =>      $className, 
                    'School ID'     =>      $school_id, 
                    'Student Size'  =>      $class_size, 
            )
        );

        $log = array(
            "activity"          =>      'Class Created',
            "user_id"           =>      $user->ID,
            "user_details"      =>      $this->getUserDetails($user),
            "activity_details"  =>      $activity_details
        );

        $this->activityLog($log); 
    }

    private function editClassFilter($args, $o_class){
        $updated = [];

        if(!isset($args) && empty($args)){ 
            return false;
        }  
        $keys = array('name', 'students', 'year', 'licence_product', 'teacher_add');
        foreach($keys as $key){ 
            if(isset($args[$key])){ 
                if($key == 'licence_product'){  
                    if(!$args[$key]){
                        $updated['licence'] = 'Licence Removed';
                    }else{  
                        $prev_licence = (!$o_class->$key)?'': $o_class->$key;
        
                        $updated['from'] = array(
                            'licence'  =>  $prev_licence
                        );
                        $updated['to'] = array(
                            'licence'  =>  $args[$key]
                        );
                    } 
                }elseif($key == 'teacher_add'){
                    $teacher = get_user_by_hash($args['teacher_add']);
                    $updated['teacher_id'] = $teacher->ID;
                    if($args['teacher_rem']){ 
                        $updated['message'] = 'Teacher has been removed from the class.';
                    }else{
                        $updated['message'] = 'Teacher has been added to the class.';
                    }

                }else{ 
                    if(empty($args[$key])){
                        $updated[$key] = ucfirst($key).' removed.';
                    }else{
                        $updated['from'] = array(
                            $key  =>  $o_class->$key
                        );
                        $updated['to'] = array(
                            $key  =>  $args[$key]
                        );
                    }
                } 
            }
        } 

        if(empty((array) $updated)){
            return false;
        }

        return $updated;
    
    
    }

    public function editClassActivity($class_id, $args, $o_class = null){
        $user = wp_get_current_user();

        $updated = $this->editClassFilter($args, $o_class); 

        if(!$updated){
            return false;
        }
        
        $activity_details = array(
            'Status'        =>      'Successfully class updated.',
            'class_id'      =>      $class_id,
        ) + $updated;

        $log = array(
            "activity"              =>      'Class Updated',
            "user_id"               =>      $user->ID,
            "user_details"          =>      $this->getUserDetails($user),
            "activity_details"      =>      $activity_details
        );

        $this->activityLog($log); 
    }

    public function archiveClassActivity($class_id){
        $user = wp_get_current_user(); 


        $class = wushka_get_class($class_id);

        $activity_details = array(
            'Status'        =>      'Successfully class archived.',
            'Details'       =>      [
                'Class ID'       =>      $class_id,
                'Class name'     =>      $class->name,
                'Student Size'  =>      $class->students,
            ] 
        );

        $log = array(
            "activity"              =>      'Class Archived',
            "user_id"               =>      $user->ID,
            "user_details"          =>      $this->getUserDetails($user),
            "activity_details"      =>      $activity_details
        );

        $this->activityLog($log); 
    }

    private function setActivityCookie($cookie_name, $cookie_value){
        $cookie_value = encrypt_decrypt('encrypt', $cookie_value);
        setcookie($cookie_name, $cookie_value, strtotime('+1 day'));
    }

    private function getActivityCookie($cookie_name){
        if(!isset($_COOKIE[$cookie_name])){
           return false; 
        }

        $cookie_value = encrypt_decrypt('decrypt', $_COOKIE[$cookie_name]);
        return $cookie_value;
    }

    private function unsetActivityCookie($cookie_name){
        unset($_COOKIE[$cookie_name]);
        setcookie( $cookie_name, ' ', time() - YEAR_IN_SECONDS, SITECOOKIEPATH, COOKIE_DOMAIN );
    }

}