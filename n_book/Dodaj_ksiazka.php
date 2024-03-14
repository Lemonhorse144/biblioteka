<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8" />
    <title>Potwierdzenie dodania książki</title>
    <link rel="stylesheet" type="text/css" href="../styles.css">
</head>

<body>
    <nav>
        <div>
            <button onclick="location.href='../n_lend'">Wypożycz</button>
            <button onclick="location.href='../view'">Zarządzaj</button>
            <button onclick="location.href='../n_user'">Dodaj czytelnika</button>
            <button onclick="location.href='../n_book'">Dodaj książkę</button>
        </div>
    </nav>
    <h1>Potwierdzenie dodania książki</h1>
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $tytul = $_POST["tytul"];
        $autor = $_POST["autor"];
        $isbn = $_POST["isbn"];

        $polaczenie = new mysqli("localhost", "bibliotekarz", "Hhaslo", "biblioteka");

        if ($polaczenie->connect_error) {
            die("Błąd połączenia z bazą danych: " . $polaczenie->connect_error);
        }

        $sql = "INSERT INTO ksiegozbior (tytul, autor, isbn) VALUES ('$tytul', '$autor', '$isbn')";

        if ($polaczenie->query($sql) === TRUE) {
            echo "Dodano książkę o tytule '$tytul', autorze '$autor' i numerze ISBN '$isbn'.";
        } else {
            echo "Błąd podczas dodawania książki: " . $polaczenie->error;
        }

        $polaczenie->close();
    }
    ?>
</body>

</html>