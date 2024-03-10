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
            echo "Dodano wypożyczenie dla czytelnika $czytelnik, kodu książki $kod, na dzień wypożyczenia $data_wypozyczenia, z planowanym zwrotem $data_zwrotu <br>";
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
