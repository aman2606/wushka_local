<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include $_SERVER['DOCUMENT_ROOT'] . '/wp-config.php';
//include $_SERVER['DOCUMENT_ROOT'] . '/wp-includes.php';

         
if( isset($_POST)){
     $_SESSION[$_POST['id']] = $_POST['value'];
}
  echo json_encode($_POST); 
 ?>