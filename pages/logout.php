<?php
    // for logged out users
    // start session
    session_start();

    // remove user session
    unset($_SESSION["user"]);

    // redirect back to index.php
    header("Location: /");
?>