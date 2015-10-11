<?php
  error_reporting(E_ALL);
  ini_set('display_errors', 'on');

  require '../lib/AltoRouter.php';
  $router = new AltoRouter();
  $router->setBasePath('/quero-ser-clickbus/testes/cash-machine/rest');
  $router->map( 'GET', '/list_cash', 'list_cash.php', 'list-denominations');
  $router->map( 'POST', '/get_cash', 'get_cash.php', 'put-operation');

  /* Match the current request */
  $match = $router->match();
  if($match) {
    require $match['target'];
  } else {
    header("HTTP/1.0 404 Not Found");
    require '404.html';
  }
?>