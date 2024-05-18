<?php
define("_IN_APP", 1);

require_once 'init.php';

$sarmacja = new Integracja;
$sarmacja->setConfiguration($config['sarmacja']);
$user = $sarmacja->getUser();
$wynik = $sarmacja->getWynik();

if (isset($wynik['error']) && $wynik['error'] == 666) {
  $info = 'Dokonano zmiany wymaganych uprawnień. Przeautoryzuj się';
  require 'views/login.php';
  exit();
}
if (isset($wynik['error']) && $wynik['error'] != 200) { //jeżeli wystąpił błąd w żądaniu
  $info = 'Wyskaczyl jakis blad ' . $wynik['errorD'];
  require 'views/login.php';
  exit();
}

if (!isset($user) || empty($user) || is_null($user)) {
  //jeżeli user nie jest zalogowany
  $info = 'Zaloguj się, aby zagrać';
  require 'views/login.php';
  exit();
}

if (isset($_GET['wyloguj'])) {
  session_destroy();
  header('Location: /');
  exit();
}

/**
 * ZALOGOWANY
 */
$kup = (int)($_GET['kup'] ?? 0);
if ($kup) {
  if ($kup < (time() - 60 * 60)) {
    header('Location: /');
    exit();
  }

  $danePrzelewu = array(
    'nadawca' => $user['paszport'], // od kogo chcemy zabrać kase. Najczęściej jeśli użytkownik zalogowany to wpidujemy tu $user['paszport']
    'tytul' => 'Kupno zdrapki Lovelace',
    'kwota' => $config['app']['koszt']
  ); // kwota przelewu. Dopuszczalne są wartości z kropeczką.
  $sarmacja->przelew($danePrzelewu); // wykonanie przelewu
  $wynikDzialania = $sarmacja->getWynik(); // wynik przelewu

  if (isset($wynikDzialania['error']) && $wynikDzialania['error'] != 200) { //jeżeli wystąpił błąd w żądaniu
    $_SESSION['info'] = 'Wyskaczyl jakis blad ' . $wynikDzialania['errorD'];
  } elseif (isset($wynikDzialania['error']) && $wynikDzialania['error'] == 200) {
    $_SESSION['info'] = 'Zakupiono nową zdrapkę';

    $statusSQL = "SELECT (SELECT SUM(nagroda) FROM `zdrapka` WHERE `wygrana` = 1) as wygrane, (SELECT COUNT(*) FROM `zdrapka`) as ilosc;";
    $status = $db->prepare($statusSQL);
    $status->execute();
    $status = $status->fetch(PDO::FETCH_ASSOC);

    $max_wygrana = ($config['app']['koszt'] * $status['ilosc']) - $status['wygrane'];

    $wygrana = 0;
    if ($max_wygrana > $config['app']['koszt']) {
      $wygrana = rand(0, 1);
    }

    if ($wygrana) {
      $wygrane_mniejsze_niz = array_filter($config['app']['wygrane'], function ($value) use ($max_wygrana) {
        return $value < $max_wygrana;
      });

      $nagroda = $wygrane_mniejsze_niz[array_rand($wygrane_mniejsze_niz)];
    } else {
      $nagroda = $config['app']['wygrane'][array_rand($config['app']['wygrane'])];
    }

    if ($nagroda == $config['app']['koszt']) {
      $wygrana = 1;
    }


    function losowa_liczba($start, $end, $not) 
    {
      if($start >= $end) return $start;
      
      do {
        $liczba = rand($start, $end);  
      } while ($liczba == $not);
      return $liczba;
    }
    function generuj_liczby($wygrana = 0)
    {
      // losuję wygraną liczbę
      $min = 0;
      $max = 2;
      $liczba = rand(0,2);
      if($wygrana == 1)
      {
        return [$liczba, $liczba, $liczba];
      }

      $return_array = [
        $liczba,
        losowa_liczba($min, $max, $liczba),
        losowa_liczba($min, $max, $liczba)
      ];

      // mieszam 
      shuffle($return_array);
      return $return_array;
    }

    $liczby = generuj_liczby($wygrana);

    $zdrapki = $db->prepare(
      "INSERT INTO `zdrapka`(`user_id`, `liczba_1`, `liczba_2`, `liczba_3`, `nagroda`, `wygrana`) 
        VALUES (:user_id, :l1, :l2, :l3, :nagroda, :wygrana)"
    );
    $zdrapki->bindValue(':user_id', $user['paszport'], PDO::PARAM_STR);
    $zdrapki->bindValue(':l1', $liczby[0], PDO::PARAM_INT);
    $zdrapki->bindValue(':l2', $liczby[1], PDO::PARAM_INT);
    $zdrapki->bindValue(':l3', $liczby[2], PDO::PARAM_INT);
    $zdrapki->bindValue(':nagroda', $nagroda, PDO::PARAM_INT);
    $zdrapki->bindValue(':wygrana', $wygrana, PDO::PARAM_INT);
    $zdrapki->execute();

    $new_id = $db->lastInsertId();
    header('Location: /#zdrapka' . $new_id);
    exit();
  }
  header('Location: /');
  exit();
}

$otworz_id = $_GET['oid'] ?? 0;

if ($otworz_id && $_SERVER["REQUEST_METHOD"] == "POST") {
  $info = '';
  $zdrapka = $db->prepare(
    "SELECT * FROM `zdrapka` WHERE `user_id` = :user_id AND `id` = :zid"
  );
  $zdrapka->bindValue(':zid', $otworz_id, PDO::PARAM_INT);
  $zdrapka->bindValue(':user_id', $user['paszport'], PDO::PARAM_STR);
  $zdrapka->execute();
  $zdrapka = $zdrapka->fetch(PDO::FETCH_ASSOC);

  if (!$zdrapka) {
    $info = 'Nie posiadasz takiej zdrapki';
  } else {
    $update_zdrapka = $db->prepare("UPDATE `zdrapka` SET `data_otwarcia`=CURRENT_TIMESTAMP() WHERE id = :zid");
    $update_zdrapka->bindValue(':zid', $otworz_id, PDO::PARAM_INT);
    $update_zdrapka->execute();

    if ($zdrapka['wygrana']) {
      $info = 'Wygrałeś <b>' . $zdrapka['nagroda'] . '</b> lt.';
      $info = '';

      $danePrzelewu = array(
        'odbiorca' => $user['paszport'],
        'kwota' => $zdrapka['nagroda'],
        'tytul' => 'Nagroda ze zdrapki #' . $zdrapka['id']
      );
      $sarmacja->action($danePrzelewu, 'przelewOdInstytucji'); //pierwszy argument to tablica danych, drugi to nazwa opcji wywołania
      $wynikDzialania = $sarmacja->getWynik();

      if (isset($wynikDzialania['error']) && $wynikDzialania['error'] != 200) { //jeżeli wystąpił błąd w żądaniu
        $info .= '<br/>Wyskaczyl jakis blad: ' . $wynikDzialania['errorD'];
      }
    } else {
      $info = 'Może następnym razem pójdzie lepiej.';
      $info = '';
    }
  }
  echo $info;
}

$zdrapka_id = $_GET['zid'] ?? 0;

if ($zdrapka_id) {
  
  $zdrapka = $db->prepare("SELECT * FROM `zdrapka` WHERE `id` = :zid AND `user_id` = :user_id");
  $zdrapka->bindValue(':zid', $zdrapka_id, PDO::PARAM_INT);
  $zdrapka->bindValue(':user_id', $user['paszport'], PDO::PARAM_STR);
  $zdrapka->execute();
  $zdrapka = $zdrapka->fetch(PDO::FETCH_ASSOC);

  if (!$zdrapka) {
    $_SESSION['info'] = 'Taka zdrapka nie istnieje lub nie należy do Ciebie';
    header('Location: /');
    exit();
  }

  if ($zdrapka['data_otwarcia']) {
    header('Location: /zdrapka.php?zid=' . $zdrapka['id'] . '');
    exit();
  }
  
  $file_name = 'views/zdrapka.php';
  require 'views/main.php';
  exit();
}

$info = '';
if (isset($_SESSION['info'])) {
  $info = $_SESSION['info'];
  $_SESSION['info'] = '';
}

// ZDRAPKI
$zdrapki = $db->prepare(
  "SELECT * FROM `zdrapka` WHERE `user_id` = :user_id AND `data_otwarcia` IS NULL ORDER BY `zdrapka`.`id` DESC"
);
$zdrapki->bindValue(':user_id', $user['paszport'], PDO::PARAM_STR);
$zdrapki->execute();
$zdrapki = $zdrapki->fetchAll(PDO::FETCH_ASSOC);


$zdrapki_otwarte = $db->prepare(
  "SELECT * FROM `zdrapka` WHERE `user_id` = :user_id AND `data_otwarcia` IS NOT NULL ORDER BY `zdrapka`.`data_otwarcia` DESC LIMIT 10"
);
$zdrapki_otwarte->bindValue(':user_id', $user['paszport'], PDO::PARAM_STR);
$zdrapki_otwarte->execute();
$zdrapki_otwarte = $zdrapki_otwarte->fetchAll(PDO::FETCH_ASSOC);


$file_name = 'views/home.php';
require 'views/main.php';
