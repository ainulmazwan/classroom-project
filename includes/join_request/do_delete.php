<?php 
    $database = connectToDB();

    $join_request_id = $_POST["join_request_id"];
    $class_id = $_POST["class_id"];

    // delete join request with id
    $sql = "DELETE FROM join_request WHERE id = :id";
    $query = $database->prepare( $sql );
    $query->execute([
        "id" => $join_request_id
    ]);

    // redirect to classroom
    header("location: /classroom?id=" . $class_id);
    exit;
?>