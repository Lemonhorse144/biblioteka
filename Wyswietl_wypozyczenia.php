<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8" />
    <title>Wypożyczenia dla czytelnika</title>
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

        // Pobierz imię i nazwisko czytelnika
        $sql_czytelnik = "SELECT imie, nazwisko FROM czytelnicy WHERE czytelnik = '$czytelnik'";
        $result_czytelnik = $polaczenie->query($sql_czytelnik);

        if ($result_czytelnik->num_rows > 0) {
            $row_czytelnik = $result_czytelnik->fetch_assoc();
            echo "<h1>Wypożyczenia dla czytelnika: " . $row_czytelnik["imie"] . " " . $row_czytelnik["nazwisko"] . "</h1>";
        } else {
            echo "Brak danych dla podanego czytelnika.";
        }

        // Pobierz wypożyczenia
        $sql_wypozyczenia = "SELECT wypożyczenia.czytelnik, wypożyczenia.kod, wypożyczenia.wypożyczenie, wypożyczenia.zwrot, ksiegozbior.tytul
                            FROM wypożyczenia
                            JOIN ksiegozbior ON wypożyczenia.kod = ksiegozbior.kod
                            WHERE wypożyczenia.czytelnik = '$czytelnik'";
        $result_wypozyczenia = $polaczenie->query($sql_wypozyczenia);

        if ($result_wypozyczenia->num_rows > 0) {
            echo "<table>";
            echo "<tr><th>Kod książki</th><th>Tytuł książki</th><th>Data wypożyczenia</th><th>Data zwrotu</th></tr>";

            while ($row_wypozyczenia = $result_wypozyczenia->fetch_assoc()) {
                echo "<tr><td>" . $row_wypozyczenia["kod"] . "</td><td>" . $row_wypozyczenia["tytul"] . "</td><td>" . $row_wypozyczenia["wypożyczenie"] . "</td><td>" . $row_wypozyczenia["zwrot"] . "</td></tr>";
            }

            echo "</table>";
        } else {
            echo "Brak wypożyczeń dla podanego czytelnika.";
        }

        $polaczenie->close();
    }
    ?>
    <br>
    <a href='index.html'>Wróć</a>
</body>
</html>