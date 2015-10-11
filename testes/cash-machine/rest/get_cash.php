<?php
  error_reporting(E_ALL);
  ini_set('display_errors', 'on');
  header('Content-Type: application/json');
  require '../lib/classes.php';

  $cajero = new Cajero();
  if (!isset($_POST['amount'])) {

    $cajero->setOperationResult('Please enter an amount of cash.', 'ERROR');
    return print_r($cajero->getOperationResult());
  }

  $amount = (int)$_POST['amount'];
  $cajero = new Cajero();
  return print_r($cajero->getMoney($amount));
?>