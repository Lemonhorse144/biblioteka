<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8" />
    <title>Szczegóły wypożyczenia</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $czytelnik = $_POST["uzytkownik"];

        $polaczenie = new mysqli("localhost", "admin", "admin", "biblioteka");

        if ($polaczenie->connect_error) {
            die("Błąd połączenia z bazą danych: " . $polaczenie->connect_error);
        }

        // Pobierz dane z tabeli czytelnicy
        $sql_czytelnik = "SELECT imie, nazwisko FROM czytelnicy WHERE czytelnik = '$czytelnik'";
        $wynik_czytelnik = $polaczenie->query($sql_czytelnik);

        if ($wynik_czytelnik->num_rows > 0) {
            $row_czytelnik = $wynik_czytelnik->fetch_assoc();
            echo "<h1>Szczegóły wypożyczenia dla: {$row_czytelnik['imie']} {$row_czytelnik['nazwisko']}</h1>";
        }

        // Pobierz dane z tabeli wypozyczenia
        $sql_wypozyczenia = "SELECT wypozyczenie, zwrot, kod FROM wypozyczenia WHERE czytelnik = '$czytelnik'";
        $wynik_wypozyczenia = $polaczenie->query($sql_wypozyczenia);

        if ($wynik_wypozyczenia->num_rows > 0) {
            echo "<table border='1'>";
            echo "<tr><th>Wypożyczenie</th><th>Zwrot</th><th>Kod</th><th>Tytuł</th><th>Autor</th></tr>";

            while ($row_wypozyczenia = $wynik_wypozyczenia->fetch_assoc()) {
                $data_wypozyczenia = $row_wypozyczenia['wypozyczenie'];
                $data_zwrotu = $row_wypozyczenia['zwrot'];
                $kod_ksiazki = $row_wypozyczenia['kod'];

                // Pobierz dane z tabeli ksiegozbior
                $sql_ksiegozbior = "SELECT tytul, autor FROM ksiegozbior WHERE kod = '$kod_ksiazki'";
                $wynik_ksiegozbior = $polaczenie->query($sql_ksiegozbior);

                if ($wynik_ksiegozbior->num_rows > 0) {
                    $row_ksiegozbior = $wynik_ksiegozbior->fetch_assoc();
                    $tytul = $row_ksiegozbior['tytul'];
                    $autor = $row_ksiegozbior['autor'];
                } else {
                    $tytul = "Brak danych";
                    $autor = "Brak danych";
                }

                echo "<tr><td>$data_wypozyczenia</td><td>$data_zwrotu</td><td>$kod_ksiazki</td><td>$tytul</td><td>$autor</td></tr>";
            }

            echo "</table>";
        } else {
            echo "Brak danych wypożyczeń dla użytkownika o ID: $czytelnik";
        }

        $polaczenie->close();
    }
    ?>
    <br>
    <a href='index.html'>Wróć</a>
</body>
</html>
