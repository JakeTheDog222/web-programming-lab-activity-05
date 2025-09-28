<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once "../classes/book.php";
$bookObj = new Book();

$search = $genre = "";

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $search = isset($_GET["search"]) ? trim(htmlspecialchars($_GET["search"])) : "";
    $genre = isset($_GET["genre"]) ? trim(htmlspecialchars($_GET["genre"])) : "";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>View Books</title>
<style>
    table { border-collapse: collapse; width: 100%; margin-top: 20px; }
    table, th, td { border: 1px solid black; }
    th, td { padding: 8px; text-align: left; }
    a { margin-right: 10px; }
</style>
</head>
<body>
<h1>Books</h1>

<form action="" method="get">
    <label for="search">Search:</label>
    <input type="search" name="search" id="search" value="<?= htmlspecialchars($search) ?>">

    <label for="genre">Genre:</label>
    <select name="genre" id="genre">
        <option value="">All Genres</option>
        <option value="history" <?= ($genre == "history") ? "selected" : "" ?>>History</option>
        <option value="science" <?= ($genre == "science") ? "selected" : "" ?>>Science</option>
        <option value="fiction" <?= ($genre == "fiction") ? "selected" : "" ?>>Fiction</option>
    </select>

    <input type="submit" value="Search">
</form>

<button><a href="addBook.php">Add Book</a></button>

<table>
    <tr>
        <th>No.</th>
        <th>Title</th>
        <th>Author</th>
        <th>Genre</th>
        <th>Date Published</th>
        <th>Action</th>
    </tr>

    <?php
    $books = $bookObj->viewBook($search, $genre);
    $no = 1;

    if ($books) {
        foreach ($books as $book) {
            $title_js = addslashes($book['title']);
            ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= htmlspecialchars($book['title']) ?></td>
                <td><?= htmlspecialchars($book['author']) ?></td>
                <td><?= htmlspecialchars($book['genre']) ?></td>
                <td><?= htmlspecialchars($book['published']) ?></td>
                <td>
                    <a href="editBook.php?id=<?= $book['id'] ?>">Edit</a>
                    <a href="deleteBook.php?id=<?= $book['id'] ?>"
                       onclick="return confirm('Are you sure you want to delete the book &quot;<?= $title_js ?>&quot;?')">
                       Delete
                    </a>
                </td>
            </tr>
            <?php
        }
    } else {
        echo "<tr><td colspan='6'>No books found</td></tr>";
    }
    ?>
</table>
</body>
</html>
