<?php
require_once "../classes/book.php";

$books = ["title"=>"", "author"=>"","genre"=>"", "published"=>""];
$errors = ["title"=>"", "author"=>"","genre"=>"", "published"=>""];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $books["title"] = trim(htmlspecialchars($_POST["title"]));
    $books["author"] = trim(htmlspecialchars($_POST["author"]));
    $books["genre"] = trim(htmlspecialchars($_POST["genre"]));
    $books["published"] = trim(htmlspecialchars($_POST["published"]));

    if (empty($books["title"])) {
        $errors["title"] = "Title is required";
    }
    if (empty($books["author"])) {
        $errors["author"] = "Author is required";
    }
    if (empty($books["genre"])) {
        $errors["genre"] = "Please select a genre";
    }
    if (empty($books["published"])) {
        $errors["published"] = "Publication date is required";
    } elseif (strtotime($books["published"]) > strtotime(date("Y-m-d"))) {
        $errors["published"] = "Publication date cannot be in the future";
    }

    if (!array_filter($errors)) {
        $bookObj = new Book();
        $bookObj->title = $books["title"];
        $bookObj->author = $books["author"];
        $bookObj->genre = $books["genre"];
        $bookObj->published = $books["published"];

        if ($bookObj->addBooks()) {
            header("Location: viewBook.php");
            exit();
        } else {
            echo "<p style='color:red'>Error: Failed to save book.</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Book</title>
    <style>
         body { font-family: Arial, sans-serif; margin: 20px; }
    label { display: block; margin-top: 10px; }
    input, select { margin-top: 5px; padding: 5px; width: 250px; }
    .errors { color: red; font-size: 14px; margin: 0; }
    span { color: red; }
    </style>
</head>
<body>
    <h1>Add Book</h1>
    <label>Fields with <span>*</span> are required</label>
    <form action="" method="post">
        <label>Title <span>*</span></label>
        <input type="text" name="title" value="<?= $books["title"] ?>">
        <p class="error"><?= $errors["title"] ?></p>

        <label>Author <span>*</span></label>
        <input type="text" name="author" value="<?= $books["author"] ?>">
        <p class="error"><?= $errors["author"] ?></p>

        <label>Genre <span>*</span></label>
        <select name="genre">
            <option value="">--SELECT--</option>
            <option value="history" <?= ($books["genre"] == "history") ? "selected": "" ?>>History</option>
            <option value="science" <?= ($books["genre"] == "science") ? "selected": "" ?>>Science</option>
            <option value="fiction" <?= ($books["genre"] == "fiction") ? "selected": "" ?>>Fiction</option>
        </select>
        <p class="error"><?= $errors["genre"] ?></p>

        <label>Date Published <span>*</span></label>
        <input type="date" name="published" value="<?= $books["published"] ?>">
        <p class="error"><?= $errors["published"] ?></p>

        <br><br>
        <input type="submit" value="Save Book">
    </form>
</body>
</html>
