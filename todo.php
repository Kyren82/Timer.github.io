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
            <?php
                $day = isset($_GET["day"]) ? $_GET["day"] : (isset($_POST["day"]) ? $_POST["day"] : date("d"));
                echo("<p>dzień ".$day.". Co zrobiłeś?</p>");
            ?>
        </div>
        <div id="main">
            <form action="todo.php" method="post">
                <input type="hidden" name="day" value="<?php echo htmlspecialchars($day); ?>">
                <input type="text" name="text" id="text" required>
                <button type="submit">Prześlij</button>
                <?php
                    if(isset($_POST["text"])){
                        $text = $_POST["text"];
                        $formatted_date = date("Y-m-d"); 
                        
                        $conn = mysqli_connect("localhost", "root", "", "staz");
                        
                        if ($conn) {
                            $text_escaped = mysqli_real_escape_string($conn, $text);
                            
                            $check_query = "SELECT * FROM dni WHERE dzien = '$formatted_date'";
                            $result = mysqli_query($conn, $check_query);
                            
                            if (mysqli_num_rows($result) > 0) {
                                $query = "UPDATE dni SET opis = '$text_escaped' WHERE dzien = '$formatted_date'";
                            } else {
                                $query = "INSERT INTO dni (dzien, opis) VALUES ('$formatted_date', '$text_escaped')";
                            }
                            
                            mysqli_query($conn, $query);
                            mysqli_close($conn);
                            
                            header("Location: todo.php?day=".$day);
                            exit();
                        }
                    }
                ?>
            </form>
        </div>
        <div id="footer">
            <p>Autor: umarł z nudów</p>
        </div>
    </div>
</body>
</html>