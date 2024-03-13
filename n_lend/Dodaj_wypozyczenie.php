<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8" />
    <title>Potwierdzenie wypożyczenia</title>
    <link rel="stylesheet" type="text/css" href="../styles.css">
</head>

<body>
    <nav>
        <div>
            <button onclick="location.href='../view'">Zarządzaj</button>
            <button onclick="location.href='../n_user'">Dodaj czytelnika</button>
            <button onclick="location.href='../n_book'">Dodaj książkę</button>
            <button onclick="location.href='../n_lend'">Wypożycz</button>
        </div>
    </nav>
    <h1>Potwierdzenie wypożyczenia</h1>
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $czytelnik = $_POST["uzytkownik"];
        $kod = $_POST["ksiazka"];
        $data_wypozyczenia = date("Y-m-d");
        $data_zwrotu = date("Y-m-d", strtotime("+1 month"));

        $polaczenie = new mysqli("localhost", "admin", "admin", "biblioteka");

        if ($polaczenie->connect_error) {
            die("Błąd połączenia z bazą danych: " . $polaczenie->connect_error);
        }

        $sql = "INSERT INTO wypozyczenia (czytelnik, kod, wypozyczenie, zwrot) VALUES ('$czytelnik', '$kod', '$data_wypozyczenia', '$data_zwrotu')";

        if ($polaczenie->query($sql) === TRUE) {
            $query_czytelnik = "SELECT imie, nazwisko FROM czytelnicy WHERE czytelnik = '$czytelnik'";
            $result_czytelnik = $polaczenie->query($query_czytelnik);
            $row_czytelnik = $result_czytelnik->fetch_assoc();
            $imie_nazwisko = $row_czytelnik["imie"] . " " . $row_czytelnik["nazwisko"];

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
</body>

</html>