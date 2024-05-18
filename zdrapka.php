<?php
// Ustawienie nagłówki obrazu
define("_IN_APP", 1);

require_once 'init.php';

$zdrapka_id = $_GET['zid'] ?? 0;
$pokaz_zdrapke = (isset($_GET['open'])) ? true : false;

$zdrapka = $db->prepare("SELECT * FROM `zdrapka` WHERE `id` = :zid");
$zdrapka->bindValue(':zid', $zdrapka_id, PDO::PARAM_INT);
$zdrapka->execute();
$zdrapka = $zdrapka->fetch(PDO::FETCH_ASSOC);

if (!$zdrapka) {
  echo 'Taka zdrapka nie istnieje';
  exit();
}

// Utworzenie obrazu
$im = imagecreatetruecolor(300, 300);

// Załadowanie obrazka tła
$tlo = imagecreatefrompng('img/zdrapka.png');

// Ustawienie koloru tła na obrazku
imagecopy($im, $tlo, 0, 0, 0, 0, 300, 300);

$white = imagecolorallocate($im, 255, 255, 255);
$black = imagecolorallocate($im, 0, 0, 0);

if ($pokaz_zdrapke || $zdrapka['data_otwarcia']) {
  $icon_pos_y = 140;
  add_icon($im, $zdrapka['liczba_1'], 54, $icon_pos_y);
  add_icon($im, $zdrapka['liczba_2'], 119, $icon_pos_y);
  add_icon($im, $zdrapka['liczba_3'], 183, $icon_pos_y);
}

if ($pokaz_zdrapke || $zdrapka['data_otwarcia']) {
  add_text($im, 4, 'Nagroda: ' . $zdrapka['nagroda'] . ' lt', 50, 210, $black);
}

// Wyświetlam warunki wygranej
add_text($im, 2, 'Znajdz trzy takie same ikony, aby wygrac', 30, 240, $black);
// Wyświetlam ID zdrapki
add_text($im, 2, 'Love#' . $zdrapka['id'] . '', 30, 260, $black);
// Wyświetlam informacje o autorze ikon
add_text($im, 1, 'icons designed by BZZRINCANTATION', 30, 280, $black);

header('Content-Type: image/png');

// Wyświetlam wygenerowaną zdrapkę
imagepng($im);

// Niszcze niepotrzebne już obrazy, aby zwolnić zasoby
imagedestroy($im);
imagedestroy($tlo);

// Ikona Króla, Wieży i Konia autorstwa:
// https://www.flaticon.com/authors/bzzrincantation

// rysowanie ikon
function add_icon($im, $number, $x, $y)
{
  if ($number == 1) $img_path = 'img/krol.png';
  elseif ($number == 0) $img_path = 'img/wieza.png';
  else $img_path = 'img/kon.png';
  $icon = imagecreatefrompng($img_path);
  $icon_width = imagesx($icon);
  $icon_height = imagesy($icon);

  imagecopy($im, $icon, $x, $y, 0, 0, $icon_width, $icon_height);
}

// wyświetlanie tekstu
function add_text($im, $font, $text, $x, $y, $color)
{
  imagestring($im, $font, $x, $y, $text, $color);
}
