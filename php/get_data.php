<?php
include("db.php");

if (isset($_GET["sort"])) {
    $sort = $_GET["sort"];
    if ($sort == "DESC") {
        $query = "SELECT * FROM items ORDER BY id DESC";
        $path = "ASC";
    } else {
        $query = "SELECT * FROM items ORDER BY id ASC";
        $path = "DESC";
    }
} else {
    $query = "SELECT * FROM items";
    $path = "DESC";
}

$statement = $link->prepare($query);
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($result);
?>