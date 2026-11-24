<?php
    session_start();
    $error = "Submission failed. Please try again";
    $invalid_e = "Unfortunately, you did not enter a valid email address. Please try again with a valid email.";
    $invalde_n = "You did not enter a valid name. Please enter your name again.";
    $invalid = false;
    $due = date("l\, F jS\, Y", strtotime("+ 2 weeks"));

    //These make it so the information is saved on this page on refresh, but resets when you go back to index.php
    //Not sure if this is the best way to do this but it appears to work
    if(isset($_SESSION["cart"])) {
        $_SESSION["order"] = $_SESSION["cart"];
        unset($_SESSION["cart"]);
    }
    if (isset($_SESSION["first"]) && isset($_SESSION["last"]) && isset($_SESSION["email"])) {
        $_SESSION["contact"]["first"] = $_SESSION["first"];
        $_SESSION["contact"]["last"] = $_SESSION["last"];
        $_SESSION["contact"]["email"] = $_SESSION["email"];
        unset($_SESSION["first"]);
        unset($_SESSION["last"]);
        unset($_SESSION["email"]);
    }

    //This fills the summary grid with the books ordered
    function createSummary() {
        foreach($_SESSION["order"] as $book) {
            $title = $book["Name"];
            $author = $book["Author"];
            $cover = $book["cover"];
            $date = $book["Date"];
            echo "<div class='book'>
                <img src='$cover' alt='Cover of $title by $author'>
                <h3>$title</h3>
                <p>$author, $date</p></div>";
        }
    }
?>

<!DOCTYPE HTML>
<html>
    <head>
        <title>Library Checkout</title>
        <link rel="stylesheet" href="catalog_style.css">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&family=Libre+Caslon+Condensed:ital,wght@0,400..700;1,400..700&family=Roboto+Slab:wght@100..900&display=swap" rel="stylesheet">
    </head>
    <body>
        <div class="checkout-page">
            <header>
                <h1>Library Checkout</h1>
            </header>
            <main class="checkout-body">
                <div class="message">
                    <?php 
                        $first = htmlspecialchars($_SESSION["contact"]["first"]);
                        $last = htmlspecialchars($_SESSION["contact"]["last"]);
                        $email = htmlspecialchars($_SESSION["contact"]["email"]);

                        //Form should prevent user from entering invalid data, but it checks again here to be safe
                        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                            echo "<p>$invalid_e</p>";
                            $invalid = true;
                        }
                        elseif (strlen($first) === 0 || strlen($last) === 0) {
                            echo "<p>$invalid_n</p>";
                            $invalid = true;
                        }
                        else {
                            echo "<p>Hi $first,</p>
                            <p>Thank you for checking out with our library. You will receive an email confirming your book order to <u>$email.</u>
                            Please make sure to return your book(s) on or before <b>$due</b>.</p> 
                            <p>Late returns will be subject to fines. If you have any questions, visit our front desk or contact us at <u>ourlibrary@email.com</u></p>";
                        }
                    ?>
                    <p>Click <a href="catalog.php">here</a> to return to the catalog!</p>
                </div>
                <div class="summary"> 
                    <h2>Order Summary:</h2>
                    <div class="summary-grid">
                        <?php if (!$invalid) {
                            createSummary();
                        }
                        else {
                            echo "<p>No books ordered.</p>";
                        }
                        ?>
                    </div>
                </div>
            </main>
            <footer>
                <p>Created by Brynn Sidelinger | Contact at <a href="mailto:bks1029@usnh.edu">bks1029@usnh.edu</a></p>
            </footer>
        </div>
    </body>
</html>

