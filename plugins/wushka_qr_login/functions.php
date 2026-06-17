<?php

function qrl_find_schools()
{

    $results = [];

    $results['items'] = [];

    $results['more'] = false;

    global $wpdb;

    $q = $_GET['q'];

    $schools = $wpdb->get_results(
        $wpdb->prepare("SELECT t.* FROM {$wpdb->prefix}terms as t INNER JOIN {$wpdb->prefix}term_taxonomy AS tt ON(t.term_id = tt.term_id) WHERE tt.taxonomy = 'school' AND (t.name LIKE %s OR t.slug LIKE %s)", "%{$q}%", "%{$q}%")
    );

    if (!empty($schools) && !empty($q)) {

        foreach ($schools as $school) {

            $record = [
                'id' => $school->term_id,
                'html' => $school->name . " ($school->slug)",
                'text' => $school->name . " ($school->slug)"
            ];

            array_push($results['items'], $record);
        }
    }

    echo json_encode(['results' => $results]);
    exit;
}


add_action('wp_ajax_qrl_find_schools', 'qrl_find_schools');


function qrl_save_schools(){

  $data = $_POST['data'];

  update_option('disable_QR_login_school',htmlentities(json_encode($data),ENT_QUOTES, "UTF-8"));

  echo json_encode(['success'=> true]);

  exit;

}
add_action('wp_ajax_qrl_save_schools', 'qrl_save_schools');


function qrl_get_saved_schools(){

    $data =  get_option('disable_QR_login_school');


    if(!empty($data)){

        $data = json_decode(stripslashes(htmlspecialchars_decode($data)));

    }

    if(empty($data)){
        
        $data = [];
    }
  
    echo json_encode(['success'=> true,'data'=> $data]);
  
    exit;
  
  }
  add_action('wp_ajax_qrl_get_saved_schools', 'qrl_get_saved_schools');