<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once "../classes/book.php";
$bookObj = new Book();

$book = [];
$errors = [
    "title" => "",
    "author" => "",
    "genre" => "",
    "published" => ""
];

// -----------------------------
// GET Request: Load book data
// -----------------------------
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (isset($_GET["id"])) {
        $bid = intval($_GET["id"]); // ensure numeric
        $book = $bookObj->fetchBook($bid);

        if (!$book) {
            echo "<a href='viewBook.php'>View Books</a>";
            exit("No book found");
        }
    } else {
        echo "<a href='viewBook.php'>View Books</a>";
        exit("No book found");
    }
}

// -----------------------------
// POST Request: Update book
// -----------------------------
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $book["id"] = isset($_POST["id"]) ? intval($_POST["id"]) : 0;
    $book["title"] = trim(htmlspecialchars($_POST["title"] ?? ""));
    $book["author"] = trim(htmlspecialchars($_POST["author"] ?? ""));
    $book["genre"] = trim(htmlspecialchars($_POST["genre"] ?? ""));
    $book["published"] = trim(htmlspecialchars($_POST["published"] ?? ""));

    // Validation
    if (empty($book["title"])) {
        $errors["title"] = "Title is required";
    }
    if (empty($book["author"])) {
        $errors["author"] = "Author is required";
    }
    if (empty($book["genre"])) {
        $errors["genre"] = "Please select a genre";
    }
    if (empty($book["published"])) {
        $errors["published"] = "Publication date is required";
    } elseif (strtotime($book["published"]) > strtotime(date("Y-m-d"))) {
        $errors["published"] = "Publication date cannot be in the future";
    }

    // If no errors, update the book
    if (!array_filter($errors)) {
        $bookObj->title = $book["title"];
        $bookObj->author = $book["author"];
        $bookObj->genre = $book["genre"];
        $bookObj->published = $book["published"];

        if ($bookObj->editBook($book["id"])) {
            header("Location: viewBook.php");
            exit();
        } else {
            echo "<p style='color:red'>Error: Failed to update book.</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Book</title>
<style>
    body { font-family: Arial, sans-serif; margin: 20px; }
    label { display: block; margin-top: 10px; }
    input, select { margin-top: 5px; padding: 5px; width: 250px; }
    .errors { color: red; font-size: 14px; margin: 0; }
    span { color: red; }
</style>
</head>
<body>
<h1>Edit Book</h1>
<form action="" method="post">
    <input type="hidden" name="id" value="<?= $book["id"] ?? "" ?>">

    <label for="title">Title <span>*</span></label>
    <input type="text" name="title" id="title" value="<?= htmlspecialchars($book["title"] ?? "") ?>">
    <p class="errors"><?= $errors["title"] ?></p>

    <label for="author">Author <span>*</span></label>
    <input type="text" name="author" id="author" value="<?= htmlspecialchars($book["author"] ?? "") ?>">
    <p class="errors"><?= $errors["author"] ?></p>

    <label for="genre">Genre <span>*</span></label>
    <select name="genre" id="genre">
        <option value="">--Select--</option>
        <option value="history" <?= (isset($book["genre"]) && $book["genre"] == "history") ? "selected" : "" ?>>History</option>
        <option value="science" <?= (isset($book["genre"]) && $book["genre"] == "science") ? "selected" : "" ?>>Science</option>
        <option value="fiction" <?= (isset($book["genre"]) && $book["genre"] == "fiction") ? "selected" : "" ?>>Fiction</option>
    </select>
    <p class="errors"><?= $errors["genre"] ?></p>

    <label for="published">Date Published <span>*</span></label>
    <input type="date" name="published" id="published" value="<?= htmlspecialchars($book["published"] ?? "") ?>">
    <p class="errors"><?= $errors["published"] ?></p>

    <br><br>
    <input type="submit" value="Save Changes">
</form>
</body>
</html>
