<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8" />
    <title>Wypożyczenia dla czytelnika</title>
    <link rel="stylesheet" type="text/css" href="../styles.css">
    
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var Zwin = document.getElementById("Zwin");
            var Ukryty = document.getElementById("Ukryty");

            Zwin.addEventListener("click", function () {
                if (Ukryty.style.display === "none") {
                    Ukryty.style.display = "block";
                    Zwin.innerText = "Ukryj dane użytkownika";
                } else {
                    Ukryty.style.display = "none";
                    Zwin.innerText = "Pokaż dane użytkownika";
                }
            });
        });
    </script>
</head>

<body>
    <nav>
        <div>
            <button onclick="location.href='../n_lend'">Wypożycz</button>
            <button onclick="location.href='../n_user'">Dodaj czytelnika</button>
            <button onclick="location.href='../n_book'">Dodaj książkę</button>
            <button onclick="location.href='../view'">Zarządzaj</button>
        </div>
    </nav>
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $czytelnik = $_POST["uzytkownik"];

        $polaczenie = new mysqli("localhost", "admin", "admin", "biblioteka");

        if ($polaczenie->connect_error) {
            die("Błąd połączenia z bazą danych: " . $polaczenie->connect_error);
        }

        $sql_czytelnik = "SELECT czytelnicy.imie, czytelnicy.nazwisko, dane.miejscowosc, dane.kodpocztowy, dane.ulica, dane.telefon, dane.mail 
        FROM czytelnicy JOIN dane ON czytelnicy.czytelnik = dane.czytelnik 
        WHERE czytelnicy.czytelnik = '$czytelnik'";
        $result_czytelnik = $polaczenie->query($sql_czytelnik);

        if ($result_czytelnik->num_rows > 0) {
            $row_czytelnik = $result_czytelnik->fetch_assoc();
            echo "<h1>Czytelnik: " . $row_czytelnik["imie"] . " " . $row_czytelnik["nazwisko"] . "</h1>
            <br><button id='Zwin'>Pokaż dane użytkownika</button>
            <div id='Ukryty' style='display: none;'>
                <table><tbody>
                    <tr><td>Adres</td><td>" . $row_czytelnik["ulica"] . "</td></tr>
                    <tr><td>Miejscowość</td><td>" . $row_czytelnik["miejscowosc"] . ", " . $row_czytelnik["kodpocztowy"] . "</td></tr>
                    <tr><td>Telefon</td><td>" . $row_czytelnik["telefon"] . "</td></tr>
                    <tr><td>Mail</td><td>" . $row_czytelnik["mail"] . "</td></tr>
                </tbody></table>
            </div>
            ";
        } else {
            echo "Brak danych dla podanego czytelnika.";
        }

        $sql_wypozyczenia = "SELECT wypozyczenia.num, wypozyczenia.czytelnik, wypozyczenia.kod, wypozyczenia.wypozyczenie, wypozyczenia.zwrot, ksiegozbior.tytul, ksiegozbior.autor
        FROM wypozyczenia JOIN ksiegozbior ON wypozyczenia.kod = ksiegozbior.kod
        WHERE wypozyczenia.czytelnik = '$czytelnik' AND wypozyczenia.oddano IS NULL
        ORDER BY wypozyczenia.zwrot ASC;";
        $result_wypozyczenia = $polaczenie->query($sql_wypozyczenia);
        echo "<h2>Wypożyczone: " . $result_wypozyczenia->num_rows . "</h2>";

        if ($result_wypozyczenia->num_rows > 0) {
            echo "<table>";
            echo "<tr>
                <th>Tytuł, Autor</th>
                <th>Wypożyczono</th>
                <th>Termin</th>
                <th>Akcje</th>
            </tr>";

            while ($row_wypozyczenia = $result_wypozyczenia->fetch_assoc()) {
                $roznica = floor((strtotime($row_wypozyczenia['zwrot']) - strtotime(date("Y-m-d"))) / 86400);

                echo "<tr>
                    <td>" . $row_wypozyczenia["tytul"] . ", " . $row_wypozyczenia["autor"] . "</td>
                    <td>" . $row_wypozyczenia["wypozyczenie"] . "</td>
                    <td>";
                if ($roznica < 0) {
                    echo "<b>" . $row_wypozyczenia["zwrot"] . ", " . $roznica . " dni </b>";
                } else {
                    echo $row_wypozyczenia["zwrot"] . ", " . $roznica . " dni";
                }
                echo "</td>
                    <td>
                        <form method='post' action='Prolonguj.php'>
                            <input type='hidden' name='num' value='" . $row_wypozyczenia["num"] . "'>
                            <button type='submit' name='action' value='Prolonguj'>Prolonguj</button>
                        </form>
                        <form method='post' action='Zwroc.php'>
                            <input type='hidden' name='num' value='" . $row_wypozyczenia["num"] . "'>
                            <button type='submit' name='action' value='Zwroc'>Zwróć</button>
                        </form>
                    </td>
                </tr>";
            }
            echo "</table>";
        } else {
            echo "Brak wypożyczeń dla podanego czytelnika.";
        }

        $sql_zwroty = "SELECT wypozyczenia.num, wypozyczenia.czytelnik, wypozyczenia.kod, wypozyczenia.wypozyczenie,
        wypozyczenia.zwrot, wypozyczenia.oddano, ksiegozbior.tytul, ksiegozbior.autor
        FROM wypozyczenia JOIN ksiegozbior ON wypozyczenia.kod = ksiegozbior.kod
        WHERE wypozyczenia.czytelnik = '$czytelnik' AND wypozyczenia.oddano IS NOT NULL
        ORDER BY wypozyczenia.oddano DESC;";
        $result_zwroty = $polaczenie->query($sql_zwroty);
        echo "<h2>Zwrócone: " . $result_zwroty->num_rows . "</h2>";

        if ($result_zwroty->num_rows > 0) {
            echo "<table>";
            echo "<tr>
                <th>Tytuł, Autor</th>
                <th>Wypożyczono</th>
                <th>Termin</th>
                <th>Oddano</th>
            </tr>";

            while ($row_zwroty = $result_zwroty->fetch_assoc()) {
                echo "<tr>
                    <td>" . $row_zwroty["tytul"] . ", " . $row_zwroty["autor"] . "</td>
                    <td>" . $row_zwroty["wypozyczenie"] . "</td>
                    <td>" . $row_zwroty["zwrot"] . "</td>
                    <td>" . $row_zwroty["oddano"] . "</td>
                </tr>";
            }

            echo "</table>";
        } else {
            echo "Brak zwrotów dla podanego czytelnika.";
        }

        $polaczenie->close();
    }
    ?>
</body>

</html>
