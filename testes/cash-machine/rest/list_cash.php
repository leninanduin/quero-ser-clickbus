<?php
  error_reporting(E_ALL);
  ini_set('display_errors', 'on');
  header('Content-Type: application/json');
  require '../lib/classes.php';

  $cajero =  new Cajero();
  print_r($cajero->listBilletes());
?>