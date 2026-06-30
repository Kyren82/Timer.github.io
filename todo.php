<?php
$day = isset($_GET["day"]) ? $_GET["day"] : (isset($_POST["day"]) ? $_POST["day"] : date("d"));

if (isset($_POST["text"])) {
    $text = $_POST["text"];
    $formatted_date = date("Y-m-") . sprintf("%02d", $day);

    $conn = mysqli_connect("localhost", "root", "", "staz");

    if ($conn) {
        $text_escaped = mysqli_real_escape_string($conn, $text);
        $date_escaped = mysqli_real_escape_string($conn, $formatted_date);

        $check_query = "SELECT * FROM dni WHERE dzien = '$date_escaped'";
        $result = mysqli_query($conn, $check_query);

        if (!$result) {
            die("Błąd zapytania SELECT: " . mysqli_error($conn));
        }

        if (mysqli_num_rows($result) > 0) {
            $query = "UPDATE dni SET opis = '$text_escaped' WHERE dzien = '$date_escaped'";
        } else {
            $query = "INSERT INTO dni (dzien, opis) VALUES ('$date_escaped', '$text_escaped')";
        }

        if (!mysqli_query($conn, $query)) {
            die("Błąd zapytania zapisu (INSERT/UPDATE): " . mysqli_error($conn) . "<br>Zapytanie: " . $query);
        }

        mysqli_close($conn);

        header("Location: todo.php?day=" . urlencode($day));
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Licznik</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div id="wrapper">
        <div id="header">
            <?php echo("<p>dzień ".htmlspecialchars($day).". Co zrobiłeś?</p>"); ?>
        </div>
        <div id="main">
            <form action="todo.php?day=<?php echo urlencode($day); ?>" method="post">
                <input type="hidden" name="day" value="<?php echo htmlspecialchars($day); ?>">
                <input type="text" name="text" id="text" required>
                <button type="submit">Prześlij</button>
            </form>
        </div>
        <div id="footer">
            <p>Autor: umarł z nudów</p>
        </div>
    </div>
</body>
</html>