<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8" />
    <title>Zwrot książki</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>

<body>
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $num = $_POST["num"];

        $polaczenie = new mysqli("localhost", "admin", "admin", "biblioteka");

        if ($polaczenie->connect_error) {
            die("Błąd połączenia z bazą danych: " . $polaczenie->connect_error);
        }

        $data_oddania = date("Y-m-d");

        $sql_update = "UPDATE wypozyczenia SET oddano = '$data_oddania' WHERE num = '$num'";

        if ($polaczenie->query($sql_update) === TRUE) {
            echo "<h1>Książka została zwrócona pomyślnie.</h1>";
        } else {
            echo "<h1>Błąd podczas zwracania książki: " . $polaczenie->error . "</h1>";
        }

        $polaczenie->close();
    }
    ?>
    <br>
    <a href='javascript:history.back()'>Wróć</a>
</body>

</html>