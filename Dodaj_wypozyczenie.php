<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8" />
    <title>Potwierdzenie wypożyczenia</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <h1>Potwierdzenie wypożyczenia</h1>
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $czytelnik = $_POST["uzytkownik"];
        $kod = $_POST["ksiazka"];
        // Ustaw datę wypożyczenia na teraz
        $data_wypozyczenia = date("Y-m-d");
        // Ustaw datę zwrotu na miesiąc od teraz
        $data_zwrotu = date("Y-m-d", strtotime("+1 month"));

        $polaczenie = new mysqli("localhost", "admin", "admin", "biblioteka");

        if ($polaczenie->connect_error) {
            die("Błąd połączenia z bazą danych: " . $polaczenie->connect_error);
        }

        $sql = "INSERT INTO wypożyczenia (czytelnik, kod, wypożyczenie, zwrot) VALUES ('$czytelnik', '$kod', '$data_wypozyczenia', '$data_zwrotu')";

        if ($polaczenie->query($sql) === TRUE) {
            // Pobierz imię i nazwisko czytelnika
            $query_czytelnik = "SELECT imie, nazwisko FROM czytelnicy WHERE czytelnik = '$czytelnik'";
            $result_czytelnik = $polaczenie->query($query_czytelnik);
            $row_czytelnik = $result_czytelnik->fetch_assoc();
            $imie_nazwisko = $row_czytelnik["imie"] . " " . $row_czytelnik["nazwisko"];

            // Pobierz tytuł książki
            $query_ksiazka = "SELECT tytul FROM ksiegozbior WHERE kod = '$kod'";
            $result_ksiazka = $polaczenie->query($query_ksiazka);
            $row_ksiazka = $result_ksiazka->fetch_assoc();
            $tytul_ksiazki = $row_ksiazka["tytul"];

            echo "Dodano wypożyczenie dla czytelnika $imie_nazwisko, kodu książki $kod (tytuł: $tytul_ksiazki), na dzień wypożyczenia $data_wypozyczenia, z planowanym zwrotem $data_zwrotu <br>";
        } else {
            echo "Błąd podczas dodawania wypożyczenia: " . $polaczenie->error;
        }

        $polaczenie->close();
    }
    ?>
    <br>
    <a href='index.html'>Wróć</a>
</body>
</html>
