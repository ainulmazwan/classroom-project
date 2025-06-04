<?php 
    $database = connectToDB();

    $join_request_id = $_POST["join_request_id"];
    $class_id = $_POST["class_id"];

    $sql = "DELETE FROM join_request WHERE id = :id";
    $query = $database->prepare( $sql );
    $query->execute([
        "id" => $join_request_id
    ]);
    header("location: /classroom?id=" . $class_id);
    exit;
?>