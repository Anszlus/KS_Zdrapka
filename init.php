<?php

if (!defined('_IN_APP')) {
  header('Location: /');
  exit();
}

if (!session_id()) {
  session_start();
}

require_once 'config.php';
require_once 'sarmacja.php';

try {
  $db = new PDO(
    'mysql:host=' . $config['database']['host'] . ';dbname=' . $config['database']['name'],
    $config['database']['user'],
    $config['database']['pass']
  );
} catch (PDOException $e) {
  print "Error!: " . $e->getMessage() . "<br/>";
  die();
}
