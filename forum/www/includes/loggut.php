<?php
    session_start();
    session_destroy();

    header("Location: http://localhost/forum/www/", true, 301);
    exit;
?>