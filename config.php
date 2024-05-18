<?php

if (!defined('_IN_APP')) {
  header('Location: /');
  exit();
}

$config = [];

/**
 * Konfiguracja połączenia PDO z bazą danych
 */
$config['database'] = [
  'host' => 'localhost',
  'name' => 'root',
  'user' => 'root',
  'pass' => ''
];

/**
 * Konfiguracja integracji z sarmacją
 * appID      -   id instytucji
 * appName    - wyświetlana nazwa aplikacji
 * appSecret  - hasło instytucji do aplikacji
 * address    - adres www naszej aplikacji, 
 *              na który nastąpi przekierowanie z integracji
 * options    - uprawnienia jakie potrzebuje nasza aplikacja
 */
$config['sarmacja'] = array(
  'appId' => '###',
  'appName' => 'Zdrapka',
  'appSecret' => '###',
  'adress' => 'https://example.com/',
  'options' => array(
    'przelew' => true, //jeżeli chcemy aby mozliwe było pobranie autoryzowanej opłaty od użytkownika
    'jednorazowyPrzelew' => 1000, //maksymalna wartość pojedyńczego przelewu jaką może aplikacja wykonać
    'dniowyPrzelew' => 10000, //maksymalna wartość przelewów jaką aplikacja może wykonać w ciągu 24 godzin
    'powiadomienie' => true,
  )
);

/**
 * Konfiguracja aplikacji
 * koszt   - cena zdrapki
 * wygrane - [możliwe wygrane]
 */
$config['app'] = [
  'koszt' => 1,
  'wygrane' => [
    1, 2, 5, 10
  ]
];
