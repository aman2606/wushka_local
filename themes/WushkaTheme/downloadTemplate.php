<?php
    header('Content-Type: application/download');
    header('Content-Disposition: attachment; filename="classListTemplate.csv"');
    header("Content-Length: " . filesize("classListTemplate.csv"));

    $fp = fopen("classListTemplate.csv", "r");
    fpassthru($fp);
    fclose($fp);
?>