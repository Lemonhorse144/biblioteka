<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8" />
    <title>Prolonguj wypożyczenie</title>
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

        $termin = date("Y-m-d", strtotime("+1 month"));

        $sql_update = "UPDATE wypozyczenia SET zwrot = '$termin' WHERE num = '$num'";

        if ($polaczenie->query($sql_update) === TRUE) {
            echo "<h1>Prolongowano wypożyczenie książki do: $termin</h1>";
        } else {
            echo "<h1>Błąd podczas prolongowania wypożyczenia: " . $polaczenie->error . "</h1>";
        }

        $polaczenie->close();
    }
    ?>
    <br>
    <a href='javascript:history.back()'>Wróć</a>
</body>

</html>