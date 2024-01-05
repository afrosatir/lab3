<?php
header("content-type:application/json");
include("../php/db.php");

// GET запросы
if ($_SERVER['REQUEST_METHOD'] === "GET") {
    $query = "SELECT * FROM items";
    $statement = $link->prepare($query);
    $statement->execute();
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($result);
}

// update запросы
elseif ($_SERVER['REQUEST_METHOD'] === "PUT") {
    $inputJSON = file_get_contents('php://input', true);
    $input = json_decode($inputJSON, true);

    $id = $input['id'];
    $value = $input['value'];

    if (!empty($value)) {
        $query = "UPDATE items SET value = :value WHERE id = :id";
        $statement = $link->prepare($query);
        $statement->bindParam(':id', $id, PDO::PARAM_INT);
        $statement->bindParam(':value', $value, PDO::PARAM_STR);

        if ($statement->execute()) {
            echo json_encode(["message" => "Элемент успешно обновлен"]);
        } else {
            echo json_encode(["error" => "Не удалось обновить элемент"]);
        }
    }
}

// POST запросы
elseif ($_SERVER['REQUEST_METHOD'] === "POST") {
    $inputJSON = file_get_contents('php://input', true);
    $input = json_decode($inputJSON, true);

    $value = $input['value'];

    if (!empty($value)) {
        $query = "INSERT INTO items (value) VALUES (:value)";
        $statement = $link->prepare($query);
        $statement->bindParam(':value', $value, PDO::PARAM_STR);

        if ($statement->execute()) {
            echo json_encode(["message" => "Элемент успешно добавлен в базу данных"]);
        } else {
            echo json_encode(["error" => "Не удалось добавить элемент в базу данных"]);
        }
    }
}

// DELETE запросы
elseif ($_SERVER['REQUEST_METHOD'] === "DELETE") {
    $inputJSON = file_get_contents('php://input', true);
    $input = json_decode($inputJSON, true);

    $id = $input['id'];

    $query = "DELETE FROM items WHERE id = :id";
    $statement = $link->prepare($query);
    $statement->bindParam(':id', $id, PDO::PARAM_INT);

    if ($statement->execute()) {
        echo json_encode(["message" => "Элемент успешно удален"]);
    } else {
        echo json_encode(["error" => "Не удалось удалить элемент"]);
    }
}
?>
