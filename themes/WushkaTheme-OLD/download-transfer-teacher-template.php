<?php

    header('Content-Type: application/download');
    header('Content-Disposition: attachment; filename="transfer_teacher-template.csv"');
    header("Content-Length: " . filesize("transfer_teacher-template.csv"));

    $fp = fopen("transfer_teacher-template.csv", "r");
    fpassthru($fp);
    fclose($fp);
?>