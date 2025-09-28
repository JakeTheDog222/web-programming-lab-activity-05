<?php
require_once "../classes/book.php";
$bookObj = new Book();

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (isset($_GET["id"])) {
        $bid = intval($_GET["id"]);
        $book = $bookObj->fetchBook($bid);

        if (!$book) {
            echo "<a href='viewBook.php'>View Books</a>";
            exit("No book found");
        } else {
            $bookObj->deleteBook($bid);
            header("Location: viewBook.php");
            exit();
        }
    } else {
        echo "<a href='viewBook.php'>View Books</a>";
        exit("No book found");
    }
}
?>
