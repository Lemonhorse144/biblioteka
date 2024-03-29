<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8" />
    <title>Potwierdzenie dodania użytkownika</title>
    <link rel="stylesheet" type="text/css" href="../styles.css">
</head>

<body>
    <nav>
        <div>
            <button onclick="location.href='../n_lend'">Wypożycz</button>
            <button onclick="location.href='../view'">Zarządzaj</button>
            <button onclick="location.href='../n_book'">Dodaj książkę</button>
            <button onclick="location.href='../n_user'">Dodaj czytelnika</button>
        </div>
    </nav>
    <h1>Potwierdzenie dodania użytkownika</h1>
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $imie = $_POST["imie"];
        $nazwisko = $_POST["nazwisko"];
        $miejscowosc = $_POST["miejscowosc"];
        $kodpocztowy = $_POST["kodpocztowy"];
        $ulica = $_POST["ulica"];
        $telefon = $_POST["telefon"];
        $mail = $_POST["mail"];

        $polaczenie = new mysqli("localhost", "bibliotekarz", "Hhaslo", "biblioteka");

        if ($polaczenie->connect_error) {
            die("Błąd połączenia z bazą danych: " . $polaczenie->connect_error);
        }

        $query_czytelnicy = "SELECT MAX(czytelnik) AS max_czytelnik FROM czytelnicy";
        $result_czytelnicy = $polaczenie->query($query_czytelnicy);
        $row_czytelnicy = $result_czytelnicy->fetch_assoc();
        $max_czytelnik_czytelnicy = $row_czytelnicy["max_czytelnik"];

        $query_dane = "SELECT MAX(czytelnik) AS max_czytelnik FROM dane";
        $result_dane = $polaczenie->query($query_dane);
        $row_dane = $result_dane->fetch_assoc();
        $max_czytelnik_dane = $row_dane["max_czytelnik"];

        $nowy_czytelnik = max($max_czytelnik_czytelnicy, $max_czytelnik_dane) + 1;

        $sql_czytelnicy = "INSERT INTO czytelnicy (czytelnik, imie, nazwisko) VALUES ('$nowy_czytelnik', '$imie', '$nazwisko')";

        $sql_dane = "INSERT INTO dane (czytelnik, miejscowosc, kodpocztowy, ulica, telefon, mail) 
                        VALUES ('$nowy_czytelnik', '$miejscowosc', '$kodpocztowy', '$ulica', '$telefon', '$mail')";

        if ($polaczenie->query($sql_czytelnicy) === TRUE && $polaczenie->query($sql_dane) === TRUE) {
            echo "Dodano użytkownika o imieniu '$imie', nazwisku '$nazwisko', miejscowości '$miejscowosc', kodzie pocztowym '$kodpocztowy', adresie '$ulica', telefonie '$telefon' i mailu '$mail'.";
        } else {
            echo "Błąd podczas dodawania użytkownika: " . $polaczenie->error;
        }

        $polaczenie->close();
    }
    ?>
</body>

</html>