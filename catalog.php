<?php 
    session_start();

    include("database_info.inc");
    $conn = mysqli_connect($server, $username, $password, $database);
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    $sql = "SELECT * FROM BookView;";
    $res = mysqli_query($conn, $sql);
    $books = mysqli_fetch_all($res, MYSQLI_ASSOC);

    //echo json_encode($books);
    mysqli_close($conn);

    //This function creates a <div> with information at a given index in a book array
    function createBookInfo($row) {
        $title = $row["Name"];
        $author = $row["Author"];
        $date = $row["Date"];
        $id = $row["BookID"];

        $disabled = false;
        $restricted = $row["Restricted"];
        $unavailable = ($row["Available"] < 1);

        if (isset($_SESSION["cart"])) {
            $disabled = (isset($_SESSION["cart"][$id]) || count($_SESSION["cart"]) >= 5);
        }

        echo "<div class='book'>
        <img src='$cover' alt='Cover of $title by $author'>
        <h3>$title</h3>
        <p>$author, $date</p>
        <button type='button' id='add-$id' name='add' value='$id' formnovalidate ";
        if ($disabled || $restricted || $unavailable) {
            echo "disabled";
        }
        if ($restricted) {
            echo ">Restricted";
        }
        elseif ($unavailable) {
            echo ">Unavailable";
        }
        else {
            echo ">Add";
        }
        echo "</button></div>";
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $_SESSION["first"] = $_POST["first"] ?? "";
        $_SESSION["last"] = $_POST["last"] ?? "";
        $_SESSION["email"] = $_POST["email"] ?? "";
        $_SESSION["cart"] = json_decode($_POST["cart"], true) ?? [];
        
        header("Location: checkout.php");
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Library Catalog</title>
        <link rel="stylesheet" href="catalog_style.css">
        <script src="catalog_script.js" defer></script>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&family=Libre+Caslon+Condensed:ital,wght@0,400..700;1,400..700&family=Roboto+Slab:wght@100..900&display=swap" rel="stylesheet">
    </head>
    <body>
        <header>
            <h1>Library Catalog</h1>
        </header>
        <main>
            <form method="post" autocomplete="off" id="form">
                <div class="book-grid" data-id="<?= htmlspecialchars(json_encode($books)); ?>" id="books">
                    <?php foreach($books as $row) {
                        createBookInfo($row);
                    }
                    ?>
                </div>
                <div class="checkout-column">
                    <div class="contact">
                        <h2>Contact</h2>
                        <div class="input">
                            <label for="first">First Name:</label>
                            <input type="text" id="first" name="first" required>
                        </div>
                        <div class="input">
                            <label for="last">Last Name:</label>
                            <input type="text" id="last" name="last" required>
                        </div>
                        <div class="input">
                            <label for="email">Email:</label>
                            <input type="email" id="email" name="email" required>
                        </div> 
                    </div>
                    <div class="cart">
                        <h2>Cart</h2>
                        <h4><i>(Maximum of 5)</i></h4>
                        <ul id="cart-list">
                        </ul>
                        <input type="hidden" id="cart-data" name="cart">
                    </div>
                    <div class="submit">
                        <button id="reset" name="reset" type="reset" formnovalidate>Remove All</button>
                        <button name="submit" type="submit" id="submit" disabled>Check Out</button>
                    </div>
                </div>
            </form>
        </main>
        <footer>
            <p>Created by Brynn Sidelinger | Contact at <a href="mailto:bks1029@usnh.edu">bks1029@usnh.edu</a></p>
        </footer>
    </body>
</html>
