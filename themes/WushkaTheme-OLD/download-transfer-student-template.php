<?php

    header('Content-Type: application/download');
    header('Content-Disposition: attachment; filename="transfer_student-template.csv"');
    header("Content-Length: " . filesize("transfer_student-template.csv"));

    $fp = fopen("transfer_student-template.csv", "r");
    fpassthru($fp);
    fclose($fp);
?>