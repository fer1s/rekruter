# rekruter

Rekruter to aplikacja webowa, która ma na celu ułatwienie procesu rekrutacji do projektów szkolnych.

! PROJEKT SZKOLNY !

## Instalacja

1. Sklonuj repozytorium
```bash
git clone https://github.com/fer1s/rekruter
```
2. Zaimportuj bazę danych z pliku `rekruter.sql` do phpMyAdmin. (Nazwij bazę danych `rekruter`)
3. Zamieść pliki na serwerze.
```bash
xampp/htdocs/rekruter
```
4. Uruchom serwer Apache i MySQL w XAMPP.
5. Otwórz przeglądarkę i wpisz adres `http://localhost/rekruter`.


## TODO

-  [x] Strona główna
   -  [x] Wyświetlanie projektów
   -  [x] Przyciski w pasku nawigacji zależne od stanu zalogowania

-  [x] Podstrona ze wszystkimi projektami

-  [x] Podstrona ze szczegółami projektu

-  [ ] Panel użytkownika

-  [ ] Panel administratora
   -  [x] Dodawanie projektów
   -  [ ] Usuwanie projektów
   -  [ ] Edytowanie projektów
   -  [x] Zarządzanie użytkownikami (usuwanie, zmiana uprawnień)
   -  [ ] Eksport CSV/Excel
  
-  [ ] Rekrutowanie