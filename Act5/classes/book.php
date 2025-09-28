<?php
require_once "database.php"; 

class Book {
    public $id;
    public $title;
    public $author;
    public $genre;
    public $published;

    protected $db;

    public function __construct() {
        $this->db = new Database();
    }

    // Add a new book
    public function addBooks() {
        $sql = "INSERT INTO book (title, author, genre, published) 
                VALUES (:title, :author, :genre, :published)";
        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(":title", $this->title);
        $query->bindParam(":author", $this->author);
        $query->bindParam(":genre", $this->genre);
        $query->bindParam(":published", $this->published);
        return $query->execute();
    }

    // View all books (with optional search & filter)
    public function viewBook($search = "", $genre = "") {
        $sql = "SELECT * FROM book WHERE 1";

        if (!empty($search)) {
            $sql .= " AND (title LIKE :search OR author LIKE :search)";
        }
        if (!empty($genre)) {
            $sql .= " AND genre = :genre";
        }

        $sql .= " ORDER BY title ASC";

        $query = $this->db->connect()->prepare($sql);

        if (!empty($search)) {
            $like = "%" . $search . "%";
            $query->bindParam(":search", $like);
        }
        if (!empty($genre)) {
            $query->bindParam(":genre", $genre);
        }

        if ($query->execute()) {
            return $query->fetchAll();
        } else {
            return [];
        }
    }

   
    public function fetchBook($id) {
    $sql = "SELECT * FROM book WHERE id = :id LIMIT 1";
    $stmt = $this->db->connect()->prepare($sql);
    $stmt->bindParam(":id", $id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}


public function editBook($id) {
    $sql = "UPDATE book 
            SET title = :title, author = :author, genre = :genre, published = :published 
            WHERE id = :id";
    $query = $this->db->connect()->prepare($sql);
    $query->bindParam(":title", $this->title);
    $query->bindParam(":author", $this->author);
    $query->bindParam(":genre", $this->genre);
    $query->bindParam(":published", $this->published);
    $query->bindParam(":id", $id, PDO::PARAM_INT);
    return $query->execute();
}

    
    public function deleteBook($id) {
        $sql = "DELETE FROM book WHERE id = :id";
        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(":id", $id, PDO::PARAM_INT);
        return $query->execute();
    }
}
?>
