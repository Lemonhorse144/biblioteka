<!DOCTYPE html>
<html lang="pl">
<head>
<meta charset="UTF-8" />
<title>Dodawanie książki</title>
</head>
<body>
    <h1>Dodawanie książki</h1>
    <form action="dodaj_ksiazke.php" method="post">
        Tytuł:
        <input type="text" name="tytul" maxlength="50">
        Autor:
        <input type="text" name="autor" maxlength="50">
        <input type="submit" value="Zapisz">
    </form>
</body>
</html>
