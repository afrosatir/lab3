<?php
include("php/db.php");

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
$count = $statement->rowCount();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TODO-лист</title>
    <script src="/lab3/public/js/main.js"></script>
    <link rel="stylesheet" href="/lab3/public/css/bootstrap.css">
</head>
<body>
    <div class="container text-center">
    <button class="btn btn-primary" sort>Сортировать</button>
</div>



    <div class="container text-center">
        <table>
        </table>
    </div>

    <div class="container text-center">
        <div class="border">
            <input task type="text" name="value" />
            <button class="btn btn-primary" create>Добавить</button>
        </div>
    </div>
</body>
</html>
