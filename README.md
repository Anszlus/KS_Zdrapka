Aplikacja "Zdrapka Lovelace"

Celem projektu było stworzenie aplikacji do zdrapek, która pozwala użytkownikom na kupowanie zdrapek, zdrapywanie ich, oraz wyświeltnie listy ostatnich otwartych zdrapek oraz tych nieotwartych. Projekt ten ma na celu pokazanie mojej znajomości technologii PHP, JavaScript, CSS oraz obsługi bazy danych MySQL.

W aplikacji została użyta Integracja Sarmacka do Autoryzacji mieszkańca, dzięki której docelowi użytkownicy nie będą musieli tworzyć konta w aplikacji.

Funkcje aplikacji:
* Obsługa sesji użytkownika (Logowanie, Wylogowywanie się oraz proste kominikaty)
* Kupno zdrapki
* Lista nieotworzonych zdrapek
* Lista 10 ostatnich otworzonych zdrapek
* Wyświetlenie zdrapki
* Zdrapywanie zdrapki

Użyte technologie:
* Backend: PHP do obsługi logiki serwera i interakcji z bazą danych.
* Frontend: JavaScript do dynamicznej interakcji z użytkownikiem oraz CSS do stylizacji strony.
* Baza danych: MySQL do przechowywania danych o zdrapkach.

Rozwiązane problemy:
* Połączenie aplikacji z bazą danych
* Weryfikacja użytkowników z wykorzystaniem integracji Sarmackiej
* Stworzenie schematu tabeli przechowującej informacje o zdrapkach
* Stworzenie skryptu generującego zdrapkę w formacie png
* Stworzenie skryptu ustalającego wysokość nagrody w zdrapce
* Stworzenie skryptu ustalającego wygraną zdrapki, aby aplikacja nie generowała kosztów
* Stworzenie skryptu ustalającego ikony na zdrapce w zależności od tego czy jest ona wygrana
* Stworzenie skryptu odpowiadającego za zdrapywanie zdrapki
* Wyświetlanie odpowiedniego widoku aplikacji w zależności od autoryzacji i wybranych opcji
* Pobieranie opłaty za kupno zdrapki od użytkownika i generowanie dla niego produktu
* Wypłata nagrody za wygraną zdrapkę dla użytkownika po zdrapaniu odpowiedniego pola


Pliki projektu:
/css/style.css
Plik ze stylami CSS odpowiadającymi, za wygląd strony

/img/
Folder z grafikami wykorzystywanymi w aplikacji

/views/
Folder z plikami PHP, zawierającymi wygląd wyświetlanych podstron

config.php
Plik przechowujący danych potrzebnych do konfiguracji aplikacji, takich jak połączenie z bazą czy ustawienia aplikacji

index.php
Główny plik aplikacji, obsługujący wykonywanie odpowiednich funkcji aplikacji takich jak obsługa zdarzeń lub wyświetlanie odpowiedniego widoku

init.php
Plik odpowiedzialny za załadowanie konfiguracji, Integracji z Sarmacją oraz ustanowienie połączenia z bazą danych

sarmacja.php
Plik Integracji Sarmackiej, storzony przez NIA

zdrapka.php
Plik odpowiedzialny za generowanie obrazu zdrapki na podstawie ID

zdrapka.sql
Plik zawierający schemat tabeli SQL
